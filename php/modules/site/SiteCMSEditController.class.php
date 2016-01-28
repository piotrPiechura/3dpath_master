<?php
class SiteCMSEditController extends CMSAbstractControllerEdit {
        protected $trajectory = null;
        protected $trajectoryRender = null;
        protected $trajectoryRenderParams = null;
    
	public function getMenuItemDescription() {
		return 'Site';
	}

	protected function getDAOClass() {
		return 'SiteDAO';
	}
	
	protected function initRecordManyToManyRelations() {}

	protected function initMultiselectRelations() {}
	
	/*protected function initActions() {
		$this->availableActions = array('Save');
		/*if ($this->record['faqItemState'] == 'visible') {
			$this->availableActions[] = 'Hide';
		} else {
			$this->availableActions[] = 'Show';
		}*/
		/*if(!empty($this->record['id']) && !$this->dao->hasRelatedRecords($this->record)) {
			$this->availableActions[] = 'Delete';
		}
	}*/
        
        public function prepareData() {
		parent::prepareData();
                if (!empty($this->record['id'])){
                   $this->drawTrajectories(); 
                }
                
                
        }        
        
        protected function drawTrajectories(){
                $wellDAO = new WellDAO;
                $wells = $wellDAO->getWellBySiteId($this->record['id']);
                //print_r($wells);
                $trajectoryCalculations = new TrajectoryCalculations;
                foreach ($wells as $well){
                    //print_r($well);
                    //if ($well['wellType'] != '3d' && $well['well3DTrajectory'] != 'KONC'){
                        $this->trajectory[$well['id']] = $trajectoryCalculations->calculateTrajectory($well['id'], 200, $well['azimuth']);
                    //}
                    //print_r($this->trajectory[$well['id']]);;
                       
                }
                if (!empty($this->trajectory)){
                    foreach ($this->trajectory as $key =>$well){

                        $this->trajectoryRender[$key] = $well['render'];
                        $this->trajectoryRender3d[$key]['xMax'] = $well['xMax'];
                        $this->trajectoryRender3d[$key]['yMax'] = $well['yMax'];
                        $this->trajectoryRender3d[$key]['zMax'] = $well['zMax'];
                        $this->trajectoryRender3d[$key]['color'] = $well['color'];
                        $this->trajectoryRender3d[$key]['wellName'] = $well['wellName'];
                    }
                }
                //print_r($trajectory[3]);
        }
        
        protected function initForm() {
		$projectId = CoreServices2::getRequest()->getFromGet('proj');
		$this->form = new CoreForm(
			'post',
			CoreServices::get('url')->createHTML('_m', 'Site', '_o', 'CMSEdit', 'proj', $projectId)
		);
	}

	protected function createFormFields() {
		parent::createFormFields();
		//$this->form->addField(new CoreFormFieldSelectAjax('Id'));
                
		$this->form->addField(new CoreFormFieldText('siteName'));
                $this->form->addField(new CoreFormFieldText('siteDistrict'));
                $this->form->addField(new CoreFormFieldText('siteBlock'));
                $this->form->addField(new CoreFormFieldText('siteElevation'));
                $this->form->addField(new CoreFormFieldText('siteLocation'));
                $this->form->addField(new CoreFormFieldText('siteTeditNorthing'));
                $this->form->addField(new CoreFormFieldText('siteTeditEasting'));
                //$this->form->addField(new CoreFormFieldText('companySurveyCalcMethod'));
	}

	protected function addFormValidators() {
		//$this->form->addValidator(new UserValidatorActiveUserId());
		//$this->form->addValidator(new AuthorValidatorSingleAuthorForUserId());
		$this->form->addValidator(new CoreFormValidatorNotEmpty('siteName'));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('siteName', 100));
	}

	protected function setSpecialRecordFieldsBeforeSave() {
                $projectId = CoreServices2::getRequest()->getFromGet('proj');
		if(empty($this->record['id'])) {
			$this->record['projectId'] = $projectId;
		}
	}

	protected function afterSave() {}
        
        protected function handleDeleteRequest() {
		parent::handleDeleteRequest();
		$this->setRedirectAddress(CoreServices2::getUrl()->createAddress(
					'_m', 'Home',
					'_o', 'CMSEdit'
				));
		
	}
        
        public function assignDisplayVariables() {
		parent::assignDisplayVariables();
                $display = CoreServices::get('display');
		$display->assign('trajectory', $this->trajectory);
                if (!empty($this->trajectoryRender)){
                    $display->assign('trajectoryRender', $this->trajectoryRender);
                }
                if (!empty($this->trajectoryRender3d)){
                    $display->assign('trajectoryRender3d', $this->trajectoryRender3d);
                }
                if (!empty($this->trajectoryRenderParams)){
                    $display->assign('trajectoryRenderParams', $this->trajectoryRenderParams);
                }
        }
        
        
}
?>