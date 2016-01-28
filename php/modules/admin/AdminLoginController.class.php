<?php
class AdminLoginController extends CMSAbstractController {
	protected $form = null;
	protected $errorMessageContainer = null;
	protected $recordType = 'admin';
	protected $logDAO = null;

	protected function initLayout() {
		$this->layout = new CMSLayoutStartPage($this);
	}

	public function initDAO() {
		$this->logDAO = new LogDAO();
	}

	public function getMenuItemDescription() {
		return null;
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		CoreServices::get('display')->assign('loginForm', $this->form);
		if (!is_null($this->errorMessageContainer) && $this->errorMessageContainer->isAnyErrorMessage()) {
			CoreServices::get('display')->assign('formErrorMessages', $this->errorMessageContainer);
		}
	}

	public function prepareData() {
		parent::prepareData();
		$this->form = new CoreForm('post');
		$this->createFormFields();
		$this->errorMessageContainer = new CoreFormValidationMessageContainer();
		if (CoreServices::get('request')->isSetGet('logout')) {
			$this->logAction('logout');
			CoreServices::get('access')->logout();
			$this->currentUser = null;
		}
		elseif ($this->form->isSubmitted()) {		
			$this->addFormValidators();
			CoreServices::get('access')->logout();	
			$this->currentUser = null;
			$this->form->setFieldValuesFromRequest();
			$this->errorMessageContainer = $this->form->getValidationResults();
			if (!$this->errorMessageContainer->isAnyErrorMessage()) {
				CoreServices::get('access')->login(
					$this->form->getField('adminName')->getValue(),
					$this->form->getField('password')->getValue(),
					$this->errorMessageContainer
				);
				$this->logAction('login');
			}
		}
		if (!$this->form->isSubmitted() || !$this->errorMessageContainer->isAnyErrorMessage()) {
			$adminId = CoreServices::get('access')->getCurrentUserId();
			if ($adminId) {
				$this->currentUser = CoreServices::get('access')->getCurrentUserData();
				$redirectAddress = $this->getFirstAccessiblePage();
				if ($redirectAddress == CoreServices::get('url')->getCurrentPageUrl()) {
					$this->errorMessageContainer->addMessage('youHaveNoPermissions');
				}
				else {
					CoreUtils::redirect($redirectAddress);
				}
			}
		}
	}

	protected function logAction($action) {
		$logRecord = $this->logDAO->getRecordTemplate();
		$logRecord['adminId'] = CoreServices2::getAccess()->getCurrentUserId();
		$logRecord['recordType'] = $this->recordType;
		$logRecord['recordId'] = CoreServices2::getAccess()->getCurrentUserId();
		$logRecord['logTime'] = CoreUtils::getDateTime();
		$logRecord['logIP'] = CoreServices2::getRequest()->getRealIP();
		$logRecord['logOperation'] = $action;
		$this->logDAO->save($logRecord);
	}

	protected function isControllerUsagePermitted() {
		return true;
	}

	protected function createFormFields() {
		$this->form->addField(new CoreFormFieldText('adminName'));
		$this->form->addField(new CoreFormFieldPassword('password'));
		$this->form->addField(new CoreFormFieldSubmit('_login'));
	}

	protected function addFormValidators() {
		$this->form->addValidator(new CoreFormValidatorNotEmpty('adminName'));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('adminName', 40));
		$this->form->addValidator(new CoreFormValidatorNotEmpty('password'));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('password', 40));
	}
	
	protected function getFirstAccessiblePage() {
		$fullMenuStruct = CoreConfig::get('Structure', 'cmsMenu');
		$menuStruct = $fullMenuStruct[$this->currentUser['adminRole']];
		$firstMenuItem = each($menuStruct);
		return CoreServices2::getUrl()->createAddress($firstMenuItem['value']);
	}
}
?>