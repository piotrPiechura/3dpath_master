<?php
class SubpageLRTreeNode {
	protected $id = null;
	protected $left = null;
	protected $right = null;
	protected $level = null;

	protected $parent = null;
	protected $children = null;

	public static function getTree(&$array) {
		$root = new SubpageLRTreeNode();
		$root->init($array);
		return $root;
	}

	protected function getInitializedInstance(&$array, $left) {
		$className = get_class($this);
		$obj = new $className();
		$obj->init($array, $left, $this);
		return $obj;
	}

	protected function __construct() {}

	protected function init(&$array, $left = 1, $parent = null) {
		if (!empty($array['id'])) {
			$this->id = $array['id'];
		}
		$this->left = $left++;
		$this->parent = $parent;
		$this->level = empty($this->parent) ? -1 : $this->parent->getLevel() + 1;
		$this->children = array();
		if (!empty($array['children'])) {
			foreach ($array['children'] as $childArray) {
				$newChild = $this->getInitializedInstance($childArray, $left);
				$this->children[] = $newChild;
				$left = $newChild->getRight() + 1;
			}
		}
		$this->right = $left;
	}

	protected function getId() {
		return $this->id;
	}

	public function getParentId() {
		if (empty($this->parent)) {
			return null;
		}
		return $this->parent->getId();
	}

	public function getLeft() {
		return $this->right;
	}

	public function getRight() {
		return $this->right;
	}

	public function getLevel() {
		return $this->level;
	}
}
/*
class SubpageLRTreeNode {
	protected $id = null;
	protected $left = null;
	protected $right = null;
	protected $level = null;
	protected $descendantCount = null;
	protected $descendantMaxLevel = null;

	protected $parent = null;
	protected $children = null;

	public function __construct(&$array, $left = 1, $parent = null) {
		if (!empty($array['id'])) {
			$this->id = $array['id'];
		}
		$this->left = $left;
		$this->parent = $parent;
		$this->children = array();
		if (empty($array['children'])) {
			$this->right = $left + 1;
			$this->level = empty($this->parent) ? -1 : $this->parent->getLevel() + 1;
			$this->descendantCount = 0;
			$this->descendantMaxLevel = $this->level;
		}
		else {
			$this->descendantCount = 0;
			$this->descendantMaxLevel = $this->level;
			foreach ($array['children'] as $childArray) {
				$newChild = new SubpageLRTreeNode($childArray, $left + 1, $this);
				$this->children[] = $newChild;
				$left = $newChild->getRight() + 1;
				$this->descendantCount += (1 + $newChild->getDescendantCount());
				$this->descendantMaxLevel = max(
					$this->descendantMaxLevel,
					$newChild->getDescendantMaxLevel()
				);
			}
			$this->right = $left;
		}
	}

	public function getRight() {
		return $this->right;
	}

	public function getLevel() {
		return $this->level;
	}

	public function getDescendantCount() {
		return $this->descendantCount;
	}

	public function getDescendantMaxLevel() {
		return $this->descendantMaxLevel;
	}
}
 *
 */
?>
