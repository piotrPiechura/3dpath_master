<?php
abstract class CoreFormAbstractFieldsetValidator extends CoreFormAbstractValidator {
	protected $fieldNames = null;

	public function __construct($fieldNames) {
		$this->fieldNames = $fieldNames;
	}

	public function modifyFieldNamesForVLF($vlfName, $index) {
		$oldFieldNames = $this->fieldNames; // copy!
		$this->fieldNames = array();
		foreach ($oldFieldNames as $fieldName) {
			$this->fieldNames[] = CoreServices::get('request')->composeFormFieldName(array($vlfName, $index, $fieldName));
		}
	}
}
?>