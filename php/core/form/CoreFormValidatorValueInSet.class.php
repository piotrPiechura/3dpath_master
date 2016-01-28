<?php
class CoreFormValidatorValueInSet extends CoreFormAbstractSingleFieldValidator {
	protected $acceptedValues = null;

	public function __construct($fieldName, &$acceptedValues) {
		parent::__construct($fieldName);
		$this->acceptedValues = $acceptedValues;
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		if (!in_array($field->getValue(), $this->acceptedValues)) {
			$messageManager->addMessage('selectFieldValueOutOfRange', array($this->fieldName => $field->getCaption()));
		}
	}
}
?>