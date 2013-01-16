<?php

require_once '../lib/OHTAPI.php';
try {
    OHTAPI::config(array(
        'public_key' => '<public key here>',
        'secret_key' => '<secret key here>',
        'sandbox' => true
    ));
    $oht = OHTAPI::getInstance();

    $result = $oht->uploadFileResource('../lib/OHTAPI.php', 'OHTAPI');

    var_dump($result);
} catch (Exception $e) {
    echo $e;
}
