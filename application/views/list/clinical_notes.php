<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Wed, 30 Nov 2011 03:47:05 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Clinical Notes</title>
    <link rel="stylesheet" type="text/css" href="/medisys/css/abstract.css" />
    <style type="text/css">
    input{font-size:80%}
    textarea{font-size:100%}	
	
    </style>
    <!--[if IE]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js">
    </script>
    <![endif]-->
  </head>
  <body>
<?php
//authorize user with uname and pword

$auth_list = $this->Users_model->get_all_users();
$csess = get_cookie('ci_session');
authorize_user($auth_list, $csess);

make_page_header($_SERVER['PHP_AUTH_USER']);
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
    $label = array('value'=>"Back to Admissions");
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


if (!strcmp($one_gm, "y"))  
    echo form_open('show/update_cnotes');
foreach ($p_admission as $row){
    if (!strcmp($my_service, 'micu'))
        $vars['eadmission'] = $row->micu_id;
    elseif (!strcmp($my_service, 'er'))
        $vars['eadmission'] = $row->er_id;
    else
        $vars['eadmission'] = $row->a_id;
    $pdata = $this->Patient_model->get_one_patient($row->p_id);
	foreach ($pdata as $patient){    
        $age = compute_age_adm($row->date_in, $patient->p_bday);
        echo "<p>Patient Name: ".revert_form_input($patient->p_name)." ".$age."/".revert_form_input($patient->p_sex)."</p>";
    }    
    echo "Date of Admission: ".revert_form_input($row->date_in)."</br>";
    echo "<p>Enter Clinical Notes below.</p>";
    echo "<div><div style=\"float:left\">";
    echo "<textarea rows=300 cols=100 name=\"c_notes\">";
    if ($row->c_notes)
	    echo revert_form_input($row->c_notes);
    echo "</textarea></div>";
}
    if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"Capture Notes");
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
    }    
    

