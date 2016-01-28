<?php
class UserAjaxAutoSuggestController extends AjaxAbstractController {
	protected $dao = null;
	protected $id = null;
	protected $query = null;
	protected $name = null;
	protected $rows = null;

	public function getSessionName() {
		return 'CMSSession';
	}

	protected function initRows() {
		$limit = intval(CoreServices::get('request')->getFromGet('limit'));
		if (!$limit) {
			$limit = 50;
		}
		$minQueryLength = intval(CoreServices::get('request')->getFromGet('minChars'));
		if (!$minQueryLength) {
			$minQueryLength = 3;
		}
		if (strlen($this->query) >= $minQueryLength) {
			$userList = $this->dao->getListForAutoSuggest($this->query, $limit);
			$this->rows = $this->dao->modifyListForSelect(
				$userList,
				'<userEmail> <userFirstName> <userSurname> (<userNick>) <userCompanyName>'
			);
		}
		else {
			$this->rows = array();
		}
	}

	protected function initName() {
		$record = $this->dao->getRecordById($this->id);
		$this->name =
			$record['userEmail']
			. ' ' . $record['userFirstName']
			. ' ' . $record['userSurname'];
		if (!empty($record['userNick'])) {
			$this->name .= ' (' . $record['userNick'] . ')';
		}
		$this->name .= ' ' . $record['userCompanyName'];
	}

	protected function isControllerUsagePermitted() {
		$userId = CoreServices2::getAccess()->getCurrentUserId();
		return (!empty($userId));
	}

	public function prepareData() {
		parent::prepareData();
		$this->id = CoreServices::get('request')->getFromGet('id');
		$this->query = CoreServices::get('request')->getFromGet('query');
		$this->dao = new UserDAO();
		if ($this->query) {
			$this->initRows();
		}
		elseif ($this->id) {
			$this->initName();
		}
	}
	
	public function display() {
		if ($this->query) {
			echo("{\n");
			echo("query:'" . $this->query . "',\n");
			echo("suggestions:[");
			if (!empty($this->rows)) {
				echo("'" . implode("','", $this->rows) . "'");
			}
			echo("],\n");
			echo("data:[");
			if (!empty($this->rows)) {
				echo("'" . implode("','", array_keys($this->rows)) . "'");
			}
			echo("]\n");
			echo("}\n");
		}
		elseif ($this->id) {
			echo($this->name);
		}
	}
}
?>