<?php

require_once '../lib/OHTAPI.php';
try {
    OHTAPI::config(array(
        'public_key' => OHT_API_PUBLIC_KEY,
        'secret_key' => OHT_API_SECRET_KEY,
        'sandbox' => true
    ));
    $oht = OHTAPI::getInstance();

    $result = $oht->uploadFileResource('../lib/OHTAPI.php', 'OHTAPI');

    var_dump($result);
} catch (Exception $e) {
    echo $e;
}
