<?php
abstract class SessionRefreshAbstractController extends CoreControllerAbstractPage {
	public function prepareData() {}

	public function isTemplateEngineNeeded() {
		return False;
	}

	public function sendHeaders() {
		header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		header('Pragma: no-cache');
		header('Content-type: text/html; charset=' . strtolower(CoreConfig::get('CoreDisplay', 'globalCharset')));	
	}
	
	protected function setDisplayBasics() {}
	
	public function assignDisplayVariables() {}

	public function display() {
		echo('ok');
	}
}
?>