<?php
if(!$_SESSION['lang'])
    $_SESSION['lang'] = 'cs';

$translation = loadTranslations($_SESSION['lang']);

function loadTranslations($lang = 'cs') {
    $file = __DIR__ . "/../../frontend/locales/{$lang}.json";
    if (file_exists($file)) {
        return json_decode(file_get_contents($file), true);
    }
    return [];
}

function __($key) {
    global $translation;
    return $translation[$key] ?? $key;
}

?>