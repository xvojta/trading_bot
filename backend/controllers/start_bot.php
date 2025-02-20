<?php
// start_bot.php

// require_once the database connection
require_once(__DIR__  . '/../config/database.php');
require_once(__DIR__  . '/account_manager.php');

// Get the JSON payload from the request
$input = json_decode(file_get_contents('php://input'), true);

// Validate and sanitize input data
if (isset($input['name'], $input['dip'], $input['sell'], $input['amount'])) {
    $buy_dip = filter_var($input['dip'], FILTER_VALIDATE_FLOAT);
    $sell_target = filter_var($input['sell'], FILTER_VALIDATE_FLOAT);
    $usd_amount = filter_var($input['amount'], FILTER_VALIDATE_FLOAT);
    $model_name = $input['name'];

    // Ensure all inputs are valid numbers
    if ($buy_dip === false || $sell_target === false || $usd_amount === false) {
        echo json_encode(['success' => false, 'message' => 'Invalid input values.', 0]);
        exit;
    }

    // Insert the trade settings into the database
    try {
        $stmt = $pdo->prepare('INSERT INTO trade_settings (`name`, owner, dip, sell, amount) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$model_name, $uid, $buy_dip, $sell_target, $usd_amount]);

        // Successfully saved settings
        echo json_encode(['success' => true, 'message' => 'Trading bot started with your settings.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to save trade settings: ' . $e->getMessage(), 0]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing input values.', 0]);
}
?>
