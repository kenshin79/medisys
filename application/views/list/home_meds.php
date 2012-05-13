<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Wed, 30 Nov 2011 03:47:05 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Home Medications Form</title>
    <link rel="stylesheet" type="text/css" href="/medisys/css/abstract.css" />
  	<script type="text/javascript" src="/medisys/js/jquery.js"></script>

        <script>
    	$(document).ready(function(){
    	 if ($.browser.mozilla) {
    	    $(".main_frame:eq(0)").addClass("ffpage1");
    	    $(".main_frame:eq(1)").addClass("ffpage2");
            document.getElementById("addbreaks").innerHTML="<br/><br/><br/><br/><br/><br/><br/><br/>";
         }
         else{
    	    $(".main_frame:eq(0)").addClass("page1");
    	    $(".main_frame:eq(1)").addClass("page2");
         }

        });
       </script>
    <style type="text/css">
	input{font-size:120%; border-style:none;}
	textarea{font-size:100%; border-style:none;}
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

echo "<div class = \"main_frame\">";
if (!strcmp($one_gm, "y"))  
    echo form_open('show/update_home');
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
        $pname = $patient->p_name;
        $psex = $patient->p_sex;
        $cnum = $patient->cnum;
    }    

    if ($row->home)
        $ehome = explode(",", $row->home); 

    echo "<font size = 1>PGH Form No. P-371002 </font>";    
    echo "<table>";
    echo "<tr><th><div style=\"float:left\"><img src=\"/medisys/img/pghlogo.bmp\" style=\"width:80px; height:50px; \" /></div><div style=\"float:none\"><img src=\"/medisys/img/pghlabel.bmp\" style=\"width:300px; height:50px\" /></div><div style=\"float:right;\"></div></th></tr>";
    echo "<tr><th style=\"width:1000px\"><div align= \"center\" style=\"text-align:center; text-decoration:underline; \"><font size=4>HOME MEDICATION SHEET</div></th></tr>";
    echo "</table>";

    echo "<table ><tr><th></th><th></th><th style=\"width:200px; text-align:right; \">Date:</th><th>".mdate("%M-%d-%Y")."</th></tr>";
    echo "<tr><th>Patient:</th><th style=\"width:200px; text-align:left; \">";
//patient name
    echo revert_form_input($pname);
    echo "</th><th style=\"width:200px; text-align:right; \">Date of Admission: </th><th>";
//Date admitted
    echo revert_form_input($row->date_in);
    echo "</th></tr>";
    echo "<tr><th>Age/Sex:</th><th style=\"width:200px; text-align:left; \">";
//age
    echo $age;
    echo "/";
//sex
    echo revert_form_input($psex);
    echo "</th><th style=\"width:200px; text-align:right; \">Date of Discharge:</th><th><input name=\"out_date\" type=\"text\" size=\"20\" value=\"";
//discharge date
    if ($row->home)
        echo revert_form_input($ehome[0]);
    echo "\"></th></tr>";
    echo "<tr><th>Case Number:</th><th style=\"width:200px; text-align:left;\">";
//Case number
    echo revert_form_input($cnum);
    echo "</th><th style=\"width:200px; text-align:right; \">Service: Gen Med - ";
//Service
    if (!strcmp($my_service, 'micu'))
        echo "MICU";
    elseif (!strcmp($my_service, 'er'))
        echo "ER";
    else
        echo $row->service;
    echo "</th><th></th></tr>";
    echo "<tr><th></th><th></th><th style=\"width:200px; text-align:right; \">Resident-in-charge:</th><th>";
//RIC
    if (!strcmp($my_service, "er"))
         $jr = $this->Resident_model->get_resident_name($row->pod_id);
    else 
         $jr = $this->Resident_model->get_resident_name($row->r_id);      
    foreach ($jr as $name)
		 echo revert_form_input($name->r_name)." M.D.";
    echo "</th></tr></table>";
    echo "Final Diagnosis:<br />";
    echo "<textarea cols=\"80\" rows=\"7\">";
//Plist
    echo revert_form_input($row->plist);
    echo "</textarea>";
    echo "<table border=3 style=\"border-color:black; border-style:solid;\">";
    echo "<tr><th>MEDICATION<br />(GAMOT)</th><th>TIME (ORAS NG PAG-INOM)</th><th>DURATION<br />(HANGGANAN)</th></tr>";
    echo "<tr><td></td><td><table border=1 style=\"border-color:black; border-style:solid;\"><tr><td style=\"width:40px\">6 am</td><td style=\"width:40px\">8 am</td><td style=\"width:40px\">1 pm</td><td style=\"width:40px\">6 pm</td><td style=\"width:40px\">8 pm</td><td style=\"width:40px\">10 pm</td></tr></table></td><td></td></tr>";
//row1
    echo "<tr><td><input name=\"med1\" type=\"text\" style=\"width:250px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[1]);
    echo "\"/></td>";
    if ($row->home){
        if (!strcmp($ehome[2], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td><table border=1 style=\"border-color:black; border-style:solid;\"><tr><td style=\"width:40px\">".form_checkbox('a6am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[3], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('a8am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[4], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('a1pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[5], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('a6pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[6], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('a8pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[7], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('a10pm', 'yes', $take)."</td></tr></table></td>";
    echo "<td><input name=\"long1\" type=\"text\" style=\"width:100px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[8]);
    echo "\"></td></tr>";

//row2
    echo "<tr><td><input name=\"med2\" type=\"text\" style=\"width:250px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[9]);
    echo "\"/></td>";
    if ($row->home){
        if (!strcmp($ehome[10], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td><table border=1 style=\"border-color:black; border-style:solid;\"><tr><td style=\"width:40px\">".form_checkbox('b6am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[11], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('b8am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[12], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('b1pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[13], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('b6pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[14], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('b8pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[15], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('b10pm', 'yes', $take)."</td></tr></table></td>";
    echo "<td><input name=\"long2\" type=\"text\" style=\"width:100px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[16]);
    echo "\"></td></tr>";

//row3
    echo "<tr><td><input name=\"med3\" type=\"text\" style=\"width:250px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[17]);
    echo "\"/></td>";
    if ($row->home){
        if (!strcmp($ehome[18], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td><table border=1 style=\"border-color:black; border-style:solid;\"><tr><td style=\"width:40px\">".form_checkbox('c6am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[19], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('c8am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[20], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('c1pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[21], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('c6pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[22], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('c8pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[23], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('c10pm', 'yes', $take)."</td></tr></table></td>";
    echo "<td><input name=\"long3\" type=\"text\" style=\"width:100px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[24]);
    echo "\"></td></tr>";

//row4
    echo "<tr><td><input name=\"med4\" type=\"text\" style=\"width:250px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[25]);
    echo "\"/></td>";
    if ($row->home){
        if (!strcmp($ehome[26], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td><table border=1 style=\"border-color:black; border-style:solid;\"><tr><td style=\"width:40px\">".form_checkbox('d6am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[27], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('d8am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[28], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('d1pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[29], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('d6pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[30], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('d8pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[31], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('d10pm', 'yes', $take)."</td></tr></table></td>";
    echo "<td><input name=\"long4\" type=\"text\" style=\"width:100px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[32]);
    echo "\"></td></tr>";

//row5
    echo "<tr><td><input name=\"med5\" type=\"text\" style=\"width:250px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[33]);
    echo "\"/></td>";
    if ($row->home){
        if (!strcmp($ehome[34], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td><table border=1 style=\"border-color:black; border-style:solid;\"><tr><td style=\"width:40px\">".form_checkbox('e6am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[35], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('e8am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[36], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('e1pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[37], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('e6pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[38], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('e8pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[39], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('e10pm', 'yes', $take)."</td></tr></table></td>";
    echo "<td><input name=\"long5\" type=\"text\" style=\"width:100px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[40]);
    echo "\"></td></tr>";

//row6
    echo "<tr><td><input name=\"med6\" type=\"text\" style=\"width:250px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[41]);
    echo "\"/></td>";
    if ($row->home){
        if (!strcmp($ehome[42], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td><table border=1 style=\"border-color:black; border-style:solid;\"><tr><td style=\"width:40px\">".form_checkbox('f6am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[43], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('f8am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[44], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('f1pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[45], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('f6pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[46], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('f8pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[47], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('f10pm', 'yes', $take)."</td></tr></table></td>";
    echo "<td><input name=\"long6\" type=\"text\" style=\"width:100px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[48]);
    echo "\"></td></tr>";

//row7
    echo "<tr><td><input name=\"med7\" type=\"text\" style=\"width:250px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[49]);
    echo "\"/></td>";
    if ($row->home){
        if (!strcmp($ehome[50], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td><table border=1 style=\"border-color:black; border-style:solid;\"><tr><td style=\"width:40px\">".form_checkbox('g6am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[51], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('g8am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[52], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('g1pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[53], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('g6pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[54], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('g8pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[55], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('g10pm', 'yes', $take)."</td></tr></table></td>";
    echo "<td><input name=\"long7\" type=\"text\" style=\"width:100px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[56]);
    echo "\"></td></tr>";

//row8
    echo "<tr><td><input name=\"med8\" type=\"text\" style=\"width:250px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[57]);
    echo "\"/></td>";
    if ($row->home){
        if (!strcmp($ehome[58], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td><table border=1 style=\"border-color:black; border-style:solid;\"><tr><td style=\"width:40px\">".form_checkbox('h6am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[59], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('h8am', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[60], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('h1pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[61], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('h6pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[62], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('h8pm', 'yes', $take)."</td>";
    if ($row->home){
        if (!strcmp($ehome[63], 'yes'))
	        $take = TRUE;
	    else
	        $take = FALSE;	
    }
    else
        $take = FALSE;	
    echo "<td style=\"width:40px\">".form_checkbox('h10pm', 'yes', $take)."</td></tr></table></td>";
    echo "<td><input name=\"long8\" type=\"text\" style=\"width:100px\"  value=\"";
    if ($row->home)
        echo revert_form_input($ehome[64]);
    echo "\"></td></tr>";
    echo "</table>";

    echo "To comeback on <b>(Petsa ng pagbabalik)</b>:";
    echo "<table><tr><td>____________________________</td><td>(___: am/pm)</td><td style=\"width:150px\"></td><td>OPD at 1BO4-Room 3</td></tr>";
    echo "<tr><td>__________________________</td><td>(___: am/pm)</td><td></td><td>OPD at 1BO4-Room 1</td></tr>";
    echo "<tr><td>__________________________</td><td>(___:am/pm)</td><td></td><td>Subspecialty Clinic:_______________________</td></tr>";
    echo "<tr><td>__________________________</td><td>(___:am/pm)</td><td></td><td>Others:____________________________________</td></tr></table>";
    echo "<br /><br />";
    echo "<div align=\"right\"><b>Resident's Signature:</b>_____________________________________</div>";
    if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"***");
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
    }    
    echo "</div>";
}
?>
