# OneHourTranslation API Library for PHP #

## Introduction 

One Hour Translation&trade; provides translation, proofreading and transcription services worldwide.
The following API library allows customers to submit and monitor jobs automatically and remotely.

## Preconditions and Dependencies  

#### Authentication 
1. Register as a customer on [One Hour Translation](http://www.onehourtranslation.com/auth/register).
2. Request your API Keys [here](http://www.onehourtranslation.com/profile/apiKeys).

#### Dependencies
1. PHP >5.3
2. PHP [cURL](http://www.php.net/manual/en/book.curl.php) Library

#### Installation - Method 1:
Download this library to your project's 3rd party libraries path:

    git clone https://github.com/OneHourTranslation/API-2-Client-PHP /your-project/vendor/OHT

And include the library in your project:
    
    <?php
    require_once 'path/to/OHTAPI.php';
    
#### Installation - Method 2:
Install with Composer:

    "require" : {
    	"onehourtranslation/api2" : ">=1.0"
	} 
    
    
## Starters' Guide ##

#### Configuration ####

The API Library must be instantiated and configured before calling any API method.

One method of configuration is done on object instantiation:

    require_once 'path/to/OHTAPI.php';
    $oht = new OHTAPI(array(
        'public_key',
    	'secret_key',
    	FALSE // or TRUE for using OHT Sandbox environment
    ));

If you plan on using multiple OHT accounts, use the above method.
If you plan on using only one OHT account, you may use the following method instead:

    require_once 'path/to/OHTAPI.php';
    OHTAPI::config(array(
    	'public_key',
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
	    	'public_key',
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

