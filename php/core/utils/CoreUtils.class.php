<?php
class CoreUtils {
	protected static $datetime = null;

	public static function redirect($address) {
		header('Location: ' . $address);
		exit();
	}

	public static function getTimeMicroseconds() { 
		list($micsec, $sec) = explode(" ", microtime());
		return round(((float) $micsec + (float) $sec) * 1000000);
    }

	public static function getTimeMiliseconds() { 
		list($micsec, $sec) = explode(" ", microtime());
		return round(((float) $micsec + (float) $sec) * 1000);
    }

	public static function substr($str, $startPos, $length = 0) { 
		if ($startPos < 0) {
			$startPos = length($str) + $startPos;
			if ($startPos < 0) {
				$startPos = length($str);
			}
		}
		preg_match_all("/./u", $str, $array);
		if ($length) {
			$end = $startPos + $length; 
			return join("", array_slice($array[0], $start, $end)); 
		}
		else {
			return join("", array_slice($array[0], $start));
		}
	}
	
	public static function getDateTime() {
		if (!self::$datetime) {
			self::$datetime = date('Y-m-d H:i:s');
		}
		return self::$datetime;
	}

	public static function getDate() {
		return substr(self::getDateTime(), 0, 10);
	}
	
	public static function checkConstraint($booleanValue) {
		if (!$booleanValue) {
			throw new CoreException('Logic constraint check failed!');
		}
	}

	public static function shortText($text, $maxLength) {
		$modified = strip_tags($text);
		if (strlen($modified) > $maxLength) {
			$modified = substr($modified, 0, $maxLength);
 			$modified = substr($modified, 0, strrpos($modified, ' '));
			$modified .= ' ...';
		}
		return $modified;
	}

	public static function printR($var) {
		echo('<pre>');
		print_r($var);
		echo('</pre>');
	}
}
?>