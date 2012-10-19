<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Resident Reports</title>
	<style type="text/css">
	a{text-align:left; font-size:x-large; font-weight:bold;
	   }
	#greport{background-color:green; width:200px;}   
	</style>
    <script type="text/javascript" src="/medisys/js/jquery.js"></script>
	<script type="text/javascript" src="/medisys/calendar/calendar.js"></script>	
  	<script type="text/javascript" src="/medisys/js/my_jscripts.js"></script>	
	<link rel="stylesheet" type="text/css" href="/medisys/css/menu.css" />
	<link rel="stylesheet" type="text/css" href="/medisys/calendar/calendar.css" />
	<script type="text/javascript" src="/medisys/js/validate_form.js"></script>
	<script>
		$(document).ready(function(){
            $("#greport").hover(function() {
                    $(this).addClass('hover');
                    }, function() {
                    $(this).removeClass('hover');
                    }
                    );
	    });
    </script>
					
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
$rname = $this->session->userdata('rname');
$eresident = $this->session->userdata('eresident');
$vars = array( 
	 	    	    'my_service'=>$my_service,
	    	        'my_dispo'=>$my_dispo,
					'one_gm'=>$one_gm,
					'stp1'=>$stp1,
					'rname'=>$rname,
					'$eresident'=>$eresident,
				 );

				 $js = 'onClick="return validatePeriod(this.form)"';

//greeting user and logout link
make_page_header($_SERVER['PHP_AUTH_USER']);
//back button
echo form_open('menu');
     $label = array('class'=>'menuback', 'name'=>'main_showr', 'value'=>'Back to Manage Residents');
     $vars = array('my_service'=>"All", 'my_dispo'=>"", 'one_gm'=>"res", 'stp1'=>"");	
     make_buttons("", $label, $vars, "center", "");	
echo form_close();
echo "<div align=\"center\"><h1>Resident Reports: ".$rname."</h1>";
echo "<table><tr><th>Select Period:</th>";
//date picker
echo form_open();
require_once('calendar/classes/tc_calendar.php');
echo "<th>";
      $myCalendar = new tc_calendar("my_datea", true, false);
	  $myCalendar->setIcon("/medisys/calendar/images/iconCalendar.gif");
	  $myCalendar->setPath("/medisys/calendar/");
	  $myCalendar->setYearInterval(2011, 2020);
	  $myCalendar->setAlignment('left', 'bottom');
	  $myCalendar->setDatePair('my_datea', 'my_dateb');
	  $myCalendar->writeScript();	  
echo "</th>";
echo "<th>";
	  $myCalendar = new tc_calendar("my_dateb", true, false);
	  $myCalendar->setIcon("/medisys/calendar/images/iconCalendar.gif");
	  $myCalendar->setPath("/medisys/calendar/");
	  $myCalendar->setYearInterval(2011, 2020);
	  $myCalendar->setAlignment('left', 'bottom');
	  $myCalendar->setDatePair('my_datea', 'my_dateb');
	  $myCalendar->writeScript();	  
echo "</th>"; 
echo "<th id = \"greport\" onclick = \"getresReport(my_datea, my_dateb, '".$eresident."', 'All', '".$my_dispo."', '".$one_gm."', '".$stp1."')\">Click to Get Report</th>";
echo form_close();
echo "</tr></table>";
echo "</div>";
echo "<hr />";

echo "<div id = \"selected_report\"></div>";

?>
</body>
</html>
