<?php
class CoreFormVariableLengthFieldsetList {
	protected $name = null;
	protected $fieldTemplates = null;
	protected $actualFields = null;
	
	public function __construct($name) {
		$this->name = $name;
		$this->fieldTemplates = array();
		$this->actualFields = array();
	}
	
	public function hasFieldTemplate($fieldTemplateName) {
		return (array_key_exists($fieldTemplateName, $this->fieldTemplates));
	}
	
	public function getFieldTemplate($fieldTemplateName) {
		return $this->fieldTemplates[$fieldTemplateName];
	}
	
	public function addFieldTemplate($field) {
		$fieldName = $field->getName();
		if ($this->hasFieldTemplate($fieldName)) {
			throw new CoreException(
				'Tried to add field \'' . $fieldName . '\' twice in field set array \'' . $vlfName . '\'.'
			);
		}
		$this->fieldTemplates[$fieldName] = $field;
	}
	
	protected function addRow($index) {
		$this->actualFields[$index] = array();
		foreach ($this->fieldTemplates as $fieldTemplateName => $fieldTemplate) {
			$actualField = clone $fieldTemplate;
			// name of the field is changed but caption remains the same as in template!
			$actualField->setName(
				CoreServices::get('request')->composeFormFieldName(array($this->name, $index, $fieldTemplateName))
			);
			$this->actualFields[$index][$fieldTemplateName] = $actualField;
		}
	}

	public function setValuesFromRequest($httpMethod) {
		$value = CoreServices::get('request')->getFromRequest($this->name, $httpMethod);
		// It is assumed that at least one row must be submitted,
		// it may be inactive, but it does exist.
		if (!is_array($value)) {
			throw new CoreException(
				'Wrong request for VLF \'' . $this->name . '\'. Possibly HTML template lacks some fields'
			);
		}
		// Be careful! Indices in array $value could be spoilt on client side
		// (simply by adding and removing rows in JavaScript), so they can't be used.
		$index = 0;
		foreach ($value as $row) {
			if (!is_array($row)) {
				throw new CoreException('Wrong request for VLF \'' . $this->name . '\'.');
			}
			$this->addRow($index);
			foreach ($this->actualFields[$index] as $fieldName => $field) {
				$field->setValueFromRequest($httpMethod);
			}
			$index ++;
		}
	}

	public function addEmptyRow() {
		$this->addRow(0);
	}

	public function setValuesFromRecords(&$recordArray = null) {
		$i = 0;
		foreach ($recordArray as $record) {
			$this->addRow($i);
			foreach ($record as $fieldName => $value) {
				if ($this->hasFieldTemplate($fieldName)) {
					$this->actualFields[$i][$fieldName]->setValue($value);
				}
			}
			if (array_key_exists('_inactive', $record)) {
				$this->actualFields[$i]['_inactive']->setValue($record['_inactive']);
			}
			$i += 1;
		}
	}

	public function getRecordsFromFields(&$templateRecord, &$recordList) {
		$result = array();
		foreach ($this->actualFields as $index => $fieldSet) {
			$record = null;
			if (!array_key_exists('id', $fieldSet)) {
				throw new CoreException('There is no \'id\' field in the VLF!');
			}
			$id = $fieldSet['id']->getValue();
			if (!empty($id)) {
				if (is_array($recordList) && array_key_exists($id, $recordList)) {
					$record = $recordList[$id];
				}
				else {
					// Unknown and invalid id, it might be some problem or hacking attempt
					// - just skip this record.
					continue;
				}
			}
			if (is_null($record)) {
				$record = $templateRecord; // copying! If it was a record, keyword 'clone' would be used here.
			}
			foreach ($fieldSet as $templateFieldName => $field) {
				if (array_key_exists($templateFieldName, $templateRecord)) {
					$record[$templateFieldName] = $field->getValue();
				}
			}
			$result[] = $record;
		}
		return $result;
	}
	
	public function getRowCount() {
		return sizeof($this->actualFields);
	}
	
	public function hasActualField($index, $templateName) {
		return (
			array_key_exists($index, $this->actualFields)
			&& array_key_exists($templateName, $this->actualFields[$index])
		);
	}

	public function getActualField($index, $templateName) {
		return $this->actualFields[$index][$templateName];
	}
}
?>