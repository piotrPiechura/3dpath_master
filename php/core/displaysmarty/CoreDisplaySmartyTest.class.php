<?php
class CoreDisplaySmartyTest extends CoreDisplayAbstractTest {
	protected function getDisplayHandler() {
		return new CoreDisplaySmarty();
	}

	protected function getTemplatesLocation() {
		return CoreConfig::get('CoreDisplaySmarty', 'templateDir');
	}
	

	protected function getVariableEmbedding($varName) {
		return '{$' . $varName . '}';
	}

	protected function getTplFileExtension() {
		return 'tpl';
	}
}
?>