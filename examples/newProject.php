<?php

require_once '../lib/OHTAPI.php';
try {
    OHTAPI::config(array(
        'account_id' => 6, //demo user account
        'secret_key' => '7b65907c8fc341bcd558850b71150fd2', //demo user account
        'sandbox' => true //use sandbox
    ));
    $oht = OHTAPI::getInstance();

    $result = $oht->newTranslationProject(
            'en-us', //from English
            'fr-fr', //to French
            'rsc-50c5f40edae016-93120380', 0, //word count - leave 0 or false to auto-count
            'Please be accurate!', //note to the translator (will not be translated)
            'http://www.example.com/path/to/callback' //callback url
    );

    var_dump($result);
} catch (Exception $e) {
    echo $e;
}
