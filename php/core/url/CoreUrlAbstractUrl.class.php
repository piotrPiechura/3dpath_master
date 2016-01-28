<?php
/**
 * Very important convention: get parameters '_m' and '_o' have special meaning.
 * Value of '_m' is the name of a module and value of '_o' specifies which operation
 * should be performed, e.g. 'list', 'cmsList', 'cmsEdit', etc.
 */
abstract class CoreUrlAbstractUrl {
	public function isHTTPSOn() {
		return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] = 'on');
	}
	
	protected function getCurrentProtocolPrefix() {
		return $this->isHTTPSOn() ? 'https://' : 'http://';
	}

	public function getGetRequestArray() {
		return $this->createGetParamsTable($this->getCurrentExactAddress());
	}

	public function getHTMLVersion($url) {
		return htmlentities(
			$url,
			ENT_QUOTES,
			CoreConfig::get('CoreDisplay', 'globalCharset')
		);
	}

	/**
	 * This function takes any even number of arguments, like this:
	 * createAddress('lang', 'en', '_o', 'CMSList');
	 * This function should also accept single argument if it is an array, like this:
	 * $a = array('lang', 'en', '_o', 'CMSList');
	 * createAddress($a);
	 */
	public function createAddress() {
		$actualArgs = func_get_args();
		$args = $this->translateArguments($actualArgs);
		return $this->appendArguments(
			$this->getCurrentProtocolPrefix() . CoreConfig::get('Environment', 'urlPrefix'),
			$args
		);
	}
	
	public function absolute($url){
        $url = CoreConfig::get('Environment', 'urlPath') . ltrim($url,'/');
        return htmlentities(
            $url, ENT_QUOTES, CoreConfig::get('CoreDisplay', 'globalCharset')
        );
    }
	
	
	/**
	 * This function takes any even number of arguments, like this:
	 * createHTML('lang', 'en', '_o', 'CMSList');
	 * This function should also accept single argument if it is an array, like this:
	 * $a = array('lang', 'en', '_o', 'CMSList');
	 * createHTML($a);
	 */
	public function createHTML() {
		$actualArgs = func_get_args();
		return htmlentities(
			$this->createAddress($this->translateArguments($actualArgs)),
			ENT_QUOTES,
			CoreConfig::get('CoreDisplay', 'globalCharset')
		);
	}

	public function translateHTMLToPlain($urlHTML) {
		return html_entity_decode(
			$urlHTML,
			ENT_QUOTES,
			CoreConfig::get('CoreDisplay', 'globalCharset')
		);
	}

	/**
	 * This function takes any even number of arguments, like createHTML, but there's one
	 * addidtional argument, address, that comes first, like this:
	 * appendArguments('http://www.example.com', 'lang', 'en', '_o', 'CMSList');
	 * This function should also accept single argument if it is an array, like this:
	 * $a = array('lang', 'en', '_o', 'CMSList');
	 * appendArguments('http://www.example.com', $a);
	 * It should be safe to pass $address containing "HTML entites" either encoded or not encoded.
	 */
	abstract public function appendArguments($address);

	/**
	 * This function works exactly like appendArguments(), with the difference that it
	 * returns URL with "HTML entities" encoded.
	 */
	public function appendArgumentsHTML($address) {
		$actualArgs = func_get_args();
		$otherArgs = array_slice($actualArgs, 1);
		return htmlentities(
			$this->appendArguments($address, $this->translateArguments($otherArgs)),
			ENT_QUOTES,
			CoreConfig::get('CoreDisplay', 'globalCharset')
		);
	}

	public function getFullPath($relativePath = null) {
		if (!$relativePath) {
			$relativePath = '';
		}
		return $this->getCurrentProtocolPrefix() . CoreConfig::get('Environment', 'urlPrefix') . $relativePath;
	}

	public function getFullPathHTML($relativePath) {
		return htmlentities($this->getFullPath($relativePath));
	}

	public function getCurrentPageUrl() {
		$actualArgs = func_get_args();
		$args = array_merge($this->getCurrentPageGetAttrs(), $this->translateArguments($actualArgs));
		return $this->createAddress($args);
	}

	public function getCurrentPageUrlHTML() {
		$actualArgs = func_get_args();
		$args = array_merge($this->getCurrentPageGetAttrs(), $this->translateArguments($actualArgs));
		return $this->createHTML($args);
	}

	public function getCurrentPageFullUrl() {
		// @TODO: urldecode jest reczej źle użyte
		// return $this->getCurrentProtocolPrefix() . CoreConfig::get('Environment', 'domainName') . urldecode($_SERVER['REQUEST_URI']);
		return $this->getCurrentProtocolPrefix() . CoreConfig::get('Environment', 'domainName') . $_SERVER['REQUEST_URI'];
	}

	public function getCurrentPageFullUrlHTML() {
		return htmlentities(
			$this->getCurrentPageFullUrl(),
			ENT_QUOTES,
			CoreConfig::get('CoreDisplay', 'globalCharset')
		);
	}

	public function stripParams($address, $unwantedParams) {
		$allParams = $this->createGetParamsTable($address);
		$newUrlAttributes = array();
		foreach ($allParams as $param => $value) {
			if (!in_array($param, $unwantedParams)) {
				$newUrlAttributes[] = $param;
				$newUrlAttributes[] = $value;
			}
		}
		return $this->createAddress($newUrlAttributes);
	}

	abstract public function createGetParamsTable($address);

	protected function translateArguments($actualArgs) {
		if (array_key_exists(0, $actualArgs) && is_array($actualArgs[0])) {
			$args = $actualArgs[0];
		}
		elseif (sizeof($actualArgs) % 2 != 0) {
			throw new CoreException('Bad number or type of arguments');
		}
		else {
			$args = $actualArgs;
		}
		return $args;
	}
	
	protected function getCurrentPageGetAttrs() {
		return array('_m', CoreServices::get('modules')->getControllerModule(), '_o', CoreServices::get('modules')->getControllerMode());
	}
	
	/**
	 * $protocol can be 'http' or 'https'
	 */
	public function getCurrentExactAddress($protocol = null) {
		$protocolPrefix = $protocol ? $protocol . '://' : $this->getCurrentProtocolPrefix();
		// @TODO: urldecode jest oczywiście źle, tylko jak powinno być dobrze???
		// return $protocolPrefix . CoreConfig::get('Environment', 'domainName') . urldecode($_SERVER['REQUEST_URI']);
		return $protocolPrefix . CoreConfig::get('Environment', 'domainName') . $_SERVER['REQUEST_URI'];
	}
}
?>