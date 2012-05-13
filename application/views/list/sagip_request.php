<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Wed, 30 Nov 2011 03:47:05 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Sagip Buhay Request Form</title>
    <link rel="stylesheet" type="text/css" href="/medisys/css/abstract.css" />
      	<script type="text/javascript" src="/medisys/js/jquery.js"></script>
	<script type="text/javascript" src="/medisys/js/my_jscripts.js"></script>
    <script>
    function getTotal1(form)
    {
	 var a=form.cost1.value;
	 var b=form.numitem1.value;

     document.getElementById("comp1").innerHTML= "P" + (Math.round(a * b * 100))/100 + ".00";
     getTotal5(form);

    }
	function getTotal2(form)
    {
	 var a=form.cost2.value;
	 var b=form.numitem2.value;
     document.getElementById("comp2").innerHTML= "P" + (Math.round(a * b * 100))/100 + ".00";
     getTotal5(form);
    }
	function getTotal3(form)
    {
	 var a=form.cost3.value;
	 var b=form.numitem3.value;
     document.getElementById("comp3").innerHTML= "P" + (Math.round(a * b * 100))/100 + ".00";
     getTotal5(form);
    }
	function getTotal4(form)
    {
	 var a=form.cost4.value;
	 var b=form.numitem4.value;
     document.getElementById("comp4").innerHTML= "P" + (Math.round(a * b * 100))/100 + ".00";
     getTotal5(form);
    } 
	function getTotal5(form)
    {
	 var a=form.cost1.value;
	 var b=form.numitem1.value;
	 var c=form.cost2.value;
	 var d=form.numitem2.value;
	 var e=form.cost3.value;
	 var f=form.numitem3.value;
	 var g=form.cost4.value;
	 var h=form.numitem4.value;
     document.getElementById("comp5").innerHTML= "P" + ((Math.round(a * b * 100) + Math.round(c * d * 100) + Math.round(e * f * 100) + Math.round(g * h * 100))/100) + ".00";
    } 
    </script>
	<script type="text/javascript">
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

if (!strcmp($one_gm, "y"))  
    echo form_open('show/update_sagip');
    foreach ($p_admission as $row){
        if ($row->sagip)
            $esagip = explode(",", $row->sagip);   
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
        echo "<div class = \"main_frame\">";
        echo "<table>";
        echo "<tr><th><div style=\"float:left\"><img src=\"/medisys/img/pghlogo.bmp\" style=\"width:80px; height:80px; \" /></div></th><th style=\"text-align:center; width:450px; \">UNIVERSITY OF THE PHILIPPINES<br/>PHILIPPINE GENERAL HOSPITAL-COLLEGE OF MEDICINE<br/>DEPARTMENT OF MEDICINE<br /><font size=3>SAGIP BUHAY REQUEST FOR MEDICAL ASSISTANCE</font></th><th><div style=\"float:right;\"><img src=\"\medisys\img\dept_logo.bmp\" style=\"width:80px; height:80px\" /></div></th></tr>";
        echo "</table>"; 
        echo "<table style=\"text-align:left;\"><tr><th style=\"width:500px\">Date: ".mdate("%M-%d-%Y")."</th><th>RIC: ";
//RIC
        if (!strcmp($my_service, "er"))
            $jr = $this->Resident_model->get_resident_name($row->pod_id);  
        else
            $jr = $this->Resident_model->get_resident_name($row->r_id); 
        foreach ($jr as $name)
	       echo revert_form_input($name->r_name)." M.D.";
        echo "</th></tr>";
//service, patient name, age/sex and cnum
        echo "<tr><th>Service: Gen Med ".$row->service."</th><th></th></tr>";
        echo "<tr><th>Patient's name: ".revert_form_input($pname)."</th><th>age: ".$age." sex: ".revert_form_input($psex)."</th></tr>";
        echo "<tr><th>Case number: ".revert_form_input($cnum)."</th><th>date admitted: ".revert_form_input($row->date_in)."</th></tr>";
        echo "<tr><th>Ward/Bed: ";
        if (!strcmp($my_service, 'micu'))
            echo "MICU";
        elseif (!strcmp($my_service, 'er'))
            echo "ER";
        else
            echo $row->location;
        if (strcmp($my_service, 'er'))
            echo " Bed".revert_form_input($row->bed);
        echo "</th></table>";
        echo "<div style=\"text-align:center; text-decoration:underline;\"><font size=4 >Medical Profile</font></div>";
        echo "<div style=\"vertical-align:top;\">Present problem list:</div>";
//Plist
        echo "<textarea cols=\"80\" rows=\"18\" readonly=\"readonly\"  >".revert_form_input($row->plist)."</textarea><br />"; 
        echo "<div style=\"text-align:center; text-decoration:underline;\"><font size=4 >Socio-economic profile</font></div>";
        echo "<table style=\"text-align:left;\">";
        echo "<tr><th>Occupation: ";
//Occupation
        if ($row->sagip)
 	        echo "<input type=\"text\" name = \"occupation\" style=\"width:300px\" value = \"".revert_form_input($esagip[0])."\"/>";
        else
            echo "<input type=\"text\" name = \"occupation\" style=\"width:300px\" value = \"\"/>";
        echo "</th></tr>";
        echo "<tr><th>MSS classification: ";
//MSS Class
        if ($row->sagip){
            if (!strcmp($esagip[1], 'A'))
                echo form_checkbox('mss', 'A', TRUE)."Class A";
            else
  	            echo form_checkbox('mss', 'A', FALSE)."Class A";
	  
            if (!strcmp($esagip[1], 'B'))
                echo form_checkbox('mss', 'B', TRUE)."Class B";
            else
  	            echo form_checkbox('mss', 'B', FALSE)."Class B";	
	  
            if (!strcmp($esagip[1], 'C'))
                echo form_checkbox('mss', 'C', TRUE)."Class C";
            else
  	            echo form_checkbox('mss', 'C', FALSE)."Class C";	

            if (!strcmp($esagip[1], 'D'))
                echo form_checkbox('mss', 'D', TRUE)."Class D";
            else
  	            echo form_checkbox('mss', 'D', FALSE)."Class D";	    
        }
        else{
                echo form_checkbox('mss', 'A', FALSE)."Class A";
                echo form_checkbox('mss', 'B', FALSE)."Class B";
                echo form_checkbox('mss', 'C', FALSE)."Class C";
                echo form_checkbox('mss', 'D', FALSE)."Class D";
 
        }
        echo "</th></tr>";
        echo "<tr><th>Number of people in the household: ";
//Household
        if ($row->sagip)
 	        echo "<input type=\"text\" name = \"houspip\" style=\"width:100px\" value = \"".revert_form_input($esagip[2])."\"/>";
        else
            echo "<input type=\"text\" name = \"houspip\" style=\"width:100px\" value = \"\"/>";
        echo "</th></tr>";
        echo "<tr><th>Number of dependents in the household: ";
//Dependents
        if ($row->sagip)
 	        echo "<input type=\"text\" name = \"housdep\" style=\"width:100px\" value = \"".revert_form_input($esagip[3])."\"/>";
        else
            echo "<input type=\"text\" name = \"housdep\" style=\"width:100px\" value = \"\"/>";
        echo "</th></tr>";
        echo "</table>";
 
        echo "<div style=\"text-align:center; text-decoration:underline;\"><font size=4 >Request for Drugs/ Procedure</font></div>";
        echo "<table>";
        echo "<tr><th>Drugs/procedure requested</th><th>cost per unit</th><th># of units</th><th>total cost</th><th>*</th></tr>";
        echo "<tr><td>";
//1st item
        if ($row->sagip)
            echo "<input type=\"text\" name=\"req1\" style=\"width:300px\" value=\"".revert_form_input($esagip[4])."\"";
        else
            echo "<input type=\"text\" name=\"req1\" style=\"width:300px\"";
        echo "></td><td>";
        if ($row->sagip)
            echo "<input type=\"text\" name=\"cost1\" style=\"width:100px\" value=\"".revert_form_input($esagip[5])."\"";
        else
            echo "<input type=\"text\" name=\"cost1\" style=\"width:100px\"";
        echo "onkeyup = \"getTotal1(this.form)\"></td><td>";
        if ($row->sagip)
            echo "<input type=\"text\" name=\"numitem1\" style=\"width:100px\" value=\"".revert_form_input($esagip[6])."\"";
        else
            echo "<input type=\"text\" name=\"numitem1\" style=\"width:100px\"";
        echo "onkeyup = \"getTotal1(this.form)\"></td>";
        echo "<td id = \"comp1\"></td></tr>";
        echo "<tr><td>";
//2nd item 
        if ($row->sagip)
            echo "<input type=\"text\" name=\"req2\" style=\"width:300px\" value=\"".revert_form_input($esagip[7])."\">";
        else
            echo "<input type=\"text\" name=\"req2\" style=\"width:300px\">";
        echo "</td><td>";
        if ($row->sagip)
            echo "<input type=\"text\" name=\"cost2\" style=\"width:100px\" value=\"".revert_form_input($esagip[8])."\"";
        else
            echo "<input type=\"text\" name=\"cost2\" style=\"width:100px\"";
        echo "onkeyup = \"getTotal2(this.form)\"></td><td>";
        if ($row->sagip)
            echo "<input type=\"text\" name=\"numitem2\" style=\"width:100px\" value=\"".revert_form_input($esagip[9])."\"";
        else
            echo "<input type=\"text\" name=\"numitem2\" style=\"width:100px\"";
        echo "onkeyup = \"getTotal2(this.form)\"></td>";
        echo "<td id = \"comp2\"></td></tr>";
        echo "<tr><td>";
//3rd item
        if ($row->sagip)
            echo "<input type=\"text\" name=\"req3\" style=\"width:300px\" value=\"".revert_form_input($esagip[10])."\">";
        else
            echo "<input type=\"text\" name=\"req3\" style=\"width:300px\">";
        echo "</td><td>";
        if ($row->sagip)
            echo "<input type=\"text\" name=\"cost3\" style=\"width:100px\" value=\"".revert_form_input($esagip[11])."\"";
        else
            echo "<input type=\"text\" name=\"cost3\" style=\"width:100px\"";
        echo "onkeyup = \"getTotal3(this.form)\"></td><td>";
        if ($row->sagip)
            echo "<input type=\"text\" name=\"numitem3\" style=\"width:100px\" value=\"".revert_form_input($esagip[12])."\"";
        else
            echo "<input type=\"text\" name=\"numitem3\" style=\"width:100px\"";
        echo "onkeyup = \"getTotal3(this.form)\"></td>";
        echo "<td id = \"comp3\"></td></tr>";
        echo "<tr><td>";
//4th item
        if ($row->sagip)
            echo "<input type=\"text\" name=\"req4\" style=\"width:300px\" value=\"".revert_form_input($esagip[13])."\">";
        else
            echo "<input type=\"text\" name=\"req4\" style=\"width:300px\">";
        echo "</td><td>";
        if ($row->sagip)
            echo "<input type=\"text\" name=\"cost4\" style=\"width:100px\" value=\"".revert_form_input($esagip[14])."\"";
        else
            echo "<input type=\"text\" name=\"cost4\" style=\"width:100px\"";
        echo "onkeyup = \"getTotal4(this.form)\"></td><td>";
        if ($row->sagip)
            echo "<input type=\"text\" name=\"numitem4\" style=\"width:100px\" value=\"".revert_form_input($esagip[15])."\"";
        else
            echo "<input type=\"text\" name=\"numitem4\" style=\"width:100px\"";
        echo "onkeyup = \"getTotal4(this.form)\"></td>";
        echo "<td id = \"comp4\"></td></tr>";
        echo "<tr><td colspan = \"3\"></td><td id = \"comp5\" ></td></tr>";
        echo "</table>";
        echo "<br />";

        echo "<div style=\"float:right;\"><table><th>Signed:</th><td style=\"border-top-color:black; border-top-style:solid;\">";
        if (strcmp($my_service, 'er')){
            $sr = $this->Resident_model->get_resident_name($row->sr_id); 
            foreach ($sr as $name)
    	       echo $name->r_name." M.D.";
            echo " / ";
        }
        if (!strcmp($my_service, "er"))
            $jr = $this->Resident_model->get_resident_name($row->pod_id);
        else
            $jr = $this->Resident_model->get_resident_name($row->r_id); 
        foreach ($jr as $name)
	       echo revert_form_input($name->r_name)." M.D.";
        echo "</td></table></div>";
        echo "<div style=\"float:left;\"><table>";
        echo "<tr><td>Status of request:</td></tr>";
        echo "<tr><td>".form_checkbox('', '')."approved ".form_checkbox('','')."disapproved, reason:</td></tr>";
        echo "<tr><td style=\"border-bottom:solid; border-bottom-color:black\"><br /><br /></td></tr>";
        echo "<tr><td>Chief Resident</td></tr>";
        echo "</table></div>";
        echo "<div style=\"clear:both;\">";
        echo "<table><tr><td>Outcome of patient</td></tr>";
        echo "<tr><td>".form_checkbox('','')."Discharged</td><td>".form_checkbox('','')."HAA/absconded</td><td>".form_checkbox('','')."died</td></tr></table>";
    if (!strcmp($one_gm, "y")){  
        $label = array('value'=>"***");
        make_buttons($my_service, $label, $vars, "left", "");
        echo form_close();
    }    
        echo "</div>";
}  
    echo "</div>";
?>
