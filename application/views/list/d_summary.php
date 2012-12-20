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
	td, span{font-weight:bold;}
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
        echo "<tr><td>Name:</td><td style=\"text-align:left;\"><input type=\"text\" name=\"plname\" style = \"text-align:left;border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" size = 30 value=\"";
//Last name of patient
        if ($row->dsummary){  
            if ($edsummary[0])
                echo revert_form_input($edsummary[0]);
            else
                echo revert_form_input($pname);
        }
        else
            echo revert_form_input($pname);
        echo "\"></td><td><input type=\"text\" name=\"pfname\" style = \"text-align:left;border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" size = 30 value =\"";
//First name of patient
        if ($row->dsummary)
            echo revert_form_input($edsummary[1]);
        echo "\"></td><td><input type=\"text\" name=\"pmname\" style = \"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" size = 20 value=\"";
//Middle Name of patient
        if ($row->dsummary)
            echo revert_form_input($edsummary[2]);
        echo "\"></td>";
        echo "<td style=\"width:50px\"></td>";
        echo "<td>Age/Sex:</td><td><input type=\"text\" style = \"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" size = 10 value=\"";
//age and sex
        echo $age."/".revert_form_input($psex)."\"></td>";
        echo "<td style=\"width:10px\"></td>";
        echo "<td style=\"text-align:left;\">Case No.:<input type=\"text\" style = \"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" size = 20 value = \"";
//cnum
        echo revert_form_input($pcnum)."\"></td>";
        echo "</td></tr>"; 
        echo "<tr><td></td><td>Last</td><td>First</td><td>Middle</td><td></td><td></td><td></td></tr>";
        echo "</table>";

        echo "<table>";
        echo "<tr><td style=\"text-align:left;vertical-align:top; width:150px\">Discharge Diagnosis:</td><td></td><td><textarea  name=\"disdiagnosis\" rows = \"4\" cols = \"120\" wrap = \"on\" >";
//Discharge Diagnosis
        if ($row->dsummary){
            if ($edsummary[3])
                echo revert_form_input($edsummary[3]);
            else
                echo revert_form_input($row->plist);
        }
        else
            echo $row->plist;
        echo "</textarea></td>";

        echo "<table>";
        echo "<tr><td style=\"width:110px\">Date Admitted:</td><td><input style = \"width:80px; border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" type=\"text\" name=\"\" size=\"10\" value=\"";
//Date Admitted
        echo substr(revert_form_input($row->date_in), 5, 2)."-".substr(revert_form_input($row->date_in), 8, 2)."-".substr(revert_form_input($row->date_in), 2, 2)."\"></td>";
		
		
		echo "<td>Time Admitted:</td><td style=\"width:50px;\">_________</td><td>AM</td><td style=\"width:50px;\">_________</td><td>PM</td><td style=\"width:50px;\"></td>";		
//chief complaint
		echo "<td rowspan = 6 style=\"width:280px; vertical-align:top;border-top-width:thin; border-top-color:black; border-top-style:solid;border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;border-left-width:thin; border-left-color:black; border-left-style:solid;border-right-width:thin; border-right-color:black; border-right-style:solid; \">Chief Complaint/ Reason for Admission <br/>";
        if ($row->abstract)
            echo revert_form_input($eabstract[5]);		
		echo "</td></tr>";
		//labels for Date and Time
		echo "<tr><td></td><td>mm/dd/yy</td><td></td><td>hh-mm</td><td></td><td>hh-mm</td><td></td><td></td></tr>";
		echo "<tr><td>Date Discharged:</td><td><input style = \"width:80px; border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" type=\"text\" name=\"disdate\" size=\"8\" value=\"";		
		//Date Discharged
        if ($row->dsummary)
            echo revert_form_input($edsummary[5]);
        echo "\"></td><td>Time Discharged:</td><td>_________</td><td>AM</td><td>_________</td><td>PM</td><td></td></tr>";
		//labels for Date and Time
		echo "<tr><td></td><td>mm/dd/yy</td><td></td><td>hh-mm</td><td></td><td>hh-mm</td><td></td><td></td></tr>";		
//Attending Physician
		echo "<tr><td>Attending Physician</td><td colspan = 6><input style=\"text-align:center\" type=\"text\" name=\"ric\" size=\"30\" value=\"";
//RIC
        if (!strcmp($my_service, "er"))
            $jr = $this->Resident_model->get_resident_name($row->pod_id); 
        else
            $jr = $this->Resident_model->get_resident_name($row->r_id); 
        foreach ($jr as $name)
            echo revert_form_input($name->r_name)." M.D.";
 
        echo "\"/></td><td></td></tr>";		
        echo "<tr><td></td><td colspan = 6 style=\"border-top-width:thin; border-top-color:black; border-top-style:solid;text-align:center;\"></td></tr>";		
        echo "</table>";
		if ($row->dsummary){
//for prev chest xray		
		    echo form_hidden('xnum', $edsummary[4]);	
//for prev histopath variable
			echo form_hidden('hisnum', $edsummary[6]);
//for prev mail address variable
			echo form_hidden('mailadd', $edsummary[7]);
//for prev telnumber variable
			echo form_hidden('ptelnum', $edsummary[8]);
//for prev relative variable
			echo form_hidden('relative', $edsummary[9]);
//for prev relative address variable
			echo form_hidden('reladd', $edsummary[10]);
//for prev relative telnum variable
			echo form_hidden('rtelnum', $edsummary[11]);
//for prev discharge pe
			echo form_hidden('dispe', $edsummary[12]);	
//for prev hgb
			echo form_hidden('hgb', $edsummary[13]);	
//for prev hct
			echo form_hidden('hct', $edsummary[14]);		
//for prev btype
			echo form_hidden('btype', $edsummary[15]);		
//for prev wbc
			echo form_hidden('wbc', $edsummary[16]);	
//for prev esr
			echo form_hidden('esr', $edsummary[17]);		
//for prev aphos
			echo form_hidden('aphos', $edsummary[18]);
//for prev culture
			echo form_hidden('culture', $edsummary[19]);				
//for prev xray
			echo form_hidden('xray', $edsummary[20]);				
//for prev postop
			echo form_hidden('postop', $edsummary[21]);	
//for prev or1
			echo form_hidden('or1', $edsummary[22]);
//for prev surg1
			echo form_hidden('surg1', $edsummary[23]);
//for prev ordate1
			echo form_hidden('ordate1', $edsummary[24]);		
//for prev or2
			echo form_hidden('or2', $edsummary[25]);
//for prev surg2
			echo form_hidden('surg2', $edsummary[26]);
//for prev ordate2
			echo form_hidden('ordate2', $edsummary[27]);
//for prev or3
			echo form_hidden('or3', $edsummary[28]);
//for prev surg3
			echo form_hidden('surg3', $edsummary[29]);
//for prev ordate3
			echo form_hidden('ordate3', $edsummary[30]);	
//for prev or4
			echo form_hidden('or4', $edsummary[31]);
//for prev surg4
			echo form_hidden('surg4', $edsummary[32]);
//for prev ordate4
			echo form_hidden('ordate4', $edsummary[33]);
//for prev or5
			echo form_hidden('or5', $edsummary[34]);
//for prev surg5
			echo form_hidden('surg5', $edsummary[35]);
//for prev ordate5
			echo form_hidden('ordate5', $edsummary[36]);			
//for prev orfind
			echo form_hidden('orfind', $edsummary[37]);
//for prev mspecify
			echo form_hidden('mspecify', $edsummary[40]);	
//for prev prepby
			echo form_hidden('prepby', $edsummary[42]);			
	
	    }
		else{
		    echo form_hidden('xnum','');
			echo form_hidden('hisnum', '');
			echo form_hidden('mailadd', '');
			echo form_hidden('ptelnum', '');
			echo form_hidden('relative', '');
			echo form_hidden('reladd', '');
			echo form_hidden('rtelnum', '');
			echo form_hidden('dispe', '');		
			echo form_hidden('hgb', '');
			echo form_hidden('hct', '');
			echo form_hidden('btype', '');
			echo form_hidden('wbc', '');
			echo form_hidden('esr', '');
			echo form_hidden('aphos', '');
			echo form_hidden('culture', '');		
			echo form_hidden('xray', '');
			echo form_hidden('postop', '');
			echo form_hidden('or1', '');
			echo form_hidden('surg1', '');
			echo form_hidden('ordate1', '');
			echo form_hidden('or2', '');
			echo form_hidden('surg2', '');
			echo form_hidden('ordate2', '');
			echo form_hidden('or3', '');
			echo form_hidden('surg3', '');
			echo form_hidden('ordate3', '');
			echo form_hidden('or4', '');
			echo form_hidden('surg4', '');
			echo form_hidden('ordate4', '');
			echo form_hidden('or5', '');
			echo form_hidden('surg5', '');
			echo form_hidden('ordate5', '');			
		    echo form_hidden('orfind', '');
			echo form_hidden('mspecify', '');
			echo form_hidden('prepby', '');
		}

        echo "<hr/>";
        echo "<span>Brief History of Present Illness:</span>";
        echo "<table>";
	
        echo "<tr><td><textarea cols=\"150\" rows=\"7\" wrap=\"on\">";
//HPI
        if ($row->abstract)
            echo revert_form_input($eabstract[7]);
        echo "</textarea></td></tr></table>";
		echo "<hr/>";
//New Discharge PE	
		echo "<span>Physical Examination on Discharge:</span>";
		echo "<table>";
		echo "<tr><td  style=\"width:100px;\">General Survey</td><td>:</td><td colspan = 8><input style = \"width:600px; \" type=\"text\" name=\"gensurvey\" size=\"300\" value=\"";
		if ($row->dsummary)
			echo revert_form_input($edsummary[46]);
		echo "\"/></td></tr>";
		echo "<tr><td>Vital Signs</td><td style=\"width:2px;\">:</td><td style=\"width:60px;\">BP:<input style = \"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" type=\"text\" name=\"bp\" size=\"4\" value=\"";
		if ($row->dsummary)
			echo revert_form_input($edsummary[47]);		
		echo "\" /></td>";
		echo "<td style=\"width:60px;\">CR:<input style = \"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" type=\"text\" name=\"cr\" size=\"4\" value=\"";
		if ($row->dsummary)
			echo revert_form_input($edsummary[48]);			
		echo "\"/></td>";
		echo"<td style=\"width:60px;\">RR:<input style = \"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" type=\"text\" name=\"rr\" size=\"4\" value=\"";
		if ($row->dsummary)
			echo revert_form_input($edsummary[49]);					
		echo "\"/></td>";
		echo "<td>Temperature:<input style = \"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\" type=\"text\" name=\"temp\" size=\"4\" value=\"";
		if ($row->dsummary)
			echo revert_form_input($edsummary[50]);		
		echo "\"/></td>";
		echo "<td>Abdomen</td><td>:<input type=\"text\" name=\"abdomen\" size=\"60\" value=\"";
		if ($row->dsummary)
			echo revert_form_input($edsummary[51]);				
		echo "\"/></td></tr>";
        echo "<tr><td>HEENT</td><td colspan = 5>:<input type=\"text\" name=\"heent\" size=\"60\" value=\"";
		if ($row->dsummary)
			echo revert_form_input($edsummary[52]);				
		echo "\"/></td><td>GU(IE)</td><td>:<input type=\"text\" name=\"gu\" size=\"60\" value=\"";
		if ($row->dsummary)
			echo revert_form_input($edsummary[53]);					
		echo "\"/></td></tr>";
        echo "<tr><td>Chest/Lungs</td><td colspan = 5>:<input type=\"text\" name=\"chest\" size=\"60\" value=\"";
		if ($row->dsummary)
			echo revert_form_input($edsummary[54]);				
		echo "\"/></td><td>Skin/Extremities</td><td>:<input type=\"text\" name=\"skin\" size=\"60\" value=\"";
		if ($row->dsummary)
			echo revert_form_input($edsummary[55]);			
		echo "\"/></td></tr>";
        echo "<tr><td>CVS</td><td colspan = 5>:<input type=\"text\" name=\"cvs\" size=\"60\" value=\"";
		if ($row->dsummary)
			echo revert_form_input($edsummary[56]);				
		echo "\"/></td><td>Neuro Examination</td><td>:<input type=\"text\" name=\"neuro\" size=\"60\" value=\"";
		if ($row->dsummary)
			echo revert_form_input($edsummary[57]);					
		echo "\"/></td></tr>";
        echo "</table>";
		echo "<hr/>";

//Course in Wards		
		echo "<span>Course in the WARD:</span>";
		echo "<table>";
        echo "<tr><td style=\"vertical-align:top\"><textarea name = \"cwards\" cols=\"120\" rows=\"6\" wrap=\"on\">";
		//Course in Wards
        if ($row->dsummary)
            echo revert_form_input($edsummary[38]);
        echo "</textarea></td></tr>";
        echo "</table>";
//Lab Findings
		echo "<hr/>";
		echo "<span>Pertinent Laboratory and Diagnostic Findings: (CBC, Urinalysis, Fecalysis, Biopsy, etc.)</span>";
		echo "<table>";
        echo "<tr><td style=\"vertical-align:top\"><textarea name = \"lfinding\" cols=\"120\" rows=\"8\" wrap=\"on\">";
        if ($row->dsummary)
            echo revert_form_input($edsummary[44]);
        echo "</textarea></td></tr>";
        echo "</table>";	
		
//Discharge Plans
		echo "<hr/>";
		echo "<span>Discharge Plans:</span>";
        echo "<table>";
        echo "<tr><td style=\"vertical-align:top\"><textarea name = \"displans\" cols=\"120\" rows=\"5\" wrap=\"on\">";
		//Discharge Plans
        if ($row->dsummary)
            echo revert_form_input($edsummary[41]);
        echo "</textarea></td></tr>";
        echo "</table>";
//Morbidity		
		echo "<hr/>";
        echo "<table>";
        echo "<tr><td>Disposition on Discharge:</td>";
		echo "<td>";

        if ($row->dsummary){
            if (!strcmp($edsummary[39], 'improve')){
                echo form_radio('morbid', 'improve', TRUE);
                echo "Improved</td><td>";
			}
			else{	
                echo form_radio('morbid', 'improve', FALSE);
                echo "Improved</td><td>";
            }
            if (!strcmp($edsummary[39], 'transferred')){
                echo form_radio('morbid', 'transferred', TRUE);
                echo "Transferred</td><td>";
			}
			else{	
                echo form_radio('morbid', 'transferred', FALSE);
                echo "Transferred</td><td>";
            } 
            if (!strcmp($edsummary[39], 'hama')){
                echo form_radio('morbid', 'hama', TRUE);
                echo "HAMA</td><td>";
			}
			else{	
                echo form_radio('morbid', 'hama', FALSE);
                echo "HAMA</td><td>";
            } 		
            if (!strcmp($edsummary[39], 'absconded')){
                echo form_radio('morbid', 'absconded', TRUE);
                echo "Absconded</td><td>";
			}
			else{	
                echo form_radio('morbid', 'absconded', FALSE);
                echo "Absconded</td><td>";
            } 	
            if (!strcmp($edsummary[39], 'expired')){
                echo form_radio('morbid', 'expired', TRUE);
                echo "Expired</td><td>";
			}
			else{	
                echo form_radio('morbid', 'expired', FALSE);
                echo "Expired</td>";
            } 						
        }
        else{
                echo form_radio('morbid', 'improve', FALSE);
                echo "Improved</td><td>";		
                echo form_radio('morbid', 'transferred', FALSE);
                echo "Transferred</td><td>";	
                echo form_radio('morbid', 'hama', FALSE);
                echo "HAMA</td><td>";
                echo form_radio('morbid', 'absconded', FALSE);
                echo "Absconded</td><td>";
                echo form_radio('morbid', 'expired', FALSE);
                echo "Expired</td>";
				
		}
/*		
        echo "<td>(Specify)<input style=\"border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid\" type=\"text\" name=\"mspecify\" size=\"60\" value=\"";
//Morbidity Specify
        if ($row->dsummary)
            echo revert_form_input($edsummary[40]);
        echo "\"/></td></tr>";
*/		
        echo "</tr></table>";
		echo "<hr/>";
		echo "<table>";
        echo "<tr><td>Prepared by:</td><td><input style=\"text-align:center\" type=\"text\" name=\"ric\" size=\"30\" value=\"";
/*		
//Prep By
        if ($row->dsummary)
            echo revert_form_input($edsummary[42]);
        echo "\"/></td><td style=\"width:50px\"</td><td ><input style=\"text-align:center\" type=\"text\" name=\"ric\" size=\"30\" value=\"";
*/		
//RIC
        if (!strcmp($my_service, "er"))
            $jr = $this->Resident_model->get_resident_name($row->pod_id); 
        else
            $jr = $this->Resident_model->get_resident_name($row->r_id); 
        foreach ($jr as $name)
            echo revert_form_input($name->r_name)." M.D.";
 
        echo "\"/></td>";
		echo "<td>Date Accomplished:</td><td><input style=\"text-align:center\" type=\"text\" name=\"dateacc\" size=\"30\" value=\"";
        if ($row->dsummary)
            echo revert_form_input($edsummary[45]);
        echo "\"/></td></tr>";
        echo "<tr><td></td><td style=\"width:400px; border-top-width:thin; border-top-color:black; border-top-style:solid;text-align:center;\">Physician-in-Charge</td><td></td><td style=\"width:200px; border-top-width:thin; border-top-color:black; border-top-style:solid;text-align:center;\"></td></tr>";
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
