<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Thu, 29 Sep 2011 16:50:20 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Step 2 - Select Patient or Add New Patient</title>
    <style type="text/css">
	a{text-align:left; font-size:x-large; font-weight:bold;
	   }
	</style>
	<link rel="stylesheet" type="text/css" href="/medisys/css/show.css" />
	<link rel="stylesheet" type="text/css" href="/medisys/calendar/calendar.css" />
	<script type="text/javascript" src="/medisys/js/validate_form.js"></script>
	<script type="text/javascript" src="/medisys/calendar/calendar.js"></script>
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
$aa = array('name'=>'main_add_a', 'value'=>'Back to Step 1', 'class'=>'menubb');
$ap = array('value'=>'Add this Patient', 'class'=>'menubb');

$my_service = $this->session->userdata('my_service');
$my_dispo = $this->session->userdata('my_dispo');
$one_gm = $this->session->userdata('one_gm');  
$stp1 = $this->session->userdata('stp1');
$vars = array('one_gm'=>$one_gm, 'my_service'=>$my_service, 'my_dispo'=>$my_dispo, 'stp1'=>$stp1);
make_page_header($_SERVER['PHP_AUTH_USER']);

echo "<div align=\"center\"><h1>Add New Admission - Step 2: Add New Patient</h1> </div>"; 
echo form_open('menu');	
$label = array('name'=>'main_add_a', 'value'=>'Back to Step 1', 'class'=>'menubb');
make_buttons($my_service, $label, $vars, "center", "");
echo form_close();

echo form_open('add/insert_patient'); 
echo "<div align=\"center\"><table><tr><th>General Data</th><th>Address</th><th>Problem List</th></tr>";
echo "<tr><td><table>";
echo "<tr><td>New!</td><td></td></tr>";
echo "<tr><td>Case No.</td><td>".form_input('cnum','' )."</td></tr>";
echo "<tr><td>Name:</td><td>".form_input('p_name', '')."</td></tr>";
echo "<tr><td>Sex:</td><td>".form_dropdown('p_sex', $this->config->item('sex'), 'M')."</td></tr>";
//echo "<tr><td>Birth Date (yyyy-mm-dd):</td><td>".form_input('p_bday', '')."</td></tr></table></td>";
//date picker
 //get class into the page
 echo "<tr><td>Birth Date:</td><td>";
require_once('calendar/classes/tc_calendar.php');
 $myCalendar = new tc_calendar("p_bday", true, false);
	  $myCalendar->setIcon("calendar/images/iconCalendar.gif");
	  $myCalendar->setPath("calendar/");
	  $myCalendar->setYearInterval(1900, 2015);
	  $myCalendar->dateAllow('1900-01-01', '2015-01-01');
	  $myCalendar->setDateFormat('j F Y');
	  $myCalendar->setAlignment('right', 'top');
	  $myCalendar->writeScript();
echo "</td></tr></table></td>";	  
echo "<td><textarea name = \"p_add\" rows = \"8\" cols = \"35\"></textarea></td>";
echo "<td><textarea name = \"p_plist\" rows = \"8\" cols = \"35\"></textarea></td></tr></table></div>";
$bvars = array('one_gm'=>$one_gm, 'my_service'=>$my_service, 'my_dispo'=>$my_dispo);
$label = array('value'=>'Add this Patient', 'class'=>'menubb');
make_buttons($my_service, $label, $bvars, "center", 'onClick = "return validatePedit(this.form)"');
echo form_close();

?>
  </body>
</html>
