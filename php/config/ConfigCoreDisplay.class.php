<?php
class ConfigCoreDisplay extends CoreConfigAbstractConfig {
	protected function init() {
		$this->values['globalCharset'] = 'UTF-8';
		$this->values['htmlDocType'] = 'XHTML 1.0 Transitional';
		
		$this->values['paginationDefaultMaxRecords'] = 20;
		$this->values['paginationDefaultMaxPageLinks'] = 21;
		$this->values['paginationChars'] = array(
			'universal' => CoreConfig::get('CoreLangs', 'allLocalCharVariants')
		);
		$this->values['paginationDummy'] = '?';
	}
}
?>