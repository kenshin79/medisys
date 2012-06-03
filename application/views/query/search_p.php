<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Thu, 29 Sep 2011 14:22:54 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Add Admission - Step 1: Search Patient</title>
    <style type="text/css">
	</style>
	<script type="text/javascript" src="/medisys/calendar/calendar.js"></script>
	<link rel="stylesheet" type="text/css" href="/medisys/calendar/calendar.css" />
    <script type="text/javascript" src="/medisys/js/jquery.js"></script>
  	<script type="text/javascript" src="/medisys/js/my_jscripts.js"></script>
	<link rel="stylesheet" type="text/css" href="/medisys/css/menu.css" />
	<script type="text/javascript" src="/medisys/js/validate_form.js"></script>
    <!--[if IE]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>

<?php 
//this view, search_p is called from census as a first step before adding patients
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

if (!strcmp($stp1, "stp1"))
        echo "<div align=\"center\"><h3>Add Admission - Step 1: Search Patient</h3></div>";

echo "<hr />";
//search patient by name
echo "<div align=\"center\">";
echo "<h1>Find Patient</h1>";
$fn = array('id'=>'search_p', 'name'=>'pname');
echo "<table><tr><td><h3>Search name or case number(min of 3 characters):</h3></td><td>";
echo "<input type = \"text\" name = \"pname\" id = \"search_p\" onkeyup= \"searchPatients('".$my_service."', '".$my_dispo."', '".$one_gm."', '".$stp1."')\" /></td>";
echo "</td></tr></table>";
echo "</div>";
echo "<div id = \"patientTable\"></div>";

echo "</div>";
?>
  </body>
</html>
