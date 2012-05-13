<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Thu, 29 Sep 2011 16:01:44 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Success! - New Admission Added</title>
    <style type="text/css">
	a{text-align:left; font-size:x-large; font-weight:bold;
	   }
	</style>
	<link rel="stylesheet" type="text/css" href="/medisys/css/show.css" />
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
//greetings and logout
make_page_header($_SERVER['PHP_AUTH_USER']);

echo "<div align=\"center\"><h1>Add New Admission - Success! New Admission Added to Database</h1> </div>"; 
$my_service = $this->session->userdata('my_service');
$my_dispo = $this->session->userdata('my_dispo');
$one_gm = $this->session->userdata('one_gm');  
$stp1 = $this->session->userdata('stp1');
$vars = array('my_service'=>$my_service, 'my_dispo'=>$my_dispo, 'one_gm'=>$one_gm, 'stp1'=>$stp1);

echo form_open('menu');
$pb = array('name'=>'main_add_a', 'value'=>'Add Another Patient', 'class'=>'menubb');
make_buttons($my_service, $pb, $vars, "center", "");
echo form_close();

if (!strcmp($my_service, 'er') || !strcmp($my_service, 'micu') || !empty($my_service)){
     echo form_open('census/one_gm_census');
     if (!strcmp($my_service, 'er')){
         $era = array('name'=>'era', 'value'=>'ER Admissions', 'class'=>'menubb');
         make_buttons($my_service, $era, $vars, "center", "");   
     }    
     elseif (!strcmp($my_service, 'micu')){
         $mia = array('name'=>'era', 'value'=>'MICU Admissions', 'class'=>'menubb');
         make_buttons($my_service, $mia, $vars, "center", ""); 
     }
     elseif (!strcmp($my_service, 'preop')){
         $pra = array('name'=>'era', 'value'=>'Pre-op Admissions', 'class'=>'menubb');
         make_buttons($my_service, $pra, $vars, "center", ""); 
     }    
     else{
         $wa = array('name'=>'era', 'value'=>'WARD Admissions', 'class'=>'menubb');
         make_buttons($my_service, $wa, $vars, "center", ""); 
     }
     echo form_close();
}
else{
     echo form_open('menu');
     $ma = array('name'=>'main_showa', 'value'=>'Manage Admissions', 'class'=>'menubb');
     make_buttons($my_service, $ma, $vars, "center", ""); 
     echo form_close();
}
echo "<div align = \"center\">";
echo "<table><tr><th>Admission Data</th><th>Problem List</th><th>Medications</th><th>In-Referrals</th><th>Out-Referrals</th></tr>";
foreach($admission as $row){
     $pdata = $this->Patient_model->get_one_patient($row->p_id);
	 foreach ($pdata as $patient)  
	        $age = compute_age_adm(revert_form_input($row->date_in), revert_form_input($patient->p_bday));	
	 $hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
     echo "<tr><td><table>";
     foreach ($pdata as $patient){  
            echo "<tr><td>Case No. ".revert_form_input($patient->cnum)."</td></tr>";    
            echo "<tr><td>Name: ".revert_form_input($patient->p_name)."</td></tr>";
	        echo "<tr><td>Age: ".$age."  Sex: ".revert_form_input($patient->p_sex)."</td></tr>";
	 }      
     if(strcmp($my_service, 'er') && strcmp($my_service, 'micu')){
		  echo "<tr><td>Source: ".$row->source."</td></tr>";
		  echo "<tr><td>Type: ".$row->type."  Status: ".$row->dispo."</td></tr>";
     }
	 echo "<tr><td>GM Service: ".$row->service."</td></tr>";
     if (!strcmp($my_service, 'er'))
            echo "<tr><td>Location: ER</td></tr>";
     elseif (!strcmp($my_service, 'micu'))
            echo "<tr><td>Location: MICU Bed: ".$row->bed."</td></tr>";
     else
	        echo "<tr><td>Location: ".$row->location." Bed: ".$row->bed."</td></tr>";
     if (!strcmp($my_service, 'er')){
            echo "<tr><td>POD: ";
		    $pod = $this->Resident_model->get_resident_name($row->pod_id); 
		    foreach ($pod as $name)
		          echo revert_form_input($name->r_name);
            echo "</td></tr>";
     }
     else{ //(strcmp($my_service, 'er')){
		    echo "<tr><td>SRIC: ";
		    $sr = $this->Resident_model->get_resident_name($row->sr_id); 
		    foreach ($sr as $name)
		          echo revert_form_input($name->r_name);		  
		    echo "</td></tr>";
		    echo "<tr><td>JRIC: ";
		    $jr = $this->Resident_model->get_resident_name($row->r_id); 
		    foreach ($jr as $name)
		          echo revert_form_input($name->r_name);		  
		    echo "</td></tr>";
		    if (strcmp($my_service, 'preop'))
		          echo "<tr><td>SIC: ".revert_form_input($row->sic)."</td></tr>";
     }
     echo "<tr><td>Date-IN: ".$row->date_in."  HD: ".$hd." days</td></tr>";
	 echo "<tr><td>Date-OUT: ".$row->date_out."</td></tr>";	  
	 echo "</table>";
	 echo "<td><textarea name = \"plist\" rows = \"28\" cols = \"26\" readonly = \"readonly\">".revert_form_input($row->plist)."</textarea></td>";
	 echo "<td><table><tr><textarea name = \"meds\" rows = \"12\" cols = \"30\" readonly = \"readonly\">".revert_form_input($row->meds)."</textarea></td></tr>";
	 echo "<tr><th>Notes</th></tr>";
	 echo "<tr><td><textarea name = \"notes\" rows =\"12\" cols = \"26\" readonly=\"readonly\">".revert_form_input($row->notes)."</textarea></td></tr>";
	 echo "</table></td>";
	 echo "<td><textarea name = \"refs\" rows =\"28\" cols = \"15\"readonly=\"readonly\">";
	 $cs_refs = explode(",", revert_form_input($row->refs));
	 foreach ($cs_refs as $refs)
		  echo $refs."\n";
	 echo "</textarea></td>";
	 echo "<td><textarea name = \"erefs\" rows =\"28\" cols = \"15\" readonly=\"readonly\">";
	 $cs_erefs = explode(",", revert_form_input($row->erefs));
	 foreach ($cs_erefs as $erefs)
		  echo $erefs."\n";
	 echo "</textarea></td></tr>";   
	 echo"</tr>";
}
echo "</table></div>";
?>

  </body>
</html>
