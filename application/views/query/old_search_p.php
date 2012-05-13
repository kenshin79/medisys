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
	a{text-align:left; font-size:x-large; font-weight:bold;
	   }
	</style>
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
        echo "<div align=\"center\"><h1>Add Admission - Step 1: Search Patient</h1></div>";

echo "<hr />";
//search patient by name
$js1 = array('name'=>'find_pname', 'onsubmit'=>'return validatePname()'); 
echo "<div align=\"center\">".form_open('show/find_pname', $js1);
echo "<h1>Find Patient</h1>";
echo "<table><tr><td><h3>Search in Name:</h3></td><td>".form_input('pname','')."</td>";
$label = array('name'=>"find_pname", 'value'=>'GO!');
echo "<td>";
make_buttons($my_service, $label, $vars, "center", "");
echo form_close();
echo "</td></tr>";

//search patient by cnum
$js2 = array('name'=>'find_pcnum', 'onsubmit'=>'return validatePcnum()'); 
echo form_open('show/find_pcnum', $js2);
echo "<tr><td><h3>Search in Case Number:</h3></td><td>".form_input('cnum','')."</td>";
$label = array('name'=>"find_pcnum", 'value'=>'GO!');
echo "<td>";
make_buttons($my_service, $label, $vars, "center", "");
echo form_close();
echo "</td></tr></table>";
echo "</div>";
?>
  </body>
</html>
