<?php
/**
 * This class should be final because it has public static functions.
 * Creating a subclass would force changes in the code of many other classes.
 */
final class CoreServices {
	private static $services = array();

	public static function get($serviceName) {
		if (!array_key_exists($serviceName, self::$services)) {
			self::initService($serviceName);
		}
		return self::$services[$serviceName];
	}
	
	private static function initService($serviceName) {
		// throws exception if $serviceName is not valid
		$serviceObject = CoreConfig::get('CoreServices', $serviceName);
		self::$services[$serviceName] = $serviceObject;
	}
}
?>