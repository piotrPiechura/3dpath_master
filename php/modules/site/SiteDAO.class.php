<?php
class SiteDAO extends CoreModelAbstractDAO {
	protected function getTableName() {
		return 'site';
	}

	protected function getFields() {
		return array(
			'id' => null,
			'projectId' => null,
			'siteName' => null,
                        'siteDistrict' => null,
                        'siteBlock' => null,
                        'siteElevation' => null,
                        'siteLocation' => null,
                        'siteTeditNorthing' => null,
                        'siteTeditEasting' => null
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
		return 'siteName ASC, id';
	}

	public function getListForAutoSuggest($query, $limit) {
		$db = CoreServices::get('db');
		$queryConditionSQL = '0';
		$parts = explode(' ', $query);
		$names = array();
		for ($i = 0; $i < min(2, sizeof($parts)); $i++) {
			$names[] = $db->prepareInputValue('%' . $parts[$i] . '%');
		}
		foreach (array('siteName') as $colName) {
			foreach ($names as $nameSQL) {
				$queryConditionSQL .= '
					OR ' . $colName . ' LIKE ' . $nameSQL;
			}
		}
		$sql = '
			SELECT *
			FROM site
			WHERE
				(' . $queryConditionSQL . ')
			ORDER BY
				siteName';
		if ($limit) {
			$sql .= '
			LIMIT 0, ' . $db->prepareInputValue($limit);
		}
		return $db->getRows($sql);
	}
        
        public function getSiteByProjectIdAndUserId($projectId,$userId){
		$db = CoreServices2::getDB();
		$sql = '
			SELECT
				site.id, projectId, companyId, projectName, siteName
			FROM
				site   
                        LEFT JOIN 
                                project on (projectId = project.id)
                        LEFT JOIN 
                                company ON (companyId = company.id)
			WHERE
				projectId = ' . $db->prepareInputValue($projectId) .'
                        AND userId = ' . $db->prepareInputValue($userId) .' 
                        ORDER BY siteName';		
		return $db->getRows($sql);
        }
                
        public function getRecordPath($recordId){
                $db = CoreServices2::getDB();
		$sql = '
			SELECT
				companyName, projectName, projectId, companyId, site.id as siteId, siteName
			FROM
				site
                        LEFT JOIN 
                                project on (projectId = project.id)
                        LEFT JOIN 
                                company ON (companyId = company.id)
			WHERE
				site.id = ' . $db->prepareInputValue($recordId) .'
                        LIMIT 0,1';
                
		return $db->getRow($sql);
        }

        

}
?>