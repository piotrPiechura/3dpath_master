<?php
/**
 * VLF stands for Variable Length list of Fieldsets.
 * As for now multiselect fields are not allowed in VLFs, which seems reasonable,
 * because multiselects in VLF could mean a huge amount of data to process in
 * a single script execution.
 */
class CoreFormWithVLFs extends CoreForm {
	protected $vlfs = null;
	protected $vlfValidators = null;
	protected $vlfActiveRowCount = null;

	public function __construct($httpMethod = 'post', $actionHTML = null, $formId = null) {
		parent::__construct($httpMethod, $actionHTML, $formId);
		$this->vlfs = array();
		$this->vlfValidators = array();
		$this->vlfActiveRowCount = array();
	}

	/**
	 * If VLF doesn't exist yet, it is created together with '_inactive' checkbox.
	 */
	protected function checkAndInitVLF($vlfName) {
		if (!array_key_exists($vlfName, $this->vlfs)) {
			$this->vlfs[$vlfName] = new CoreFormVariableLengthFieldsetList($vlfName);
			$this->vlfs[$vlfName]->addFieldTemplate(new CoreFormFieldCheckbox('_inactive', 1));
		}
	}

	public function addFieldToVLF($vlfName, $field) {
		$fieldName = $field->getName();
		$this->checkAndInitVLF($vlfName);
		$this->vlfs[$vlfName]->addFieldTemplate($field);
		switch ($field->getType()) {
			case 'Select':
				$this->addValidatorForVLF($vlfName, new CoreFormValidatorStandardSelectCheck($fieldName));
				break;
			case 'Multiselect':
				throw new CoreException(
					'Invalid field \'' . $field->getName() . '\'. Multiselect fields are not allowed in VLFs.'
				);
			case 'HTML':
				$this->addValidatorForVLF($vlfName, new CoreFormValidatorPartialHTMLCheck($fieldName));
				break;
			case 'BBcode':
				$this->addValidatorForVLF($vlfName, new CoreFormValidatorBBCodeCheck($fieldName));
				break;
		}
	}

	public function getVLFRowCount($vlfName) {
		return $this->vlfs[$vlfName]->getRowCount();
	}

	public function getVLFActiveRowCount($vlfName) {
		if (!array_key_exists($vlfName, $this->vlfActiveRowCount)) {
			throw new CoreException('This method can only be invoked after validateVLFActiveRowsAndGetActiveRowCount(). Add CoreFormValidatorVLFRecordListConsistency to the VLF to fix this problem!');
		}
		return $this->vlfActiveRowCount[$vlfName];
	}

	public function validateVLFActiveRowsAndGetActiveRowCount($vlfName, &$oldValues) {
		if (!array_key_exists($vlfName, $this->vlfActiveRowCount)) {
			$vlf = $this->vlfs[$vlfName];
			$rowCount = $vlf->getRowCount();
			$this->vlfActiveRowCount[$vlfName] = 0;
			if ($rowCount) {
				for ($i = 0; $i < $rowCount; $i++) {
					$id = $vlf->getActualField($i, 'id')->getValue();
					if ($id && !array_key_exists($id, $oldValues)) {
						// Jeżeli się tu znajdziemy, to znaczy zazwyczaj, że kilku
						// użytkowników jednocześnie dokonuje zmian.  takiej sytuacji
						// musimy tylko zadbać o spójność b.d.
						$vlf->getActualField($i, 'id')->setValue(null);
						$vlf->getActualField($i, '_inactive')->setValue('1');
						$this->vlfActiveRowCount[$vlfName] = -1;
						return $this->vlfActiveRowCount[$vlfName];
					}
					elseif (!$vlf->getActualField($i, '_inactive')->getValue()) {
						$this->vlfActiveRowCount[$vlfName] ++;
					}
				}
			}
		}
		return $this->vlfActiveRowCount[$vlfName];
	}

	public function hasField($fieldName) {
		$nameSegments = CoreServices::get('request')->getFormFieldNameSegments($fieldName);
		if (sizeof($nameSegments) == 1) {
			return parent::hasField($fieldName);
		}
		elseif (sizeof($nameSegments) == 3) {
			return $this->vlfs[$nameSegments[0]]->hasActualField($nameSegments[1], $nameSegments[2]);
		}
		throw new CoreException('Invalid field name \'' . $fieldName . '\'.');
	}

	public function getField($fieldName) {
		$nameSegments = CoreServices::get('request')->getFormFieldNameSegments($fieldName);
		if (sizeof($nameSegments) == 1) {
			return parent::getField($fieldName);
		}
		elseif (sizeof($nameSegments) == 3) {
			return $this->vlfs[$nameSegments[0]]->getActualField($nameSegments[1], $nameSegments[2]);
		}
		throw new CoreException('Invalid field name \'' . $fieldName . '\'.');
	}

	public function hasVLFFieldTemplate($vlfName, $fieldName) {
		return $this->vlfs[$vlfName]->hasFieldTemplate($fieldName);
	}

	public function getVLFFieldTemplate($vlfName, $fieldName) {
		return $this->vlfs[$vlfName]->getFieldTemplate($fieldName);
	}

	public function setFieldValuesFromRequest() {
		parent::setFieldValuesFromRequest();
		foreach ($this->vlfs as $vlf) {
			$vlf->setValuesFromRequest($this->httpMethod);
		}
	}

	public function setVLFValuesFromRecords($vlfName, &$recordArray = null) {
		if (empty($this->vlfs[$vlfName])) {
			throw new CoreException(
				'Tried to set field values from records in a not initialized VLF \'' . $vlfName . '\'.'
			);
		}
		if (empty($recordArray)) {
			$this->vlfs[$vlfName]->addEmptyRow();
		}
		else {
			$this->vlfs[$vlfName]->getFieldTemplate('_inactive')->setValue(0);
			$this->vlfs[$vlfName]->setValuesFromRecords($recordArray);
			$this->vlfs[$vlfName]->getFieldTemplate('_inactive')->setValue(1);
		}
	}

	/**
	 * $recordList is a list of old values, indexed by identifiers
	 */
	public function getRecordsFromVLF($vlfName, &$templateRecord, &$recordList) {
		$templateRecord['_inactive'] = '';
		if (empty($this->vlfs[$vlfName])) {
			throw new CoreException(
				'Tried to set records from field values of a not initialized VLF \'' . $vlfName . '\'.'
			);
		}
		return $this->vlfs[$vlfName]->getRecordsFromFields($templateRecord, $recordList);
	}
	
	public function addValidatorForVLF($vlfName, $validator) {
		if (empty($this->vlfs[$vlfName])) {
			throw new CoreException(
				'Tried to add validator to a not initialized VLF \'' . $vlfName . '\'.'
			);
		}
		if (!isset($this->vlfValidators[$vlfName])) {
			$this->vlfValidators[$vlfName] = array();
		}
		$validator->setForm($this);
		$this->vlfValidators[$vlfName][] = $validator;
	}
	
	public function getValidationResults() {
		$messageManager = parent::getValidationResults();
		foreach ($this->vlfs as $vlfName => $vlf) {
			$rowCount = $vlf->getRowCount();
			$validatorTemplates = $this->vlfValidators[$vlfName];
			if ($rowCount && !empty($validatorTemplates)) {
				for ($i = 0; $i < $rowCount; $i++) {
					if (!$vlf->getActualField($i, '_inactive')->getValue()) {
						foreach ($validatorTemplates as $validatorTemplate) {
							$actualValidator = clone $validatorTemplate;
							$actualValidator->modifyFieldNamesForVLF($vlfName, $i);
							$actualValidator->validate($messageManager);
						}
					}
				}
			}
		}
		return $messageManager;
	}
}
?>