<?php
require_once('kraken_api.php');  // Custom Kraken API wrapper
require_once('/opt/lampp/htdocs/trading_bot/backend/config/database.php');

// Debug log function
function log_debug($message) {
    //file_put_contents(__DIR__ . '/bot_log.txt', date('Y-m-d H:i:s') . " - DEBUG: $message\n", FILE_APPEND);
}


function check_trades(int $model_id, 
float $current_price = null, int $current_time = null, bool $place_order = true, bool $save_trade = true):mixed {
    include '/opt/lampp/htdocs/trading_bot/backend/config/database.php';

    if ($current_price === null) {
        $current_price = get_eth_price();
    }
    if ($current_time === null) {
        $current_time = time();
    }
    // Log the current price and model ID
    log_debug("Checking trades for model ID: $model_id at price: $current_price");

    // Get current price from Kraken API
    $prices = getEthMinMaxPriceLastMonth($current_price);
    $min = $prices['min_price'];
    $max = $prices['max_price'];

    // Fetch user-specified thresholds from DB (e.g., buy dip, sell target)
    $settings = $pdo->query("SELECT * FROM trade_settings WHERE `id` = " . $model_id)->fetch(PDO::FETCH_ASSOC);
    
    if (!$settings) {
        log_debug("No settings found for model ID: $model_id");
        return null;
    }
    
    $buy_threshold = $min * (1 + ($settings['dip'] / 100));
    $sell_threshold = $max * (1 - ($settings['sell'] / 100));

    // Check for Buy Condition
    if ($current_price <= $buy_threshold) {
        try {
            if($place_order) place_order('ETHUSD', 'buy', $current_price, $settings['amount']);
            if($save_trade) save_trade('buy', $current_price, $settings['amount']);
            log_debug("Buy order placed for $current_price with amount: {$settings['amount']}");
            return ['command' => 'buy','price' => $current_price,'amount' => $settings['amount']];
        } catch (\Throwable $th) {
            log_debug("Error placing buy order: " . $th->getMessage());
        }
    }

    // Check for Sell Condition
    if ($current_price >= $sell_threshold) {
        try {
            if($place_order) place_order('ETHUSD', 'sell', $current_price, $settings['amount']);
            if($save_trade) save_trade('sell', $current_price, $settings['amount']);
            return ['command' => 'sell','price' => $current_price,'amount' => $settings['amount']];
        } catch (\Throwable $th) {
            log_debug("Error placing sell order: " . $th->getMessage());
        }
    }
    return null;
}

function save_trade($action, $price, $amount) {
    require_once '/opt/lampp/htdocs/trading_bot/backend/config/database.php';

    $stmt = $pdo->prepare('INSERT INTO trade_history (action, price, amount) VALUES (?, ?, ?)');
    try {
        $stmt->execute([$action, $price, $amount]);
    } catch (\Throwable $th) {
        log_debug("Error saving trade: " . $th->getMessage());
    }
}
?>
