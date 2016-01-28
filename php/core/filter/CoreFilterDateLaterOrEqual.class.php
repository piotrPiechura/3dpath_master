<?php
class CoreFilterDateLaterOrEqual extends CoreFilterAbstract {
	public function getConditionType() {
		return 'moreThanOrEqual';
	}

	public function getFieldType() {
		return 'datepicker';
	}
}
?>