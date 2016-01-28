<?php
interface iCoreController {
	public function prepareData();

	public function isTemplateEngineNeeded();

	public function display();

	public function getSessionName();

	public function sendHeaders();
}
?>