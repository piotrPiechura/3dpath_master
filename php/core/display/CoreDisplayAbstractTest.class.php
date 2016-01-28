<?php
/* ąćęłńóśźż ĄĆĘŁŃÓŚŹŻ - UTF8 */

abstract class CoreDisplayAbstractTest extends CoreTestAbstractTest {
	abstract protected function getDisplayHandler();

	abstract protected function getTemplatesLocation();

	abstract protected function getVariableEmbedding($varName);

	abstract protected function getTplFileExtension();
	
	public function run() {
		$displayHandler = $this->getDisplayHandler();

		$tplName = '__test';
		$tplFileName = $this->getTemplatesLocation() . $tplName .'.' . $this->getTplFileExtension();
		
		$testText = 'ąćęłńóśźż ĄĆĘŁŃÓŚŹŻ';
		$embedding = $this->getVariableEmbedding('text');
		$testTplContent = '<html><head><title>Template engine test</title></head><body>' . $testText . ' ' . $embedding . '</body></html>';
		$testExpectedHTML = str_replace($embedding, $testText, $testTplContent);
		
		$fh = fopen($tplFileName, 'w');
		fwrite($fh, $testTplContent);
		fclose($fh);

		$this->testingEngine->tryToPass($displayHandler, 'setRootTemplateType', array($tplName));
		$this->testingEngine->tryToFail($displayHandler, 'assign', array('_text', $testText), 'CoreException');
		$this->testingEngine->tryToPass($displayHandler, 'assign', array('text', $testText));
		$this->testingEngine->tryToPass($displayHandler, 'display', array(), null, $testExpectedHTML);
		
		unlink($tplFileName);
	}
}
?>