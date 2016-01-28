<?php
class SubpageUpdateLRTreeNode extends SubpageLRTreeNode {
	public static function getTree(&$array) {
		$root = new SubpageUpdateLRTreeNode();
		$root->init($array);
		return $root;
	}

	public function updateRecords(&$indexedRecordList) {
		$this->updateRecord($indexedRecordList);
		foreach ($this->children as $child) {
			$child->updateRecords($indexedRecordList);
		}
	}

	protected function updateRecord(&$indexedRecordList) {
		$indexedRecordList[$this->id]['subpageParentId'] = $this->getParentId();
		$indexedRecordList[$this->id]['subpageLevel'] = $this->level;
		$indexedRecordList[$this->id]['subpageLeft'] = $this->left;
		$indexedRecordList[$this->id]['subpageRight'] = $this->right;
	}
}
?>
