<?php

namespace com\OHT\API;

if (!function_exists('curl_init')) {
    throw new \Exception('OneHourTranslation needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
    throw new \Exception('OneHourTranslation needs the JSON PHP extension.');
}

require_once __DIR__.'/../config/config.php';

class OHTAPI_Exception extends \Exception
{

    protected $statusCode;
    protected $statusMessage;
    protected $errorsArray;

    function __construct($statusCode, $statusMessage, Array $errorsArray = array())
    {
        parent::__construct(sprintf("#%d %s Errors [%s]", $statusCode, $statusMessage, implode(",", $errorsArray)));
        $this->setStatusCode($statusCode);
        $this->setStatusMessage($statusMessage);
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

    /**
     *
     * @return
     */
    public function getErrorsArray()
    {
        return $this->errorsArray;
    }

    /**
     *
     * @param $errorsArray
     */
    public function setErrorsArray(Array $errorsArray = array())
    {
        $this->errorsArray = $errorsArray;
    }

    function __toString()
    {
        return $this->getMessage();
    }

}

class OHTAPI
{

    /**
     * @var integer
     */
    protected static $staticPublicKey;

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
    protected $publicKey;

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
     * @param Array $conf - contains the following parameters:<br />
     * 	<ol>
     * 	<li>'public_key' - Your OHT public KEY</li>
     *  <li>'secret_key' - Your OHT secret API key</li>
     *  <li>'sandbox' - (boolean) Use OHT sandbox</li>
     *  </ol>
     *
     */
    static public function config($conf = Array())
    {
        self::$staticPublicKey = $conf['public_key'];
        self::$staticSecretKey = $conf['secret_key'];
        self::$staticSandbox = (bool) $conf['sandbox'];
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
            self::$instance = new $className(self::$staticPublicKey, self::$staticSecretKey, self::$staticSandbox);
        }
        return self::$instance;
    }

    /**
     * @param $publicKey
     * @param $secretKey
     * @param $sandbox boolean true to use OHT sandbox
     */
    public function __construct($publicKey, $secretKey, $sandbox)
    {
        $this->setPublicKey($publicKey);
        $this->setSecretKey($secretKey);
        $this->setSandbox((bool) $sandbox);
    }

    /**
     * Create a new Translation Project
     * @param string $source
     * @param string $target
     * @param string $sources
     * @param integer $wordCount (optional)
     * @param string $notes (optional)
     * @param string $callback_url (optional)
     * @param array $params (optional)
     * @return stdClass response object
     */
    public function newTranslationProject($source, $target, $sources, $wordCount = 0, $notes = '', $callbackUrl = '', $params = array())
    {
        $url = "/projects/translation";
        $method = 'post';
        $params['source_language'] = $source;
        $params['target_language'] = $target;
        $params['sources'] = $sources;
        $params['wordcount'] = $wordCount;
        $params['notes'] = $notes;
        $params['callback_url'] = $callbackUrl;

        return $this->jsonOutput($this->request($url, $method, $params));
    }

    /**
     * Create a new Transcription Project
     * @param string $source
     * @param string $sources
     * @param integer $wordCount (optional)
     * @param string $notes (optional)
     * @param string $callback_url (optional)
     * @param array $params (optional)
     * @return stdClass response object
     */
    public function newTranscriptionProject($source, $sources, $wordCount = 0, $notes = '', $callbackUrl = '', $params = array())
    {
        $url = "/projects/transcription";
        $method = 'post';
        $params['source_language'] = $source;
        $params['target_language'] = $source;
        $params['sources'] = $sources;
        $params['wordcount'] = $wordCount;
        $params['notes'] = $notes;
        $params['callback_url'] = $callbackUrl;

        return $this->jsonOutput($this->request($url, $method, $params));
    }

    /**
     * Create a new Proofreading Project (only source docs)
     * @param string $source
     * @param string $sources
     * @param integer $wordCount (optional)
     * @param string $notes (optional)
     * @param string $callback_url (optional)
     * @param array $params (optional)
     * @return stdClass response object
     */
    public function newProofReadingProject($source, $sources, $wordCount = 0, $notes = '', $callbackUrl = '', $params = array())
    {
        $url = "/projects/proofgeneral";
        $method = 'post';
        $params['source_language'] = $source;
        $params['target_language'] = $source;
        $params['sources'] = $sources;
        $params['wordcount'] = $wordCount;
        $params['notes'] = $notes;
        $params['callback_url'] = $callbackUrl;

        return $this->jsonOutput($this->request($url, $method, $params));
    }

    /**
     * Create a new Proofreading Project (source and translated docs)
     * @param string $source
     * @param string $target
     * @param string $sources
     * @param string $translations
     * @param integer $wordCount (optional)
     * @param string $notes (optional)
     * @param string $callback_url (optional)
     * @param array $params (optional)
     * @return stdClass response object
     */
    public function newProofTranslatedProject($source, $target, $sources, $translations, $wordCount = 0, $notes = '', $callbackUrl = '', $params = array())
    {
        $url = "/projects/prooftranslated";
        $method = 'post';
        $params['source_language'] = $source;
        $params['target_language'] = $target;
        $params['sources'] = $sources;
        $params['translations'] = $translations;
        $params['wordcount'] = $wordCount;
        $params['notes'] = $notes;
        $params['callback_url'] = $callbackUrl;

        return $this->jsonOutput($this->request($url, $method, $params));
    }

    /**
     * Create a new Translation + Proofreading
     * @param string $source
     * @param string $target
     * @param string $sources
     * @param integer $wordCount (optional)
     * @param string $notes (optional)
     * @param string $callback_url (optional)
     * @param array $params (optional)
     * @return stdClass response object
     */
    public function newTranslationProofreadingProject($source, $target, $sources, $wordCount = 0, $notes = '', $callbackUrl = '', $params = array())
    {
        $url = "/projects/transproof";
        $method = 'post';
        $params['source_language'] = $source;
        $params['target_language'] = $target;
        $params['sources'] = $sources;
        $params['wordcount'] = $wordCount;
        $params['notes'] = $notes;
        $params['callback_url'] = $callbackUrl;

        return $this->jsonOutput($this->request($url, $method, $params));
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

        return $this->jsonOutput($this->request($url, $method));
    }

    /**
     * Create new comment to project
     * @param integer $projectId
     * @param string $content (optional)
     * @return stdClass response object
     */
    public function newProjectComment($projectId, $content = '')
    {
        $url = "/projects/{$projectId}/comments";
        $method = 'post';
        $params['content'] = $content;

        return $this->jsonOutput($this->request($url, $method, $params));
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

        return $this->jsonOutput($this->request($url, $method));
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

        return $this->jsonOutput($this->request($url, $method));
    }

    /**
     * Fetch account details and credits balance
     *
     * @return stdClass response object
     */
    public function getAccountDetails()
    {
        $url = "/account";
        $method = 'get';

        return $this->jsonOutput($this->request($url, $method));
    }

    /**
     * Get supported languages
     * @return stdClass response object
     */
    public function getSupportedLanguages()
    {
        $url = "/discover/languages";
        $method = 'get';

        return $this->jsonOutput($this->request($url, $method));
    }

    /**
     * Get supported language pairs
     * @return stdClass response object
     */
    public function getSupportedLanguagePairs()
    {
        $url = "/discover/language_pairs";
        $method = 'get';

        return $this->jsonOutput($this->request($url, $method));
    }

    /**
     * Get supported expertise
     *
     * @param string|null $sourceLanguage
     * @param string|null $targetLanguage
     * @return stdClass response object
     */
    public function getSupportedExpertise($sourceLanguage = null, $targetLanguage = null)
    {
        $url = "/discover/expertise";
        $method = 'get';

        if ($sourceLanguage || $targetLanguage) {
            $params['source_language'] = $sourceLanguage;
            $params['target_language'] = $targetLanguage;
        } else {
            $params = [];
        }

        return $this->jsonOutput($this->request($url, $method, $params));
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

        return $this->jsonOutput($this->request($url, $method));
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

        return $this->jsonOutput($this->request($url, $method, $params));
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

        return $this->jsonOutput($this->request($url, $method));
    }

    /**
     * Get context
     *
     * @param string $contextId
     * @return stdClass response object
     */
    public function getContext($contextId)
    {
        $url = "/tm/context/{$contextId}";
        $method = 'get';

        return $this->jsonOutput($this->request($url, $method));
    }

    /**
     * Translate context
     *
     * @param string $contextId
     * @param string $source
     * @param string $target
     * @param integer $wordCount (optional)
     * @param string $phraseKeys (optional)
     * @param boolean $retranslate (optional)
     * @param string $callbackUrl (optional)
     * @param string $notes (optional)
     * @return stdClass response object
     */
    public function translateContext($contextId, $source, $target, $wordCount = 0, $phraseKeys = '', $retranslate = false, $callbackUrl = '', $notes = '')
    {
        $url = "/tm/context/{$contextId}/translate/{$source}/to/{$target}";
        $method = 'post';
        $params['word_count'] = $wordCount;
        $params['phrase_keys'] = $phraseKeys;
        $params['retranslate'] = $retranslate;
        $params['callback_url'] = $callbackUrl;
        $params['notes'] =  $notes;

        return $this->jsonOutput($this->request($url, $method, $params));
    }

    /**
     * Update phrase
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
    public function updatePhrase($contextId, $phraseKey, $sourceText = '', $targetLang = '', $targetText = '', $flags = 0, $remarks = '')
    {
        $url = "/tm/context/{$contextId}/phrase/{$phraseKey}";
        $method = 'post';
        $params['context_id'] = $contextId;
        $params['phrase_key'] = $phraseKey;
        $params['source_text'] = $sourceText;
        $params['target_language'] = $targetLang;
        $params['target_text'] = $targetText;
        $params['flags'] = $flags;
        $params['remarks'] = $remarks;

        return $this->jsonOutput($this->request($url, $method, $params));
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

        return $this->jsonOutput($this->request($url, $method));
    }

    /**
     * Get phrase
     *
     * @param string $contextId
     * @param string $phraseKey
     * @return stdClass response object
     */
    public function getPhrase($contextId, $phraseKey)
    {
        $url = "/tm/context/{$contextId}/phrase/{$phraseKey}";
        $method = 'get';

        return $this->jsonOutput($this->request($url, $method));
    }

    /**
     * Create phrases
     *
     * @param string $contextId
     * @param string $sourceLang
     * @param string $sourceText
     * @param string $targetLang (optional)
     * @param string $targetText (optional)
     * @param string $phraseKey (optional)
     * @param string $remarks (optional)
     * @return stdClass response object
     */
    public function createPhrase($contextId, $sourceLang, $sourceText, $targetLang = '', $targetText = '', $phraseKey = '', $remarks = '')
    {
        $url = "/tm/context/{$contextId}/phrases";
        $method = 'post';
        $params['context_id'] = $contextId;
        $params['source_language'] = $sourceLang;
        $params['source_text'] = $sourceText;
        $params['target_language'] = $targetLang;
        $params['target_text'] = $targetText;
        $params['phrase_key'] = $phraseKey;
        $params['remarks'] = $remarks;

        return $this->jsonOutput($this->request($url, $method, $params));
    }

    /**
     * Get phrases
     *
     * @param string $contextId
     * @param string $sourceLang (optional)
     * @param string $sourceText (optional)
     * @param string $targetLang (optional)
     * @return stdClass response object
     */
    public function getPhrases($contextId, $sourceLang = '', $sourceText = '', $targetLang = '')
    {
        $url = "/tm/context/{$contextId}/phrases";
        $method = 'get';
        $params['context_id'] = $contextId;
        $params['source_language'] = $sourceLang;
        $params['source_text'] = $sourceText;
        $params['target_language'] = $targetLang;

        return $this->jsonOutput($this->request($url, $method, $params));
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
        
        if(class_exists('\CURLFile')) {
            $params['file'] = new \CURLFile($filePath);
        }
        else {
            $params['file'] = '@' . $filePath;
        }
        
        $params['file_name'] = $fileName;

        return $this->jsonOutput($this->request($url, $method, $params));
    }

    /**
     * Upload file resource from content
     *
     * @param string $fileName
     * @param string $fileContent
     * @return stdClass response object
     */
    public function uploadFileResourceFromContent($fileName, $fileContent)
    {
        $url = "/resources/file";
        $method = 'post';

        $params['file_name']    = $fileName;
        $params['file_content'] = $fileContent;

        return $this->jsonOutput($this->request($url, $method, $params));
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

        return $this->jsonOutput($this->request($url, $method, $params));
    }

    /**
     * Get resource
     *
     * @param string $resource
     * @param string $fetch (optional)
     * @param string $filePath (optional)
     * @param integer $projectId (optional)
     * @return stdClass response object
     */
    public function getResource($resource, $fetch = false, $filePath = false, $projectId = false)
    {
        if ($fetch == RESOURCE_RESPONSE_DOWNLOAD && empty($filePath)) {
            throw new \Exception('Please specify path where resource should be saved');
        }

        if ($fetch == RESOURCE_RESPONSE_DOWNLOAD) {
            $this->downloadResource($resource, $filePath, $projectId);
        }

        $url = "/resources/{$resource}";
        $method = 'get';
        $params['fetch'] = $fetch;
        $params['project_id'] = $projectId;
        return $this->jsonOutput($this->request($url, $method, $params));
    }

    /**
     * Download resource
     *
     * @param string $resource
     * @param string $filePath
     * @param integer $projectId (optional)
     * @return stdClass response object
     */
    public function downloadResource($resource, $filePath, $projectId = false)
    {
        $url = "/resources/{$resource}/download";
        $method = 'get';
        $params['project_id'] = $projectId;

        file_put_contents($filePath, $this->request($url, $method, $params));
        if (file_exists($filePath)) {
            return true;
        }
        throw new \Exception('Please specify correct path');
    }

    /**
     * Get quotations
     *
     * @param string $sourceLangauge
     * @param string $targetLanguage
     * @param string $resources (optional)
     * @param string $wordcount (optional)
     * @param string $currency (optional)
     * @param string $proofreading (optional)
     * @param string $expertise (optional)
     * @return stdClass response object
     */
    public function getQuotations(
        $sourceLangauge,
        $targetLanguage,
        $resources = '',
        $wordcount = '',
        $currency = 'USD',
        $proofreading = 0,
        $expertise = ''
    ) {
        if (empty($resources) && empty($wordcount)) {
            throw new \Exception('Please specify at least sources or wordcount.');
        }

        $url = "/tools/quote";
        $method = 'get';
        $params['source_language'] = $sourceLangauge;
        $params['target_language'] = $targetLanguage;
        $params['resources'] = $resources;
        $params['wordcount'] = $wordcount;
        $params['currency'] = $currency;
        $params['proofreading'] = $proofreading;
        $params['expertise'] = $expertise;

        return $this->jsonOutput($this->request($url, $method, $params));
    }

    /**
     * Get wordcount
     *
     * @param string $sources
     * @return stdClass response object
     */
    public function getWordcount($resources)
    {
        $url = "/tools/wordcount";
        $method = 'get';
        $params['resources'] = $resources;

        return $this->jsonOutput($this->request($url, $method, $params));
    }

    /**
     * Detect languauge
     *
     * @param string $sourceContent
     * @param string $contentType
     * @return stdClass response object
     */
    public function detectLanguage($sourceContent, $contentType = CONTENT_TYPE_TEXT)
    {
        $url = "/mt/detect/{$contentType}";
        $method = 'get';
        $params['source_content'] = $sourceContent;

        return $this->jsonOutput($this->request($url, $method, $params));
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
    public function machineTranslation($sourceLang, $targetLang, $sourceContent, $contentType = CONTENT_TYPE_TEXT)
    {
        $url = "/mt/translate/{$contentType}";
        $method = 'get';
        $params['source_content'] = $sourceContent;
        $params['source_language'] = $sourceLang;
        $params['target_language'] = $targetLang;

        return $this->jsonOutput($this->request($url, $method, $params));
    }

    protected function request($requestUrl, $method = 'get', $params = array())
    {
        $ch = curl_init();
        $url = $this->getBaseURL() . $requestUrl;
        $opts = array();
        $params['public_key'] = $this->getPublicKey();
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
        curl_close($ch);

        return $result;
    }

    protected function jsonOutput($result)
    {
        $obj = json_decode($result);
        if (!is_object($obj)) {
            throw new \Exception('OneHourTranslation response was malformed.');
        }
        return $obj;
    }

    /**
     *
     * @return
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     *
     * @param $publicKey
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
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
            return OHT_SANDBOX_URL;
        }
        return OHT_PRODUCTION_URL;
    }

}
