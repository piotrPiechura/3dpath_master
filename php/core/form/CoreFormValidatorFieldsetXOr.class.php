<?php
class CoreFormValidatorFieldsetXOr extends CoreFormAbstractFieldsetValidator {
	public function __construct($fieldNames) {
		parent::__construct($fieldNames);
	}

	public function validate($messageManager) {
		$allEmpty = True;
		foreach ($this->fieldNames as $name) {
			$fieldValue = $this->form->getField($name)->getValue();
			if (!empty($fieldValue)) {
				if ($allEmpty) {
					$allEmpty = False;
				}
				else {
					$fieldCaptions = array();
					foreach ($this->fieldNames as $name) {
						$fieldCaptions[$name] = $this->form->getField($name)->getCaption();
					}
					$messageManager->addMessage(
						'tooManyFieldsFilled',
						$fieldCaptions
					);
				}
			}
		}
	}
}
?>