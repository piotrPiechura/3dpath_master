<?php
class CoreFormTokenManagerDummy {
	public function __construct() {}

	public function createToken() {
		return 1;
	}

	/**
	 * Returns boolean value, not null.
	 */
	public function isValidToken($token) {
		return $token == 1;
	}
}
?>