<?php
class CoreAutoload {
	protected static function getBasePath() {
		return substr(__FILE__, 0, -strlen('core/autoload/CoreAutoload.class.php'));
	}

	public static function getClassPath($name) {
		if (strpos($name, '_') !== false) {
			return self::getClassPathZendStyle($name);
		}
		return self::getClassPathOldSchool($name);
	}

	protected static function getClassPathOldSchool($name) {
		$path = self::getBasePath();
		if (substr($name, 0, 1) == 'i') {
			$interface = True;
			$nameMainPart = substr($name, 1);
		}
		else {
			$interface = False;
			$nameMainPart = $name;
		}
		if (substr($nameMainPart, 0, 4) == 'Core') {
			$path .= 'core/';
			$path .= self::getModuleDirectory($path, substr($nameMainPart, 4));
		}
		elseif (substr($nameMainPart, 0, 6) == 'Config') {
			$path .= 'config/';
			$path .= self::getModuleDirectory($path, substr($nameMainPart, 6));
		}
		else {
			$path .= 'modules/';
			$path .= self::getModuleDirectory($path, $nameMainPart);
		}
		if ($interface) {
			$path .= $name . '.interface.php';
		}
		else {
			$path .= $name . '.class.php';
		}
		return $path;
	}

	protected static function getModuleDirectory($directory, $text) {
		$suffixStartPositions = self::getPositiveUpperCasePositions($text);
		foreach ($suffixStartPositions as $suffixStartPos) {
			$prefix = strtolower(substr($text, 0, $suffixStartPos));
			if (file_exists($directory . $prefix)) {
				return $prefix . '/';
			}
		}
		return '';
	}

	protected static function getPositiveUpperCasePositions($text) {
		$positions = array(strlen($text));
		$lowerCaseText = strtolower($text);
		for ($i = strlen($text) - 1; $i > 0; $i --) {
			if ($text[$i] != $lowerCaseText[$i]) {
				$positions[] = $i;
			}
		}
		return $positions;
	}

	protected static function getClassPathZendStyle($name) {
		$parts = explode('_', $name);
		$path = self::getBasePath();
		switch ($parts[0]) {
			case 'Core':
				$path .= 'core/';
				for ($i = 1; $i < sizeof($parts) - 1; $i++) {
					$path .= $parts[$i] . '/';
				}
				break;
			case 'Config':
				$path .= 'config/';
				for ($i = 1; $i < sizeof($parts) - 1; $i++) {
					$path .= $parts[$i] . '/';
				}
				break;
			default:
				$path .= 'modules/';
				for ($i = 0; $i < sizeof($parts) - 1; $i++) {
					$path .= $parts[$i] . '/';
				}
				break;
		}
		$path .= $parts[sizeof($parts) - 1] . '.php';
		return $path;
	}
}
?>