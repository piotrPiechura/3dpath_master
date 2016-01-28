<?php
class CoreUrlStandard extends CoreUrlAbstractUrl {
	public function getGetRequestArray() {
		return $_GET;
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
			throw new CoreUrlUsageException('Bad number of arguments for function appendArguments()');
		}
		$address = html_entity_decode($address, ENT_QUOTES, CoreConfig::get('CoreDisplay', 'globalCharset'));

		$useQuestionMark = (strpos($address, '?') === False);
		$i = 0;
		while ($i < $argNum) {
			if ($i == 0 && $useQuestionMark) {
				$address .= '?';
			}
			else {
				$address .= '&';
			}
			$address .= urlencode($args[$i]) . '=' . urlencode($args[$i + 1]);
			$i += 2;
		}
		return $address;
	}

	public function createGetParamsTable($address) {
		$parts = explode('?', $address);
		if (sizeof($parts) < 2) {
			return array();
		}
		$pairs = explode('&', str_replace('&amp;', '&', $parts[1]));
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
  		return $result;
	}
}
?>