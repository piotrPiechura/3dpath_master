<?php
class CoreDisplaySmartyForm {
	/**
	 * @var CoreDisplaySmarty
	 */
	protected $templateEngine = null;
	protected $form = null;
	protected $showFormErrors = null;
	
	public function __construct($templateEngine) {
		$this->templateEngine = $templateEngine;
	}

	public function start($form, $formId = null, $showErrors = False, $htmlAttributes = null) {
		if ($this->form) {
			throw new CoreException('HTML form within another form!');
		}
		$this->form = $form;
		$this->showFormErrors = $showErrors;
		$this->templateEngine->assignPrivate('_currentForm', $this->form);
		if ($formId) {
			$this->templateEngine->assignPrivate('_currentFormId', $formId);
		}
		if ($htmlAttributes) {
			$this->templateEngine->assignPrivate('_currentFormHtmlAttributes', $htmlAttributes);
		}		
		$this->templateEngine->displayTemplate('form/form.start.tpl');
		$this->templateEngine->assignPrivate('_currentFormHtmlAttributes', null);

		$errorMessageStruct = $this->templateEngine->getVarValue('formErrorMessages');
		if (!empty($errorMessageStruct)) {
			$this->templateEngine->assignPrivate(
				'_currentFormErrorMessages',
				$errorMessageStruct->getMessages()
			);
		}

		$formFields = $this->form->getFields();
		foreach ($formFields as $fieldName => $field) {
			if ($field->getType() == 'Hidden') {
				$this->field($fieldName);
			}
		}
	}

	/**
	 * Can only be used if form has been initialized with start().
	 */
	public function end() {
		if (is_null($this->form)) {
			throw new CoreException('HTML form ends but it never started!');
		}
		$this->form = null;
		$this->showFormErrors = null;
		$this->templateEngine->assignPrivate('_currentFormId', null);
		$this->templateEngine->assignPrivate('_currentForm', null);
		$this->templateEngine->assignPrivate('_currentFormErrorMessages', null);
		$this->templateEngine->displayTemplate('form/form.end.tpl');
	}

	/**
	 * Can only be used between start() and end().
	 */
	public function hasField($fieldName) {
		return $this->form->hasField($fieldName);
	}

	/**
	 * Only for CoreFormWithVLF; if used with basic form, it will cause an error.
	 */
	public function getVLFRowCount($vlfName) {
		return $this->form->getVLFRowCount($vlfName);
	}

	/**
	 * Only for CoreFormWithVLF; if used with basic form, it will cause an error.
	 */
	public function getVLFRowIteration($vlfName) {
		$result = array();
		$rowCount = $this->form->getVLFRowCount($vlfName);
		for ($i = 0; $i < $rowCount; $i ++) {
			$result[] = $i;
		}
		return $result;
	}

	protected function showField($field, $variant, $htmlAttributes, $htmlClass) {
		if (is_null($variant)) {
			$variant = strtolower($field->getType());
		}
		$this->templateEngine->assignPrivate(
			'_currentFieldStruct',
			array('field' => $field, 'htmlAttributes' => $htmlAttributes, 'htmlClass' => $htmlClass, 'showErrors' => $this->showFormErrors)
		);
		$this->templateEngine->displayTemplate('form/field.' . $variant . '.tpl');
		$this->templateEngine->assignPrivate('_currentFieldStruct', null);
	}
	
	/**
	 * Can only be used between start() and end().
	 */
	public function field($fieldName, $variant = null, $htmlAttributes = null, $htmlClass = null) {
		if (!$this->form->hasField($fieldName)) {
			throw new CoreException('Tried to display field \'' . $fieldName . '\' which is not defined!');
		}
		$this->showField($this->form->getField($fieldName), $variant, $htmlAttributes, $htmlClass);
	}
	
	/**
	 * Can only be used between start() and end().
	 */
	public function vlfField($vlfName, $index, $fieldName, $variant = null, $htmlAttributes = null, $htmlClass = null) {
		$this->field(
			CoreServices::get('request')->composeFormFieldName(array($vlfName, $index, $fieldName)),
			$variant,
			$htmlAttributes,
			$htmlClass
		);
	}

	/**
	 * Can only be used between start() and end().
	 */
	public function vlfFieldTemplate($vlfName, $index, $fieldName, $variant = null, $htmlAttributes = null, $htmlClass = null) {
		if (!$this->form->hasVLFFieldTemplate($vlfName, $fieldName)) {
			throw new CoreException(
				'Tried to display undefined field template \'' . $fieldName . '\' in VLF \'' . $vlfName . '\'!'
			);
		}
		$field = $this->form->getVLFFieldTemplate($vlfName, $fieldName);
		$field->setName(CoreServices::get('request')->composeFormFieldName(array($vlfName, $index, $fieldName)));
		$this->showField($field, $variant, $htmlAttributes, $htmlClass);
	}

	/**
	 * Returns an array $value => $description.
	 * Can only be used between start() and end().
	 */
	public function getFieldOptions($fieldName) {
		return $this->form->getField($fieldName)->getPossibleValues();
	}

	/**
	 * Can only be used between start() and end().
	 */
	public function fieldOption($fieldName, $optionValue, $variant, $htmlAttributes = null) {
		$this->templateEngine->assignPrivate(
			'_currentFieldOptionStruct',
			array('field' => $this->form->getField($fieldName), 'optionValue' => $optionValue, 'htmlAttributes' => $htmlAttributes)
		);
		$this->templateEngine->displayTemplate('form/fieldoption.' . $variant . '.tpl');
		$this->templateEngine->assignPrivate('_currentFieldOptionStruct', null);
	}

	public function getShowErrors() {
		return $this->showFormErrors;
	}

}
?>