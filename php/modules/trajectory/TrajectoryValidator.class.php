<?php

class TrajectoryValidator {
    
    public function validate($parametr, $condition){
        if ($parametr == null){
            return false;
        }
        if ($condition == 'biggerThanZero'){
            if($parametr > 0){
                return true;
            }
        }
        
        return false;
        
    }
}
