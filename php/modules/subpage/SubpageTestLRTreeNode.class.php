<?php
class SubpageTestLRTreeNode extends SubpageLRTreeNode  {
	protected $subtreeHeight = null;

	public static function getTree(&$array) {
		$root = new SubpageTestLRTreeNode();
		$root->init($array);
		return $root;
	}

//	protected function isRoot() {
//		return empty($this->parent);
//	}

//	protected function initSubtreeHeight() {
//		if (empty($this->children)) {
//			$this->subtreeHeight = 0;
//		}
//		else {
//			$maxChildSubtreeHeight = 0;
//			foreach ($this->children as $child) {
//				$maxChildSubtreeHeight = max(
//					$maxChildSubtreeHeight,
//					$child->initSubtreeHeight()
//				);
//			}
//			$this->subtreeHeight = 1 + $maxChildSubtreeHeight;
//		}
//		return $this->subtreeHeight;
//	}

	public function checkTreeSize(&$indexedRecordList) {
		$descendants = ($this->right - $this->left - 1) / 2;
		return (sizeof($indexedRecordList) == $descendants + 1);
	}

	public function checkIdSetConsistency(&$indexedRecordList) {
		if (!array_key_exists($this->id, $indexedRecordList)) {
			return False;
		}
		foreach ($this->children as $child) {
			if (!$child->checkIdSetConsistency($indexedRecordList)) {
				return False;
			}
		}
		return True;
	}

	/**
	 * @param array $indexedRecordList
	 * @return boolean
	 * 
	 * Sprawdza czy któryś z węzłów specjalnych (root, menu) nie został przesunięty
	 * na inny poziom - to by było poważne uszkodzenie struktury!
	 */
//	public function checkMenuNodesConstraints(&$indexedRecordList) {
//		if (
//			$indexedRecordList[$this->id]['subpageLevel'] <= 0
//			&& $this->level != $indexedRecordList[$this->id]['subpageLevel']
//		) {
//			return False;
//		}
//		foreach ($this->children as $child) {
//			if (!$child->checkMenuNodesConstraints($indexedRecordList)) {
//				return False;
//			}
//		}
//		return True;
//	}

//	public function checkSubpageNodesConstraints(&$indexedRecordList) {
//		if (
//			$indexedRecordList[$this->id]['subpageLevel'] > 0
//			&& $this->level <= 0
//		) {
//			return False;
//		}
//		foreach ($this->children as $child) {
//			if (!$child->checkSubpageNodesConstraints($indexedRecordList)) {
//				return False;
//			}
//		}
//		return True;
//	}

	public function checkMaxChildrenConstraints(&$indexedRecordList) {
		if (sizeof($this->children) > $indexedRecordList[$this->id]['subpageMaxChildren']) {
			return False;
		}
		foreach ($this->children as $child) {
			if (!$child->checkMaxChildrenConstraints($indexedRecordList)) {
				return False;
			}
		}
		return True;
	}

//	public function checkHeightConstraints(&$indexedRecordList) {
//		if ($this->isRoot() && empty($this->subtreeHeight)) {
//			$this->initSubtreeHeight();
//		}
//		if ($this->subtreeHeight > $indexedRecordList[$this->id]['subpageMaxSubtreeHeight']) {
//			return False;
//		}
//		foreach ($this->children as $child) {
//			if (!$child->checkHeightConstraints($indexedRecordList)) {
//				return False;
//			}
//		}
//		return True;
//	}

	public function checkHushvizConstraints(&$indexedRecordList) {
		if (
			$this->level != $indexedRecordList[$this->id]['subpageLevel']
		) {
			return False;
		}
		foreach ($this->children as $child) {
			if (!$child->checkHushvizConstraints($indexedRecordList)) {
				return False;
			}
		}
		return True;
	}

}
?>
