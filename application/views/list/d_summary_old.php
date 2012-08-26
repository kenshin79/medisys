<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Wed, 30 Nov 2011 03:47:05 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Discharge Summary</title>

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
                  $(".button").hover(function() {
                    $(this).addClass('hover');
                    }, function() {
                    $(this).removeClass('hover');
                    }
                    );

        });

    </script>

    <style type="text/css">
	input{font-size:80%; border-style:none;}
	textarea{font-size:80%; border-style:none;}
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
    $label = array('value'=>"******", 'class'=>'button');
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
    $label = array('value'=>"Back to Patients", 'class'=>'button');
    make_buttons($my_service, $label, $vars, "left", "");
    echo form_close();
}
elseif (!strcmp($one_gm, 'res')){
    echo form_open('census/resident_census');
    $label = array('value'=>"Back to Residents", 'class'=>'button');
    make_buttons($my_service, $label, $vars, "left", "");
    echo form_close();
}
if (!strcmp($one_gm, "y"))  
    echo form_open('show/update_dsummary');
foreach ($p_admission as $row){
        if (!strcmp($my_service, 'micu'))
            echo form_hidden('eadmission', $row->micu_id);
        elseif (!strcmp($my_service, 'er'))
            echo form_hidden('eadmission', $row->er_id);
        else
            echo form_hidden('eadmission', $row->a_id);
        if ($row->abstract)
            $eabstract = explode(",", $row->abstract); 
        if ($row->dsummary)
            $edsummary = explode(",", $row->dsummary); 
        $pdata = $this->Patient_model->get_one_patient($row->p_id);
        foreach ($pdata as $patient){    
            $age = compute_age_adm($row->date_in, $patient->p_bday);
            $pname = $patient->p_name;
            $psex = $patient->p_sex;
            $pcnum = $patient->cnum;
        }  
    echo "<div>";
    echo "<div class = \"main_frame\" style = \"border-left:none; border-right:none\" >";
        echo "<table><tr>";
        echo "<td style=\"width:1000px;border-bottom-color:black; border-bottom-style:solid; border-bottom-width:thin; \"><div style=\"float:left\"><img src=\"/medisys/img/pghlabel2.jpg\" style=\"width:300px; height:80px;\" /></div><div style=\"float:right;\"><img src=\"/medisys/img/dsummary.jpg\" style=\"width:300px; height:75px\" /></div></td>";
        echo "</tr></table>";
        echo "<table>";
        echo "<tr><td>Name:</td><td style=\"text-align:left;\"><input type=\"text\" name=\"plname\" style = \"text-align:left;border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" size = 20 value=\"";
//Last name of patient
        if ($row->dsummary){  
            if ($edsummary[0])
                echo revert_form_input($edsummary[0]);
            else
                echo revert_form_input($pname);
        }
        else
            echo revert_form_input($pname);
        echo "\"></td><td><input type=\"text\" name=\"pfname\" style = \"text-align:left;border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" size = 20 value =\"";
//First name of patient
        if ($row->dsummary)
            echo revert_form_input($edsummary[1]);
        echo "\"></td><td><input type=\"text\" name=\"pmname\" style = \"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" size = 2 value=\"";
//Middle Name of patient
        if ($row->dsummary)
            echo revert_form_input($edsummary[2]);
        echo "\"></td>";
        echo "<td style=\"width:50px\"></td>";
        echo "<td>Age/Sex:</td><td><input type=\"text\" style = \"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" size = 10 value=\"";
//age and sex
        echo $age."/".revert_form_input($psex)."\"></td>";
        echo "<td style=\"width:100px\"></td>";
        echo "<td style=\"text-align:left;\">Case No.:<input type=\"text\" style = \"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" size = 10 value = \"";
//cnum
        echo revert_form_input($pcnum)."\"></td>";
        echo "</td></tr>"; 
        echo "<tr><td></td><td>Last</td><td>First</td><td>Middle</td><td></td><td></td><td></td></tr>";
        echo "</table>";

        echo "<table>";
        echo "<tr><td style=\"text-align:left;vertical-align:top; width:150px\">Discharge Diagnosis:</td><td></td><td><textarea  name=\"disdiagnosis\" rows = \"3\" cols = \"50\" wrap = \"on\" >";
//Discharge Diagnosis
        if ($row->dsummary){
            if ($edsummary[3])
                echo revert_form_input($edsummary[3]);
            else
                echo revert_form_input($row->plist);
        }
        else
            echo $row->plist;
        echo "</textarea></td><td style=\"width:50px\"></td><td style=\"text-align:right;vertical-align:top;\">X-ray No.:</td><td style=\"vertical-align:top;\"><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\"type=\"text\" name=\"xnum\" size=\"10\" value=\"";
//xray number
        if ($row->dsummary)
            echo revert_form_input($edsummary[4]);
        echo "\"/></td></tr>";
        echo "<tr><td></td><td></td><td></td><td style=\"width:70px\"></td><td>Service:GM </td><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\"type=\"text\" size=\"10\" value=\"".$my_service."\"/></td></tr>";
        echo "</table>";

        echo "<table>";
        echo "<tr><td style=\"width:100px\">Date of Admission:</td><td></td><td><input style = \"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" type=\"text\" name=\"\" size=\"10\" value=\"";
//Date Admitted
        echo revert_form_input($row->date_in)."\"></td><td></td><td>Date Discharge:</td><td></td><td><input style = \"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" type=\"text\" name=\"disdate\" size=\"10\" value=\"";
//Date Discharged
        if ($row->dsummary)
            echo revert_form_input($edsummary[5]);
        echo "\"></td><td style=\"width:100px\"></td><td style=\"width:100px;text-align:right\">Histopath No.:</td><td><input type=\"text\" name=\"hisnum\" style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" size=\"10\" value=\"";
//Histopath number
        if ($row->dsummary)
            echo revert_form_input($edsummary[6]);
        echo "\"/></td></tr>";
        echo "</table>";

        echo "<table>";
        echo "<tr><td>Mailing Address:</td><td style=\"width:20px\"></td><td><input type=\"text\" name=\"mailadd\" style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" size=\"50\" value=\"";
//Mail Address
        if ($row->dsummary)
            echo revert_form_input($edsummary[7]);
        echo "\"/></td><td style=\"width:90px\"></td><td style=\"width:100px;text-align:right\">Tel. No.:</td><td><input type=\"text\" name=\"ptelnum\" style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" value=\"";
//Tel Number
        if ($row->dsummary)
            echo revert_form_input($edsummary[8]);
        echo "\" size=\"10\"></td></tr>";
        echo "</table>";

        echo "<table>";
        echo "<tr><td style=\"width:100px\">Name of next of kin:</td><td><input type=\"text\" name=\"relative\" style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" size=\"50\" value=\"";
//relative
        if ($row->dsummary)
            echo revert_form_input($edsummary[9]);
        echo "\"/></td><td style=\"width:140px;text-align:right\">Address:</td><td><input type=\"text\" style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" name=\"reladd\" size=\"40\" value=\"";
//relative address
        if ($row->dsummary)
            echo revert_form_input($edsummary[10]);
        echo "\"/></td></tr>";
        echo "<tr><td style=\"width:140px\"></td><td></td><td style=\"width:70px; text-align:right\">Tel.No.</td><td><input type=\"text\" name=\"rtelnum\" style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" value=\"";
//relative tel num
        if ($row->dsummary)
            echo revert_form_input($edsummary[11]);
        echo "\"></td></tr>";
        echo "</table>";
        echo "<hr style=\"border-bottom-style:dotted;\" />";

        echo "<table>";
        echo "<tr><td>Chief Complaint:</td><td><input type=\"text\" name=\"cc\" value=\"";
//Chief Complaint
        if ($row->abstract)
            echo revert_form_input($eabstract[5]);
        echo "\" /></td></tr>";
        echo "<tr><td style=\"vertical-align:top;\">History of Present Illness:</td><td><textarea cols=\"120\" rows=\"25\" wrap=\"on\">";
//HPI
        if ($row->abstract)
            echo revert_form_input($eabstract[7]);
        echo "</textarea></td></tr>";
        echo "<tr><td style=\"vertical-align:top;\">Physical Exam on Discharge:</td><td><textarea name = \"dispe\" cols=\"120\" rows=\"10\" wrap=\"on\">";
//PE on discharge           
        if ($row->dsummary)
            echo revert_form_input($edsummary[12]);
        echo "</textarea></td></tr>";
        echo "</table>";

        echo "<table>";
        echo "<tr><td style=\"width:100px\">Pertinent Labs:</td><td>Hb =<input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"hgb\" size=\"5\" value=\"";
//Labs
        if ($row->dsummary)
            echo revert_form_input($edsummary[13]);
        echo "\"/>g/l</td><td>Hct.=<input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"hct\" size=\"5\" value=\"";
//Hct
        if ($row->dsummary)
            echo revert_form_input($edsummary[14]);
        echo "\"/></td><td>Blood Type<input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"btype\" size=\"5\" value=\"";
//Blood Type
        if ($row->dsummary)
            echo revert_form_input($edsummary[15]);
        echo "\"/></td></tr>";
        echo "<tr><td></td><td>Wbc =<input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"wbc\" size=\"10\" value=\"";
//wbc
        if ($row->dsummary)
            echo revert_form_input($edsummary[16]);
        echo "\"/>x 10^9/l</td><td>ESR =<input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"esr\" size=\"10\" value=\"";
//esr
        if ($row->dsummary)
            echo revert_form_input($edsummary[17]);
        echo "\"/>mm/hr</td><td>Alk. Phos.<input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"aphos\" size=\"10\" value=\"";
//Alk Phos
        if ($row->dsummary)
            echo revert_form_input($edsummary[18]);
        echo "\"/>U/L</td></tr>";
        echo "<tr><td></td><td style=\"vertical-align:top\">Culture and Sensitivity  Studies:</td><td><textarea name=\"culture\" cols=\"30\" rows=\"3\" wrap=\"on\">";
//Cultures
        if ($row->dsummary)
            echo revert_form_input($edsummary[19]);
        echo "</textarea></td><td></td></tr>";
        echo "</table>";

        echo "<table>";
        echo "<tr><td style=\"vertical-align:top\">X-ray/CT-Scan/MRI Studies:</td><td><textarea name = \"xray\" cols=\"120\" rows=\"4\" wrap=\"on\">";
//xray
        if ($row->dsummary)
            echo revert_form_input($edsummary[20]);
        echo "</textarea></td></tr>";

        echo "<tr><td style=\"vertical-align:top\">Post-OP:</td><td><textarea name = \"postop\" cols=\"120\" rows=\"6\" wrap=\"on\">";
//post-op 
        if ($row->dsummary)
            echo revert_form_input($edsummary[21]);
        echo "</textarea></td></tr>";
        echo "</table>";
        echo "<br/>";
        echo "<br/>";
        echo "<br/>";
        echo "<br/>";
    echo "</div>";
        echo "<div id = \"addbreaks\"></div>";
        echo "<div class = \"main_frame\" style = \"border-left:none; border-right:none\">"; 


        echo "<div align=\"center\"><table>";
        echo "<tr><th style=\"text-align:center;\">OPERATION</th><th style=\"text-align:center;\">SURGEON</th><th style=\"text-align:center;\">DATE</th></tr>";
        echo "<tr><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"or1\" size=\"30\" value=\"";
//OR1
        if ($row->dsummary)
            echo revert_form_input($edsummary[22]);
        echo "\" /></td><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"surg1\" size=\"30\" value=\"";
//Surg 1
        if ($row->dsummary)
            echo revert_form_input($edsummary[23]);
        echo "\" /></td><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"ordate1\" size=\"30\" value=\"";
//OR Date 1
        if ($row->dsummary)
            echo revert_form_input($edsummary[24]);
        echo "\" /></td></tr>";
        echo "<tr><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"or2\" size=\"30\" value=\"";
//OR2
        if ($row->dsummary)
            echo revert_form_input($edsummary[25]);
        echo "\" /></td><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"surg2\" size=\"30\" value=\"";
//Surg 2
        if ($row->dsummary)
            echo revert_form_input($edsummary[26]);
        echo "\" /></td><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"ordate2\" size=\"30\" value=\"";
//OR Date 2
        if ($row->dsummary)
            echo revert_form_input($edsummary[27]);
        echo "\" /></td></tr>";
        echo "<tr><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"or3\" size=\"30\" value=\"";
//OR3
        if ($row->dsummary)
            echo revert_form_input($edsummary[28]);
        echo "\" /></td><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"surg3\" size=\"30\" value=\"";
//Surg 3
        if ($row->dsummary)
            echo revert_form_input($edsummary[29]);
        echo "\" /></td><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"ordate3\" size=\"30\" value=\"";
//OR date 3
        if ($row->dsummary)
            echo revert_form_input($edsummary[30]);
        echo "\" /></td></tr>";
        echo "<tr><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"or4\" size=\"30\" value=\"";
//OR4
        if ($row->dsummary)
            echo revert_form_input($edsummary[31]);
        echo "\" /></td><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"surg4\" size=\"30\" value=\"";
//Surg 4
        if ($row->dsummary)
            echo revert_form_input($edsummary[32]);
        echo "\" /></td><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"ordate4\" size=\"30\" value=\"";
//OR Date 4
        if ($row->dsummary)
            echo revert_form_input($edsummary[33]);
        echo "\" /></td></tr>";
        echo "<tr><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"or5\" size=\"30\" value=\"";
//OR 5
        if ($row->dsummary)
            echo revert_form_input($edsummary[34]);
        echo "\" /></td><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"surg5\" size=\"30\" value=\"";
//Surg 5
        if ($row->dsummary)
            echo revert_form_input($edsummary[35]);
        echo "\" /></td><td><input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"ordate5\" size=\"30\" value=\"";
//OR Date 5
        if ($row->dsummary)
            echo revert_form_input($edsummary[36]);
        echo "\" /></td></tr>";
        echo "</table></div>";

        echo "<table>";
        echo "<tr><td style=\"vertical-align:top\">Operative Findings:</td><td><textarea name = \"orfind\" cols=\"120\" rows=\"15\" wrap=\"on\">";
//OR findings
        if ($row->dsummary)
            echo revert_form_input($edsummary[37]);
        echo "</textarea></td></tr>";
        echo "<tr><td style=\"vertical-align:top\">Course in the WARD (PRE & POST-OP):</td><td><textarea name = \"cwards\" cols=\"120\" rows=\"15\" wrap=\"on\">";
//Course in Wards
        if ($row->dsummary)
            echo revert_form_input($edsummary[38]);
        echo "</textarea></td></tr>";
        echo "</table>";

        echo "<table>";
        echo "<tr><td>MORBIDITY:</td><td>";
//Morbidity
        if ($row->dsummary){
            if (!strcmp($edsummary[39], 'yes')){
                echo form_checkbox('morbid', 'no', FALSE);
                echo "NO</td><td>";
                echo form_checkbox('morbid', 'yes', TRUE);
                echo "YES</td>";
            }
            else
                echo form_radio('morbid', 'no', TRUE)."NO</td><td>".form_radio('morbid', 'yes', FALSE)."YES</td>";
        }
        else
            echo form_radio('morbid', 'no', FALSE)."NO</td><td>".form_radio('morbid', 'yes', FALSE)."YES</td>";
        echo "<td>(Specify)<input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"mspecify\" size=\"60\" value=\"";
//Morbidity Specify
        if ($row->dsummary)
            echo revert_form_input($edsummary[40]);
        echo "\"/></td></tr>";
        echo "</table>";

        echo "<table>";
        echo "<tr><td style=\"vertical-align:top\">DISCHARGE PLANS:</td><td><textarea name = \"displans\" cols=\"120\" rows=\"16\" wrap=\"on\">";
//Discharge Plans
        if ($row->dsummary)
            echo revert_form_input($edsummary[41]);
        echo "</textarea></td></tr>";
        echo "</table>";

        echo "<table>";
        echo "<tr><td>PREPARED BY:</td><td><input style=\"text-align:center\" type=\"text\" name=\"prepby\" size=\"30\" value=\"";
//Prep By
        if ($row->dsummary)
            echo revert_form_input($edsummary[42]);
        echo "\"/></td><td style=\"width:50px\"</td><td ><input style=\"text-align:center\" type=\"text\" name=\"ric\" size=\"30\" value=\"";
//RIC
        if (!strcmp($my_service, "er"))
            $jr = $this->Resident_model->get_resident_name($row->pod_id); 
        else
            $jr = $this->Resident_model->get_resident_name($row->r_id); 
        foreach ($jr as $name)
            echo revert_form_input($name->r_name)." M.D.";
 
        echo "\"/></td></tr>";
        echo "<tr><td></td><td style=\"border-top-width:thin; border-top-color:black; border-top-style:solid; text-align:center;\">(Signature Over Printed Name)</td><td></td><td style=\"border-top-width:thin; border-top-color:black; border-top-style:solid;text-align:center;\">Resident-in-Charge<br />(Signature Over Printed Name)</td></tr>";
        echo "</table>";
    }
        if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"******", 'class'=>'button');
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
        }    

    echo "</div>";
    echo "/div>";    

?>
