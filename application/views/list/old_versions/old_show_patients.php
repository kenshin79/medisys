<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Sun, 25 Sep 2011 04:34:35 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Search - Patients</title>
	<style type="text/css">
	a{text-align:left; font-size:x-large; font-weight:bold;
	   }
	</style>
	<link rel="stylesheet" type="text/css" href="/medisys/css/main.css" />
	<script type="text/javascript" src="/medisys/js/validate_form.js"></script>
    <!--[if IE]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
<body>

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

make_page_header($_SERVER['PHP_AUTH_USER']);

    echo "<div align=\"center\"><h1>Patient Search Results</h1></div>";
	if (!strcmp($stp1, "stp1")){
	        echo "<div align=\"center\"><h1>Add New Admission - Step 2: Select Patient or Add New Patient</h1> </div>"; 
            echo "<div align=\"center\"><h3>Click 'Admit this patient-button' to create new admission.</h3></div>"; 
            echo "<div align=\"center\"><h4>OR</h4></div>";
	        echo form_open('menu');
            $ap = array('name'=>'main_new_p', 'value'=>'Click to Add New Patient', 'class'=>'menubb');
            make_buttons($my_service, $ap, $vars, "center", "");
            echo form_close();
    }	
    else
    {
            echo form_open('menu');	
            $pb = array('name'=>'main_showp', 'value'=>'Back to Manage Patients', 'class'=>'menubb');
            make_buttons($my_service, $pb, $vars, "center", "");
        	echo form_close();
     }
    $numrows = count($patient);
    if (strcmp($criteria, "All")) 
            echo "<h1><div align=\"center\">'".$numrows."' patient(s) retrieved for '".$pname."'.</div></h1>";
    else
             echo "<h1><div align=\"center\">All '".$numrows."' patient(s) retrived for '".$pname."'.</div></h1>";   

     
   
    $js = 'onClick="return validateAdmstatus(this.form)"';			
    if ($patient){
            
            $x = 1;
            echo "<div align=\"center\"><table><tr><th>General Data</th><th>Address</th><th>Problem List</th><th>Edit</th></tr>";
            foreach($patient as $row){
                    $vars['epatient'] = $row->p_id; 
                    $vars['pname'] = $row->p_name;                          
	                echo form_open('show/edit_patient'); 
                    echo "<tr><td><table>";
		            echo "<tr><td>No.</td><td>".$x."</td></tr>";
		            echo "<tr><td>Case No.</td><td>".form_input('cnum', $row->cnum)."</td></tr>";
		            echo "<tr><td>Name:</td><td>".form_input('p_name', $row->p_name)."</td></tr>";
		            echo "<tr><td>Sex:</td><td>".form_dropdown('p_sex', $this->config->item('sex'), $row->p_sex)."</td></tr>";
		            echo "<tr><td>Birth Date:</td><td>".form_input('p_bday', $row->p_bday)."</td></tr>";
		            if (!strcmp($row->adm_status, "Admitted"))
		                    $adm_status = "Admitted";
		            else
		                    $adm_status = "Not Admitted";	  
		            $vars['adm_status'] = $adm_status;         
		            echo "<tr><td>Status:</td><td>".$adm_status."</td></tr></table></td>";
		            echo "<td><textarea name = \"p_add\" rows = \"8\" cols = \"30\">".$row->p_add."</textarea></td>";
		            echo "<td><textarea name = \"p_plist\" rows = \"8\" cols = \"35\">".$row->p_plist."</textarea></td>";
		                //edit patient data    
		            echo "<td><table><tr><td>";
                    $label = array('value'=>"Edit", 'class'=>"menubbb");   
                    make_buttons($my_service, $label, $vars, "center", 'onClick = "return validatePedit(this.form)"'); 
		            echo "</td></tr>";
		            echo form_close();
                        //patient admissions
		            echo "<tr><td>".form_open('census/get_patient_admissions');
                    $this->db->where('p_id', $row->p_id);
                    $this->db->from('admissions');
                    $wadm = $this->db->count_all_results();
                    $this->db->where('p_id', $row->p_id);
                    $this->db->where('dispo', "Admitted");
                    $this->db->from('admissions');
                    $numadm = $this->db->count_all_results();
                    if ($numadm > 0)
                        $wlabel = "* Ward Adm (".$wadm.")";   
                    else
                        $wlabel = "Ward Adm (".$wadm.")";
                        //wards
                    if (strcmp($one_gm, "y"))    
                        $vars['my_service'] = "All";    
                    $p2 = array('value'=>$wlabel, 'class'=>'menubbb');
                    make_buttons($my_service, $p2, $vars, "center", "");
		            echo "</td></tr>";
		            echo form_close();
		            
                    echo "<tr><td>".form_open('census/get_patient_admissions');
                    $this->db->where('p_id', $row->p_id);
                    $this->db->from('er_census');
                    $eadm = $this->db->count_all_results();
                    $this->db->where('p_id', $row->p_id);
                    $this->db->where('dispo', "Admitted");
                    $this->db->from('er_census');
                    $numadm = $this->db->count_all_results();
                    if ($numadm > 0)
                        $elabel = "* ER Adm (".$eadm.")";
                    else
                        $elabel = "ER Adm (".$eadm.")";
                        //er 
                    if (strcmp($one_gm, "y"))        
                        $vars['my_service'] = "er";                            
                    $p4 = array('value'=>$elabel, 'class'=>'menubbb');
                    make_buttons($my_service, $p4, $vars, "center", "");
		            echo "</td></tr>";
		            echo form_close();
		            
                    echo "<tr><td>".form_open('census/get_patient_admissions');
                    $this->db->where('p_id', $row->p_id);
                    $this->db->from('micu_census');
                    $madm = $this->db->count_all_results();
                    $this->db->where('p_id', $row->p_id);
                    $this->db->where('dispo', "Admitted");
                    $this->db->from('micu_census');
                    $numadm = $this->db->count_all_results();
                    if ($numadm > 0)
                        $mlabel = "* MICU Adm (".$madm.")";
                    else
                        $mlabel = "MICU Adm (".$madm.")";
                        //micu
                    if (strcmp($one_gm, "y"))    
                        $vars['my_service'] = "micu";                                                    
                    $p5 = array('value'=>$mlabel, 'class'=>'menubbb'); 
                    make_buttons($my_service, $p5, $vars, "center", "");
		            echo "</td></tr>";
		            echo form_close();
		            
                    if (!strcmp($stp1, "stp1")){
		                    echo "<tr><td>".form_open('show/create_admission_form');
		                    $p1 = array('value'=>'Admit this Patient', 'class'=>'menubbb');
		                    make_buttons($my_service, $p1, $vars, "center", "");
                			echo form_close();
                			echo "</td></tr>";
		       
		            }
                    echo "</table></td></tr>";
		            $x++;
		  
        }                        //wards
    	echo "</table></div>";
  }
    
  	    
  ?>

  </table>
  </body>
</html>
