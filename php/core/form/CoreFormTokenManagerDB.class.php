<?php
class CoreFormTokenManagerDB {
	protected $maxAgeMiliseconds = null;

	public function __construct() {
		$this->maxAgeMiliseconds = 1000 * 60 * 5;
	}

	public function createToken() {
		return mt_rand();
	}

	/**
	 * Returns boolean value, not null.
	 */
	public function isValidToken($token) {
		if (empty($token)) {
			return False;
		}
		$sessionId = CoreServices::get('request')->getSessionId();
		$timeMiliseconds = CoreUtils::getTimeMiliseconds();
		$db = CoreServices::get('db');
		$db->change($this->deleteOldTokensSQL($timeMiliseconds));
		$db->change($this->insertTokenSQL($token, $sessionId, $timeMiliseconds));
		$row = $db->getRow($this->checkTokenSQL($token, $sessionId));
		return ($row['num'] == '1');
	}

	protected function getTableName() {
		return '_token';
	}

	protected function deleteOldTokensSQL($currentTimeMiliseconds) {
		$limitTime = $currentTimeMiliseconds - $this->maxAgeMiliseconds;
		return 'DELETE FROM ' . $this->getTableName() . ' WHERE timeMiliseconds < ' . $limitTime;
	}

	protected function insertTokenSQL($token, $sessionId, $timeMiliseconds) {
		$db = CoreServices::get('db');
		return '
			INSERT
			INTO ' . $this->getTableName() . ' (id, token, sessionId, timeMiliseconds)
			VALUES (
				NULL,
				' . $db->prepareInputValue($token) . ',
				' . $db->prepareInputValue($sessionId) . ',
				' . $db->prepareInputValue($timeMiliseconds) . '
			)';
	}

	protected function checkTokenSQL($token, $sessionId) {
		$db = CoreServices::get('db');
		return '
			SELECT COUNT(*) AS num
			FROM ' . $this->getTableName() . '
			WHERE sessionId = ' . $db->prepareInputValue($sessionId) . ' AND token = ' . $db->prepareInputValue($token);
	}
}
?>