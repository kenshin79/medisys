<?php
//Computation functions

function compute_age_adm($din, $pbd)
    {
	 	    list($ay, $am, $ad) = explode("-",$din);
			list($py, $pm, $pd) = explode("-",$pbd);
			$YearDiff = $ay - $py;
            $MonthDiff = $am - $pm;
            $DayDiff = $ad - $pd;
            if ($DayDiff < 0 || $MonthDiff < 0)
              $YearDiff--;
			return $YearDiff;  
    }
function compute_hd($dis, $din, $dout)
	{
	 	    if (!strcmp($dis, "Admitted")){
		    $hd = 1 + round((strtotime(date('Y-m-d', time())) - strtotime(date($din)))/(60*60*24)) ;
		    }
			else {
			$hd = 1 + round((strtotime(date($dout)) - strtotime(date($din)))/(60*60*24)) ;
			} 
			return $hd;
	}	
//end of computation functions	

//Census headers
function make_page_header($uname){
	//greeting user and logout link
	echo "<div>";
	echo "<div align=\"left\"><b>Welcome User: ".$uname."!</b></div>";
	echo "<div align=\"right\">".form_open('loguser/log_out')."<b>".form_submit('','Log-out Medisys','').form_close()."</b></div>";
	echo "</div>";
	echo "<a href=\"/medisys\">Main Menu</a>";

}
function make_buttons($serv, $label, $vars, $align, $js){
	if ($vars)
		make_hidden_vars($vars);
	echo "<div align=\"".$align."\">".form_submit($label,'',$js)."</div>";
	echo form_close();
}

function make_hidden_vars($vars){
	foreach($vars as $k=>$value)
		echo form_hidden($k, $value);
}
function make_census_header_buttons($serv, $disp, $one, $numrows, $num_er, $num_micu, $num_pre, $num_prim, $num_comx){
	$mc = array('value'=>'Make Gen Med Census!', 'class'=>'menubb');
        $aa = array('name'=>'main_add_a', 'value'=>'Add New Admission', 'class'=>'menubb'); 
	if (strcmp($serv, 'er'))
	{
     		echo form_open('census/print_gmcensus');
     		echo form_hidden('service', $serv);
     		echo form_hidden('dispo', $disp);
     		echo form_hidden('one', $one);
     		echo "<div align=\"center\">".form_submit($mc)."</div>";
     		echo form_close();
	}

	echo form_open('menu');
	echo form_hidden('service', $serv);
	echo form_hidden('dispo', $disp).form_hidden('one', $one);
	echo form_hidden('stp1', 'stp1');
	echo "<div align=\"center\">".form_submit($aa)."</div>";
	echo form_close();
	
	
}
//end of census headers

//Census summary list table maker
//for num_column, 2 is for ward-micu, 1 is for preop, 0 is for the ward-in
function make_num_column($preop, $x, $cnum){
	if ($preop == 1)
		echo "<tr><td><a href=\"#p".$cnum."\">".$x."</a></td>"; 
	elseif ($preop == 0)
		echo "<td><a href=\"#p".$x."\">".$x."</a></td>"; 
	else
		echo "<tr><td>".$x."</td>";
}
function make_location_column($location, $bed){
	echo "<td>".$location." Bed ".$bed."</td>";
}
function make_type_column($type){
	echo "<td>".$type."</td>";
}
function make_patient_column($pname){
	echo "<td class = \"allCaps\">".$pname."</td>";
}
function make_ric_column($rname){
	echo "<td class = \"unemphasis\">".$rname."</td>";
}
function make_sic_column($sic){
	echo "</td><td class = \"unemphasis\">".$sic."</td>";
}
function make_gm_column($serv){
	echo "</td><td class = \"unemphasis\">Gen Med ".$serv."</td>";
}
function make_hd_column($hd){
	echo "<td>".$hd."</td>";
}
function make_micubed_column($bed){
	echo "<td>MICU Bed ".$bed."</td>";
}


//Clean and revert data inputs
function clean_form_input($form_data){
	$temp = str_ireplace(",","@", $form_data);
	$temp = str_ireplace("\"","|", $temp); 
	$temp = str_ireplace("<", "$", $temp);
	$temp = str_ireplace(">", "?", $temp);
        return $temp;
}
function revert_form_input($form_data){
	$temp = str_ireplace("@",",", $form_data);
	$temp = str_ireplace("|","\"", $temp); 
	$temp = str_ireplace("$", "<", $temp);
	$temp = str_ireplace("?", ">", $temp);
	return $temp;
}
//end of Clean and revert data inputs


