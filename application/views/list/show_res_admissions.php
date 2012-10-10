<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Sun, 25 Sep 2011 04:34:35 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Resident Admissions Lists</title>
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
        prepresList();
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
$eresident = $this->session->userdata('eresident');
//array list variables
$loc_list = $this->config->item('loc_list');
$type_list = $this->config->item('type_list');
$dispo_list = $this->config->item('dispo_list');
$service_list = $this->config->item('service_list');
$bed_list = $this->config->item('bed_list');
$mbed_list = $this->config->item('m_beds');


//count variables
$numWards = count($w_admissions);
$numMicu = count($m_admissions);
$numER = count($e_admissions);
$numComx = count($cm_admissions);
$numPreop = count($p_admissions);
//assign session variables
$vars = array('my_service'=> $my_service, 'my_dispo'=>$my_dispo, 'one_gm'=>$one_gm, 'stp1'=>$stp1);

//greetings and logout
make_page_header($_SERVER['PHP_AUTH_USER']);
$rn = $this->Resident_model->get_resident_name($eresident);
echo "<div align=\"center\"><h1>";
foreach ($rn as $r)
	echo revert_form_input($r->r_name);
echo " - Census by Service Area</h1></div>";
echo "<div align=\"center\"><b>(as of ".date("M-d-Y").")</b></div>";

//list of admissions
//main display area
echo "<div id=\"main_resdisplay\">";
//(Wards)
echo "<div class = \"clickable\" id=\"res_wards\" onclick = \"showresWards()\">";
echo "Wards (".$numWards.")";
echo "</div>";
//(ER)
echo "<div class = \"clickable\" id=\"res_er\" onclick = \"showresER()\">";
echo "ER (".$numER.")";
echo "</div>";
//(MICU)
echo "<div class = \"clickable\" id=\"res_micu\" onclick = \"showresMicu()\">";
echo "MICU (".$numMicu.")";
echo "</div>";
//(COMX)
echo "<div class = \"clickable\" id=\"res_comx\" onclick = \"showresComx()\">";
echo "Co-Managed (".$numComx.")";
echo "</div>";
//(PREOP)
echo "<div class = \"clickable\" id=\"res_preop\" onclick = \"showresPreop()\">";
echo "Pre-OP (".$numPreop.")";
echo "</div>";
//(EDIT)
echo "<div class = \"clickable\" id=\"res_edit\" onclick = \"showresEdit()\">";
echo "EDIT";
echo "</div>";

echo "<div id=\"ward_body\">";
	echo "<div align = \"center\" class = \"census_table\" id = \"editable\">";
	echo "<table><tr><th>No.</th>";
	echo "<th>Admission Date</th><th>Location - Bed</th><th>Patient Name</th><th>Problem List</th><th>PCP-ICD</th><th>Dispo / Dispo Date</th></tr>";

	$x = 1;
	foreach ($w_admissions as $row){
		$hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
		$pdata = $this->Patient_model->get_one_patient($row->p_id);
		echo "<tr onclick = \"getresAdm('".$row->a_id."', 'All', '".$my_dispo."', '".$one_gm."', '".$stp1."')\"><td>".$x."</td><td>".$row->date_in;
		echo "  (HD: ".$hd." days)";
		echo "</td><td>".$row->location." Bed ".$row->bed."</td><td>";
		foreach ($pdata as $p){
			$page = compute_age_adm($row->date_in, $p->p_bday);
			echo revert_form_input($p->p_name)." ".$page."/".$p->p_sex;			
		}
		echo "<td><textarea cols=40 rows=5 readonly=readonly>".revert_form_input($row->plist)."</textarea></td><td><textarea cols=15 rows=5>".revert_form_input($row->pcpdx)."</textarea></td><td>".$row->dispo;
		if (strcmp($row->dispo, "Admitted"))
			echo " ".$row->date_out; 
		echo "</td></tr>";
		$x++;
	}
	echo "</table>";
	echo "</div>";	
echo "</div>";

echo "<div id=\"er_body\">";
	echo "<div align = \"center\" class = \"census_table\" id = \"editable\">";
	echo "<table><tr><th>No.</th><th>Admission Date</th>";
	echo "<th>Patient Name</th><th>Problem List</th><th>PCP-ICD</th><th>Dispo / Dispo Date</th></tr>";
	$x = 1;
	foreach ($e_admissions as $row){
		$hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
		$pdata = $this->Patient_model->get_one_patient($row->p_id);
		echo "<tr onclick = \"getresAdm('".$row->er_id."', 'er', '".$my_dispo."', '".$one_gm."', '".$stp1."')\"><td>".$x."</td><td>".$row->date_in;
		echo "  (HD: ".$hd." days)</td><td>";
		foreach ($pdata as $p){
			$page = compute_age_adm($row->date_in, $p->p_bday);
			echo revert_form_input($p->p_name)." ".$page."/".$p->p_sex;			
		}
		echo "<td><textarea cols=40 rows=5 readonly=readonly>".revert_form_input($row->plist)."</textarea></td><td><textarea cols=15 rows=5>".revert_form_input($row->pcpdx)."</textarea></td><td>".$row->dispo;
		if (strcmp($row->dispo, "Admitted"))
			echo " ".$row->date_out; 
		echo "</td></tr>";
		$x++;
	}
	echo "</table>";
	echo "</div>";	

echo "</div>";

echo "<div id=\"micu_body\">";
	echo "<div align = \"center\" class = \"census_table\" id = \"editable\">";
	echo "<table><tr><th>No.</th><th>Admission Date</th>";
	echo "<th>Location - Bed</th><th>Patient Name</th><th>Problem List</th><th>PCP-ICD</th><th>Dispo / Dispo Date</th></tr>";
	$x = 1;
	foreach ($m_admissions as $row){
		$hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
		$pdata = $this->Patient_model->get_one_patient($row->p_id);
		echo "<tr onclick = \"getresAdm('".$row->micu_id."', 'micu', '".$my_dispo."', '".$one_gm."', '".$stp1."')\"><td>".$x."</td><td>".$row->date_in;
		echo "  (HD: ".$hd." days)";
		echo "</td><td>MICU Bed ".$row->bed."</td><td>";
		foreach ($pdata as $p){
			$page = compute_age_adm($row->date_in, $p->p_bday);
			echo revert_form_input($p->p_name)." ".$page."/".$p->p_sex;			
		}
		echo "<td><textarea cols=40 rows=5 readonly=readonly>".revert_form_input($row->plist)."</textarea></td><td><textarea cols=15 rows=5>".revert_form_input($row->pcpdx)."</textarea></td><td>".$row->dispo;
		if (strcmp($row->dispo, "Admitted"))
			echo " ".$row->date_out; 
		echo "</td></tr>";
		$x++;
	}


	echo "</table>";
	echo "</div>";	

echo "</div>";

echo "<div id=\"preop\">";
	echo "<div align = \"center\" class = \"census_table\" id = \"editable\">";
	echo "<table><tr><th>No.</th>";
	echo "<th>Admission Date</th><th>Location - Bed</th><th>Patient Name</th><th>Problem List</th><th>PCP-ICD</th><th>Dispo / Dispo Date</th></tr>";

	$x = 1;
	foreach ($p_admissions as $row){
		$hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
		$pdata = $this->Patient_model->get_one_patient($row->p_id);
		echo "<tr onclick = \"getresAdm('".$row->a_id."', 'preop', '".$my_dispo."', '".$one_gm."', '".$stp1."')\"><td>".$x."</td><td>".$row->date_in;
		echo "  (HD: ".$hd." days)";
		echo "</td><td>".$row->location." Bed ".$row->bed."</td><td>";
		foreach ($pdata as $p){
			$page = compute_age_adm($row->date_in, $p->p_bday);
			echo revert_form_input($p->p_name)." ".$page."/".$p->p_sex;			
		}
		echo "<td><textarea cols=40 rows=5 readonly=readonly>".revert_form_input($row->plist)."</textarea></td><td><textarea cols=15 rows=5>".revert_form_input($row->pcpdx)."</textarea></td><td>".$row->dispo;
		if (strcmp($row->dispo, "Admitted"))
			echo " ".$row->date_out; 
		echo "</td></tr>";
		$x++;
	}
	echo "</table>";
echo "</div>";
echo "</div>";

echo "<div id=\"comx\">";
	echo "<div align = \"center\" class = \"census_table\" id = \"editable\">";
	echo "<table><tr><th>No.</th>";
	echo "<th>Admission Date</th><th>Location - Bed</th><th>Patient Name</th><th>Problem List</th><th>PCP-ICD</th><th>Dispo / Dispo Date</th></tr>";

	$x = 1;
	foreach ($cm_admissions as $row){
		$hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
		$pdata = $this->Patient_model->get_one_patient($row->p_id);
		echo "<tr onclick = \"getresAdm('".$row->a_id."', 'comx', '".$my_dispo."', '".$one_gm."', '".$stp1."')\"><td>".$x."</td><td>".$row->date_in;
		echo "  (HD: ".$hd." days)";
		echo "</td><td>".$row->location." Bed ".$row->bed."</td><td>";
		foreach ($pdata as $p){
			$page = compute_age_adm($row->date_in, $p->p_bday);
			echo revert_form_input($p->p_name)." ".$page."/".$p->p_sex;			
		}
		echo "<td><textarea cols=40 rows=5 readonly=readonly>".revert_form_input($row->plist)."</textarea></td><td><textarea cols=15 rows=5>".revert_form_input($row->pcpdx)."</textarea></td><td>".$row->dispo;
		if (strcmp($row->dispo, "Admitted"))
			echo " ".$row->date_out; 
		echo "</td></tr>";
		$x++;
	}
	echo "</table>";
echo "</div>";
echo "</div>";
echo "<div id=\"selected_body\">";
	echo "<div align = \"center\" class = \"census_table\" id = \"editable\">";
	echo "<table><tr><th>No.</th>";
	echo "<th>Location</th><th>Bed</th><th>Patient Name</th><th>Problem List</th><th>PCP-ICD</th></tr>";
	echo "</table>";
	echo "</div>";	

echo "</div>";

echo "</div>";