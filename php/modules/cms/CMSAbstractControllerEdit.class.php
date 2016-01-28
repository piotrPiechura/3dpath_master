<?php
abstract class CMSAbstractControllerEdit extends CMSAbstractController {
	protected $record = null;
	protected $recordOldValues = null;
	/**
	 * @var CoreForm
	 */
	protected $form = null;
	protected $availableActions = null;
	protected $redirectAddress = null;
        
        protected $pagePath = null;
	/**
	 * @var CoreFormValidationMessageContainer
	 */
	protected $errorMessageContainer = null;

	public function prepareData() {
		parent::prepareData();
		$this->initForm();
		if ($this->form->isSubmitted()) {
			CoreServices::get('db')->transactionStart();
			$this->initRecord();
			$this->prepareAdditionalData();
			$this->initActions();
                        $this->getMenuData();
			$this->createFormFields();
                        $this->setFormFieldValuesFromRequest();
			$this->addFormValidators();
			
			$this->handleRequest();
			CoreServices::get('db')->transactionCommit();
		}
		else {
			$this->initRecord();
			$this->prepareAdditionalData();
			$this->initActions();
                        $this->getMenuData();
			$this->createFormFields();
			$this->setFormValuesFromRecord();
		}
		if (!is_null($this->redirectAddress)) {
			CoreUtils::redirect($this->redirectAddress);
		}
	}

	protected function getRecordType() {
		$className = $this->getDAOClass();
		return strtolower(substr($className, 0, 1)) . substr($className, 1, -3);
	}

	protected function initDAO() {
		parent::initDAO();
		$this->logDAO = new LogDAO();
	}

         protected function getMenuData(){
                if(!empty($this->record['id'])){
                    $this->pagePath = $this->dao->getRecordPath($this->record['id']);
                }
                else{
                    $daoClass =  $this->getDAOClass();
                    if ($daoClass == 'CompanyDAO'){
                        $this->pagePath['companyName'] = 'New Comapny';
                        $this->pagePath['comanyId'] = 0;  
                    }
                    elseif ($daoClass == 'ProjectDAO'){
                         
                        $comapanyId = CoreServices2::getRequest()->getFromGet('comp');
                        if (!empty($comapanyId)){
                            $companyDAO = new CompanyDAO();
                            $this->pagePath = $companyDAO->getRecordPath($comapanyId);
                        } 
                        $this->pagePath['projectName'] = 'New Project';
                        $this->pagePath['projectId'] = 0;
                    }
                    elseif ($daoClass == 'SiteDAO'){
                        $projectId = CoreServices2::getRequest()->getFromGet('proj');
                        if (!empty($projectId)){
                            $projectDAO = new ProjectDAO();
                            $this->pagePath = $projectDAO->getRecordPath($projectId);
                        }
                        $this->pagePath['siteName'] = 'New Site';
                        $this->pagePath['siteId'] = 0;
                    }
                    elseif ($daoClass == 'WellDAO'){   
                        $siteId = CoreServices2::getRequest()->getFromGet('site');
                        if (!empty($siteId)){
                            $siteDAO = new SiteDAO();
                            $this->pagePath = $siteDAO->getRecordPath($siteId);
                        }
                        $this->pagePath['wellName'] = 'New Well';
                        $this->pagePath['wellId'] = 0; 
                    }
                }
        }
        
	protected function initRecord() {
		$id = CoreServices::get('request')->getFromRequest('id');
		if (!empty($id)) {
			$this->record = $this->dao->getRecordById($id);
			if (!$this->record['id']) {
				CoreServices::get('db')->transactionCommit();
				CoreUtils::redirect($this->getListPageAddress());
			}
		}
		else {
			$this->record = $this->dao->getRecordTemplate();
		}
		$this->initMultiselectRelations();
		$this->recordOldValues = $this->record; // clone!
		$this->checkUserPermissionsForRecord();
	}

	protected function checkUserPermissionsForRecord() {}

	abstract protected function initMultiselectRelations();

	protected function initForm() {
		$this->form = new CoreForm('post');
	}

	/**
	 * Aquire any additional data that should be shown on that page.
	 */
	protected function prepareAdditionalData() {}

	/**
	 * In basic situation we assume that enyone who can watch the data, can also change it.
	 * This is likely to change in subclasses.
	 * Action 'DeleteAll' is disabled by default.
	 */
	protected function initActions() {
		$this->availableActions = array();
		// $this->availableActions[] = 'None';
		$this->availableActions[] = 'Save';
		if ($this->record['id'] && !$this->dao->hasRelatedRecords($this->record)) {
			$this->availableActions[] = 'Delete';
		}
	}

	protected function createFormFields() {
		if (!empty($this->availableActions)) {
			$this->form->addField(new CoreFormFieldSubmitSet(
				'_action',
				$this->availableActions[0],
				$this->availableActions
			));
		}
		$this->form->addField(new CoreFormFieldHidden('id'));
	}

	abstract protected function addFormValidators();

	protected function setFormFieldValuesFromRequest() {
		$this->form->setFieldValuesFromRequest();
	}

	/**
	 * Sometimes form fields can be different than DAO fields; in such cases this method
	 * must be overwritten.
	 */
	protected function setFormValuesFromRecord() {
		// @TODO: nie wiadomo czy ten warunek czegoś nie popsuje!!!
		if (!empty($this->record['id'])) {
			$this->form->setFieldValuesFromRecord($this->record);
		}
	}

	/**
	 * Sometimes form fields can be different than DAO fields; in such cases this method
	 * must be overwritten.
	 */
	protected function setRecordValuesFromForm() {
		$this->form->setRecordValuesFromFields($this->record);
	}

	protected function setSpecialRecordFieldsBeforeSave() {}

	protected function afterSave() {}

	protected function getValidationResults() {
		$this->errorMessageContainer = $this->form->getValidationResults();
	}

	protected function saveWithStatusChange($statusFieldName, $newStatus, $successMessage) {
		$this->getValidationResults();
		if (!$this->errorMessageContainer->isAnyErrorMessage()) {
			$this->setRecordValuesFromForm();
			$this->setSpecialRecordFieldsBeforeSave();
			if (!empty($statusFieldName) && !empty($newStatus)) {
				$this->record[$statusFieldName] = $newStatus;
			}
			$this->dao->save($this->record);
			$this->afterSave();
			$this->setRedirectAddress(CoreServices::get('url')->getCurrentPageUrl(
				'id', $this->record['id'],
				'_sm', $successMessage
			));
		}
	}

	protected function handleSaveRequest() {
		$this->saveWithStatusChange(null, null, 'Save');
	}

	/**
	 * This operation should only be used if there are no records related to $this->record.
	 * This operation should only be enabled if handleDeleteAllRequest() is disabled
	 * and vice versa.
	 */
	protected function handleDeleteRequest() {
		$this->dao->delete($this->record);
		$this->setRedirectAddress(
			$this->getListPageAddress(array('_sm', 'Delete'))
		);
	}

	/**
	 * This operation should be used if there are some records related to $this->record
	 * This operation should only be enabled if handleDeleteRequest() is disabled
	 * and vice versa.
	 */
	protected function handleDeleteAllRequest() {
		$relatedRecords = $this->dao->getRelatedRecords($this->record);
		foreach ($relatedRecords as $daoClass => $records) {
			$dao = new $daoClass();
			foreach ($records as $record) {
				$dao->delete($record);
			}
		}
		$this->dao->delete($this->record);
		$this->setRedirectAddress(
			$this->getListPageAddress(array('_sm', 'DeleteAll'))
		);
	}

	protected function getAction() {
		return $this->form->getField('_action')->getValue();
	}

	/**
	 * Check what kind of action should be executed on DAO and execute it. This function doesn't
	 * check if the action is permitted for the current user with submitted data.
	 * The action should be valid, because it is one of the possible actions passed to the
	 * constructor of the '_action' field. If there are some data-related limitations for
	 * possible actions, a validator can be added to the '_action' field.
	 */
	protected function handleRequest() {
		$action = $this->getAction();
		if (
			empty($action)
			|| !in_array($action, $this->availableActions)
			// @TODO: papa
			// || $action == 'DummyDelete'
		) {
			$this->errorMessageContainer = new CoreFormValidationMessageContainer();
			$this->errorMessageContainer->addMessage('invalidAction');
		}
		else {
			$this->runRequestHandler();
			if (
				empty($this->errorMessageContainer)
				|| !$this->errorMessageContainer->isAnyErrorMessage()
			) {
				$this->logAction($action);
			}
		}
	}

	protected function logAction($action) {
		$recordType = $this->getRecordType();
		if(!empty($recordType) && !empty($this->record['id'])) {
			$logRecord = $this->logDAO->getRecordTemplate();
			$logRecord['adminId'] = CoreServices2::getAccess()->getCurrentUserId();
			$logRecord['recordType'] = $recordType;
			// $this->recordOldValues['id'] może być puste, $this->record['id'] nie może.
			$logRecord['recordId'] = $this->record['id'];
			$logRecord['logTime'] = CoreUtils::getDateTime();
			$logRecord['logIP'] = CoreServices2::getRequest()->getRealIP();
			switch($action) {
				case 'Save':
				case 'ChangeWithdrawDate':
					if (empty($this->recordOldValues['id'])) {
						$logRecord['logOperation'] = 'create';
					}
					else {
						$logRecord['logOperation'] = 'modify';
					}
					$logRecord['recordId'] = $this->record['id'];
					break;
				case 'DeleteAll':
					$logRecord['logOperation'] = 'delete';
					break;
				default:
					$logRecord['logOperation'] = strtolower($action);
					break;
			}
			$this->logDAO->save($logRecord);
		}
	}

	protected function runRequestHandler() {
		$action = $this->getAction();
		switch ($action) {
			case 'Save':
				$this->handleSaveRequest();
				break;
			case 'Delete':
				$this->handleDeleteRequest();
				break;
			case 'DeleteAll':
				$this->handleDeleteAllRequest();
				break;
		}
	}

	protected function getListPageAddress($args = null) {
		if (is_null($args)) {
			$args = array();
		} 
		$args = array_merge(array('_m', CoreServices::get('modules')->getControllerModule(), '_o', 'CMSList'), $args);
		return CoreServices::get('url')->createAddress($args);
	}

	protected function setRedirectAddress($address) {
		$this->redirectAddress = $address;
	}

	/**
	 * Likely to be overwritten if initAdditions() is not empty.
	 */
	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices::get('display');
		$display->assign('mainForm', $this->form);
		$display->assign('recordOldValues', $this->recordOldValues);
                $display->assign('pagePath', $this->pagePath);
		if ($this->record['id']) {
			$display->assign('recordId', $this->record['id']);
		}
		if (!is_null($this->errorMessageContainer) && $this->errorMessageContainer->isAnyErrorMessage()) {
			$display->assign('formErrorMessages', $this->errorMessageContainer);
		}
	}
}
?>