<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ethereum Trading Bot</title>
    <link rel="stylesheet" href="css/styles.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-dark">
    
    <?php 
    //TODO turn off error reporting for production
    error_reporting(0); 

    //Session start
    require_once(__DIR__  . '/../backend/controllers/account_manager.php');
    //Localization load
    require_once(__DIR__ . '/../backend/controllers/localization.php');
    
    if($logged_in)
        require_once(__DIR__  . '/navigation.php');
    ?>


    <div class="container mt-5 text-light">
        <div class="row justify-content-center">
