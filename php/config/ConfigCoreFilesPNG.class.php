<?php
class ConfigCoreFilesPNG extends CoreConfigAbstractConfig {
	protected function init() {
		$this->values['defaultCompression'] = 3; // 0 to 9
	}
}
?>