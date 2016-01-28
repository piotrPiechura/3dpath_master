<?php
class CoreFormFieldSubmitSet extends CoreFormAbstractField {
	protected $actions = null;
	
	public function __construct($name, $defaultValue, $actions) {
		parent::__construct($name, $defaultValue);
		if (!isset($actions[0])) {
			throw new CoreException('Invalid parameters for submit button set constructor!');
		}
		$this->actions = $actions;
		$this->fieldType = 'SubmitSet';
	}
	
	public function getActions() {
		return $this->actions;
	}

	public function hasAction($action) {
		return in_array($action, $this->actions);
	}

	public function getHTMLId($formId) {
		throw new CoreException('This method should not be used for a set of submit buttons!');
	}

	public function getActionHTMLId($formId, $action) {
		return parent::getHTMLId($formId) . '_' . $action;
	}

	public function adjustSubmittedValue($submittedValue) {
		if (is_array($submittedValue)) {
			foreach ($submittedValue as $action => $caption) { // in fact there can be only one such pair
				if (in_array($action, $this->actions)) { // else assume hacking attempt
					return $action;
				}
			}
		}
		return $this->actions[0];
	}
}
?>