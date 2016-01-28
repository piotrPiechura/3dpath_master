<?php
require_once('php/core/controller/CoreControllerMain.class.php');

$testSet = array(
	// @TODO: 'CoreDisplaySmartyTest' - brak pliku konfiguracyjnego i szablonu
	'CoreDBMySQLTest',
	'CoreFileImageHandlerTest',
	'CoreMailSimpleTest',
	'CoreSMSSoap1Test'
);
$coreController = new CoreControllerMain();
$coreController->test($testSet);
?>
