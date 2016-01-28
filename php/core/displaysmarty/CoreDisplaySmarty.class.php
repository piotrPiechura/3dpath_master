<?php
require_once('php/external/smarty/libs/Smarty.class.php');

class CoreDisplaySmarty extends CoreDisplayAbstractEngine {
	protected $smarty = null;

	public function __construct() {
		$this->smarty = new Smarty();
		$this->smarty->template_dir = CoreConfig::get('CoreDisplaySmarty', 'templateDir');
		$this->smarty->compile_dir = CoreConfig::get('CoreDisplaySmarty', 'compileDir');
		$this->smarty->cache_dir = CoreConfig::get('CoreDisplaySmarty', 'cacheDir');
		$this->smarty->config_dir = CoreConfig::get('CoreDisplaySmarty', 'configDir');
		$this->assignPrivate('_form', new CoreDisplaySmartyForm($this));
		$this->assignPrivate('_utils', new CoreDisplaySmartyUtils($this));
		$this->assignPrivate('_htmlCharset', CoreConfig::get('CoreDisplay', 'globalCharset'));
	}

	/**
	 * This should only be used within displaysmarty package, to assign some special template variables,
	 * e.g. for printing forms. Names of these variables will never collide with 'normal' variables
	 * assigned by controllers, just because if the leading underscore.
	 */
	public function assignPrivate($varName, $value) {
		if (!$this->isPrivateTplVarName($varName)) {
			throw new CoreException('Invalid private temporary variable name \'' . $varName . '\'');
		}
		$this->assignNoCheck($varName, $value);
	}

	public function fetch() {
		if ($this->contentType) {
			$this->assignPrivate('_contentTemplate', strtolower($this->contentType) . '.' . $this->getTplFileExtension());
		}
		$this->initConfig();
		return $this->smarty->fetch(strtolower($this->rootTemplateType) . '.' . $this->getTplFileExtension());
	}

	/**
	 * This should only be used within displaysmarty package.
	 */
	public function displayTemplate($tplName) {
		$this->smarty->display($tplName);
	}

	protected function initConfig() {
		if ($this->lang) {
			$configFiles = CoreConfig::get('CoreDisplaySmarty', 'configFileByRootTpl');
			if (array_key_exists($this->rootTemplateType, $configFiles)) {
				$configFileName = $this->lang . '_' . $configFiles[$this->rootTemplateType] . '.conf';
			}
			else {
				foreach ($configFiles as $prefix => $fileBaseName) {
					if (substr($fileBaseName, 0, strlen($prefix)) == $prefix) {
						$configFileName = $this->lang . '_' . $fileBaseName . '.conf';
					}
				}
			}
			if ($this->contentType) {
				$this->smarty->config_load($configFileName, $this->contentType);
			}
			else {
				$this->smarty->config_load($configFileName);
			}
		}
	}

	public function getVarValue($varName) {
		return $this->smarty->get_template_vars($varName);
	}

	protected function isVarAlreadyAssigned($varName) {
		return ($this->smarty->get_template_vars($varName) != null);
	}

	protected function assignNoCheck($varName, &$value) {
		if (is_scalar($value)) {
			$this->smarty->assign($varName, $value);
		}
		else {
			$this->smarty->assign_by_ref($varName, $value);
		}
	}
}
?>