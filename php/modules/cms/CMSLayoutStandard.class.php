<?php 
class CMSLayoutStandard extends CMSLayoutAbstract {
	public function assignDisplayVariables() {
		$display = CoreServices::get('display');
		$menuStruct = $this->controller->getMenuStruct();
		if ($menuStruct) {
			$display->assign('mainMenu', $menuStruct);
		}
		$display->assign(
			'currentMenuItemDescription',
			$this->controller->getMenuItemDescription()
		);
	}

	public function getBaseTemplate() {
		return 'cms';
	}
}
?>