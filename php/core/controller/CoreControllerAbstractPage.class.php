<?php
abstract class CoreControllerAbstractPage implements iCoreController {
	public function isTemplateEngineNeeded() {
		return True;
	}

	public function assignDisplayVariables() {
		$display = CoreServices2::getDisplay();
		$modules = CoreServices2::getModules();
		$display->assign('controllerModule', $modules->getControllerModule());
		$display->assign('controllerMode', $modules->getControllerMode());
		$display->assign('url', CoreServices2::getUrl());
	}

	public function display() {
		$this->setDisplayBasics();
		$this->setDisplayContentType();
		CoreServices2::getDisplay()->display();
	}

	public function getSessionName() {
		return 'defaultSession';
	}

	public function sendHeaders() {
		header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		header('Pragma: no-cache');
		header('Content-type: text/html; charset=' . strtolower(CoreConfig::get('CoreDisplay', 'globalCharset')));	
	}

	protected function setDisplayContentType() {
		CoreServices2::getDisplay()->setContentType(CoreServices::get('modules')->getCurrentControllerName());
	}

	protected function getNoPermissionsAddress() {
		return CoreServices2::getUrl()->createAddress();
	}
	
	abstract protected function setDisplayBasics();
}
?>