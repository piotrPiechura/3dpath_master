<?php

final class CoreConfig {
	protected static $configObjects = null;

	public static function get($configName, $varName) {
		$configClassName = 'Config' . $configName;
		if (!isset(self::$configObjects[$configClassName])) {
			self::$configObjects[$configClassName] = new $configClassName();
		}
		return self::$configObjects[$configClassName]->get($varName);
	}
}
?>