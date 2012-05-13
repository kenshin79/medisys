<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Wed, 30 Nov 2011 03:47:05 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Clinical Abstract</title>
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
    input{font-size:80%; font-family:"Times New Roman", Times, serif; border-style:none;}
    textarea{font-size:80%; font-family:"Times New Roman", Times, serif; border-style:none;}	
    td{font-size:95%}	
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
  				
/* remove for now
  if (!strcmp($one_gm, "y")){
  		$my_date1 = $this->session->userdata('my_date1');
        $my_date2 = $this->session->userdata('my_date2');
  }      
*/


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
    echo form_open('show/update_abstract');  
    foreach ($p_admission as $row){
        if (!strcmp($my_service, 'micu'))
            echo form_hidden('eadmission', $row->micu_id);
        elseif (!strcmp($my_service, 'er'))
            echo form_hidden('eadmission', $row->er_id);
        else
            echo form_hidden('eadmission', $row->a_id);
        if ($row->abstract)
            $eabstract = explode(",",$row->abstract); 
    echo "<div>";        
    
        echo "<div class = \"main_frame\" >";    
        echo "<table rules=rows frame=box style=\"border-color:black\">";
        echo "<tr><th></div><div style=\"float:left\"><img src=\"/medisys/img/pghlabel.jpg\" style=\"width:400px; height:75px\" /></div><div style=\"float:right;\"><img src=\"/medisys/img/pghfarright.jpg\" style=\"width:200px; height:75px\" /></div></th></tr>";
        echo "<tr><th style=\"width:800px\"><div align= \"center\" style=\"text-align:center\"><font size=5>CLINICAL ABSTRACT</div></th></tr>";
        echo "</table>";

        echo "<table rules=rows frame=box style=\"border-color:black\">";
        echo "<tr>";
        echo "<td><table rules=cols frame=vsides style=\"border-color:black\" ><tr><th style=\"text-align:left;vertical-align:top;width:400px\">Name of Hospital/ Ambulatory Clinic<br />Philippine General Hospital<br /><br /></th></tr></table></td>";
        echo "<td><table>";
        echo "<tr><th style=\"text-align:left; width:400px; border-bottom-color:black; border-bottom-style:solid; border-bottom-width:thin;\">Case No.: ";
        $pdata = $this->Patient_model->get_one_patient($row->p_id);
//cnum
	    foreach ($pdata as $patient)    
            echo revert_form_input($patient->cnum);
        echo "</th></tr>";
        echo "<tr><th style=\"text-align:left;width:400px; border-bottom-color:black; border-bottom-style:solid; border-bottom-width:thin;\">Admission:</th></tr>";
        echo "<tr><th style=\"text-align:left;width:400px; border-bottom-color:black; border-bottom-style:solid; border-bottom-width:thin;\">Date : ".$row->date_in." \t Time:";
//time
        if ($row->abstract)
            echo form_input('a_time', revert_form_input($eabstract[0]));
        else
            echo form_input('a_time', '');
        echo "am/pm</th></tr>";
        echo "<tr><th style=\"text-align:left;width:400px; \">Accreditation No.:</th></tr>";
        echo "</table></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<table rules=cols frame=vsides style=\"border-color:black\">";
        echo "<tr><th style=\"text-align:left;width:400px\">Address of Hospital/ Ambulatory Clinic:<br />No. Street: Taft Avenue </th>";
        echo "<th style=\"vertical-align:top;width:400px; text-align:left;\">Barangay</th>";
        echo "</tr>";
        echo "</table>";
        echo "</tr>";

        echo "<tr>";
        echo "<table rules=rows frame=box style=\"border-color:black\">";
        echo "<tr>";
        echo "<td><table><tr><th style=\"width:300px; text-align:left;\">Municipality/ City<br />Manila</th><th style=\"width:300px; text-align:left;\">Province<br />NCR</th><th style=\"text-align:left;width:200px\">Zip Code<br />1000</th></tr></table></td>";
        echo "</tr>";
        echo "</table>";
        echo "</tr>";

        echo "<tr>";
        echo "<table  rules=rows frame=box style=\"border-color:black\">";
        echo "<tr>";
        echo "<td style=\"width:800px\"><div style=\"text-align:center\"><font size = 3><b>PATIENT'S CLINICAL RECORD</b></font></div></td>";
        echo "</tr>";
        echo "</table>";
        echo "</tr>";

        echo "<tr>";
        echo "<table rules=rows frame=box style=\"border-color:black\">";
        echo "<tr><th style=\"text-align:left;width:800px;\">1. Patient Name(Last Name) ";
	    foreach ($pdata as $patient){    
            $age = compute_age_adm($row->date_in, $patient->p_bday);
            $pname = $patient->p_name;
        }    
//lastname of patient
        if ($row->abstract){
            if ($eabstract[1])
                echo form_input('plname',revert_form_input($eabstract[1]));
            else
                echo form_input('plname', revert_form_input($pname));  
        }      
        else 
            echo form_input('plname', revert_form_input($pname));
//age            
        echo "2. Age: ".form_input('',revert_form_input($age))."3. Sex: ";
        
//sex check box
        foreach ($pdata as $patient){    
            if (!strcmp($patient->p_sex, 'M')){
                echo form_checkbox('', '', TRUE)."Male ";
                echo form_checkbox('', '', FALSE)."Female";
            }
            else
            { 
                echo form_checkbox('', '', FALSE)."Male ";
                echo form_checkbox('', '', TRUE)."Female";
            }
        }    
        echo "</th></tr>";
        echo "</table>";
        echo "</tr>";

        echo "<tr>";
        echo "<table rules=rows frame=box style=\"border-color:black\">";
        echo "<tr><th style=\"text-align:left;width:400px;border-top-style:solid; border-top-color:black; border-top-width:thin;\">First Name ";
//patient fname
        if ($row->abstract)
            echo form_input('pfname', revert_form_input($eabstract[2]));
        else 
            echo form_input('pfname', '');

        echo "</th><th style=\"text-align:left;width:400px; border-left-style:solid; border-left-color:black; border-left-width:thin;border-top-style:solid; border-top-color:black; border-top-width:thin;\">4.</br></br><div align=\"center\">";
//resident in charge
        if (!strcmp($my_service, "er"))
            $jr = $this->Resident_model->get_resident_name($row->pod_id); 
        else 
            $jr = $this->Resident_model->get_resident_name($row->r_id); 
		foreach ($jr as $name)
		    echo revert_form_input($name->r_name)." M.D.";
        echo "</div></th></tr>";
        echo "<tr><th style=\"text-align:left;width:400px;border-top-style:solid; border-top-color:black; border-top-width:thin;\">Middle Name ";
//middlename of patient
        if ($row->abstract)
            echo form_input('pmname', revert_form_input($eabstract[3]));
        else
            echo form_input('pmname', '');
        echo "</th><th style=\"text-align:center;width:400px; border-left-style:solid; border-left-color:black; border-left-width:thin;border-top-style:solid; border-top-color:black; border-top-width:thin;\">Printed Name and Signature of Admitting Officer</th></tr>";
        echo "</table>";
        echo "</tr>";

        echo "<tr>";
        echo "<table style=\"border-color:black; border-style:solid; border-width:thin;\">";
        echo "<tr><th style=\"text-align:left;width:800px; border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\">5. Admitting Diagnosis:<br/><textarea  name=\"admdx\" rows = \"10\" cols = \"190\" wrap = \"on\" >";
//diagnosis-plist
        if ($row->abstract)
            echo revert_form_input($eabstract[4]);
        else 
            echo revert_form_input($row->plist);
        echo "</textarea></th></tr>";
        echo "<tr><th style=\"text-align:left;; border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\">6. Chief Complaint ";
//chief complaint
        if ($row->abstract)
            echo form_input('cc', revert_form_input($eabstract[5]));
        else
            echo form_input('cc', '');
        echo "</th></tr>";
        echo "<tr><th style=\"text-align:left;; border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\">7. Reason for Admission ";
//reason for admission
        if ($row->abstract)
            echo form_input('reason', revert_form_input($eabstract[6]));
        else
            echo form_input('reason', '');
        echo "</th></tr>";
        echo "<tr><th style=\"text-align:left;; \">8. Brief History of Present Illness/OB History <br/><textarea  style=\"font-size:10px;\" name=\"history\" rows = \"24\" cols = \"190\" wrap = \"on\" >";
//HPI
        if ($row->abstract)
            echo revert_form_input($eabstract[7]);
        echo "</textarea></th></tr>";
        echo "</table>";
        echo "</tr>";

        echo "<tr>";
        echo "<table style=\"border-color:black; border-style:solid; border-width:thin; font-size:10px\">";
        echo "<tr><th style=\"text-align:left;;\">9. Physical Examination (Pertinent Findings per System)</th></tr>";
//general survey
        echo "<tr><th style=\"text-align:left;width:800px;\">General Survey:<input type=\"text\" name=\"survey\" value=\"";
        if ($row->abstract)
            echo revert_form_input($eabstract[8]);
        echo "\" size=130\"></th></tr>";
        echo "<tr><th style=\"text-align:left;width:800px;\">Vital Signs: BP: <input type=\"text\" name=\"bp\" value=\"";
//BP
        if ($row->abstract)
            echo revert_form_input($eabstract[9]);
        echo "\" size=10\"> HR: <input type=\"text\" name=\"hr\" value=\"";
//HR
        if ($row->abstract)
            echo revert_form_input($eabstract[10]);
        echo "\" size=6\"> RR: <input type=\"text\" name=\"rr\" value=\"";
//RR
        if ($row->abstract)
            echo revert_form_input($eabstract[11]);
        echo "\" size=10\"> TEMPERATURE: <input type=\"text\" name=\"temp\" value=\"";
//Temp
        if ($row->abstract)
            echo revert_form_input($eabstract[12]);
        echo "\" size=10\"></th></tr>";
        echo "<tr><th style=\"text-align:left;width:800px;\">HEENT: <input type=\"text\" name=\"heente\" value=\"";
//HEENT
        if ($row->abstract)
            echo revert_form_input($eabstract[13]);
        echo "\" size=160\"></th></tr>";
        echo "<tr><th style=\"text-align:left;width:800px;\">Chest/Lungs: <input type=\"text\" name=\"cheste\" value=\"";
//CHEST
        if ($row->abstract)
            echo revert_form_input($eabstract[14]);
        echo "\" size=160\"></th></tr>";
        echo "<tr><th style=\"text-align:left;width:800px;\">CVS: <input type=\"text\" name=\"cvse\" value=\"";
//CVS
        if ($row->abstract)
            echo revert_form_input($eabstract[15]);
        echo "\" size=160\"></th></tr>";
        echo "<tr><th style=\"text-align:left;width:800px;\">Abdomen: <input type=\"text\" name=\"abdomene\" value=\"";
//Abdomen
        if ($row->abstract)
            echo revert_form_input($eabstract[16]);
        echo "\" size=160\"></th></tr>";
        echo "<tr><th style=\"text-align:left;width:800px;\">GU(IE): <input type=\"text\" name=\"gue\" value=\"";
//GU-IE
        if ($row->abstract)
            echo revert_form_input($eabstract[17]);
        echo "\" size=160\"></th></tr>";
        echo "<tr><th style=\"text-align:left;width:800px;\">Skin/Extremities: <input type=\"text\" name=\"skine\" value=\"";
//Skin
        if ($row->abstract)
            echo revert_form_input($eabstract[18]);
        echo "\" size=160\"></th></tr>";
        echo "<tr><th style=\"text-align:left;width:800px;\">Neuro Examination: <input type=\"text\" name=\"neuroe\" value=\"";
//Neuro Exam
        if ($row->abstract)
            echo revert_form_input($eabstract[19]);
        echo "\" size=160\"></th></tr>";
        echo "</table>";
        echo "</tr>";
        echo "</table>";
        echo "</div>";
//second page        
        echo "<div class = \"main_frame\">";
        echo "<div id = \"addbreaks\"></div>";
        echo "<table>";
        echo "<tr>";
        echo "<table style=\"border-color:black; border-style:solid; border-width:thin;\">";
        echo "<tr><th style=\"text-align:left;width:800px;border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\">10. Course in the Wards:<br/>";
        echo "<textarea  name=\"cwards\" rows = \"8\" cols = \"190\" wrap = \"on\" >";


//Course in Wards
        if ($row->abstract)
            echo revert_form_input($eabstract[20]);
        echo "</textarea>";
        echo "</th></tr>";
        echo "<tr><th style=\"text-align:left;width:800px;border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\">11. Pertinent Laboratory and Pertinent Diagnostic Findings: (CBC, Urinalysis, Fecalysis, X-ray, Biopsy, etc.)<br/>";
        echo "<textarea  name=\"plabs\" rows = \"12\" cols = \"190\" wrap = \"on\" >";
//Labs
        if ($row->abstract)
            echo revert_form_input($eabstract[21]);
        echo "</textarea>";
        echo "</th></tr>";
        echo "</table>";

        echo "<table style=\"border-color:black; border-style:solid; border-width:thin;\">";
        echo "<tr><th style=\"text-align:left;width:450px; border-right-color:black; border-right-style:solid; border-right-width:thin;\">12. Surgical Operation: <input type=\"text\" name=\"surgop\" size=40 value=\"";
//Surgical Operations
        if ($row->abstract)
            echo revert_form_input($eabstract[22]);
        echo "\"> </th><th style=\"text-align:left;width:450px;\"><div align=\"center\"><input type=\"text\" name=\"surgeon\" size=30 value=\"";
//Surgeon
        if ($row->abstract)
            echo revert_form_input($eabstract[23]);
        echo "\">M.D.</div></th></tr>";
        echo "<tr><th style=\"text-align:left;width:450px;border-right-color:black; border-right-style:solid; border-right-width:thin;border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;\">Date:<input type=\"text\" name=\"dateop\" size=10 value=\"";
        if ($row->abstract)
            echo revert_form_input($eabstract[24]);
        echo "\"> Time:<input type=\"text\" name=\"timeop\" size=10 value=\"";
//Time of OR
        if ($row->abstract)
            echo revert_form_input($eabstract[25]);
        echo "\">AM/PM</th><th style=\"text-align:center;width:450px;border-top-width:thin; border-top-color:black; border-top-style:solid;\">Printed Name and Signature of Surgeon</th></tr>";
        echo "<tr><th style=\"text-align:left;width:450px;border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;border-right-color:black; border-right-style:solid; border-right-width:thin;>(Month/Day/Year)</th><th>Printed Name and Signature of Surgeon</th></tr>";
        echo "<tr><th style=\"text-align:left;width:450px;border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;border-right-color:black; border-right-style:solid; border-right-width:thin;\">Type of Anesthesia:";
        echo "<textarea  name=\"anestype\" rows = \"5\" cols = \"80\" wrap = \"on\" >";
//Anesthesia
        if ($row->abstract)
            echo revert_form_input($eabstract[28]);
        echo "</textarea>";
        echo "</th>";
        echo "<th style=\"text-align:center;width:450px;border-bottom-width:thin; border-bottom-color:black; border-bottom-style:solid;border-top-width:thin; border-top-color:black; border-top-style:solid; vertical-align:bottom;\"><input type=\"text\" name=\"anes\" size=30 value=\"";
//anesthesiologist
        if ($row->abstract)
            echo revert_form_input($eabstract[29]);
        echo "\">M.D.<br/>Printed Name and Signature of Anesthesiologist</th></tr>";
        echo "</table>";
        echo "</tr>";

        echo "<table style=\"border-color:black; border-style:solid; border-width:thin;\">";
        echo "<tr><th style=\"text-align:left;width:450px;\">13. Discharge</th><th style=\"text-align:left;width:450px;\"></th></tr>";
        echo "<tr><th style=\"text-align:left;width:450px;\">a. Date: <div align=\"center\"><input type=\"text\" size=10 value=\"$row->date_out\"></div></th><th style=\"text-align:left;width:450px;\">b. Time:<input type=\"text\" name=\"distime\" size=10 value=\"";
//discharge date
        if ($row->abstract)
            echo revert_form_input($eabstract[26]);
        echo "\"></th></tr>";
        echo "<tr><th>(Year/Month/Day)</th><th></th></tr>";
        echo "</table>";

        echo "<table style=\"border-color:black; border-style:solid; border-width:thin;\">";
        echo "<tr><th style=\"text-align:left;width:800px;\">c. Final Diagnosis:<br/>";
        //$cs_pcpdx = explode(",", $row->pcpdx);
        echo "<textarea rows = \"10\" cols = \"190\" wrap = \"on\" >";
        //foreach ($cs_pcpdx as $pcp)
	        echo revert_form_input($row->plist);
        echo "</textarea>";
        echo "</th></tr>";
        echo "<tr><th style=\"text-align:left;;\">d. Condition on Discharge:<br/>";
        echo "<textarea  name=\"d_condition\" rows = \"6\" cols = \"190\" wrap = \"on\" >";
//Discharge condition
        if ($row->abstract)
             echo revert_form_input($eabstract[27]);
        echo "</textarea>";
        echo "</th></tr>";
        echo "<tr><th style=\"text-align:left;width:800px;\"><br/><br/><br/>e. Signature of Attending Physician:</th></tr>";
        echo "</table>";


        echo "<table style=\"border-color:black; border-style:solid; border-width:thin;\">";
        echo "<tr><th style=\"text-align:left;width:450px;\">14. Signature or Right Thumbmark of patient or his/her Representative:<br/><textarea  rows = \"3\" cols = \"60\" wrap = \"on\" readonly=\"readonly\"></textarea></th><th style=\"text-align:left;width:450px;\"></th></tr>";
        echo "<tr><th style=\"text-align:left;width:450px;border-top-width:thin; border-top-color:black; border-top-style:solid;\">Printed Name and Signature of patient or his/her Representative</th><th style=\"text-align:left;width:450px;\"></th></tr>";
        echo "<tr><th><div align=\"center\"><img src=\"/medisys/img/box.JPG\" style=\"width:120px; height:110px\" /></div></th><th style=\"text-align:center;width:450px;\"></th></tr>";
        echo "<tr><th style=\"text-align:center;width:450px;\">Right Thumbmark<br />(In case patient and representative could not write)</th><th style=\"text-align:center;width:450px;border-top-width:thin; border-top-color:black; border-top-style:solid;\">Printed Name and Signature of Witness to Thumbmark</th></tr>";
        echo "</table>";
        echo "</table>";
}
    if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"******", 'class'=>'button');
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
    }    
    echo "</div>";
    echo "</div>";
?>
  </body>
</html>
