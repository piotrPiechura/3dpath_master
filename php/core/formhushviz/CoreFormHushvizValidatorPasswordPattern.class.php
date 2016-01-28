<?php
class CoreFormHushvizValidatorPasswordPattern extends CoreFormAbstractSingleFieldValidator {
	protected $fieldType = null;
	
	public function __construct($fieldName, $fieldType = 'password') {
		parent::__construct($fieldName);
		$this->fieldType = $fieldType;
	}

	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$value = ($this->fieldType == 'password') ? $field->getOpenText() : $field->getValue();
		if (empty($value)) {
			return;
		}
		// Znaki specjalne w preg_match (takie które trzeba wyescapowaæ):
		// \ + * ? [ ^ ] $ ( ) { } = ! < > | : -
		// oraz znak od którego siê zaczê³o, zwykle /
		if (
			strlen($value) < 8
			|| (
				!preg_match('/[0-9]/', $value)
				&& !preg_match('/[\\\?\!@#\$%\^&\*\/\|\[\]\{\}\(\)]/', $value)
			)
		) {
			$messageManager->addMessage('newPasswordTooWeak', array($this->fieldName => $field->getCaption()));
			return;
		}
	}
}
?>