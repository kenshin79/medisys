<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Mon, 26 Sep 2011 13:51:57 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Success! - New Resident Added</title>
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
//greeting user and logout link
echo "<div>";
echo "<div align=\"left\"><b>Welcome User: ".$_SERVER['PHP_AUTH_USER']."!</b></div>";
echo "<div align=\"right\">".form_open('loguser/log_out')."<b>".form_submit('','Log-out Medisys','').form_close()."</b></div>";
echo "</div>";

echo "<a href=\"/medisys\">Main Menu</a>";  
echo "<div align=\"center\"><h1>New Resident Added</h1>";  
echo "<table><tr><th>Resident Name</th><th>Date Started</th><th>Status</th></tr>";
echo "<tr><td>".revert_form_input($r_name)."</td>";
echo "<td>".revert_form_input($dstart)."</td>";
echo "<td>".revert_form_input($status)."</td></tr>";
echo "</table></div>";
?>  
  </body>
</html>
