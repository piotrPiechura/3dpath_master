<?php
abstract class CoreConfigAbstractConfig {
	protected $values = null;

	public function __construct() {
		$this->values = array();
		$this->init();
	}
	
	/**
	 * Initializes $values array;
	 */
	abstract protected function init();

	public function get($index) {
		if (!isset($this->values[$index])) {
			throw new CoreException('Unknown config variable \'' . $index . '\'.');
		}
		return $this->values[$index];
	}
}
?>