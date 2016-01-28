<?php
class CoreMailPhpMailerTest extends CoreMailAbstractTest {
	protected function getMail() {
		return new CoreMailPhpMailer();
	}
}
?>