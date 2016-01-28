<?php
class CoreRequestStandard implements iCoreRequest {
	protected $sessionInitialized = null;
	protected $stripSlashes = null;
	protected $stripDoubleQuotes = null;
	protected $httpGet = null;
	protected $sessionNameSuffix = null;
	
	public function __construct() {
		if (ini_get('magic_quotes_sybase') && (strtolower(ini_get('magic_quotes_sybase')) != "off")) {
			$this->stripDoubleQuotes = True;
		} 
		elseif (ini_get('magic_quotes_gpc') && (strtolower(ini_get('magic_quotes_gpc')) != "off")) {
			$this->stripSlashes = True;
		}
		$this->httpGet = CoreServices::get('url')->getGetRequestArray();
		if (!empty($_SERVER['argv'])) {
			for ($i = 1; $i + 1 < sizeof($_SERVER['argv']); $i += 2) {
				$this->httpGet[$_SERVER['argv'][$i]] = $_SERVER['argv'][$i + 1];
			}
		}
	}

	public function initSession($sessionName, $sessionId = null) {
		$this->sessionNameSuffix = $sessionName;
		if ($this->sessionInitialized) {
			throw new CoreException('Tried to initialize session, but it was already initialized.');
		}
		$this->sessionInitialized = True;
		if (!empty($sessionId)) {
			session_id($sessionId);
		}
		session_name(CoreConfig::get('Environment', 'websiteName') . '_' . $this->sessionNameSuffix);
		session_start();
	}

	public function getSessionId() {
		return session_id();
	}

	public function isActiveSession($sessionId) {
		$dir = session_save_path();
		return is_file($dir . '/sess_' . $sessionId);
	}

	public function getSessionName() {
		return $this->sessionNameSuffix;
	}

	public function isNotEmptyRequest($requestType) {
		$this->checkIfValidRequestType($requestType);
		switch ($requestType) {
			case 'post':
				return (!empty($_POST));
			case 'get':
				return (!empty($this->httpGet));
			case 'session':
				return (!empty($_SESSION));
		}
	}

	public function getFromRequest($fieldName, $requestType = null) {
		$this->checkIfValidRequestType($requestType);
		$result = null;
		if (is_null($requestType) || $requestType == 'post') {
			$result = $this->getFromPost($fieldName);
		}
		if (is_null($result) && (is_null($requestType) || $requestType == 'get')) {
			$result = $this->getFromGet($fieldName);
		}
		if (is_null($result) && (is_null($requestType) || $requestType == 'session')) {
			$result = $this->getFromSession($fieldName);
		}
		return $result;
	}

	public function getFromPost($fieldName) {
		return $this->getFromRequestArray($fieldName, $_POST);
	}

	public function isSetPost($fieldName) {
		return $this->isInRequestArray($fieldName, $_POST);
	}

	public function getFromGet($fieldName) {
		return $this->getFromRequestArray($fieldName, $this->httpGet);
	}

	public function isSetGet($fieldName) {
		return $this->getFromRequestArray($fieldName, $this->httpGet);
	}

	public function getFromSession($key) {
		if ($this->isSetSession($key)) {
			return unserialize($_SESSION[$key]);
		}
		return null;
	}

	public function isSetSession($key) {
		$this->checkIfSessionInitialized();
		return array_key_exists($key, $_SESSION);;
	}

	public function setSession($key, $value) {
		$this->checkIfSessionInitialized();
		$_SESSION[$key] = serialize($value);
	}

	public function getFileStruct($key) {
		$segments = $this->getFormFieldNameSegments($key);
		if (empty($_FILES[$segments[0]])) {
			return null;
		}
		if (sizeof($segments) == 1) {
			return $_FILES[$segments[0]];
		}
		$result = array();
		foreach ($_FILES[$segments[0]] as $attribute => $array) {
			$value = $array;
			for ($i = 1; $i < sizeof($segments); $i++) {
				$value = $value[$segments[$i]];
			}
			$result[$attribute] = $value;
		}
		return $result;
	}

	/**
	 * Splits field names like 'fieldSetName[index][fieldName]' to parts:
	 * array (0 => 'fieldSetName', 1 => 'index', 2 => 'fieldName')
	 */
	public function getFormFieldNameSegments($fieldName) {
		$name = str_replace(']', '', $fieldName);
		return explode('[', $name);	
	}

	/**
	 * Composes form field name like 'fieldSetName[index][fieldName]' of an array of segments like:
	 * array (0 => 'fieldSetName', 1 => 'index', 2 => 'fieldName')
	 */
	public function composeFormFieldName($segments) {
		if (!is_array($segments) || empty($segments)) {
			throw new CoreException('Invalid argument for function composeFormFieldName().');
		}
		$result = $segments[0];
		for ($i = 1; $i < sizeof($segments); $i++) {
			$result .= '[' . $segments[$i] . ']';
		}
		return $result;
	}

	/**
	 * Note: this simple solution doesn't work for $_FILES array,
	 * because of its odd structure.
	 */
	protected function getFromRequestArray($fieldName, &$array) {
		$segments = $this->getFormFieldNameSegments($fieldName);
		$value = $array;
		$i = 0;
		foreach($segments as $segment) {
			if (!is_array($value)) {
				throw new CoreException('Invalid request index \'' . $fieldName . '\'');
			}
			if (!array_key_exists($segment, $value)) {
				return null;
			}
			$value = $value[$segment];
			$i += 1;
		}
		$x = $this->removeMagicQuotesDeep($value);
		return $x;
	}
	
	/**
	 * Note: this simple solution doesn't work for $_FILES array,
	 * because of its odd structure.
	 */
	protected function isInRequestArray($fieldName, &$array) {
		$segments = $this->getFormFieldNameSegments($fieldName);
		$value = $array;
		foreach($segments as $segment) {
			if (!is_array($value)) {
				throw new CoreException('Invalid request index \'' . $fieldName . '\'');
			}
			if (!array_key_exists($segment, $value)) {
				return False;
			}
			$value = $value[$segment];
		}
		return True;
	}

	/**
	 * Works for simple values (strings) and for arrays.
	 * In the latter case it recursively removes magic quotes from array elements.
	 */
	protected function removeMagicQuotesDeep(&$value) {
		if (!$this->stripDoubleQuotes && !$this->stripSlashes) {
			return $value;
		}
		if (is_array($value)) {
			$retVal = array();
			foreach ($value as $key => $element) {
				if (is_array($element)) {
					$retVal[$key] = $this->removeMagicQuotesDeep($element);
				}
				else {
					$retVal[$key] = $this->removeMagicQuotes($element);
				}
			}
			return $retVal;
		}
		return $this->removeMagicQuotes($value);
	} 

	protected function removeMagicQuotes($text) {
		if ($this->stripDoubleQuotes) {
			return str_replace('\'\'', '\'', $text);
		}
		if ($this->stripSlashes) {
			return stripslashes($text);
		}
		return $text;
	}
	
	protected function checkIfValidRequestType($requestType) {
		if (!in_array($requestType, array(null, 'post', 'get', 'session'))) {
			throw new CoreException('Request type not supported: \'' . $requestType . '\'.');
		}
	}

	protected function checkIfSessionInitialized() {
		if (!$this->sessionInitialized) {
			throw new CoreException('Tried to access session variable, but the session was not initialized.');
		}
	}

	public function sendRequest($url, $timeout = null, &$optionalHeaders = null, &$getParams = null, &$postParams = null) {
		if (!empty($getParams)) {
			$sep = ((strpos($url, '?') === FALSE) ? '?' : '&');
			$url .= $sep . http_build_query($getParams);
		}
		$params = array();
		$params['method'] = (empty($postParams) ? 'GET' : 'POST');
		if (!empty($postParams)) {
			$params['content'] = http_build_query($postParams);
		}
		if ($optionalHeaders) {
			$headers = '';
			foreach ($optionalHeaders as $headerText) {
				$headers .= $headerText . "\r\n";
			}
			$params['header'] = $headers;
		}
		$params['timeout'] = ($timeout ? $timeout : 3);
		$contextParams = array('http' => $params);
		$context = stream_context_create($contextParams);
		$fp = @fopen($url, 'rb', false, $context);
		if (!$fp) {
			return null;
		}
		$response = @stream_get_contents($fp);
		if ($response === false) {
			return null;
		}
		return $response;
	}

	public function getRealIP() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}
?>