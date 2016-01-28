<?php
class CMSLayoutAjax extends CMSLayoutAbstract {
	public function assignDisplayVariables() {
		$display = CoreServices::get('display');
	}

	public function getBaseTemplate() {
		return 'cmsajax';
	}
}
?>