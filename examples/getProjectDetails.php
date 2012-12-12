<?php

require_once '../lib/OHTAPI.php';
try {
    OHTAPI::config(array(
        'public_key' => OHT_API_PUBLIC_KEY,
        'secret_key' => OHT_API_SECRET_KEY,
        'sandbox' => true
    ));
    $oht = OHTAPI::getInstance();

    $result = $oht->getProjectDetails(34);

    var_dump($result);
} catch (Exception $e) {
    echo $e;
}
