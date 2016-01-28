<?php
class TrajectoryAjaxSlotController extends AjaxAbstractController {
	protected $dao = null;
	protected $name = null;
        protected $Variant = null;
        protected $trajectory = null;
        
        public function getSessionName() {
		return 'CMSSession';
	}

	

	protected function isControllerUsagePermitted() {
		$userId = CoreServices2::getAccess()->getCurrentUserId();
		return (!empty($userId));
	}

        
        
	public function prepareData() {
		parent::prepareData();
                $this->initForm();
		$this->createFormFields();
		$this->setFieldValuesFromRequest();
                
                $request = CoreServices::get('request');
		$this->A = $this->form->getField('A')->getValue();
		$this->H = $this->form->getField('H')->getValue();
                $this->L1 = $this->form->getField('L1')->getValue();
		$this->DLS = $this->form->getField('DLS')->getValue();
                $this->DLS2 = $this->form->getField('DLS2')->getValue();
		$this->alfa2 = $this->form->getField('alfa2')->getValue();
                $this->L3 = $this->form->getField('L3')->getValue();
                $this->Azimuth = $this->form->getField('Azimuth')->getValue();
                $this->targetInclination = $this->form->getField('targetInclination')->getValue();
                $this->targetAzimuth = $this->form->getField('targetAzimuth')->getValue();
		$this->TR = $this->form->getField('TR')->getValue();
                $this->TL = $this->form->getField('TL')->getValue();
		$this->BR = $this->form->getField('BR')->getValue();
                $this->BL = $this->form->getField('BL')->getValue();
		$this->SR = $this->form->getField('SR')->getValue();
                $this->ST = $this->form->getField('ST')->getValue();
                $this->SM = $this->form->getField('SM')->getValue();
                $this->NOS = $this->form->getField('NOS')->getValue();
                $this->DeltaX = $this->form->getField('DeltaX')->getValue();
                $this->DeltaY = $this->form->getField('DeltaY')->getValue();  
                $this->DeltaZ = $this->form->getField('DeltaZ')->getValue(); 
                $this->Variant = $this->form->getField('Variant')->getValue();
		$this->Name = $this->form->getField('Name')->getValue();
                $this->siteId = $this->form->getField('SiteId')->getValue();
                
                if (empty($this->Name)){
                    $this->Name = "Pads";
                }
                
                if (empty($this->targetInclination)){
                    $this->targetInclination = 0;
                }
                
                if (empty($this->targetAzimuth)){
                   $this->targetAzimuth = 0;
                }
                
                if (empty($this->Variant)){
                    throw new Exception("No Trajectory Variant");
                }
                $wellDAO = new WellDAO();
                $well3dpointDAO = new Well3DPointDAO();
                $well = $wellDAO->getRecordTemplate();
                $well['A'] = $this->A;
                $well['H'] = $this->H;
                $well['L1'] = $this->L1;
                $well['DLS'] = $this->DLS;
                $well['DLS2'] = $this->DLS2;
                $well['alfa2'] = $this->alfa2;
                $well['L3'] = $this->L3;
                $well['wellName'] = $this->Name." W0";
                $well['siteId'] = $this->siteId;
                $well['wellType'] = '2d';
                $well['wellTrajectory'] = "J2";
                $well['wellTrajectoryVariant'] = $this->Variant;	
                $wellDAO->save($well);
                
                $this->trajectory = new TrajectoryVariant();
                $algoritm = new TrajectoryAlgoritm();
                $trajectoryMethod = $this->Variant;
                $methodParameters = array();
                $parameters = explode("_", $this->Variant);
                array_shift($parameters);
                   
                foreach ($parameters as $parameter){
                    $methodParameters[$parameter] = $well[$parameter];
                }
                   
                $this->trajectoryParams = call_user_func_array(array($this->trajectory, $trajectoryMethod), $methodParameters);
                if (!empty($this->trajectoryParams['error'])){
                    //$this->errorMessageContainer = new CoreFormValidationMessageContainer();
                    //$this->errorMessageContainer->addMessage('parametrError');
                    return 0; //!!!!!
                }
                //print_r($this->trajectoryParams);
                $sectionNumber = 3;
                $step = 10;
                $azimut = 0;
                $this->trajectoryTable = $algoritm->calculateCoordinates($sectionNumber, $this->trajectoryParams['A'], $this->trajectoryParams['H'], $this->trajectoryParams['L'], $this->trajectoryParams['alfa'], $this->trajectoryParams['delta'], $this->trajectoryParams['DLS'], $this->trajectoryParams['R'], $azimut, $step);

                //print_r($this->trajectoryTable);
                
                $firstElements = array_values($this->trajectoryTable);
                $firstElement = $firstElements[0];
                $trayectoryTableLenght = count($this->trajectoryTable);
                $section = $firstElement['section'];
                $hpoints[] = $firstElement;
                $counter = 1;
                foreach ($this->trajectoryTable as $point){
                    if ($point['section'] == 'PP' || ($section != $point['section'] && $section !='PP') || $counter == $trayectoryTableLenght){
                        $section = $point['section'];
                        $hpoints[] = $point;
                    }
                    $counter++;
                }
                //print_r($hpoints);
                
                if ($this->TR == 'true'){
                    // In loop create 
                    // 1. well 3d with R3Konc
                    // 2. calulate points W, HP, TP
                    // 3. save well & points
                    if ($this->NOS > 0){
                        for ($i = 1; $i <= $this->NOS; $i++){
                            $padWell = $wellDAO->getRecordTemplate();
                            $padWell['wellName'] = $this->Name . "_W".$i."R";
                            $padWell['wellType'] = '3d';
                            $padWell['well3DTrajectory'] = "KONC";
                            $padWell['siteId'] = $this->siteId;
                            // add norting 
                            
                            $srSum = $i * $this->SR;
                            $stSum = $i * $this->ST;
                            $BOPX =  + $srSum * cos($this->Azimuth*Pi()/180) + $this->DeltaX;
                            $BOPY =  - $srSum * sin($this->Azimuth*Pi()/180) + $this->DeltaY;
                            $padWell['tvectorX'] = $BOPX;
                            $padWell['tvectorY'] = $BOPY;
                            $padWell['tvectorZ'] = 0;
                            $wellDAO->save($padWell);
                            
                            $bopx = + $srSum * cos($this->Azimuth*Pi()/180);
                            $bopy = - $srSum * sin($this->Azimuth*Pi()/180);
                            
                            $BOPX = $firstElement['X']; //+ $srSum * cos($this->Azimuth*Pi()/180) + $this->DeltaX;
                            $BOPY = $firstElement['Y']; //- $srSum * sin($this->Azimuth*Pi()/180) + $this->DeltaY;
                            //$HPX =  $hpoints[2]['X'] + $stSum * cos($this->Azimuth*Pi()/180) + $this->DeltaX;
                            //$HPY =  $hpoints[2]['Y'] - $srSum * sin($this->Azimuth*Pi()/180) + $this->DeltaY;
                            $TPX =  $hpoints[3]['X'] + $stSum * cos($this->Azimuth*Pi()/180) - $bopx; //+ $this->DeltaX;
                            $TPY =  $hpoints[3]['Y'] - $stSum * sin($this->Azimuth*Pi()/180) - $bopy;//+ $this->DeltaY;
                            
                           
                            $well3dpoint =  $well3dpointDAO->getRecordTemplate();
                            $well3dpoint['wellId'] = $padWell['id'];
                            $well3dpoint['number'] = 1;
                            $well3dpoint['X'] = 0;
                            $well3dpoint['Y'] = 0;
                            $well3dpoint['Z'] = 0;
                            $well3dpoint['alfa'] = 0;
                            $well3dpoint['beta'] = 0;
                            $well3dpoint['LP'] = 0;
                            // add L1 and points 
                            $well3dpointDAO->save($well3dpoint);
                            
                            $well3dpoint =  $well3dpointDAO->getRecordTemplate();
                            $well3dpoint['wellId'] = $padWell['id'];
                            $well3dpoint['number'] = 2;
                            $well3dpoint['X'] = $BOPX; //$HPX;
                            $well3dpoint['Y'] = $BOPY;//$HPY;
                            $well3dpoint['Z'] = $this->trajectoryParams['L'][1];//$hpoints[2]['Z'];
                            
                            $well3dpoint['alfa'] = 0;
                            $well3dpoint['beta'] = 0;
                            $well3dpoint['LP'] = $this->trajectoryParams['L'][1]; //0;
                            // change point position to kop
                            $well3dpointDAO->save($well3dpoint);
                            
                            $well3dpoint =  $well3dpointDAO->getRecordTemplate();
                            $well3dpoint['wellId'] = $padWell['id'];
                            $well3dpoint['number'] = 3;
                            $well3dpoint['X'] = $TPX;
                            $well3dpoint['Y'] = $TPY;
                            $well3dpoint['Z'] = $hpoints[3]['Z'];
                            $well3dpoint['alfa'] = $this->targetInclination;
                            $well3dpoint['beta'] = $this->targetAzimuth;
                            $well3dpoint['LP'] =  $this->trajectoryParams['L'][3];//0;
                            //add li
                            $well3dpointDAO->save($well3dpoint);
                        }
                    }
                    
                    
                }
                if ($this->TL == 'true'){
                    if ($this->NOS > 0){
                        for ($i = 1; $i <= $this->NOS; $i++){
                            $padWell= $wellDAO->getRecordTemplate();
                            $padWell['wellName'] = $this->Name . "_W".$i."L";
                            $padWell['wellType'] = '3d';
                            $padWell['well3DTrajectory'] = "KONC";
                            $padWell['siteId'] = $this->siteId;
                            
                            
                            $srSum = $i * $this->SR;
                            $stSum = $i * $this->ST;
                            $BOPX =  - $srSum * cos($this->Azimuth*Pi()/180) + $this->DeltaX;
                            $BOPY =  + $srSum * sin($this->Azimuth*Pi()/180) + $this->DeltaY;
                            $padWell['tvectorX'] = $BOPX;
                            $padWell['tvectorY'] = $BOPY;
                            $padWell['tvectorZ'] = 0;
                            
                            $wellDAO->save($padWell);
                            
                            $srSum = $i * $this->SR;
                            $stSum = $i * $this->ST;
                            
                            $bopx = - $srSum * cos($this->Azimuth*Pi()/180);
                            $bopy = + $srSum * sin($this->Azimuth*Pi()/180);
                            
                            $BOPX = $firstElement['X']; //- $srSum * cos($this->Azimuth*Pi()/180) + $this->DeltaX;
                            $BOPY = $firstElement['Y']; //+ $srSum * sin($this->Azimuth*Pi()/180) + $this->DeltaY;
                            //$HPX =  $hpoints[2]['X'] - $stSum * cos($this->Azimuth*Pi()/180) + $this->DeltaX;
                            //$HPY =  $hpoints[2]['Y'] + $srSum * sin($this->Azimuth*Pi()/180) + $this->DeltaY;
                            $TPX =  $hpoints[3]['X'] - $stSum * cos($this->Azimuth*Pi()/180) - $bopx; //+ $this->DeltaX;
                            $TPY =  $hpoints[3]['Y'] + $stSum * sin($this->Azimuth*Pi()/180) - $bopy; //+ $this->DeltaY;
                            
                           
                            $well3dpoint =  $well3dpointDAO->getRecordTemplate();
                            $well3dpoint['wellId'] = $padWell['id'];
                            $well3dpoint['number'] = 1;
                            $well3dpoint['X'] = 0;
                            $well3dpoint['Y'] = 0;
                            $well3dpoint['Z'] = 0;
                            $well3dpoint['alfa'] = 0;
                            $well3dpoint['beta'] = 0;
                            $well3dpoint['LP'] = 0;
                            $well3dpointDAO->save($well3dpoint);
                            
                            $well3dpoint =  $well3dpointDAO->getRecordTemplate();
                            $well3dpoint['wellId'] = $padWell['id'];
                            $well3dpoint['number'] = 2;
                            $well3dpoint['X'] = $BOPX;
                            $well3dpoint['Y'] = $BOPY;
                            $well3dpoint['Z'] =  $this->trajectoryParams['L'][1];//$hpoints[2]['Z'];;
                            $well3dpoint['alfa'] = 0;
                            $well3dpoint['beta'] = 0;
                            $well3dpoint['LP'] = $this->trajectoryParams['L'][1];
                            $well3dpointDAO->save($well3dpoint);
                            
                            $well3dpoint =  $well3dpointDAO->getRecordTemplate();
                            $well3dpoint['wellId'] = $padWell['id'];
                            $well3dpoint['number'] = 3;
                            $well3dpoint['X'] = $TPX;
                            $well3dpoint['Y'] = $TPY;
                            $well3dpoint['Z'] = $hpoints[3]['Z'];
                            $well3dpoint['alfa'] = $this->targetInclination;
                            $well3dpoint['beta'] = $this->targetAzimuth;
                            $well3dpoint['LP'] =  $this->trajectoryParams['L'][3];
                            $well3dpointDAO->save($well3dpoint);
                        }
                    }
                }
                if ($this->BR == 'true'){
                     if ($this->NOS > 0){
                        for ($i = 1; $i <= $this->NOS; $i++){
                            $this->Azimuth =  $this->Azimuth + 180;
                            $padWell= $wellDAO->getRecordTemplate();
                            $padWell['wellName'] = $this->Name . "_W".$i."MR";
                            $padWell['wellType'] = '3d';
                            $padWell['well3DTrajectory'] = "KONC";
                            $padWell['siteId'] = $this->siteId;
                            
                            $DeltaX = $this->DeltaX + $this->SM * sin($this->Azimuth*pi()/180);
                            $DeltaY = $this->DeltaY + $this->SM * sin($this->Azimuth*pi()/180);
                            
                            $srSum = $i * $this->SR;
                            $stSum = $i * $this->ST;
                            
                            $bopx = + $srSum * cos($this->Azimuth*Pi()/180);
                            $bopy = - $srSum * sin($this->Azimuth*Pi()/180);
                            
                            $BOPX =  + $srSum * cos($this->Azimuth*Pi()/180) + $DeltaX;
                            $BOPY =  - $srSum * sin($this->Azimuth*Pi()/180) + $DeltaY;
                            $padWell['tvectorX'] = $BOPX;
                            $padWell['tvectorY'] = $BOPY;
                            $padWell['tvectorZ'] = 0;
                            
                            $wellDAO->save($padWell);
                            
                            $BOPX = $firstElement['X']; //+ $srSum * cos($this->Azimuth*Pi()/180) + $DeltaX;
                            $BOPY = $firstElement['Y']; // - $srSum * sin($this->Azimuth*Pi()/180) + $DeltaY;
                            //$HPX =  $hpoints[2]['X'] + $stSum * cos($this->Azimuth*Pi()/180) + $DeltaX;
                            //$HPY =  $hpoints[2]['Y'] - $srSum * sin($this->Azimuth*Pi()/180) + $DeltaY;
                            $TPX =  $hpoints[3]['X']  + $stSum * cos($this->Azimuth*Pi()/180) - $bopx; //+ $DeltaX;
                            $TPY =  $hpoints[3]['Y']  - $stSum * sin($this->Azimuth*Pi()/180) - $bopy; //+ $DeltaY;
                            
                            $well3dpoint =  $well3dpointDAO->getRecordTemplate();
                            $well3dpoint['wellId'] = $padWell['id'];
                            $well3dpoint['number'] = 1;
                            $well3dpoint['X'] = 0;
                            $well3dpoint['Y'] = 0;
                            $well3dpoint['Z'] = 0;
                            $well3dpoint['alfa'] = 0;
                            $well3dpoint['beta'] = 0;
                            $well3dpoint['LP'] = 0;
                            $well3dpointDAO->save($well3dpoint);
                            
                            $well3dpoint =  $well3dpointDAO->getRecordTemplate();
                            $well3dpoint['wellId'] = $padWell['id'];
                            $well3dpoint['number'] = 2;
                            $well3dpoint['X'] = $BOPX;
                            $well3dpoint['Y'] = $BOPY;
                            $well3dpoint['Z'] = $this->trajectoryParams['L'][1]; //$hpoints[2]['Z'];;
                            $well3dpoint['alfa'] = 0;
                            $well3dpoint['beta'] = 0;
                            $well3dpoint['LP'] = $this->trajectoryParams['L'][1]; //0;
                            $well3dpointDAO->save($well3dpoint);
                            
                            $well3dpoint =  $well3dpointDAO->getRecordTemplate();
                            $well3dpoint['wellId'] = $padWell['id'];
                            $well3dpoint['number'] = 3;
                            $well3dpoint['X'] = $TPX;
                            $well3dpoint['Y'] = $TPY;
                            $well3dpoint['Z'] = $hpoints[3]['Z'];
                            $well3dpoint['alfa'] = $this->targetInclination;
                            $well3dpoint['beta'] = $this->targetAzimuth;
                            $well3dpoint['LP'] =  $this->trajectoryParams['L'][3];//0;
                            $well3dpointDAO->save($well3dpoint);
                        }
                    }
                }
                if ($this->BL == 'true'){
                    if ($this->NOS > 0){
                        for ($i = 1; $i <= $this->NOS; $i++){
                            $this->Azimuth =  $this->Azimuth + 180;
                            $padWell= $wellDAO->getRecordTemplate();
                            $padWell['wellName'] = $this->Name . "_W".$i."ML";
                            $padWell['wellType'] = '3d';
                            $padWell['well3DTrajectory'] = "KONC";
                            $padWell['siteId'] = $this->siteId;
                            
                            $DeltaX = $this->DeltaX + $this->SM * sin($this->Azimuth*pi()/180);
                            $DeltaY = $this->DeltaY + $this->SM * sin($this->Azimuth*pi()/180);
                            
                            $srSum = $i * $this->SR;
                            $stSum = $i * $this->ST;
                            
                            $bopx = - $srSum * cos($this->Azimuth*Pi()/180);
                            $bopy = + $srSum * sin($this->Azimuth*Pi()/180);
                            
                            $BOPX =  - $srSum * cos($this->Azimuth*Pi()/180) + $DeltaX;
                            $BOPY = + $srSum * sin($this->Azimuth*Pi()/180) + $DeltaY;
                            $padWell['tvectorX'] = $BOPX;
                            $padWell['tvectorY'] = $BOPY;
                            $padWell['tvectorZ'] = 0;
                            $wellDAO->save($padWell);
                            
                           
                            
                            $BOPX = $firstElement['X'];// - $srSum * cos($this->Azimuth*Pi()/180) + $DeltaX;
                            $BOPY = $firstElement['Y'];// + $srSum * sin($this->Azimuth*Pi()/180) + $DeltaY;
                            //$HPX =  $hpoints[2]['X'] - $stSum * cos($this->Azimuth*Pi()/180) + $DeltaX;
                            //$HPY =  $hpoints[2]['Y'] + $srSum * sin($this->Azimuth*Pi()/180) + $DeltaY;
                            $TPX =  $hpoints[3]['X'] - $stSum * cos($this->Azimuth*Pi()/180) - $bopx; //+ $DeltaX;
                            $TPY =  $hpoints[3]['Y'] + $srSum * sin($this->Azimuth*Pi()/180) - $bopy; //+ $DeltaY;
                            
                            $well3dpoint =  $well3dpointDAO->getRecordTemplate();
                            $well3dpoint['wellId'] = $padWell['id'];
                            $well3dpoint['number'] = 1;
                            $well3dpoint['X'] = 0;
                            $well3dpoint['Y'] = 0;
                            $well3dpoint['Z'] = 0;
                            $well3dpoint['alfa'] = 0;
                            $well3dpoint['beta'] = 0;
                            $well3dpoint['LP'] = 0;
                            $well3dpointDAO->save($well3dpoint);
                            
                            $well3dpoint =  $well3dpointDAO->getRecordTemplate();
                            $well3dpoint['wellId'] = $padWell['id'];
                            $well3dpoint['number'] = 2;
                            $well3dpoint['X'] = $BOPX;
                            $well3dpoint['Y'] = $BOPY;
                            $well3dpoint['Z'] = $this->trajectoryParams['L'][1];//$hpoints[2]['Z'];
                            $well3dpoint['alfa'] = 0;
                            $well3dpoint['beta'] = 0;
                             $well3dpoint['LP'] = $this->trajectoryParams['L'][1]; //0;
                            $well3dpointDAO->save($well3dpoint);
                            
                            $well3dpoint =  $well3dpointDAO->getRecordTemplate();
                            $well3dpoint['wellId'] = $padWell['id'];
                            $well3dpoint['number'] = 3;
                            $well3dpoint['X'] = $TPX;
                            $well3dpoint['Y'] = $TPY;
                            $well3dpoint['Z'] = $hpoints[3]['Z'];
                            $well3dpoint['alfa'] = $this->targetInclination;
                            $well3dpoint['beta'] = $this->targetAzimuth;
                            $well3dpoint['LP'] =  $this->trajectoryParams['L'][3];//0;
                            $well3dpointDAO->save($well3dpoint);
                        }
                    }
                }
                
                
                // +add slot to DB
                // +calculate trajectory
                // +get points 0, HP, TP
                // calculate additional trajectories points
                
                
                
		// create 
                
                
	}
        
        protected function initForm() {
		$this->form = new CoreForm('post');
	}

	protected function createFormFields() {
            
		$this->form->addField(new CoreFormFieldText('A'));
		$this->form->addField(new CoreFormFieldText('H'));
                $this->form->addField(new CoreFormFieldText('L1'));
                $this->form->addField(new CoreFormFieldText('DLS'));
                $this->form->addField(new CoreFormFieldText('DLS2'));
		$this->form->addField(new CoreFormFieldText('alfa2'));
                $this->form->addField(new CoreFormFieldText('L3'));
                $this->form->addField(new CoreFormFieldText('targetInclination'));
                $this->form->addField(new CoreFormFieldText('targetAzimuth'));
                $this->form->addField(new CoreFormFieldText('DeltaX'));
                $this->form->addField(new CoreFormFieldText('DeltaY'));
                $this->form->addField(new CoreFormFieldText('DeltaZ'));
                $this->form->addField(new CoreFormFieldText('Azimuth'));
                $this->form->addField(new CoreFormFieldText('TR'));
		$this->form->addField(new CoreFormFieldText('TL'));
		$this->form->addField(new CoreFormFieldText('BR'));
                $this->form->addField(new CoreFormFieldText('BL'));
                $this->form->addField(new CoreFormFieldText('SR'));
                $this->form->addField(new CoreFormFieldText('ST'));
                $this->form->addField(new CoreFormFieldText('SM'));
                $this->form->addField(new CoreFormFieldText('NOS'));
		$this->form->addField(new CoreFormFieldText('Variant'));
                $this->form->addField(new CoreFormFieldText('Name'));
                $this->form->addField(new CoreFormFieldText('SiteId'));
	}

	// OK
	protected function setFieldValuesFromRequest() {
		$this->form->setFieldValuesFromRequest();
	}
        
	
	public function display() {
		
		echo("OK");
		
	}
}
?>