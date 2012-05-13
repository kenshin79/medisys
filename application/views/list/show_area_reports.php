<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Wed, 30 Nov 2011 03:47:05 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Ward/MICU Occupancy</title>
    
    <style type="text/css">
    .s1{background-color:white}
    .s2{background-color:pink}
    .s3{background-color:yellow}
    .s4{background-color:green}
    .s5{background-color:blue}
    .s6{background-color:orange}	
    .s7{background-color:maroon; color:white}	
    td{font-size:medium; font-weight:bold }	
	
    </style>
    <!--[if IE]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js">
    </script>
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
echo "<div style=\" float:left\"><b>Welcome User: ".$_SERVER['PHP_AUTH_USER']."!</b></div>";
echo "<div style=\"float:right\">".form_open('loguser/log_out')."<b>".form_submit('','Log-out Medisys','').form_close()."</b></div>";
echo "</div>";
echo "<a href=\"/medisys\">Main Menu</a>";


?>
