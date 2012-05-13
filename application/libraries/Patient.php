<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Patient {
    public $name;
	public $bday;
	public $sex;
	public $cnum;
	public $address;

    public function count_p_age($pbd)
    {
	 $today = date("d-m-y");
	 list($ty, $tm, $td) = explode("-",$today);
	 list($py, $pm, $pd) = explode("-",$pbd);
	 $YearDiff = $ay - $py;
     $MonthDiff = $am - $pm;
     $DayDiff = $ad - $pd;
     if ($DayDiff < 0 || $MonthDiff < 0)
            $YearDiff--;
     $age = $YearDiff;	
    }
}