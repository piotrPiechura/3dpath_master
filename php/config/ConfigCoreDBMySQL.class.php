<?php
class ConfigCoreDBMySQL extends CoreConfigAbstractConfig {
	protected function init() {
		$this->values['connectionCharset'] = 'utf8';
		$this->values['connectionCollation'] = 'utf8_polish_ci';
	}
}
?>