<?php
/**
 * Walidator, który parsuje wartość pola menuStructure, sprawdza
 * czy wszystkie identyfikatory sie pokrywają, etc.
 */
class SubpageMenuStructureStringValidator extends CoreFormAbstractSingleFieldValidator {
	protected $recordList = null;
	protected $rootId = null;

	public function __construct($fieldName, &$indexedRecordList, $rootId) {
		parent::__construct($fieldName);
		$this->recordList = $indexedRecordList;
		$this->rootId = $rootId;
	}

	protected function validateMenuStructure(&$menuStructure, $messageManager) {
		$testTree = SubpageTestLRTreeNode::getTree($menuStructure);
		if (
			!$testTree->checkTreeSize($this->recordList)
			|| !$testTree->checkIdSetConsistency($this->recordList)
		) {
			$messageManager->addMessage('errorMenuStructureOutOfDate');
			return;
		}
		if (!$testTree->checkMaxChildrenConstraints($this->recordList)) {
			$messageManager->addMessage('errorMenuStructureTooManyChildNodes');
		}
		//if (!$testTree->checkHeightConstraints($this->recordList)) {
		//	$messageManager->addMessage('errorMenuStructureMaxDepthExceeded');
		//}
		//if (!$testTree->checkMenuNodesConstraints($this->recordList)) {
		//	$messageManager->addMessage('errorMenuStructureSpecialNodesMoved');
		//}
		//if (!$testTree->checkSubpageNodesConstraints($this->recordList)) {
		//	$messageManager->addMessage('errorMenuIllegalSubpageLocation');
		//}

		// To zastępuje wszystkie powyższe a w dodatku może wystąpić tylko w wyniku
		// hackowania lub awarii JSa
		if (!$testTree->checkHushvizConstraints($this->recordList)) {
			$messageManager->addMessage('errorMenuIllegalSubpageLocation');
		}
	}

	public function validate($messageManager) {
		$fieldValue = $this->form->getField($this->fieldName)->getValue();

		// Funkcja parse_str nie rzuca wyjątków, nie zgłasza błędów. Jeżeli wejściowy
		// string jest bez sensu to go sobie jakoś modyfikuje i coś jednak zwraca.
		parse_str(str_replace('&amp;', '&', $fieldValue), $menuStruct);
		if (!is_array($menuStruct)) {
			$messageManager->addMessage('errorInvalidInputFormat');
			return;
		}
		if (!empty($menuStruct)) {
			// $menuStruct jest tablicą:
			// [
			//     ['children'] => [
			//	       [0] => [
			//	           ['id'] => <id>
			//	           ['children'] => []
			//	       ]
			//	       [1] => [
			//	           ['id'] => <id>
			//	           ['children'] => []
			//	       ]
			//     ]
			// ]
			// itd.
			// Trzeba jeszcze dodać id korzenia!
			$menuStruct['id'] = $this->rootId;
			$this->validateMenuStructure($menuStruct, $messageManager);
		}
	}
}
?>