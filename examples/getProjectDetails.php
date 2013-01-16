<?php

require_once '../lib/OHTAPI.php';
try {
    OHTAPI::config(array(
        'public_key' => '<public key here>',
        'secret_key' => '<secret key here>',
        'sandbox' => true
    ));
    $oht = OHTAPI::getInstance();

    $result = $oht->getProjectDetails(34);

    var_dump($result);
} catch (Exception $e) {
    echo $e;
}
