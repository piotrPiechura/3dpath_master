<?php
class SubpageCMSListController extends CMSAbstractController {
	// OK
	protected $recordList = null;
	// OK
	protected $form = null;
	// OK
	protected $availableActions = null;
	// OK
	protected $redirectAddress = null;
	// OK
	protected $errorMessageContainer = null;
	// OK
	protected $rootId = null;

	// OK
	public function getMenuItemDescription() {
		return 'Subpage';
	}

	// OK
	protected function getDAOClass() {
		return 'SubpageDAO';
	}

	protected function isControllerUsagePermitted() {
		return (
			!empty($this->currentUser['id'])
			&& $this->currentUser['adminRole'] >= $this->adminRoles['adminRoleSuperadmin']
		);
	}

	// OK
	public function prepareData() {
		parent::prepareData();
		$this->initDAO();
		$this->form = new CoreForm('post');
		if ($this->form->isSubmitted()) {
			CoreServices::get('db')->transactionStart();
			$this->initRecordList();
			$this->initActions();
			$this->createFormFields();
			$this->addFormValidators();
			$this->form->setFieldValuesFromRequest();
			$this->handleRequest();
			CoreServices::get('db')->transactionCommit();
		}		
		else {
			$this->initRecordList();
			$this->initActions();
			$this->createFormFields();
		}
		if (!is_null($this->redirectAddress)) {
			CoreUtils::redirect($this->redirectAddress);
		}		
	}

	// OK
	protected function initRecordList() {
		$this->recordList = $this->dao->getFullIndexedList();
		$this->rootId = $this->dao->getRootId();
		if (empty($this->recordList[$this->rootId]['id'])) {
			throw new SubpageUsageException('Tree root does not exist!');
		}

	}

	// OK
	protected function initActions() {
		$this->availableActions = array('Save');
	}

	// OK
	protected function createFormFields() {
		$this->form->addField(new CoreFormFieldSubmitSet(
			'_action',
			'Save',
			$this->availableActions
		));
		$this->form->addField(new CoreFormFieldHidden('menuStructure'));
	}
	
	// OK
	protected function addFormValidators() {
		$this->form->addValidator(new SubpageMenuStructureStringValidator(
			'menuStructure',
			$this->recordList,
			$this->rootId
		));
	}
	
	protected function handleSaveRequest() {
		$this->errorMessageContainer = $this->form->getValidationResults();
		if (!$this->errorMessageContainer->isAnyErrorMessage()) {
			$fieldValue = $this->form->getField('menuStructure')->getValue();
			if (!empty($fieldValue)) {
				parse_str(str_replace('&amp;', '&', $fieldValue), $newStructure);
				$newStructure['id'] = $this->rootId;
				$tree = SubpageUpdateLRTreeNode::getTree($newStructure);
				$tree->updateRecords($this->recordList);
				foreach ($this->recordList as $record) {
					$this->dao->updateNodePosition($record);
				}
			}
			$this->redirectAddress =
				CoreServices::get('url')->getCurrentPageUrl('_sm', 'Save');
		}
	}

	protected function handleRequest() {
		$action = $this->form->getField('_action')->getValue();
		if (empty($action) || !in_array($action, $this->availableActions)) {
			$this->errorMessageContainer = new CoreFormValidationMessageContainer();
			$this->errorMessageContainer->addMessage('invalidAction');
		}	
		else { // the only possible action is 'Save'
			$this->handleSaveRequest();
		}
	}

	/**
	 * Likely to be overwritten if initAdditions() is not empty.
	 */
	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices2::getDisplay();
		$children = $this->recordList;
		unset($children[$this->rootId]);
		$display->assign('recordList', $children);
		$display->assign('rootRecord', $this->recordList[$this->rootId]);
		
		$display->assign('mainForm', $this->form);

		if (!is_null($this->errorMessageContainer) && $this->errorMessageContainer->isAnyErrorMessage()) {
			$display->assign('formErrorMessages', $this->errorMessageContainer);
		}
		$display->assign('addButtonOff', 1);
	}
}
?>