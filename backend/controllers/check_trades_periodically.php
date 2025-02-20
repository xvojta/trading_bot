<?php

require_once(__DIR__  . '/check_trades.php');
require_once(__DIR__  . '/../config/database.php');
$status_file = "bot_running.status";
$not_terminated = true;

if(file_exists($status_file)) 
    $not_terminated = false;
else
    fopen($status_file, "w");

// Continuous loop
while ($not_terminated) {
    // Log the iteration start time
    file_put_contents(__DIR__ . '/bot_log.txt', "Iteration started at: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

    // Fetch trade settings
    try {
        file_put_contents(__DIR__ . '/bot_log.txt', "Going to try to fetch at: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        $query = "SELECT id FROM trade_settings WHERE `running` = 1";
        file_put_contents(__DIR__ . '/bot_log.txt', "Executing query: $query\n", FILE_APPEND);
        $ids = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
        file_put_contents(__DIR__ . '/bot_log.txt', "Fetched at: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        file_put_contents(__DIR__ . '/bot_log.txt', "Number of trade settings fetched: " . count($ids) . "\n", FILE_APPEND);
    } catch (Exception $e) {
        file_put_contents(__DIR__ . '/bot_log.txt', "Error fetching trade settings: " . $e->getMessage() . "\n", FILE_APPEND);
        continue; // Skip to the next iteration if fetching fails
    }

    // Check if any trade settings are found
    if ($ids) {
        foreach ($ids as $setting) {
            $id = $setting['id'];
            // Log which trade setting is being processed
            file_put_contents(__DIR__ . '/bot_log.txt', "Processing trade setting ID: $id\n", FILE_APPEND);
            try {
                check_trades(get_eth_price(), $id);
            } catch (Exception $e) {
                file_put_contents(__DIR__ . '/bot_log.txt', "Error processing trade setting ID: $id. Error: " . $e->getMessage() . "\n", FILE_APPEND);
            }
        }
    } else {
        // Log if no trade settings are running
        file_put_contents(__DIR__ . '/bot_log.txt', "No running trade settings found.\n", FILE_APPEND);
    }

    // Log the end of the iteration
    file_put_contents(__DIR__ . '/bot_log.txt', "Iteration ended at: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

    // Sleep for 10 seconds before the next iteration
    sleep(10);
}
?>
