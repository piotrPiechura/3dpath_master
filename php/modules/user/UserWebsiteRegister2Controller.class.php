<?php
class UserWebsiteRegister2Controller extends WebsiteAbstractControllerThickboxLayout {
	protected function isUsagePermitted() {
		return $this->isUserLogged();
	}
}
?>
