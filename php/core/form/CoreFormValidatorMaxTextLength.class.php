<?php
/**
 * Checks if the real length of input doesn't exceed database field size.
 */
class CoreFormValidatorMaxTextLength extends CoreFormAbstractSingleFieldValidator {
	protected $maxLength = null;

	public function __construct($fieldName, $maxLength) {
		parent::__construct($fieldName);
		$this->maxLength = $maxLength;
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		if ($fieldValue = $field->getValue()) {
			if (CoreServices::get('db')->getInputSize($fieldValue) > $this->maxLength) {
				$messageManager->addMessage('textTooLong', array($this->fieldName => $field->getCaption()));
			}
		}
	}
}
?>