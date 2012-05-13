<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Mon, 26 Sep 2011 23:00:30 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Manage Admissions</title>
    <style type="text/css">
	a{text-align:left; font-size:x-large; font-weight:bold;
	   }
	.occ{
		font-size:large;
		color:red;
		height:50px;
		width:210px;
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
//authorize user with uname and pword
$auth_list = $this->Users_model->get_all_users();
$csess = get_cookie('ci_session');
authorize_user($auth_list, $csess);
$uname = $_SERVER['PHP_AUTH_USER'];
$usvc = $this->Users_model->get_svc($uname);
foreach ($usvc as $us)
     $uservice = $us->svc;

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
     
make_page_header($_SERVER['PHP_AUTH_USER']);

echo "<div align=\"center\"><h1>View Reports Page</h1></div>";
echo "<hr />";
//clear session variable 'one'
$this->session->unset_userdata('one');

$fs = array('class'=>'title');
$gm = array('class'=>'menubb', 'value'=>'GO!'); 

//Gen Med census
echo "<div align=\"center\">";

//Occupy Report Button
echo form_open('census/occupy');
$wo = array('class'=>'occ', 'value'=>'Ward/ MICU Occupancy'); 
make_buttons("", $wo, "", "center", "");
echo form_close();

//Area Reports Button
echo form_open('census/area_reports');
$wo = array('class'=>'occ', 'value'=>'!Under Construction!'); 
make_buttons("", $wo, "", "center", "");
echo form_close();


echo "<table><tr><th>WARDS</th><th>ER</th><th>MICU</th></tr>";
echo "<tr><td>";
if ($uservice!=7 && $uservice!=8)
{
echo form_open('census/gm_census');
echo form_fieldset('Ward Admissions', $fs);
if ($uservice==1 || $uservice==11 ) 
    {
     echo "GM Service 1 </br>";
     echo form_hidden('my_service', '1');
    }
if ($uservice==2 || $uservice==22 )
    {
     echo "GM Service 2 </br>";
     echo form_hidden('my_service', '2');
    }
if ($uservice==3 || $uservice==33 )
    {
     echo "GM Service 3 </br>";
     echo form_hidden('my_service', '3');
    }
if ($uservice==4 || $uservice==44 )
    {
     echo "GM Service 4 </br>";
     echo form_hidden('my_service', '4');
    }
if ($uservice==5 || $uservice==55 )
    {
     echo "GM Service 5 </br>";
     echo form_hidden('my_service', '5');
    }
if ($uservice==6 || $uservice==66 )
    {
     echo "GM Service 6 </br>";
     echo form_hidden('my_service', '6');
    }
if (($uservice==0) || ($uservice==9) || ($uservice==77) || ($uservice==88))
    echo "Select GM Service ".form_dropdown('my_service',$this->config->item('census_service_list'))."<br />";

$d1 = array(
              'name'        => 'my_date1',
              'size'        => '8',
              
            );
$d2 = array(
              'name'        => 'my_date2',
              'size'        => '8',
              
            );

echo "Start Date (yyyy-mm-dd)";
echo form_input($d1)."</br>";
echo "End Date (yyyy-mm-dd)";
echo form_input($d2);
$js = 'onClick="return validatePeriod(this.form)"';
echo form_submit($gm,'', $js).form_close().form_fieldset_close();
}
echo "</td>";


echo "<td>";
if ($uservice==7 || $uservice==0 || $uservice==9 || $uservice == 77 || $uservice==88 )
{
echo form_open('census/gm_census');
echo form_fieldset('ER Admissions', $fs);
echo form_hidden('my_service', 'er');

$d1 = array(
              'name'        => 'my_date1',
              'size'        => '8',
              
            );
$d2 = array(
              'name'        => 'my_date2',
              'size'        => '8',
              
            );

echo "Start Date (yyyy-mm-dd)";
echo form_input($d1)."</br>";
echo "End Date (yyyy-mm-dd)";
echo form_input($d2);
$js = 'onClick="return validatePeriod(this.form)"';
echo form_submit($gm,'', $js).form_close().form_fieldset_close()."</td>";
}
echo "<td>";
if ($uservice==8 || $uservice==0 || $uservice==9 || $uservice == 88 || $uservice==77 )
{
echo form_open('census/gm_census');
echo form_fieldset('MICU Admissions', $fs);
echo form_hidden('my_service', 'micu');

$d1 = array(
              'name'        => 'my_date1',
              'size'        => '8',
              
            );
$d2 = array(
              'name'        => 'my_date2',
              'size'        => '8',
              
            );

echo "Start Date (yyyy-mm-dd)";
echo form_input($d1)."</br>";
echo "End Date (yyyy-mm-dd)";
echo form_input($d2);
$js = 'onClick="return validatePeriod(this.form)"';
echo form_submit($gm,'', $js).form_close().form_fieldset_close();
}
echo "</td></tr>";

//Ward ICD-PCP Report
echo "<tr><td>";
echo form_open('census/count_pcp');
echo form_hidden('area', 'ward');
echo form_fieldset('Count ICD-PCP Cases', $fs);
echo "Ward ICD-PCP Case Count<br />";
$d1 = array(
              'name'        => 'my_date1',

              'size'        => '8',
              
            );

$d2 = array(
              'name'        => 'my_date2',
              'size'        => '8',
              

            );

echo "Start Date (yyyy-mm-dd)";
echo form_input($d1)."</br>";

echo "End Date (yyyy-mm-dd)";
echo form_input($d2);
$js = 'onClick="return validatePeriod(this.form)"';
echo form_submit($gm,'', $js).form_close().form_fieldset_close()."</td>";

//ER ICD-PCP Report
echo "<td>";
echo form_open('census/count_pcp');
echo form_hidden('area', 'er');
echo form_fieldset('Count ICD-PCP Cases', $fs);
echo "Ward ICD-PCP Case Count<br />";
$d1 = array(
              'name'        => 'my_date1',
              'size'        => '8',
              
            );
$d2 = array(
              'name'        => 'my_date2',
              'size'        => '8',
              
            );

echo "Start Date (yyyy-mm-dd)";
echo form_input($d1)."</br>";
echo "End Date (yyyy-mm-dd)";
echo form_input($d2);
$js = 'onClick="return validatePeriod(this.form)"';
echo form_submit($gm,'', $js).form_close().form_fieldset_close();

echo "</td><td>";
echo form_open('census/count_pcp');
echo form_hidden('area', 'micu');
echo form_fieldset('Count ICD-PCP Cases', $fs);
echo "Ward ICD-PCP Case Count<br />";
$d1 = array(
              'name'        => 'my_date1',
              'size'        => '8',
              
            );
$d2 = array(
              'name'        => 'my_date2',
              'size'        => '8',
              
            );

echo "Start Date (yyyy-mm-dd)";
echo form_input($d1)."</br>";
echo "End Date (yyyy-mm-dd)";
echo form_input($d2);
$js = 'onClick="return validatePeriod(this.form)"';
echo form_submit($gm,'', $js).form_close().form_fieldset_close()."</td>";

echo "</td></tr>";


echo "</table></div>";
?>
  </body>
</html>
