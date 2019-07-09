<?php

require_once '../lib/OHTAPI.php';
try {
    com\OHT\API\OHTAPI::config(array(
        'public_key' => '<public key here>',
        'secret_key' => '<secret key here>',
        'sandbox' => true
    ));
    $oht = com\OHT\API\OHTAPI::getInstance();

    $result = $oht->newTranslationProject(
            'rsc-50c5f40edae016-93120380',
            'en-us', //from English
            'fr-fr', //to French
            0, //word count - leave 0 or false to auto-count
            'Please be accurate!', //note to the translator (will not be translated)
            'http://www.example.com/path/to/callback' //callback url
    );

    var_dump($result);
} catch (Exception $e) {
    echo $e;
}
