<?php
require_once('kraken_api.php');  // Custom Kraken API wrapper
require_once('../config/database.php');
require_once('price_history_from_file.php');

// Debug log function
function log_debug($message) {
    file_put_contents(__DIR__ . '/evaluate_log.txt', date('Y-m-d H:i:s') . " - DEBUG: $message\n", FILE_APPEND);
}


function check_trades(int $model_id, int $current_time = null, bool $place_order = true, bool $save_trade = true) {
    $startTime = microtime(true);
    include '../config/database.php';
    $checkFromHistory = true;

    $current_price;
    $prices;

    if ($current_time === null) {
        $current_time = time();
        $checkFromHistory = false;
        $current_price = get_eth_price();
        // Get current price from Kraken API
        $prices = getEthMinMaxPriceLastMonth($current_time);
    } else {
        $current_price = get_eth_history_price($current_time)['price'];
        log_debug("Got current price: " . floor((microtime(true)-$startTime) * 1000));
        $prices = get_eth_price_month_extremes($current_time - (31 * 24 * 60 * 60)); //curent time - one month
        log_debug("Got prices: " . floor((microtime(true)-$startTime) * 1000));
        if(!$current_price || isset($prices['error'])) {
            log_debug("Error searching for price in check_trades.php on line 25");
            return null;
        }
    }

    if(isset($prices['error'])) return null;
    $min = $prices['min_price'];
    $max = $prices['max_price'];
    log_debug("Min price: " . $min . ", Max price: " . $max);

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
            log_debug("Sell order placed for $current_price with amount: {$settings['amount']}");
            return ['command' => 'sell','price' => $current_price,'amount' => $settings['amount']];
        } catch (\Throwable $th) {
            log_debug("Error placing sell order: " . $th->getMessage());
        }
    }
    return null;
}

function save_trade($action, $price, $amount) {
    require_once '../config/database.php';

    $stmt = $pdo->prepare('INSERT INTO trade_history (action, price, amount) VALUES (?, ?, ?)');
    try {
        $stmt->execute([$action, $price, $amount]);
    } catch (\Throwable $th) {
        log_debug("Error saving trade: " . $th->getMessage());
    }
}
?>
