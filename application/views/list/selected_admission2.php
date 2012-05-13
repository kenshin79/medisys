<?php

//set user session variables
$my_service = $this->session->userdata('my_service');
$my_dispo = $this->session->userdata('my_dispo');
$one_gm = $this->session->userdata('one_gm');
$stp1 = $this->session->userdata('stp1');

//assign session variables
$vars = array('my_service'=> $my_service, 'my_dispo'=>$my_dispo, 'one_gm'=>$one_gm, 'stp1'=>$stp1);

//array list variables
$loc_list = $this->config->item('loc_list');
$type_list = $this->config->item('type_list');
$dispo_list = $this->config->item('dispo_list');
$service_list = $this->config->item('service_list');
$bed_list = $this->config->item('bed_list');
$mbed_list = $this->config->item('m_beds');

//make patients list

  if ($c_admissions){
      $x = 1;
      echo "<div align=\"center\" style = \"clear:left;\" ><table id=\"census_table\" >";
      echo "<tr onclick = \"hideMe(this)\"><th>Admission Data</th><th>Problem List and Medications</th><th>In-Referral</th><th>Out-Referral</th><th>Modify</th></tr>";	
      foreach($c_admissions as $row){  
	      $hd = compute_hd($row->dispo, $row->date_in, $row->date_out);
          echo "<tr><td>";
          
//contents of general data column	
         echo form_open('show/edit_admission'); 
	      echo "<table>";

	      $pdata = $this->Patient_model->get_one_patient($row->p_id);
	      foreach ($pdata as $patient)
	      {
		      $age = compute_age_adm($row->date_in, $patient->p_bday);
		      $pname = $patient->p_name;
		      if (strcmp($my_service, 'er') && strcmp($my_service, 'micu'))
	              		make_case_num_row($my_service, $x, $row->type, revert_form_input($patient->cnum));
	      	  else
                      	make_case_num_row($my_service, $x, "", revert_form_input($patient->cnum));
		      make_pname_row(revert_form_input($pname));	
	      	  make_agesex_row($age, $patient->p_sex);
	      }
	      if (strcmp($my_service, 'er') && strcmp($my_service, 'micu'))
	      	      make_type_row(1, $my_service, revert_form_input($row->type), $type_list);
	      make_status_row(1, $my_service, revert_form_input($row->dispo), $dispo_list);	
	      if (strcmp($my_service, 'er'))
		      make_source_row(1, $my_service, revert_form_input($row->source), $loc_list);
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
	      	      make_pod_row(1, $row->pod_id, revert_form_input($pod), $residents); 		
              }
              else   
              {
		      $sr = $this->Resident_model->get_resident_name($row->sr_id); 
		      $jr = $this->Resident_model->get_resident_name($row->r_id);
		      make_sric_row(1, $row->sr_id, revert_form_input($sr), $residents);
		      make_jric_row(1, $row->r_id, revert_form_input($jr), $residents);
		      if (strcmp($my_service, "preop"))	
                      		make_sic_row(1, revert_form_input($row->sic));
              }
	      make_datein_row(1, revert_form_input($row->date_in), $hd);
	      make_dateout_row(1, revert_form_input($row->date_out));
 
	      echo "</table>";
//end of gendata column
//start of plist column
          echo "<td><table class = \"p_list\">";
          make_plist_row(1, revert_form_input($row->plist));
          make_meds_row(1, revert_form_input($row->meds));
          make_pcpdx_row(revert_form_input($row->pcpdx));
          make_notes(revert_form_input($row->notes));
          echo "</table></td>";  
//end of plist column
//start of refs and erefs column
	//refs
	      echo "<td class=\"refs\">";
	      $def_cb = array('name'=>'refs[]', 'readonly'=>'readonly', 'checked'=>'TRUE'); 
	      $ref_array = $this->config->item('ref_list');
	      make_refs_row($my_service, $def_cb, $ref_array, revert_form_input($row->refs)); 
	      echo "</td>";
        //erefs
	      echo "<td class=\"refs\">";
	      $def_cb1 = array('name'=>'erefs[]', 'readonly'=>'readonly', 'checked'=>'TRUE');
	      $eref_array = $this->config->item('eref_list');
	      make_erefs_row($def_cb1, $eref_array, revert_form_input($row->erefs));
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
	     echo "<td>";
 	     echo "<table class = \"buttons_col\">";  
	//image for help   	         			
             echo "<tr><td><span onClick = \"helpAdmission()\" alt=\"Click for help\" ><font size=\"2\">Click for HELP</font></span></td></tr>";
	//edit button (end of all other columns)

	     echo "<tr><td>";	
	       $label = array(
				'name'=>'eadmission_sumit',
				'value'=>'Edit',
		  		'class'=>'forms_buttons',
			);
	
	     make_buttons($my_service, $label, $dvars, "center", "onClick = \"return validateAedit(this.form)\"");	    
	     echo form_close();
	     
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
         echo form_close();
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
     echo form_close();
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






?>
