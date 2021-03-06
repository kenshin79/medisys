<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Sun, 25 Sep 2011 04:34:35 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Manage Residents</title>
	<style type="text/css">
	a{text-align:left; font-size:x-large; font-weight:bold;
	   }
	</style>
    <script type="text/javascript" src="/medisys/js/jquery.js"></script>
	<script type="text/javascript" src="/medisys/calendar/calendar.js"></script>	
  	<script type="text/javascript" src="/medisys/js/my_jscripts.js"></script>	
	<link rel="stylesheet" type="text/css" href="/medisys/css/menu.css" />
	<link rel="stylesheet" type="text/css" href="/medisys/calendar/calendar.css" />
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

//greeting user and logout link
make_page_header($_SERVER['PHP_AUTH_USER']);

echo "<div align=\"center\"><h1>Manage Residents Page</h1></div>";
echo "<hr />";

$rb = array('class'=>'menubb', 'value'=>'GO!'); 
$nf = array('name'=>'rname', 'size'=>'35');
$js = array('name'=>'find_resident', 'onsubmit'=>'return validateName()'); 

//Find Resident
echo "<div align=\"center\">";
//.form_open('show/find_resident', $js);
echo "<h1>Find Resident</h1>";
echo "<table ><tr><td><h3>Search(min of 3 characters):</h3></td><td>";
echo "<input type = \"text\" name = \"rname\" id = \"search_r\" onkeyup= \"searchResidents('".$my_service."', '".$my_dispo."', '".$one_gm."', '".$stp1."')\" /></td>";

//.form_input($nf)."</td>";
//echo "<td>";
//make_buttons($my_service, $rb, $vars, "left", "center");
echo "</tr></table><hr />";
//echo form_close();
echo "<br />";

echo "<div id = \"residentTable\"></div>";
//Add new resident
echo form_open('add/insert_resident');
echo "<h1>Add New Resident</h1>";
echo "<font size=\"4\">Note: Only 'Active' Residents will be included in dropdown list for RIC.</font>"; 
$rn = array (
	  	'name' => 'rname',
		'size' => '35',
		);
echo "<table ><tr><td colspan = 2>Name:".form_input($rn)."</td><td>Date Started:</td><td>";
$ds = array (
	  	'name' => 'dstart',
		'size' => '10',
		);
//date picker
 //get class into the page		
require_once('calendar/classes/tc_calendar.php');
 $myCalendar = new tc_calendar("dstart", true, false);
 $myCalendar->setIcon("calendar/images/iconCalendar.gif");
 $myCalendar->setPath("calendar/");
 $myCalendar->setYearInterval(2008, 2020);
 $myCalendar->dateAllow('2008-01-01', '2020-01-01');
 $myCalendar->setDateFormat('j F Y');
 $myCalendar->setAlignment('right', 'top');
 $myCalendar->writeScript();
echo "</td><td>";	
echo " Status: ".form_radio('status', 'Y', TRUE)."Active  ".form_radio('status', 'N', FALSE)."Not Active</td><td>";
make_buttons("", $rb, $vars, "center", 'onClick = "return validateRedit(this.form)"');
echo "</td></tr></table>";
echo form_close()."</div>";
?>



  </body>
</html>
