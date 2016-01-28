<?php
/**
 *
 */
class CoreFormValidatorMoney extends CoreFormAbstractSingleFieldValidator {
	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$fieldValue = $field->getValue();
		if (!$fieldValue) {
			return;
		}
		$fieldValue = str_replace(',', '.', $fieldValue);
		if (floatval($fieldValue) != $fieldValue) {
			$messageManager->addMessage(
				'invalidMoneyAmount',
				array($this->fieldName => $field->getCaption())
			);
		}
		$fieldValue = number_format($fieldValue, 2, '.', '');
		$field->setValue($fieldValue);
	}
}
?>