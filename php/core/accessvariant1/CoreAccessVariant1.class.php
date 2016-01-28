<?php
/**
 * V. 1.2
 */
class CoreAccessVariant1 implements iCoreAccess {
	protected $currentUser = null;
	
	public function __construct() {
		$this->currentUser = CoreServices2::getRequest()->getFromSession('currentUser');
	}

	protected function setLoginErrorMessage($errorMessageContainer, $type, $accountBlocked, $passwordOK) {
		if ($accountBlocked) {
			$errorMessageContainer->addMessage($type . 'AccountTemporarilyBlocked');
		}
		elseif (!$passwordOK) {
			$errorMessageContainer->addMessage('invalidLoginOrPassword');
		}
	}

	public function login($userName, $password, $errorMessageContainer = null) {
		$adminRequired = $this->isAdminRequired();
		$dao = $adminRequired ? new AdminDAO() : new UserDAO();
		$type = $adminRequired ? 'admin' : 'user';
		$record = $dao->getByNameAndPassword($userName, $password);
		$passwordOK = False;
		$accountBlocked = False;
		if ($record['id']) {
			$passwordOK = True;
		}
		else {
			$record = $dao->getByName($userName);
		}
		if ($record['id']) {
			$loginHistoryDAO = new CoreLoginHistoryDAO();
			$failedAttempts = $loginHistoryDAO->getRecentFailedLoginAttempts($type, $record['id']);
			if (
				$failedAttempts['num'] >= CoreConfig::get('Settings', $type . 'MaxLoginAttempts')
				&& $failedAttempts['time'] > date('Y-m-d H:i:s', strtotime('-' . CoreConfig::get('Settings', $type . 'AccountBlockSeconds') . ' seconds'))
			) {
				$accountBlocked = True;
			}
			else {
				$this->updateLoginHistory($loginHistoryDAO, $type, $record['id'], $passwordOK);
			}
		}
		if ($passwordOK && !$accountBlocked) {
			$this->currentUser = $record;
			CoreServices::get('request')->setSession('currentUser', $record);
		}
		elseif (!empty($errorMessageContainer)) {
			$this->setLoginErrorMessage($errorMessageContainer, $type, $accountBlocked, $passwordOK);
		}
		return ($passwordOK && !$accountBlocked);
	}

	protected function updateLoginHistory($loginHistoryDAO, $type, $userId, $loginSuccessful) {
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
		$host =
			isset($_SERVER['REMOTE_HOST'])
			? gethostbyaddr($_SERVER['REMOTE_HOST'])
			: ($ip ? gethostbyaddr($ip) : null); 
		$record = $loginHistoryDAO->getRecordTemplate();
		$record['loginHistorySuccess'] = $loginSuccessful ? 1 : null;
		$record['loginHistoryTime'] = CoreUtils::getDateTime();
		$record['loginHistoryMicrotime'] = CoreUtils::getTimeMicroseconds();
		$record['loginHistoryIP'] = $ip;
		if ($host != $ip) {
			$record['loginHistoryHost'] = $host;
		}
		$record['loginHistoryPHPSessionId'] = CoreServices::get('request')->getSessionId();
		$record[$type . 'Id'] = $userId;
		$loginHistoryDAO->save($record);	
	}

	public function logout() {
		$this->currentUser = null;
		CoreServices::get('request')->setSession('currentUser', null);
	}

	/**
	 * Returns a copy of user's record (or null)
	 */
	public function getCurrentUserData() {
		return $this->currentUser;
	}
	
	public function getCurrentUserId() {
		return (is_null($this->currentUser) ? null : $this->currentUser['id']);
	}

	protected function isAdminRequired() {
		return (CoreServices::get('modules')->getController()->getSessionName() == 'CMSSession');
	}
}
?>