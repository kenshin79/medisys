<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Sun, 25 Sep 2011 04:34:35 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Add New Resident</title>
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
//greeting user and logout link
echo "<div>";
echo "<div align=\"left\"><b>Welcome User: ".$_SERVER['PHP_AUTH_USER']."!</b></div>";
echo "<div align=\"right\">".form_open('loguser/log_out')."<b>".form_submit('','Log-out Medisys','').form_close()."</b></div>";
echo "</div>";
echo "<a href=\"/medisys\">Main Menu</a>";


$rb = array('name'=>'main_showr', 'value'=>'Back to Manage Residents', 'class'=>'menubb');

    echo form_open('menu');	
	echo "<div align=\"center\">".form_submit($rb,'','');
	echo form_close();
echo "<hr />";
echo "<h1>You have successfully edited the following Resident Information:</h1>";
echo "<br />";
echo "<table><tr><th>Resident Name</th><th>Date Started</th><th>Active</th></tr>";
foreach($resident as $row){
echo "<tr><td>".revert_form_input($row->r_name)."</td>";
echo "<td>".revert_form_input($row->dstart)."</td>";
echo "<td>".revert_form_input($row->status)."</td></tr>";
}
echo "</table></div>";
  
?>  
  </body>
</html>
