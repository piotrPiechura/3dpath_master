<?php
class ConfigCoreDisplaySmarty extends CoreConfigAbstractConfig {
	protected function init() {
		$this->values['templateDir'] = 'tpl/';
		$this->values['compileDir'] = '_tmp/smarty/compile/';
		$this->values['cacheDir'] = '_tmp/smarty/cache/';
		$this->values['configDir'] = 'tpl/_configs/';

		$this->values['configFileByRootTpl'] = array(
			'cms' => 'cms',
			'cmsstartpage' => 'cms',
			'cmsajax' => 'cms',
			'website' => 'website',
			'websitethickbox' => 'website',
			'email' => 'email'
		);
	}
}
?>