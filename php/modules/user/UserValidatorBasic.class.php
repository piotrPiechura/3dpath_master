<?php
class UserValidatorBasic extends CoreFormAbstractValidator {
	protected $userRecord = null;
	protected $validators = null;
	
	public function __construct(&$userRecord) {
		$this->userRecord = $userRecord;
	}
	
	protected function initValidators(&$form) {
		$this->validators = array();
		if ($form->hasField('userEmail')) {
			$this->validators[] = new CoreFormValidatorNotEmpty('userEmail');
			$this->validators[] = new CoreFormValidatorMaxTextLength('userEmail', 200);
			$this->validators[] = new UserEmailValidator('userEmail', 'id', $this->userRecord['userEmail']);
		}
		$this->validators[] = new CoreFormValidatorFieldsEqual(array('userPassword', 'userPasswordConfirm'));
		$this->validators[] = new CoreFormValidatorOldValueIfEmpty('userPassword', 'id', $this->userRecord['userPassword']);
		$this->validators[] = new CoreFormValidatorPasswordPattern('userPassword');
		$this->validators[] = new CoreFormValidatorMaxTextLength('userNick', 40);
		if (!empty($this->userRecord['id'])) {
			$this->validators[] = new CoreFormValidatorMaxTextLength('userCompanyName', 200);
			$this->validators[] = new CoreFormValidatorMaxTextLength('userFirstName', 100);
			$this->validators[] = new CoreFormValidatorMaxTextLength('userSurname', 100);
			
			$this->validators[] = new CoreFormValidatorMaxTextLength('userAddressStreet', 100);
			$this->validators[] = new CoreFormValidatorMaxTextLength('userAddressCity', 100);
			$this->validators[] = new CoreFormValidatorMaxTextLength('userAddressRegion', 100);

			$this->validators[] = new CoreFormValidatorMaxTextLength('userZipCode', 20);
			$this->validators[] = new CoreFormValidatorVATNumber('userTaxIdentifier', 'countryId');
		}
	}

	public function setForm($form) {
		$this->initValidators($form);
		for ($i = 0; $i < sizeof($this->validators); $i++) {
			$this->validators[$i]->setForm($form);
		}
	}
	
	public function validate($messageManager) {
		for ($i = 0; $i < sizeof($this->validators); $i++) {
			$this->validators[$i]->validate($messageManager);
		}
		return (!$messageManager->isAnyErrorMessage());
	}
	
	public function modifyFieldNamesForVLF($vlfName, $index) {
		for ($i = 0; $i < sizeof($this->validators); $i++) {
			$this->validators[$i]->modifyFieldNamesForVLF($vlfName, $index);
		}
	}
}