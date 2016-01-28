<?php
class CoreFormValidatorNotEmptyFieldset extends CoreFormAbstractFieldsetValidator {
	public function __construct($fieldNames) {
		parent::__construct($fieldNames);
	}

	public function validate($messageManager) {
		foreach ($this->fieldNames as $name) {
			$fieldValue = $this->form->getField($name)->getValue();
			if (!empty($fieldValue)) {
				return;
			}
		}
		$fieldCaptions = array();
		foreach ($this->fieldNames as $name) {
			$fieldCaptions[$name] = $this->form->getField($name)->getCaption();
		}
		$messageManager->addMessage(
			'allFieldsEmpty',
			$fieldCaptions
		);
	}
}
?>