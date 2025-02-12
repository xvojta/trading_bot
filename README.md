# Setup
1. Install wamp (https://wampserver.aviatechno.net/)
2. Copy files from this repo to www directory
3. To root directory of the project (www/trading_bot) extract data folder from ([download from onedrive](https://gymtisnov-my.sharepoint.com/:u:/g/personal/vojtech_ondracek_gym-tisnov_cz/EdWz7-Sb1g9DoQZlDFpHmP4BXIsiD0igZvceHdJQ-N2q2Q?e=aZYw41))
4. Run backend/db/create_database.sql script on your MySQL database
5. Fill in your database credentials in backend/config/database.php
6. Run the project at "http://localhost/trading_bot/frontend/"


# Trading Bot Platform

## Overview
This platform allows users to create and evaluate trading models based on historical data from the last year. You can sign up, create models, customize their settings, and test them against real past market conditions.

## How It Works
- **Create an Account** – Register and log in to access the model creation features.
- **Create Model** – Define your strategy by setting specific parameters.
- **Evaluate Model** – Simulate your strategy on historical market data to see how it would have performed.

## Model Settings
- **Volume per trade (in USD)**: The amount of money used for a single transaction (buy/sell).
- **Buy Dip Percentage**: The minimum percentage above the monthly low that the current price must be for a purchase to be made.
- **Sell Target Percentage**: The minimum percentage below the monthly high that the current price must be for a sale to be made.

## Evaluation Process
The system calculates the minimum and maximum over the last 31 days each day and evaluates whether the ETH price matches the defined thresholds for buying or selling. If the conditions are met, the transaction is automatically executed within the simulation. This allows users to test their trading strategies before deploying them on the real market.

## Possible Future Project Expansion
This project is ready for deployment in real trading via the Kraken API, but this functionality is not part of the Maturita project:

- Using a regularly running PHP script, the ETH prices of currently running models will be checked daily (option to enable/disable in the tab **[Models](models.php)**).
- If the set thresholds are met, real buy/sell transactions will be executed according to the model settings on your Kraken platform account.
- This will allow tested trading strategies to be implemented on the real market.
- Executed transactions will then be saved in the database and can be viewed in the tab **[Trade History](trade_history.php)**.
