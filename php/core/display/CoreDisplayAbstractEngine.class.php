<?php
abstract class CoreDisplayAbstractEngine {
	protected $rootTemplateType = null;
	protected $contentType = null;
	protected $lang = null;

	/**
	 * Assign PHP object or simple value to be used in templates to a temporary variable.
	 * Name of the variable can't start with underscore. In such case function throws
	 * CoreException.
	 * It's impossible to assign to a variable that is already registered.
	 * This function must be overwritten in subclasses.
	 */
	public function assign($varName, $value) {
		if ($this->isVarAlreadyAssigned($varName)) {
			throw new CoreException('Temporary variable \'' . $varName . '\' already in use!');
		}
		if ($this->isPrivateTplVarName($varName)) {
			throw new CoreException('Invalid temporary variable name \'' . $varName . '\'');
		}
		$this->assignNoCheck($varName, $value);
	}

	public function setRootTemplateType($rootTemplateType) {
		$this->rootTemplateType = $rootTemplateType;
	}

	public function setContentType($contentType) {
		$this->contentType = $contentType;
	}

	public function setLang($lang) {
		$this->lang = $lang;
	}

	abstract public function fetch();

	public function display() {
		echo($this->fetch());
	}

	protected function getTplFileExtension() {
		return 'tpl';
	}

	protected function isPrivateTplVarName($varName) {
		return (substr($varName, 0, 1) == '_');
	}

	abstract protected function isVarAlreadyAssigned($varName);

	abstract protected function assignNoCheck($varName, &$value);
}
?>