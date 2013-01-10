<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Main Menu - MediSys</title>
    <link rel="stylesheet" type="text/css" href="css/menu.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery_ui.css" />
   	<script type="text/javascript" src="/medisys/js/jquery.js"></script>
   	<script type="text/javascript" src="/medisys/js/jquery_ui.js"></script>	
   	<script type="text/javascript" src="/medisys/js/my_jscripts.js"></script>
	<script>
		$(document).ready(function(){
            $("#version").hover(function() {
                    $(this).addClass('hover');
                    }, function() {
                    $(this).removeClass('hover');
                    }
                    );
            $(".btab").hover(function() {
                $(this).addClass('hover');
                }, function() {
                $(this).removeClass('hover');
                }
                );
			//openSysnotes();	
            showSysNotes(1);
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
//log update task in text file
$uname = $_SERVER['PHP_AUTH_USER'];
$usvc = $this->Users_model->get_svc($uname);
foreach ($usvc as $us)
     $uservice = $us->svc;
        //write login log
                                  $file = "userlogin_log.txt"; 
                                  $handle = fopen($file, 'a');
                                  date_default_timezone_set('Asia/Hong_Kong');
                                  $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: logged-in \r\n"; 
                                  fwrite($handle, $data);  	

//clear session variables
$this->session->unset_userdata('one_gm');
$this->session->unset_userdata('my_service');
$this->session->unset_userdata('my_dispo');
$this->session->unset_userdata('stp1');
$this->session->unset_userdata('pname');
$this->session->unset_userdata('epatient');
$this->session->unset_userdata('rname');
$this->session->unset_userdata('eresident');
//greeting user, logo and logout link
echo "<div>";
echo "<div align=\"left\"><b>Welcome User: ".$_SERVER['PHP_AUTH_USER']."!</b></div>";
echo "<div align=\"right\">".form_open('loguser/log_out')."<b>".form_submit('','Log-out Medisys','').form_close()."</b></div>";
echo "</div>";
echo "<div align=\"center\"><img src=\"/medisys/img/dept_logo2.bmp\"  id = \"logo\"></div>";
echo "<font size=\"7\" ><div align=\"center\" onmouseover = \"showCredits()\">Medicine Admissions Database System</div></font>";
echo "<div align=\"center\" id = \"version\" onclick = \"showInfo2()\">version 2.1!(April 2012)</div>";
echo "<div align=\"center\" style = \"color:red; font-size:large; font-weight:bold;\">If you have any feedback, comments and suggestions, please submit them <a href=\"/medisys/webpage/addcomment.html\" target=\"_blank\">here</a>.</div>";
echo "<div align=\"center\" style = \"color:red; font-size:large; font-weight:bold;\">To view user feedback, click <a href=\"/medisys/webpage/view_fback.html\" target=\"_blank\">here</a>.</div>";

//bulletin board
echo "<div style=\"border:2px solid black; position:relative;; left:50%; margin-left:-400px; margin-top:20px;width:800px; height:182px;\">";
echo "<div class = \"btab\" onclick = \"showSysNotes(1)\" style=\"text-align:center; font-weight:bold; position:relative; top:0%; left:50%; margin-left:-400px; width:200px; height:30px; background-color:#F0E68C; color:red;\">Sysadmin Bulletin</div>";
echo "<div class = \"btab\" onclick = \"showSysNotes(3)\" style=\"text-align:center; font-weight:bold; position:relative; top:0%; left:50%; margin-left:-200px; margin-top:-30px; width:200px; height:30px; background-color:#B0E0E6; color:blue; \">LU6 Board</div>";
echo "<div class = \"btab\" onclick = \"showSysNotes(2)\" style=\"text-align:center; font-weight:bold; position:relative; top:0%; left:50%; margin-left:0px; margin-top:-30px; width:200px; height:30px; background-color:#8B0000; color:white; \">LU7 Board</div>";
echo "<div class = \"btab\" onclick = \"showSysNotes(4)\" style=\"text-align:center; font-weight:bold; position:relative; top:0%; left:50%; margin-left:200px; margin-top:-30px; width:200px; height:30px; background-color:#DDA0DD; color:#8A2BE2; text-decoration:blink; \">Quiz Board</div>";
echo "<div class = \"scrollbulletin\" id=\"sysnotes\"  style=\"top:-30px; border-top:2px solid black; background-color:#DDEBDD; color:black;\"></div>";
echo "</div>";

echo "<div style=\"position:relative; top:0%; margin-top:20px\">";
echo "<div align=\"center\"><h3 style=\"color:red;\">Click on bulb to submit answers to quiz. <a href=\"/medisys/webpage/answerSubmit.html\" target=\"_blank\"><img src = \"/medisys/img/lbulb.jpeg\" width=\"20\" height=\"20\"</a></h3></div>";
echo "<div align=\"center\" ><a  style=\"font-weight:bold; color:red; font-size:large;\" href = \"/medisys/webpage/gmservices.html\" target = \"_blank\">Gen Med Roster for the Month</a></div>";

//menu table
echo "<table class=\"center\" >";
echo "<tr><th><h1>MAIN MENU</h1></th></tr>";
echo "<div align=\"center\"><b>Today is: ".mdate("%M-%d-%Y")."</b></div>";
echo "<tr><td>";
echo "<font size=\"5\">General Medicine Service Census</font><br />";


echo "<div align=\"center\"><table><tr>";
echo "<div align=\"center\">";

    //one_gm of 'y' means census for 1 genmed svc or micu , er
//svc1
if (($uservice == 1) || ($uservice == 0) || ($uservice == 11))
{
    echo "<td>".form_open('census/one_gm_census');
    $vars = array('my_service'=>'1', 'my_dispo'=>'Admitted', 'one_gm'=>'y', 'stp1'=>"stp1");
    $label = array ('class'=>'menubb', 'name'=>'get1', 'value'=>'I');				
    make_buttons(1, $label, $vars, "", "");	
    echo form_close();
    echo "</td>";
}
//svc2
if (($uservice == 2) || ($uservice == 0) || ($uservice == 22))
{
    echo "<td>".form_open('census/one_gm_census');
    $vars = array('my_service'=>'2', 'my_dispo'=>'Admitted', 'one_gm'=>'y', 'stp1'=>"stp1");
    $label = array ('class'=>'menubb', 'name'=>'get2', 'value'=>'II');	
    make_buttons(2, $label, $vars, "", "");	
    echo form_close();
    echo "</td>";
}
//svc3
if (($uservice == 3) || ($uservice == 0) || ($uservice == 33))
{
    echo "<td>".form_open('census/one_gm_census');
    $vars = array('my_service'=>'3', 'my_dispo'=>'Admitted', 'one_gm'=>'y', 'stp1'=>"stp1");
    $label = array ('class'=>'menubb', 'name'=>'get3', 'value'=>'III');	
    make_buttons(3, $label, $vars, "", "");	
    echo form_close();
    echo "</td>";
}
//svc4
if (($uservice == 4) || ($uservice == 0) || ($uservice == 44))
{
    echo "<td>".form_open('census/one_gm_census');
    $vars = array('my_service'=>'4', 'my_dispo'=>'Admitted', 'one_gm'=>'y', 'stp1'=>"stp1");
    $label = array ('class'=>'menubb', 'name'=>'get4', 'value'=>'IV');	
    make_buttons(4, $label, $vars, "", "");	
    echo form_close();
    echo "</td>";
}
//svc5
if (($uservice == 5) || ($uservice == 0) || ($uservice == 55))
{
    echo "<td>".form_open('census/one_gm_census');
    $vars = array('my_service'=>'5', 'my_dispo'=>'Admitted', 'one_gm'=>'y', 'stp1'=>"stp1");
    $label = array ('class'=>'menubb', 'name'=>'get5', 'value'=>'V');	
    make_buttons(5, $label, $vars, "", "");	
    echo form_close();
    echo "</td>";
}
//svc6
if (($uservice == 6) || ($uservice == 0) || ($uservice == 66))
{
    echo "<td>".form_open('census/one_gm_census');
    $vars = array('my_service'=>'6', 'my_dispo'=>'Admitted', 'one_gm'=>'y', 'stp1'=>"stp1");
    $label = array ('class'=>'menubb', 'name'=>'get6', 'value'=>'VI');	
    make_buttons(6, $label, $vars, "", "");	
    echo form_close();
    echo "</td>";
}
echo "</table>";
if ($uservice != 9)
    echo "Update Current Charity Service Census</tr>";
echo "</div>";
//ER
if (($uservice == 7) || ($uservice == 0) || ($uservice == 77) )
{
    echo "<tr><td>".form_open('census/one_gm_census');
    $vars = array('my_service'=>'er', 'my_dispo'=>'Admitted', 'one_gm'=>'y', 'stp1'=>"stp1");
    $label = array ('class'=>'menub', 'name'=>'er_census', 'value'=>'ER Census');	
    make_buttons('er', $label, $vars, "", "");	
    echo "(Update ER Census)</td></tr>";
    echo form_close();
}

//MICU
if (($uservice == 8) || ($uservice == 0) || ($uservice == 88))
{
    echo "<tr><td>".form_open('census/one_gm_census');
    $vars = array('my_service'=>'micu', 'my_dispo'=>'Admitted', 'one_gm'=>'y', 'stp1'=>"stp1");
    $label = array ('class'=>'menub', 'name'=>'micu_census', 'value'=>'MICU Census');	
    make_buttons('er', $label, $vars, "", "");	
    echo "(Update MICU Census)</td></tr>";
    echo form_close();
}
//pre-op patients
if (($uservice == 11) || ($uservice == 22) || ($uservice == 33) || ($uservice == 44) || ($uservice == 55) || ($uservice == 66) || ($uservice == 77) || ($uservice == 88) || ($uservice == 0))
//if (($uservice == 99) || ($uservice == 0))
{
    echo "<tr><td>".form_open('census/one_gm_census');
    $vars = array('my_service'=>'preop', 'my_dispo'=>'Admitted', 'one_gm'=>'y', 'stp1'=>"stp1");
    $label = array ('class'=>'menub', 'name'=>'preop_census', 'value'=>'Pre-operative');
    make_buttons("", $label, $vars, "", "");	
    echo "(Update Pre-operative Census)</td></tr>";
    echo form_close();
}

//Manage Admissions
echo "<tr><td>".form_open('menu');
$vars = array('my_service'=>'', 'my_dispo'=>'', 'one_gm'=>'n', 'stp1'=>'');
$label = array ('class'=>'menub', 'name'=>'main_showa', 'value'=>'View Reports');
make_buttons("", $label, $vars, "", "");	
echo "(Create Reports/Graphs)</td></tr>";
echo form_close();

//Manage Residents
if (($uservice == 11) || ($uservice == 22) || ($uservice == 33) || ($uservice == 44) || ($uservice == 55) || ($uservice == 66) || ($uservice == 77) || ($uservice == 88) || ($uservice == 0) || ($uservice == 9)){
     echo "<tr><td>".form_open('menu');
     $label = array('class'=>'menub', 'name'=>'main_showr', 'value'=>'Manage Residents');
     $vars = array('my_service'=>"All", 'my_dispo'=>"", 'one_gm'=>"res", 'stp1'=>"");	
     make_buttons("", $label, $vars, "center", "");	
     echo "(Add-Edit Resident Info/View Resident Admissions)</td></tr>";
     echo form_close();
     
}
//Manage Patients
if (($uservice!=9) && ($uservice!=999))
{
    echo "<tr><td>".form_open('menu');
    $label = array ('class'=>'menub', 'name'=>'main_showp', 'value'=>'Manage Patients');	
    $vars = array('my_service'=>"All", 'my_dispo'=>"", 'one_gm'=>"px", 'stp1'=>"");	
    make_buttons("", $label, $vars, "center", "");	
    echo "(Edit Patient Info/View Patient Admissions)</td></tr>";
    echo form_close();
}

echo "</table>";
echo "</div></div>";
?>

<div align="center"><font size="2">Section of Adult Medicine - Philippine General Hospital</font></div>
<div align="center"><a href="http://adultmedicinepgh.appspot.com">Visit our website</a></div>
<div align="center"><font size="2">IM Works! - Medicine Online Forums</font></div>
<div align="center"><a href="/im_works" target="_blank">Visit the forums!</a></div>
<div align="center"><font size="3">For your valuable comments and suggestions, email us at lu7.medicine@gmail.com. Thank you!</font></div>
<div align="center"><font size="1">created by: Homer Uy Co, MD</font></div>
  </body>
</html>
