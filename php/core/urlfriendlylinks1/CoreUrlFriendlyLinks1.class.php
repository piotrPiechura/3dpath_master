<?php
class CoreUrlFriendlyLinks1 extends CoreUrlAbstractUrl {
	protected $moduleNumbers = null;
	protected $modeNumbers = null;

	public function __construct() {
		$this->moduleNumbers = CoreConfig::get('Structure', 'moduleNumbers');
		$this->modeNumbers = CoreConfig::get('Structure', 'modeNumbers');
	}

	protected function makeFriendlyLinkText($text) {
		$text = html_entity_decode($text);
		$text = str_replace(
			CoreConfig::get('CoreLangs', 'localCharsToLatinSource'),
			CoreConfig::get('CoreLangs', 'localCharsToLatinTarget'),
			$text
		);
		$text = str_replace(' ', '_', $text);
		$text = preg_replace('/[^A-Za-z0-9_\-]/', '', $text);
		return $text;
	}

	/**
	 * Takes many arguments, 
	 */
	public function appendArguments($address) {
		$actualArgs = func_get_args();
		$otherArgs = array_slice($actualArgs, 1);
		$args = $this->translateArguments($otherArgs);
		$argNum = sizeof($args);
		if ($argNum % 2 != 0) {
			throw new CoreException('Bad number of arguments for function appendArguments()');
		}
		$address = html_entity_decode($address, ENT_QUOTES, CoreConfig::get('CoreDisplay', 'globalCharset'));
		$useQuestionMark = (strpos($address, '?') === False);
		$i = 0;
		$explicitParams = '';
		$encodedParamsArray = array();
		$first = true;
		while ($i < $argNum) {
			if ($useQuestionMark && ($args[$i] == '_m' || $args[$i] == '_o' || $args[$i] == 'id' || $args[$i] == '_fl')) {
				$encodedParamsArray[$args[$i]] = $args[$i + 1];
			}
			else {
				if ($first && $useQuestionMark) {
					$explicitParams .= '?';
					$first = false;
				}
				else {
					$explicitParams .= '&';
				}
				$explicitParams .= urlencode($args[$i]) . '=' . urlencode($args[$i + 1]);
			}
			$i += 2;
		}
		if (
			!empty($encodedParamsArray['_m'])
			&& !empty($this->moduleNumbers[$encodedParamsArray['_m']])
			&& !empty($encodedParamsArray['_o'])
			&& !empty($this->modeNumbers[$encodedParamsArray['_o']])
		) {
			$address .=
				$this->moduleNumbers[$encodedParamsArray['_m']]
				. ',' . $this->modeNumbers[$encodedParamsArray['_o']];
			if (!empty($encodedParamsArray['id'])) {
				$address .= ',' . $encodedParamsArray['id'];
			}
			else {
				$address .= ',0';
			}
			if (!empty($encodedParamsArray['_fl'])) {
				$address .= ',' . $this->makeFriendlyLinkText($encodedParamsArray['_fl']);
			}
		}
		$address .= $explicitParams;
		return $address;
	}

	/**
	 * Explicit GET arguments like ...?_o=7... overwrite the ones encoded in comma-separated part of the address.
	 * For example if address is <domain>/1,1,1?_o=2 request values will be like this:
	 * 		array('_m' => 1, '_o' => 2, 'id' = 1)
	 */
	public function createGetParamsTable($address) {
		$suffix = substr($address, strlen($this->getCurrentProtocolPrefix() . CoreConfig::get('Environment', 'urlPrefix')));
		$result = array();
		if (!$suffix) {
			return $result;
		}
		$parts1 = explode('?', $suffix);
		if (!empty($parts1[0])) {
			$parts2 = explode(',', $parts1[0]);
			$moduleNames = array_flip($this->moduleNumbers);
			$modeNames = array_flip($this->modeNumbers);
			if (!empty($parts2[0]) && !empty($moduleNames[$parts2[0]])) {
				$result['_m'] = $moduleNames[$parts2[0]];
			}
			if (!empty($parts2[1]) && !empty($modeNames[$parts2[1]])) {
				$result['_o'] = $modeNames[$parts2[1]];
			}
			if (!empty($parts2[2])) {
				$result['id'] = $parts2[2];
			}
		}
		if (!empty($parts1[1])) {
			$pairs = explode('&', str_replace('&amp;', '&', $parts1[1]));
			foreach ($pairs as $pair) {
				$param = explode('=', $pair);
				$value = isset($param[1]) ? urldecode($param[1]) : '';
				if (substr($param[0], -2) == '[]') {
					$paramName = substr($param[0], 0, -2);
					if (empty($result[$paramName])) {
						$result[$paramName] = array();
					}
					$result[$paramName][] = $value;
				}
				else {
					$result[$param[0]] = $value;
				}
			}
		}
		return $result;
	}
}
?>