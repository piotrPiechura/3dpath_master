<?php

class CoreFilterBetween extends CoreFilterAbstract {

	protected $minValueFieldName = null;
	protected $maxValueFieldName = null;

	protected $datepicker = null;

	protected $minValue = null;
	protected $maxValue = null;

	public function __construct($baseFieldName, $sortable = true, $datepicker = false) {
		parent::__construct($sortable);
		$this->minValueFieldName = $baseFieldName . '_min';
		$this->maxValueFieldName = $baseFieldName . '_max';
		$this->datepicker = $datepicker;
	}

	public function getMinValueFieldName() {
		return $this->minValueFieldName;
	}

	public function getMaxValueFieldName() {
		return $this->maxValueFieldName;
	}

	public function getValue() {
		$values = array(
			'min' => null,
			'max' => null
		);
		if(!empty($this->minValue)) {
			$minParts = explode(' ', $this->minValue);
			if(!empty($minParts[0]) && count($minParts) < 2 && $this->datepicker) {
				$values['min'] = $this->minValue . ' 00:00:00';
			} else {
				$values['min'] = $this->minValue;
			}
		}
		if(!empty($this->maxValue)) {
			$maxParts = explode(' ', $this->maxValue);
			if(!empty($maxParts[0]) && count($maxParts) < 2 && $this->datepicker) {
				$values['max'] = $this->maxValue . ' 23:59:59';
			} else {
				$values['max'] = $this->maxValue;
			}
		}
		return $values;
	}

	public function setValue($values) {
		if(!empty($values['min'])) {
			$minParts = explode(' ', $values['min']);
			if(!empty($minParts[0]) && count($minParts) < 2 && $this->datepicker) {
				$this->minValue = $values['min'] . ' 00:00:00';
			} else {
				$this->minValue = $values['min'];
			}
		}
		if(!empty($values['max'])) {
			$maxParts = explode(' ', $values['max']);
			if(!empty($maxParts[0]) && count($maxParts) < 2 && $this->datepicker) {
				$this->maxValue = $values['max'] . ' 23:59:59';
			} else {
				$this->maxValue = $values['max'];
			}
		}
	}

	public function getConditionType() {
		return 'between';
	}
	
	public function getFieldType() {
		if($this->datepicker) {
			return 'datepicker';
		}
		return 'text';
	}

	public function createField($fieldName) {
		$fields = array();
		$fields[] = new CoreFormFieldText($this->minValueFieldName);
		$fields[] = new CoreFormFieldText($this->maxValueFieldName);
		return $fields;
	}

}

?>