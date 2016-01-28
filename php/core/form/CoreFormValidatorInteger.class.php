<?php
class CoreFormValidatorInteger extends CoreFormAbstractSingleFieldValidator {
	protected $minValue = null;
	protected $maxValue = null;

	public function __construct($fieldName, $minValue, $maxValue) {
		parent::__construct($fieldName);
		$this->minValue = $minValue;
		$this->maxValue = $maxValue;
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$fieldValue = $field->getValue();
		$fieldCaption = $field->getCaption();
		if (!is_null($fieldValue)) {
			if (intval($fieldValue) != $fieldValue) {
				$messageManager->addMessage('invalidInteger', array($this->fieldName => $fieldCaption));
			}
			elseif ($fieldValue < $this->minValue) {
				$messageManager->addMessage('numberTooSmall', array($this->fieldName => $fieldCaption));
			}
			elseif ($fieldValue > $this->maxValue) {
				$messageManager->addMessage('numberTooBig', array($this->fieldName => $fieldCaption));
			}
		}
	}
}
?>