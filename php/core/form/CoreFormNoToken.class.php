<?php
class CoreFormNoToken extends CoreForm {
	protected function initTokenManagerForPostRequest() {
		$this->tokenManager = new CoreFormTokenManagerDummy();
	} 
}
?>