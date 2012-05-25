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

$e1 = 0;
$e2 = 0;
$e3 = 0;
$e4 = 0;
$e5 = 0;
$e6 = 0;


$s1w1 = 0;
$s2w1 = 0;
$s3w1 = 0;
$s4w1 = 0;
$s5w1 = 0;
$s6w1 = 0;

$s1w3 = 0;
$s2w3 = 0;
$s3w3 = 0;
$s4w3 = 0;
$s5w3 = 0;
$s6w3 = 0;

$s1m = 0;
$s2m = 0;
$s3m = 0;
$s4m = 0;
$s5m = 0;
$s6m = 0;

$enum = count($er_occupied);
$em = 0;
$ef = 0;

$v1 = 0;
$v3 = 0;
$vm = 0;
$v1_beds = array();
$v3_beds = array();
$micu_beds = array();

$class = "error";
foreach ($er_occupied as $row)
{
      $pdata = $this->Patient_model->get_one_patient($row->p_id);
      foreach ($pdata as $patient){
	if (!strcmp($patient->p_sex, 'M'))
		$em++;
	else
		$ef++;
      }
        if (!strcmp($row->service, '1'))
		$e1++;
	elseif (!strcmp($row->service, '2'))
		$e2++;
	elseif (!strcmp($row->service, '3'))
		$e3++;
	elseif (!strcmp($row->service, '4'))
		$e4++;
	elseif (!strcmp($row->service, '5'))
		$e5++;
	elseif (!strcmp($row->service, '6'))
		$e6++;
      
}

for ($x=1; $x<49; $x++)
{
        $y = 0;
	foreach ($w1_occupied as $row)
	{
	 if ($row->bed == $x){
		$y++;
		if (!strcmp($row->service, '1'))
			$s1w1++;
		elseif (!strcmp($row->service, '2'))
			$s2w1++;
		elseif (!strcmp($row->service, '3'))
			$s3w1++;
		elseif (!strcmp($row->service, '4'))
			$s4w1++;
		elseif (!strcmp($row->service, '5'))
			$s5w1++;
		elseif (!strcmp($row->service, '6'))
			$s6w1++;
	        }
        }
	if ($y == 0){	
		$v1++;
		$v1_beds[] = $x; 
	}
}
for ($x=1; $x<45; $x++)
{
        $y = 0;
	foreach ($w3_occupied as $row)
	{
	 if ($row->bed == $x){
		$y++;
                if (!strcmp($row->service, '1'))
			$s1w3++;
		elseif (!strcmp($row->service, '2'))
			$s2w3++;
		elseif (!strcmp($row->service, '3'))
			$s3w3++;
		elseif (!strcmp($row->service, '4'))
			$s4w3++;
		elseif (!strcmp($row->service, '5'))
			$s5w3++;
		elseif (!strcmp($row->service, '6'))
			$s6w3++;
	        }
	 
        }
	if ($y == 0){	
		$v3++;
		$v3_beds[] = $x; 
	}
}
for ($x=1; $x<13; $x++)
{
        $y = 0;
	foreach ($micu_occupied as $row)
	{
	 if ($row->bed == $x){
		$y++;
		if (!strcmp($row->service, '1'))
			$s1m++;
		elseif (!strcmp($row->service, '2'))
			$s2m++;
		elseif (!strcmp($row->service, '3'))
			$s3m++;
		elseif (!strcmp($row->service, '4'))
			$s4m++;
		elseif (!strcmp($row->service, '5'))
			$s5m++;
		elseif (!strcmp($row->service, '6'))
			$s6m++;
	        }
	 
        }
	if ($y == 0){	
		$vm++;
		$micu_beds[] = $x; 
	}
}
echo "<table border = 1 bgcolor = #999966 style=\"border:1px\"><tr><th>Gen Med Service Legend:</th><td class = \"s1\">Service I</td><td class = \"s2\">Service II</td><td class = \"s3\">Service III</td><td class = \"s4\">Service IV</td><td class = \"s5\">Service V</td><td class = \"s6\">Service VI</td></tr></table>";
echo "</br>";
echo "<table border = 1 bgcolor = #999966 style=\"border:1px\"><tr><th>ER Patients</th><th>Total: <font style=\"color:#CCFFCC\">".$enum."</font></th><th>Male: <font style=\"color:#CCFFCC\">".$em."</font></th><th>Female: <font style=\"color:#CCFFCC\">".$ef."</font></th><th>GM 1: <font style=\"color:#CCFFCC\">".$e1."</font></th><th>GM 2: <font style=\"color:#CCFFCC\">".$e2."</font></th><th>GM 3: <font style=\"color:#CCFFCC\">".$e3."</font></th><th>GM 4: <font style=\"color:#CCFFCC\">".$e4."</font></th><th>GM 5: <font style=\"color:#CCFFCC\">".$e5."</font></th><th>GM 6: <font style=\"color:#CCFFCC\">".$e6."</font></th></tr></table>";
echo "</br>";
echo "<div align=\"center\" >";
echo "<table border = 1 bgcolor = #999966 style=\"border:1px\"><tr><th>Location</th><th>GM service: #Beds</th><th>No. of Vacant Beds</th><th>Vacant Bed Numbers</th></tr>";
echo "<tr><td>Ward 1</td><td><font style=\"color:white\">GM1:</font>".$s1w1.";<font style=\"color:pink\">GM2:</font>".$s2w1.";<font style=\"color:yellow\">GM3:</font>".$s3w1.";<font style=\"color:green\">GM4:</font>".$s4w1.";<font style=\"color:blue\">GM5:</font>".$s5w1.";<font style=\"color:orange\">GM6:</font>".$s6w1."</td><td>".$v1."</td><td>".implode(',', $v1_beds)."</td></tr>";
echo "<tr><td>Ward 3</td><td><font style=\"color:white\">GM1:</font>".$s1w3.";<font style=\"color:pink\">GM2:</font>".$s2w3.";<font style=\"color:yellow\">GM3:</font>".$s3w3.";<font style=\"color:green\">GM4:</font>".$s4w3.";<font style=\"color:blue\">GM5:</font>".$s5w3.";<font style=\"color:orange\">GM6:</font>".$s6w3."</td><td>".$v3."</td><td>".implode(',', $v3_beds)."</td></tr>";
echo "<tr><td>MICU</td><td><font style=\"color:white\">GM1:</font>".$s1m.";<font style=\"color:pink\">GM2:</font>".$s2m.";<font style=\"color:yellow\">GM3:</font>".$s3m.";<font style=\"color:green\">GM4:</font>".$s4m.";<font style=\"color:blue\">GM5:</font>".$s5m.";<font style=\"color:orange\">GM6:</font>".$s6m."</td><td>".$vm."</td><td>".implode(',', $micu_beds)."</td></tr>";
echo "</table></div>";
echo "</br>";

echo "<div align=\"center\" style=\"width:1800px\">";
echo "<div style=\"float:left\">";
echo "<table border=1 bgcolor=\"violet\"><tr><th>Ward 1</th><th></th><th></th><th></th></tr>";
echo "<tr><th>Bed#</th><th>GM</th><th>Patient Name</th><th>HD</th></tr>";
for ($x=1; $x<49; $x++)
{
    $y = 0;
    
    foreach ($w1_occupied as $row1)
    { 
	if (!strcmp($row1->service, '1'))
		$class = "s1";
	elseif (!strcmp($row1->service, '2'))
		$class = "s2";
	elseif (!strcmp($row1->service, '3'))
		$class = "s3";
	elseif (!strcmp($row1->service, '4'))
		$class = "s4";
	elseif (!strcmp($row1->service, '5'))
		$class = "s5";
	elseif (!strcmp($row1->service, '6'))
		$class = "s6";
        
        $hd = compute_hd($row1->dispo, revert_form_input($row1->date_in), revert_form_input($row1->date_out));
	$age = compute_age_adm(revert_form_input($row1->date_in), revert_form_input($row1->p_bday));	
	if ($row1->bed == $x){
                if ($y == 0) 
		    echo "<tr><td class = \"".$class."\"><font size=5>".$x."</font></td><td class = \"".$class."\">GM: ".$row1->service."</td><td class = \"".$class."\">".revert_form_input($row1->p_name)." ".$age."/".$row1->p_sex."</td><td class = \"".$class."\">".$hd."</td></tr>";
		else
		    echo "<tr><td class = \"".$class."\"><font size=5>".$x."</font></td><td class = \"".$class."\">GM: ".$row1->service."</td><td class = \"".$class."\">".revert_form_input($row1->p_name)." ".$age."/".$row1->p_sex."</td><td class = \"".$class."\">".$hd."</td></tr>";
	        $y++;
        }
     }	
     
     if ($y == 0){
		echo "<tr><td class = \"s7\"><font size=5>".$x."</font></td><td class = \"s7\">--</td><td class = \"s7\">Vacant</td><td class = \"s7\">--</td></tr>";
		$v1_beds[] = $x;
		$v1++;
     }
}

echo "</table></div>";

echo "<div style=\"float:left\">";
echo "<table border=1 bgcolor=\"brown\"><tr><th>Ward 3</th><th></th><th></th><th></th></tr>";
echo "<tr><th>Bed#</th><th>GM</th><th>Patient Name</th><th>HD</th></tr>";
for ($x=1; $x<51; $x++)
{
    $y = 0;
  	
    foreach ($w3_occupied as $row3)
    { 

	if (!strcmp($row3->service, '1'))
		$class = "s1";
	elseif (!strcmp($row3->service, '2'))
		$class = "s2";
	elseif (!strcmp($row3->service, '3'))
		$class = "s3";
	elseif (!strcmp($row3->service, '4'))
		$class = "s4";
	elseif (!strcmp($row3->service, '5'))
		$class = "s5";
	elseif (!strcmp($row3->service, '6'))
		$class = "s6";
	else
		$class = "s7";
        $hd = compute_hd($row3->dispo, revert_form_input($row3->date_in), revert_form_input($row3->date_out));
	$age = compute_age_adm(revert_form_input($row3->date_in), revert_form_input($row3->p_bday));	
	if ($row3->bed == $x){
                if ($y == 0) 
		    echo "<tr><td class = \"".$class."\"><font size=5>".$x."</font></td><td class = \"".$class."\">GM: ".$row3->service."</td><td class = \"".$class."\"><a href=\"\">".revert_form_input($row3->p_name)." ".$age."/".$row3->p_sex."</a></td><td class = \"".$class."\">".$hd."</td></tr>";
		else
		    echo "<tr><td class = \"".$class."\"><font size=5>".$x."</font></td><td class = \"".$class."\">GM: ".$row3->service."</td><td class = \"".$class."\">".revert_form_input($row3->p_name)." ".$age."/".$row3->p_sex."</td><td class = \"".$class."\">".$hd."</td></tr>";
	        $y++;
        }
     }	
     if ($y == 0){
		echo "<tr><td class = \"s7\"><font size=5>".$x."</font></td><td class = \"s7\">--</td><td class = \"s7\">Vacant</td><td class = \"s7\">--</td></tr>";
		$v3_beds[] = $x;
		$v3++;
     }
}

echo "</table></div>";

echo "<div style=\"float:left\">";
echo "<table border=1 bgcolor=\"gray\"><tr><th>MICU</th><th></th><th></th><th></th></tr>";
echo "<tr><th>Bed#</th><th>GM</th><th>Patient Name</th><th>HD</th></tr>";

for ($x=1; $x<13; $x++)
{
    $y = 0;

    foreach ($micu_occupied as $rowm)
    { 
	if (!strcmp($rowm->service, '1'))
		$class = "s1";
	elseif (!strcmp($rowm->service, '2'))
		$class = "s2";
	elseif (!strcmp($rowm->service, '3'))
		$class = "s3";
	elseif (!strcmp($rowm->service, '4'))
		$class = "s4";
	elseif (!strcmp($rowm->service, '5'))
		$class = "s5";
	elseif (!strcmp($rowm->service, '6'))
		$class = "s6";
	else
		$class = "s7";

        $hd = compute_hd(revert_form_input($rowm->dispo), revert_form_input($rowm->date_in), revert_form_input($rowm->date_out));
	$age = compute_age_adm(revert_form_input($rowm->date_in), revert_form_input($rowm->p_bday));	
	if ($rowm->bed == $x){
                if ($y == 0) 
		    echo "<tr><td class = \"".$class."\"><font size=5>".$x."</font></td><td class = \"".$class."\">GM: ".$rowm->service."</td><td class = \"".$class."\">".revert_form_input($rowm->p_name)." ".$age."/".$rowm->p_sex."</td><td class = \"".$class."\">".$hd."</td></tr>";
		else
		    echo "<tr><td class = \"".$class."\"><font size=5>".$x."</font></td><td class = \"".$class."\">GM: ".$rowm->service."</td><td class = \"".$class."\">".revert_form_input($rowm->p_name)." ".$age."/".$rowm->p_sex."</td><td class = \"".$class."\">".$hd."</td></tr>";
	        $y++;
        }
     }	
     if ($y == 0){
		echo "<tr><td class = \"s7\"><font size=5>".$x."</font></td><td class = \"s7\">--</td><td class = \"s7\">Vacant</td><td class = \"s7\">--</td></tr>";
		$micu_beds[] = $x;
                $vm++;
     }
}

echo "</table></div>";


echo "</div>";
