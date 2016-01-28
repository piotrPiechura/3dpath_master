<?php
class Well3DPointDAO extends CoreModelAbstractDAO {
	protected function getTableName() {
		return 'well3dpoint';
	}

	protected function getFields() {
		return array(
			'id' => null,
			'wellId' => null,
			'number' => null,
                        'X' => null,
                        'Y' => null,
                        'Z' => null,
                        'LP' => null,
                        'alfa' => null,
                        'beta' => null
		);
	}

	protected function getManyToManyRelations()	{
		return array();
	}

	protected function hasNumerousDirectlyRelatedRecords(array &$record) {

	}

	protected function getDirectlyRelatedRecords(&$record) {
		return array();
	}

	protected function getDefaultOrderBySQL() {
		return 'wellId ASC, id';
	}
        
        public function getWellPointsByWellId($wellId){
                $db = CoreServices2::getDB();
                $sql = '
			SELECT
				*
			FROM
				well3dpoint 
			WHERE
				wellId = ' . $db->prepareInputValue($wellId) .'
                        Order By number';
                
                return $db->getRows($sql);
                    
        }
}
?>