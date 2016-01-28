<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoordinateAlgoritm
 *
 * @author Dariusz Mankowski
 */
class TrajectoryCalculations {
    protected $trajectory = null;
    protected $record = null;
    protected $sectionNumber = null;
    protected $trajectoryParams = null;
    
   public function calculateTrajectory($wellId, $step, $azimut){
            $wellDAO = new WellDAO();
           
            $this->record = $wellDAO->getRecordById($wellId);
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
            if(method_exists($this->trajectory, $this->record['wellTrajectoryVariant'])){ 
                
                if ($this->record['wellType'] == '2d'){
                    $trajectoryMethod = $this->record['wellTrajectoryVariant'];
                    $methodParameters = array();
                    $parameters = explode("_", $this->record['wellTrajectoryVariant']);
                    array_shift($parameters);
                    //print_r($parameters);
                    foreach ($parameters as $parameter){
                        $methodParameters[$parameter] = $this->record[$parameter];
                    }
                    //print_r($methodParameters);
                    //print_r($trajectoryMethod);
                    $this->trajectoryParams = call_user_func_array(array($this->trajectory, $trajectoryMethod), $methodParameters);
                    if (!empty($this->trajectoryParams['error'])){
                    //    $this->errorMessageContainer = new CoreFormValidationMessageContainer();
                    //  $this->errorMessageContainer->addMessage('parametrError');
                        return 0;
                    }
                            //$this->trajectory->$trajectoryMethod(extract($methodParameters));
                    
                    
                    
                   
                
                        $this->trajectoryTable = $algoritm->calculateCoordinates($this->sectionNumber, $this->trajectoryParams['A'], $this->trajectoryParams['H'], $this->trajectoryParams['L'], $this->trajectoryParams['alfa'], $this->trajectoryParams['delta'], $this->trajectoryParams['DLS'], $this->trajectoryParams['R'], $azimut, $step);

                        $firstElements = array_values($this->trajectoryTable);
                        $firstElement = $firstElements[0];
                        //print_r($firstElement);

                        if ($this->record['vsection'] !== null){
                            $angleParametr = $this->record['vsection'];
                        }
                        else {
                            $angleParametr = $algoritm->calculateVsAngle($this->trajectoryTable[1], $this->trajectoryTable[count($this->trajectoryTable)]);
                        } 
                        $algoritm->calculateVerticalSection($this->trajectoryTable,  $angleParametr);
                        
                        /*foreach($this->trajectoryTable as $key => $telement){
                            //print_r($telement);

                            $kx = $telement['Y'] - $firstElement['Y'];
                            $ky = $telement['X'] - $firstElement['X'];
                            $dept = sqrt(pow($kx,2) + pow($ky,2));

                            if ($ky==0){
                                $angle = 0; 
                            }
                            else $angle = rad2deg(atan(abs($kx/$ky)));

                            if (($kx=0) && ($ky=0)){
                                $angle = 0;
                            }    
                            if (($kx>=0)&&($ky>0)) $angle = $angle;
                            if (($kx>0) && ($ky==0)) $angle = rad2deg(pi()/2);
                            if (($kx>=0) && ($ky<0)) $angle = rad2deg(pi()) - $angle;
                            if (($kx<=0) && ($ky<0)) $angle = rad2deg(pi()) + $angle;
                            if (($kx<0) && ($ky==0))  $angle = rad2deg(3*pi()/2);
                            if (($kx<=0) && ($ky>0)) $angle = rad2deg(2*pi()) - $angle;

                            $this->trajectoryTable[$key]['CL_DEP'] = $dept;
                            $this->trajectoryTable[$key]['CL_Angle'] = $angle;
                            //$this->trajectoryTable[$key]['VS_Displacment'] = $dept*cos(deg2rad($angle-vs_angle));              
                        }*/

                        //print_r($this->trajectoryTable);

                        foreach($this->trajectoryTable as $key => $trajectoryTableElement){
                                $this->trajectoryTable[$key]['alfa'] = rad2deg($trajectoryTableElement['alfa']);
                                $this->trajectoryTable[$key]['beta'] = rad2deg($trajectoryTableElement['beta']); 
                        }

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
                            $this->trajectory3DRender = null;
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
                                $tmpArr['y'] =  1 * ($element['Y'] + floatval($this->record['tvectorY'])); 
                                $tmpArr['z'] =  1 * ($element['Z'] + floatval($this->record['tvectorZ'])); 
                                $tmpArr['md'] =  1 * $element['MD']; 
                                $this->trajectory3DRender[] =  $tmpArr;
                            }
                            $this->trajectory3DRenderParam['render'] = $this->trajectory3DRender;
                            $this->trajectory3DRenderParam['trayectory'] = $this->trajectoryTable;
                            $this->trajectory3DRenderParam['xMax'] = 1 * $xmax;
                            $this->trajectory3DRenderParam['yMax'] = 1 * $ymax;
                            $this->trajectory3DRenderParam['zMax'] = 1 * $zmax;
                            $this->trajectory3DRenderParam['mdMax'] = 1 * $mdmax;
                            $this->trajectory3DRenderParam['color'] = $this->record['color'];
                            $this->trajectory3DRenderParam['wellName'] = $this->record['wellName'];
                        }
                        //print_r($this->trajectoryTable);
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
                                $this->trajectoryTable = $algoritm->r3pocz($np, $MX, $MY, $MZ, $MLP, $Malfa, $Mbeta, $step, $this->record['vsection']); 
                            }
                        }
                        else{
                            if ($np > 0){
                                $this->trajectoryTable = $algoritm->r3konc($np, $MX, $MY, $MZ, $MLP, $Malfa, $Mbeta, $step, $this->record['vsection']);
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
                            $this->trajectory3DRender = null;
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
                                
                                $tmpArr['x'] =  ($element['X'] + floatval($this->record['tvectorX']));   
                                $tmpArr['y'] =  ($element['Y'] + floatval($this->record['tvectorY'])); 
                                $tmpArr['z'] =  ($element['Z'] + floatval($this->record['tvectorZ'])); 
                                $tmpArr['md'] = $element['MD']; 
                                $this->trajectory3DRender[] =  $tmpArr;
                            }
                            $this->trajectory3DRenderParam['render'] = $this->trajectory3DRender;
                            //$this->trajectory3DRenderParam['trajectory'] = $this->trajectoryTable;
                            $this->trajectory3DRenderParam['xMax'] = 1 * $xmax;
                            $this->trajectory3DRenderParam['yMax'] = 1 * $ymax;
                            $this->trajectory3DRenderParam['zMax'] = 1 * $zmax;
                            $this->trajectory3DRenderParam['mdMax'] = 1 * $mdmax;
                            $this->trajectory3DRenderParam['color'] = $this->record['color'];
                            $this->trajectory3DRenderParam['wellName'] = $this->record['wellName'];
                        }
                    }
                    //print_r($this->trajectory3DRenderParam);
                        return $this->trajectory3DRenderParam;
   }
    

}
