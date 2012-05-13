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
	<link rel="stylesheet" type="text/css" href="/medisys/css/main.css" />
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

echo "<a name=\"UP\"><div align=\"center\"><h2>Admissions Search Results</h2></div></a>";
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

//census count labels
if (!strcmp($my_service, 'er'))
    echo "<h1><div align=\"center\">ER Admissions: Total of ".$numrows." Admissions.</div></h1>";
elseif (!strcmp($my_service, 'micu'))
	echo "<h1><div align=\"center\">MICU Admissions: Total of ".$numrows." Admissions.</div></h1>";
elseif (!strcmp($my_service, "preop"))
	echo "<h1><div align=\"center\">Pre-operative Admissions: Total of ".$numrows." Admissions.</div></h1>";
else
{
 	echo "<h1><div align=\"center\">Gen Med Svc '".$my_service."'</div>";
	echo "<h1><div align=\"center\">(".$num_prim.")<a href=\"#primary\">Primary and (".$num_comx.")Co-Managed</a> and <a href=\"#preop\"> , (".$num_er.")<a href=\"#er\">ER</a> and (".$num_micu.")<a href=\"#micu\">MICU Admissions</a></div></h1>";
}
echo "<div align=\"center\"><b>(as of ".date("M-d-Y").")</b></div>";

//list of admissions
//main display area
echo "<div id=\"main_display\">";
if ($c_admissions){
	echo "<div align = \"center\"><h3><a name=\"primary\">Service Patients</a><a href=\"#UP\">[UP]</a></h3></div>";
	echo "<div align = \"center\"><table><tr><th>No.</th>";
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
	echo "<th>Select</th></tr>";
	
    if (strcmp($my_service, 'micu') && strcmp($my_service, 'er'))
    {
		$x =1;
        	foreach ($c_admissions as $row){
		    $eadmission = $row->a_id;
			if (!strcmp($my_service, "preop"))
			{
				echo "<tr>";
				$pdata = $this->Patient_model->get_one_patient($row->p_id);
				foreach ($pdata as $patient)
					make_num_column(0, $x, $patient->cnum);
				make_location_column($row->location, $row->bed);
				make_type_column($row->type);
	      			foreach ($pdata as $patient)
	      				make_patient_column($patient->p_name);
    				$jr = $this->Resident_model->get_resident_name($row->r_id);	
				foreach ($jr as $rdata)
					make_ric_column($rdata->r_name);
    				$hd = compute_hd($row->dispo, $row->date_in, $row->date_out);
				    make_hd_column($hd);
					echo "<td><button type=\"button\" onclick = \"getAdm('".$x."', '".$eadmission."', '".$my_service."', '".$my_dispo."', '".$one_gm."', '".$stp1."')\"> Show</button></td>";
					echo "</tr>";
                    echo "<tr><td colspan=\"7\" id=\"selected_adm".$x."\"></td></tr>";

    				$x++;
			}
			else{
				if (!strcmp($row->type, 'Primary') || !strcmp($row->type, 'Co-Managed'))
				{
					echo "<tr>";
					$pdata = $this->Patient_model->get_one_patient($row->p_id);
					foreach ($pdata as $patient)
						make_num_column(0, $x, $patient->cnum);
					make_location_column($row->location, $row->bed);
					make_type_column($row->type);
	      				foreach ($pdata as $patient)
	      					make_patient_column($patient->p_name);
    					$jr = $this->Resident_model->get_resident_name($row->r_id);	
					foreach ($jr as $rdata)
						make_ric_column($rdata->r_name);
    					make_sic_column($row->sic);
    					$hd = compute_hd($row->dispo, $row->date_in, $row->date_out);
					make_hd_column($hd);
					echo "<td><button type=\"button\" onclick = \"getAdm('".$x."', '".$eadmission."', '".$my_service."', '".$my_dispo."', '".$one_gm."', '".$stp1."')\"> Show</button></td>";
					echo "</tr>";
                    echo "<tr><td colspan=\"8\" id=\"selected_adm".$x."\"></td></tr>";
  					$x++;
			 }
                         }
		}
		echo "</table>";
		echo "<span id=\"selected_adm\"></span>";
		echo "<div align = \"center\"><h3><a name=\"micu\">MICU Patients</a><a href=\"#UP\">[UP]</a></h3></div>";
		echo "<div align = \"center\"><table><tr><th><a name=\"UP\">No.</a></th>";
		echo "<th>Location</th>";
		echo "<th>Patient Name</th><th>RIC</th><th>SIC</th>";
		echo "<th>GM Svc</th>";
		echo "<th>Hosp Days</th></tr>";	
	 if (strcmp($my_service, "preop")){
		$x =1;
		foreach ($c_micu as $row){
			echo "<tr>";
			$pdata = $this->Patient_model->get_one_patient($row->p_id);
			make_num_column(2, $x, "");
			make_micubed_column($row->bed);
	      	foreach ($pdata as $patient)
	      			make_patient_column($patient->p_name);
    		$jr = $this->Resident_model->get_resident_name($row->r_id);	
			foreach ($jr as $rdata)
			make_ric_column($rdata->r_name);
  			make_sic_column($row->sic);
    			make_gm_column($row->service);
    			$hd = compute_hd($row->dispo, $row->date_in, $row->date_out);
    			make_hd_column($hd);
			echo "</tr>";
    			$x++;
		}
		echo "</table>";
		echo "<div align = \"center\"><h3><a name=\"er\">ER Patients</a><a href=\"#UP\">[UP]</a></h3></div>";
		echo "<div align = \"center\"><table><tr><th><a name=\"UP\">No.</a></th>";
		echo "<th>Patient Name</th><th>ER-POD</th>";
		echo "<th>GM Svc</th>";
		echo "<th>Hosp Days</th></tr>";
		$x =1;
		foreach ($c_er as $row){
			echo "<tr>";
			make_num_column(2, $x, "");
			$pdata = $this->Patient_model->get_one_patient($row->p_id);
	      		foreach ($pdata as $patient)
	      			make_patient_column($patient->p_name);
    		$jr = $this->Resident_model->get_resident_name($row->pod_id);	
			foreach ($jr as $rdata)
			make_ric_column($rdata->r_name);
  			make_gm_column($row->service);
    			$hd = compute_hd($row->dispo, $row->date_in, $row->date_out);
    			make_hd_column($hd);
			echo "</tr>";
    			$x++;
    		}
		echo "</table>";
		echo "<br/>";

	  }

}
 else 
	 {
		$x =1;
		foreach ($c_admissions as $row){
		    if (!strcmp($my_service, 'er'))
		        $eadmission = $row->er_id;
		    else
		        $eadmission = $row->micu_id;
			$pdata = $this->Patient_model->get_one_patient($row->p_id);
	      		foreach ($pdata as $patient)
				make_num_column(0, $x, $patient->cnum);
			if (!strcmp($my_service, 'micu'))
				make_micubed_column($row->bed);
  			foreach ($pdata as $patient)
	      			make_patient_column($patient->p_name);
		        if (!strcmp($my_service, 'micu')) 
    				$jr = $this->Resident_model->get_resident_name($row->r_id);

			else
				$jr = $this->Resident_model->get_resident_name($row->pod_id);	
			foreach ($jr as $rdata)
				make_ric_column($rdata->r_name);
    			if (!strcmp($my_service, 'micu'))
         			make_sic_column($row->sic);
    			make_gm_column($row->service);
    			$hd = compute_hd($row->dispo, $row->date_in, $row->date_out);
    			make_hd_column($hd);
    			echo "<td><button type=\"button\" onclick = \"getAdm('".$x."', '".$eadmission."', '".$my_service."', '".$my_dispo."', '".$one_gm."', '".$stp1."')\"> Show</button></td>";
				echo "</tr>";
                echo "<tr><td colspan=\"8\" id=\"selected_adm".$x."\"></td></tr>";
    			$x++;
		 } 			
		 echo "</table>";
		 echo "<br/>";
	   }
	 		

}
//main display area
echo "</div>";

/*
//make patients list
  if ($c_admissions){
      $x = 1;
      echo "<div align=\"center\" style = \"clear:left;\"><table id=\"census_table\" >";
      echo "<tr><th>Admission Data</th><th>Problem List and Medications</th><th>In-Referral</th><th>Out-Referral</th><th>Modify</th></tr>";	
      foreach($c_admissions as $row){  
	      $hd = compute_hd($row->dispo, $row->date_in, $row->date_out);
	      echo form_open('show/edit_admission'); 
          echo "<tr><td>";
//contents of general data column	
	      echo "<table>";
	      $pdata = $this->Patient_model->get_one_patient($row->p_id);
	      foreach ($pdata as $patient)
	      {
		      $age = compute_age_adm($row->date_in, $patient->p_bday);
		      $pname = $patient->p_name;
		      if (strcmp($my_service, 'er') && strcmp($my_service, 'micu'))
	              		make_case_num_row($my_service, $x, $row->type, $patient->cnum);
	      	  else
                      	make_case_num_row($my_service, $x, "", $patient->cnum);
		      make_pname_row($pname);	
	      	  make_agesex_row($age, $patient->p_sex);
	      }
	      if (strcmp($my_service, 'er') && strcmp($my_service, 'micu'))
	      	      make_type_row(1, $my_service, $row->type, $type_list);
	      make_status_row(1, $my_service, $row->dispo, $dispo_list);	
	      if (strcmp($my_service, 'er'))
		      make_source_row(1, $my_service, $row->source, $loc_list);
	      make_gmservice_row(1, $row->service, $service_list);
	      if (strcmp($my_service, 'er')) 
	      {
	 	      if (!strcmp($my_service, 'micu'))
			      make_micu_location_row(1, $row->bed, $loc_list, $mbed_list);	
                      else
			      make_ward_location_row(1, $row->location, $row->bed, $loc_list, $bed_list);
              }	 
	          $residents = $this->Resident_model->get_activer();        
              if (!strcmp($my_service, 'er'))
              {
		      $pod = $this->Resident_model->get_resident_name($row->pod_id); 
	      	      make_pod_row(1, $row->pod_id, $pod, $residents); 		
              }
              else   
              {
		      $sr = $this->Resident_model->get_resident_name($row->sr_id); 
		      $jr = $this->Resident_model->get_resident_name($row->r_id);
		      make_sric_row(1, $row->sr_id, $sr, $residents);
		      make_jric_row(1, $row->r_id, $jr, $residents);
		      if (strcmp($my_service, "preop"))	
                      		make_sic_row(1, $row->sic);
              }
	      make_datein_row(1, $row->date_in, $hd);
	      make_dateout_row(1, $row->date_out);
 
	      echo "</table>";
//end of gendata column
//start of plist column
          echo "<td><table class = \"p_list\">";
          make_plist_row(1, $row->plist);
          make_meds_row(1, $row->meds);
          make_pcpdx_row($row->pcpdx);
          make_notes($row->notes);
          echo "</table></td>";  
//end of plist column
//start of refs and erefs column
	//refs
	      echo "<td class=\"refs\">";
	      $def_cb = array('name'=>'refs[]', 'readonly'=>'readonly', 'checked'=>'TRUE'); 
	      $ref_array = $this->config->item('ref_list');
	      make_refs_row($my_service, $def_cb, $ref_array, $row->refs); 
	      echo "</td>";
        //erefs
	      echo "<td class=\"refs\">";
	      $def_cb1 = array('name'=>'erefs[]', 'readonly'=>'readonly', 'checked'=>'TRUE');
	      $eref_array = $this->config->item('eref_list');
	      make_erefs_row($def_cb1, $eref_array, $row->erefs);
	      echo "</td>";
	
//need to post patient and admission id, pcpdx, session var
	      if (!strcmp($my_service, 'er'))
	      	    $dvars = array('eadmission'=>$row->er_id,);
	      elseif (!strcmp($my_service, 'micu'))
		        $dvars = array('eadmission'=>$row->micu_id,);	
	      else
		        $dvars = array('eadmission'=>$row->a_id,);	
		 //other hidden variables for this admission       
	     $dvars['p_id']= $row->p_id;
	     $dvars['my_service']= $my_service;
	     $dvars['my_dispo']= $my_dispo;
	     $dvars['one_gm']= $one_gm;
     	 $dvars['stp1'] = $stp1;
     	 $dvars['p_id'] = $row->p_id;
//start of buttons column	
	     echo "<td><table class = \"buttons_col\">";  
	//image for help   	         			
             echo "<tr><td><span onClick = \"helpAdmission()\" alt=\"Click for help\" ><font size=\"2\">Click for HELP</font></span></td></tr>";
	//edit button (end of all other columns)
	     $label = array(
				'name'=>'eadmission_sumit',
				'value'=>'Edit',
		  		'class'=>'forms_buttons',
			);
	     echo "<tr><td>";		
	     make_buttons($my_service, $label, $dvars, "center", "onClick = \"return validateAedit(this.form)\"");	    
	     echo "</td></tr>";

    //delete button
             echo "<tr><td>";
	     echo form_open('census/delete_admission');       
	//make delete button
	     $label = array(
				'name'=>"delete_admission", 
				'value'=>"Delete",
				'class'=>'forms_buttons',
			);
	     make_buttons($my_service, $label, $dvars, "center", "onClick = \"return validateDelete(this.form)\"");	
             echo "</td></tr>";
 
//ICD/PCP button
	  echo "<tr><td>";
	  echo form_open('show/edit_pcpdx_form');
	//make ICD/PCP button	
	  $label = array(
				'name'=>"", 
				'value'=>"ICD/PCP",
				'class'=>'forms_buttons',
			);
	 make_buttons($my_service, $label, $dvars, "center", "");	
         echo "</td></tr>";

//Clinical History button
	  echo "<tr><td>";
          echo form_open('show/edit_cnotes');
	  $label = array(
				'name'=>"", 
				'value'=>"Clinical Notes",
				'class'=>'forms_buttons',
			);
	 make_buttons($my_service, $label, $dvars, "center", "");  
         echo "</td></tr>";
         echo form_close();	

//Clinical abstract button
         echo "<tr><td>";
         echo form_open('show/edit_abstract');
	 $label = array(
				'name'=>"", 
				'value'=>"Abstract",
				'class'=>'forms_buttons',
			);
	 make_buttons($my_service, $label, $dvars, "center", "");  
         echo "</td></tr>";
         echo form_close();	         
//Discharge summary button
         echo "<tr><td>";
         echo form_open('show/edit_dsummary');
	 $label = array(
				'name'=>"", 
				'value'=>"D-Summary",
				'class'=>'forms_buttons',
			);
	 make_buttons($my_service, $label, $dvars, "center", "");  
         echo "</td></tr>";
         echo form_close();	         

//Sagipbuhay buttons
         echo "<tr><td>";
         echo form_open('show/edit_sagipbuhay');
	 $label = array(
				'name'=>"", 
				'value'=>"Sagip Buhay",
				'class'=>'forms_buttons',
			);
	 make_buttons($my_service, $label, $dvars, "center", "");  
         echo "</td></tr>";
         echo form_close();	                         

//Home meds buttons
         echo "<tr><td>";
         echo form_open('show/edit_home');
	 $label = array(
				'name'=>"", 
				'value'=>"Home Meds",
				'class'=>'forms_buttons',
			);
	 make_buttons($my_service, $label, $dvars, "center", "");  
         echo "</td></tr>";
         echo form_close();	          
//Lab Flow #1
	 echo "<tr><td>";
         echo form_open('show/lab_forms');
	 $label = array(
				'name'=>"", 
				'value'=>"Lab Flow #1",
				'class'=>'forms_buttons',
			);
	 make_buttons($my_service, $label, $dvars, "center", "");  
         echo "</td></tr>";
         echo form_close();	  	      
//Lab Flow #2
	 echo "<tr><td>";
         echo form_open('show/lab_forms2');
	 $label = array(
				'name'=>"", 

				'value'=>"Lab Flow #2",
				'class'=>'forms_buttons',
			);
	 make_buttons($my_service, $label, $dvars, "center", "");  
         echo "</td></tr>";
         echo form_close();		      
//end of row
//filler
	echo "<tr><td height=\"85px\" class=\"filler\"></td></tr>";
         echo "</table></td>";	
	 $x++;
		  
    }
    echo "</table></div>";
	  
}
*/  
  	    
  ?>
  </table>
  </body>
</html>
