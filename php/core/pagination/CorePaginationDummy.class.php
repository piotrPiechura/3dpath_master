<?php
class CorePaginationDummy implements iCorePagination {
 	public function __construct() {}

	public function getType() {
		return 'Dummy';
	}
	
	public function getPageCount() {
		return 1;
	}
}
?>