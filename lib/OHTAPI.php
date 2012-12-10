<?php

if (!function_exists('curl_init')) {
    throw new Exception('OneHourTranslation needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
    throw new Exception('OneHourTranslation needs the JSON PHP extension.');
}

define('OHT_API_ACCOUNT_ID', '6'); //demo account
define('OHT_API_SECRET_KEY', '7b65907c8fc341bcd558850b71150fd2'); //demo account
define('OHT_API_SANDBOX', true);

class OHTAPI_Exception extends Exception
{

    protected $httpCode;
    protected $statusCode;
    protected $statusMessage;

    function __construct($httpCode, $statusCode, $statusMessage)
    {
        parent::__construct(sprintf("#%d %s (HTTP %d)", $statusCode, $statusMessage, $httpCode));
        $this->setHttpCode($httpCode);
        $this->setStatusCode($statusCode);
        $this->setStatusMessage($statusMessage);
    }

    /**
     *
     * @return
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     *
     * @param $httpCode
     */
    public function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;
    }

    /**
     *
     * @return
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     *
     * @param $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     *
     * @return
     */
    public function getStatusMessage()
    {
        return $this->statusMessage;
    }

    /**
     *
     * @param $statusMessage
     */
    public function setStatusMessage($statusMessage)
    {
        $this->statusMessage = $statusMessage;
    }

    function __toString()
    {
        return $this->getMessage();
    }

}

class OHTAPI
{

    const VERSION = '2';
    const OHT_PRODUCTION_URL = 'http://max.oht/api/2';
    const OHT_SANDBOX_URL = 'http://max.oht/api/2';

    /**
     * @var integer
     */
    protected static $staticAccountId;

    /**
     * @var string
     */
    protected static $staticSecretKey;

    /**
     * @var boolean
     */
    protected static $staticSandbox;

    /**
     * @var OHTAPI
     */
    protected static $instance = null;

    /**
     * @var integer
     */
    protected $accountId;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var boolean
     */
    protected $sandbox;

    /**
     * Performes preliminary configuration on the OHTAPI class.<br />Make sure to run it before calling OHTAPI::instance() for the first time
     *
     * @param array $conf - contains the following parameters:<br />
     * 	<ol>
     * 	<li>'account_id' - Your OHT account ID</li>
     *  <li>'secret_key' - Your OHT secret API key</li>
     *  <li>'sandbox' - (boolean) Use OHT sandbox</li>
     *  </ol>
     *
     */
    static public function config($conf = array())
    {
        self::$staticAccountId = (empty($conf['account_id'])) ? OHT_API_ACCOUNT_ID : $conf['account_id'];
        self::$staticSecretKey = (empty($conf['secret_key'])) ? OHT_API_SECRET_KEY : $conf['secret_key'];
        self::$staticSandbox = (isset($conf['sandbox'])) ? (bool) $conf['sandbox'] : OHT_API_SANDBOX;
    }

    /**
     * Fetch OHTAPI Instance. Make sure to run this right after running config()
     *
     * @return OHTAPI
     */
    static public function getInstance()
    {
        if (!self::$instance) {
            $className = __CLASS__;
            self::$instance = new $className(self::$staticAccountId, self::$staticSecretKey, self::$staticSandbox);
        }
        return self::$instance;
    }

    /**
     * @param $account_id
     * @param $secret_key
     * @param $sandbox boolean true to use OHT sandbox
     */
    public function __construct($accountId = OHT_API_ACCOUNT_ID, $secretKey = OHT_API_SECRET_KEY, $sandbox = OHT_API_SANDBOX)
    {
        $this->setAccountId($accountId);
        $this->setSecretKey($secretKey);
        $this->setSandbox($sandbox);
    }

    /**
     * Create a new Translation Project
     * @param string $source
     * @param string $target
     * @param string $resources
     * @param integer $word_count (optional)
     * @param string $notes (optional)
     * @param string $callback_url (optional)
     * @param array $params (optional)
     * @return stdClass response object
     */
    public function newTranslationProject($source, $target, $resources, $wordCount = 0, $notes = '', $callbackUrl = '', $params = array())
    {
        $url = "/projects/translation";
        $method = 'post';
        $params['source_lang'] = $source;
        $params['target_lang'] = $target;
        $params['resources'] = $resources;
        $params['word_count'] = $wordCount;
        $params['notes'] = $notes;
        $params['callback_url'] = $callbackUrl;

        return $this->request($url, $method, $params);
    }

    /**
     * Create a new Transcription Project
     * @param string $source
     * @param string $resources
     * @param integer $word_count (optional)
     * @param string $notes (optional)
     * @param string $callback_url (optional)
     * @param array $params (optional)
     * @return stdClass response object
     */
    public function newTranscriptionProject($source, $resources, $wordCount = 0, $notes = '', $callbackUrl = '', $params = array())
    {
        $url = "/projects/transcription";
        $method = 'post';
        $params['source_lang'] = $source;
        $params['target_lang'] = $source;
        $params['resources'] = $resources;
        $params['word_count'] = $wordCount;
        $params['notes'] = $notes;
        $params['callback_url'] = $callbackUrl;

        return $this->request($url, $method, $params);
    }

    /**
     * Create a new Proofreading Project (only source docs)
     * @param string $source
     * @param string $resources
     * @param integer $word_count (optional)
     * @param string $notes (optional)
     * @param string $callback_url (optional)
     * @param array $params (optional)
     * @return stdClass response object
     */
    public function newProofGeneralProject($source, $resources, $wordCount = 0, $notes = '', $callbackUrl = '', $params = array())
    {
        $url = "/projects/proofgeneral";
        $method = 'post';
        $params['source_lang'] = $source;
        $params['target_lang'] = $source;
        $params['resources'] = $resources;
        $params['word_count'] = $wordCount;
        $params['notes'] = $notes;
        $params['callback_url'] = $callbackUrl;

        return $this->request($url, $method, $params);
    }

    /**
     * Create a new Proofreading Project (source and translated docs)
     * @param string $source
     * @param string $target
     * @param string $resources
     * @param string $translations
     * @param integer $word_count (optional)
     * @param string $notes (optional)
     * @param string $callback_url (optional)
     * @param array $params (optional)
     * @return stdClass response object
     */
    public function newProofTranslatedProject($source, $target, $resources, $translations, $wordCount = 0, $notes = '', $callbackUrl = '', $params = array())
    {
        $url = "/projects/prooftranslated";
        $method = 'post';
        $params['source_lang'] = $source;
        $params['target_lang'] = $target;
        $params['resources'] = $resources;
        $params['translations'] = $translations;
        $params['word_count'] = $wordCount;
        $params['notes'] = $notes;
        $params['callback_url'] = $callbackUrl;

        return $this->request($url, $method, $params);
    }

    /**
     * Create a new Translation + Proofreading
     * @param string $source
     * @param string $target
     * @param string $resources
     * @param integer $word_count (optional)
     * @param string $notes (optional)
     * @param string $callback_url (optional)
     * @param array $params (optional)
     * @return stdClass response object
     */
    public function newTranslationProofreadingProject($source, $target, $resources, $wordCount = 0, $notes = '', $callbackUrl = '', $params = array())
    {
        $url = "/projects/transproof";
        $method = 'post';
        $params['source_lang'] = $source;
        $params['target_lang'] = $target;
        $params['resources'] = $resources;
        $params['word_count'] = $wordCount;
        $params['notes'] = $notes;
        $params['callback_url'] = $callbackUrl;

        return $this->request($url, $method, $params);
    }

    /**
     * Cancel project
     * @param integer $projectId
     * @return stdClass response object
     */
    public function cancelProject($projectId)
    {
        $url = "/projects/{$projectId}";
        $method = 'delete';

        return $this->request($url, $method);
    }

    /**
     * Create new comment to project
     * @param integer $projectId
     * @param string $content (optional)
     * @return stdClass response object
     */
    public function newComment($projectId, $content = '')
    {
        $url = "/projects/{$projectId}/comments";
        $method = 'post';
        $params['content'] = $content;

        return $this->request($url, $method, $params);
    }

    /**
     * Fetch comments by project id
     *
     * @param integer $projectId
     * @return stdClass response object
     */
    public function getComments($projectId)
    {
        $url = "/projects/{$projectId}/comments";
        $method = 'get';

        return $this->request($url, $method);
    }

    /**
     * Fetch project details by project id
     *
     * @param integer $projectId
     * @return stdClass response object
     */
    public function getProjectDetails($projectId)
    {
        $url = "/projects/{$projectId}";
        $method = 'get';

        return $this->request($url, $method);
    }

    /**
     * Fetch account details and credits balance
     *
     * @return stdClass response object
     */
    public function getAccountDetails()
    {
        $url = "/accounts/";
        $method = 'get';

        return $this->request($url, $method);
    }

    /**
     * Fetch context list for specified user
     *
     * @return stdClass response object
     */
    public function getContextList()
    {
        $url = "/tm/context";
        $method = 'get';

        return $this->request($url, $method);
    }

    /**
     * Create new context
     *
     * @param string $parentContext (optional)
     * @param string $contextName (optional)
     * @return stdClass response object
     */
    public function createContext($parentContext = '', $contextName = '')
    {
        $url = "/tm/context";
        $method = 'post';
        $params['parent_context'] = $parentContext;
        $params['context_name'] = $contextName;

        return $this->request($url, $method, $params);
    }

    /**
     * Delete context
     *
     * @param string $contextId
     * @return stdClass response object
     */
    public function deleteContext($contextId)
    {
        $url = "/tm/context/{$contextId}";
        $method = 'delete';

        return $this->request($url, $method);
    }

    /**
     * Show context
     *
     * @param string $contextId
     * @return stdClass response object
     */
    public function showContext($contextId)
    {
        $url = "/tm/context/{$contextId}";
        $method = 'get';

        return $this->request($url, $method);
    }

    /**
     * Translate context
     *
     * @param string $contextId
     * @return stdClass response object
     */
    public function translateContext($contextId, $source, $target, $wordCount = 0, $phraseKeys = '', $retranslate = false)
    {
        $url = "/tm/context/{$contextId}/translate/{$source}/to/{$target}";
        $method = 'post';
        $params['word_count'] = $wordCount;
        $params['phrase_keys'] = $phraseKeys;
        $params['retranslate'] = $retranslate;

        return $this->request($url, $method, $params);
    }

    /**
     * Create phrase
     *
     * @param string $contextId
     * @param string $phraseKey
     * @param string $sourceText (optional)
     * @param string $targetLang (optional)
     * @param string $targetText (optional)
     * @param integer $flags (optional)
     * @param string $remarks (optional)
     * @return stdClass response object
     */
    public function createPhrase($contextId, $phraseKey, $sourceText = '', $targetLang = '', $targetText = '', $flags = 0, $remarks = '')
    {
        $url = "/tm/context/{$contextId}/phrase/{$phraseKey}";
        $method = 'post';
        $params['context_id'] = $contextId;
        $params['phrase_key'] = $phraseKey;
        $params['source_text'] = $sourceText;
        $params['target_lang'] = $targetLang;
        $params['target_text'] = $targetText;
        $params['flags'] = $flags;
        $params['remarks'] = $remarks;

        return $this->request($url, $method, $params);
    }

    /**
     * Delete phrase
     *
     * @param string $contextId
     * @param string $phraseKey
     * @return stdClass response object
     */
    public function deletePhrase($contextId, $phraseKey)
    {
        $url = "/tm/context/{$contextId}/phrase/{$phraseKey}";
        $method = 'delete';

        return $this->request($url, $method);
    }

    /**
     * Show phrase
     *
     * @param string $contextId
     * @param string $phraseKey
     * @return stdClass response object
     */
    public function showPhrase($contextId, $phraseKey)
    {
        $url = "/tm/context/{$contextId}/phrase/{$phraseKey}";
        $method = 'get';

        return $this->request($url, $method);
    }

    /**
     * Create phrases
     *
     * @param string $contextId
     * @param string $sourceLang
     * @param string $sourceText
     * @param string $targetLang (optional)
     * @param string $targetText (optional)
     * @param string $phraseKey
     * @param string $remarks (optional)
     * @return stdClass response object
     */
    public function createPhrases($contextId, $sourceLang, $sourceText, $targetLang = '', $targetText = '', $phraseKey = '', $remarks = '')
    {
        $url = "/tm/context/{$contextId}/phrases";
        $method = 'post';
        $params['context_id'] = $contextId;
        $params['source_lang'] = $sourceLang;
        $params['source_text'] = $sourceText;
        $params['target_lang'] = $targetLang;
        $params['target_text'] = $targetText;
        $params['phrase_key'] = $phraseKey;
        $params['remarks'] = $remarks;

        return $this->request($url, $method, $params);
    }

    /**
     * Show phrases
     *
     * @param string $contextId
     * @param string $sourceLang (optional)
     * @param string $sourceText (optional)
     * @param string $targetLang (optional)
     * @return stdClass response object
     */
    public function showPhrases($contextId, $sourceLang = '', $sourceText = '', $targetLang = '')
    {
        $url = "/tm/context/{$contextId}/phrases";
        $method = 'get';
        $params['context_id'] = $contextId;
        $params['source_lang'] = $sourceLang;
        $params['source_text'] = $sourceText;
        $params['target_lang'] = $targetLang;

        return $this->request($url, $method, $params);
    }

    /**
     * Upload file resource
     *
     * @param string $filePath
     * @param string $fileName
     * @return stdClass response object
     */
    public function uploadFileResource($filePath, $fileName)
    {
        $url = "/resources/file";
        $method = 'post';
        $params['file_name'] = $fileName;
        $params['file'] = $filePath;

        return $this->request($url, $method, $params);
    }

    /**
     * Upload text resource
     *
     * @param string $text
     * @return stdClass response object
     */
    public function uploadTextResource($text)
    {
        $url = "/resources/text";
        $method = 'post';
        $params['text'] = $text;

        return $this->request($url, $method, $params);
    }

    /**
     * Show resource
     *
     * @param string $resources
     * @return stdClass response object
     */
    public function showResource($resources)
    {
        $url = "/resources/{$resources}";
        $method = 'get';
        $params['fetch'] = $text;

        return $this->request($url, $method, $params);
    }

    /**
     * Get quotations
     *
     * @param string $resources
     * @param string $wordcount
     * @param string $currency (optional)
     * @param string $proofreading (optional)
     * @param string $expertise (optional)
     * @return stdClass response object
     */
    public function getQuotations($resources, $wordcount, $currency = '', $proofreading = '', $expertise = '')
    {
        $url = "/tools/quote";
        $method = 'get';
        $params['resources'] = $resources;
        $params['wordcount'] = $wordcount;
        $params['currency'] = $currency;
        $params['proofreading'] = $proofreading;
        $params['expertise'] = $expertise;

        return $this->request($url, $method, $params);
    }

    /**
     * Get wordcount
     *
     * @param string $resources
     * @return stdClass response object
     */
    public function getWordcount($resources)
    {
        $url = "/tools/wordcount";
        $method = 'get';
        $params['resources'] = $resources;

        return $this->request($url, $method, $params);
    }

    /**
     * Detect languauge
     *
     * @param string $sourceContent
     * @param string $contentType
     * @return stdClass response object
     */
    public function detectLanguage($sourceContent, $contentType)
    {
        $url = "/mt/detect/{$contentType}";
        $method = 'get';
        $params['source_content'] = $sourceContent;

        return $this->request($url, $method, $params);
    }

    /**
     * Translate text
     *
     * @param string $sourceLang
     * @param string $targetLang
     * @param string $sourceContent
     * @param string $contentType (optional)
     * @return stdClass response object
     */
    public function machineTranslation($sourceLang, $targetLang, $sourceContent, $contentType = 'text')
    {
        $url = "/mt/translate/{$contentType}";
        $method = 'get';
        $params['source_content'] = $sourceContent;
        $params['source_lang'] = $sourceLang;
        $params['target_lang'] = $targetLang;

        return $this->request($url, $method, $params);
    }

    protected function request($requestUrl, $method = 'get', $params = array())
    {
        $ch = curl_init();
        $url = $this->getBaseURL() . $requestUrl;
        $opts = array();
        $params['account_id'] = $this->getAccountId();
        $params['secret_key'] = $this->getSecretKey();

        if ($method == 'post') {
            $opts[CURLOPT_URL] = $url;
            $opts[CURLOPT_POSTFIELDS] = $params;
        } elseif ($method == 'delete') {
            $opts[CURLOPT_URL] = $url . '?' . http_build_query($params, '', '&');
            $opts[CURLOPT_CUSTOMREQUEST] = "DELETE";
        } else {
            $opts[CURLOPT_URL] = $url . '?' . http_build_query($params, '', '&');
        }
        $opts[CURLOPT_RETURNTRANSFER] = TRUE;
        $opts[CURLOPT_SSL_VERIFYPEER] = FALSE;


        curl_setopt_array($ch, $opts);
        $result = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 404) {
            throw new Exception('OneHourTranslation could not be reached.');
        } else {
            $obj = json_decode($result);
            if (!is_object($obj)) {
                throw new Exception('OneHourTranslation response was malformed.');
            } elseif ($httpCode != 200) {
                throw new OHTAPI_Exception($httpCode, $obj->status_code, $obj->status_msg);
            } else {
                return $obj;
            }
        }
    }

    /**
     *
     * @return
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     *
     * @param $accountId
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }

    /**
     *
     * @return
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     *
     * @param $_secret_key
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     *
     * @return
     */
    public function getSandbox()
    {
        return (boolean) $this->sandbox;
    }

    /**
     *
     * @param $_sandbox
     */
    public function setSandbox($sandbox)
    {
        $this->sandbox = (boolean) $sandbox;
    }

    public function getBaseURL()
    {
        if ($this->getSandbox()) {
            return self::OHT_SANDBOX_URL;
        }
        return self::OHT_PRODUCTION_URL;
    }

}
