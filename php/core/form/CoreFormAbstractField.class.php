<?php
abstract class CoreFormAbstractField {
	protected $name = null;
	/**
	 * Caption is generally the same as name; it only differs in fields belonging to VLFs.
	 * This is needed for standard validators to work properly with VLFs.
	 */
	protected $caption = null;
	/**
	 * Determines the way the field should processed. Possible values:
	 * text, select, multiselect, checkbox, file, multifile, ...
	 */
	protected $fieldType = null;
	protected $value = null;

	public function __construct($name, $defaultValue) {
		$this->name = $name;
		$this->initCaption();
		$this->value = $defaultValue;
	}

	protected function initCaption() {
		// explode name on underscores; only the part before the first unserscore determines caption;
		// exception: there may be a leading underscore, then we leave it;
		$nameSuffix = substr($this->name, 1);
		$parts = explode('_', $nameSuffix);
		$this->caption = substr($this->name, 0, 1) . $parts[0];
	}

	public function getType() {
		return $this->fieldType;
	}
		
	public function getName() {
		return $this->name;
	}	
		
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getCaption() {
		return $this->caption;
	}	

	public function setCaption($caption) {
		$this->caption = $caption;
	}	

	public function getHTMLId($formId) {
		return $formId . '_' . str_replace(array('[', ']'), array('_', ''), $this->getName());
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function getHTMLValue() {
		return $this->value;
	}
		
	public function setValue($value) {
		$this->value = $value;
	}

	/**
	 * This method only works for simple field names. It doesn't work for 'book[4][title]' for example.
	 */
	public function setValueFromRequest($httpMethod) {
		$value = CoreServices::get('request')->getFromRequest($this->name, $httpMethod);
		$this->setValue($this->adjustSubmittedValue($value));
	}

	/**
	 * Applies functions like htmlspecialchars, dependent on field type.
	 */
	abstract protected function adjustSubmittedValue($submittedValue);
}
?>