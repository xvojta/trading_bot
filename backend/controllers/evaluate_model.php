<?php
require_once(__DIR__  . '/check_trades.php');
require_once(__DIR__  . '/kraken_api.php');

log_debug("hi!");

$input = json_decode(file_get_contents('php://input'), true);

log_debug("Input: " . file_get_contents('php://input'));

if (isset($input['id']) && isset($input['balance'])) {
    $usd_balance = $input['balance'];
    $usd_actual_wallet = $usd_balance;
    $eth_wallet = 0;
    $buys = 0;
    $sells = 0;
    $graph_data = [];
    $price; // = get_eth_price_binary_search($time, $price_data);

    $time = time() - (365 * 24 * 60 * 60); // current time 365 days in seconds

    for ($i = 0; $i < 365; $i++) {
        $time += (24 * 60 * 60); // moves time by one day
        log_debug("Day: ". $i);

        $trade = check_trades($input['id'], /*$price['price'], */$time, false, false);

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
                    log_debug(json_encode($price));
                    if ($eth_wallet - ($amount / $price) < 0) break;
                    $usd_actual_wallet += $amount;
                    $eth_wallet -= ($amount / $price);
                    $sells ++;
                    break;
            }
            $graph_data[] = ['date' => date("Y-m-d", $time), 'value' => ($usd_actual_wallet + $eth_wallet * $price)];
        }
    }
    //$evaluating_price = get_eth_history_price($time, $price_data)['price']; //TODO what if there is no price for the time
    $final_balance = ($usd_actual_wallet + $eth_wallet * $price);
    $evaluation = $final_balance / $usd_balance * 100; //* 100 to convert to %
    echo json_encode(['success' => true, 'evaluation' => $evaluation, 'usd_wallet' => $usd_actual_wallet, 
    'eth_wallet' => $eth_wallet, 'eval_price' => $price, 'buys' => $buys, 'sells' => $sells, 'graphData' => $graph_data,
    'final_balance' => $final_balance]);
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
?>
