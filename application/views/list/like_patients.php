<?php
//authorize user with uname and pword
$auth_list = $this->Users_model->get_all_users();
$csess = get_cookie('ci_session');
authorize_user($auth_list, $csess);
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
    echo "<hr/>";
    echo "<div align=\"center\"><h3>Add New Admission - Step 2: Select Patient or Add New Patient</h3> </div>"; 
    echo "<div align=\"center\"><h4>Patient Search Results</h4></div>";
	if (!strcmp($stp1, "stp1")){
            echo "<div align=\"center\"><h4>Click 'Admit this patient-button' to create new admission.</h4></div>"; 
            echo "<div align=\"center\"><h4>OR</h4></div>";
	        echo form_open('menu');
	        echo form_hidden('my_service', $my_service).form_hidden('my_dispo', $my_dispo).form_hidden('one_gm', $one_gm).form_hidden('stp1', $stp1);
            echo "<div align = \"center\"><input type = \"submit\" name = \"main_new_p\" value = \"Click to Add New Patients\" style = \"font-size:large; color:red; height:30px; width:250px;\" /></div>";
            echo form_close();
    }	
    $numrows = count($patient);
   
    $js = 'onClick="return validateAdmstatus(this.form)"';			
    if ($patient){
            echo "<div align = \"center\"><h3>".$numrows." matches for '".$clue."'.</h3></div>"; 
            $x = 1;
            echo "<div align=\"center\"><table><tr><th>No.</th><th>Case No.</th><th>Name</th><th>Sex</th><th>Birth Date</th><th>Status</th></tr>";
            foreach($patient as $row){
                    $vars['epatient'] = $row->p_id; 
                    $vars['pname'] = $row->p_name;                          
                    echo "<tr>";
		            echo "<td>".$x."</td>";
		            echo "<td>".revert_form_input($row->cnum)."</td>";
		            echo "<td>".revert_form_input($row->p_name)."</td>";
		            echo "<td>".revert_form_input($row->p_sex)."</td>";
		            echo "<td>".revert_form_input($row->p_bday)."</td>";
		            if (!strcmp(revert_form_input($row->adm_status), "Admitted"))
		                    $adm_status = "Admitted";
		            else
		                    $adm_status = "Not Admitted";	  
		            $vars['adm_status'] = $adm_status;         
		            echo "<td>".$adm_status."</td><td>";
            echo form_open('show/create_admission_form');
		                    $p1 = array('value'=>'Admit this Patient', 'style'=>'font-size:large; color:red;height:30px;width:170px');
		                    make_buttons($my_service, $p1, $vars, "center", "");
                			echo form_close();
                			echo "</td></tr>";
		            $x++;
		            }
		            
	echo "</table></div>";	  
    }
    else   
        echo "<div align = \"center\"><h3>No matches found for '".$clue."'.</h3></div>";
                      
    
  
 
  	    
  ?>

  </table>

