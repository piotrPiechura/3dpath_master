<?php
class CoreControllerManager {
	protected $currentModule = null;
	protected $currentMode = null;
	protected $currentController = null;

	public function __construct() {
		$this->initCurrentControllerName();	
	}

	public function getController() {
		if (is_null($this->currentController)) {
			$controllerClassName = $this->getControllerClass($this->currentModule, $this->currentMode);
			$this->currentController = new $controllerClassName();
		}
		return $this->currentController;
	}
	
	public function getControllerModule() {
		return $this->currentModule;
	}

	public function getControllerMode() {
		return $this->currentMode;
	}

	public function getCurrentControllerName() {
		return $this->getControllerName($this->currentModule, $this->currentMode);
	}

	/**
	 * Be careful when making some changes to this function.
	 * It indirectly validates GET input.
	 */
	protected function initCurrentControllerName() {
		$moduleName = CoreServices::get('request')->getFromGet('_m');		
		if (empty($moduleName)) {
			$moduleName = CoreConfig::get('Structure', 'defaultModule');
		}
		$modeName = CoreServices::get('request')->getFromGet('_o');
		if (empty($modeName)) {
			$modeName = CoreConfig::get('Structure', 'defaultMode');
		}
		if (!file_exists(CoreAutoload::getClassPath($this->getControllerClass($moduleName, $modeName)))) {
			$moduleName = CoreConfig::get('Structure', 'defaultModule');
			$modeName = CoreConfig::get('Structure', 'defaultMode');
		}
		$this->currentModule = $moduleName;
		$this->currentMode = $modeName;
	}

	protected function getControllerName($moduleName, $modeName) {
		return $moduleName . $modeName;
	}

	protected function getControllerClass($moduleName, $modeName) {
		return $this->getControllerName($moduleName, $modeName) . 'Controller';
	}
}
?>