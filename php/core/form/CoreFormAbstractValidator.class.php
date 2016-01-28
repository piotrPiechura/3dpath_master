<?php
abstract class CoreFormAbstractValidator {
	protected $form = null;

	/**
	 * In case of validation problems it adds message to the $messageManager.
	 *
	 * @param CoreFormValidationMessageContainer $messageManager
	 */
	abstract public function validate($messageManager);

	public function setForm($form) {
		$this->form = $form;
	}

	abstract public function modifyFieldNamesForVLF($vlfName, $index);
}
?>