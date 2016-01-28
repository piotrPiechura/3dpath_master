<?php
class WellCMSEditController extends CMSAbstractControllerEdit {
        
        protected $trajectory = null;
        protected $trajectoryParams = null;
        protected $sectionNumber = null;
        protected $trajectoryTable = null;
        protected $trajectory3DRender = null;
        protected $trajectory3DRenderParam = null;
        protected $trajectoryHaracteristicPoints = null;
        protected $startingPoint = null;
        protected $points3d = null;
        
        protected $wellColor = null;
        
	public function getMenuItemDescription() {
		return 'Well';
	}

	protected function getDAOClass() {
		return 'WellDAO';
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
                $trajectory = new TrajectoryVariant();
                $this->calculateTrajectory();
                $this->wellColor = $this->record['color'];
            
            
        }
        protected function initForm() {
		$siteId = CoreServices2::getRequest()->getFromGet('site');
		$this->form = new CoreForm(
			'post',
			CoreServices::get('url')->createHTML('_m', 'Well', '_o', 'CMSEdit', 'site', $siteId)
		);
	}

        protected function createWellTypeField() {
		$options = CoreConfig::get('Data', 'wellType');
                //$options['2d'] = '2D';
                //$options['3d'] = '3D';
		$this->form->addField(new CoreFormFieldSelect(
			'wellType',
			null,
			$options
		));
	}
        
        protected function createWell3DTrajectoryField() {
		$options = CoreConfig::get('Data', 'well3DTrajectory');
		$this->form->addField(new CoreFormFieldSelect(
			'well3DTrajectory',
			null,
			$options
		));
	}
        
        
        protected function createWellTrajectoryField() {
		$options = CoreConfig::get('Data', 'wellTrajectory');
		$this->form->addField(new CoreFormFieldSelect(
			'wellTrajectory',
			null,
			$options
		));
	}
        
	protected function createFormFields() {
		parent::createFormFields();
		//$this->form->addField(new CoreFormFieldSelectAjax('Id'));
                
		$this->form->addField(new CoreFormFieldText('wellName'));
                $this->form->addField(new CoreFormFieldText('wellDescription'));
                $this->form->addField(new CoreFormFieldText('wellUWI'));
                $this->form->addField(new CoreFormFieldText('wellAPI'));
                $this->form->addField(new CoreFormFieldText('wellSlot'));
                $this->form->addField(new CoreFormFieldText('wellTedit'));
                $this->createWellTypeField();
                $this->createWellTrajectoryField();
                $this->createWell3DTrajectoryField();
                
                $this->form->addField(new CoreFormFieldText('A'));
                $this->form->addField(new CoreFormFieldText('A1'));
                $this->form->addField(new CoreFormFieldText('A2'));
                $this->form->addField(new CoreFormFieldText('A3'));
                $this->form->addField(new CoreFormFieldText('A4'));
                
                $this->form->addField(new CoreFormFieldText('H'));
                $this->form->addField(new CoreFormFieldText('H1'));
                $this->form->addField(new CoreFormFieldText('H2'));
                $this->form->addField(new CoreFormFieldText('H3'));
                $this->form->addField(new CoreFormFieldText('H4'));
                
                $this->form->addField(new CoreFormFieldText('L'));
                $this->form->addField(new CoreFormFieldText('L1'));
                $this->form->addField(new CoreFormFieldText('L2'));
                $this->form->addField(new CoreFormFieldText('L3'));
                $this->form->addField(new CoreFormFieldText('L4'));
                $this->form->addField(new CoreFormFieldText('L5'));
                
                $this->form->addField(new CoreFormFieldText('R'));
                $this->form->addField(new CoreFormFieldText('R1'));
                $this->form->addField(new CoreFormFieldText('R2'));
                $this->form->addField(new CoreFormFieldText('R3'));
                $this->form->addField(new CoreFormFieldText('R4'));
                
                $this->form->addField(new CoreFormFieldText('alfa'));
                $this->form->addField(new CoreFormFieldText('alfa1'));
                $this->form->addField(new CoreFormFieldText('alfa2'));
                $this->form->addField(new CoreFormFieldText('alfa3'));
                $this->form->addField(new CoreFormFieldText('alfa4'));
                
                $this->form->addField(new CoreFormFieldText('delta'));
                $this->form->addField(new CoreFormFieldText('delta1'));
                $this->form->addField(new CoreFormFieldText('delta2'));
                $this->form->addField(new CoreFormFieldText('delta3'));
                $this->form->addField(new CoreFormFieldText('delta4'));
                
                $this->form->addField(new CoreFormFieldText('DLS'));
                $this->form->addField(new CoreFormFieldText('DLS1'));
                $this->form->addField(new CoreFormFieldText('DLS2'));
                $this->form->addField(new CoreFormFieldText('DLS3'));
                $this->form->addField(new CoreFormFieldText('DLS4'));
                
                $this->form->addField(new CoreFormFieldText('azimuth'));
                
                $this->form->addField(new CoreFormFieldText('Q'));
                $this->form->addField(new CoreFormFieldText('ro'));
                
                $this->form->addField(new CoreFormFieldText('tvectorX'));
                $this->form->addField(new CoreFormFieldText('tvectorY'));
                $this->form->addField(new CoreFormFieldText('tvectorZ'));
                $this->form->addField(new CoreFormFieldText('vsection'));
                $this->form->addField(new CoreFormFieldText('step'));
                $this->form->addField(new CoreFormFieldHidden('color'));
  
                //$this->form->addField(new CoreFormFieldText('wellType'));
                
                $this->form->addField(new CoreFormFieldText('wellTrajectoryVariant'));
                $this->form->addField(new CoreFormFieldText('well3DPints'));
	}

	protected function addFormValidators() {
                //print_r("VALIDATORS");
		//$this->form->addValidator(new UserValidatorActiveUserId());
		//$this->form->addValidator(new AuthorValidatorSingleAuthorForUserId());
		$this->form->addValidator(new CoreFormValidatorNotEmpty('wellName'));
		$this->form->addValidator(new CoreFormValidatorMaxTextLength('wellName', 100));
                //print_r($this->form->getField("wellType")->getValue());
                if ($this->form->getField("wellType")->getValue() == '2D'){
                    $fields = $this->form->getField("wellTrajectoryVariant")->getValue();
                    $filedsNames = explode("_",$fields);
                    $trajectory = array_shift($filedsNames);
                    foreach($filedsNames as $field){
                        $this->form->addValidator(new CoreFormValidatorNotEmpty($field));
                        $this->form->addValidator(new CoreFormValidatorIsNumber($field));
                    }
                }
                $this->form->addValidator(new CoreFormValidatorInteger('step', 1, 500));
                //$this->form->addValidator(new CoreFormValidatorIsNumber('tvectorX'));
                //$this->form->addValidator(new CoreFormValidatorIsNumber('tvectorY'));
                //$this->form->addValidator(new CoreFormValidatorIsNumber('tvectorZ'));
                
	}

	protected function setSpecialRecordFieldsBeforeSave() {
                $siteId = CoreServices2::getRequest()->getFromGet('site');
                //print_r($this->record);
		if(empty($this->record['id'])) {
			$this->record['siteId'] = $siteId;
		}
	}

	protected function afterSave() {
            // save 3D points 
            $well3DPints = $this->form->getField('well3DPints')->getValue();
            $pointsData = json_decode(str_replace('&quot;','"',$well3DPints), true);
            if (!empty( $pointsData)){
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
                $oldPoints = $well3dDAO->getWellPointsByWellId($this->record['id']);
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
                    $recordTemplate['wellId'] = $this->record['id'];
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
            }
        }
        
        protected function handleDeleteRequest() {
		parent::handleDeleteRequest();
		$this->setRedirectAddress(CoreServices2::getUrl()->createAddress(
					'_m', 'Home',
					'_o', 'CMSEdit'
				));
		
	}
        
        protected function calculateTrajectory(){
            // sprawdzić czy:
            // 1. jest pole zawierające trajektorię?
            // 2. czy klasa zawiera taką metodę?
            //jeśli tak to liczymy pozostałe parametry i wyznaczamy trajektorię
            // 
            
            $step = 10;
            if (!empty($this->record['azimuth'])){
                $azimut = $this->record['azimuth'];
            }
            else{
              $azimut = 0;  
            }
            
            $algoritm = new TrajectoryAlgoritm();
            
            $this->trajectory = new TrajectoryVariant();
            if ($this->record['wellTrajectory'] == 'J1'){
                $this->sectionNumber = 2;
                
            }
            if ($this->record['wellTrajectory'] == 'J2'){
                $this->sectionNumber = 3;
            }
            if ($this->record['wellTrajectory'] == 'J3'){
                $this->sectionNumber = 5;
                
            }
            if ($this->record['wellTrajectory'] == 'S1'){
                $this->sectionNumber = 3;
                
            }
            if ($this->record['wellTrajectory'] == 'S2'){
                $this->sectionNumber = 4;
                
            }
            if ($this->record['wellTrajectory'] == 'S3'){
                $this->sectionNumber = 4;
                
            }
            if ($this->record['wellTrajectory'] == 'S4'){
                $this->sectionNumber = 5;                
            }
            
            if ($this->record['wellTrajectory'] == 'catenary'){
                $this->sectionNumber = 3;
            }
            
            if(method_exists($this->trajectory, $this->record['wellTrajectoryVariant'])){ 
                
                if ($this->record['wellType'] == '2d'){
                    $trajectoryMethod = $this->record['wellTrajectoryVariant'];
                    $methodParameters = array();
                    $parameters = explode("_", $this->record['wellTrajectoryVariant']);
                    array_shift($parameters);
                    foreach ($parameters as $parameter){
                        $methodParameters[$parameter] = $this->record[$parameter];
                    }
                    $this->trajectoryParams = call_user_func_array(array($this->trajectory, $trajectoryMethod), $methodParameters);
                    if (!empty($this->trajectoryParams['error'])){
                        $this->errorMessageContainer = new CoreFormValidationMessageContainer();
			$this->errorMessageContainer->addMessage('parametrError');
                        return 0;
                    }
                        
                    
                        $this->trajectoryTable = $algoritm->calculateCoordinates($this->sectionNumber, $this->trajectoryParams['A'], $this->trajectoryParams['H'], $this->trajectoryParams['L'], $this->trajectoryParams['alfa'], $this->trajectoryParams['delta'], $this->trajectoryParams['DLS'], $this->trajectoryParams['R'], $azimut, $step);
      
                        $firstElements = array_values($this->trajectoryTable);
                        $firstElement = $firstElements[0];
                       
                        
                        if ($this->record['vsection'] !== null){
                            $angleParametr = $this->record['vsection'];
                        }
                        else {
                            $angleParametr = $algoritm->calculateVsAngle($this->trajectoryTable[1], $this->trajectoryTable[count($this->trajectoryTable)]);
                        } 
                        
                        foreach($this->trajectoryTable as $key => $trajectoryTableElement){
                                $this->trajectoryTable[$key]['alfa'] = rad2deg($trajectoryTableElement['alfa']);
                                $this->trajectoryTable[$key]['beta'] = rad2deg($trajectoryTableElement['beta']); 
                        }
                        
                        $algoritm->calculateVerticalSection($this->trajectoryTable,  $angleParametr);
                        

                        /*foreach($this->trajectoryTable as $key => $trajectoryTableElement){
                                $this->trajectoryTable[$key]['alfa'] = rad2deg($trajectoryTableElement['alfa']);
                                $this->trajectoryTable[$key]['beta'] = rad2deg($trajectoryTableElement['beta']); 
                        }*/

                        for($i=1;$i<=$this->sectionNumber;$i++){
                             $this->trajectoryParams['alfa'][$i] = rad2deg($this->trajectoryParams['alfa'][$i]);
                             $this->trajectoryParams['delta'][$i] = rad2deg($this->trajectoryParams['delta'][$i]);
                        }
                        
                        if (!empty($this->trajectoryTable)){
                            $xmax = 0;
                            $ymax = 0;
                            $zmax = 0;
                            $mdmax = 0;
                            $tmpArr = array();
                            $section = null;
                            foreach($this->trajectoryTable as $element){
                                   
                                if ($xmax < $element['X']) $xmax = $element['X'];
                                if ($ymax < $element['Y']) $ymax = $element['Y'];
                                if ($zmax < $element['Z']) $zmax = $element['Z'];
                                if ($mdmax < $element['MD']) $mdmax = $element['MD'];
                                //print_r($element);
                                $this->trajectoryTable[$key]['X'] =  $this->trajectoryTable[$key]['X'] + floatval($this->record['tvectorX']);
                                $this->trajectoryTable[$key]['Y'] =  $this->trajectoryTable[$key]['Y'] + floatval($this->record['tvectorY']);
                                $this->trajectoryTable[$key]['Z'] =  $this->trajectoryTable[$key]['Z'] + floatval($this->record['tvectorZ']);
                                
                                
                                $tmpArr['x'] =  1 * ($element['X'] + floatval($this->record['tvectorX']));   
                                $tmpArr['y'] =  -1 * ($element['Y'] + floatval($this->record['tvectorY'])); 
                                $tmpArr['z'] =  1 * ($element['Z'] + floatval($this->record['tvectorZ'])); 
                                $tmpArr['md'] =  -1 * $element['MD']; 
                                $this->trajectory3DRender[1][] =  $tmpArr;
                                
                                if ($section == null){
                                    $section = $element['section'];
                                    $this->startingPoint = $tmpArr;
                                } 
                                
                                if ($element['section']=='PP'){
                                     $this->trajectoryHaracteristicPoints[count($this->trajectory3DRender[1])-1] = $tmpArr;
                                }
                                elseif ($element['section'] != $section){
                                    $this->trajectoryHaracteristicPoints[count($this->trajectory3DRender[1])-1] = $tmpArr;
                                }    
                                
                                $section = $element['section'];
                            }
                            
                            $this->trajectory3DRenderParam[1]['xMax'] = 1 * $xmax;
                            $this->trajectory3DRenderParam[1]['yMax'] = -1 * $ymax;
                            $this->trajectory3DRenderParam[1]['zMax'] = 1 * $zmax;
                            $this->trajectory3DRenderParam[1]['mdMax'] = -1 * $mdmax;

                        }
                } 
            }    
                    if ($this->record['wellType'] == '3d'){
                        // get points from DAO
                        $well3dDAO = new Well3DPointDAO();
                        $this->points3d = $well3dDAO->getWellPointsByWellId($this->record['id']);
                        
                        
                      // print_r("Input points X[m], Y[m], Z[m], LP[m], alfa[st], beta[st] \n");
                        foreach($this->points3d as $point){
                            $MX[$point['number']] = $point['X'];
                            $MY[$point['number']] = $point['Y'];
                            $MZ[$point['number']] = $point['Z'];
                            $MLP[$point['number']] = $point['LP'];
                            $Malfa[$point['number']] = $point['alfa'];
                            $Mbeta[$point['number']] = $point['beta'];
                            
                          //  print_r( $point['X']." ".$point['Y']." ".$point['Z']." ".$point['LP']." ".$point['alfa']." ".$point['beta']."\n");
                        }
                        $np = count($this->points3d);
                        //$step = 50;
                        
                        if ($this->record['well3DTrajectory'] == 'POCZ' || empty($this->record['well3DTrajectory'])){
                            if ($np > 0){
                                $this->trajectoryTable = $algoritm->r3pocz($np, $MX, $MY, $MZ, $MLP, $Malfa, $Mbeta, $step); 
                                $this->defaultVsection = $algoritm->calculateVsAngle($this->trajectoryTable[1], $this->trajectoryTable[count($this->trajectoryTable)]);
                            }
                        }
                        else{
                            if ($np > 0){
                                $this->trajectoryTable = $algoritm->r3konc($np, $MX, $MY, $MZ, $MLP, $Malfa, $Mbeta, $step);
                                $this->defaultVsection = $algoritm->calculateVsAngle($this->trajectoryTable[1], $this->trajectoryTable[count($this->trajectoryTable)]);
                            }
                        }
                        //print_r($this->record);
                        //print_r($this->trajectoryTable);
                        // count in algoritm
                        if (!empty($this->trajectoryTable)){
                            $xmax = 0;
                            $ymax = 0;
                            $zmax = 0;
                            $mdmax = 0;
                            $tmpArr = array();
                            $section = null;
                            foreach($this->trajectoryTable as $key => $element){
                                //print_r($element['X']);
                                //print_r("MD ". $element['MD'] ." ". $element['X'] ."\n\t");
                                if ($xmax < $element['X']) $xmax = $element['X'];
                                if ($ymax < $element['Y']) $ymax = $element['Y'];
                                if ($zmax < $element['Z']) $zmax = $element['Z'];
                                if ($mdmax < $element['MD']) $mdmax = $element['MD'];
                                //print_r($element);
                                
                                $this->trajectoryTable[$key]['X'] =  $this->trajectoryTable[$key]['X'] + floatval($this->record['tvectorX']);
                                $this->trajectoryTable[$key]['Y'] =  $this->trajectoryTable[$key]['Y'] + floatval($this->record['tvectorY']);
                                $this->trajectoryTable[$key]['Z'] =  $this->trajectoryTable[$key]['Z'] + floatval($this->record['tvectorZ']);
                                
                                $tmpArr['x'] =   ($element['X'] + floatval($this->record['tvectorX']));   
                                $tmpArr['y'] =  ($element['Y'] + floatval($this->record['tvectorY'])); 
                                $tmpArr['z'] =   -($element['Z'] + floatval($this->record['tvectorZ'])); 
                                $tmpArr['md'] =  -1 * $element['MD']; 
                                $this->trajectory3DRender[1][] =  $tmpArr;
                                
                                if ($section == null){
                                    $section = $element['section'];
                                    $this->startingPoint = $tmpArr;
                                } 
                                
                                if ($element['section']=='PP'){
                                     $this->trajectoryHaracteristicPoints[count($this->trajectory3DRender[1])-1] = $tmpArr;
                                }
                                elseif ($element['section'] != $section){
                                    $this->trajectoryHaracteristicPoints[count($this->trajectory3DRender[1])-1] = $tmpArr;
                                }    
                                
                                $section = $element['section'];
                                
                                
                            }

                            $this->trajectory3DRenderParam[1]['xMax'] = 1 * $xmax;
                            $this->trajectory3DRenderParam[1]['yMax'] = -1 * $ymax;
                            $this->trajectory3DRenderParam[1]['zMax'] = 1 * $zmax;
                            $this->trajectory3DRenderParam[1]['mdMax'] = -1 * $mdmax;
                            $this->trajectory3DRenderParam[1]['color'] = $this->record['color'];
                        }
                        
                        
                        //
                        
                    }
                

        }
        
        public function assignDisplayVariables() {
		parent::assignDisplayVariables();
                $display = CoreServices::get('display');
		$display->assign('sectionNumber', $this->sectionNumber);
                if (empty($this->trajectoryParams['error'])){
                    $display->assign('trajectoryParamsA', $this->trajectoryParams["A"]);
                    $display->assign('trajectoryParamsH', $this->trajectoryParams["H"]);
                    $display->assign('trajectoryParamsL', $this->trajectoryParams["L"]);
                    $display->assign('trajectoryParamsR', $this->trajectoryParams["R"]);
                    $display->assign('trajectoryParamsAlfa', $this->trajectoryParams["alfa"]);
                    $display->assign('trajectoryParamsDelta', $this->trajectoryParams["delta"]);
                    $display->assign('trajectoryParamsDLS', $this->trajectoryParams["DLS"]);
                }
                $display->assign('points3d', $this->points3d);
                $display->assign('trajectoryTable', $this->trajectoryTable);
                $display->assign('trajectory3DRender', $this->trajectory3DRender);
                $display->assign('trajectory3DRenderParam', $this->trajectory3DRenderParam);
                //print_r($this->trajectory3DRender);
                $display->assign('startingPoint', $this->startingPoint);
                $display->assign('trajectoryHaracteristicPoints', $this->trajectoryHaracteristicPoints);
                $display->assign('wellColor', $this->wellColor);
                if (!empty($this->defaultVsection)){
                    $display->assign('defaultVsection', $this->defaultVsection);
                }
        }

}
?>