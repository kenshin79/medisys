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

//assign session variables
$vars = array('my_service'=> $my_service, 'my_dispo'=>$my_dispo, 'one_gm'=>$one_gm, 'stp1'=>$stp1);
$eresident = $this->session->userdata('eresident');
$rname = $this->session->userdata('rname');
$vars['eresident'] = $eresident;
$vars['rname'] = $rname;
//array list variables
$loc_list = $this->config->item('loc_list');
$type_list = $this->config->item('type_list');
$dispo_list = $this->config->item('dispo_list');
$service_list = $this->config->item('service_list');
$bed_list = $this->config->item('bed_list');
$mbed_list = $this->config->item('m_beds');

//get resident list
$res_list = $this->Resident_model->get_activer();

//make patients list
    echo "<div align=\"center\"><table ><tr><th>Admission Data</th><th>Problem List and Medications</th><th>In-Referrals</th><th>Out-Referrals</th><th>Docs</th></tr>";
    foreach($c_admissions as $row){  
		
        if (!strcmp($my_service, 'er'))
	        $vars['eadmission'] = $row->er_id;
        elseif (!strcmp($my_service, 'micu'))
	        $vars['eadmission'] = $row->micu_id;
	    else
	        $vars['eadmission'] = $row->a_id;

	    $hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
	    //$vars['num'] = $y;


	    //if not monitor, allow edit date dispo
	    if (strcmp($_SERVER['PHP_AUTH_USER'], 'monitor'))
	      echo form_open('census/edit_date_out');
        echo "<tr><td><table id = \"leftcol\">";
        $pdata = $this->Patient_model->get_one_patient($row->p_id);
	    foreach ($pdata as $patient){
	        $age = compute_age_adm(revert_form_input($row->date_in), revert_form_input($patient->p_bday));	
            if (strcmp($my_service, 'er') && strcmp($my_service, 'micu'))
                make_case_num_row($my_service, "1", $row->type, revert_form_input($patient->cnum));
            else    
                make_case_num_row($my_service, "1", "", revert_form_input($patient->cnum));
            echo "<tr><td colspan = 2>Name: ".revert_form_input($patient->p_name)."</td></tr>";
	        echo "<tr><td colspan = 2>Age:".$age." Sex:".$patient->p_sex."</td></tr>";
        }
        if (strcmp($my_service, 'er'))
            echo "<tr><td colspan = 2>Source: ".$row->source."</td></tr>";
        if (strcmp($my_service, 'er') && strcmp($my_service, 'micu'))
            echo "<tr><td colspan = 2>Type: ".$row->type."</td></tr>";
	    echo "<tr><td colspan = 2>Status: ".form_dropdown('dispo', $dispo_list, $row->dispo)."</td></tr>";
	    echo "<tr><td colspan = 2>GM Service: ".$row->service."</td></tr>";
        if (strcmp($my_service, 'er')){
            if (!strcmp($my_service, 'micu'))
                echo "<tr><td colspan = 2>Loc: MICU Bed ".$row->bed."</td></tr>";
            else
                echo "<tr><td colspan = 2>Loc: ".$row->location." Bed ".$row->bed."</td></tr>";
        }
        //POD if ER
		if (!strcmp($my_service, 'er')){
		    $pod = $this->Resident_model->get_resident_name($row->pod_id); 
			make_pod_row(1, $row->pod_id, $pod, $res_list);
		}

        //SRIC
		if (strcmp($my_service, 'er')){
			$srname = $this->Resident_model->get_resident_name($row->sr_id);
			make_sric_row(1, $row->sr_id, $srname, $res_list);

		//JRIC
			$jrname = $this->Resident_model->get_resident_name($row->r_id);
			make_jric_row(1, $row->r_id, $jrname, $res_list);
		}

        //SIC
        if (strcmp($my_service, 'er'))
               echo "<tr><td colspan = 2>SIC: ".revert_form_input($row->sic)."</td></tr>";
        //Date IN     
	    echo "<tr><td colspan = 2>Date-IN:".revert_form_input($row->date_in)." HD: ".$hd." days</td></tr>";
	    //Date OUT
	    echo "<tr><td>Date-OUT:</td><td>";
        if (strcmp($_SERVER['PHP_AUTH_USER'], 'monitor')){
		      //echo "<input type=\"text\" name = \"date_out\" size = 8 value = \"".revert_form_input($row->date_out)."\">";
		  $donum = "date_out"; 	  
	      require_once('calendar/classes/tc_calendar.php');		
		  $myCalendar = new tc_calendar($donum, true, false);
		  $myCalendar->setIcon(base_url()."calendar/images/iconCalendar.gif");
		  $dd = (int)substr($row->date_out,8, 2);
		  $mm = (int)substr($row->date_out, 5, 2);
		  $yy = (int)substr($row->date_out, 0, 4);
		  $myCalendar->setDate($dd, $mm, $yy);
		  $myCalendar->setPath(base_url()."calendar/");
		  $myCalendar->setYearInterval(1900, 2015);
		  $myCalendar->dateAllow('2011-01-01', '2015-01-01');
		  $myCalendar->setDateFormat('j F Y');
		  $myCalendar->setAlignment('right', 'top');
		  $myCalendar->writeScript();				  
			  
		}	  
	    else
		      echo revert_form_input($row->date_out);
 	    echo "</td></tr>";	  
	    echo "</table>";
	    echo "<td><table>";
        //Plist 
	    echo "<tr><td id = \"mednotes\">Problem List:\n<textarea  name = \"plist\" rows = \"6\" cols = \"40\" wrap = \"off\" >".revert_form_input($row->plist)."</textarea></td></tr>";
        //Meds
	    echo "<tr><td id = \"mednotes\">Medications:\n<textarea name = \"meds\" rows = \"6\" cols = \"40\" wrap = \"off\" readonly = \"readonly\">".revert_form_input($row->meds)."</textarea></td></tr>";
	    echo "</td></tr>";  
        //PCP-ICD 
        $cs_pcpdx = explode(",", revert_form_input($row->pcpdx));
	    echo "<tr><td><input type =\"button\" value = \"PCP/ICD\" onClick = \" return showPcp(this.form)\" /><textarea name = \"pcpdx\" rows=\"5\" cols=\"40\" wrap=\"off\" readonly=\"readonly\">";
        foreach ($cs_pcpdx as $pcp)
		      echo $pcp."\n";
	    echo "</textarea></td></tr></table></td>";
        //refs
	    echo "<td class = \"tarea\">In-Referrals:\n<textarea name = \"refs\" rows = \"17\" cols = \"13\" readonly=\"readonly\">";
	    $cs_refs = explode(",", revert_form_input($row->refs));
	    foreach ($cs_refs as $refs) 
		      echo $refs."\n";
	    echo "</textarea></td>";
        //erefs
	    echo "<td class = \"tarea\">Out-Referrals:\n<textarea name = \"erefs\" rows = \"17\" cols = \"13\" readonly=\"readonly\">";
	    $cs_erefs = explode(",", revert_form_input($row->erefs));
	    foreach ($cs_erefs as $erefs)
		      echo $erefs."\n";
	    echo "</textarea></td>";  	  
        echo "<td><table>";
        //notes
        echo "<tr><td class = \"b_col\"><input  type = \"button\" value = \"View Notes\" size = 30 onClick = \"return showNotes(this.form)\"/>";
        echo "<input type = \"hidden\" name = \"pnotes\" value = \"".revert_form_input($row->notes)."\" /></td></tr>";   
	    if (strcmp($_SERVER['PHP_AUTH_USER'], 'monitor')){
                $label = array( 'name'=>"", 'value'=>"Edit Admission", 'class'=>"");
                echo "<tr><td>";
                //make_buttons($my_service, $label, $vars, "center", 'onClick = "return validateAedit(this.form)"');
			    $djs = "onClick = \"return validateDateedit(".$row->date_in." , this.form)\"";
				make_buttons($my_service, $label, $vars, "center", $djs);
				echo form_close();
			    echo "</td></tr>";		
	    }
        //Forms Button Column
		  //edit ICD/PCP
          //  
      if (strcmp($one_gm, "px")){    
            echo form_open('show/edit_pcpdx_form');
	        $label = array('value'=>"Edit ICD/PCP");	 
	        echo "<tr><td class = \"b_col\">";
	        make_buttons($my_service, $label, $vars, "center", "");
            echo "</td></tr>";
	        echo form_close();
      }           

      echo "</td></tr>"; 
	  echo "</table></td>";
	  echo "</tr>";
	}  
	 echo "</table></div>";