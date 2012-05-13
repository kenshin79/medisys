<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Tue, 27 Sep 2011 13:31:05 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Success! - Delete Admission</title>
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
//greetings and logout
make_page_header($_SERVER['PHP_AUTH_USER']);

    
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

    echo form_open('census/one_gm_census');
     $ea = array('value'=>'Back to Admissions', 'class'=>'menubb');
     make_buttons($my_service, $ea, $vars, "center", "");
    echo form_close();
    	
  if ($query)
   		echo "<h1>You have successfully DELETED one (1) Admission Record.</h1>";
  else
	    echo "<h1>Unable to delete this Admission Record.</h1>"; 	 
echo "</div>";  
?>
  </body>
</html>
