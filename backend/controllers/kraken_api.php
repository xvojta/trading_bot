<?php
// kraken_api.php

// Your Kraken API credentials
define('KRAKEN_API_KEY', 'your_api_key'); // Replace with your actual API key
define('KRAKEN_API_SECRET', 'your_api_secret'); // Replace with your actual API secret

function get_eth_price() {
    $url = 'https://api.kraken.com/0/public/Ticker?pair=ETHUSD';

    // Initialize cURL session
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL session
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response
    $data = json_decode($response, true);

    error_log("eth price data: " . $data);
    // Check if the response is valid and extract the ETH price
    if (isset($data['result']['XETHZUSD']['c'][0])) {
        return (float)$data['result']['XETHZUSD']['c'][0];
    } else {
        throw new Exception("Failed to fetch ETH price from Kraken API.");
    }
}

function getEthMinMaxPriceLastMonth(int $current_time) {
    error_log("Get eth min max price log");
    $url = "https://api.kraken.com/0/public/OHLC";
    $pair = "ETHUSD";  // Ethereum to USD
    $interval = 1440;  // Daily candles (1440 minutes)
    $since = $current_time - (30 * 24 * 60 * 60);  // 30 days ago in seconds (Unix timestamp)

    $query_params = http_build_query([
        'pair' => $pair,
        'interval' => $interval,
        'since' => $since
    ]);

    $request_url = "$url?$query_params";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $request_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    error_log("EthMinMax data: " . $data);
    if (!isset($data))
    {
        return ['error' => "Error fetching data"];
    }
    if (isset($data['error']) && !empty($data['error'])) {
        return ['error' => "Error fetching data: " . implode(", ", $data['error'])];
    }

    $eth_data = $data['result']['XETHZUSD'];

    $min_price = PHP_FLOAT_MAX;
    $max_price = PHP_FLOAT_MIN;

    foreach ($eth_data as $day) {
        $low_price = floatval($day[3]);
        $high_price = floatval($day[2]);

        if ($low_price < $min_price) {
            $min_price = $low_price;
        }
        if ($high_price > $max_price) {
            $max_price = $high_price;
        }
    }

    return [
        'min_price' => $min_price,
        'max_price' => $max_price
    ];
}

function place_order($pair, $type, $price, $amount) {
    $url = 'https://api.kraken.com/0/private/AddOrder';

    $data = [
        'pair' => $pair,
        'type' => $type,
        'ordertype' => 'limit',
        'price' => $price,
        'volume' => $amount,
    ];

    $response = "true";

    return $response;
}

function kraken_api_request($url, $data) {
    $data['nonce'] = time();
    $post_data = http_build_query($data);

    $path = parse_url($url, PHP_URL_PATH);
    $secret = base64_decode(KRAKEN_API_SECRET);
    $nonce = $data['nonce'];

    $api_sign = hash_hmac('sha512', $nonce . $post_data, $secret);

    $headers = [
        'API-Key: ' . KRAKEN_API_KEY,
        'API-Sign: ' . base64_encode($api_sign),
        'Content-Type: application/x-www-form-urlencoded'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
?>
