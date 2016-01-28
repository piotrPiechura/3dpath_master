<?php
require 'equation.class.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TrayectoryVariantsControler
 *
 * @author darek
 */
class TrajectoryVariant {
  
    // trajectory 
    protected $equation = null;
    protected $resArr = null;
    
    
    public function __construct() {
        $this->equation = new equation();
        $this->validator = new TrajectoryValidator();
    }
    
    public function j1_A_H_L1($A,$H,$L1){
        
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
         
        $this->resArray['A'][1] = 0;
        $this->resArray['A'][2] = $A;
        $this->resArray['H'][1] = $L1;
       
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        $this->resArray['H'][2] = $this->resArray['H']['sum'] - $this->resArray['H'][1];
        if (!$this->validator->validate($this->resArray['H'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['alfa'][2] = 2 * atan(($this->resArray['A'][2]/$this->resArray['H'][2]));
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        if (!$this->validator->validate(sin($this->resArray['alfa'][2]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = $this->resArray['H'][2] / sin($this->resArray['alfa'][2]); 
        if (!$this->validator->validate($this->resArray['R'][2],'biggerThanZero') ){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['DLS'][2] = rad2deg((1 / $this->resArray['R'][2]) * 30.48);
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2];       
        return $this->resArray;
    }
    
    public function j1_H_alfa2_DLS2($H,$alfa2,$DLS2){
        $this->resArray['H']['sum'] = $H;
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        $this->resArray['delta'][2] = $this->resArray['alfa'][2]; 
        
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = 2 * $this->resArray['R'][2] * pow(sin(($this->resArray['alfa'][2]/2)),2);
        $this->resArray['A']['sum'] = $this->resArray['A'][2];
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2];
        $this->resArray['DLS'][2] = $DLS2;
        return $this->resArray;
    }
    
    public function j1_A_H_DLS2($A,$H,$DLS2){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['A'][2] = $this->resArray['A']['sum'];
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        if (!$this->validator->validate($this->resArray['R'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['alfa'][2] = 2 * asin(sqrt(($this->resArray['A'][2]/(2 * $this->resArray['R'][2]))));
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2];
        $this->resArray['DLS'][2] = $DLS2;
        return $this->resArray;
    }

    public function j1_A_H_alfa2($A,$H,$alfa2){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['A'][2] = $this->resArray['A']['sum'];
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        if (!$this->validator->validate(tan(($this->resArray['alfa'][2]/2)),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['H'][2] =  $this->resArray['A'][2] / tan(($this->resArray['alfa'][2]/2));
        if (!$this->validator->validate(sin($this->resArray['alfa'][2]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = $this->resArray['H'][2] / sin($this->resArray['alfa'][2]);
        $this->resArray['DLS'][2]  = rad2deg((1 / $this->resArray['R'][2]) * 30.48);
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2];
        return $this->resArray;
    }
    
    public function j1_H_L1_alfa2($H,$L1,$alfa2){
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['H'][1] = $this->resArray['L'][1];
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['H'][2] = $this->resArray['H']['sum'] - $this->resArray['H'][1];
        //$this->resArray['H'][2] =  $this->resArray['A'][2] / tan(($this->resArray['alfa'][2]/2));
        if (!$this->validator->validate(sin($this->resArray['alfa'][2]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = $this->resArray['H'][2] / sin($this->resArray['alfa'][2]);
        if (!$this->validator->validate($this->resArray['R'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['DLS'][2]  = rad2deg((1 / $this->resArray['R'][2]) * 30.48);
        $this->resArray['A'][2] = 2 * $this->resArray['R'][2] * pow(sin(($this->resArray['alfa'][2]/2)),2);
        $this->resArray['A']['sum'] = $this->resArray['A'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2];
        return $this->resArray;   
    }

    public function j2_A_H_L1_DLS($A,$H,$L1,$DLS2){
        
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['L'][1] = $L1;
        
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['A'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        $this->resArray['DLS'][3] = 0;
        
        $this->resArray['H'][1] = $this->resArray['L'][1];
        
        $this->resArray['R'][2] =  $this->equation->R_j($this->resArray['DLS'][2]);
        $this->resArray['alfa'][2] = $this->equation->Alfa($this->resArray['R'][2], $this->resArray['A']['sum'], $this->resArray['H']['sum'], $this->resArray['L'][1]);
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        $this->resArray['delta'][2] = $this->equation->Delta($this->resArray['alfa'][2], $this->resArray['alfa'][1]); 
        $this->resArray['H'][2] = $this->equation->H_j($this->resArray['R'][2], $this->resArray['alfa'][2], $this->resArray['alfa'][1]);
        $this->resArray['A'][2] = $this->equation->A_j($this->resArray['R'][2], $this->resArray['alfa'][2], $this->resArray['alfa'][1]);
        $this->resArray['L'][2] = $this->equation->L_j($this->resArray['delta'][2], $this->resArray['R'][2]);
        $this->resArray['H'][3] = $this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][2];
        $this->resArray['A'][3] = $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2];  
        $this->resArray['L'][3] = $this->equation->L_j_2($this->resArray['A'][3], $this->resArray['H'][3]);
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] + $this->resArray['L'][3];
        $this->resArray['DLS'][2] = $DLS2;
        return $this->resArray;
    }
    
    public function j2_A_H_alfa2_DLS2($A,$H,$alfa2,$DLS2){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['alfa'][2] =  deg2rad($alfa2);
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][1] = 0;
        $this->resArray['DLS'][3] = 0;

        
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['R'][2] =  $this->equation->R_j($this->resArray['DLS'][2]);
        $this->resArray['H'][2] = $this->equation->H_j($this->resArray['R'][2], $this->resArray['alfa'][2],0);
        $this->resArray['A'][2] = $this->equation->A_j($this->resArray['R'][2], $this->resArray['alfa'][2], $this->resArray['alfa'][1]);
        $this->resArray['L'][2] = $this->equation->L_j($this->resArray['delta'][2], $this->resArray['R'][2]);
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        $this->resArray['A'][3] = $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2]; 
        $this->resArray['L'][3] = $this->equation->L_j_3($this->resArray['A'][3], $this->resArray['alfa'][3]);
        $this->resArray['H'][3] = $this->resArray['L'][3] * cos($this->resArray['alfa'][3]);
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2] - $this->resArray['H'][3];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] + $this->resArray['L'][3];
        $this->resArray['DLS'][2] = $DLS2;
        return $this->resArray;   
    }
    
    public function j2_A_H_L3_alfa2($A,$H,$L3,$alfa2){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['L'][3] = $L3;
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][1] = 0;
        $this->resArray['DLS'][3] = 0;
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['A'][3] = $this->resArray['L'][3] * sin($this->resArray['alfa'][3]);
        $this->resArray['H'][3] = sqrt(pow($this->resArray['L'][3],2) - pow($this->resArray['A'][3],2));
        $this->resArray['A'][2] = $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][3];
        $this->resArray['R'][2] = $this->resArray['A'][2] / (1 - cos($this->resArray['alfa'][2]));
        $this->resArray['R'][3] = 0;
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        if (!$this->validator->validate($this->resArray['R'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['DLS'][2] = rad2deg((1 / $this->resArray['R'][2]) * 30.48);
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2] - $this->resArray['H'][3];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] + $this->resArray['L'][3];
        return $this->resArray;   
    }
    
    public function j2_H_L1_L3_alfa2($H,$L1,$L3,$alfa2){
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
        $this->resArray['L'][3] = $L3;
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][1] = 0;
        $this->resArray['DLS'][3] = 0;
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['A'][3] = $this->resArray['L'][3] * sin($this->resArray['alfa'][3]);
        $this->resArray['H'][3] = sqrt(pow($this->resArray['L'][3],2) - pow($this->resArray['A'][3],2));
        $this->resArray['H'][1] = $this->resArray['L'][1];
        $this->resArray['H'][2] = $this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][3];
        if (!$this->validator->validate(sin($this->resArray['alfa'][2]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = $this->resArray['H'][2] / sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        if (!$this->validator->validate($this->resArray['R'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['DLS'][2]  = 1 / $this->resArray['R'][2]; //!!!!
        $this->resArray['A']['sum'] = $this->resArray['A'][1] + $this->resArray['A'][2] + $this->resArray['A'][3];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] + $this->resArray['L'][3];
        $this->resArray['DLS'][2] = rad2deg($this->resArray['DLS'][2] * 30.48);
        return $this->resArray;
    }
    
    public function j2_A_H_L1_L3($A,$H,$L1,$L3){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
        $this->resArray['L'][3] = $L3;
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][1] = 0;
        $this->resArray['DLS'][3] = 0;
        
        $this->resArray['H'][1] = $this->resArray['L'][1];
        $this->resArray['R'][2] = $this->equation->R_j_2($this->resArray['A']['sum'], $this->resArray['L'][3], $this->resArray['H']['sum'], $this->resArray['L'][1] );
        
        $this->resArray['DLS'][2]  = rad2deg((1 / $this->resArray['R'][2]) * 30.48);
        $this->resArray['alfa'][2] = $this->equation->Alfa($this->resArray['R'][2], $this->resArray['A']['sum'], $this->resArray['H']['sum'], $this->resArray['L'][1]);
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        $this->resArray['H'][3] = $this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][2];
        $this->resArray['A'][3] = $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] + $this->resArray['L'][3];
        return $this->resArray;
    }
    
    public function j3_A_H_L1_alfa2_alfa4_DLS2_DLS4($A,$H,$L1,$alfa2,$alfa4,$DLS2,$DLS4){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][4] = $this->equation->DLS2m($DLS4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['H'][1] = $this->resArray['L'][1];
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / ($this->resArray['DLS'][2]);
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        
        $this->resArray['R'][3] = 0;
        $this->resArray['delta'][3] = 0;
        $this->resArray['DLS'][3] = 0;
        
        $this->resArray['delta'][4] = $this->resArray['alfa'][4] - $this->resArray['alfa'][3];
        $this->resArray['R'][4] = 1 / ($this->resArray['DLS'][4] );
        $this->resArray['L'][4] = $this->resArray['delta'][4] * $this->resArray['R'][4];
        $this->resArray['H'][4] = $this->resArray['R'][4] * (sin($this->resArray['alfa'][4]) - sin($this->resArray['alfa'][3]));
        $this->resArray['A'][4] = $this->resArray['R'][4] * (cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][4]));
        
        $this->resArray['alfa'][5] = $this->resArray['alfa'][4];
        $this->resArray['delta'][5] = 0;
        $this->resArray['R'][5] = 0;
        $this->resArray['DLS'][5] = 0;
        
        $this->resArray['L'][5] = (
                                  (
                                  ($this->resArray['A']['sum'] - $this->resArray['A'][2] - $this->resArray['A'][4])
                                  * cos($this->resArray['alfa'][3])
                                  ) / sin(($this->resArray['alfa'][5] - $this->resArray['alfa'][3]))
                                  ) - (
                                  (
                                  ($this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][2]-$this->resArray['H'][4])
                                  *sin($this->resArray['alfa'][3])
                                  )
                                  / sin($this->resArray['alfa'][5]-$this->resArray['alfa'][3])
                                  );
        $this->resArray['H'][5] = $this->resArray['L'][5] * cos($this->resArray['alfa'][5]);
        $this->resArray['A'][5] = $this->resArray['L'][5] * sin($this->resArray['alfa'][5]);
        
        $this->resArray['L'][3] = ($this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][2]-$this->resArray['H'][4])
                                   / cos($this->resArray['alfa'][3])
                                   -
                                   $this->resArray['L'][5]*cos($this->resArray['alfa'][5]) / cos($this->resArray['alfa'][3]);
        
        $this->resArray['H'][3] = $this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][2]- $this->resArray['H'][4] - $this->resArray['H'][5];
        $this->resArray['A'][3] = $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2]- $this->resArray['A'][4] - $this->resArray['A'][5];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] +$this->resArray['L'][4] + $this->resArray['L'][5];
        $this->resArray['DLS'][2] = $DLS2;
        $this->resArray['DLS'][4] = $DLS4;
        return $this->resArray;
    }
    
    public function j3_A_H_L3_alfa2_alfa4_DLS2_DLS4($A,$H,$L3,$alfa2,$alfa4,$DLS2,$DLS4){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][3] = $L3;
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][4] = $this->equation->DLS2m($DLS4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / ($this->resArray['DLS'][2]);
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][3] = 0;
        $this->resArray['H'][3] = $this->resArray['L'][3] * cos($this->resArray['alfa'][3]);
        $this->resArray['A'][3] = $this->resArray['L'][3] * sin($this->resArray['alfa'][3]);
        
        $this->resArray['delta'][4] = $this->resArray['alfa'][4] - $this->resArray['alfa'][2];
        if (!$this->validator->validate($this->resArray['DLS'][4],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][4] = 1 / ($this->resArray['DLS'][4] );
        $this->resArray['L'][4] = $this->resArray['delta'][4] * $this->resArray['R'][4];
        $this->resArray['H'][4] = $this->resArray['R'][4] * (sin($this->resArray['alfa'][4]) - sin($this->resArray['alfa'][3]));
        $this->resArray['A'][4] = $this->resArray['R'][4] * (cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][4]));
        
        $this->resArray['alfa'][5] = $this->resArray['alfa'][4];
        $this->resArray['delta'][5] = 0;
        $this->resArray['R'][5] = 0;
        $this->resArray['DLS'][5] = 0;
        
        $this->resArray['A'][5] = $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2]- $this->resArray['A'][3] - $this->resArray['A'][4];
        if (!$this->validator->validate(sin($this->resArray['alfa'][5]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['L'][5] = $this->resArray['A'][5] / sin($this->resArray['alfa'][5]);
        $this->resArray['H'][5] = $this->resArray['L'][5] * cos($this->resArray['alfa'][5]);
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2] - $this->resArray['H'][3]- $this->resArray['H'][4] - $this->resArray['H'][5];
        $this->resArray['L'][1] =  $this->resArray['H'][1];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] +$this->resArray['L'][4] + $this->resArray['L'][5];
        return $this->resArray; 
    }
    
    public function j3_A_H_L5_alfa2_alfa4_DLS2_DLS4($A,$H,$L5,$alfa2,$alfa4,$DLS2,$DLS4){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][5] = $L5;
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][4] = $this->equation->DLS2m($DLS4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / ($this->resArray['DLS'][2]);
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][3] = 0;
        
        $this->resArray['delta'][4] = $this->resArray['alfa'][4] - $this->resArray['alfa'][2];
        if (!$this->validator->validate($this->resArray['DLS'][4],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][4] = 1 / ($this->resArray['DLS'][4] );
        $this->resArray['L'][4] = $this->resArray['delta'][4] * $this->resArray['R'][4];
        $this->resArray['H'][4] = $this->resArray['R'][4] * (sin($this->resArray['alfa'][4]) - sin($this->resArray['alfa'][3]));
        $this->resArray['A'][4] = $this->resArray['R'][4] * (cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][4]));
        
        $this->resArray['alfa'][5] = $this->resArray['alfa'][4];
        $this->resArray['delta'][5] = 0;
        $this->resArray['R'][5] = 0;
        $this->resArray['DLS'][5] = 0;
        
        $this->resArray['H'][5] = $this->resArray['L'][5] * cos($this->resArray['alfa'][5]);
        $this->resArray['A'][5] = $this->resArray['L'][5] * sin($this->resArray['alfa'][5]);
        $this->resArray['A'][3] = $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2]- $this->resArray['A'][4] - $this->resArray['A'][5];
        if (!$this->validator->validate(sin($this->resArray['alfa'][3]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['L'][3] = $this->resArray['A'][3] / sin($this->resArray['alfa'][3]);
        $this->resArray['H'][3] = $this->resArray['L'][3] * cos($this->resArray['alfa'][3]);
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2] - $this->resArray['H'][3]- $this->resArray['H'][4] - $this->resArray['H'][5];
        $this->resArray['L'][1] =  $this->resArray['H'][1];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] +$this->resArray['L'][4] + $this->resArray['L'][5];
        $this->resArray['DLS'][2] = $DLS2;
        $this->resArray['DLS'][4] = $DLS4;
        return $this->resArray;
    }
    
    public function j3_H_L1_L3_L5_alfa2_alfa4_DLS4($H,$L1,$L3,$L5,$alfa2,$alfa4,$DLS4){ 
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
        $this->resArray['L'][3] = $L3;
        $this->resArray['L'][5] = $L5;
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        $this->resArray['DLS'][4] = $this->equation->DLS2m($DLS4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['H'][1] = $this->resArray['L'][1];
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][3] = 0;
        $this->resArray['H'][3] = $this->resArray['L'][3] * cos($this->resArray['alfa'][3]);
        $this->resArray['A'][3] = $this->resArray['L'][3] * sin($this->resArray['alfa'][3]);
        
        $this->resArray['delta'][4] = $this->resArray['alfa'][4] - $this->resArray['alfa'][2];
        if (!$this->validator->validate($this->resArray['DLS'][4],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][4] = 1 / ($this->resArray['DLS'][4] );
        $this->resArray['L'][4] = $this->resArray['delta'][4] * $this->resArray['R'][4];
        $this->resArray['H'][4] = $this->resArray['R'][4] * (sin($this->resArray['alfa'][4]) - sin($this->resArray['alfa'][3]));
        $this->resArray['A'][4] = $this->resArray['R'][4] * (cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][4]));
        
        $this->resArray['alfa'][5] = $this->resArray['alfa'][4];
        $this->resArray['delta'][5] = 0;
        $this->resArray['R'][5] = 0;
        $this->resArray['DLS'][5] = 0;
        $this->resArray['H'][5] = $this->resArray['L'][5] * cos($this->resArray['alfa'][5]);
        $this->resArray['A'][5] = $this->resArray['L'][5] * sin($this->resArray['alfa'][5]);
        
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['H'][2] = $this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][3]- $this->resArray['H'][4] - $this->resArray['H'][5];
        if (!$this->validator->validate( sin($this->resArray['alfa'][2]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = $this->resArray['H'][2] / sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        if (!$this->validator->validate( $this->resArray['R'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['DLS'][2] = rad2deg(1/ $this->resArray['R'][2] * 30.48);
        
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] +$this->resArray['L'][4] + $this->resArray['L'][5];
        $this->resArray['A']['sum'] = $this->resArray['A'][1] + $this->resArray['A'][2] +$this->resArray['A'][3] +$this->resArray['A'][4] + $this->resArray['A'][5];
        $this->resArray['DLS'][4] = $DLS4;
        return $this->resArray;
        
    }
    
    public function j3_H_L1_L3_L5_alfa2_alfa4_DLS2($H,$L1,$L3,$L5,$alfa2,$alfa4,$DLS2){ 
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
        $this->resArray['L'][3] = $L3;
        $this->resArray['L'][5] = $L5;
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['H'][1] = $this->resArray['L'][1];
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / ($this->resArray['DLS'][2] );
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][3] = 0;
        $this->resArray['H'][3] = $this->resArray['L'][3] * cos($this->resArray['alfa'][3]);
        $this->resArray['A'][3] = $this->resArray['L'][3] * sin($this->resArray['alfa'][3]);
        
        $this->resArray['alfa'][5] = $this->resArray['alfa'][4];
        $this->resArray['delta'][5] = 0;
        $this->resArray['R'][5] = 0;
        $this->resArray['DLS'][5] = 0;
        $this->resArray['H'][5] = $this->resArray['L'][5] * cos($this->resArray['alfa'][5]);
        $this->resArray['A'][5] = $this->resArray['L'][5] * sin($this->resArray['alfa'][5]);
        
        $this->resArray['delta'][4] = $this->resArray['alfa'][4] - $this->resArray['alfa'][2];
        $this->resArray['H'][4] = $this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][2]- $this->resArray['H'][3] - $this->resArray['H'][5];
        if (!$this->validator->validate((sin($this->resArray['alfa'][4]) - sin($this->resArray['alfa'][3])),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][4] = $this->resArray['H'][4] / (sin($this->resArray['alfa'][4]) - sin($this->resArray['alfa'][3]));
        if (!$this->validator->validate($this->resArray['R'][4],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['DLS'][4] = rad2deg(1/ $this->resArray['R'][4] * 30.48);
        $this->resArray['A'][4] = $this->resArray['R'][4] * (cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][4]));
        $this->resArray['L'][4] = $this->resArray['delta'][4] * $this->resArray['R'][4];
        
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] +$this->resArray['L'][4] + $this->resArray['L'][5];
        $this->resArray['A']['sum'] = $this->resArray['A'][1] + $this->resArray['A'][2] +$this->resArray['A'][3] +$this->resArray['A'][4] + $this->resArray['A'][5];
        $this->resArray['DLS'][2] = $DLS2;
        return $this->resArray;
        
    }
    
    public function s1_A_A2_H_DLS2_DLS3($A, $A2, $H, $DLS2, $DLS3){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['A'][2] = $A2;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][3] = $this->equation->DLS2m($DLS3);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        if (!$this->validator->validate($this->resArray['R'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['alfa'][2] = acos(1 - ($this->resArray['A'][2] / $this->resArray['R'][2]));
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        if (!$this->validator->validate($this->resArray['DLS'][3],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][3] = 1 / ($this->resArray['DLS'][3] );
        $this->resArray['A'][3] = $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2];
        $this->resArray['alfa'][3] = acos($this->resArray['A'][3]/$this->resArray['R'][3] + cos($this->resArray['alfa'][2]));
        $this->resArray['H'][3] = $this->resArray['R'][3] *  (sin($this->resArray['alfa'][2]) - sin($this->resArray['alfa'][3]));
        $this->resArray['delta'][3] = $this->resArray['alfa'][2] - $this->resArray['alfa'][3];
        $this->resArray['L'][3] = $this->resArray['delta'][3] * $this->resArray['R'][3];
        
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2] - $this->resArray['H'][3];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3];
        $this->resArray['DLS'][2] = $DLS2;
        $this->resArray['DLS'][3] = $DLS3;
        return $this->resArray;
    }
    
    public function s1_A_A2_H_H2_alfa3($A, $A2, $H, $H2, $alfa3){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['A'][2] = $A2;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['H'][2] = $H2;
        $this->resArray['alfa'][3] = deg2rad($alfa3);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['alfa'][2] = 2 * atan($this->resArray['A'][2] / $this->resArray['H'][2]);
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        if (!$this->validator->validate((2 * pow(sin($this->resArray['alfa'][2]/2),2)),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = $this->resArray['A'][2] / (2 * pow(sin($this->resArray['alfa'][2]/2),2));
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['DLS'][2] = rad2deg(1/ $this->resArray['R'][2] * 30.48);
        
        $this->resArray['A'][3] = $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2];
        if (!$this->validator->validate( (cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][2])),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][3] = $this->resArray['A'][3] / (cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][2]));
        $this->resArray['H'][3] = $this->resArray['R'][3] *  (sin($this->resArray['alfa'][2]) - sin($this->resArray['alfa'][3]));
        $this->resArray['delta'][3] = $this->resArray['alfa'][2] - $this->resArray['alfa'][3];
        $this->resArray['L'][3] = $this->resArray['delta'][3] * $this->resArray['R'][3];
        if (!$this->validator->validate( $this->resArray['R'][3],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['DLS'][3] = rad2deg(1/ $this->resArray['R'][3] * 30.48);
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2] - $this->resArray['H'][3];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3];
        
        return $this->resArray; 
    }
    
    public function s1_A_H_DLS2_alfa2_alfa3($A, $H, $DLS2, $alfa2, $alfa3){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['alfa'][3] = deg2rad($alfa3);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        if (!$this->validator->validate( $this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        
        $this->resArray['delta'][3] = $this->resArray['alfa'][2] - $this->resArray['alfa'][3];
        $this->resArray['A'][3] = $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2];
        if (!$this->validator->validate((cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][2])),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][3] = $this->resArray['A'][3] / (cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][2]));
        $this->resArray['H'][3] = $this->resArray['R'][3] *  (sin($this->resArray['alfa'][2]) - sin($this->resArray['alfa'][3]));
        $this->resArray['L'][3] = $this->resArray['delta'][3] * $this->resArray['R'][3];
        if (!$this->validator->validate( $this->resArray['R'][3],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['DLS'][3] = rad2deg(1/ $this->resArray['R'][3] * 30.48);
        
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2] - $this->resArray['H'][3];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3];
        
        return $this->resArray;
    }
    
    public function s1_H_L1_DLS2_DLS3_alfa3($H, $L1, $DLS2, $DLS3, $alfa3){
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][3] = $this->equation->DLS2m($DLS3);
        $this->resArray['alfa'][3] = deg2rad($alfa3);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        $this->resArray['H'][1] = $this->resArray['L'][1];
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        if (!$this->validator->validate($this->resArray['DLS'][3],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][3] = 1 / $this->resArray['DLS'][3];
        
        $this->resArray['alfa'][2] = asin(($this->resArray['H']['sum'] - $this->resArray['L'][1] + $this->resArray['R'][3] * sin($this->resArray['alfa'][3])) / ($this->resArray['R'][2] + $this->resArray['R'][3]));
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['delta'][3] = $this->resArray['alfa'][2] - $this->resArray['alfa'][3];
        $this->resArray['L'][3] = $this->resArray['R'][3] * sin($this->resArray['alfa'][3]);
        $this->resArray['H'][3] = $this->resArray['R'][3] *  (sin($this->resArray['alfa'][2]) - sin($this->resArray['alfa'][3]));
        $this->resArray['A'][3] = $this->resArray['R'][3] *  (cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][2]));
        
        $this->resArray['A']['sum'] = $this->resArray['A'][1] + $this->resArray['A'][2] +$this->resArray['A'][3];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3];
        
        return $this->resArray;
        
    }
    
    public function s1_A_H_alfa3_DLS2_DLS3($A, $H, $alfa3, $DLS2, $DLS3){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][3] = $this->equation->DLS2m($DLS3);
        $this->resArray['alfa'][3] = deg2rad($alfa3);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        if (!$this->validator->validate($this->resArray['DLS'][3],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][3] = 1 / $this->resArray['DLS'][3];
        
        $this->resArray['alfa'][2] = asin(($this->resArray['R'][2] + $this->resArray['R'][3] * cos($this->resArray['alfa'][3]) - $this->resArray['A']['sum']) / ($this->resArray['R'][2] + $this->resArray['R'][3]));
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['delta'][3] = $this->resArray['alfa'][2] - $this->resArray['alfa'][3];
        $this->resArray['L'][3] = $this->resArray['R'][3] * sin($this->resArray['alfa'][3]);
        $this->resArray['H'][3] = $this->resArray['R'][3] *  (sin($this->resArray['alfa'][2]) - sin($this->resArray['alfa'][3]));
        $this->resArray['A'][3] = $this->resArray['R'][3] *  (cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][2]));
        
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2] - $this->resArray['H'][3];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3];
        
        return $this->resArray;
    }
    
    public function s2_A_H_L4_alfa4_DLS2_DLS3($A, $H,$L4, $alfa4, $DLS2, $DLS3){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][4] = $L4;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][3] = $this->equation->DLS2m($DLS3);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['delta'][4] = 0;
        $this->resArray['R'][4] = 0;
        $this->resArray['DLS'][4] = 0;
        
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        if (!$this->validator->validate($this->resArray['DLS'][3],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][3] = 1 / $this->resArray['DLS'][3];
        
        $this->resArray['alfa'][2] = acos(($this->resArray['R'][2] + $this->resArray['R'][3] * cos($this->resArray['alfa'][4]) + $this->resArray['L'][4] * sin($this->resArray['alfa'][4]) - $this->resArray['A']['sum'] )/ ($this->resArray['R'][2] + $this->resArray['R'][3]));
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][4];
        $this->resArray['delta'][3] = $this->resArray['alfa'][2] - $this->resArray['alfa'][3];
        $this->resArray['L'][3] = $this->resArray['delta'][3] * $this->resArray['R'][3];
        $this->resArray['H'][3] = $this->resArray['R'][3] *  (sin($this->resArray['alfa'][2]) - sin($this->resArray['alfa'][3]));
        $this->resArray['A'][3] = $this->resArray['R'][3] *  (cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][2]));
        
        $this->resArray['H'][4] = $this->resArray['L'][4] * cos($this->resArray['alfa'][4]);
        $this->resArray['A'][4] = $this->resArray['L'][4] * sin($this->resArray['alfa'][4]);
        
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2] - $this->resArray['H'][3] - $this->resArray['H'][4];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] + $this->resArray['L'][4];
        
        $this->resArray['DLS'][2] = $DLS2;
        $this->resArray['DLS'][3] = $DLS3;
        
        return $this->resArray;
    }
    
    public function s2_A_A2_H_H2_alfa4_DLS3($A, $A2, $H, $H2, $alfa4, $DLS3){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['A'][2] = $A2;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['H'][2] = $H2;
        $this->resArray['DLS'][3] = $this->equation->DLS2m($DLS3);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['delta'][4] = 0;
        $this->resArray['R'][4] = 0;
        $this->resArray['DLS'][4] = 0;
        
        if (!$this->validator->validate($this->resArray['H'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        
        $this->resArray['alfa'][2] = 2 * atan($this->resArray['A'][2] / $this->resArray['H'][2]);
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        if (!$this->validator->validate(( 2 * pow(sin($this->resArray['alfa'][2]/2),2)),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] =   $this->resArray['A'][2] /( 2 * pow(sin($this->resArray['alfa'][2]/2),2));
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        if (!$this->validator->validate($this->resArray['R'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['DLS'][2] = rad2deg(1/ $this->resArray['R'][2] * 30.48);
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][4];
        $this->resArray['delta'][3] = $this->resArray['alfa'][2] - $this->resArray['alfa'][3];
        $this->resArray['R'][3] = 1 / $this->resArray['DLS'][3];
        $this->resArray['L'][3] = $this->resArray['delta'][3] * $this->resArray['R'][3];
        $this->resArray['H'][3] = $this->resArray['R'][3] *  (sin($this->resArray['alfa'][2]) - sin($this->resArray['alfa'][3]));
        $this->resArray['A'][3] = $this->resArray['R'][3] *  (cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][2]));
        
        $this->resArray['A'][4] = $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2] - $this->resArray['A'][3];
        if (!$this->validator->validate(sin($this->resArray['alfa'][4]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['L'][4] = $this->resArray['A'][4] / sin($this->resArray['alfa'][4]);
        $this->resArray['H'][4] = $this->resArray['L'][4] * cos($this->resArray['alfa'][4]);
        
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2] - $this->resArray['H'][3] - $this->resArray['H'][4];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] + $this->resArray['L'][4];
        $this->resArray['DLS'][3] = $DLS3;
        
        return $this->resArray;
    }
    
    public function s2_A_H_alfa2_alfa4_DLS2_DLS3($A, $H, $alfa2, $alfa4, $DLS2, $DLS3){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][3] = $this->equation->DLS2m($DLS3);
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['delta'][4] = 0;
        $this->resArray['R'][4] = 0;
        $this->resArray['DLS'][4] = 0;
        
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][4];
        $this->resArray['delta'][3] = $this->resArray['alfa'][2] - $this->resArray['alfa'][3];
        if (!$this->validator->validate($this->resArray['DLS'][3],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][3] = 1 / $this->resArray['DLS'][3];
        $this->resArray['L'][3] = $this->resArray['delta'][3] * $this->resArray['R'][3];
        $this->resArray['H'][3] = $this->resArray['R'][3] *  (sin($this->resArray['alfa'][2]) - sin($this->resArray['alfa'][3]));
        $this->resArray['A'][3] = $this->resArray['R'][3] *  (cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][2]));
        
        $this->resArray['A'][4] = $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2] - $this->resArray['A'][3];
        if (!$this->validator->validate(sin($this->resArray['alfa'][4]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['L'][4] = $this->resArray['A'][4] / sin($this->resArray['alfa'][4]);
        $this->resArray['H'][4] = $this->resArray['L'][4] * cos($this->resArray['alfa'][4]);
        
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2] - $this->resArray['H'][3] - $this->resArray['H'][4];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] + $this->resArray['L'][4];
        
        $this->resArray['DLS'][2] = $DLS2;
        $this->resArray['DLS'][3] = $DLS3;
        
        return $this->resArray;
    }
    
    public function s2_H_L1_L4_alfa4_DLS2_DLS3($H, $L1, $L4, $alfa4, $DLS2, $DLS3){
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
        $this->resArray['L'][4] = $L4;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][3] = $this->equation->DLS2m($DLS3);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['H'][1] = $this->resArray['L'][1];
        
        $this->resArray['delta'][4] = 0;
        $this->resArray['R'][4] = 0;
        $this->resArray['DLS'][4] = 0;
        
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        
        if (!$this->validator->validate($this->resArray['DLS'][3],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        
        $this->resArray['R'][3] = 1 / $this->resArray['DLS'][3];
        
        $this->resArray['alfa'][2] = asin(($this->resArray['H']['sum'] - $this->resArray['L'][1] + $this->resArray['R'][3] * sin($this->resArray['alfa'][4]) - $this->resArray['L'][1] * cos($this->resArray['alfa'][4]) )/ ($this->resArray['R'][2] + $this->resArray['R'][3]));
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][4];
        $this->resArray['delta'][3] = $this->resArray['alfa'][2] - $this->resArray['alfa'][3];
        
        $this->resArray['L'][3] = $this->resArray['delta'][3] * $this->resArray['R'][3];
        $this->resArray['H'][3] = $this->resArray['R'][3] *  (sin($this->resArray['alfa'][2]) - sin($this->resArray['alfa'][3]));
        $this->resArray['A'][3] = $this->resArray['R'][3] *  (cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][2]));
        
        $this->resArray['H'][4] = $this->resArray['L'][4] * cos($this->resArray['alfa'][4]);
        $this->resArray['A'][4] = $this->resArray['L'][4] * sin($this->resArray['alfa'][4]);
        
        $this->resArray['A']['sum'] = $this->resArray['A'][1] + $this->resArray['A'][2] +$this->resArray['A'][3] + $this->resArray['A'][4];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] + $this->resArray['L'][4];
        
        $this->resArray['DLS'][2] = $DLS2;
        $this->resArray['DLS'][3] = $DLS3;
        
        return $this->resArray;
    }
    
    public function s2_L1_L4_alfa2_alfa4_DLS2_DLS3($L1, $L4, $alfa2, $alfa4, $DLS2, $DLS3){
        $this->resArray['L'][1] = $L1;
        $this->resArray['L'][4] = $L4;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][3] = $this->equation->DLS2m($DLS3);
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['H'][1] = $this->resArray['L'][1];
        
        $this->resArray['delta'][4] = 0;
        $this->resArray['R'][4] = 0;
        $this->resArray['DLS'][4] = 0;
        
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        if (!$this->validator->validate($this->resArray['DLS'][3],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][3] = 1 / $this->resArray['DLS'][3];
        
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][4];
        $this->resArray['delta'][3] = $this->resArray['alfa'][2] - $this->resArray['alfa'][3];
        
        $this->resArray['L'][3] = $this->resArray['delta'][3] * $this->resArray['R'][3];
        $this->resArray['H'][3] = $this->resArray['R'][3] *  (sin($this->resArray['alfa'][2]) - sin($this->resArray['alfa'][3]));
        $this->resArray['A'][3] = $this->resArray['R'][3] *  (cos($this->resArray['alfa'][3]) - cos($this->resArray['alfa'][2]));
        
        $this->resArray['H'][4] = $this->resArray['L'][4] * cos($this->resArray['alfa'][4]);
        $this->resArray['A'][4] = $this->resArray['L'][4] * sin($this->resArray['alfa'][4]);
        
        $this->resArray['A']['sum'] = $this->resArray['A'][1] + $this->resArray['A'][2] +$this->resArray['A'][3] + $this->resArray['A'][4];
        $this->resArray['H']['sum'] = $this->resArray['H'][1] + $this->resArray['H'][2] +$this->resArray['H'][3] + $this->resArray['H'][4];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] + $this->resArray['L'][4];
        
        $this->resArray['DLS'][2] = $DLS2;
        $this->resArray['DLS'][3] = $DLS3;
        
        return $this->resArray;
    }
    
    public function s3_A_H_L1_L3_alfa4_DLS4($A, $H, $L1, $L3, $alfa4, $DLS4){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
        $this->resArray['L'][3] = $L3;
        $this->resArray['DLS'][4] = $this->equation->DLS2m($DLS4);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['H'][1] = $this->resArray['L'][1];
        
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][3] = 0;
        
        $this->resArray['R'][4] = 1 / $this->resArray['DLS'][4];
        
        $this->resArray['R'][2] = (pow($this->resArray['L'][3],2) - pow($this->resArray['A']['sum'],2) - pow($this->resArray['H']['sum'],2) - pow($this->resArray['L'][1],2))
                                  /
                                  (2 * ($this->resArray['R'][4]* ( cos($this->resArray['alfa'][4]) -1 ) -  $this->resArray['A']['sum'])) 
                                  + 
                                  ( $this->resArray['A']['sum'] * $this->resArray['R'][4] * cos($this->resArray['alfa'][4]) + $this->resArray['H']['sum'] * $this->resArray['L'][1] - ($this->resArray['H']['sum'] * $this->resArray['R'][4] - $this->resArray['L'][1] * $this->resArray['R'][4]) * sin($this->resArray['alfa'][4]))
                                   /
                                  ($this->resArray['R'][4]* (cos($this->resArray['alfa'][4]) -1) - $this->resArray['A']['sum']);  
        
        
         $this->resArray['alfa'][2] = acos(
                                        (($this->resArray['R'][2]+$this->resArray['R'][4]) * ($this->resArray['R'][4]*cos($this->resArray['alfa'][4]) - $this->resArray['A']['sum'] + $this->resArray['R'][4]) + $this->resArray['L'][3] *($this->resArray['H']['sum'] - $this->resArray['H'][1] + $this->resArray['R'][4] * sin($this->resArray['alfa'][4])))
                                        /
                                        (pow($this->resArray['R'][2]+$this->resArray['R'][4],2) + pow($this->resArray['L'][3],2))
                                       );
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        if (!$this->validator->validate($this->resArray['R'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['DLS'][2] = rad2deg(1/ $this->resArray['R'][2] * 30.48);
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        $this->resArray['H'][3] = $this->resArray['L'][3] * cos($this->resArray['alfa'][3]);
        $this->resArray['A'][3] = $this->resArray['L'][3] * sin($this->resArray['alfa'][3]);
        
        $this->resArray['delta'][4] = $this->resArray['alfa'][3] - $this->resArray['alfa'][4];
        $this->resArray['L'][4] = $this->resArray['delta'][4] * $this->resArray['R'][4];
        $this->resArray['H'][4] = $this->resArray['R'][4] *  (sin($this->resArray['alfa'][3]) - sin($this->resArray['alfa'][4]));
        $this->resArray['A'][4] = $this->resArray['R'][4] *  (cos($this->resArray['alfa'][4]) - cos($this->resArray['alfa'][3]));
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] + $this->resArray['L'][4];

        $this->resArray['DLS'][4] = $DLS4;
        
        return $this->resArray;
    }
    
    public function s3_A_H_L1_L3_alfa4_DLS2($A, $H, $L1, $L3, $alfa4, $DLS2){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
        $this->resArray['L'][3] = $L3;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['H'][1] = $this->resArray['L'][1];
        
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][3] = 0;
        
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        
        $this->resArray['R'][4] = (pow($this->resArray['H']['sum']-$this->resArray['L'][1],2) - pow($this->resArray['L'][3],2) + pow($this->resArray['A']['sum'],2) - 2 * $this->resArray['R'][2] * $this->resArray['A']['sum'] )
                                  /
                                  (2 * ($this->resArray['A']['sum'] * cos($this->resArray['alfa'][4]) - ($this->resArray['H']['sum'] - $this->resArray['L'][1]) * sin($this->resArray['alfa'][4]) + $this->resArray['R'][2]* (1 - cos($this->resArray['alfa'][4]))));
        
        $this->resArray['alfa'][2] = acos(
                                        (($this->resArray['R'][2] + $this->resArray['R'][4]) * ($this->resArray['R'][4] * cos($this->resArray['alfa'][4]) - $this->resArray['A']['sum'] + $this->resArray['R'][4]) + $this->resArray['L'][3] * ($this->resArray['H']['sum'] -  $this->resArray['H'][1]  +  $this->resArray['R'][4] * sin($this->resArray['alfa'][4])))
                                        / 
                                        (pow($this->resArray['R'][2] + $this->resArray['R'][4],2) + pow($this->resArray['L'][3],2))
                                        );
        
        //$this->resArray['alfa'][2] = 2 * atan($this->resArray['A'][2] / $this->resArray['H'][2]);
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        if (!$this->validator->validate($this->resArray['R'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['DLS'][2] = rad2deg(1/ $this->resArray['R'][2] * 30.48);
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        $this->resArray['H'][3] = $this->resArray['L'][3] * cos($this->resArray['alfa'][3]);
        $this->resArray['A'][3] = $this->resArray['L'][3] * sin($this->resArray['alfa'][3]);
        
        $this->resArray['delta'][4] = $this->resArray['alfa'][3] - $this->resArray['alfa'][4];
        $this->resArray['L'][4] = $this->resArray['delta'][4] * $this->resArray['R'][4];
        $this->resArray['H'][4] = $this->resArray['R'][4] *  (sin($this->resArray['alfa'][3]) - sin($this->resArray['alfa'][4]));
        $this->resArray['A'][4] = $this->resArray['R'][4] *  (cos($this->resArray['alfa'][4]) - cos($this->resArray['alfa'][3]));
        
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] + $this->resArray['L'][4];
        
        $this->resArray['DLS'][2] = $DLS2;
  
        return $this->resArray;   
    }
    public function s3_A_H_alfa2_alfa4_DLS2_DLS4($A, $H, $alfa2, $alfa4, $DLS2, $DLS4){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][4] = $this->equation->DLS2m($DLS4);
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][3] = 0;
        
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        if (!$this->validator->validate($this->resArray['DLS'][4],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        
        $this->resArray['R'][4] = 1 / $this->resArray['DLS'][4];
        $this->resArray['delta'][4] = $this->resArray['alfa'][3] - $this->resArray['alfa'][4];
        $this->resArray['L'][4] = $this->resArray['delta'][4] * $this->resArray['R'][4];
        $this->resArray['H'][4] = $this->resArray['R'][4] *  (sin($this->resArray['alfa'][3]) - sin($this->resArray['alfa'][4]));
        $this->resArray['A'][4] = $this->resArray['R'][4] *  (cos($this->resArray['alfa'][4]) - cos($this->resArray['alfa'][3]));
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][4];
        
        $this->resArray['A'][3] = $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2] - $this->resArray['A'][4];
        
        if (!$this->validator->validate( sin($this->resArray['alfa'][3]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        
        $this->resArray['L'][3] = $this->resArray['A'][3] / sin($this->resArray['alfa'][3]);
        $this->resArray['H'][3] = $this->resArray['L'][3] * cos($this->resArray['alfa'][3]);
        
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2] - $this->resArray['H'][3] - $this->resArray['H'][4];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] + $this->resArray['L'][4];
        
        $this->resArray['DLS'][2] = $DLS2;
        $this->resArray['DLS'][4] = $DLS4;
        
        return $this->resArray;    
    }
    
    public function s3_H_L1_alfa2_alfa4_DLS2_DLS4($H, $L1, $alfa2, $alfa4, $DLS2, $DLS4){
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][4] = $this->equation->DLS2m($DLS4);
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['H'][1] = $this->resArray['L'][1];
        
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][3] = 0;
        
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        if (!$this->validator->validate($this->resArray['DLS'][4],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][4] = 1 / $this->resArray['DLS'][4];
        $this->resArray['delta'][4] = $this->resArray['alfa'][3] - $this->resArray['alfa'][4];
        $this->resArray['L'][4] = $this->resArray['delta'][4] * $this->resArray['R'][4];
        $this->resArray['H'][4] = $this->resArray['R'][4] *  (sin($this->resArray['alfa'][3]) - sin($this->resArray['alfa'][4]));
        $this->resArray['A'][4] = $this->resArray['R'][4] *  (cos($this->resArray['alfa'][4]) - cos($this->resArray['alfa'][3]));
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][4];
        
        $this->resArray['H'][3] = $this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][2] - $this->resArray['H'][4];
        if (!$this->validator->validate(cos($this->resArray['alfa'][3]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['L'][3] = $this->resArray['H'][3] / cos($this->resArray['alfa'][3]);
        $this->resArray['A'][3] = $this->resArray['L'][3] * sin($this->resArray['alfa'][3]);
        
        $this->resArray['A']['sum'] = $this->resArray['A'][1] + $this->resArray['A'][2] +$this->resArray['A'][3] + $this->resArray['A'][4];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] + $this->resArray['L'][4];
        
        $this->resArray['DLS'][2] = $DLS2;
        $this->resArray['DLS'][4] = $DLS4;
        
        
        return $this->resArray; 
    }
    
    public function s3_A_H_L1_alfa4_DLS2_DLS4($A, $H, $L1, $alfa4, $DLS2, $DLS4){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][4] = $this->equation->DLS2m($DLS4);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['H'][1] = $this->resArray['L'][1];
        
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][3] = 0;
        
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        if (!$this->validator->validate($this->resArray['DLS'][4],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][4] = 1 / $this->resArray['DLS'][4];
        if (!$this->validator->validate((pow($this->resArray['R'][2] + $this->resArray['R'][4],2) + pow($this->resArray['L'][3],2)),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['alfa'][2] = acos(
                                         (($this->resArray['R'][2] + $this->resArray['R'][4]) * ($this->resArray['R'][4] * cos($this->resArray['alfa'][4]) - $this->resArray['A']['sum'] + $this->resArray['R'][4]) + $this->resArray['L'][3] * ($this->resArray['H']['sum'] -  $this->resArray['H'][1]  +  $this->resArray['R'][4] * sin($this->resArray['alfa'][4])))
                                        /
                                        (pow($this->resArray['R'][2] + $this->resArray['R'][4],2) + pow($this->resArray['L'][3],2))
                                        );
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['delta'][4] = $this->resArray['alfa'][3] - $this->resArray['alfa'][4];
        $this->resArray['L'][4] = $this->resArray['delta'][4] * $this->resArray['R'][4];
        $this->resArray['H'][4] = $this->resArray['R'][4] *  (sin($this->resArray['alfa'][3]) - sin($this->resArray['alfa'][4]));
        $this->resArray['A'][4] = $this->resArray['R'][4] *  (cos($this->resArray['alfa'][4]) - cos($this->resArray['alfa'][3]));
        
        $this->resArray['H'][3] = $this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][2] - $this->resArray['H'][4];
        if (!$this->validator->validate(cos($this->resArray['alfa'][3]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['L'][3] = $this->resArray['H'][3] / cos($this->resArray['alfa'][3]);
        $this->resArray['A'][3] = $this->resArray['L'][3] * sin($this->resArray['alfa'][3]);
        
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] + $this->resArray['L'][4];
        
        $this->resArray['DLS'][2] = $DLS2;
        $this->resArray['DLS'][4] = $DLS4;
        
        
        return $this->resArray; 
    
    }
    
    public function s4_A_H_L1_alfa2_alfa4_DLS2_DLS4($A, $H, $L1,$alfa2, $alfa4, $DLS2, $DLS4 ){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][4] = $this->equation->DLS2m($DLS4);
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['H'][1] = $this->resArray['L'][1];
        
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][3] = 0;
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] *  (sin($this->resArray['alfa'][2]) - sin($this->resArray['alfa'][2]));
        $this->resArray['A'][2] = $this->resArray['R'][2] *  (cos($this->resArray['alfa'][2]) - cos($this->resArray['alfa'][2]));
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        $this->resArray['delta'][4] = $this->resArray['alfa'][3] - $this->resArray['alfa'][4];
        if (!$this->validator->validate($this->resArray['DLS'][4],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][4] = 1 / $this->resArray['DLS'][4];
        $this->resArray['L'][4] = $this->resArray['delta'][4] * $this->resArray['R'][4];
        $this->resArray['H'][4] = $this->resArray['R'][4] *  (sin($this->resArray['alfa'][3]) - sin($this->resArray['alfa'][4]));
        $this->resArray['A'][4] = $this->resArray['R'][4] *  (cos($this->resArray['alfa'][4]) - cos($this->resArray['alfa'][3]));
        
        $this->resArray['alfa'][5] = $this->resArray['alfa'][4];
        $this->resArray['delta'][5] = 0;
        $this->resArray['R'][5] = 0;
        $this->resArray['DLS'][5] = 0;
        
        $this->resArray['L'][5] =   ($this->resArray['A']['sum'] - $this->resArray['A'][2] - $this->resArray['A'][4]) * cos($this->resArray['alfa'][3]) 
                                    / 
                                    (sin($this->resArray['alfa'][5] - $this->resArray['alfa'][3]))
                                    -
                                    (($this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][2] - $this->resArray['H'][4])*sin($this->resArray['alfa'][3]))
                                    /
                                    (sin($this->resArray['alfa'][5] - $this->resArray['alfa'][3]));
        
        $this->resArray['H'][5] = $this->resArray['L'][5] * cos($this->resArray['alfa'][5]);
        $this->resArray['A'][5] = $this->resArray['L'][5] * sin($this->resArray['alfa'][5]);
        
        $this->resArray['L'][3] =  ($this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][2] - $this->resArray['H'][4])
                                    /
                                    (cos($this->resArray['alfa'][3]))
                                    -
                                    ($this->resArray['L'][5] * cos($this->resArray['alfa'][5]))
                                    /
                                    (cos($this->resArray['alfa'][3]));
         
        $this->resArray['H'][3] =  $this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][2] - $this->resArray['H'][4] - $this->resArray['H'][5];
        $this->resArray['A'][3] =  $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2] - $this->resArray['A'][4] - $this->resArray['A'][5];
        
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] + $this->resArray['L'][4] + $this->resArray['L'][5];
        
        $this->resArray['DLS'][2] = $DLS2;
        $this->resArray['DLS'][4] = $DLS4;
        
        
        return $this->resArray; 
    }
    
    public function s4_A_H_L3_alfa2_alfa4_DLS2_DLS4($A, $H, $L3, $alfa2, $alfa4, $DLS2, $DLS4){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][3] = $L3;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][4] = $this->equation->DLS2m($DLS4);
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] *  (sin($this->resArray['alfa'][2]) - sin($this->resArray['alfa'][2]));
        $this->resArray['A'][2] = $this->resArray['R'][2] *  (cos($this->resArray['alfa'][2]) - cos($this->resArray['alfa'][2]));
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];

        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][3] = 0;
        
        $this->resArray['H'][3] = $this->resArray['L'][3] * cos($this->resArray['alfa'][3]);
        $this->resArray['A'][3] = $this->resArray['L'][3] * sin($this->resArray['alfa'][3]);
        
        $this->resArray['delta'][4] = $this->resArray['alfa'][3] - $this->resArray['alfa'][4];
        if (!$this->validator->validate($this->resArray['DLS'][4],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][4] = 1 / $this->resArray['DLS'][4];
        $this->resArray['L'][4] = $this->resArray['delta'][4] * $this->resArray['R'][4];
        
        $this->resArray['H'][4] = $this->resArray['R'][4] *  (sin($this->resArray['alfa'][3]) - sin($this->resArray['alfa'][4]));
        $this->resArray['A'][4] = $this->resArray['R'][4] *  (cos($this->resArray['alfa'][4]) - cos($this->resArray['alfa'][3]));
        
        $this->resArray['alfa'][5] = $this->resArray['alfa'][4];
        $this->resArray['delta'][5] = 0;
        $this->resArray['R'][5] = 0;
        $this->resArray['DLS'][5] = 0;
        
        $this->resArray['A'][5] =  $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2] - $this->resArray['A'][3] - $this->resArray['A'][4];
        if (!$this->validator->validate(sin($this->resArray['alfa'][5]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['L'][5] = $this->resArray['A'][5] / sin($this->resArray['alfa'][5]);
        $this->resArray['H'][5] = $this->resArray['L'][5] * cos($this->resArray['alfa'][5]);
        
        $this->resArray['H'][1] =  $this->resArray['H']['sum'] - $this->resArray['H'][2] - $this->resArray['H'][3] - $this->resArray['H'][4] - $this->resArray['H'][5];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] + $this->resArray['L'][4] + $this->resArray['L'][5];
        
        $this->resArray['DLS'][2] = $DLS2;
        $this->resArray['DLS'][4] = $DLS4;
  
        return $this->resArray; 
    }
    
    public function s4_A_H_L5_alfa2_alfa4_DLS2_DLS4($A, $H, $L5, $alfa2, $alfa4, $DLS2, $DLS4){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][5] = $L5;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['DLS'][4] = $this->equation->DLS2m($DLS4);
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];

        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][3] = 0;
        
        $this->resArray['delta'][4] = $this->resArray['alfa'][3] - $this->resArray['alfa'][4];
        if (!$this->validator->validate($this->resArray['DLS'][4],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][4] = 1 / $this->resArray['DLS'][4];
        $this->resArray['L'][4] = $this->resArray['delta'][4] * $this->resArray['R'][4];
        $this->resArray['H'][4] = $this->resArray['R'][4] *  (sin($this->resArray['alfa'][3]) - sin($this->resArray['alfa'][4]));
        $this->resArray['A'][4] = $this->resArray['R'][4] *  (cos($this->resArray['alfa'][4]) - cos($this->resArray['alfa'][3]));
        
        $this->resArray['alfa'][5] = $this->resArray['alfa'][4];
        $this->resArray['delta'][5] = 0;
        $this->resArray['R'][5] = 0;
        $this->resArray['DLS'][5] = 0;
        
        $this->resArray['H'][5] = $this->resArray['L'][5] * cos($this->resArray['alfa'][5]);
        $this->resArray['A'][5] = $this->resArray['L'][5] * sin($this->resArray['alfa'][5]);
        
        $this->resArray['A'][3] =  $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2] - $this->resArray['A'][4] - $this->resArray['A'][5];
        if (!$this->validator->validate(sin($this->resArray['alfa'][3]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['L'][3] = $this->resArray['A'][3] / sin($this->resArray['alfa'][3]);
        $this->resArray['H'][3] = $this->resArray['L'][3] * cos($this->resArray['alfa'][3]);
        
        $this->resArray['H'][1] =  $this->resArray['H']['sum'] - $this->resArray['H'][2] - $this->resArray['H'][3] - $this->resArray['H'][4] - $this->resArray['H'][5];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] + $this->resArray['L'][4] + $this->resArray['L'][5];

        $this->resArray['DLS'][2] = $DLS2;
        $this->resArray['DLS'][4] = $DLS4;

        return $this->resArray; 
    }
    
    public function s4_A_H_L1_L3_alfa2_alfa4_DLS2($A, $H, $L1, $L3, $alfa2, $alfa4, $DLS2){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
        $this->resArray['L'][3] = $L3;
        $this->resArray['DLS'][2] = $this->equation->DLS2m($DLS2);
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['H'][1] = $this->resArray['L'][1];
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][3] = 0;
        if (!$this->validator->validate($this->resArray['DLS'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][2] = 1 / $this->resArray['DLS'][2];
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['H'][3] = $this->resArray['L'][3] * cos($this->resArray['alfa'][3]);
        $this->resArray['A'][3] = $this->resArray['L'][3] * sin($this->resArray['alfa'][3]);
        
        $this->resArray['delta'][4] = $this->resArray['alfa'][3] - $this->resArray['alfa'][4];
         
        $this->resArray['R'][4] =   ($this->resArray['A']['sum'] - $this->resArray['A'][2] - $this->resArray['A'][3]) * cos($this->resArray['alfa'][4]) 
                                    / 
                                    (1 - cos($this->resArray['alfa'][2] - $this->resArray['alfa'][4]))
                                    -
                                    (($this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][2] - $this->resArray['H'][3])*sin($this->resArray['alfa'][4]))
                                    /
                                    (1 - cos($this->resArray['alfa'][2] - $this->resArray['alfa'][4]));
        
        $this->resArray['DLS'][4] = rad2deg(1/ $this->resArray['R'][4] * 30.48);
        $this->resArray['L'][4] = $this->resArray['delta'][4] * $this->resArray['R'][4];
        $this->resArray['H'][4] = $this->resArray['R'][4] *  (sin($this->resArray['alfa'][3]) - sin($this->resArray['alfa'][4]));
        $this->resArray['A'][4] = $this->resArray['R'][4] *  (cos($this->resArray['alfa'][4]) - cos($this->resArray['alfa'][3]));
        
        $this->resArray['alfa'][5] = $this->resArray['alfa'][4];
        $this->resArray['delta'][5] = 0;
        $this->resArray['R'][5] = 0;
        $this->resArray['DLS'][5] = 0;
        
        $this->resArray['H'][5] =  $this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][2] - $this->resArray['H'][3] - $this->resArray['H'][4];
        $this->resArray['A'][5] =  $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2] - $this->resArray['A'][3] - $this->resArray['A'][4];
        if (!$this->validator->validate(sin($this->resArray['alfa'][5]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['L'][5] = $this->resArray['A'][5] / sin($this->resArray['alfa'][5]);
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] + $this->resArray['L'][4] + $this->resArray['L'][5];
        
        $this->resArray['DLS'][2] = $DLS2;
        
        return $this->resArray; 
    }
    
     public function s4_A_H_L1_L5_alfa2_alfa4_DLS4($A, $H, $L1, $L5, $alfa2, $alfa4, $DLS4){
        $this->resArray['A']['sum'] = $A;
        $this->resArray['H']['sum'] = $H;
        $this->resArray['L'][1] = $L1;
        $this->resArray['L'][5] = $L5;
        $this->resArray['DLS'][4] = $this->equation->DLS2m($DLS4);
        $this->resArray['alfa'][2] = deg2rad($alfa2);
        $this->resArray['alfa'][4] = deg2rad($alfa4);
        
        $this->resArray['A'][1] = 0;
        $this->resArray['alfa'][1] = 0;
        $this->resArray['delta'][1] = 0;
        $this->resArray['R'][1] = 0;
        $this->resArray['DLS'][1] = 0;
        
        $this->resArray['H'][1] = $this->resArray['L'][1];
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        
        $this->resArray['delta'][3] = 0;
        $this->resArray['R'][3] = 0;
        $this->resArray['DLS'][3] = 0;
        if (!$this->validator->validate($this->resArray['DLS'][4],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['R'][4] = 1 / $this->resArray['DLS'][4];
        $this->resArray['delta'][4] = $this->resArray['alfa'][3] - $this->resArray['alfa'][4];
        
        $this->resArray['L'][4] = $this->resArray['delta'][4] * $this->resArray['R'][4];
        $this->resArray['H'][4] = $this->resArray['R'][4] *  (sin($this->resArray['alfa'][3]) - sin($this->resArray['alfa'][4]));
        $this->resArray['A'][4] = $this->resArray['R'][4] *  (cos($this->resArray['alfa'][4]) - cos($this->resArray['alfa'][3]));
        
        $this->resArray['H'][5] = $this->resArray['L'][5] * cos($this->resArray['alfa'][5]);
        $this->resArray['A'][5] = $this->resArray['L'][5] * sin($this->resArray['alfa'][5]);
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        
        $this->resArray['R'][2] =   (($this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][4] - $this->resArray['H'][5])*sin($this->resArray['alfa'][2]))
                                    / 
                                    (1 - cos($this->resArray['alfa'][2]))
                                    -
                                   ($this->resArray['A']['sum'] - $this->resArray['A'][4] - $this->resArray['A'][5]) * cos($this->resArray['alfa'][2]) 
                                    /
                                    (1 - cos($this->resArray['alfa'][2]));
        if (!$this->validator->validate($this->resArray['R'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['DLS'][2] = rad2deg(1/ $this->resArray['R'][2] * 30.48);
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][5] * sin($this->resArray['alfa'][2]);
        $this->resArray['A'][2] = $this->resArray['R'][2] * (1 - cos($this->resArray['alfa'][2]));
        
        $this->resArray['alfa'][5] = $this->resArray['alfa'][4];
        $this->resArray['delta'][5] = 0;
        $this->resArray['R'][5] = 0;
        $this->resArray['DLS'][5] = 0;
        
        $this->resArray['H'][3] =  $this->resArray['H']['sum'] - $this->resArray['H'][1] - $this->resArray['H'][2] - $this->resArray['H'][4] - $this->resArray['H'][5];
        $this->resArray['A'][3] =  $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][2] - $this->resArray['A'][4] - $this->resArray['A'][5];
        if (!$this->validator->validate(sin($this->resArray['alfa'][3]),'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['L'][3] = $this->resArray['A'][3] / sin($this->resArray['alfa'][3]);
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] +$this->resArray['L'][3] + $this->resArray['L'][4] + $this->resArray['L'][5];

        $this->resArray['DLS'][4] = $DLS4;
  
        return $this->resArray; 
    }
    
    public function catenary_H_A2_L1_L3_Q_ro($H,$A2,$L1,$L3,$Q,$ro){

        $this->resArray['H'][1] = $L1;
	$this->resArray['H'][2] = $H - $L1;
        $this->resArray['H'][3] = 0;
	$this->resArray['H']['sum'] = $H;
        $this->resArray['A'][1] = 0;
        $this->resArray['A'][2] = $A2;
        $this->resArray['A'][3] = $L3;
     
        $this->resArray['A']['sum'] = $A2 + $L3;
        $this->resArray['L'][1] = $L1;
        $this->resArray['L'][3] = $L3;

	$this->resArray['CWIM'] = ( Q * ( 1 - ( $ro / 7700 ) ) ) * 9.81; 
	$this->resArray['HF'] = 35626.7;
	$this->resArray['L'][2] = resArray['HF'] / resArray['CWIM'] * sinh( resArray['CWIM'] * $A2 / resArray['HF'] );
        $this->resArray['L']['sum'] = $L1 + $L3 + resArray['L'][2];


        
        $this->resArray['alfa'][3] = $this->resArray['alfa'][2];
        $this->resArray['delta'][2] = $this->resArray['alfa'][2];
        $this->resArray['A'][3] = $this->resArray['L'][3] * sin($this->resArray['alfa'][3]);
        $this->resArray['H'][3] = sqrt(pow($this->resArray['L'][3],2) - pow($this->resArray['A'][3],2));
        $this->resArray['A'][2] = $this->resArray['A']['sum'] - $this->resArray['A'][1] - $this->resArray['A'][3];
        $this->resArray['R'][2] = $this->resArray['A'][2] / (1 - cos($this->resArray['alfa'][2]));
        $this->resArray['R'][3] = 0;
        $this->resArray['L'][2] = $this->resArray['delta'][2] * $this->resArray['R'][2];
        $this->resArray['H'][2] = $this->resArray['R'][2] * sin($this->resArray['alfa'][2]);
        if (!$this->validator->validate($this->resArray['R'][2],'biggerThanZero')){
            $this->resArray['error'] = 'Parametr error';
            return $this->resArray;
        }
        $this->resArray['DLS'][2] = rad2deg((1 / $this->resArray['R'][2]) * 30.48);
        $this->resArray['H'][1] = $this->resArray['H']['sum'] - $this->resArray['H'][2] - $this->resArray['H'][3];
        $this->resArray['L'][1] = $this->resArray['H'][1];
        $this->resArray['L']['sum'] = $this->resArray['L'][1] + $this->resArray['L'][2] + $this->resArray['L'][3];
        return $this->resArray;   
    }
}
