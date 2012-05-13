<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Thu, 29 Sep 2011 13:34:31 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Add New Admission</title>
    <style type="text/css">
	a{text-align:left; font-size:x-large; font-weight:bold;
	   }
	</style>
	<link rel="stylesheet" type="text/css" href="/medisys/css/show.css" />
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

$my_service = $this->session->userdata('my_service');
$my_dispo = $this->session->userdata('my_dispo');
$one_gm = $this->session->userdata('one_gm');  
$stp1 = $this->session->userdata('stp1');
$loc_list = $this->config->item('loc_list');

make_page_header($_SERVER['PHP_AUTH_USER']);

echo "<div>";
echo "<div align=\"center\"><h1>Add New Admission - Step 2: Select Patient or Add New Patient</h1> </div>"; 
echo "<h2><div align=\"center\">(Edit and/or Add Admission Details, including Problem List and Medications)</div></h2>";  
	//Skip Adding
echo form_open('menu');	
$label = array('class'=>'menubb', 'name'=>'main_add_a', 'value'=>"Skip Adding Admission");
$vars = array('one_gm'=>$one_gm, 'my_service'=>$my_service, 'my_dispo'=>$my_dispo, 'stp1'=>$stp1);
make_buttons($my_service, $label, $vars, "center", "");
echo form_close();
echo "<div align = \"center\">";
echo "<table><tr><th>Admission Data</th><th>Problem List</th><th>Medications</th><th>In-Referral</th><th>Out-Referral</th></tr>";
echo form_open('add/insert_admission'); 
foreach($patients as $row){
	echo "<tr><td><table id = \"leftcol\">";
	echo "<tr><td>Case No. ".revert_form_input($row->cnum)."</td></tr>";
	//make source row
	make_source_row(0, $my_service, "ER", $loc_list);
        //make type row
	if (strcmp($my_service, 'er') && strcmp($my_service, 'micu')){
        if (!strcmp($my_service, 'preop')){
			   echo "<tr><td>Type: Pre-operative</td></tr>";
               echo form_hidden('type', "Pre-operative");
	    } 
        else
   		       echo "<tr><td>Type:".form_dropdown('type', $this->config->item('type_list'), "Primary")."</td></tr>";
   	}	       
         //make status row
    echo "<tr><td>Status:".form_dropdown('dispo', $this->config->item('dispo_list'))."</td></tr>";
	    //make pname row
	echo "<tr><td>Name: ".revert_form_input($row->p_name)."</td></tr>";
	     //make Bday-Sex row
	echo "<tr><td>Birth Date: ".revert_form_input($row->p_bday)."Sex: ".$row->p_sex."</td></tr>";
	     //make Service row
    if (strcmp($my_service, 'er') && strcmp($my_service, 'micu') && strcmp($my_service, 'preop')){ 
        echo "<tr><td>GM Service: ".$my_service."</td></tr>";
        echo form_hidden('service', $my_service);
    }
    else    
         echo "<tr><td>GM Service:".form_dropdown('service', $this->config->item('service_list'), 1)."</td></tr>";
	    //make Location-Bed row
    if (strcmp($my_service, 'er')){
         if (!strcmp($my_service, 'micu'))
		        echo "<tr><td>Loc: MICU Bed ".form_dropdown('bed', $this->config->item('m_beds'))."</td></tr>";
         else
                echo "<tr><td>Loc:".form_dropdown('location', $this->config->item('loc_list'))."Bed".form_dropdown('bed', $this->config->item('bed_list'))."</td></tr>";
    }
	//make Residents row
		//ER POD
	if (!strcmp($my_service, 'er')){
         echo "<tr><td>POD: <select name = \"pod_id\">";
         echo "<option value=\"1\">None</option>";
	     foreach($residents as $res)
	            echo "<option value=\"".$res->r_id."\">".revert_form_input($res->r_name)."</option>";
	     echo "</select></td></tr>";
    }
		//Ward and MICU (SRIC and RIC-SCU-SAPOD)
	        //SRIC
    if (strcmp($my_service, 'er')){
         echo "<tr><td>SRIC: <select name = \"sr_id\">";
         echo "<option value=\"1\">None</option>";
	     foreach($residents as $res)
	           echo "<option value=\"".$res->r_id."\">".revert_form_input($res->r_name)."</option>";
	     echo "</select></td></tr>";
	     echo "<tr><td>";
	    //JRIC
         if (!strcmp($my_service, 'preop'))
          	echo "SAPOD:";
	     else
	      	echo "JRIC/SCUPOD:";
         echo "<select name = \"r_id\">";
         echo "<option value=\"1\">None</option>";
	     foreach($residents as $res)
	        echo "<option value=\"".$res->r_id."\">".revert_form_input($res->r_name)."</option>";
	     echo "</select></td></tr>";
	     
	      //SIC for Wards/MICU only
         if (strcmp($my_service, 'preop'))
            echo "<tr><td>SIC:".form_input('sic', '')."</td></tr>";
     }       
	  //Date in
	$today = mdate("%Y-%m-%d");
	$a_datein = array(
	    	      'name'=>'date_in',
	    		  'size'=>'8',
	    		  'value'=>$today,
	 	      );
	echo "<tr><td>Date-IN:".form_input($a_datein)." </td></tr>";
	  //Date out 	  
    $a_dateout = array(
	    	      'name'=>'date_out',
	    		  'size'=>'8',
	    		  'value'=>'0000-00-00',

		      );
    echo "<tr><td>Date-OUT:".form_input($a_dateout)."</td></tr>";	  
	echo "</table>";
	  //P list
    echo "<td><textarea name = \"plist\" rows = \"20\" cols = \"28\">".revert_form_input($row->p_plist)."</textarea></td>";
	  //Meds	  
	echo "<td><table><tr><textarea name = \"meds\" rows = \"9\" cols = \"22\"></textarea></td></tr>";
	  //Notes  
    echo "<tr><th>Notes</th></tr>";
	echo "<tr><td><textarea name = \"notes\" rows =\"7\" cols = \"20\" ></textarea></td></tr>";
	echo "</table></td>";
	  //refs	  
	echo "<td>";
	$def_cb = array('name'=>'refs[]', 'readonly'=>'readonly', 'checked'=>'TRUE');
	echo form_checkbox($def_cb)."Check<br />";
	if (strcmp($my_service, 'micu')){ 
          foreach ($this->config->item('ref_list') as $r)
		  	   echo form_checkbox('refs[]', $r, FALSE)."$r<br />"; 	  
	}
	if (!strcmp($my_service, 'micu')){
          foreach ($this->config->item('ref_list') as $r){
               if (strcmp($r, 'MICU'))
                   echo form_checkbox('refs[]', $r, FALSE)."$r<br />"; 
          }
    }
	echo "</td>";
      //erefs
	echo "<td>";
	$def_cb1 = array('name'=>'erefs[]', 'readonly'=>'readonly', 'checked'=>'TRUE');
	echo form_checkbox($def_cb1)."Check<br />";
	foreach ($this->config->item('eref_list') as $er) 
	      echo form_checkbox('erefs[]', $er, FALSE)."$er<br />"; 	  
    echo "</td>";	  
    echo "</tr></table>";
	$vars = array(
	        		'epatient'=> $row->p_id,
	        		'one_gm'=>$one_gm,
	        		'my_service'=>$my_service,
	        		'my_dispo'=>$my_dispo,
	        		'stp1'=>$stp1
	        );		
	$label = array(
	        		 'class'=>'menubb',
	        		 'value'=>'Add this admission'
			);
	make_buttons($my_service, $label, $vars, "center", 'onClick = "return validateAedit(this.form)"');
	echo form_close();
}	  
          echo "</div>";
?>
  </body>
</html>
