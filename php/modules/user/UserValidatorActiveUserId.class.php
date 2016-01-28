<?php
/**
 *
 */
class UserValidatorActiveUserId extends CoreFormAbstractValidator {
	public function validate($messageManager) {
		$userId = $this->form->getField('userId')->getValue();
		if (!empty($userId)) {
			$userDAO = new UserDAO();
			$user = $userDAO->getRecordById($userId);
			if (empty($user['id'])) {
				$messageManager->addMessage('errorInvalidUserId');
			}
			if ($user['userState'] != 'active') {
				$messageManager->addMessage('errorUserInactive');
			}
		}
	}

	public function modifyFieldNamesForVLF($vlfName, $index) {
		throw new CoreFormExceptionUsage('Method not implemented');
	}
}
?>