<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of equation
 * TO DO Poprawić indeksy w funkcjach
 * @author darek
 */
class equation {
    
    public function DLS2m($DLS){
        return deg2rad($DLS / 30.48);
    }
   
    public function Alfa($R_i, $A, $H, $L_i){                   // eq. 3.14
        return acos(($R_i *($R_i-$A)+($H-$L_i)* sqrt(pow(($H-$L_i),2) + pow($A,2) - 2 * $A * $R_i)) / (pow(($R_i - $A),2) + pow(($H-$L_i),2)));
    }
    
    public function R_j($DLS){                                  // eq. 3.4 
        return 1 / $DLS;
    }
    
    public function Delta($alfa_i, $alfa_i_min_1){              //eq. 3.5
        return abs($alfa_i - $alfa_i_min_1);
    }
    
    public function H_j($R_j, $alfa_i, $alfa_i_min_1){          // eq. 3.6
        if ( $alfa_i > $alfa_i_min_1){
            return $R_j * ( sin($alfa_i) - sin($alfa_i_min_1));
        }
        
        if ( $alfa_i_min_1 > $alfa_i){
            return $R_j * ( sin($alfa_i_min_1) - sin($alfa_i));
        }    
    }
    
    public function A_j($R_j, $alfa_i, $alfa_i_min_1) {         // eq. 3.7
        if ( $alfa_i > $alfa_i_min_1){
            return $R_j * ( cos($alfa_i_min_1) - cos($alfa_i));
        }
        
        if ( $alfa_i_min_1 > $alfa_i){
            return $R_j * (cos($alfa_i) - cos($alfa_i_min_1));
        }  
    }
    
    public function L_j($delta_i, $R_i){                        // eq. 3.8   
        return $delta_i * $R_i;
    }
    //--
    public function L_j_3($A_j,$alfa_j){                        // eq. 3.10
        return $A_j / sin($alfa_j);
    }
    
    public function L_j_2($A_j, $H_j){                          // eq. 3.11
        return sqrt(pow($A_j,2) + pow($H_j,2));
    }
    
    public function R_j_2($A, $L_k, $H, $L_i ){
        return (pow($A,2) - pow($L_k, 2) + pow(($H - $L_i),2)) / (2*$A);
    }
    
    
}
