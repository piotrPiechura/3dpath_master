<?php
class UserCMSListForDeletionController extends CMSAbstractController {
	protected $deletionForm = null;
	protected $searchForm = null;
	protected $recordList = null;

	protected function getDAOClass() {
		return 'UserDAO';
	}

	public function getMenuItemDescription() {
		return 'UserDeletion';
	}

	public function prepareData() {
		parent::prepareData();
		$this->initDAO();
		$this->initSearchForm();
		if ($this->searchForm->isSubmitted()) {
			$this->searchForm->setFieldValuesFromRequest();
		}
		$this->initRecordList();
		$this->initDeletionForm();
		if ($this->deletionForm->isSubmitted()) {
			$this->deletionForm->setFieldValuesFromRequest();
			$this->handleDeleteRequest();
			CoreUtils::redirect(CoreServices::get('url')->getCurrentPageUrl('_sm', 'MassDelete'));
		}
	}

	//protected function isControllerUsagePermitted() {
	//	return (
	//		!empty($this->currentUser['id'])
	//		&& ($this->currentUser['adminRole'] >= $this->adminRoles['adminRoleSuperadmin'])
	//	);
	//}

	protected function initSearchForm() {
		$this->searchForm = new CoreForm('get', null, 'searchForm');
		$this->searchForm->addField(new CoreFormFieldText('dateFrom'));
		$this->searchForm->addField(new CoreFormFieldText('dateTo'));
		// @TODO: może jeszcze coś? może w ogóle co innego?
	}

	protected function initDeletionForm() {
		$this->deletionForm = new CoreForm('post', null, 'deletionForm');
		$options = array();
		foreach ($this->recordList as $record) {
			$options[$record['id']] = $record['id'];
		}
		$this->deletionForm->addField(new CoreFormFieldMultiselect(
			'_delete',
			null,
			$options
		));
	}

	protected function initRecordList() {
		$this->recordList = $this->dao->getListForDeletion(
			$this->searchForm->getField('dateFrom')->getValue(),
			$this->searchForm->getField('dateTo')->getValue()
		);
	}

	protected function handleDeleteRequest() {
		$ids = $this->deletionForm->getField('_delete')->getValue();
		if (empty($ids)) {
			return;
		}
		CoreServices2::getDB()->transactionStart();
		foreach ($this->recordList as $record) {
			if (in_array($record['id'], $ids)) {
				$this->dao->delete($record);
			}
		}
		CoreServices2::getDB()->transactionCommit();
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices2::getDisplay();
		$display->assign('recordList', $this->recordList);
		if ($this->searchForm) {
			$display->assign('searchForm', $this->searchForm);
		}
		if ($this->deletionForm) {
			$display->assign('deletionForm', $this->deletionForm);
		}
	}
}
?>