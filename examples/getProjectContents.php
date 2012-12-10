<?php
	require_once '../lib/OHTAPI.php';
	try{
		OHTAPI::config(array(
			'account_id'=> 269 , //demo user account
			'secret_key' => '5a68561984276108fa42d7cffbbf91a5' , //demo user account
			'sandbox' => true //use sandbox
		));
		$oht = OHTAPI::instance();
		
		$result = $oht->getProjectContents(1418);
		
		var_dump($result);
	}catch(Exception $e){
		echo $e;
	}
	
?>