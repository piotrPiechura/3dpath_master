<?php
class CoreFormValidatorVLFLength extends CoreFormAbstractValidator {
	protected $vlfName = null;
	protected $minLength = null;
	protected $maxLength = null;

	public function __construct($vlfName, $minLength = null, $maxLength = null) {
		$this->vlfName = $vlfName;
		$this->minLength = $minLength;
		$this->maxLength = $maxLength;
	}
	
	public function validate($messageManager) {
		// detailed error messages are not necessary
		// - if this validation fails it must be hacking or faulty javascript.
		$rowCount = $this->form->getVLFActiveRowCount($this->vlfName);
		if ($this->minLength && $rowCount < $this->minLength) {
			$messageManager->addMessage('vlfTooFewItems_' . $this->vlfName);
		}
		elseif ($this->maxLength && $rowCount > $this->maxLength) {
			$messageManager->addMessage('vlfTooManyItems_' . $this->vlfName);
		}
	}
	
	public function modifyFieldNamesForVLF($vlfName, $index) {
		throw new CoreException('To add this type of validator use function addValidator() instead of addValidatorForVLF()!');
	}
}
?>