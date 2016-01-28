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
                if ( $this->LL - $L[$i] > 0){
                    
                    $counter ++;
                    $res[$counter]['MD'] = $this->sumL + $L[$i];
                    $res[$counter]['beta'] = $azimut;
                    $res[$counter]['alfa'] = $ALPHA[$i];

                    $res[$counter]['X'] = ($this->sumA + $A[$i]) * sin($res[$counter]['beta']);
                    $res[$counter]['Y'] = ($this->sumA + $A[$i]) * cos($res[$counter]['beta']);
                    $res[$counter]['Z'] = $this->sumH + $H[$i];
                    $res[$counter]['section'] = "PP";
                } 
                $this->sumH = $this->sumH + $H[$i];
                $this->sumA = $this->sumA + $A[$i];
                $this->sumL = $this->sumL + $L[$i];
        }
        return $res;
    }
    
    public function calculateVsAngle($firstPoint, $lastPoint){
        $kx = $lastPoint['X'] - $firstPoint['X'];
        $ky = $lastPoint['Y'] - $firstPoint['Y'];
        $angle = rad2deg(atan(abs($kx/$ky)));
        if (($kx == 0) and ($ky == 0)) $angle = 0;
        if (($kx >= 0) and ($ky >  0)) $angle = $angle;
        if (($kx >  0) and ($ky == 0)) $angle = rad2deg(PI() / 2);
        if (($kx >= 0) and ($ky <  0)) $angle = rad2deg(PI()) - $angle;
        if (($kx <= 0) and ($ky <  0)) $angle = rad2deg(PI()) * $angle;
        if (($kx <  0) and ($ky == 0)) $angle = rad2deg(3 * PI() / 2);
        if (($kx <= 0) and ($ky >  0)) $angle = rad2deg(2 * PI()) - $angle; 
        return $angle;
    }
    
    public function calculateVerticalSection(&$dataArray, $vs_angle = 0){
        for ($i = 1; $i <= count($dataArray); $i++){
            $kx = $dataArray[$i]['X'] - $dataArray[1]['X'];
            $ky = $dataArray[$i]['Y'] - $dataArray[1]['Y'];
            $dept = sqrt(pow($kx,2) + pow($ky,2));
            if ($ky == 0) {
                $angle = 0;
            }    
            else{ 
                $angle = rad2deg(atan(abs($kx/$ky)));
            }
            
            if (($kx == 0)and($ky == 0)) $angle = 0;
            if (($kx >= 0)and($ky >  0)) $angle = $angle;
            if (($kx >  0)and($ky == 0)) $angle = rad2deg(PI() / 2);
            if (($kx >= 0)and($ky <  0)) $angle = rad2deg(PI()) - $angle;
            if (($kx <= 0)and($ky <  0)) $angle = rad2deg(PI()) * $angle;
            if (($kx <  0)and($ky == 0)) $angle = rad2deg(3*PI()/2);
            if (($kx <= 0)and($ky >  0)) $angle = rad2deg(2*PI()) - $angle;
            
            $dataArray[$i]['CL_DEP'] = $dept;
            $dataArray[$i]['CL_Angle'] = $angle;
            $dataArray[$i]['V_SECTION'] = $dept* cos(deg2rad($angle-$vs_angle));

            if ($i > 1){
			 $dataArray[$i]['DLS'] = rad2deg(acos (cos (deg2rad ( $dataArray[$i-1]['alfa'] )) * cos(deg2rad( $dataArray[$i]['alfa'] )) 
				
					+ sin( deg2rad($dataArray[$i-1]['alfa'] )) * sin(deg2rad( $dataArray[$i]['alfa']) ) 
                                    	
				        * cos( deg2rad($dataArray[$i]['beta'] - $dataArray[$i-1]['beta'] ) ) ) )
                                    
					* 30.48 / ($dataArray[$i]['MD'] - $dataArray[$i-1]['MD']);

            }
            else{
               $dataArray[$i]['DLS'] = 0; 
            };
        }
    } 
        
    public function inputK($x1, $y1, $z1, $x2, $y2, $z2, $alfa2, $beta2, $lp){
        
        //wg Profesora

        $sx = sin(deg2rad($alfa2)) * sin(deg2rad($beta2));
        $sy = sin(deg2rad($alfa2)) * cos(deg2rad($beta2));
        $sz = cos(deg2rad($alfa2));
        //if (abs($sz<0.00001)){$sz=0.0;}
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
          //  if (abs($tz<0.00001)){$tz=0.0;}
	}
        $wx = $ty * $sz - $tz * $sy;     // to sa nasze probelmy!   tz=0, sz=0 => wx=0
        $wy = $tz * $sx - $tx * $sz;     // to sa nasze probelmy!
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

		print_r($xHPprim . " xHPprim | \n\t ");


        
        if ($xHPprim < $r && $yHPprim >= 0){
            $delta = atan($yHPprim / ($r - $xHPprim));
        }
        elseif($xHPprim == $r && $yHPprim > 0){
            $delta = pi()/2;
        }
        elseif($xHPprim > $r && $yHPprim >= 0){
            $delta = pi() - atan($yHPprim / ($xHPprim - $r));
        }
        elseif($xHPprim > $r && $yHPprim < 0){
            $delta = pi() + atan(abs($yHPprim) / ($xHPprim - $r));
        }
        elseif($xHPprim == $r && $yHPprim < 0){
            $delta = 3/2 * pi();
        }
        elseif ($xHPprim < $r && $yHPprim < 0){
            $delta = 2 * pi() - atan(abs($yHPprim) / ($r - $xHPprim));
        }
		print_r($xHPprim . " xHPprimPODELTA | \n\t ");
     
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


            if ( abs ( $kx ) <= 0.01  ) { 
					$kx = 0;
					}
            if ( abs ( $ky ) <= 0.01  ) { 
					$ky = 0;
					}
	    $alfa[$l] = /*rad2deg(*/ acos($kz/sqrt(pow($kx,2)+pow($ky,2)+pow($kz,2)));// ); warunek wynikaj¥cy z pˆaszczyzny pionowej		                

            if ( $kx == 0 && $ky == 0) {
					$kx =  $sx; 
					$ky =  $sy; 
						}	
        // -------------------z r3pocz  

            //print_r($kx . "KX-c| \n\t ");
            //print_r($ky . "KY-c| \n\t ");
    
            $beta0 = atan(abs($kx/$ky));
        
        if ($kx == 0 && $ky >= 0){
            $beta[$l] = 0;
        }
        elseif($kx > 0 && $ky > 0){
            $beta[$l] = $beta0;
        }
        elseif($kx > 0 && $ky == 0){
            $beta[$l] = 1/2 * pi();
        }
        elseif($kx > 0 && $ky < 0){
            $beta[$l] = pi() - $beta0;
        }
        elseif($kx == 0 && $ky < 0){
                $beta[$l] = pi();
        }
        elseif($kx < 0 && $ky < 0 ){    
        $beta[$l] =pi() + $beta0;
        }
        elseif($kx < 0 && $ky == 0){
            $beta[$l] = 3/2 * pi();
        }
        else{
            $beta[$l] =  2 * pi() - $beta0;
        }
//print_r($beta[$l] . " beta0 COORDr3k | \n\t ");

//----------- end of r3pocz

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
        return $res;
    }

    function r3konc($np, $MX, $MY, $MZ, $MLP, $Malfa, $Mbeta, $step, $vsAngle = null){
        $step = 10;
        $LL = 0;
        $SumaLL = 0;
        $korekta = 0;
        $step_wzor = $step;
        $l = 1;
        $res = null; 
        
         // wellId 
        for ($i = $np; $i >= 2; $i--){
            
            if ($i == 2){
                $Malfa[$i-1] = 0;
                $Mbeta[$i-1] = 0;
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
	    $lp = $MLP[$i];

            $inputK = $this->inputK($x1, $y1, $z1, $x2, $y2, $z2, $alfa2, $beta2, $lp);
                   //	print_r($inputK['x2prim'] . "X2P WELLID | \n\t ");
                   //	print_r($inputK['xHPprim'] . "X_HP WELLID | \n\t ");

            $this->firstStreightInput($inputK, $lp, $i);          

	    $kx = $inputK['wx'] * ($inputK['y2prim'] - $lp) + $inputK['sx'] * (-1 * $inputK['x2prim'] + $inputK['r']);    //gdy wx i wy=0 dla 90 stopni to zmieniaja sie znaki kx i ky, nastepnie zle liczy beta0
            $ky = $inputK['wy'] * ($inputK['y2prim'] - $lp) + $inputK['sy'] * (-1 * $inputK['x2prim'] + $inputK['r']);
            $kz = $inputK['wz'] * ($inputK['y2prim'] - $lp) + $inputK['sz'] * (-1 * $inputK['x2prim'] + $inputK['r']);

/*            if ( abs ( $kx ) <= 0.005  ) { 
					$kx = 0;
					}
            if ( abs ( $ky ) <= 0.005  ) { 
					$ky = 0;
					}
*/	
	    $Malfa[$i-1] = rad2deg( acos($kz/sqrt(pow($kx,2)+pow($ky,2)+pow($kz,2))) );// warunek wynikaj¥cy z pˆaszczyzny pionowej		                
/*	
            if ( $kx == 0 && $ky == 0) {
					$kx =  $inputK['sx']; 
					$ky =  $inputK['sy']; }
*/	
        /*    print_r($kx . " KX | \n\t ");
            print_r($ky . " KY | \n\t ");
            print_r($kz . " KZ | \n\t ");
          */

          /*print_r($inputK['x2prim'] . " X2PRIM | \n\t ");
            print_r($inputK['r'] . " RRR | \n\t ");
            print_r($inputK['wx'] . " WX | \n\t ");
            print_r($inputK['wy'] . " WY | \n\t ");
            if ($inputK['wx']!=0) { print_r("ZERO");}  
            */
        $beta0 = atan(abs($kx/$ky));

        if ($kx == 0 && $ky >= 0){
            $Mbeta[$i-1] = 0;
        }
        elseif($kx > 0 && $ky > 0){
            $Mbeta[$i-1] = $beta0;
        }
        elseif($kx > 0 && $ky == 0){
            $Mbeta[$i-1] = 1/2 * pi();
        }
        elseif($kx > 0 && $ky < 0){
            $Mbeta[$i-1] = pi() - $beta0;
        }
        elseif($kx == 0 && $ky < 0){
                $Mbeta[$i-1] = pi();
        }
        elseif($kx < 0 && $ky < 0 ){    
        $Mbeta[$i-1] = pi() + $beta0; //tutaj wchodzi
        }
        elseif($kx < 0 && $ky == 0){
            $Mbeta[$i-1] = 3/2 * pi();
        }
        else{
            $Mbeta[$i-1] =  2 * pi() - $beta0;
        }
            $Mbeta[$i-1] = rad2deg($Mbeta[$i-1]);
          }
/*	print_r("---------------------------well id  ");
        print_r($i);
        print_r("Malfa="); 
        print_r($Malfa); 
        print_r("Mbeta=");
        print_r($Mbeta);  
*/        
        $Malfa[1] = 0;
        $Mbeta[1] = 0;
        for($i = 2 ; $i <= $np; $i++){
            $x1 = $MX[$i-1];
            $y1 = $MY[$i-1];
            $z1 = $MZ[$i-1];
            
            $x2 = $MX[$i];
            $y2 = $MY[$i];
            $z2 = $MZ[$i];
            
		{
                $alfa2 = $Malfa[$i];
                $beta2 = $Mbeta[$i];
  	        }
         
            $lp = $MLP[$i];

            $inputK = $this->inputK($x1, $y1, $z1, $x2, $y2, $z2, $alfa2, $beta2, $lp);
		 $this->firstStreightInput($inputK, $lp, $i);

        do {
            while($LL < $inputK['lk']){
                $xprim = $inputK['xHPprim'] - $inputK['r'] * (1 - cos(($inputK['lk'] - $LL) / $inputK['r']));
                
		//print_r($inputK['xHPprim'] . " XHP-c | \n\t ");
               // print_r($inputK['r'] . " r-c | \n\t ");

		$yprim=  $inputK['yHPprim'] - $inputK['r'] * sin(($inputK['lk'] - $LL) / $inputK['r']);
                $MD = $SumaLL + $LL;
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
          if ($i == 2) 
		{
               $alfa2 = $Malfa[$i+1];
               $beta2 = $Mbeta[$i+1];
               $res[$l]['alfa'] = $Malfa[$i]; //   
               $res[$l]['beta'] = $Mbeta[$i]; //$beta2;   
            	}
            else 
		{
               $alfa2 = $res[$l]['alfa'];
               $beta2 = $res[$l]['beta'];
            	}
            $step = $LL - $inputK['lc'];
            if($step == 0){
               $step = $step_wzor;
            }
            $l++;
            $SumaLL = $SumaLL + $inputK['lc'];
            $LL = $step;
        }  // petla for
        if ($vsAngle !== null){
           $angleParametr = $vsAngle;
        }
        else {
            $angleParametr = $this->calculateVsAngle($res[1], $res[count($res)]);
        }    
        $this->calculateVerticalSection($res, $angleParametr);
        return $res;
    }
 
   public function inputP($x1, $y1, $z1, $x2, $y2, $z2, $alfa1, $beta1, $lp){
        
        $sx = sin(deg2rad($alfa1)) * sin(deg2rad($beta1));
        $sy = sin(deg2rad($alfa1)) * cos(deg2rad($beta1));
        $sz = cos(deg2rad($alfa1)); 
        
        $txLicznik = ($z2-$z1)*$sy - $sz*($y2-$y1);
        $tyLicznik = ($x2-$x1)*$sz - $sx * ($z2-$z1);
        $tzLicznik = ($y2-$y1)*$sx - $sy * ($x2-$x1);
        
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
        
        $wx = $ty * $sz - $sy * $tz;
        $wy = $tz * $sx - $tx * $sz;
        $wz = $tx * $sy - $sx * $ty;
        
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
         $r =  (pow($y2prim,2) + pow($x2prim,2) - pow($lp,2))/ (2 * $x2prim);
        if($y2prim >= 0){
            $xHPprim = $r *($r * $x2prim + pow($lp,2) - $lp * $y2prim) / (pow($r,2) + pow($lp,2)); 
            $yHPprim = sqrt(2* $xHPprim * $r - pow($xHPprim,2));
        }else{
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
        elseif($xHPprim == $r && $yHPprim > 0){
            $delta = pi() / 2;
        }
        elseif($xHPprim > $r && $yHPprim >= 0){
            $delta = pi() - atan($yHPprim / ($xHPprim - $r));
        }
        elseif($xHPprim > $r && $yHPprim < 0){
            $delta = pi() + atan(abs($yHPprim) / ($xHPprim - $r));
        }
        elseif($xHPprim == $r && $yHPprim < 0){
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
    function coordR3p($sx, $sy, $sz, $tx, $ty, $tz, $wx, $wy, $wz, $x1 ,$y1, $z1, $xprim, $yprim, $xHPprim, $yHPprim, $r, $l, $ll, $lk, $iteration){
        $X[$l] = $x1 + $wx * $xprim + $sx * $yprim;
        $Y[$l] = $y1 + $wy * $xprim + $sy * $yprim;
        $Z[$l] = $z1 + $wz * $xprim + $sz * $yprim;
        if ($ll < $lk){
            $kx = $wx * $yprim + $sx * ($r - $xprim);
            $ky = $wy * $yprim + $sy * ($r - $xprim);
            $kz = $wz * $yprim + $sz * ($r - $xprim);
        }
        else{
            $kx = $wx * $yHPprim + $sx * ($r - $xHPprim);
            $ky = $wy * $yHPprim + $sy * ($r - $xHPprim);
            $kz = $wz * $yHPprim + $sz * ($r - $xHPprim);
            
        }
        if($kx == 0 && $kx == 0 && $kx == 0){
            $alfa[$l] = 0;
            $beta0 = 0;
        }
        else{
            $alfa[$l] = acos($kz/sqrt(pow($kx,2)+pow($ky,2)+pow($kz,2)));
            $beta0 = atan(abs($kx/$ky));
        }
        
        if ($kx == 0 && $ky >= 0){
            $beta[$l] = 0;
        }
        elseif($kx > 0 && $ky > 0){
            $beta[$l] = $beta0;
        }
        elseif($kx > 0 && $ky == 0){
            $beta[$l] = 1/2 * pi();
        }
        elseif($kx > 0 && $ky < 0){
            $beta[$l] = pi() - $beta0;
        }
        elseif($kx == 0 && $ky < 0){
                $beta[$l] = pi();
        }
        elseif($kx < 0 && $ky < 0 ){    
        $beta[$l] = pi() + $beta0;
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
        $res['alfa'] = $alfa[$l];
        $res['beta0'] = $beta0;
        $res['beta'] = $beta[$l]; 
        if ($iteration == 2){
            
        }
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
           $res['alfa'] = $Malfa[$i-1];
           $res['beta'] = $Mbeta[$i-1];
        }
    }
    function r3pocz($np, $MX, $MY, $MZ, $MLP, $Malfa, $Mbeta, $step, $vsAngle = null){
        $step = 10;
        $LL = 0;
        $SumaLL = 0;
        $korekta = 0;
        $step_wzor = $step;
        $l = 1;
        $res = null; 
        
        for($i = 2 ; $i <= $np; $i++){
            $x1 = $MX[$i-1];
            $y1 = $MY[$i-1];
            $z1 = $MZ[$i-1];
            
            $x2 = $MX[$i];
            $y2 = $MY[$i];
            $z2 = $MZ[$i];
            
            if ($i == 2 ) 
            {   $alfa1 = $Malfa[$i-1];
		$beta1 = $Mbeta[$i-1];
 	    }
            $lp = $MLP[$i];
            $inputP = $this->inputP($x1, $y1, $z1, $x2, $y2, $z2, $alfa1, $beta1, $lp);
           $this->firstStreightInput($inputP, $lp, $i);
        do {
            while($LL < $inputP['lk']){
                $xprim = $inputP['r'] * (1 -cos($LL / $inputP['r']));
                $yprim = $inputP['r'] *  sin($LL / $inputP['r']);
                $MD = $SumaLL + $LL;
                $res[$l] = $this->coordR3p($inputP['sx'], $inputP['sy'], $inputP['sz'], $inputP['tx'], $inputP['ty'], $inputP['tz'], $inputP['wx'], $inputP['wy'], $inputP['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputP['xHPprim'], $inputP['yHPprim'], $inputP['r'], $l, $LL, $inputP['lk']);
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
                    
                    $alfa1 = $res[$l]['alfa'];
                    $beta1 = $res[$l]['beta'];
                $korekta = 1;
                $step = $step_wzor;
                $l++;
                $LL = $LL + $step;
            }
            
            if (!($lp == 0 && $i>2)){    
                if($korekta == 1){
                    $xprim = $inputP['xHPprim'];
                    $yprim = $inputP['yHPprim'];
                    $korekta = 0;
                    $MD = $SumaLL + $inputP['lk'];
                    $res[$l] = $this->coordR3p($inputP['sx'], $inputP['sy'], $inputP['sz'], $inputP['tx'], $inputP['ty'], $inputP['tz'], $inputP['wx'], $inputP['wy'], $inputP['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputP['xHPprim'], $inputP['yHPprim'], $inputP['r'], $l, $LL, $inputP['lk']);
                    $res[$l]['section'] = "PP";
                    $res[$l]['MD'] = $MD;
                    $res[$l]['CL_DEP'] = 0;
                    $res[$l]['CL_Angle'] = 0;
                    $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
                    $step = $LL -  $inputP['lk'];
                    $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                    $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                    $alfa1 = $res[$l]['alfa'];
                    $beta1 = $res[$l]['beta'];
                    $l++;
                    
                    if ($step == 0){
                        $step = $step_wzor;
                        $LL = $LL + $step;
                    }
                   
                }
                
                    $xprim = $inputP['xHPprim'] + ($LL - $inputP['lk']) * sin($inputP['delta']);
                    $yprim = $inputP['yHPprim'] + ($LL - $inputP['lk']) * cos($inputP['delta']);
                    $MD = $SumaLL + $LL;
                    $res[$l] = $this->coordR3p($inputP['sx'], $inputP['sy'], $inputP['sz'], $inputP['tx'], $inputP['ty'], $inputP['tz'], $inputP['wx'], $inputP['wy'], $inputP['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputP['xHPprim'], $inputP['yHPprim'], $inputP['r'], $l, $LL, $inputP['lk']);
                    $res[$l]['section'] = $i;
                    $res[$l]['MD'] = $MD;
                    $res[$l]['CL_DEP'] = 0;
                    $res[$l]['CL_Angle'] = 0;
                    $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
                    $res[$l]['beta'] = rad2deg($res[$l]['beta']);
                    $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
                    $alfa1 = $res[$l]['alfa'];
                    $beta1 = $res[$l]['beta'];
                    $step = $step_wzor;
                    $l++;
                    $LL = $LL + $step;
            }
            
        }while($LL < $inputP['lc']);
            $xprim = $inputP['x2prim'];
            $yprim = $inputP['y2prim'];
            $MD = $SumaLL + $inputP['lc'];
            $res[$l] = $this->coordR3p($inputP['sx'], $inputP['sy'], $inputP['sz'], $inputP['tx'], $inputP['ty'], $inputP['tz'], $inputP['wx'], $inputP['wy'], $inputP['wz'], $x1, $y1, $z1, $xprim, $yprim, $inputP['xHPprim'], $inputP['yHPprim'], $inputP['r'], $l, $LL, $inputP['lk']);
            $res[$l]['section'] = "PP";
            $res[$l]['MD'] = $MD;
            $res[$l]['CL_DEP'] = 0;
            $res[$l]['CL_Angle'] = 0;
            $this->firstStreightCoord($res[$l],$MD, $Malfa[$i], $Mbeta[$i], $i);
            $res[$l]['alfa'] = rad2deg($res[$l]['alfa']);
            $res[$l]['beta'] = rad2deg($res[$l]['beta']);
            
            if ($i == 2) 
		{
               $alfa1 = $Malfa[$i];
               $beta1 = $Mbeta[$i];
               $res[$l]['alfa'] = $alfa1;    //poprawka 15.09.2015
               $res[$l]['beta'] = $beta1;    //pop.
            	}
            else {
                $alfa1 = $res[$l]['alfa'];
                $beta1 = $res[$l]['beta'];
            }
           $step = $LL - $inputP['lc'];
           
           if($step == 0){
               $step = $step_wzor;
           }
           $l++;
           $SumaLL = $SumaLL + $inputP['lc'];
           $LL = $step;
        }
        if ($vsAngle !== null){
           $angleParametr = $vsAngle;
        }
        else {
            $angleParametr = $this->calculateVsAngle($res[1], $res[count($res)]);
        }    
        $this->calculateVerticalSection($res, $angleParametr);
        return $res;
    }
}
