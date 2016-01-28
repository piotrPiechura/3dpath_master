<?php
class CoreFormValidatorFieldsEqual extends CoreFormAbstractFieldsetValidator {
	public function __construct($fieldNames) {
		parent::__construct($fieldNames);
		if (sizeof($fieldNames) < 2) {
			throw new CoreException('Invalid parameter for CoreFormValidatorFieldsEqual constructor');
		}
	}

	public function validate($messageManager) {
		$value = null;
		$first = True;
		foreach ($this->fieldNames as $fieldName) {
			$fieldValue = $this->form->getField($fieldName)->getValue();
			if ($first) {
				$value = $fieldValue;
				$first = False;
			}
			elseif ($value != $fieldValue) {
				$fieldCaptions = array();
				foreach ($this->fieldNames as $fieldName) {
					$fieldCaptions[$fieldName] = $this->form->getField($fieldName)->getCaption();
				}
				$messageManager->addMessage('valuesNotEqual', $fieldCaptions);
			}
		}
	}
}
?>