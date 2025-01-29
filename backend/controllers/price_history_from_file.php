<?php
ini_set('memory_limit', '1024M');

file_put_contents(__DIR__ . '/evaluate_log.txt', "Loading price data..." . "\n", FILE_APPEND);
$price_data = load_csv_as_generator('../../data/ETHEUR_last_one_year.csv');
$price_extremes = load_csv_as_generator('../../data/ETHEUR_Daily_Extremes.csv');

if ($price_data === null) {
    echo json_encode(['success' => false, 'error' => 'Failed to load CSV data.']);
    exit;
}
file_put_contents(__DIR__ . '/evaluate_log.txt', "Price data loaded!" . "\n", FILE_APPEND);

function get_eth_history_price($timestamp) {
    global $price_data;
    $low = 0;
    $high = count($price_data) - 1;

    $last_valid_price = null;
    $last_valid_timestamp = null;
    $index = null;

    while ($low <= $high) {
        $mid = intval(($low + $high) / 2);
        $parsed = str_getcsv($price_data[$mid]);
        $candle_time = intval($parsed[0]);
        $price = floatval($parsed[1]);

        if ($candle_time <= $timestamp) {
            $last_valid_price = $price;
            $last_valid_timestamp = $candle_time;
            $low = $mid + 1; // Narrow down to later times
            $index = $mid;
        } else {
            $high = $mid - 1; // Narrow down to earlier times
        }
    }

    if ($last_valid_price === null) {
        return ['error' => "No price data available before or at the specified time."];
    }

    return [
        'timestamp' => $last_valid_timestamp,
        'price' => $last_valid_price,
        'index' => $index
    ];
}

function get_eth_price_month_extremes($timestamp) {
    global $price_extremes;
    $min_price = PHP_FLOAT_MAX;
    $max_price = PHP_FLOAT_MIN;
    $candle_time = PHP_INT_MIN;
    for($i = 0; $candle_time < $timestamp + (31 * 24 * 60 * 60); $i++) //month in seconds
    {
        if(!isset($price_extremes[$i])) break;

        $parsed = str_getcsv($price_extremes[$i]);
        $candle_time = intval($parsed[0]);
        if($candle_time >= $timestamp)
        {
            $min_day_price = floatval($parsed[1]);
            $max_day_price = floatval($parsed[2]);
    
            if ($min_day_price < $min_price) {
                $min_price = $min_day_price;
            } else 
            if ($max_day_price > $max_price) {
                $max_price = $max_day_price;
            }
        }
    }

    if($max_price == PHP_FLOAT_MIN || $min_price == PHP_FLOAT_MAX)
    {
        return ['error' => "couldn't find any data"];
    }

    return [
        'min_price' => $min_price,
        'max_price' => $max_price
    ];    
}
?>