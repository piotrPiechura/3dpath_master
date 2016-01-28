<?php
class UserCreditsLogic {
	/**
	 * @var UserDAO
	 */
	protected $userDAO = null;
	/**
	 * @var CreditsPackageDAO
	 */
	protected $creditsPackageDAO = null;

	public function  __construct() {
		$this->userDAO = new UserDAO();
		$this->creditsPackageDAO = new CreditsPackageDAO();
	}

	protected function changeUserCredits(&$user, $change, $newPackageId = null) {
		$user = $this->userDAO->getRecordById($user['id']);
		// nie chcemy nulla...
		if (empty($user['userCredits'])) {
			$user['userCredits'] = 0;
		}
		$userOld = $user; // kopia, nie referencja
		$package = $this->creditsPackageDAO->getLastActiveCreditsPackage(
			$user['id'],
			$newPackageId
		);
		if (empty($package['id'])) {
			$user['userCredits'] = 0;
		}
		if ($user['userCredits'] + $change < 0) {
			if ($userOld != $user) {
				$this->userDAO->save($user);
			}
			return false;
		}
		$user['userCredits'] = $user['userCredits'] + $change;
		$this->userDAO->save($user);
		return true;
	}

	/**
	 * @param array $user
	 * Ta funkcja zmienia dane w b.d. oraz w tablicy będącej parametrem
	 * ale nie przelogowuje użytkownika!
	 */
	public function updateUserCredits(&$user) {
		$this->changeUserCredits($user, 0);
	}

	/**
	 * @param array $user
	 * @param int $newCredits
	 * Ta funkcja zmienia dane w b.d. oraz w tablicy będącej parametrem
	 * ale nie przelogowuje użytkownika!
	 * Jeżeli funkcja zostanie wywołana PO aktywacji wpłaty związanej z nowym pakietem,
	 * to trzeba podać id nowego pakietu, wpp należy podać null.
	 */
	public function addUserCredits(&$user, $newCredits, $newPackageId) {
		$this->changeUserCredits($user, $newCredits, $newPackageId);
	}

	/**
	 * @param array $user
	 * @param int $removedCredits
	 * @return true jeżeli użytkownik posiadał żądaną liczbę kredytów, wpp. false.
	 * Ta funkcja zmienia dane w b.d. oraz w tablicy będącej parametrem
	 * ale nie przelogowuje użytkownika!
	 */
	public function checkAndRemoveUserCredits(&$user, $removedCredits) {
		return $this->changeUserCredits($user, -$removedCredits);
	}
}
?>
