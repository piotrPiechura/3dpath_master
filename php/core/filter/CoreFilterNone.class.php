<?php

class CoreFilterNone extends CoreFilterAbstract {

	public function getConditionType() {
		return 'none';
	}

	public function createField($fieldName) {
		return null;
	}

}

?>