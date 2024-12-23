<?php
require_once 'check_trades.php';
require_once('kraken_api.php');

file_put_contents(__DIR__ . '/bot_log.txt', "Hi " . "\n", FILE_APPEND);

$input = json_decode(file_get_contents('php://input'), true);

if(isset($input['id']))
{
    $usd_wallet = 100000;
    $eth_wallet = 0;
    for($i = 0; $i < 365; $i++)
    {
        $time = time() - (365-$i) * (24 * 60 * 60); //current time - (365-i) days in seconds
        if (isset($price_at_time['error'])) {
            echo $price_at_time['error'];
        }
        $price = get_eth_price_at_time($time);
        file_put_contents(__DIR__ . '/bot_log.txt', "Price evaluation: " . json_encode($price) . "\n", FILE_APPEND);
        if(!isset($price['close_price'])) continue;
        $trade = check_trades($input['id'], $price['close_price'], $time, false, false);

        if($trade) {
            $command = $trade['command'];
            $price = $trade['price'];
            $amount = $trade['amount'];

            switch($command) {
                case 'buy':
                    if($usd_wallet-$amount < 0) break;
                    $usd_wallet -= $amount;
                    $eth_wallet += $amount / $price;
                    break;
                case 'sell':
                    if($eth_wallet-$amount < 0) break;
                    $usd_wallet += $amount;
                    $eth_wallet -= $amount / $price;
                    break;
            }
        }
    }
    $evaluation = (($usd_wallet + $eth_wallet*get_eth_price())/1000)."%";
    echo json_encode(['success' => true, 'evaluation' => $evaluation, 'usd_wallet' => $usd_wallet, 'eth_wallet' => $eth_wallet]);
}
?>