<?php
/**
 * W parametrach requestu musi być:
 * - view ('c' lub 'w')
 * - id (rekordu pliku)
 * - nazwa sesji = id sesji
 */
class WellAjax3DPointsController extends AjaxAbstractController {
	
	
	protected $form = null;
        
        protected $currentUserId = null;
        
        protected $wellId = null;
        protected $wellType = null;
        protected $wellPoints = null;
	/**
	 * @var CoreFormValidationMessageContainer
	 */
	protected $messageManager = null;
	

        public function getSessionName() {
		return 'CMSSession';
	}
        
	// OK
	public function prepareData() {
		parent::prepareData();
                $this->wellId =         CoreServices2::getRequest()->getFromPost('wellId');
                $this->wellType =	CoreServices2::getRequest()->getFromPost('wellType');
                $this->wellPoints =	CoreServices2::getRequest()->getFromPost('wellPoints');
		$this->messageManager = new CoreFormValidationMessageContainer();
		CoreServices2::getDB()->transactionStart();
		if (!$this->hasUserPermissionsForRecord()) {
                    $this->messageManager->addMessage('fileDeleteErrorNoPermission');
                    return;
		}
                
                $pointsData = json_decode($this->wellPoints);
                foreach ($pointsData as $key => $pointData){
                    if ($pointData == ""){
                        return false;
                    }
                    $keyValue = explode( "_", $key);
                    if ($keyValue[1] != "rowOrder"){
                        $wellPoints[$keyValue[2]][$keyValue[1]] = $pointData;
                        
                    }
                    else{
                        $pointsOrder = explode(',',$pointData);
                    }
                }
                
                // find old points connectet with welll
                $well3dDAO = new Well3DPointDAO();
                $oldPoints = $well3dDAO->getWellPointsByWellId($this->wellId);
                // delete old points 
                if (!empty($oldPoints)){
                    foreach($oldPoints as $point){
                        $well3dDAO->delete($point);
                    }
                }
                $pointsCounter = 1;
                foreach($pointsOrder as $order){
                    // save new points into table
                    $recordTemplate = $well3dDAO->getRecordTemplate();
                    $recordTemplate['wellId'] = $this->wellId;
                    $recordTemplate['number'] = $pointsCounter;
                    $recordTemplate['X'] = $wellPoints[$order]['X'];
                    $recordTemplate['Y'] = $wellPoints[$order]['Y'];
                    $recordTemplate['Z'] = $wellPoints[$order]['Z'];
                    $recordTemplate['LP'] = $wellPoints[$order]['LP'];
                    $recordTemplate['alfa'] = $wellPoints[$order]['alfa'];
                    $recordTemplate['beta'] = $wellPoints[$order]['beta'];
                    $well3dDAO->save($recordTemplate);       
                    $pointsCounter ++;
                }
		CoreServices2::getDB()->transactionCommit();
	}

	

	protected function isControllerUsagePermitted() {
		$this->currentUserId = CoreServices2::getAccess()->getCurrentUserId();
		return ($this->getSessionName() == 'CMSSession') && !empty($this->currentUserId);
	}

	protected function hasUserPermissionsForRecord() {
                $wellDAO = new WellDAO();
                $record = $wellDAO->getWellOwnerUserId($this->wellId);
                if ($this->currentUserId != $record['userId']){
                    return false;
                }
		return true;
	}



	public function sendHeaders() {
		header('Content-type: application/json');
	}

	public function display() {
		$returnValue = json_encode(array(
			'status' => 'OK'
		));
		echo($returnValue);
	}
}
?>