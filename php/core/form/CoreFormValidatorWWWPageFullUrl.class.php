<?php
class CoreFormValidatorWWWPageFullUrl extends CoreFormAbstractSingleFieldValidator {
	protected $checkLink = null;
	
	public function __construct($fieldName, $checkLink = false) {
		parent::__construct($fieldName);
		$this->checkLink = $checkLink;
	}

	protected function isValidPattern($fieldValue) {
		return true;
	}
	
	public function validate($messageManager) {
		$field = $this->form->getField($this->fieldName);
		$fieldValue = $field->getValue();
		if (!$fieldValue) {
			return;
		}
		$fieldCaption = $field->getCaption();
		$pos = strpos($fieldValue, '://');
		if (false === $pos) {
			$fieldValue = 'http://' . $fieldValue;
		}
		else {
			$protocolPrefix = substr($fieldValue, 0, $pos);
			if (!in_array($protocolPrefix, array('http', 'https'))) {
				$messageManager->addMessage('invalidWWWProtocolPrefix',  array($this->fieldName => $fieldCaption));
				return;
			}
		}

		if (!$this->isValidPattern($fieldValue)) {
			$messageManager->addMessage('invalidWWWPageAddress',  array($this->fieldName => $fieldCaption));
			return;		
		}
		
		if ($this->checkLink && ini_get('allow_url_fopen')) {
			$filePointer = @fopen($fieldValue, 'r');
			if (false === $filePointer) {
				$messageManager->addMessage('brokenLink',  array($this->fieldName => $fieldCaption));
				return;
			}
			fclose($filePointer);
		}
		$field->setValue($fieldValue);
	}
}
?>