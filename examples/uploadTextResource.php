<?php

require_once '../lib/OHTAPI.php';
try {
    OHTAPI::config(array(
        'account_id' => 6, //demo user account
        'secret_key' => '7b65907c8fc341bcd558850b71150fd2', //demo user account
        'sandbox' => true //use sandbox
    ));
    $oht = OHTAPI::getInstance();

    $result = $oht->uploadTextResource('Hello world');

    var_dump($result);
} catch (Exception $e) {
    echo $e;
}
