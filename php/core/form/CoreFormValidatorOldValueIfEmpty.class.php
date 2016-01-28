<?php
class CoreFormValidatorOldValueIfEmpty extends CoreFormValidatorNotEmpty {
	protected $oldValue = null;
	protected $idFieldName = null;
	
	public function __construct($fieldName, $idFieldName, $oldValue) {
		parent::__construct($fieldName);
		$this->idFieldName = $idFieldName;
		$this->oldValue = $oldValue;
	}

	public function validate($messageManager) {
		$recordId = $this->form->getField($this->idFieldName)->getValue();
		if (empty($recordId)) {
			parent::validate($messageManager);
		}
		else {
			$field = $this->form->getField($this->fieldName);
			$fieldValue = $field->getValue();
			if (empty($fieldValue)) {
				$field->setValue($this->oldValue);
			}
		}
	}

	public function modifyFieldNamesForVLF($vlfName, $index) {
		parent::modifyFieldNamesForVLF($vlfName, $index);
		$this->idFieldName = CoreServices::get('request')->composeFormFieldName(array($vlfName, $index, $this->idFieldName));
	}
}
?>