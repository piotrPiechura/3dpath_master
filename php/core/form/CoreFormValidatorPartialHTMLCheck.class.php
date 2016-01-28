<?php
class CoreFormValidatorPartialHTMLCheck extends CoreFormAbstractSingleFieldValidator {
	public function __construct($fieldName) {
		parent::__construct($fieldName);
	}

	public function validate($messageManager) {
		$fieldValue = $this->form->getField($this->fieldName)->getValue();
		if (!empty($fieldValue)) {
			$htmlValidator = new Core_HTML_Validator();
			$newFieldValue = $htmlValidator->getValidPartialHTML($fieldValue);
			$this->form->getField($this->fieldName)->setValue($newFieldValue);
		}
	}
}
?>