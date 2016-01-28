<?php
class ConfigCoreFilesJPG extends CoreConfigAbstractConfig {
	protected function init() {
		$this->values['defaultQuality'] = 95; // 0 to 100
	}
}
?>