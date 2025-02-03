<?php
require_once(__DIR__ . '/account_manager.php');
require_once(__DIR__ . '/localization.php');

$lang = $_SESSION['lang'] ?? 'cs';
$translations = loadTranslations($lang);

header('Content-Type: application/json');
echo json_encode($translations);
?>
