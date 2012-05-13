<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Mon, 24 Oct 2011 03:14:15 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Lab Flow Sheet #2</title>
    <style type="text/css">
    input{font-size:70%}
    	
	
    </style>
    <link rel="stylesheet" type="text/css" href="/medisys/css/census.css" />
    <!--[if IE]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>
<?php
//authorize user with uname and pword
$auth_list = $this->Users_model->get_all_users();
$csess = get_cookie('ci_session');
authorize_user($auth_list, $csess);
//greet user
//make_page_header($_SERVER['PHP_AUTH_USER']);
//get user session variables
  $my_service = $this->session->userdata('my_service');
  $my_dispo = $this->session->userdata('my_dispo');
  $one_gm = $this->session->userdata('one_gm');
  $stp1 = $this->session->userdata('stp1');
  $vars = array( 
	 	    	    'my_service'=>$my_service,
	    	        'my_dispo'=>$my_dispo,
					'one_gm'=>$one_gm,
					'stp1'=>$stp1,
				 );
//if from manage resident
if (!strcmp($one_gm, "res")){
    $eresident = $this->session->userdata('eresident');
    $rname = $this->session->userdata('rname');
    $vars['eresident'] = $eresident;
    $vars['rname'] = $rname;
}

//if from manage patient
if (!strcmp($one_gm, "px")){
    $epatient = $this->session->userdata('epatient');
    $pname = $this->session->userdata('pname');
    $vars['epatient'] = $epatient;
    $vars['pname'] = $pname;
}

if (!strcmp($one_gm, 'y')){ 	
    echo form_open('census/one_gm_census');
    $label = array('value'=>"******");
    make_buttons($my_service, $label, $vars, "left", "");
    echo form_close();
}
elseif (!strcmp($one_gm, "px")){
    if (!strcmp($my_service, 'er'))
        echo form_open('census/get_patient_eradmissions');
    elseif (!strcmp($my_service, 'micu'))
        echo form_open('census/get_patient_micuadmissions');
    else
        echo form_open('census/get_patient_admissions');
    $label = array('value'=>"Back to Patients");
    make_buttons($my_service, $label, $vars, "left", "");
    echo form_close();
}
elseif (!strcmp($one_gm, 'res')){
    echo form_open('census/resident_census');
    $label = array('value'=>"Back to Residents");
    make_buttons($my_service, $label, $vars, "left", "");
    echo form_close();
}


echo "<div align=\"center\"><h3><a name=\"top\">Laboratory Flow Sheet No. 2</a></h3>";
foreach ($p_admission as $row){

    $pdata = $this->Patient_model->get_one_patient($row->p_id);
	foreach ($pdata as $patient){    
        $age = compute_age_adm(revert_form_input($row->date_in), revert_form_input($patient->p_bday));
        $pname = revert_form_input($patient->p_name);
        $psex = revert_form_input($patient->p_sex);
        $cnum = revert_form_input($patient->cnum);
    }    
    echo "<table><tr><th>Patient:</th><td>".$pname." ".$age."/".$psex."</td><th>Service: Gen Med Svc: </th><td>".$my_service."</td></tr>";
    echo "<tr><th>Date of Admission:</th><td>".$row->date_in."</td><th>Service senior: </th><td>";
    if (strcmp($my_service, 'er')){
	    $sr = $this->Resident_model->get_resident_name($row->sr_id); 	
	    foreach ($sr as $name)
             echo revert_form_input($name->r_name);
	
    }
    echo " M.D.</td></tr>";
    echo "<tr><th>Location:</th><td>";
    if (strcmp($my_service, 'er') && strcmp($my_service,'micu'))
        echo $row->location." Bed".$row->bed;
    elseif (!strcmp($my_service, 'micu'))
        echo "MICU Bed ".$row->bed;
    else
        echo "ER";
    echo "</td><th>RIC:</th><td>";
    if (!strcmp($my_service, 'er'))
        $jr = $this->Resident_model->get_resident_name($row->pod_id); 
    else    
	    $jr = $this->Resident_model->get_resident_name($row->r_id); 	
	foreach ($jr as $name)
        echo revert_form_input($name->r_name);
	
    echo " M.D.</td></tr>";
    echo "<tr><th>Case Number:</th><td>".$cnum."</td><th>SIC:</th><td>";
    if (strcmp($my_service, 'er'))
        echo revert_form_input($row->sic);
    echo "</td></tr></table>";
    echo "</div>"; 
    echo "<div>";
    echo "<div style=\"\">";
//Menu links
    echo "<div align=\"center\">";
    echo "<table><tr><th>Available Labs</th><th><a href=\"#cbc\">CBC</a></th><th><a href=\"#bloodchem\">Blood Chemistry</a></th><th><a href=\"#protime\">Protime/aPTT</a></th><th><a href=\"#urine\">Urinalysis</a></th><th><a href=\"#abg\">ABG</a></th><th><a href=\"#fecalysis\">Fecalysis</a></th><th><a href=\"#uchem\">24H Urine Chemistry</a></th><th><a href=\"#culture\">Cultures</a></th><th><a href=\"#imaging\">Imaging</a></th><th><a href=\"#ecg\">ECG/EEG</a></th><th><a href=\"#others\">Other Labs</a></th></tr></table>";
    echo "</div>";
//CBC table
if (!strcmp($one_gm, "y"))  
    echo form_open('show/update_cbc2');
    if (!strcmp($my_service, 'micu'))
        $vars['eadmission'] = $row->micu_id;
    elseif (!strcmp($my_service, 'er'))
        $vars['eadmission'] = $row->er_id;
    else
        $vars['eadmission'] = $row->a_id;
    $cbc_part = array(
		        '0'=>'Date', 
		        '1'=>'Time', 
		        '2'=>'WBC', 
		        '3'=>'RBC', 
		        '4'=>'Hgb', 
		        '5'=>'Hct', 
		        '6'=>'MCV', 
		        '7'=>'MCH', 
		        '8'=>'MCHC', 
		        '9'=>'RDWCV',
 		        '10'=>'Platelets',
		        '11'=>'Neut',
		        '12'=>'Lymph',
		        '13'=>'Mono',
		        '14'=>'Eo',	
		        '15'=>'Baso',
		        '16'=>'Pro_Mye_Jv',
		        '17'=>'Stabs',
		        '18'=>'Blasts',
		        '19'=>'NRBC',
		
		);
    echo "<a href=\"#top\">Back to TOP</a>";
    echo "<table border=1><tr>";
    echo "<td>";
//labels
    echo "<table><tr><th><a name=\"cbc\">CBC</a></th></tr>";
    if ($row->cbc2)
         $ecbc = explode(",", $row->cbc2); 
    foreach ($cbc_part as $part)
        echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"12\" value=\"".$part."\"/></td></tr>";
    echo "</table></td><td>";
//N.V.
    echo "<table><tr><th>(N.V.)</th></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(4-11x109/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(4-6x109/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(110-180g/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0.370-0.540%)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(80-100fL)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(27-31pg)\" /></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(310-360g/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(150-450x109/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(2-4x1011/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0.5-0.7)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0.2-0.5)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0.02-0.09)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0.0-0.06)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0.0-0.02)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0.02-0.04)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"--\"/></td></tr>";
    echo "</table></td>";

//input columns
    $y = 0;
    for($x=1; $x<=15; $x++){
        echo "<td>";
        echo "<table><tr><th>".$x."</th></tr>";

        foreach ($cbc_part as $part){
//format: cbc_part# ie cbc_wbc1, cbc_neut1...cbc_wbc2, cbc_neut2
            echo "<tr><td><input type=\"text\" name=\"".$part.$x."\" value=\"";
            if ($row->cbc2)
                echo revert_form_input($ecbc[$y]);
            echo "\" size=\"5\" /></td></tr>";
            $y++;
        }
        echo "</table></td>";
    }
    echo "</tr></table>";
    if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"Update CBC");
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
    }
//end of CBC table

//Blood Chemistry
if (!strcmp($one_gm, "y"))
    echo form_open('show/update_bloodchem2');
    $bloodchem_part = array(
		        '0'=>'Date', 
		        '1'=>'Time', 
		        '2'=>'Glucose', 
		        '3'=>'BUN', 
		        '4'=>'Creatinine', 
		        '5'=>'Sodium', 
		        '6'=>'Potassium', 
		        '7'=>'Chloride', 
		        '8'=>'Calcium', 
		        '9'=>'Magnesium',
 		        '10'=>'Phosphorus',
		        '11'=>'Total_Protein',
		        '12'=>'Albumin',
		        '13'=>'Globulin',
		        '14'=>'AST_SGOT',	
		        '15'=>'ALT_SGPT',
		        '16'=>'Alk_Phos',
		        '17'=>'Total_Bilirubin',
		        '18'=>'Direct_Bilirubin',
		        '19'=>'Indirect_Bilirubin',
                '20'=>'HDL',
		        '21'=>'LDL',
		        '22'=>'Cholesterol',
		        '23'=>'Triglycerides',
		        '24'=>'Uric_Acid',	
		        '25'=>'Amylase',
                '26'=>'Lipase', 
		        '27'=>'CK_Total',
		        '28'=>'CK_MB',
		        '29'=>'CK_MM',
		        '30'=>'Trop_I',
                '31'=>'Myoglobin',
				
		);
    echo "<a href=\"#top\">Back to TOP</a>";
    echo "<table border=1><tr>";
    echo "<td>";
//labels
    echo "<table><tr><th><a name=\"bloodchem\">Blood Chem</a></th></tr>";
    if ($row->bloodchem2)
        $ebloodchem = explode(",", $row->bloodchem2); 
    foreach ($bloodchem_part as $part)
        echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"12\" value=\"".$part."\"/></td></tr>";
    echo "</table></td><td>";
//N.V.
    echo "<table><tr><th>(N.V.)</th></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(3.9-6.1 mmol/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(2.6-6.4 mmol/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(53-115 umol/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(140-148 mmol/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(3.6-5.2 mmol/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(100-108 mmol/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(2.12-2.62 mmol/L)\" /></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0.74-1.0 mmol/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0.8-1.97 mmol/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(64-83 g/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(34-50 g/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(15-35 U/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(15-37 U/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(36-65 U/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(50-136 U/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0-17.1 umol/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0-3.42 umol/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(3.4-13.7 umol/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(30-60 mg/dL)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(100-190 mg/dL)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(4.2-5.2 mmol/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(<180 mg/dL)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0.13-0.44 mmol/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(1-63 U/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(30-210 U/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(21-232 U/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0-6 U/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(6-97 U/L)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0-0.4 ng/mL)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(0-85 ng/mL)\"/></td></tr>";
    echo "</table></td>";

//input columns
    $y = 0;
    for($x=1; $x<=15; $x++){
        echo "<td>";
        echo "<table><tr><th>".$x."</th></tr>";
        foreach ($bloodchem_part as $part){
//format: cbc_part# ie cbc_wbc1, cbc_neut1...cbc_wbc2, cbc_neut2
            echo "<tr><td><input type=\"text\" name=\"".$part.$x."\" value=\"";
            if ($row->bloodchem2)
                echo revert_form_input($ebloodchem[$y]);
            echo "\" size=\"5\" /></td></tr>";
            $y++;
        }
        echo "</table></td>";
    }
    echo "</tr></table>";
    if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"Update Blood Chemistry");
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
    }    
//end of Blood chemistry table

//Protime/PTT
if (!strcmp($one_gm, "y"))
    echo form_open('show/update_protime2');
    if (!strcmp($my_service, 'micu'))
        $vars['eadmission'] = $row->micu_id;
    elseif (!strcmp($my_service, 'er'))
        $vars['eadmission'] = $row->er_id;
    else
        $vars['eadmission'] = $row->a_id;
    $protime_part = array(
		'0'=>'Date', 
		'1'=>'Time', 
		'2'=>'Protime_Ctrl', 
		'3'=>'Protime_Patient', 
		'4'=>'Protime_Activity', 
		'5'=>'Protime_INR', 
		'6'=>'aPTT_Ctrl', 
		'7'=>'aPTT_Patient', 
		
		);
    echo "<a href=\"#top\">Back to TOP</a>";
    echo "<table border=1><tr>";
    echo "<td>";
//labels
    echo "<table><tr><th><a name=\"protime\">Protime/aPTT</a></th></tr>";
    if ($row->protime2)
        $eprotime = explode(",", $row->protime2); 
    foreach ($protime_part as $part)
        echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"12\" value=\"".$part."\"/></td></tr>";
    echo "</table></td><td>";
//N.V.
    echo "<table><tr><th>(N.V.)</th></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(12-15 secs)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(1.0)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\" /></td></tr>";
    echo "</table></td>";

//input columns
    $y = 0;
    for($x=1; $x<=15; $x++){
        echo "<td>";
        echo "<table><tr><th>".$x."</th></tr>";
        foreach ($protime_part as $part){
//format: cbc_part# ie cbc_wbc1, cbc_neut1...cbc_wbc2, cbc_neut2
            echo "<tr><td><input type=\"text\" name=\"".$part.$x."\" value=\"";
            if ($row->protime2)
                echo revert_form_input($eprotime[$y]);
            echo "\" size=\"5\" /></td></tr>";
            $y++;
        }
        echo "</table></td>";
    }
    echo "</tr></table>";
    if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"Update Protime");
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
    }    
//end of Protime/PTT


//Urinalysis table
if (!strcmp($one_gm, "y"))
    echo form_open('show/update_urine2');
    if (!strcmp($my_service, 'micu'))
        $vars['eadmission'] = $row->micu_id;
    elseif (!strcmp($my_service, 'er'))
        $vars['eadmission'] = $row->er_id;
    else
        $vars['eadmission'] = $row->a_id;
    $urine_part = array(
		'0'=>'Date', 
		'1'=>'Time', 
		'2'=>'Color', 
		'3'=>'Transparency', 
		'4'=>'SG', 
		'5'=>'pH', 
		'6'=>'Sugar', 
		'7'=>'Albumin',
		'8'=>'RBC', 
		'9'=>'WBC', 
		'10'=>'Casts', 
		'11'=>'Crystals', 
		'12'=>'Epith_Cells', 
		'13'=>'Bacteria', 
		'14'=>'Mucus_Threads',  
		);
    echo "<a href=\"#top\">Back to TOP</a>";
    echo "<table border=1><tr>";
    echo "<td>";
//labels
    echo "<table><tr><th><a name=\"urine\">Urinalysis</a></th></tr>";
    if ($row->urine2)
        $eurine = explode(",", $row->urine2); 
    foreach ($urine_part as $part)
        echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"12\" value=\"".$part."\"/></td></tr>";
    echo "</table></td><td>";
//N.V.
    echo "<table><tr><th>(N.V.)</th></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(Yellow)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(Clear, Hazy)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(1.016-1.022)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(4.6-6.5)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(-)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(-)\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\" /></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
    echo "</table></td>";

//input columns
    $y = 0;
    for($x=1; $x<=15; $x++){
        echo "<td>";
        echo "<table><tr><th>".$x."</th></tr>";
        foreach ($urine_part as $part){
//format: cbc_part# ie cbc_wbc1, cbc_neut1...cbc_wbc2, cbc_neut2
            echo "<tr><td><input type=\"text\" name=\"".$part.$x."\" value=\"";
            if ($row->urine2)
                echo revert_form_input($eurine[$y]);
        echo "\" size=\"5\" /></td></tr>";
        $y++;
        }
        echo "</table></td>";
    }
    echo "</tr></table>";
    if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"Update Urinalysis");
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
    }    
//end of urinalysis

//ABG table
if (!strcmp($one_gm, "y"))
    echo form_open('show/update_abg2');
    if (!strcmp($my_service, 'micu'))
        $vars['eadmission'] = $row->micu_id;
    elseif (!strcmp($my_service, 'er'))
        $vars['eadmission'] = $row->er_id;
    else
        $vars['eadmission'] = $row->a_id;
$abg_part = array(
		'0'=>'Date', 
		'1'=>'Time', 
		'2'=>'FiO2', 
		'3'=>'Temp', 
		'4'=>'pH', 
		'5'=>'pCO2', 
		'6'=>'pO2', 
		'7'=>'HCO3',
		'8'=>'TCO2', 
		'9'=>'O2Sats', 
		'10'=>'BE', 
		'11'=>'Na', 
		'12'=>'K', 
		'13'=>'Cl', 
				
		);
echo "<a href=\"#top\">Back to TOP</a>";
echo "<table border=1><tr>";
echo "<td>";
//labels

echo "<table><tr><th><a name=\"abg\">ABG</a></th></tr>";
if ($row->abg2)
     $eabg = explode(",", $row->abg2); 
foreach ($abg_part as $part)
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"12\" value=\"".$part."\"/></td></tr>";
echo "</table></td><td>";
//N.V.
echo "<table><tr><th>(N.V.)</th></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(7.35-7.45)\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(35-45)\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(22-28)\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\" /></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "</table></td>";

//input columns
$y = 0;
for($x=1; $x<=15; $x++){
    echo "<td>";
    echo "<table><tr><th>".$x."</th></tr>";
    foreach ($abg_part as $part){
//format: cbc_part# ie cbc_wbc1, cbc_neut1...cbc_wbc2, cbc_neut2
        echo "<tr><td><input type=\"text\" name=\"".$part.$x."\" value=\"";
        if ($row->abg2)
            echo revert_form_input($eabg[$y]);
        echo "\" size=\"5\" /></td></tr>";
        $y++;
    }
    echo "</table></td>";
}
echo "</tr></table>";
    if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"Update ABG");
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
    }    
//end of ABG

//fecalysis table
if (!strcmp($one_gm, "y"))
    echo form_open('show/update_fecalysis2');
    if (!strcmp($my_service, 'micu'))
        $vars['eadmission'] = $row->micu_id;
    elseif (!strcmp($my_service, 'er'))
        $vars['eadmission'] = $row->er_id;
    else
        $vars['eadmission'] = $row->a_id;
$fecalysis_part = array(
		'0'=>'Date', 
		'1'=>'Time', 
		'2'=>'Appearance', 
		'3'=>'Ova', 
		'4'=>'RBC', 
		'5'=>'WBC', 
		'6'=>'Occult_Blood', 
		'7'=>'Others',
						
		);
echo "<a href=\"#top\">Back to TOP</a>";
echo "<table border=1><tr>";
echo "<td>";
//labels

echo "<table><tr><th><a name=\"fecalysis\">Fecalysis</a></th></tr>";
if ($row->fecalysis2)
     $efecalysis = explode(",", $row->fecalysis2); 
foreach ($fecalysis_part as $part)
     echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"12\" value=\"".$part."\"/></td></tr>";
echo "</table></td>";
/*
//N.V.
echo "<table><tr><th>(N.V.)</th></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(7.35-7.45)\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(35-45)\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"(22-28)\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\" /></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"11\" value=\"\"/></td></tr>";
echo "</table></td>";
*/
//input columns
$y = 0;
for($x=1; $x<=10; $x++){
    echo "<td>";
    echo "<table><tr><th>".$x."</th></tr>";
    foreach ($fecalysis_part as $part){
//format: cbc_part# ie cbc_wbc1, cbc_neut1...cbc_wbc2, cbc_neut2
        echo "<tr><td><input type=\"text\" name=\"".$part.$x."\" value=\"";
        if ($row->fecalysis2)
            echo revert_form_input($efecalysis[$y]);
        echo "\" size=\"15\" /></td></tr>";
        $y++;
    }
    echo "</table></td>";
}
echo "</tr></table>";
    if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"Update Fecalysis");
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
    }    
//end of fecalysis

//uchem table
if (!strcmp($one_gm, "y"))
    echo form_open('show/update_uchem2');
    if (!strcmp($my_service, 'micu'))
        $vars['eadmission'] = $row->micu_id;
    elseif (!strcmp($my_service, 'er'))
        $vars['eadmission'] = $row->er_id;
    else
        $vars['eadmission'] = $row->a_id;
$uchem_part = array(
		'0'=>'Date', 
		'1'=>'Time', 
		'2'=>'Total_Volume', 
		'3'=>'Creatinine', 
		'4'=>'Total_Protein', 
		'5'=>'Na', 
		'6'=>'K', 
		'7'=>'Cl',
		'8'=>'Uric_Acid', 
		'9'=>'Ca', 
		'10'=>'Phosphorus', 
		'11'=>'Amylase', 
		);
echo "<a href=\"#top\">Back to TOP</a>";
echo "<table border=1><tr>";
echo "<td>";
//labels
echo "<table><tr><th><a name=\"uchem\">24H Urine Chem</a></th></tr>";
if ($row->uchem2)
     $euchem = explode(",", $row->uchem2); 
foreach ($uchem_part as $part)
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"12\" value=\"".$part."\"/></td></tr>";
echo "</table></td><td>";
//N.V.
echo "<table><tr><th>(N.V.)</th></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"(500-2000 cc)\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"(0.65-0.70 g/L)\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"(0-0.1 g/24H)\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"(80-216 mmol/L)\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"(25-100 mmol/L)\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"(80-340 mmol/L)\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"(4.42-5.9 mmol/24H)\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"(2.5-7.5 mmol/24H)\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"(22.4-33.6 mmol/24H)\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"(64.75-490.25 U/L)\" /></td></tr>";
echo "</table></td>";
//input columns
$y = 0;
for($x=1; $x<=10; $x++){
    echo "<td>";
    echo "<table><tr><th>".$x."</th></tr>";
    foreach ($uchem_part as $part){
//format: cbc_part# ie cbc_wbc1, cbc_neut1...cbc_wbc2, cbc_neut2
        echo "<tr><td><input type=\"text\" name=\"".$part.$x."\" value=\"";
        if ($row->uchem2)
            echo revert_form_input($euchem[$y]);
        echo "\" size=\"8\" /></td></tr>";
        $y++;
    }
    echo "</table></td>";
}

echo "</tr></table>";
    if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"Update 24H Urine Chem");
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
    }  

//end of uchem


//culture table
if (!strcmp($one_gm, "y"))
echo form_open('show/update_culture2');
    if (!strcmp($my_service, 'micu'))
        $vars['eadmission'] = $row->micu_id;
    elseif (!strcmp($my_service, 'er'))
        $vars['eadmission'] = $row->er_id;
    else
        $vars['eadmission'] = $row->a_id;
$culture_part = array(
		'0'=>'Date', 
		'1'=>'Time', 
		'2'=>'Specimen', 
		'3'=>'PMN', 
		'4'=>'Epith_Cell', 
		'5'=>'Gram_Stain', 
		'6'=>'Growth', 
		'7'=>'Organisms',
		'8'=>'Susceptible', 
		'9'=>'Intermediate', 
		'10'=>'Resistant', 
		);
echo "<a href=\"#top\">Back to TOP</a>";
echo "<table border=1><tr>";
echo "<td>";
//labels
echo "<table><tr><th><a name=\"culture\">Cultures</a></th></tr>";
if ($row->culture2)
     $eculture = explode(",", $row->culture2); 
foreach ($culture_part as $part)
    echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"10\" value=\"".$part."\"/></td></tr>";

echo "</table></td><td>";
//N.V.
echo "<table><tr><th>(ie)</th></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"blood, sputum,..\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"gram+/gram-\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"10^5, heavy,..\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"S. aureus, S. pneum..\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"\"/></td></tr>";
echo "<tr><td><input type=\"text\" readonly=\"readonly\" size=\"18\" value=\"\"/></td></tr>";
echo "</table></td>";
//input columns
$y = 0;
for($x=1; $x<=10; $x++){
    echo "<td>";
    echo "<table><tr><th>".$x."</th></tr>";
    foreach ($culture_part as $part){
//format: cbc_part# ie cbc_wbc1, cbc_neut1...cbc_wbc2, cbc_neut2
        echo "<tr><td><input type=\"text\" name=\"".$part.$x."\" value=\"";
        if ($row->culture2)
            echo revert_form_input($eculture[$y]);
        echo "\" size=\"14\" /></td></tr>";
        $y++;
    }
    echo "</table></td>";
}
echo "</tr></table>";
    if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"Update Culture");
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
    }    
//end of culture

//imaging table
if (!strcmp($one_gm, "y"))
    echo form_open('show/update_imaging2');
    if (!strcmp($my_service, 'micu'))
        $vars['eadmission'] = $row->micu_id;
    elseif (!strcmp($my_service, 'er'))
        $vars['eadmission'] = $row->er_id;
    else
        $vars['eadmission'] = $row->a_id;
$imaging_part = array(
		'0'=>'Date', 
		'1'=>'Time', 
		'2'=>'Test', 
		'3'=>'Body_Part',
		'4'=>'Reading', 
							
		);
echo "<a href=\"#top\">Back to TOP</a>";
//labels
echo "<table border=1><tr><th><a name=\"imaging\">Imaging No.</a></th>";
foreach ($imaging_part as $part)
    echo "<th>".$part."</th>";
echo "</tr>";
if ($row->imaging2)
     $eimaging = explode(",", $row->imaging2); 
//input columns
$y = 0;
for($x=1; $x<=15; $x++){
     echo "<tr>";
     echo "<th>".$x."</th>";
     foreach ($imaging_part as $part){
 //format: cbc_part# ie cbc_wbc1, cbc_neut1...cbc_wbc2, cbc_neut2
        echo "<td><input type=\"text\" name=\"".$part.$x."\" value=\"";
        if ($row->imaging2)
            echo revert_form_input($eimaging[$y]);
        if (!strcmp($part, 'Reading'))
            echo "\" size=\"170\" /></td>";
        else
            echo "\" size=\"15\" /></td>";
        $y++;
     }
}
echo "</tr></table>";
    if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"Update Imaging");
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
    }    
//end of imaging

//ecg table
if (!strcmp($one_gm, "y"))
    echo form_open('show/update_ecg2');
    if (!strcmp($my_service, 'micu'))
        $vars['eadmission'] = $row->micu_id;
    elseif (!strcmp($my_service, 'er'))
        $vars['eadmission'] = $row->er_id;
    else
        $vars['eadmission'] = $row->a_id;
$ecg_part = array(
		'0'=>'Date', 
		'1'=>'Time', 
		'2'=>'Test',
		'3'=>'Reading', 
							
		);
echo "<a href=\"#top\">Back to TOP</a>";
//labels
echo "<table border=1><tr><th><a name=\"ecg\">ECG/EEG No.</a></th>";
foreach ($ecg_part as $part)
    echo "<th>".$part."</th>"; 
echo "</tr>";
if ($row->ecg2)
     $eecg = explode(",", $row->ecg2); 
//input columns
$y = 0;
for($x=1; $x<=15; $x++){
     echo "<tr>";
     echo "<th>".$x."</th>";
     foreach ($ecg_part as $part){
 //format: cbc_part# ie cbc_wbc1, cbc_neut1...cbc_wbc2, cbc_neut2
         echo "<td><input type=\"text\" name=\"".$part.$x."\" value=\"";
         if ($row->ecg2)
             echo revert_form_input($eecg[$y]);
         if (!strcmp($part, 'Reading'))
             echo "\" size=\"180\" /></td>";
        else
             echo "\" size=\"15\" /></td>";
        $y++;
    }
}
echo "</tr></table>";
    if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"Update ECG");
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
    }    

//end of ecg

//others table
if (!strcmp($one_gm, "y"))
    echo form_open('show/update_others2');
    if (!strcmp($my_service, 'micu'))
        $vars['eadmission'] = $row->micu_id;
    elseif (!strcmp($my_service, 'er'))
        $vars['eadmission'] = $row->er_id;
    else
        $vars['eadmission'] = $row->a_id;
$others_part = array(
		'0'=>'Date', 
		'1'=>'Time', 
		'2'=>'Test',
		'3'=>'Results', 
							
		);
echo "<a href=\"#top\">Back to TOP</a>";
//labels
echo "<table border=1><tr><th><a name=\"others\">Other Labs</a></th>";
foreach ($others_part as $part)
    echo "<th>".$part."</th>"; 
echo "</tr>";
if ($row->others2)
     $eothers = explode(",", $row->others2); 
//input columns
$y = 0;
for($x=1; $x<=30; $x++){
    echo "<tr>";
    echo "<th>".$x."</th>";
    foreach ($others_part as $part){
 //format: cbc_part# ie cbc_wbc1, cbc_neut1...cbc_wbc2, cbc_neut2
        echo "<td><input type=\"text\" name=\"".$part.$x."\" value=\"";
        if ($row->others2)
            echo revert_form_input($eothers[$y]);
        if (!strcmp($part, 'Results'))
            echo "\" size=\"180\" /></td>";
        else
            echo "\" size=\"15\" /></td>";
        $y++;
    }
}
echo "</tr></table>";
    if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"Update Others");
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
    }    
//end of others

echo "</div>";

echo "<div style=\"float:left\">";

echo "</div>";
echo "</div>";
}


?>
