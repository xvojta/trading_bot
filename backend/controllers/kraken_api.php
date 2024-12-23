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

    // Check if the response is valid and extract the ETH price
    if (isset($data['result']['XETHZUSD']['c'][0])) {
        return (float)$data['result']['XETHZUSD']['c'][0];
    } else {
        throw new Exception("Failed to fetch ETH price from Kraken API.");
    }
}

function getEthMinMaxPriceLastMonth(int $current_time) {
    // Step 1: Define the API endpoint and parameters
    $url = "https://api.kraken.com/0/public/OHLC";
    $pair = "ETHUSD";  // Ethereum to USD
    $interval = 1440;  // Daily candles (1440 minutes)
    $since = $current_time - (30 * 24 * 60 * 60);  // 30 days ago in seconds (Unix timestamp)

    // Step 2: Prepare the request URL
    $query_params = http_build_query([
        'pair' => $pair,
        'interval' => $interval,
        'since' => $since
    ]);

    $request_url = "$url?$query_params";

    // Step 3: Make the API request using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $request_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    // Step 4: Decode the JSON response
    $data = json_decode($response, true);

    // Check for errors
    if (isset($data['error']) && !empty($data['error'])) {
        return ['error' => "Error fetching data: " . implode(", ", $data['error'])];
    }

    // Step 5: Extract OHLC data for ETHUSD
    $eth_data = $data['result']['XETHZUSD'];  // Kraken uses 'XETHZUSD' for ETH-USD

    // Step 6: Loop through the data to find min and max prices
    $min_price = PHP_FLOAT_MAX;  // Set initial min to a very high value
    $max_price = PHP_FLOAT_MIN;  // Set initial max to a very low value

    foreach ($eth_data as $day) {
        $low_price = floatval($day[3]);  // Low price is at index 3
        $high_price = floatval($day[2]); // High price is at index 2

        // Update min and max
        if ($low_price < $min_price) {
            $min_price = $low_price;
        }
        if ($high_price > $max_price) {
            $max_price = $high_price;
        }
    }

    // Step 7: Return the results as an associative array
    return [
        'min_price' => $min_price,
        'max_price' => $max_price
    ];
}

function get_eth_price_at_time($timestamp) {
    // Define the API endpoint and parameters
    $url = "https://api.kraken.com/0/public/OHLC";
    $pair = "ETHUSD";  // Ethereum to USD
    $interval = 1;     // 1-minute candles

    // Prepare the request URL
    $query_params = http_build_query([
        'pair' => $pair,
        'interval' => $interval,
        'since' => $timestamp
    ]);

    $request_url = "$url?$query_params";

    // Make the API request using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $request_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response
    $data = json_decode($response, true);

    // Check for errors
    if (isset($data['error']) && !empty($data['error'])) {
        return ['error' => "Error fetching data: " . implode(", ", $data['error'])];
    }

    // Extract OHLC data for ETHUSD
    $eth_data = $data['result']['XETHZUSD'];  // Kraken uses 'XETHZUSD' for ETH-USD

    // Iterate through the OHLC data to find the last valid price before or at the given timestamp
    $last_valid_price = null;
    $last_valid_timestamp = null;

    foreach ($eth_data as $candle) {
        $candle_time = intval($candle[0]); // Candle time in Unix timestamp
        if ($candle_time <= $timestamp) {
            $last_valid_price = floatval($candle[4]); // Closing price is at index 4
            $last_valid_timestamp = $candle_time;
        } else {
            // Exit the loop once we move past the specified time
            break;
        }
    }

    // If no valid price was found, return an error
    if ($last_valid_price === null) {
        return ['error' => "No price data available before or at the specified time."];
    }

    // Return the last valid price and timestamp
    return [
        'timestamp' => $last_valid_timestamp,
        'close_price' => $last_valid_price
    ];
}

function place_order($pair, $type, $price, $amount) {
    $url = 'https://api.kraken.com/0/private/AddOrder';

    $data = [
        'pair' => $pair,
        'type' => $type,
        'ordertype' => 'limit', // Can be 'market' or 'limit'
        'price' => $price,
        'volume' => $amount,
    ];

    /*$response = kraken_api_request($url, $data);

    if (isset($response['error']) && count($response['error']) > 0) {
        throw new Exception("Error placing order: " . implode(", ", $response['error']));
    }*/ //this code sends the order
    $response = "true";

    return $response;
}

function kraken_api_request($url, $data) {
    // Prepare the API request
    $data['nonce'] = time(); // Nonce must be unique for each request
    $post_data = http_build_query($data);

    // Create the API signature
    $path = parse_url($url, PHP_URL_PATH);
    $secret = base64_decode(KRAKEN_API_SECRET);
    $nonce = $data['nonce'];

    $api_sign = hash_hmac('sha512', $nonce . $post_data, $secret);

    // Prepare headers
    $headers = [
        'API-Key: ' . KRAKEN_API_KEY,
        'API-Sign: ' . base64_encode($api_sign),
        'Content-Type: application/x-www-form-urlencoded'
    ];

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    // Execute the request
    $response = curl_exec($ch);
    curl_close($ch);

    // Return decoded response
    return json_decode($response, true);
}
?>
