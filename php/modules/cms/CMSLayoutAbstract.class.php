<?php 
abstract class CMSLayoutAbstract {
	protected $controller = null;

	public function __construct(&$controller) {
		$this->controller = $controller; 
	}

	abstract public function getBaseTemplate();
		
	abstract public function assignDisplayVariables();
}
?>