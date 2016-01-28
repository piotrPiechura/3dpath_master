<?php
class CoreFilterDateEarlierOrEqual extends CoreFilterAbstract {
	public function getConditionType() {
		return 'lessThanOrEqual';
	}

	public function getFieldType() {
		return 'datepicker';
	}
}
?>