<?php
class StatsCMSListController extends CMSAbstractController {

	public function getMenuItemDescription() {
		return 'Stats';
	}

	protected function getDAOClass() {
		return 'StatsSimpleDAO';
	}

}
?>
