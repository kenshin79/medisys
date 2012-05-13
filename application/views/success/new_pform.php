<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Sun, 25 Sep 2011 04:34:35 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Add/Edit New Patient</title>
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
//set user session variables
$my_service = $this->session->userdata('my_service');
$my_dispo = $this->session->userdata('my_dispo');
$one_gm = $this->session->userdata('one_gm');
$stp1 = $this->session->userdata('stp1');

//greeting user and logout link
make_page_header($_SERVER['PHP_AUTH_USER']);

echo form_open('menu');	
$vars = array('my_service'=>$my_service, 'my_dispo'=>$my_dispo, 'one_gm'=>$one_gm, 'stp1'=>$stp1);
$pb = array('name'=>'main_showp', 'value'=>'Back to Manage Patients', 'class'=>'menubb');
make_buttons($my_service, $pb, $vars, "center", "");
echo form_close();

echo "<hr />";
echo "<div align=\"center\">";
echo "<h1>You have successfully edited the following Patient Information:</h1>";
echo "<br />";

echo "<table><tr><th>General Data</th><th>Address</th><th>Problem List</th></tr>";
foreach($patient as $row){    
          echo "<tr><td><table>";
		  echo "<tr><td>Case No.</td><td>".revert_form_input($row->cnum)."</td></tr>";
		  echo "<tr><td>Name:</td><td>".revert_form_input($row->p_name)."</td></tr>";
		  echo "<tr><td>Sex:</td><td>".$row->p_sex."</td></tr>";
		  echo "<tr><td>Birth Date:</td><td>".revert_form_input($row->p_bday)."</td></tr>";
		  echo "<tr><td>Status:</td><td>".revert_form_input($row->adm_status)."</td></tr></table></td>";
		  echo "<td><textarea name = \"padd\" rows = \"8\" cols = \"35\" readonly =\"readonly\">".revert_form_input($row->p_add)."</textarea></td>";
		  echo "<td><textarea name = \"pplist\" rows = \"8\" cols = \"35\" readonly = \"readonly\">".revert_form_input($row->p_plist)."</textarea></td></tr>";
      }
echo "</table>";
echo "</div>";
  
  
?>  
  </body>
</html>
