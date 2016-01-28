<?php
abstract class CoreFormAbstractSingleFieldValidator extends CoreFormAbstractValidator {
	protected $fieldName = null;

	public function __construct($fieldName) {
		$this->fieldName = $fieldName;
	}

	public function modifyFieldNamesForVLF($vlfName, $index) {
		$this->fieldName = CoreServices::get('request')->composeFormFieldName(array($vlfName, $index, $this->fieldName));
	}
}
?>