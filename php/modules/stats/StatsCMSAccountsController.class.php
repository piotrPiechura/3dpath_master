<?php
// @TODO: Nie ma powodu żeby to dziedziczyło po CMSEdit! Należałoby to zrobić inaczej!
class StatsCMSAccountsController extends CMSAbstractControllerEdit {
	protected $stats = null;

	// @TODO: powinien raczej być atrybut "userDAO"
	protected function getDAOClass() {
		return 'UserDAO';
	}

	public function getMenuItemDescription() {
		return 'Stats';
	}

	protected function initMultiselectRelations() {}

	protected function initActions() {
		$this->availableActions = array('GetStats');
	}

	protected function createFormFields() {
		parent::createFormFields();
		$this->form->addField(new CoreFormFieldText('startRegisterTime'));
		$this->form->addField(new CoreFormFieldText('endRegisterTime'));
	}

	protected function addFormValidators() {
		$this->form->addValidator(new CoreFormValidatorDateSimpleFormat('startRegisterTime'));
		$this->form->addValidator(new CoreFormValidatorDateSimpleFormat('endRegisterTime'));
	}

	protected function setSpecialRecordFieldsBeforeSave() {}

	protected function afterSave() {}

	protected function runRequestHandler() {
		$action = $this->getAction();
		switch ($action) {
			case 'GetStats':
				$this->handleGetStatsRequest();
				break;
		}
	}

	protected function handleGetStatsRequest() {
		$this->getValidationResults();
		if (!$this->errorMessageContainer->isAnyErrorMessage()) {
			$startDate = $this->form->getField('startRegisterTime')->getValue();
			$endDate = $this->form->getField('endRegisterTime')->getValue();
			$newAccounts = $this->dao->getActivatedAccountsCount($startDate, $endDate);
			$deletedAccounts = $this->dao->getDeletedAccountsCount($startDate, $endDate);
			$this->stats['newAccounts'] = $newAccounts;
			$this->stats['deletedAccounts'] = $deletedAccounts;
		}
	}

	protected function prepareAdditionalData() {
		$newAccounts = $this->dao->getActivatedAccountsCount();
		$deletedAccounts = $this->dao->getDeletedAccountsCount();
		$this->stats['newAccounts'] = $newAccounts;
		$this->stats['deletedAccounts'] = $deletedAccounts;
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices::get('display');
		$display->assign('stats', $this->stats);
	}

}
?>