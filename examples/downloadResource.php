<?php

require_once '../lib/OHTAPI.php';
try {
    com\OHT\API\OHTAPI::config(array(
        'public_key' => '<public key here>',
        'secret_key' => '<secret key here>',
        'sandbox' => true
    ));
    $oht = com\OHT\API\OHTAPI::getInstance();

    $result = $oht->downloadResource('rsc-50c8891e2ee932-49177670', 'demo.php');

    var_dump($result);
} catch (Exception $e) {
    echo $e;
}
