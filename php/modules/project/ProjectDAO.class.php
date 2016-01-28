<?php
class ProjectDAO extends CoreModelAbstractDAO {
	protected function getTableName() {
		return 'project';
	}

	protected function getFields() {
		return array(
			'id' => null,
			'companyId' => null,
			'projectName' => null,
                        'projectLocation' => null,
                        'projectDescription' => null,
                        'projectSystem' => null,
                        'projectElevation' => null
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
		return 'projectName ASC, id';
	}

	public function getListForAutoSuggest($query, $limit) {
		$db = CoreServices::get('db');
		$queryConditionSQL = '0';
		$parts = explode(' ', $query);
		$names = array();
		for ($i = 0; $i < min(2, sizeof($parts)); $i++) {
			$names[] = $db->prepareInputValue('%' . $parts[$i] . '%');
		}
		foreach (array('companyName') as $colName) {
			foreach ($names as $nameSQL) {
				$queryConditionSQL .= '
					OR ' . $colName . ' LIKE ' . $nameSQL;
			}
		}
		$sql = '
			SELECT *
			FROM project
			WHERE
				(' . $queryConditionSQL . ')
			ORDER BY
				projectName';
		if ($limit) {
			$sql .= '
			LIMIT 0, ' . $db->prepareInputValue($limit);
		}
		return $db->getRows($sql);
	}
        
        public function getProjectByCompanyIdAndUserId($companyId,$userId){
		$db = CoreServices2::getDB();
		$sql = '
			SELECT
				project.id, companyId, projectName
			FROM
				project    
                        LEFT JOIN 
                                company ON (companyId = company.id)
			WHERE
				companyId = ' . $db->prepareInputValue($companyId) .'
                        AND userId = ' . $db->prepareInputValue($userId) .' 
                        ORDER BY projectName';		
		return $db->getRows($sql);
        }
                
        public function getRecordPath($recordId){
                $db = CoreServices2::getDB();
		$sql = '
			SELECT
				companyId, companyName, project.id as projectId, projectName
			FROM
				project 
                        Left join 
                                company ON (companyId = company.id)
			WHERE
				project.id = ' . $db->prepareInputValue($recordId) .'
                        LIMIT 0,1';
                
		return $db->getRow($sql);
        }
        

}
?>