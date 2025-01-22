<?php
require_once 'check_trades.php';
require_once('kraken_api.php');

file_put_contents(__DIR__ . '/evaluate_log.txt', "Hi " . "\n", FILE_APPEND);

$input = json_decode(file_get_contents('php://input'), true);

ini_set('memory_limit', '1024M');

file_put_contents(__DIR__ . '/evaluate_log.txt', "Input: " . file_get_contents('php://input') . "\n", FILE_APPEND);

if (isset($input['id']) && isset($input['balance'])) {
    $usd_balance = $input['balance'];
    $usd_actual_wallet = $usd_balance;
    $eth_wallet = 0;
    $buys = 0;
    $sells = 0;

    file_put_contents(__DIR__ . '/evaluate_log.txt', "Loading price data..." . "\n", FILE_APPEND);
    $price_data = load_csv_as_generator('../../data/ETHEUR_last_one_year.csv');

    if ($price_data === null) {
        echo json_encode(['success' => false, 'error' => 'Failed to load CSV data.']);
        exit;
    }
    file_put_contents(__DIR__ . '/evaluate_log.txt', "Price data loaded!" . "\n", FILE_APPEND);

    $time = time() - (365 * 24 * 60 * 60); // current time 365 days in seconds

    for ($i = 0; $i < 365; $i++) {
        $time += (24 * 60 * 60); // moves time by one day
        $price = get_eth_price_binary_search($time, $price_data);

        if (!isset($price['price'])) continue;

        $trade = check_trades($input['id'], $price['price'], $time, false, false);

        if ($trade) {
            $command = $trade['command'];
            $price = $trade['price'];
            $amount = $trade['amount'];

            switch ($command) {
                case 'buy':
                    if ($usd_actual_wallet - $amount < 0) break;
                    $usd_actual_wallet -= $amount;
                    $eth_wallet += $amount / $price;
                    $buys ++;
                    break;
                case 'sell':
                    if ($eth_wallet - ($amount / $price) < 0) break;
                    $usd_actual_wallet += $amount;
                    $eth_wallet -= ($amount / $price);
                    $sells ++;
                    break;
            }

        }
    }
    $evaluating_price = get_eth_price_binary_search($time, $price_data)['price']; //TODO what if there is no price for the time
    $evaluation = (($usd_actual_wallet + $eth_wallet * $evaluating_price) / $usd_balance) * 100 . "%"; //* 100 to convert to %
    echo json_encode(['success' => true, 'evaluation' => $evaluation, 'usd_wallet' => $usd_actual_wallet, 
    'eth_wallet' => $eth_wallet, 'eval_price' => $evaluating_price, 'buys' => $buys, 'sells' => $sells]);
}

function load_csv_as_generator($csv_file_path) {
    if (!file_exists($csv_file_path)) {
        return null;
    }

    $lines = file($csv_file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$lines) {
        return null;
    }

    return $lines; // Return the file handle as the generator source
}

function get_eth_price_binary_search($timestamp, $lines) {
    $low = 0;
    $high = count($lines) - 1;

    $last_valid_price = null;
    $last_valid_timestamp = null;

    while ($low <= $high) {
        $mid = intval(($low + $high) / 2);
        $parsed = str_getcsv($lines[$mid]);
        $candle_time = intval($parsed[0]);
        $price = floatval($parsed[1]);

        if ($candle_time <= $timestamp) {
            $last_valid_price = $price;
            $last_valid_timestamp = $candle_time;
            $low = $mid + 1; // Narrow down to later times
        } else {
            $high = $mid - 1; // Narrow down to earlier times
        }
    }

    if ($last_valid_price === null) {
        return ['error' => "No price data available before or at the specified time."];
    }

    return [
        'timestamp' => $last_valid_timestamp,
        'price' => $last_valid_price
    ];
}
?>
