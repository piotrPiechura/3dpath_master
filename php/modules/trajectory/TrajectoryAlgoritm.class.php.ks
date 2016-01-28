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
class TrajectoryAlgoritm {
    
    protected $sumH = 0;
    protected $sumA = 0;
    protected $sumL = 0;
    protected $LL = 0;
    
    /*
     *  Calculate cordinates 
     *  $sections  - section amount
     *  Arrays:
     *  $A  
     *  $H
     *  $L
     *  $alpha
     *  $delta
     *  $DLS
     *  $R 
     *  $Azimut
     * 
     *  Returns Array[rownumber][MD] , [X] , [Y] , [L]
     */
    
    public function calculateCoordinates($sections, $A, $H, $L, $ALPHA, $BETA, $DLS, $R, $azimut, $step){
        $rowNumber = 1; // L from R. Wisniowski algoritm
        $res = array();
        $counter = 0;
        $corection = 0;
        $step_tmp = $step;
        $x = 0;
        $azimut = deg2rad($azimut);
        
        
        for($i=1;$i<=$sections;$i++){
            $step = $step_tmp;
            while ($this->LL < $this->sumL + $L[$i]){ 
                $counter ++;
                if($R[$i] == 0){
                    $res[$counter]['MD'] = $this->LL;
                    $res[$counter]['beta'] = $azimut;
                    $res[$counter]['alfa'] = $ALPHA[$i];
                    $res[$counter]['X'] = ($this->sumA + ($this->LL - $this->sumL)*sin($res[$counter]['alfa']))* sin($res[$counter]['beta']);
                    $res[$counter]['Y'] = ($this->sumA + ($this->LL - $this->sumL)*sin($res[$counter]['alfa']))* cos($res[$counter]['beta']);
                    $res[$counter]['Z'] = $this->sumH + ($this->LL - $this->sumL) * cos($res[$counter]['alfa']); 
                    $res[$counter]['section'] = " | " . $i;
                }
                else{
                    if($ALPHA[$i] > $ALPHA[$i-1]){
                        $res[$counter]['MD'] = $this->LL;
                        $res[$counter]['beta'] = $azimut;
                        $res[$counter]['alfa'] = $ALPHA[$i-1] + ($this->LL - $this->sumL)* deg2rad($DLS[$i] / 30.48);
                        $res[$counter]['X'] = ($this->sumA + $R[$i] * (cos($ALPHA[$i-1]) - cos($res[$counter]['alfa']))) * sin($res[$counter]['beta']);
                        $res[$counter]['Y'] = ($this->sumA + $R[$i] * (cos($ALPHA[$i-1]) - cos($res[$counter]['alfa']))) * cos($res[$counter]['beta']);
                        $res[$counter]['Z'] =  $this->sumH + $R[$i] * (sin($res[$counter]['alfa']) - sin($ALPHA[$i-1]));
                        $res[$counter]['section'] = " + " . $i;
                        
                    }
                    else{
                        $res[$counter]['MD'] = $this->LL;
                        $res[$counter]['beta'] = $azimut;
                        $res[$counter]['alfa'] = $ALPHA[$i-1] - ($this->LL - $this->sumL)* deg2rad($DLS[$i] / 30.48);
                        $res[$counter]['X'] = ($this->sumA + $R[$i] * (cos($ALPHA[$i]) - cos($ALPHA[$i-1])) * sin($res[$counter]['beta']));
                        $res[$counter]['Y'] = ($this->sumA + $R[$i] * (cos($ALPHA[$i]) - cos($ALPHA[$i-1])) * cos($res[$counter]['beta']));
                        $res[$counter]['Z'] = $this->sumH + $R[$i] * (sin($ALPHA[$i-1]) - sin($ALPHA[$i]));
                        $res[$counter]['section'] = " - " . $i;
                        
                    }
                }
                
                $this->LL = $this->LL + $step;
            }
            //if ($this->LL >= $this->sumL + $L[$i]){ // corection

                if ( $this->LL - $L[$i] > 0){
                    
                    $counter ++;
                    $res[$counter]['MD'] = $this->sumL + $L[$i];
                    $res[$counter]['beta'] = $azimut;
                    $res[$counter]['alfa'] = $ALPHA[$i];

                    $res[$counter]['X'] = ($this->sumA + $A[$i]) * sin($res[$counter]['beta']);
                    $res[$counter]['Y'] = ($this->sumA + $A[$i]) * cos($res[$counter]['beta']);
                    $res[$counter]['Z'] = $this->sumH + $H[$i];
                    $res[$counter]['section'] = "PP";
                    $counter ++;
                    //$this->LL = $this->LL + $step;
                    //echo $this->LL . " ||=|| ". ($this->LL - $L[$i]) . " Dla 1 <br/> ";
                } 
            //} 
                $this->sumH = $this->sumH + $H[$i];
                $this->sumA = $this->sumA + $A[$i];
                $this->sumL = $this->sumL + $L[$i];
            
            
        }
        
        return $res;
    }
    
    public function inputK($x1, $y1, $z1, $x2, $y2, $z2, $alfa2, $beta2, $lp){
        
        //wg Profesora
        $sx = sin(deg2rad($alfa2)) * sin(deg2rad($beta2));
        $sy = sin(deg2rad($alfa2)) * cos(deg2rad($beta2));
        $sz = cos(deg2rad($alfa2));
        
        $txLicznik = ($z2-$z1)*$sy - $sz*($y2-$y1);
        $tyLicznik = ($x2-$x1)*$sz - $sx*($z2-$z1);
        $tzLicznik = ($y2-$y1)*$sx - $sy*($x2-$x1);
        
        $tMianownik = sqrt(pow($txLicznik,2) + pow($tyLicznik,2) + pow($tzLicznik,2));
        
        if($tMianownik ==0){
            $tx = 0;
            $ty = 0;
            $tz = 0;
        }
        else{
            $tx = $txLicznik / $tMianownik;
            $ty = $tyLicznik / $tMianownik;
            $tz = $tzLicznik / $tMianownik;
        }
        $wx = $ty * $sz - $tz * $sy;
        $wy = $tz * $sx - $tx * $sz;
        $wz = $tx * $sy - $ty * $sx;
        
        $x2prim = ($x2 - $x1) * $wx + ($y2-$y1) * $wy + ($z2 - $z1) * $wz;
        $y2prim = ($x2 - $x1) * $sx + ($y2-$y1) * $sy + ($z2 - $z1) * $sz;
        
        if ($x2prim==0) {
            $res['sx'] = $sx;
            $res['sy'] = $sy;
            $res['sz'] = $sz;

            $res['tx'] = $tx;
            $res['ty'] = $ty;
            $res['tz'] = $tz;

            $res['wx'] = $wx;
            $res['wy'] = $wy;
            $res['wz'] = $wz;
            $res['lc'] = $lp;
            $res['lk'] = 0;
            $res['x2prim'] = $x2prim;
            $res['y2prim'] = $y2prim;
            $res['xHPprim'] = 0;
            $res['yHPprim'] = 0;
        
            $res['delta'] = 0;
            $res['r'] = 0;
            return $res;
        }
        
        $r = (pow($x2prim,2) + pow($y2prim,2) - 2 * $y2prim * $lp + pow($lp,2)) / (2 *$x2prim);
        
        $xHPprim = $x2prim;
        $yHPprim = $y2prim - $lp;
        
        if ($xHPprim < $r && $yHPprim >= 0){
            $delta = atan($yHPprim / ($r - $xHPprim));
        }
        elseif($xHPprim = $r && $yHPprim > 0){
            $delta = pi()/2;
        }
        elseif($xHPprim > $r && $yHPprim >= 0){
            $delta = pi() - atan($yHPprim / ($xHPprim - $r));
        }
        elseif($xHPprim > $r && $yHPprim < 0){
            $delta = pi() + atan(abs($yHPprim) / ($xHPprim - $r));
        }
        elseif($xHPprim = $r && $yHPprim < 0){
            $delta = 3/2 * pi();
        }
        elseif ($xHPprim < $r && $yHPprim < 0){
            $delta = 2 * pi() - atan(abs($yHPprim) / ($r - $xHPprim));
        }
        
        $lk = $r * $delta;
        
        $lc = $lk + $lp;
        
        $res['sx'] = $sx;
        $res['sy'] = $sy;
        $res['sz'] = $sz;
        
        $res['tx'] = $tx;
        $res['ty'] = $ty;
        $res['tz'] = $tz;
        
        $res['wx'] = $wx;
        $res['wy'] = $wy;
        $res['wz'] = $wz;
        
        $res['x2prim'] = $x2prim;
        $res['y2prim'] = $y2prim;
        
        $res['xHPprim'] = $xHPprim;
        $res['yHPprim'] = $yHPprim;
        
        $res['delta'] = $delta;
        
        $res['lk'] = $lk;
        $res['lc'] = $lc;
        
        $res['r'] = $r;
        
        //print_r($res);
        
        return $res;
        
    }
    
    function coordR3k($sx, $sy, $sz, $tx, $ty, $tz, $wx, $wy, $wz, $x1 ,$y1, $z1, $xprim, $yprim, $xHPprim, $yHPprim, $x2prim, $y2prim, $r, $l, $ll, $lk, $lp){
        
        /*if ($l >= 343){
            print_r("\n\n____________________________________________coordR3K l = $l _____________________________________________\n\n");
            print_r("\n sx\n");
            print_r($sx);
            print_r("\n sy\n");
            print_r( $sy);
            print_r("\n sz\n");
            print_r($sz);
            print_r("\n tx\n");
            print_r($tx);
            print_r("\n ty\n");
            print_r( $ty);
            print_r("\n tz\n");
            print_r($tz);
            print_r("\n sx\n");
            print_r($wx);
            print_r("\n wy\n");
            print_r($wy);
            print_r("\n wz\n");
            print_r($wz);
            print_r("\n x1\n");
            print_r($x1 );
            print_r("\n y1\n");
            print_r($y1);
            print_r("\n z1\n");
            print_r($z1);
            print_r("\n xprim\n");
            print_r($xprim);
            print_r("\n yprim\n");
            print_r($yprim);
            print_r("\n xHPrim\n");
            print_r($xHPprim);
            print_r("\n yHprim\n");
            print_r($yHPprim);
            print_r("\n x2prim\n");
            print_r($x2prim);
            print_r("\n y2prim\n");
            print_r($y2prim);
            print_r("\n r\n");
            print_r($r);
            print_r("\n l\n");
            print_r($l);
            print_r("\n ll\n");
            print_r($ll );
            print_r("\n lk\n");
            print_r($lk );
            print_r("\n lp\n");
            print_r($lp);
            
        }*/
        
        $X[$l] = $x1 + $wx * $xprim + $sx * $yprim;
        $Y[$l] = $y1 + $wy * $xprim + $sy * $yprim;
        $Z[$l] = $z1 + $wz * $xprim + $sz * $yprim;
        
        if ($ll < $lk){
            $kx = $wx * ($y2prim - $lp - $yprim) + $sx * (- $x2prim + $r + $xprim);
            $ky = $wy * ($y2prim - $lp - $yprim) + $sy * (- $x2prim + $r + $xprim);
            $kz = $wz * ($y2prim - $lp - $yprim) + $sz * (- $x2prim + $r + $xprim);
        }
        else{
            $kx = $sx * $r;
            $ky = $sy * $r;
            $kz = $sz * $r;
            
        }
        if ($kx == 0 && $kx == 0 && $kx == 0){
            $alfa[$l] = 0;
            $beta0 = 0;
        }
        
        $modulk = sqrt(pow(($kx), 2) + pow(($ky), 2) + pow(($kz), 2));
        if ($kz != 0){
            $alfa[$l] = acos($kz/ $modulk);
        }
        else{
             $alfa[$l] = acos(0);
        }
        if ($ky !=0){
            $beta0 = atan(abs($kx/$ky));
        }
        else{
            $beta0 = 0;
        }
        // obsolete
        /*
        if ($kx == 0 && $ky == 0){
            $beta[$l] = 0;
        }
        elseif($kx >= 0 && $ky > 0 ){
            $beta[$l] = $beta0;
        }
        elseif($kx > 0 && $ky = 0 ){
            $beta[$l] = 1/2 * pi();
        }
        elseif($kx <= 0 && $ky < 0 ){
            $beta[$l] = -1 * pi() - $beta0;
        }
        elseif($kx <= 0 && $ky = 0 ){
            $beta[$l] = 3/2 * pi();
        }
        else{
            $beta[$l] = 2 * pi() - $beta0;
        }*/
        
        if ($kx == 0 && $ky >= 0){
            $beta[$l] = 0;
        }
        elseif($kx > 0 && $ky > 0){
            $beta[$l] = $beta0;
        }
        elseif($kx > 0 && $ky == 0){
            $beta[$l] = 1/2 * pi();
        }
        elseif($kx < 0 && $ky < 0){
            $beta[$l] = pi() - $beta0;
        }
        elseif($kx == 0 && $ky < 0){
                $beta[$l] = pi();
        }
        elseif($kx < 0 && $ky == 0){
            $beta[$l] = 3/2 * pi();
        }
        else{
            $beta[$l] =  2 * pi() - $beta0;
        }
        
        
        
        $res['X'] = $X[$l];
        $res['Y'] = $Y[$l];
        $res['Z'] = $Z[$l];
        
        $res['kx'] = $kx;
        $res['ky'] = $ky;
        $res['kz'] = $kz;
        
        $res['modulk'] = $modulk;
        $res['alfa'] = $alfa[$l];
        $res['beta0'] = $beta0;
        $res['beta'] = $beta[$l]; 
        /*if ($l >= 343){
            print_r("\n\n WYNIK: \n");
            print_r($res);
            print_r("\n________________________________________________________________________________________________________\n\n\n");
        }*/
        return $res;
    }
    
    function wellId($np, $MX, $MY, $MZ, $MLP, $Malfa, $Mbeta){
        
        $sumaLL = 0;
        
        for ($i = $np; $i >= 2; $i --){
            $x1 = $MX[$i-1];
            $y1 = $MY[$i-1];
            $z1 = $MZ[$i-1];
            
            $x2 = $MX[$i];
            $y2 = $MY[$i];
            $z2 = $MZ[$i];
            
            $alfa2 = $Malfa[$i];
            $beta2 = $Mbeta[$i];
            $lp = $MLP[$i];
            
            $inputK = $this->inputK($x1, $y1, $z1, $x2, $y2, $z2, $alfa2, $beta2, $lp);
            $this->firstStreightInput($inputK, $lp, $i);
            $res = $inputK;
            
            $kx = $inputK['wx'] * ($inputK['y2prim'] - $lp) + $inputK['sx'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            $ky = $inputK['wy'] * ($inputK['y2prim'] - $lp) + $inputK['sy'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            $kz = $inputK['wz'] * ($inputK['y2prim'] - $lp) + $inputK['sz'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            
            $modulk = sqrt(pow(($kx), 2) + pow(($ky), 2) + pow(($kz), 2));
            $Malfa2[$i] = acos($kz/ $modulk);
            
            $beta0 = atan(abs($kx/$ky));
        
            // obsolete
            /*
            if ($kx == 0 && $ky == 0){
                $beta[$i-1] = 0;
            }
            elseif($kx >= 0 && $ky > 0 ){
                $beta[$i-1] = $beta0;
            }
            elseif($kx > 0 && $ky = 0 ){
                $beta[$i-1] = 1/2 * pi();
            }
            elseif($kx <= 0 && $ky < 0 ){
                $beta[$i-1] = -1 * pi() - $beta0;
            }
            elseif($kx <= 0 && $ky = 0 ){
                $beta[$i-1] = 2/3 * pi();
            }
            else{
                $beta[$i-1] = 2 * pi() - $beta0;
            }
            */
            if ($kx == 0 && $ky >= 0){
                $beta[$i-1] = 0;
            }
            elseif($kx > 0 && $ky > 0){
                $beta[$i-1] = $beta0;
            }
            elseif($kx > 0 && $ky == 0){
                $beta[$i-1] = 1/2 * pi();
            }
            elseif($kx < 0 && $ky < 0){
                $beta[$i-1] = pi() - $beta0;
            }
            elseif($kx == 0 && $ky < 0){
                $beta[$i-1] = pi();
            }
            elseif($kx < 0 && $ky == 0){
                $beta[$i-1] = 3/2 * pi();
            }
            else{
                $beta[$i-1] = 2 * pi() - $beta0;
            }
            
            $sumaLL = $sumaLL + $inputK['lc'];
            
        }
        
        $res['betaWellId'] = $beta;
        $res['alfaWellId'] = $Malfa;
        $res['sumaLL'] = $sumaLL;
        
        return $res;
    }
    
    function r3konc_old($np, $MX, $MY, $MZ, $MLP, $Malfa, $Mbeta, $step){
        
        //$WIData = $this->wellId($np, $MX, $MY, $MZ, $MLP, $Malfa, $Mbeta);
        
        print_r($MZ);
        
        //$step = 10;
        $LL = 0;
        $SumaLL = 0;
        $sumak = 0;
        $korekta = 0;
        $step_wzor = $step;
        $l = 1;
        $res = null; 
        //$sumaLL = $WIData['sumaLL'];
        
        // wellId 
        
        for ($i = $np; $i >= 2; $i--){
            $x1 = $MX[$i-1];
            $y1 = $MY[$i-1];
            $z1 = $MZ[$i-1];
            
            $x2 = $MX[$i];
            $y2 = $MY[$i];
            $z2 = $MZ[$i];
            
            $alfa2 = $Malfa[$i];
            $beta2 = $Mbeta[$i];
            $lp = $MLP[$i];
           // print_r($SumaLL . "\n\t");
          //  print_r($SumaLL . "\n\t");
            $inputK = $this->inputK($x1, $y1, $z1, $x2, $y2, $z2, $alfa2, $beta2, $lp);
          //  print_r($inputK);
            //$res = $inputK;
            
            $kx = $inputK['wx'] * ($inputK['y2prim'] - $lp) + $inputK['sx'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            $ky = $inputK['wy'] * ($inputK['y2prim'] - $lp) + $inputK['sy'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            $kz = $inputK['wz'] * ($inputK['y2prim'] - $lp) + $inputK['sz'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            
            if ($kx ==0 || $ky ==0 || $kz ==0){
            $Malfa[$i-1] = 0;
            $beta0 = 0;
            }
            else{
            $modulk = sqrt(pow(($kx), 2) + pow(($ky), 2) + pow(($kz), 2));
            $Malfa[$i-1] = rad2deg(acos($kz/ $modulk));
            $beta0 = atan(abs($kx/$ky));
            }
        
            if ($kx == 0 && $ky == 0){
                $Mbeta[$i-1] = 0;
            }
            elseif($kx >= 0 && $ky > 0 ){
                $Mbeta[$i-1] = $beta0;
            }
            elseif($kx > 0 && $ky = 0 ){
                $Mbeta[$i-1] = 1/2 * pi();
            }
            elseif($kx <= 0 && $ky < 0 ){
                $Mbeta[$i-1] = -1 * pi() - $beta0;
            }
            elseif($kx <= 0 && $ky = 0 ){
                $Mbeta[$i-1] = 2/3 * pi();
            }
            else{
                $Mbeta[$i-1] = 2 * pi() - $beta0;
            }
            $Mbeta[$i-1] = rad2deg($Mbeta[$i-1]);
            $SumaLL = $SumaLL + $inputK['lc'];
        }
        $Malfa[1] = 0;
        $Mbeta[1] = 0;
        
        for ($i = $np; $i >= 2; $i--){
            $x1 = $MX[$i-1];
            $y1 = $MY[$i-1];
            $z1 = $MZ[$i-1];
            
            $x2 = $MX[$i];
            $y2 = $MY[$i];
            $z2 = $MZ[$i];
            
            $alfa2 = $Malfa[$i];
            $beta2 = $Mbeta[$i];
            //$alfa2 = $WIData['alfaWellId'][$i];
            //$beta2 = $WIData['betaWellId'][$i];
            
            $lp = $MLP[$i];
            //$l = 1;
            //$LL = 0;
            $inputK = $this->inputK($x1, $y1, $z1, $x2, $y2, $z2, $alfa2, $beta2, $lp);
            $SumaLL = $SumaLL - $inputK['lc'];
            $step = $step_wzor - ($SumaLL - floor($SumaLL));
            //$LL = 0;
            //$l = 1;
            //print_r("SUMA LL = ". $SumaLL ."\n\t");
            $LL = 0;
        do {
            //print_r("LL = ". $LL ."\n\t");
            //print_r("lk = ". $inputK['lk'] ."\n\t");
            while($LL < $inputK['lk']){
                
                $xprim = $inputK['xHPprim'] - $inputK['r'] * (1 - cos(($inputK['lk'] - $LL) / $inputK['r']));
                $yprim=  $inputK['yHPprim'] - $inputK['r'] * sin(($inputK['lk'] - $LL) / $inputK['r']);
                $MD = $SumaLL + $LL;
                //print_r("MD = ". $MD ."\n\t");
                $res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
                $res[$l]['section'] = $i;
                $res[$l]['MD'] = $MD;
                $res[$l]['CL_DEP'] = 0;
                $res[$l]['CL_Angle'] = 0;
                
                $korekta = 1;
                //if ($LL != 0){
                  $step = $step_wzor;  
                //}
                //print_r($res[$l]);
                $l++;
                $LL = $LL + $step;
            }
            //if ($LL >= $inputK['lk']){
                
                if ($korekta == 1){
                    $xprim = $inputK['xHPprim'];
                    $yprim=  $inputK['yHPprim'];
                    $korekta = 0;
                    $MD = $SumaLL + $inputK['lk'];
                   // print_r("MD = ". $MD ."\n\t");
                    $res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
                    $res[$l]['section'] = $i;
                    $res[$l]['MD'] = $MD;
                    $res[$l]['CL_DEP'] = 0;
                    $res[$l]['CL_Angle'] = 0;
                    $step = $LL - $inputK['lk'];
                    $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                    $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                    $alfa1 = $res[$l]['alfa'];
                    $beta1 = $res[$l]['beta'];
                    //print_r($res[$l]);
                    $l++;
                    
                    if ($step == 0){
                        $step = $step_wzor;
                        $LL = $LL + $step;
                        
                    }
                }    
                    
                $xprim = $inputK['xHPprim'];
                $yprim =  $inputK['yHPprim'] + ($LL  - $inputK['lk']);
                $MD = $SumaLL + $LL;   
               // print_r("MD = ". $MD ."\n\t");
                $res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
                $res[$l]['section'] = $i;
                $res[$l]['MD'] =$MD;
                $res[$l]['CL_DEP'] = 0;
                $res[$l]['CL_Angle'] = 0;
                
                $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                 
                      
                $step = $step_wzor;
                $l++;
                $LL = $LL + $step;
                //print_r($res[$l]);
            }while($LL < $inputK['lc']);
            
            //if ($sumak == 0){
                $xprim = $inputK['x2prim'];
                $yprim =  $inputK['y2prim'];
                $MD = $SumaLL + $inputK['lc'];
               // print_r("MD = ". $MD ."\n\t");
                $res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
                $res[$l]['section'] = $i;
                $res[$l]['MD'] =$MD;
                $res[$l]['CL_DEP'] = 0;
                $res[$l]['CL_Angle'] = 0;
                
                $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                
                if (($Malfa[$i]==0) and ($Mbeta[$i]==0)) {
                    $alfa1 = $res[$l]['alfa'];
                    $beta1 = $res[$l]['beta'];
                }
                else {
                    $alfa1 = $Malfa[$i];
                    $beta1 = $Mbeta[$i];
                }
                
                $step = $LL - $inputK['lc'];
           
                if($step == 0){
                    $step = $step_wzor;
                }
        } 
        
        foreach($res as $res1){
            $sections[$res1['section']][] = $res1;
        }
        $sections = array_reverse($sections);
        $resPrim = array();
        foreach($sections as $section){
            foreach($section as $point){
                $resPrim[] = $point;
            }
        }
        $res = $resPrim;
        return $res;
        
        
    }
    
    function r3konc_prew($np, $MX, $MY, $MZ, $MLP, $Malfa, $Mbeta, $step){
        $step = 10;
        $LL = 0;
        $SumaLL = 0;
        $korekta = 0;
        $step_wzor = $step;
        $l = 1;
        $res = null; 
       
        print_r("Before Well ID\n");
        print_r("\n\nNP\n");
        print_r($np);
        print_r("\n\nMX\n");
        print_r($MX);
        print_r("\n\nMY\n");
        print_r($MY);
        print_r("\n\nMZ\n");
        print_r($MZ);
        print_r("\n\nMLP\n");
        print_r($MLP);
        print_r("\n\nMalfa\n");
        print_r($Malfa);
        print_r("\n\nMbeta\n");
        print_r($Mbeta);
        
         // wellId 
        for ($i = $np; $i >= 2; $i--){
        //for ($i = 2; $i <= $np; $i++){ // zmiana 13.08.2015
            
            if ($i == 2){
                $Malfa[$i-1] = 0;
                $beta0 = 0;
                break;
            }
            
            $x1 = $MX[$i-1];
            $y1 = $MY[$i-1];
            $z1 = $MZ[$i-1];
            
            $x2 = $MX[$i];
            $y2 = $MY[$i];
            $z2 = $MZ[$i];
            
            $alfa2 = $Malfa[$i];
            $beta2 = $Mbeta[$i];
            
            
            //$alfa2 = $Malfa[$i]; //zmiana 09.08 
            //$beta2 = $Mbeta[$i];
            $lp = $MLP[$i];
           // print_r($SumaLL . "\n\t");
          //  print_r($SumaLL . "\n\t");
            $inputK = $this->inputK($x1, $y1, $z1, $x2, $y2, $z2, $alfa2, $beta2, $lp);
          //  print_r($inputK);
            //$res = $inputK;
            $this->firstStreightInput($inputK, $lp, $i);
            $kx = $inputK['wx'] * ($inputK['y2prim'] - $lp) + $inputK['sx'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            $ky = $inputK['wy'] * ($inputK['y2prim'] - $lp) + $inputK['sy'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            $kz = $inputK['wz'] * ($inputK['y2prim'] - $lp) + $inputK['sz'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            
            if ($kx ==0 || $ky ==0 || $kz ==0){
                $Malfa[$i-1] = 0;
                $beta0 = 0;
            }
            else{
                $modulk = sqrt(pow(($kx), 2) + pow(($ky), 2) + pow(($kz), 2));
                $Malfa[$i-1] = rad2deg(acos($kz/ $modulk));
                $beta0 = atan(abs($kx/$ky));
            }
        
            if ($kx == 0 && $ky == 0){
                $Mbeta[$i-1] = 0;
            }
            elseif($kx >= 0 && $ky > 0 ){
                $Mbeta[$i-1] = $beta0;
            }
            elseif($kx > 0 && $ky = 0 ){
                $Mbeta[$i-1] = 1/2 * pi();
            }
            elseif($kx <= 0 && $ky < 0 ){
                $Mbeta[$i-1] = -1 * pi() - $beta0;
            }
            elseif($kx <= 0 && $ky = 0 ){
                $Mbeta[$i-1] = 2/3 * pi();
            }
            else{
                $Mbeta[$i-1] = 2 * pi() - $beta0;
            }
            $Mbeta[$i-1] = rad2deg($Mbeta[$i-1]);
          }
        
        // end of WellID
        print_r("After Well ID\n");  
        print_r("\n\nStep\n");   
        print_r($step);
        print_r("\n\nLL\n");  
        print_r($LL);
        print_r("\n\nSumaLL\n");  
        print_r($SumaLL);
        print_r("\n\nkorekta\n");  
        print_r($korekta);
        print_r("\n\nstep_wzor\n");  
        print_r($step_wzor);
        print_r("\n\nl\n");  
        print_r($l);
          
        print_r("Malfa"); 
        print_r($Malfa); 
        print_r("Mbeta");
        print_r($Mbeta); 
        
        $Malfa[1] = 0;
        $Mbeta[1] = 0;
        
        
        
        for($i = 2 ; $i <= $np; $i++){
            $x1 = $MX[$i-1];
            $y1 = $MY[$i-1];
            $z1 = $MZ[$i-1];
            
            $x2 = $MX[$i];
            $y2 = $MY[$i];
            $z2 = $MZ[$i];
            
            if ($i == 2){
                $alfa2 = $Malfa[$i];
                $beta2 = $Mbeta[$i];
            }
         
            $lp = $MLP[$i];
            $inputK = $this->inputK($x1, $y1, $z1, $x2, $y2, $z2, $alfa2, $beta2, $lp);
            $this->firstStreightInput($inputK, $lp, $i);
        do {
            while($LL < $inputK['lk']){
                //print("Wartosc L ". $l ."\n\t");
                $xprim = $inputK['xHPprim'] - $inputK['r'] * (1 - cos(($inputK['lk'] - $LL) / $inputK['r']));
                $yprim=  $inputK['yHPprim'] - $inputK['r'] * sin(($inputK['lk'] - $LL) / $inputK['r']);
                $MD = $SumaLL + $LL;
                //print_r("MD = ". $MD ."\n\t");
                $res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
                $res[$l]['section'] = $i;
                $res[$l]['MD'] = $MD;
                $res[$l]['CL_DEP'] = 0;
                $res[$l]['CL_Angle'] = 0;
                $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
                $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                $korekta = 1;
                $step = $step_wzor;  
                $l++;
                $LL = $LL + $step;
                
                $alfa2 = $res[$l]['alfa'];
                $beta2 = $res[$l]['beta'];
                
            }
            if ($korekta == 1){
                $xprim = $inputK['xHPprim'];
                $yprim=  $inputK['yHPprim'];
                $korekta = 0;
                $MD = $SumaLL + $inputK['lk'];
                
                $res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
                $res[$l]['section'] = $i;
                $res[$l]['MD'] = $MD;
                $res[$l]['CL_DEP'] = 0;
                $res[$l]['CL_Angle'] = 0;
                $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
                $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                $step = $LL - $inputK['lk'];
                
                $LL = $LL + 1;
                
                if ($step == 0){
                    $step = $step_wzor;
                    $LL = $LL + $step;     
                    //$MD = $SumaLL + $LL;
                }
                
            }
            
            if (!($lp == 0 && $i>2)){    
                if($korekta == 1){
                    $xprim = $inputK['xHPprim'];
                    $yprim=  $inputK['yHPprim'];
                    $res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
                    $res[$l]['section'] = $i;
                    $res[$l]['MD'] = $SumaLL + $inputK['lc'];
                    $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
                    $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                    $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                    $step = $LL - $inputK['lc'];

                    if ($step == 0){
                        $step = $step_wzor; 
                    }

                    $res[$l]['alfa'] = $Malfa[$i];
                    $res[$l]['beta'] = $Mbeta[$i];
                    $res[$l]['CL_DEP'] = 0;
                    $res[$l]['CL_Angle'] = 0;

                    $alfa2 = $res[$l]['alfa'];
                    $beta2 = $res[$l]['beta'];
                    
                    $SumaLL = $SumaLL + $inputK['lc'];
                    $LL = $step;
                    $l++;
                    
                  
                }
                
                $xprim = $inputK['xHPprim'];
                $yprim=  $inputK['yHPprim'] + ($LL - $inputK['lk']);
                $res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
                $res[$l]['section'] = $i;
                $MD = $SumaLL + $LL;
                $res[$l]['MD'] = $MD;
                $res[$l]['CL_DEP'] = 0;
                $res[$l]['CL_Angle'] = 0;
                $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
                $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                $step = $step_wzor;  
                $l++;
                $LL = $LL + $step;
                    
                    $alfa2 = $res[$l]['alfa'];
                    $beta2 = $res[$l]['beta'];
                  
                    
            }
            
        }while($LL < $inputK['lc']);
        
           $xprim = $inputK['xHPprim'];
           $yprim=  $inputK['yHPprim'];
            $res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
            $res[$l]['section'] = $i;
            $res[$l]['MD'] = $SumaLL + $inputK['lc'];
            $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
            $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
            $res[$l]['beta'] = rad2deg($res[$l]['beta']);
            $step = $LL - $inputK['lc'];

            if ($step == 0){
                $step = $step_wzor; 
            }

            $res[$l]['alfa'] = $Malfa[$i];
            $res[$l]['beta'] = $Mbeta[$i];
            $res[$l]['CL_DEP'] = 0;
            $res[$l]['CL_Angle'] = 0;

            $alfa2 = $res[$l]['alfa'];
            $beta2 = $res[$l]['beta'];
                    
            $SumaLL = $SumaLL + $inputK['lc'];
            $LL = $step;
            $l++;
            
            if (($Malfa[$i]==0) and ($Malfa[$i]==0)) {
                $alfa2 = $res[$l]['alfa'];
                $beta2 = $res[$l]['beta'];
            }
            else {
                if ($i == $np){
                    $alfa2 = $Malfa[$i];
                    $beta2 = $Mbeta[$i];
                }
                else{
                    $alfa2 = $Malfa[$i+1];
                    $beta2 = $Mbeta[$i+1];
                }
            }
            // dodane 08.09.2015 15:20
            $step = $LL - $inputP['lc'];
            if($step == 0){
               $step = $step_wzor;
            }
            $l++;
            $SumaLL = $SumaLL + $inputP['lc'];
            $LL = $step;
        }
        return $res;
    }
    
      function r3konc_v7($np, $MX, $MY, $MZ, $MLP, $Malfa, $Mbeta, $step){
        $step = 10;
        $LL = 0;
        $SumaLL = 0;
        $korekta = 0;
        $step_wzor = $step;
        $l = 1;
        $res = null; 
       
        print_r("Before Well ID\n");
        print_r("\n\nNP\n");
        print_r($np);
        print_r("\n\nMX\n");
        print_r($MX);
        print_r("\n\nMY\n");
        print_r($MY);
        print_r("\n\nMZ\n");
        print_r($MZ);
        print_r("\n\nMLP\n");
        print_r($MLP);
        print_r("\n\nMalfa\n");
        print_r($Malfa);
        print_r("\n\nMbeta\n");
        print_r($Mbeta);
        
         // wellId 
        for ($i = $np; $i >= 2; $i--){
        //for ($i = 2; $i <= $np; $i++){ // zmiana 13.08.2015
            
            if ($i == 2){
                $Malfa[$i-1] = 0;
                $beta0 = 0;
                break;
            }
            
            $x1 = $MX[$i-1];
            $y1 = $MY[$i-1];
            $z1 = $MZ[$i-1];
            
            $x2 = $MX[$i];
            $y2 = $MY[$i];
            $z2 = $MZ[$i];
            
            $alfa2 = $Malfa[$i];
            $beta2 = $Mbeta[$i];
            
            
            //$alfa2 = $Malfa[$i]; //zmiana 09.08 
            //$beta2 = $Mbeta[$i];
            $lp = $MLP[$i];
           // print_r($SumaLL . "\n\t");
          //  print_r($SumaLL . "\n\t");
            $inputK = $this->inputK($x1, $y1, $z1, $x2, $y2, $z2, $alfa2, $beta2, $lp);
          //  print_r($inputK);
            //$res = $inputK;
            $this->firstStreightInput($inputK, $lp, $i);
            $kx = $inputK['wx'] * ($inputK['y2prim'] - $lp) + $inputK['sx'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            $ky = $inputK['wy'] * ($inputK['y2prim'] - $lp) + $inputK['sy'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            $kz = $inputK['wz'] * ($inputK['y2prim'] - $lp) + $inputK['sz'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            
            if ($kx ==0 || $ky ==0 || $kz ==0){
                $Malfa[$i-1] = 0;
                $beta0 = 0;
            }
            else{
                $modulk = sqrt(pow(($kx), 2) + pow(($ky), 2) + pow(($kz), 2));
                $Malfa[$i-1] = rad2deg(acos($kz/ $modulk));
                $beta0 = atan(abs($kx/$ky));
            }
        
            if ($kx == 0 && $ky == 0){
                $Mbeta[$i-1] = 0;
            }
            elseif($kx >= 0 && $ky > 0 ){
                $Mbeta[$i-1] = $beta0;
            }
            elseif($kx > 0 && $ky = 0 ){
                $Mbeta[$i-1] = 1/2 * pi();
            }
            elseif($kx <= 0 && $ky < 0 ){
                $Mbeta[$i-1] = -1 * pi() - $beta0;
            }
            elseif($kx <= 0 && $ky = 0 ){
                $Mbeta[$i-1] = 2/3 * pi();
            }
            else{
                $Mbeta[$i-1] = 2 * pi() - $beta0;
            }
            $Mbeta[$i-1] = rad2deg($Mbeta[$i-1]);
          }
        
        // end of WellID
        print_r("After Well ID\n");  
        print_r("\n\nStep\n");   
        print_r($step);
        print_r("\n\nLL\n");  
        print_r($LL);
        print_r("\n\nSumaLL\n");  
        print_r($SumaLL);
        print_r("\n\nkorekta\n");  
        print_r($korekta);
        print_r("\n\nstep_wzor\n");  
        print_r($step_wzor);
        print_r("\n\nl\n");  
        print_r($l);
          
        print_r("Malfa"); 
        print_r($Malfa); 
        print_r("Mbeta");
        print_r($Mbeta); 
        
        $Malfa[1] = 0;
        $Mbeta[1] = 0;
        
        
        
        for($i = 2 ; $i <= $np; $i++){
            $x1 = $MX[$i-1];
            $y1 = $MY[$i-1];
            $z1 = $MZ[$i-1];
            
            $x2 = $MX[$i];
            $y2 = $MY[$i];
            $z2 = $MZ[$i];
            
            if ($i == 2){
                $alfa2 = $Malfa[$i];
                $beta2 = $Mbeta[$i];
            }
         
            $lp = $MLP[$i];
            $inputK = $this->inputK($x1, $y1, $z1, $x2, $y2, $z2, $alfa2, $beta2, $lp);
            $this->firstStreightInput($inputK, $lp, $i);
        do {
            while($LL < $inputK['lk']){
                //print("Wartosc L ". $l ."\n\t");
                $xprim = $inputK['xHPprim'] - $inputK['r'] * (1 - cos(($inputK['lk'] - $LL) / $inputK['r']));
                $yprim=  $inputK['yHPprim'] - $inputK['r'] * sin(($inputK['lk'] - $LL) / $inputK['r']);
                $MD = $SumaLL + $LL;
                //print_r("MD = ". $MD ."\n\t");
                $res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
                $res[$l]['section'] = $i;
                $res[$l]['MD'] = $MD;
                $res[$l]['CL_DEP'] = 0;
                $res[$l]['CL_Angle'] = 0;
                $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
                $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                $alfa2 = $res[$l]['alfa'];
                $beta2 = $res[$l]['beta'];
		$korekta = 1;
                $step = $step_wzor;  
                $l++;
                $LL = $LL + $step;
                //if ($l > 340) print_r("\n $l 1. while \n");
            }
            
            if (!($lp == 0 && $i>2)){    
                if($korekta == 1){
                    $xprim = $inputK['xHPprim'];
                    $yprim=  $inputK['yHPprim'];
		    $korekta = 0;
		    $MD = $SumaLL + $inputK['lk'];   
                    $res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
                    $res[$l]['section'] = $i;
                    $res[$l]['MD'] = $MD;//$SumaLL + $inputK['lc'];
                    $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
            	    $step = $LL - $inputK['lk'];
	            $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                    $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                    $alfa2 = $res[$l]['alfa'];
                    $beta2 = $res[$l]['beta'];
		    $l++;
                    if ($step == 0){
                        $step = $step_wzor; 
                        $LL = $LL+$step;
		    }
                    if ($l > 340) print_r("\n $l 2. while if \n");

               }
                
                $xprim = $inputK['xHPprim'];
                $yprim=  $inputK['yHPprim'] + ($LL - $inputK['lk']);
                $MD = $SumaLL + $LL;
		$res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
                $res[$l]['section'] = $i;
                $res[$l]['MD'] = $MD;
                $res[$l]['CL_DEP'] = 0;
                $res[$l]['CL_Angle'] = 0;
                $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                $res[$l]['beta'] = rad2deg($res[$l]['beta']);
		$this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
                $alfa2 = $res[$l]['alfa'];
                $beta2 = $res[$l]['beta'];
                $step = $step_wzor;
                
                /*if ($l > 341){
                    print_r("\n Punkt $l (3. while do)  \n");
                    
                    print_r("\n\nStep\n");   
                    print_r($step);
                    print_r("\n\nLL\n");  
                    print_r($LL);
                    print_r("\n\nSumaLL\n");  
                    print_r($SumaLL);
                    print_r("\n\nkorekta\n");  
                    print_r($korekta);
                    print_r("\n\nstep_wzor\n");  
                    print_r($step_wzor);
                    print_r("\n\nl\n");  
                    print_r($l);
                    
                    print_r("\n\ninputK\n");  
                    print_r($inputK);
                    
                    print_r ("\n\n Tablica wyniów \n");
                    print_r($res[$l]);
                }*/
                
                $l++;
                $LL = $LL + $step;
            }
            
        }while($LL < $inputK['lc']);
        
            $xprim = $inputK['xHPprim'];
            $yprim=  $inputK['yHPprim'];
            $MD = $SumaLL + $inputK['lc'];
	    $res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
            $res[$l]['section'] = $i;
            $res[$l]['MD'] = $MD;
            $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
            $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
            $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                       
            if (($Malfa[$i]==0)) {
                $alfa2 = $res[$l]['alfa'];
                $beta2 = $res[$l]['beta'];
            }
            //else {
                //if ($i == $np){
                //    $alfa2 = $Malfa[$i];
                //    $beta2 = $Mbeta[$i];
                //}
                else{
                    $alfa2 = $Malfa[$i+1];
                    $beta2 = $Mbeta[$i+1];
                }
            //}
            // dodane 08.09.2015 15:20
            $step = $LL - $inputK['lc'];
            if($step == 0){
               $step = $step_wzor;
            }
            
           /*if ($l > 340){
                print_r("\n $l 4. ostatni for \n");
                     print_r("\n\nStep\n");   
                    print_r($step);
                    print_r("\n\nLL\n");  
                    print_r($LL);
                    print_r("\n\nSumaLL\n");  
                    print_r($SumaLL);
                    print_r("\n\nkorekta\n");  
                    print_r($korekta);
                    print_r("\n\nstep_wzor\n");  
                    print_r($step_wzor);
                    print_r("\n\nl\n");  
                    print_r($l);
                    
                    print_r("\n\ninputK\n");  
                    print_r($inputK);
                    
                    print_r ("\n\n Tablica wyniów \n");
                    print_r($res[$l]);
                
            }*/
            
            $l++;
            $SumaLL = $SumaLL + $inputK['lc'];
            $LL = $step;
            
            
        }
        //print_r("tabla res");
        //print_r($res);
        return $res;
    }
    
    function r3konc($np, $MX, $MY, $MZ, $MLP, $Malfa, $Mbeta, $step){
        $step = 10;
        $LL = 0;
        $SumaLL = 0;
        $korekta = 0;
        $step_wzor = $step;
        $l = 1;
        $res = null; 
       
        /*print_r("Before Well ID\n");
        print_r("\n\nNP\n");
        print_r($np);
        print_r("\n\nMX\n");
        print_r($MX);
        print_r("\n\nMY\n");
        print_r($MY);
        print_r("\n\nMZ\n");
        print_r($MZ);
        print_r("\n\nMLP\n");
        print_r($MLP);
        print_r("\n\nMalfa\n");
        print_r($Malfa);
        print_r("\n\nMbeta\n");
        print_r($Mbeta);*/
        
         // wellId 
        for ($i = $np; $i >= 2; $i--){
        //for ($i = 2; $i <= $np; $i++){ // zmiana 13.08.2015
            
            if ($i == 2){
                $Malfa[$i-1] = 0;
                $beta0 = 0;
                break;
            }
            
            $x1 = $MX[$i-1];
            $y1 = $MY[$i-1];
            $z1 = $MZ[$i-1];
            
            $x2 = $MX[$i];
            $y2 = $MY[$i];
            $z2 = $MZ[$i];
            
            $alfa2 = $Malfa[$i];
            $beta2 = $Mbeta[$i];
            
            
            //$alfa2 = $Malfa[$i]; //zmiana 09.08 
            //$beta2 = $Mbeta[$i];
            $lp = $MLP[$i];
           // print_r($SumaLL . "\n\t");
          //  print_r($SumaLL . "\n\t");
            $inputK = $this->inputK($x1, $y1, $z1, $x2, $y2, $z2, $alfa2, $beta2, $lp);
          //  print_r($inputK);
            //$res = $inputK;
            $this->firstStreightInput($inputK, $lp, $i);
            $kx = $inputK['wx'] * ($inputK['y2prim'] - $lp) + $inputK['sx'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            $ky = $inputK['wy'] * ($inputK['y2prim'] - $lp) + $inputK['sy'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            $kz = $inputK['wz'] * ($inputK['y2prim'] - $lp) + $inputK['sz'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            
            if ($kx ==0 || $ky ==0 || $kz ==0){
                $Malfa[$i-1] = 0;
                $beta0 = 0;
            }
            else{
                $modulk = sqrt(pow(($kx), 2) + pow(($ky), 2) + pow(($kz), 2));
                $Malfa[$i-1] = rad2deg(acos($kz/ $modulk));
                $beta0 = atan(abs($kx/$ky));
            }
        
            if ($kx == 0 && $ky == 0){
                $Mbeta[$i-1] = 0;
            }
            elseif($kx >= 0 && $ky > 0 ){
                $Mbeta[$i-1] = $beta0;
            }
            elseif($kx > 0 && $ky = 0 ){
                $Mbeta[$i-1] = 1/2 * pi();
            }
            elseif($kx <= 0 && $ky < 0 ){
                $Mbeta[$i-1] = -1 * pi() - $beta0;
            }
            elseif($kx <= 0 && $ky = 0 ){
                $Mbeta[$i-1] = 2/3 * pi();
            }
            else{
                $Mbeta[$i-1] = 2 * pi() - $beta0;
            }
            $Mbeta[$i-1] = rad2deg($Mbeta[$i-1]);
          }
        
        // end of WellID
        /*print_r("After Well ID\n");  
        print_r("\n\nStep\n");   
        print_r($step);
        print_r("\n\nLL\n");  
        print_r($LL);
        print_r("\n\nSumaLL\n");  
        print_r($SumaLL);
        print_r("\n\nkorekta\n");  
        print_r($korekta);
        print_r("\n\nstep_wzor\n");  
        print_r($step_wzor);
        print_r("\n\nl\n");  
        print_r($l);
          
        print_r("Malfa"); 
        print_r($Malfa); 
        print_r("Mbeta");
        print_r($Mbeta); */
        
        $Malfa[1] = 0;
        $Mbeta[1] = 0;
        
        
        
        for($i = 2 ; $i <= $np; $i++){
            $x1 = $MX[$i-1];
            $y1 = $MY[$i-1];
            $z1 = $MZ[$i-1];
            
            $x2 = $MX[$i];
            $y2 = $MY[$i];
            $z2 = $MZ[$i];
            
            if ($i == 2){
                $alfa2 = $Malfa[$i];
                $beta2 = $Mbeta[$i];
            }
         
            $lp = $MLP[$i];
            $inputK = $this->inputK($x1, $y1, $z1, $x2, $y2, $z2, $alfa2, $beta2, $lp);
            $this->firstStreightInput($inputK, $lp, $i);
        do {
            while($LL < $inputK['lk']){
                //print("Wartosc L ". $l ."\n\t");
                $xprim = $inputK['xHPprim'] - $inputK['r'] * (1 - cos(($inputK['lk'] - $LL) / $inputK['r']));
                $yprim=  $inputK['yHPprim'] - $inputK['r'] * sin(($inputK['lk'] - $LL) / $inputK['r']);
                $MD = $SumaLL + $LL;
                //print_r("MD = ". $MD ."\n\t");
                $res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
                $res[$l]['section'] = $i;
                if ($l == 1){
                    $res[$l]['section'] = "PP";
                }
                $res[$l]['MD'] = $MD;
                $res[$l]['CL_DEP'] = 0;
                $res[$l]['CL_Angle'] = 0;
                $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
                $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                $alfa2 = $res[$l]['alfa'];
                $beta2 = $res[$l]['beta'];
		$korekta = 1;
                $step = $step_wzor;  
                $l++;
                $LL = $LL + $step;
            }
            
            if (!($lp == 0 && $i>2)){    
                if($korekta == 1){
                    $xprim = $inputK['xHPprim'];
                    $yprim=  $inputK['yHPprim'];
		    $korekta = 0;
		    $MD = $SumaLL + $inputK['lk'];   
                    $res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
                    //$res[$l]['section'] = $i;
                    $res[$l]['section'] = "PP";
                    
                    $res[$l]['MD'] = $MD;//$SumaLL + $inputK['lc'];
                    $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
            	    $step = $LL - $inputK['lk'];
	            $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                    $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                    $alfa2 = $res[$l]['alfa'];
                    $beta2 = $res[$l]['beta'];
		    $l++;
                    if ($step == 0){
                        $step = $step_wzor; 
                        $LL = $LL+$step;
		    }

               }
                
                $xprim = $inputK['xHPprim'];
                $yprim=  $inputK['yHPprim'] + ($LL - $inputK['lk']);
                $MD = $SumaLL + $LL;
		$res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
                $res[$l]['section'] = $i;
                $res[$l]['MD'] = $MD;
                $res[$l]['CL_DEP'] = 0;
                $res[$l]['CL_Angle'] = 0;
                $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                $res[$l]['beta'] = rad2deg($res[$l]['beta']);
		$this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
                $alfa2 = $res[$l]['alfa'];
                $beta2 = $res[$l]['beta'];
                $step = $step_wzor;  
                $l++;
                $LL = $LL + $step;
                    
            }
            
        }while($LL < $inputK['lc']);
        
            $xprim = $inputK['x2prim'];
            $yprim=  $inputK['y2prim'];
            $MD = $SumaLL + $inputK['lc'];
	    $res[$l] = $this->coordR3k($inputK['sx'], $inputK['sy'], $inputK['sz'], $inputK['tx'], $inputK['ty'], $inputK['tz'], $inputK['wx'], $inputK['wy'], $inputK['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputK['xHPprim'], $inputK['yHPprim'], $inputK['x2prim'], $inputK['y2prim'], $inputK['r'], $l, $LL, $inputK['lk'], $lp);
            //$res[$l]['section'] = $i;
            $res[$l]['section'] = "PP";
            $res[$l]['MD'] = $MD;
            $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
            $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
            $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                       
            if (($Malfa[$i]==0)) {
                $alfa2 = $res[$l]['alfa'];
                $beta2 = $res[$l]['beta'];
            }
            else {
                if ($i == $np){
                    $alfa2 = $Malfa[$i];
                    $beta2 = $Mbeta[$i];
                }
                else{
                    $alfa2 = $Malfa[$i+1];
                    $beta2 = $Mbeta[$i+1];
                }
            }
            // dodane 08.09.2015 15:20
            $step = $LL - $inputK['lc'];
            if($step == 0){
               $step = $step_wzor;
            }
            $l++;
            $SumaLL = $SumaLL + $inputK['lc'];
            $LL = $step;
        }
        //print_r("tabla res");
        //print_r($res);
        foreach($res as $line){
            
        }
        
        return $res;
    }
    
    //OK
    public function inputP($x1, $y1, $z1, $x2, $y2, $z2, $alfa1, $beta1, $lp){
        /*print_r("Input P |\n\t");
        print_r($x1 . " x|\n\t");
        print_r($y1 . " y|\n\t");
        print_r($z1 . " z|\n\t");
        print_r($x2 . " x2|\n\t");
        print_r($y2 . " y2|\n\t");
        print_r($z2 . " z2|\n\t");
        print_r($alfa1 . " a|\n\t");
        print_r($beta1 . " b|\n\t");
        print_r(rad2deg($alfa1) . " a st|\n\t");
        print_r(rad2deg($beta1) . " b st|\n\t");
        
        print_r($lp . " pl|\n\t");*/
        
        $sx = sin(deg2rad($alfa1)) * sin(deg2rad($beta1));
        $sy = sin(deg2rad($alfa1)) * cos(deg2rad($beta1));
        $sz = cos(deg2rad($alfa1)); 
        
        //print_r($sx . " sx\n\t");
        ///print_r($sy  . " sy|\n\t");
        //print_r($sz . " sz|\n\t");
        
        $txLicznik = ($z2-$z1)*$sy - $sz*($y2-$y1);
        
        //print_r(($z2-$z1)*$sy. " iloczyn1 \n\t"); // 20,0458
        //print_r($sz*($y2-$y1) . " iloczyn2  \n\t"); //
        
        $tyLicznik = ($x2-$x1)*$sz - $sx * ($z2-$z1);
        $tzLicznik = ($y2-$y1)*$sx - $sy * ($x2-$x1);
        
        //print_r($txLicznik . " sx \n\t");
       // print_r($tyLicznik  . " sy| \n\t");
        //print_r($tzLicznik . " sz| \n\t ");
        
        $tMianownik = sqrt(pow($txLicznik,2) + pow($tyLicznik,2) + pow($tzLicznik,2));
        if ($tMianownik == 0){
            $tx = 0;
            $ty = 0;
            $tz = 0; 
        }else{
            $tx = $txLicznik / $tMianownik;
            $ty = $tyLicznik / $tMianownik;
            $tz = $tzLicznik / $tMianownik; 
        }
        
       // print_r($tx . " tx| \n\t");
       // print_r($ty  . " ty| \n\t");
       // print_r($tz . " tz| \n\t");
        
        
        $wx = $ty * $sz - $sy * $tz;
        $wy = $tz * $sx - $tx * $sz;
        $wz = $tx * $sy - $sx * $ty;
        
        //print_r($wx . " wx| \n\t");
       // print_r($wy  . " wy| \n\t");
       // print_r($wz . " wz| \n\t");
        
        $x2prim = ($x2 - $x1) * $wx + ($y2-$y1) * $wy + ($z2 - $z1) * $wz;
        $y2prim = ($x2 - $x1) * $sx + ($y2-$y1) * $sy + ($z2 - $z1) * $sz;
        
       // print_r($x2prim . " x2prim| \n\t ");
       // print_r($y2prim  . " y2prim|  \n\t ");
       // print_r($wz . " wz| ");
        
       
        
        if ($x2prim==0) {
            $res['sx'] = $sx;
            $res['sy'] = $sy;
            $res['sz'] = $sz;

            $res['tx'] = $tx;
            $res['ty'] = $ty;
            $res['tz'] = $tz;

            $res['wx'] = $wx;
            $res['wy'] = $wy;
            $res['wz'] = $wz;
            $res['lc'] = $lp;
            $res['lk'] = 0;
            $res['x2prim'] = $x2prim;
            $res['y2prim'] = $y2prim;
            $res['xHPprim'] = 0;
            $res['yHPprim'] = 0;
        
            $res['delta'] = 0;
            $res['r'] = 0;
            return $res;
        }
         $r =  (pow($y2prim,2) + pow($x2prim,2) - pow($lp,2))/ (2 * $x2prim);
       //  print_r(pow($y2prim,2) + pow($x2prim,2) - pow($lp,2) . " licznik  \n\t");
      //  print_r(2 * $x2prim . " mianownik  \n\t");
        if($y2prim >= 0){
            $xHPprim = $r *($r * $x2prim + pow($lp,2) - $lp * $y2prim) / (pow($r,2) + pow($lp,2)); 
            $yHPprim = sqrt(2* $xHPprim * $r - pow($xHPprim,2));
        }else{
            //if($y2prim < 0 && $x2prim >= 2 * $r){
            if($x2prim >= 2 * $r){    
                $xHPprim = $r * ($r * $x2prim + pow($lp,2) + $lp * $y2prim) / (pow($r,2) + pow($lp,2));
                $yHPprim = sqrt(2 * $xHPprim * $r - pow($xHPprim,2));
            }
            else{
                $xHPprim = $r * ($r * $x2prim + pow($lp,2) + $lp * $y2prim) / (pow($r,2) + pow($lp,2));
                $yHPprim = -1 * sqrt(2 * $xHPprim * $r - pow($xHPprim,2));
            }
        }
            
        if ($xHPprim < $r && $yHPprim >= 0){
            $delta = atan($yHPprim / ($r - $xHPprim));
        }
        elseif($xHPprim = $r && $yHPprim > 0){
            $delta = pi() / 2;
        }
        elseif($xHPprim > $r && $yHPprim >= 0){
            $delta = pi() - atan($yHPprim / ($xHPprim - $r));
        }
        elseif($xHPprim > $r && $yHPprim < 0){
            $delta = pi() + atan(abs($yHPprim) / ($xHPprim - $r));
        }
        elseif($xHPprim = $r && $yHPprim < 0){
            $delta = 3/2 * pi();
        }
        else{
            $delta = 2 * pi() - atan(abs($yHPprim) / ($r - $xHPprim));
        }
        
        $lk = $r * $delta;
        
        $lc = $lk + $lp;
        
        $res['sx'] = $sx;
        $res['sy'] = $sy;
        $res['sz'] = $sz;
        
        $res['tx'] = $tx;
        $res['ty'] = $ty;
        $res['tz'] = $tz;
        
        $res['wx'] = $wx;
        $res['wy'] = $wy;
        $res['wz'] = $wz;
        
        $res['x2prim'] = $x2prim;
        $res['y2prim'] = $y2prim;
        
        $res['xHPprim'] = $xHPprim;
        $res['yHPprim'] = $yHPprim;
        
        $res['delta'] = $delta;
        
        $res['lk'] = $lk;
        $res['lc'] = $lc;
        
        $res['r'] = $r;
        //print_r($res);
        return $res;
        
    }
    //OK
    function coordR3p($sx, $sy, $sz, $tx, $ty, $tz, $wx, $wy, $wz, $x1 ,$y1, $z1, $xprim, $yprim, $xHPprim, $yHPprim, $r, $l, $ll, $lk, $iteration){
      //  print_r("CordR3 | \n\t");
        $X[$l] = $x1 + $wx * $xprim + $sx * $yprim;
        $Y[$l] = $y1 + $wy * $xprim + $sy * $yprim;
        $Z[$l] = $z1 + $wz * $xprim + $sz * $yprim;
       // print_r("wx  yprim sx (r - xprim)". $wx . " ". $yprim . " ". $sx. " ". ($r - $xprim) . " | \n\t");
        
        if ($ll < $lk){
           // print_r("wx  yprim sx (r - xprim)". $wx . " ". $yprim . " ". $sx. " ". ($r - $xprim). " | \n\t");
            $kx = $wx * $yprim + $sx * ($r - $xprim);
            $ky = $wy * $yprim + $sy * ($r - $xprim);
            $kz = $wz * $yprim + $sz * ($r - $xprim);
        }
        else{
           // print_r("wx  yprim sx (r - xprim)". $wx . " ". $yHPprim . " ". $sx. " ". ($r - $xHPprim) ." | \n\t");
            $kx = $wx * $yHPprim + $sx * ($r - $xHPprim);
            $ky = $wy * $yHPprim + $sy * ($r - $xHPprim);
            $kz = $wz * $yHPprim + $sz * ($r - $xHPprim);
            
        }
       // print_r("KX KY KZ ". $kx . " ". $ky . " ". $kz . " | \n\t");
        if($kx == 0 && $kx == 0 && $kx == 0){
            $alfa[$l] = 0;
            $beta0 = 0;
        }
        else{
            $alfa[$l] = acos($kz/sqrt(pow($kx,2)+pow($ky,2)+pow($kz,2)));
            $beta0 = atan(abs($kx/$ky));
        }
        
        //$modulk = sqrt(pow(($kx), 2) + pow(($ky), 2) + pow(($kz), 2));
        //$alfa[$l] = acos($kz/ $modulk);
        
        //$angle_inclination = acos($kz/sqrt(pow($kx,2)+ pow($ky,2)+pow($kz,2)));
        
        //$beta0 = atan(abs($kx/$ky));
        // obsolete
        /*if ($kx == 0 && $ky == 0){
            $beta[$l] = 0;
        }
        elseif($kx >= 0 && $ky > 0 ){
            $beta[$l] = $beta0;
        }
        elseif($kx > 0 && $ky = 0 ){
            $beta[$l] = pi()/2;
        }
        elseif($kx >= 0 && $ky < 0){
            $beta[$l] = pi() - $beta0;
        }
        elseif($kx <= 0 && $ky < 0 ){
            $beta[$l] = pi() + $beta0;
        }
        elseif($kx < 0 && $ky = 0 ){
            $beta[$l] = 3/2 * pi();
        }
        else{
            $beta[$l] = 2 * pi() - $beta0;
        }*/
        if ($kx == 0 && $ky >= 0){
            $beta[$l] = 0;
        }
        elseif($kx > 0 && $ky > 0){
            $beta[$l] = $beta0;
        }
        elseif($kx > 0 && $ky == 0){
            $beta[$l] = 1/2 * pi();
        }
        elseif($kx < 0 && $ky < 0){
            $beta[$l] = pi() - $beta0;
        }
        elseif($kx == 0 && $ky < 0){
                $beta[$l] = pi();
        }
        elseif($kx < 0 && $ky == 0){
            $beta[$l] = 3/2 * pi();
        }
        else{
            $beta[$l] =  2 * pi() - $beta0;
        }
            
        
        
        $res['X'] = $X[$l];
        $res['Y'] = $Y[$l];
        $res['Z'] = $Z[$l];
        
        $res['kx'] = $kx;
        $res['ky'] = $ky;
        $res['kz'] = $kz;
        
        //$res['modulk'] = $modulk;
        $res['alfa'] = $alfa[$l];
        $res['beta0'] = $beta0;
        $res['beta'] = $beta[$l]; 
        
        if ($iteration == 2){
            
        }
        
        //$res['ang_inc'] = $angle_inclination;
        
        //print_r($res);
        
        return $res;
    }
    
    function firstStreightInput(&$res, $lp, $i){
        if ($i == 2){
            $res['delta'] = 0;
            $res['r'] = 0;
            $res['lk'] = 0;
            $res['lc'] = $lp;
        }
        
    }
    
    function firstStreightCoord(&$res,$MD, $Malfa, $Mbeta, $i){
        if ($i == 2){
           $res['X'] = 0;
           $res['Y'] = 0;
           $res['Z'] = $MD;
           $res['alfa'] = $Malfa[$i];
           $res['beta'] = $Mbeta[$i];
        }
    }
    
    
    //OK
    function r3pocz($np, $MX, $MY, $MZ, $MLP, $Malfa, $Mbeta, $step){
        $step = 10;
        $LL = 0;
        $SumaLL = 0;
        $korekta = 0;
        $step_wzor = $step;
        $l = 1;
        $res = null; 
        
        /*print_r("At input\n");
        print_r("\n\nNP\n");
        print_r($np);
        print_r("\n\nMX\n");
        print_r($MX);
        print_r("\n\nMY\n");
        print_r($MY);
        print_r("\n\nMZ\n");
        print_r($MZ);
        print_r("\n\nMLP\n");
        print_r($MLP);
        print_r("\n\nMalfa\n");
        print_r($Malfa);
        print_r("\n\nMbeta\n");
        print_r($Mbeta);*/
        
        for($i = 2 ; $i <= $np; $i++){
            $x1 = $MX[$i-1];
            $y1 = $MY[$i-1];
            $z1 = $MZ[$i-1];
            
            $x2 = $MX[$i];
            $y2 = $MY[$i];
            $z2 = $MZ[$i];
            
            if ($i == 2 ) $alfa1 = $Malfa[$i-1];
            if ($i == 2 ) $beta1 = $Mbeta[$i-1];
          // print_r(" \n\t R3Pocz | \n\t");  
          // print_r($x1 . " | ");
          // print_r($y1. " | ");
          /// print_r($z1. " | ");
           //  print_r($x2 . " | ");
           // print_r($y2. " | ");
           /// print_r($z2. " | ");
           // print_r($alfa1. " a| ");
           // print_r($beta1. " b| ");
            
            
            
            //$alfa2 = $Malfa[$i];
            //$beta2 = $Mbeta[$i];
            $lp = $MLP[$i];
            $inputP = $this->inputP($x1, $y1, $z1, $x2, $y2, $z2, $alfa1, $beta1, $lp);
          // print_r($inputP);
           // print_r("|LL: " . $LL . " " .$i.  "| " );
           $this->firstStreightInput($inputP, $lp, $i);
        do {
            while($LL < $inputP['lk']){
                //print("Wartosc L ". $l ."\n\t");
                $xprim = $inputP['r'] * (1 -cos($LL / $inputP['r']));
                
                
                $yprim = $inputP['r'] *  sin($LL / $inputP['r']);
                $MD = $SumaLL + $LL;
                //print_r("460 !!!!!! \n\t");
                // print_r($inputP['r'] . " r\n\t");
                ///  print_r($LL . " r \n\t");
                
               // print_r($xprim . "\n\t");
               // print_r($yprim ."\n\t");
                
                //print_r($inputP);
                $res[$l] = $this->coordR3p($inputP['sx'], $inputP['sy'], $inputP['sz'], $inputP['tx'], $inputP['ty'], $inputP['tz'], $inputP['wx'], $inputP['wy'], $inputP['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputP['xHPprim'], $inputP['yHPprim'], $inputP['r'], $l, $LL, $inputP['lk']);
                $res[$l]['section'] = $i;
                if ($l == 1){
                    $res[$l]['section'] = "PP";
                            }
                $res[$l]['MD'] = $MD;
                $res[$l]['CL_DEP'] = 0;
                $res[$l]['CL_Angle'] = 0;
               // print_r(" \n\t  CordR3P1 \n\t");
               // print_r( $MD  ."  \n\t");
               // print_r(" CordR3P1 \n\t");
               // print_r($res[$l]);
                 $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
                    $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                    $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                    
                    $alfa1 = $res[$l]['alfa'];
                    $beta1 = $res[$l]['beta'];
                    
                   // print_r("Alfa1 st ".rad2deg($res[$l]['alfa']) .  "|\n\t");
                   // print_r("Beta1 st ".rad2deg($res[$l]['beta']) .  "|\n\t");
                   // print_r("KONIEC LL<LK \n\t");
                //print_r($res[$l]['alfa'] ." ALFA ST \n\t ");
                //print_r($res[$l]['beta']." BETA ST \n\t ");
                //print_r($coordR3p[$l]);
                $korekta = 1;
                $step = $step_wzor;
                $l++;
                $LL = $LL + $step;
                
            }
            
            if (!($lp == 0 && $i>2)){    
                if($korekta == 1){
                    //print_r("!!!!!!!!!!!!!!!!!!!!!!!!!!!");
                    $xprim = $inputP['xHPprim'];
                    $yprim = $inputP['yHPprim'];
                    $korekta = 0;
                    $MD = $SumaLL + $inputP['lk'];
                    
                    $res[$l] = $this->coordR3p($inputP['sx'], $inputP['sy'], $inputP['sz'], $inputP['tx'], $inputP['ty'], $inputP['tz'], $inputP['wx'], $inputP['wy'], $inputP['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputP['xHPprim'], $inputP['yHPprim'], $inputP['r'], $l, $LL, $inputP['lk']);
                    $res[$l]['section'] = "PP";
                    $res[$l]['MD'] = $MD;
                  //  print_r(" \n\t CordR3P1 \n\t");
                   // print_r( $MD  ."  \n\t");
                   // print_r(" CordR3P1 \n\t");
                    $res[$l]['CL_DEP'] = 0;
                    $res[$l]['CL_Angle'] = 0;
                    //print_r($coordR3p[$l]);
                    
                    $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
                    
                    $step = $LL -  $inputP['lk'];
                  //  print_r(" STEP: " . $step. " STEP1 |");
                  //  print_r($res[$l]);
                  // print_r("korekta1");
                    
                    $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                    $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                    
                    $alfa1 = $res[$l]['alfa'];
                    $beta1 = $res[$l]['beta'];
                   // print_r("Alfa1 st ".rad2deg($res[$l]['alfa']) .  "|\n\t");
                    //print_r("Beta1 st ".rad2deg($res[$l]['beta']) .  "|\n\t");
                    
                    
                    $l++;
                    
                    if ($step == 0){
                        $step = $step_wzor;
                        $LL = $LL + $step;
                     //   print_r(" STEP: " . $step. " STEP ". $LL ." |");
                    }
                   
                }
                
                //if ($LL < $inputP['lc']){
                    $xprim = $inputP['xHPprim'] + ($LL - $inputP['lk']) * sin($inputP['delta']);
                    $yprim = $inputP['yHPprim'] + ($LL - $inputP['lk']) * cos($inputP['delta']);
                   
                   // print_r($xprim . "  xprim | ");
                   // print_r($xprim . "  yprim |");
                    
                    $MD = $SumaLL + $LL;
                    
                 //   print_r($MD . "  MD |");
                    
                    $res[$l] = $this->coordR3p($inputP['sx'], $inputP['sy'], $inputP['sz'], $inputP['tx'], $inputP['ty'], $inputP['tz'], $inputP['wx'], $inputP['wy'], $inputP['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputP['xHPprim'], $inputP['yHPprim'], $inputP['r'], $l, $LL, $inputP['lk']);
                    $res[$l]['section'] = $i;
                    $res[$l]['MD'] = $MD;
                   // print_r(" \n\t CordR3P1 \n\t");
                   // print_r( $MD ."  \n\t");
                   // print_r(" CordR3P1 \n\t");
                    $res[$l]['CL_DEP'] = 0;
                    $res[$l]['CL_Angle'] = 0;
                     
                    $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                    $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                    
                    $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
                    
                    $alfa1 = $res[$l]['alfa'];
                    $beta1 = $res[$l]['beta'];
                   // print_r("Alfa1 st ".rad2deg($res[$l]['alfa']) .  "|\n\t");
                   // print_r("Beta1 st ".rad2deg($res[$l]['beta']) .  "|\n\t");
                    //print_r($res[$l]);
                   // print_r("po korekcie");
                    //print_r($coordR3p[$l]);
                    $step = $step_wzor;
                    $l++;
                    $LL = $LL + $step;
                  //  print_r(" STEP: " . $step. " STEP2 ". $LL ." |");
                //
                //print_r($res[$l]);
                    
                   // print_r($LL . " LL! ");
                   // print_r($inputP['lc'] . " LC! ");
            }
            
        }while($LL < $inputP['lc']);
        
           // print_r($inputP);
            $xprim = $inputP['x2prim'];
            $yprim = $inputP['y2prim'];
            
            //print_r($xprim . "  xprim | ");
            //print_r($xprim . "  yprim |");
            
            $MD = $SumaLL + $inputP['lc'];
           // print_r("##############################");
          //  print_r($inputP);
            $res[$l] = $this->coordR3p($inputP['sx'], $inputP['sy'], $inputP['sz'], $inputP['tx'], $inputP['ty'], $inputP['tz'], $inputP['wx'], $inputP['wy'], $inputP['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputP['xHPprim'], $inputP['yHPprim'], $inputP['r'], $l, $LL, $inputP['lk']);
            $res[$l]['section'] = "PP";
;
            $res[$l]['MD'] = $MD;
            $res[$l]['CL_DEP'] = 0;
            $res[$l]['CL_Angle'] = 0;
            
            $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
            
            //print_r($coordR3p[$l]);
            //print_r(" \n\t CordR3P1 \n\t");
            //print_r( $MD ."  \n\t");
            //print_r(" CordR3P1 \n\t");
            
            $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
            $res[$l]['beta'] = rad2deg($res[$l]['beta']);
            
            if (($Malfa[$i]==0) and ($Malfa[$i]==0)) {
                $alfa1 = $res[$l]['alfa'];
                $beta1 = $res[$l]['beta'];
            }
            else {
               $alfa1 = $Malfa[$i];
               $beta1 = $Mbeta[$i];
            }
            //print_r("Alfa1 ". $alfa1 .  "|\n\t");
            //print_r("Beta1 ". $beta1 ." |\n\t ");
          ///  
            //$alfa1 = $res[$l]['alfa'];
            //$beta1 = $res[$l]['beta'];
            // print_r("Alfa1 st ".rad2deg($res[$l]['alfa']) .  "|\n\t");
            // print_r("Beta1 st ".rad2deg($res[$l]['beta']) .  "|\n\t");
            //print_r($res[$l]);
            //print_r("po do while");
           $step = $LL - $inputP['lc'];
           
           if($step == 0){
               $step = $step_wzor;
           }
            
           
           $l++;
           $SumaLL = $SumaLL + $inputP['lc'];
         //  print_r("SUMA LL: " . $SumaLL . " ||");
           $LL = $step;
         //  print_r($LL . " LL!# ");
            //print_r($res[$l]);*/
        }
        
       // print_r($res);
        
        /*print_r("Malfa\n"); 
        print_r($Malfa); 
        print_r("Mbeta\n");
        print_r($Mbeta);
        
        print_r('LAST POINT [alfa and beta in degrees]'."\n\n");
        print_r(end($res));*/
        
        return $res;
        
        
        
    }
    

}
