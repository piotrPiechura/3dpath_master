<?php
class CoreFormValidatorVLFRecordListConsistency extends CoreFormAbstractValidator {
	protected $vlfName = null;
	protected $oldValues = null;
	protected $minLength = null;
	protected $maxLength = null;

	public function __construct($vlfName, &$oldValues, $minLength = null, $maxLength = null) {
		$this->vlfName = $vlfName;
		$this->oldValues = $oldValues;
		$this->minLength = $minLength;
		$this->maxLength = $maxLength;
	}

	public function validate($messageManager) {
		// detailed error messages are not necessary
		// - if this validation fails it must be hacking or faulty javascript.
		$rowCount = $this->form->validateVLFActiveRowsAndGetActiveRowCount($this->vlfName, $this->oldValues);
		if ($rowCount == -1) {
			$messageManager->addMessage('applicationErrorOrHackingAttempt');
		}
		
		if ($this->minLength && $rowCount < $this->minLength) {
			$messageManager->addMessage('vlfTooFewItems');
		}
		elseif ($this->maxLength && $rowCount > $this->maxLength) {
			$messageManager->addMessage('vlfTooManyItems');
		}
	}

	public function modifyFieldNamesForVLF($vlfName, $index) {
		throw new CoreException('To add this type of validator use function addValidator() instead of addValidatorForVLF()!');
	}
}
?>