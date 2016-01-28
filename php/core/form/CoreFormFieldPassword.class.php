<?php
class CoreFormFieldPassword extends CoreFormAbstractField {
	protected $openText = null;
	
	public function __construct($name) {
		parent::__construct($name, null);
		$this->fieldType = 'Password';
	}

	public function getHTMLValue() {
		return '';
	}

	public function setValueFromRequest($httpMethod) {
		$submittedValue = CoreServices::get('request')->getFromRequest($this->name, $httpMethod);
		if (!empty($submittedValue)) {
			$this->openText = $submittedValue;
			$this->setValue($this->adjustSubmittedValue($submittedValue));
		}
	}

	public function adjustSubmittedValue($submittedValue) {
		if (!is_scalar($submittedValue)) { // assume hacking attempt
			//return '';
			return null;
		}
		return sha1($submittedValue);
	}

	public function getOpenText() {
		return $this->openText;
	}
}
?>