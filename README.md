# OneHourTranslation API Library for PHP #

## Introduction ##

One Hour Translation&trade; provides translation, proofreading and transcription services worldwide.
The following API library allows customers to submit and monitor translation and proofreading jobs automatically and remotely.

## Preconditions and Dependencies  ##

#### Authentication ####
1. Register as a customer on [onehourtranslation.com](http://www.onehourtranslation.com).
2. Request your API Keys [here](http://www.onehourtranslation.com/profile/apiKeys).

#### Dependencies ####
1. PHP >5.0
2. PHP [cURL](http://www.php.net/manual/en/book.curl.php) Library

## Starters' Guide ##

#### Configuration ####

The API Library must be configured before calling any API method.

One method of configuration is done on object construction:

    require_once 'path/to/OHTAPI.php';
    $oht = new OHTAPI(array(
    	'account_id',
    	'secret_key',
    	FALSE // or TRUE for using OHT Sandbox environment
    ));

If you plan on using multiple OHT accounts, use the above method.
If you plan on using only one OHT account, you may use the following method instead:

    require_once 'path/to/OHTAPI.php';
    OHTAPI::config(array(
    	'account_id',
    	'secret_key',
    	FALSE // or TRUE for using OHT Sandbox environment
    ));
    $oht = OHTAPI::instance();
    
#### Running Methods ####

Once configure and initialized, you are ready to call API methods.
For example, requesting account details:

    $result = $oht->getAccountDetails();
    var_dump($result);

On success, you may expect receiving a stdClass object populated with the relevant results.

#### Complete Example ####

    require_once 'path/to/OHTAPI.php';
    try {
	    OHTAPI::config(array(
	    	'account_id',
	    	'secret_key',
	    	FALSE // or TRUE for using OHT Sandbox environment
	    ));
	    $oht = OHTAPI::instance();
	    $result = $oht->getAccountDetails();
	    var_dump($result);
    }catch(Exception $e){
		echo $e;
	}
	
#### Where to go from here? ####
1. The easiest way to learn about features and implementation is to review and run the php examples in the "examples" folder of this library.
2. Use the api-console.php file in the examples folder to manually invoke API requests (don't forget to put this file under your web root folder).
3. Read the complete [API documentation](http://www.onehourtranslation.com/resources/remote-api/). 
