<?php

require_once '../lib/OHTAPI.php';
try {
    com\OHT\API\OHTAPI::config(array(
        'public_key' => '<public key here>',
        'secret_key' => '<secret key here>',
        'sandbox' => true
    ));
    $oht = com\OHT\API\OHTAPI::getInstance();

    $result = $oht->downloadResource('rsc-51064c3792c569-42783007', 'demo.php', 47);

    var_dump($result);
} catch (Exception $e) {
    echo $e;
}
