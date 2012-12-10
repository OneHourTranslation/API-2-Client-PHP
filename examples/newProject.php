<?php
	require_once '../lib/OHTAPI.php';
	try{
		OHTAPI::config(array(
			'account_id'=> 269 , //demo user account
			'secret_key' => '5a68561984276108fa42d7cffbbf91a5' , //demo user account
			'sandbox' => true //use sandbox
		));
		$oht = OHTAPI::instance();
		
		$result = $oht->newProject(
			'en-us', //from English
			'fr-fr', //to French
			'A book is a set of printed sheets of paper held together between two covers. 
				The sheets of paper are usually covered with a text: 
				language and illustrations: that is the main point of a printed book. 
				A book can also be a text in a larger collection of texts. 
				This text has some features that do not apply to the collection as a whole. 
				aps written by one author, or it only treats one subject area. 
				Books in this sense can often be understood without knowing the whole collection.',
			0, //word count - leave 0 or false to auto-count
			'Please be accurate!', //note to the translator (will not be translated)
			'http://www.example.com/path/to/callback' //callback url
		);
		
		var_dump($result);
	}catch(Exception $e){
		echo $e;
	}
	
?>