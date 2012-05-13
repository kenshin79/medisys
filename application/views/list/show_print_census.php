<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Wed, 28 Sep 2011 10:51:07 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Gen Med Ward Census Report</title>
    <link rel="stylesheet" type="text/css" href="/medisys/css/census.css" />
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
//greeting user and logout link
echo "<div>";
echo "<div align=\"left\"><b>Welcome User: ".$_SERVER['PHP_AUTH_USER']."!</b></div>";
echo "<div align=\"right\">".form_open('loguser/log_out')."<b>".form_submit('','Log-out Medisys','').form_close()."</b></div>";
echo "</div>";
echo "<a href=\"/medisys\">Main Menu</a>";

  
  $my_service = $this->session->userdata('my_service');
  $my_dispo = $this->session->userdata('my_dispo');
  $one_gm = $this->session->userdata('one_gm');
  $loc = $this->session->userdata('l');
  $src = $this->session->userdata('src');
  $my_date1 = $this->session->userdata('my_date1');
  $my_date2 = $this->session->userdata('my_date2');
  if (strcmp($my_service, 'micu'))
  {
   
   $num_er = count($p_er);
   $num_micu = count($p_micu);
  }
  $numrows = count($p_admission);
  echo form_open('census/one_gm_census');
  echo form_hidden('my_service', $my_service);
  echo form_hidden('my_dispo', $my_dispo);
  echo form_hidden('one_gm', $one_gm);
  echo form_submit('back', 'Back to ADMISSIONS');
  echo form_close();
  if (!strcmp($my_service, 'micu'))
       echo "<div align=\"center\" class=\"label_header\">MICU Service Census</div>";
  else 
       echo "<div align=\"center\" class=\"label_header\">Gen Med Service ".$my_service." In-Patients Census</div>";
  if (!strcmp($my_service, 'micu'))
      echo "<div align=\"center\" class=\"label_header\">Total of (".$numrows.") ".$my_dispo." Patients Admitted</div>";
  else
      echo "<div align=\"center\" class=\"label_header\">Total of (".$numrows.") ".$my_dispo." Primary and Co-Managed: ".$num_er." in ER: ".$num_micu." in MICU</div>";
  echo "<div align=\"center\">as of ".date("M-d-Y")."</div>";

if ($p_admission)
{
echo "<div align = \"center\"><table><tr><th>No.</th><th>Location</th><th>Bed</th><th>Patient Name</th><th>RIC</th><th>SIC</th><th>Hosp Days</th></tr>";
$x =1;
foreach ($p_admission as $row)
{
    $pdata = $this->Patient_model->get_one_patient($row->p_id);
    echo "<tr><td>".$x."</td>"; 
    if (!strcmp($my_service, 'micu'))
        echo "<td>MICU</td>";
    else
        echo "<td>".$row->location."</td>";
    foreach($pdata as $patient)
    	echo "<td>".$row->bed."</td><td>".revert_form_input($patient->p_name)."</td><td>";
    $jr = $this->Resident_model->get_resident_name($row->r_id); 
    foreach ($jr as $name)
    {
		  	echo revert_form_input($name->r_name);
    }
    echo "</td><td>".revert_form_input($row->sic)."</td>";
    $hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
    echo "<td>".$hd."</td></tr>";
    $x++;
}
if (strcmp($my_service, 'micu'))
{
foreach ($p_micu as $row)
{
    $pdata = $this->Patient_model->get_one_patient($row->p_id);
    echo "<tr><td>".$x."</td>"; 
    echo "<td>MICU</td>";
    echo "<td>".$row->bed."</td>";
    foreach($pdata as $patient)
    	echo "<td>".revert_form_input($patient->p_name)."</td><td>";
    $jr = $this->Resident_model->get_resident_name($row->r_id); 
    foreach ($jr as $name)
    {
		  	echo revert_form_input($name->r_name);
    }
    echo "</td><td>".revert_form_input($row->sic)."</td>";
    $hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
    echo "<td>".$hd."</td></tr>";
    $x++;

}
foreach ($p_er as $row)
{
    $pdata = $this->Patient_model->get_one_patient($row->p_id);
    echo "<tr><td>".$x."</td>"; 
    echo "<td>ER</td>";
    echo "<td> -- </td>";
    foreach($pdata as $patient)
    	echo "<td>".revert_form_input($patient->p_name)."</td><td>";
    $jr = $this->Resident_model->get_resident_name($row->pod_id); 
    foreach ($jr as $name)
    {
		  	echo revert_form_input($name->r_name);
    }
    echo "</td><td> -- </td>";
    $hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
    echo "<td>".$hd."</td></tr>";
    $x++;

}
}
echo "</table></div>";
}  
echo "<table id=\"outer\"><tr><th id=\"hleft\">Admission Information</th><th>Problem List</th><th>Medications</th><th>Referrals</th><th>Notes</th></tr>";
$x = 1;
foreach($p_admission as $row){
                $pdata = $this->Patient_model->get_one_patient($row->p_id);
		foreach($pdata as $patient)
	       		$age = compute_age_adm(revert_form_input($row->date_in), revert_form_input($patient->p_bday));	
	       $hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
		echo "<tr><td style=\"width:120px\"><table id=\"left\">";
		echo "<tr><td style=\"width:12px; font-size:8pt\">No. ".$x."</td></tr>";
		if (strcmp($my_dispo, 'Admitted'))
    			echo "<tr><td style=\"width:120px; font-size:8pt\">Disposition: ".$row->dispo."</td></tr>";
                if (strcmp($my_service, 'micu'))
		        echo "<tr><td style=\"width:120px; font-size:8pt\">".$row->type."</td></tr>";
	        foreach($pdata as $patient)
			echo "<tr><td style=\"width:120px; font-size:8pt\">Case no. ".revert_form_input($patient->cnum)."</td></tr>";
		//echo "<tr><td style=\"width:120px\">Source: ".$row->source."</td></tr>";
		foreach($pdata as $patient){
			echo "<tr><td style=\"width:120px; font-size:8pt\">Name: ".revert_form_input($patient->p_name)."</td></tr>";
			echo "<tr><td style=\"width:120px; font-size:8pt\">Age/Sex: ".$age." / ".$patient->p_sex."</td></tr>";
	 	}
		echo "<tr><td style=\"width:120px; font-size:8pt\">Date Admitted: ".revert_form_input($row->date_in)."</td></tr>";
                if (!strcmp($my_service, 'micu'))
                    echo "<tr><td style=\"width:120px; font-size:8pt\">MICU Bed ".$row->bed."</td></tr>"; 
                else
		    echo "<tr><td style=\"width:120px; font-size:8pt\">".$row->location." Bed ".$row->bed."</td></tr>";

		if (strcmp($my_dispo, 'Admitted'))
    			echo "<tr><td style=\"width:120px; font-size:8pt\">Date of Dispo: ".revert_form_input($row->date_out)."</td></tr>";	 
		echo "<tr><td style=\"width:120px; font-size:8pt\">HD: ".$hd." days</td></tr>";
		echo "<tr><td style=\"width:120px; font-size:8pt\">SRIC: ";
		$sr = $this->Resident_model->get_resident_name($row->sr_id); 
		  foreach ($sr as $name){
		  	echo revert_form_input($name->r_name);
		  }
		echo "</td></tr>";
		echo "<tr><td style=\"width:120px; font-size:8pt\">JRIC: ";
		$jr = $this->Resident_model->get_resident_name($row->r_id); 
		  foreach ($jr as $name){
		  	echo revert_form_input($name->r_name);
		  }
		echo "</td></tr>";
                echo "<tr><td>SIC: ".revert_form_input($row->sic)."</td></tr>";
		echo "</table></td>";
		echo "<td style=\"width:250px; font-size:8pt\">";
		echo nl2br(revert_form_input($row->plist));
		echo "</td>";
		echo "<td style=\"width:250px; font-size:8pt\">";
		echo nl2br(revert_form_input($row->meds));
		echo "</td>";
		echo "<td style=\"width:60px; font-size:8pt\"><br />";
		$cs_refs = explode(",", revert_form_input($row->refs));
		  foreach ($cs_refs as $refs) {
		       echo $refs."<br />";
		  }
		$cs_erefs = explode(",", revert_form_input($row->erefs));
		  foreach ($cs_erefs as $erefs) {
		       echo $erefs."<br />";
		  }
		echo "</td><td style=\"width:200px; font-size:8pt\">".nl2br(revert_form_input($row->notes))."</td>";
		$x++;		  
		}
		echo "</tr></table>";



?>
  </body>
</html>
