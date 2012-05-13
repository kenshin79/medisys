<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Mon, 26 Sep 2011 16:24:26 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Success! - New Patient Added</title>
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
echo "<div align=\"center\"><h1>Add New Admission - Step 2: Add New Patient</h1> </div>";
echo "<div align=\"center\"><h1>Success! - New Patient Added</h1> </div>";  

$my_service = $this->session->userdata('my_service');
$my_dispo = $this->session->userdata('my_dispo');
$one_gm = $this->session->userdata('one_gm');
$stp1 = $this->session->userdata('stp1');
$vars = array('my_service'=>$my_service, 'my_dispo'=>$my_dispo, 'one_gm'=>$one_gm, 'stp1'=>$stp1);

echo form_open('show/create_admission_form');
$aa = array('value'=>'Admit this Patient', 'class'=>'menubb');
$vars['epatient'] = $epatient;
make_buttons($my_service, $aa, $vars, "center", "");
echo form_close();

echo "<div align = \"center\">";
$patient = $this->Patient_model->get_one_patient($epatient);
echo "<table><tr><th>General Data</th><th>Address</th><th>Problem List</th></tr>";
echo "<tr><td><table>";
foreach ($patient as $row){
    echo "<tr><td>Case No.</td><td>".revert_form_input($row->cnum)."</td></tr>";
    echo "<tr><td>Name:</td><td>".revert_form_input($row->p_name)."</td></tr>";
    echo "<tr><td>Sex:</td><td>".$row->p_sex."</td></tr>";
    echo "<tr><td>Birth Date:</td><td>".revert_form_input($row->p_bday)."</td></tr></table></td>";
    echo "<td><textarea name = \"p_add\" rows = \"8\" cols = \"35\" readonly = \"readonly\">".revert_form_input($row->p_add)."</textarea></td>";
    echo "<td><textarea name = \"p_plist\" rows = \"8\" cols = \"35\" readonly = \"readonly\">".revert_form_input($row->p_plist)."</textarea></td>";
}
echo "</tr></table></div>";
?>  
  
  </body>
</html>
