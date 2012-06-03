<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Sun, 25 Sep 2011 04:34:35 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Make/Edit Census Report by GM service</title>
	<style type="text/css">
	</style>
	<link rel="stylesheet" type="text/css" href="/medisys/calendar/calendar.css" />		
	<link rel="stylesheet" type="text/css" href="/medisys/css/main.css" />
	<script type="text/javascript" src="/medisys/calendar/calendar.js"></script>		
	<script type="text/javascript" src="/medisys/js/validate_form.js"></script>
	<script type="text/javascript" src="/medisys/js/jquery.js"></script>
	<script type="text/javascript" src="/medisys/js/my_jscripts.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
        prepList();
	    });


    </script>
    <!--[if IE]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

<?php
//authorize user with uname and pword
$auth_list = $this->Users_model->get_all_users();
$csess = get_cookie('ci_session');
authorize_user($auth_list, $csess);

//set user session variables
$my_service = $this->session->userdata('my_service');
$my_dispo = $this->session->userdata('my_dispo');
$one_gm = $this->session->userdata('one_gm');
$stp1 = $this->session->userdata('stp1');
//array list variables
$loc_list = $this->config->item('loc_list');
$type_list = $this->config->item('type_list');
$dispo_list = $this->config->item('dispo_list');
$service_list = $this->config->item('service_list');
$bed_list = $this->config->item('bed_list');
$mbed_list = $this->config->item('m_beds');

//assign session variables
$vars = array('my_service'=> $my_service, 'my_dispo'=>$my_dispo, 'one_gm'=>$one_gm, 'stp1'=>$stp1);

//greetings and logout
make_page_header($_SERVER['PHP_AUTH_USER']);

echo "<div align=\"center\"><h1>Admissions Census</h1></div>";
if (!strcmp($my_service, 'er'))
    echo "<h1><div align=\"center\">ER Admissions</div></h1>";
elseif (!strcmp($my_service, 'micu'))
	echo "<h1><div align=\"center\">MICU Admissions</div></h1>";
elseif (!strcmp($my_service, "preop"))
	echo "<h1><div align=\"center\">Pre-operative Admissions</div></h1>";

else
 	echo "<h1><div align=\"center\">Gen Med Svc ".$my_service." Admissions</div></h1>";

$numrows = count($c_admissions);
if (in_array($my_service, array(1, 2, 3, 4, 5, 6)))
{
	$num_er = count($c_er);
	$num_micu = count($c_micu);
	$num_pre = $this->Admission_model->get_num_preop($my_service); 
	$num_prim = $this->Admission_model->get_num_primary($my_service);
	$num_comx = $this->Admission_model->get_num_comx($my_service);
}
else
{
	$num_er = 0;
	$num_micu = 0;
	$num_pre = 0; 
	$num_prim = 0;
	$num_comx = 0;
}
//make census button (for ward and micu only)
if (in_array($my_service, array(1, 2, 3, 4, 5, 6, 'micu')))
{
	echo form_open('census/print_gmcensus');
	$label = array('name'=>"", 'value'=>"Make My Gen Med Census!", 'class'=>"menubb");
	make_buttons($my_service, $label, $vars, "center", "");
}

//make add new admission button
echo form_open('menu');
$label = array('name'=>"main_add_a", 'value'=>"Add New Admission", 'class'=>"menubb");
make_buttons($my_service, $label, $vars, "center", "");
echo form_close();


echo "<div align=\"center\"><b>(as of ".date("M-d-Y").")</b></div>";

//list of admissions
//main display area
echo "<div id=\"main_display\">";
//Primary tab (Wards, etc tab)
echo "<div class = \"clickable\" id=\"ward_banner";
if (!(in_array($my_service, array('micu', 'er', 'preop'))))
    echo "1";
else
    echo "2";    
echo "\" onclick = \"showWards()\">";
if (!strcmp($my_service, 'er'))
    echo "ER (".$numrows.")";
elseif (!strcmp($my_service, 'micu'))
    echo "MICU (".$numrows.")";
elseif (!strcmp($my_service, 'preop'))
    echo "Pre-op (".$numrows.")";    
else
    echo "Wards (Primary:".$num_prim." and Co-Mgt: ".$num_comx.")";
echo "</div>";
echo "<div id=\"ward_body\">";
if ($c_admissions){

    echo "<h2> Click on an admission row to view and/or edit.</h2>";
	echo "<div align = \"center\" class = \"summary_table\" id = \"editable\"><table><tr><th>No.</th>";
	if (strcmp($my_service, 'er'))
	    	echo "<th>Location</th>";
	if (strcmp($my_service, 'er') && strcmp($my_service, 'micu'))
    		echo "<th>Type</th>";
	echo "<th>Patient Name</th><th>RIC</th>";
	if (strcmp($my_service, 'er') && strcmp($my_service, "preop"))
    		echo "<th>SIC</th>";
	if (!strcmp($my_service, 'er') || !strcmp($my_service, 'micu'))
    		echo "<th>GM Service</th>";
	echo "<th>Hosp Days</th>";
//	echo "<th>Select</th></tr>";
	
    if (strcmp($my_service, 'micu') && strcmp($my_service, 'er')){
		$x =1;
		
        	foreach ($c_admissions as $row){
		    $eadmission = $row->a_id;
			if (!strcmp($my_service, "preop")){
				echo "<tr onclick = \"getAdm('".$eadmission."', '".$my_service."', '".$my_dispo."', '".$one_gm."', '".$stp1."')\">";
				$pdata = $this->Patient_model->get_one_patient($row->p_id);
				foreach ($pdata as $patient)
					make_num_column(0, $x, revert_form_input($patient->cnum));
				make_location_column($row->location, $row->bed);
				make_type_column($row->type);
	      			foreach ($pdata as $patient)
	      				make_patient_column(revert_form_input($patient->p_name));
    				$jr = $this->Resident_model->get_resident_name($row->r_id);	
				foreach ($jr as $rdata)
					make_ric_column((revert_form_input($rdata->r_name)));
    				$hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
				    make_hd_column($hd);
					echo "</tr>";
                    echo "<tr><td colspan=\"7\" id=\"selected_adm".$x."\"></td></tr>";

    				$x++;
			}
			else{
				if (!strcmp($row->type, 'Primary') || !strcmp($row->type, 'Co-Managed')){
				echo "<tr onclick = \"getAdm('".$eadmission."', '".$my_service."', '".$my_dispo."', '".$one_gm."', '".$stp1."')\">";
					$pdata = $this->Patient_model->get_one_patient($row->p_id);
					foreach ($pdata as $patient)
						make_num_column(0, $x, revert_form_input($patient->cnum));
					make_location_column($row->location, $row->bed);
					make_type_column($row->type);
	      				foreach ($pdata as $patient)
	      					make_patient_column(revert_form_input($patient->p_name));
    					$jr = $this->Resident_model->get_resident_name($row->r_id);	
					foreach ($jr as $rdata)
						make_ric_column(revert_form_input($rdata->r_name));
    					make_sic_column(revert_form_input($row->sic));
    					$hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
					make_hd_column($hd);
					echo "</tr>";
  					$x++;
			    }
            }
		  }
		  echo "</table>";
    }
    else{
    
		$x =1;
		foreach ($c_admissions as $row){
		    if (!strcmp($my_service, 'er'))
		        $eadmission = $row->er_id;
		    else
		        $eadmission = $row->micu_id;
			echo "<tr onclick = \"getAdm('".$eadmission."', '".$my_service."', '".$my_dispo."', '".$one_gm."', '".$stp1."')\">";

			$pdata = $this->Patient_model->get_one_patient($row->p_id);
	      		foreach ($pdata as $patient)
				make_num_column(0, $x, revert_form_input($patient->cnum));
			if (!strcmp($my_service, 'micu'))
				make_micubed_column($row->bed);
  			foreach ($pdata as $patient)
	      			make_patient_column(revert_form_input($patient->p_name));
		        if (!strcmp($my_service, 'micu')) 
    				$jr = $this->Resident_model->get_resident_name($row->r_id);

			else
				$jr = $this->Resident_model->get_resident_name($row->pod_id);	
			foreach ($jr as $rdata)
				make_ric_column(revert_form_input($rdata->r_name));
    			if (!strcmp($my_service, 'micu'))
         			make_sic_column(revert_form_input($row->sic));
    			make_gm_column($row->service);
    			$hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
    			make_hd_column($hd);
				echo "</tr>";
    			$x++;
		 } 			
		 echo "</table>";
		 echo "<br/>";
	   }
}	   
else
    echo "<h1>No service admissions!</h1>";
       echo "</div>"; 
echo "</div>";
//end of Ward tab
if (!(in_array($my_service, array('er', 'micu', 'preop')))){
//Micu Tab
echo "<div id=\"micu_banner\" class = \"clickable\" onclick = \"showMicu()\"> MICU (".$num_micu.")</div>";
echo "<div id=\"micu_body\">";
    if ($c_micu){
        echo "<h1>".$num_micu." MICU admissions</h1>";	
		echo "<div align = \"center\" class =\"summary_table\"><table><tr><th>No.</th>";
		echo "<th>Location</th>";
		echo "<th>Patient Name</th><th>RIC</th><th>SIC</th>";
		echo "<th>GM Svc</th>";
		echo "<th>Hosp Days</th></tr>";	
		$x =1;
		foreach ($c_micu as $row){
			echo "<tr>";
			$pdata = $this->Patient_model->get_one_patient($row->p_id);
			make_num_column(2, $x, "");
			make_micubed_column($row->bed);
	      	foreach ($pdata as $patient)
	      			make_patient_column(revert_form_input($patient->p_name));
    		$jr = $this->Resident_model->get_resident_name($row->r_id);	
			foreach ($jr as $rdata)
			make_ric_column(revert_form_input($rdata->r_name));

  			make_sic_column(revert_form_input($row->sic));
    			make_gm_column($row->service);
    			$hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
    			make_hd_column($hd);
			echo "</tr>";
    			$x++;
		}
		echo "</table>";
		echo "</div>";
	}
	else
	    echo "<h1>No MICU admissions!</h1>";	
echo "</div>";
//end of Micu tab

//ER tab
echo "<div id=\"er_banner\" class = \"clickable\" onclick = \"showEr()\"> ER (".$num_er.")</div>";
echo "<div id=\"er_body\">";
    if ($c_er){
        echo "<h1>".$num_er." ER admissions</h1>";	
		echo "<div align = \"center\" class =\"summary_table\"><table><tr><th><a name=\"UP\">No.</a></th>";
		echo "<th>Patient Name</th><th>ER-POD</th>";
		echo "<th>GM Svc</th>";
		echo "<th>Hosp Days</th></tr>";
		$x =1;
		foreach ($c_er as $row){
			echo "<tr>";
			make_num_column(2, $x, "");
			$pdata = $this->Patient_model->get_one_patient($row->p_id);
	      		foreach ($pdata as $patient)
	      			make_patient_column(revert_form_input($patient->p_name));
    		$jr = $this->Resident_model->get_resident_name($row->pod_id);	
			foreach ($jr as $rdata)
			make_ric_column(revert_form_input($rdata->r_name));
  			make_gm_column(revert_form_input($row->service));
    			$hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
    			make_hd_column($hd);
			echo "</tr>";
    			$x++;
    		}
		echo "</table>";
		echo "</div>";
	}
	else
	    echo "<h1>No ER admissions!</h1>";	
echo "</div>";
}



//selected admission tab
echo "<div class = \"clickable\" id=\"selected_banner";
if (!(in_array($my_service, array('micu', 'er', 'preop'))))
    echo "1";
else
    echo "2";    
echo "\" onclick = \"showSelected()\"> Admission for Edit </div>";
echo "<div id=\"selected_body\"></div>";
echo "</div>";


  	    
  ?>
  </table>
  </body>
</html>
