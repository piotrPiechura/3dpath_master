<?php
class CoreFormValidatorStartAndFinalWeekDay extends CoreFormAbstractValidator {
	protected $fieldFromName = null;
	protected $fieldToName = null;
	
	public function __construct($fieldFromName, $fieldToName) {
		$this->fieldFromName = $fieldFromName;
		$this->fieldToName = $fieldToName;
	}

	public function validate($messageManager) {
		$startValue = $this->form->getField($this->fieldFromName)->getValue();
		$finalValue = $this->form->getField($this->fieldToName)->getValue();
		if ($finalValue && $startValue && $finalValue < $startValue) {
			$messageManager->addMessage(
				'incompatibleWeekDays',
				array(
					$this->fieldFromName => $this->form->getField($this->fieldFromName)->getCaption(),
					$this->fieldToName => $this->form->getField($this->fieldToName)->getCaption()
				)
			);
		}
	}

	public function modifyFieldNamesForVLF($vlfName, $index) {
		parent::modifyFieldNamesForVLF($vlfName, $index);
		$this->idFieldName = CoreServices::get('request')->composeFormFieldName(array($vlfName, $index, $this->idFieldName));
	}
}
?>