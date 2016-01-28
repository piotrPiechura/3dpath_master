<?php
class CoreControllerMain {
	public function __construct() {
		require_once('php/core/autoload/__autoload.function.php');		
	}
	
	public function test($testNames) {
		$testEngine = new CoreTestEngine();
		$testEngine->run($testNames);
	}

	public function run() {
		try {
			$this->checkUrl();
			CoreServices::get('errorHandler')->init();
			$controller = CoreServices::get('modules')->getController();
			CoreServices::get('request')->initSession($controller->getSessionName());
			$controller->prepareData();
			if ($controller->isTemplateEngineNeeded()) {
				$controller->assignDisplayVariables();
			}
			$controller->sendHeaders();
			$controller->display();
		}
		catch (Exception $exception) {
			CoreServices::get('errorHandler')->handleException($exception);
		}
	}

	protected function checkUrl() {
		$uri = $_SERVER['REQUEST_URI'];
		$uriPart = $uri;
		$qmPos = strpos($uriPart, '?');
		if ($qmPos !== false) {
			$uriPart = substr($uriPart, 0, $qmPos);
		}
		$uriPart = substr($uriPart, strlen(CoreConfig::get('Environment', 'urlPath')));
		if (strpos($uriPart, '/') !== false) {
			header("HTTP/1.0 404 Not Found");
			exit();
			// W wersji testowej można tez tak:
			// throw new CoreException('Invalid location: ' . $uri);
		}
	}
}
?>