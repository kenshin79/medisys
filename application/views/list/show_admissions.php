<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Sun, 25 Sep 2011 04:34:35 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Search Admissions and Stats</title>
	<style type="text/css">
	
	</style>
	<link rel="stylesheet" type="text/css" href="/medisys/calendar/calendar.css" />		
	<link rel="stylesheet" type="text/css" href="/medisys/css/main.css" />
	<script type="text/javascript" src="/medisys/calendar/calendar.js"></script>		
	<script type="text/javascript" src="/medisys/js/validate_form.js"></script>
	<script type="text/javascript" src="/medisys/js/jscharts.js"></script>

    <!--[if IE]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

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
$vars = array( 
	 	    	    'my_service'=>$my_service,
	    	        'my_dispo'=>$my_dispo,
					'one_gm'=>$one_gm,
					'stp1'=>$stp1,
				 );
//greet user				 
make_page_header($_SERVER['PHP_AUTH_USER']);
				 
//if from manage resident
if (!strcmp($one_gm, "res")){
    $eresident = $this->session->userdata('eresident');
    $rname = $this->session->userdata('rname');
    $vars['eresident'] = $eresident;
    $vars['rname'] = $rname;
}

//if from manage patient
if (!strcmp($one_gm, "px")){
    $epatient = $this->session->userdata('epatient');
    $pname = $this->session->userdata('pname');
    $vars['epatient'] = $epatient;
    $vars['pname'] = $pname;
}

//if from manage admissions
if (!strcmp($one_gm, "n")){
    $my_date1 = $this->session->userdata('my_date1');
    $my_date2 = $this->session->userdata('my_date2');
    $vars['my_date1'] = $my_date1;
    $vars['my_date2'] = $my_date2;   
}
//if from manage admissions
if (!strcmp($one_gm, "n")){
	echo "<div align=\"center\"><h1>Admissions Stats and Figures (".$my_date1." to ".$my_date2.")</h1>";
	if (strcmp($my_service, 'micu') && strcmp($my_service, 'er'))
		echo "<h2>Gen Med Service:".strtoupper($my_service)."</h2>";
	else
		echo "<h2>".strtoupper($my_service)." Service</h2>";
	
}
$mc = array('value'=>'Make Gen Med Census!', 'class'=>'menubb');
$ab = array('name'=>'main_showa', 'value'=>'Back to View Reports', 'class'=>'menubb');
if (!strcmp($my_service, 'er'))
     $ea = array('value'=>'Back to ER Admissions', 'class'=>'menubb');
elseif (!strcmp($my_service, 'micu'))
     $ea = array('value'=>'Back to MICU Admissions', 'class'=>'menubb');
else
     $ea = array('value'=>'Back to Ward Admissions', 'class'=>'menubb');
$mp = array('name'=>'main_showp', 'value'=>'Back to Manage Patients', 'class'=>'menubb');
$mr = array('name'=>'main_showr', 'value'=>'Back to Manage Residents', 'class'=>'menubb');

    $numrows = count($c_admissions);
    

if (!strcmp($one_gm, "n")){
	if($c_admissions){
	
		if (strcmp($my_service, 'er') && strcmp($my_service, 'micu')){	
			foreach ($admission_types as $row){
				echo "<div align=\"center\" >";
                echo "<table><tr><th colspan = 3>Types of Admissions</th></tr>";
				echo "<tr><th>Admission Type</th><th>Total</th><th>%</th></tr>";
                echo "<tr><td>Primary</td><td>".$row->prime."</td><td>".round((($row->prime/$row->total)*100), 2)."</td></tr>";
                echo "<tr><td>Co-Managed</td><td>".$row->comx."</td><td>".round((($row->comx/$row->total)*100), 2)."</td></tr>"; 
                echo "<tr><td>Peri-Operative</td><td>".$row->preop."</td><td>".round((($row->preop/$row->total)*100), 2)."</td></tr></table>";
				
		    //Admission Type graph
                echo "<div id = \"typetable\" align=\"center\"></div>";         
                echo "<script type=\"text/javascript\">";
                echo "var myData = new Array(['Primary (".round((($row->prime/$row->total)*100), 2)."%)', ".$row->prime."], ['Co-Managed (".round((($row->comx/$row->total)*100), 2)."%)', ".$row->comx."], ['Pre-op (".round((($row->preop/$row->total)*100), 2)."%)', ".$row->preop."]);";
                echo "var myChart = new JSChart('typetable', 'pie');";
                echo "myChart.setDataArray(myData);";
                echo "myChart.setTitle('Admission Type Pie Chart');";
                echo "myChart.setPieUnitsOffset(20);";
                echo "myChart.setPieRadius(300/3.75);";
                echo "myChart.setBackgroundColor('#CCCC99');";
                echo "myChart.setTitleColor('#000000');";
                echo "myChart.draw();";
                echo "myChart.resize(400, 300)";
                echo "</script>";
				
				echo "</div>"; 
         }
				
				
                
         }
		
	
		foreach ($c_admissions as $row){ 
			echo "<div>";
				//hospital days table
            echo "<div style = \"float:left\">";
            echo "<table><tr><th colspan = 3>Number of Hospital Days Before Disposition</th></tr>";
			echo "<tr><th>Hospital days</th><th>Total</th><th>%</th></tr>";
			echo "<tr><td>1 day or less</td><td>".$row->hd1."</td><td>".round((($row->hd1/$row->disposed)*100),2)."</td></tr>";
            echo "<tr><td>2 days</td><td>".$row->hd2."</td><td>".round((($row->hd2/$row->disposed)*100),2)."</td></tr>";
            echo "<tr><td>3 days</td><td>".$row->hd3."</td><td>".round((($row->hd3/$row->disposed)*100),2)."</td></tr>";
            echo "<tr><td>4 days</td><td>".$row->hd4."</td><td>".round((($row->hd4/$row->disposed)*100),2)."</td></tr>";
            echo "<tr><td>5 days</td><td>".$row->hd5."</td><td>".round((($row->hd5/$row->disposed)*100),2)."</td></tr>";
            echo "<tr><td>6 days</td><td>".$row->hd6."</td><td>".round((($row->hd6/$row->disposed)*100),2)."</td></tr>";
            echo "<tr><td>7 days</td><td>".$row->hd7."</td><td>".round((($row->hd7/$row->disposed)*100),2)."</td></tr>";
            echo "<tr><td>1 to 13 days</td><td>".$row->hd8."</td><td>".round((($row->hd8/$row->disposed)*100),2)."</td></tr>";
            echo "<tr><td>14 days and more</td><td>".$row->hd9."</td><td>".round((($row->hd9/$row->disposed)*100),2)."</td></tr>";		
            echo "<tr><td>Others(Erratic data)</td><td>".$row->hd10."</td><td>".round((($row->hd10/$row->disposed)*100),2)."</td></tr>";				
			echo "<tr><th>Disposed/Total Admissions</th><th>".$row->disposed."/".$row->total."</th><th></th></tr>";	
			echo "</table>";
            echo "</div>";		
			
			 //Hospital days graph
         echo "<div id = \"hdtable\" align=\"center\"></div>";
         echo "<script type=\"text/javascript\">";
         echo "var myData = new Array(['1', ".$row->hd1."], ['2', ".$row->hd2."], ['3', ".$row->hd3."], ['4', ".$row->hd4."], ['5', ".$row->hd5."], ['6', ".$row->hd6."], ['7', ".$row->hd7."], ['7-14', ".$row->hd8."], ['>=14', ".$row->hd9."] );";
         echo "var myChart = new JSChart('hdtable', 'bar');";
         echo "myChart.setDataArray(myData);";
         echo "myChart.setAxisNameX('No. of Hospital Days');";
         echo "myChart.setAxisNameY('Number of Dispositions', true);";
         echo "myChart.setTitle('Hospital Days <Average:".$row->averagehd." days>');";
         echo "myChart.draw();";
         echo "myChart.resize(600, 300)";
         echo "</script>";
		 echo "</div>";	
		 
         //Hospital Mortality days graph
          
         echo "<div id = \"mdtable\" align=\"left\"></div>"; 
         echo "<script type=\"text/javascript\">";
         echo "var myData = new Array(['1', ".$row->md1."], ['2', ".$row->md2."], ['3', ".$row->md3."], ['4', ".$row->md4."], ['5', ".$row->md5."], ['6', ".$row->md6."], ['7', ".$row->md7."], ['7-14', ".$row->md8."], ['>=14', ".$row->md9."] );";
         echo "var myChart = new JSChart('mdtable', 'bar');";
         echo "myChart.setDataArray(myData);";
         echo "myChart.setAxisNameX('No. of Hospital Days');";
         echo "myChart.setAxisNameY('Number of Mortalities', true);";
         echo "myChart.setTitle('Mortality and Hospital Days');";
         echo "myChart.draw();";
         echo "myChart.resize(600, 300)";
         echo "</script>";		 
		 
        //Dispositions graph

         echo "<div id = \"dispotable\" align=\"center\"></div>";	
         echo "<script type=\"text/javascript\">";
         echo "var myData = new Array(['Admitted (".round((($row->admitted/$row->total)*100), 2)."%)', ".$row->admitted."], ['Discharged (".round((($row->discharged/$row->total)*100), 2)."%)', ".$row->discharged."], ['HAMA (".round((($row->hama/$row->total)*100), 2)."%)', ".$row->hama."], ['Mortality (".round((($row->mortality/$row->total)*100), 2)."%)', ".$row->mortality."], ['TOS (".round((($row->tos/$row->total)*100), 2)."%)', ".$row->tos."], ['Absconded (".round((($row->absconded/$row->total)*100), 2)."%)', ".$row->absconded."] ";
		 if (!strcmp($my_service, 'er') || !strcmp($my_service, 'micu')){
		 	if (!strcmp($my_service, 'er'))	
		 		echo ", ['Admitted to MICU (".round((($row->amicu/$row->total)*100), 2)."%)', ".$row->amicu."]";
		 	echo ", ['Admitted to Wards (".round((($row->award/$row->total)*100), 2)."%)', ".$row->award."]";	
         
		 }	
         if (strcmp($my_service, 'er') && strcmp($my_service, 'micu'))
         	echo ", ['Admitted to Micu (".round((($row->amicu/$row->total)*100), 2)."%)', ".$row->amicu."]";
		 echo ");";
         echo "var myChart = new JSChart('dispotable', 'pie');";
         echo "myChart.setDataArray(myData);";
         echo "myChart.setTitle('Dispositions Pie Chart');";
         echo "myChart.setPieUnitsOffset(20);";
         echo "myChart.setPieRadius(1000/3.75);";
         echo "myChart.setPiePosition(400, 300);";
         echo "myChart.setBackgroundColor('#CCCC99');";
         echo "myChart.setTitleColor('#000000');";
         echo "myChart.draw();";
         echo "myChart.resize(850, 600)";
         echo "</script>";
	 
	 
         //ER Direct Discharges and hospital days graph   

         if (!strcmp($my_service, 'er')){     
         echo "<div id = \"edtable\" align=\"center\"></div>";         
         echo "<script type=\"text/javascript\">";
         echo "var myData = new Array(['1', ".$row->ed1."], ['2', ".$row->ed2."], ['3', ".$row->ed3."], ['4', ".$row->ed4."], ['5', ".$row->ed5."], ['6', ".$row->ed6."], ['7', ".$row->ed7."], ['7-14', ".$row->ed8."], ['>=14', ".$row->ed9."] );";
         echo "var myChart = new JSChart('edtable', 'bar');";
         echo "myChart.setDataArray(myData);";
         echo "myChart.setAxisNameX('No. of Hospital Days');";
         echo "myChart.setAxisNameY('ER to Wards', true);";
         echo "myChart.setTitle('ER Admissions to Wards and Hospital Days at ER');";
         echo "myChart.setBackgroundColor('#CCCC99');";
         echo "myChart.setTitleColor('#000000');";
         echo "myChart.draw();";
         echo "myChart.resize(600, 300)";
         echo "</script>";
         }
         	 
         //Ward 1 bed admissions
		 $w1_total = 0;
         if (strcmp($my_service, 'er') && strcmp($my_service, 'micu') && !strcmp($my_service, 'All')){
         	echo "<div id = \"ward1bedadmtable\" align=\"center\"></div>";
         	echo "<script type=\"text/javascript\">";
         	echo "var myData = new Array(";
         	for ($x = 1; $x<=50; $x++){
         		$bedadm = $this->Admission_model->get_ward_bed_admission("Ward 1", $x, 0, $my_date1, $my_date2);
         		$b = count($bedadm);
         		echo "['".$x."', ".$b."]";
         		if ($x<50)
         			echo ", ";
				$w1_total = $w1_total + $b;		
         	}
			
         	echo ");";
         	echo "var myChart = new JSChart('ward1bedadmtable', 'bar');";
         	echo "myChart.setDataArray(myData);";
         	echo "myChart.setAxisNameX('Bed Number');";
         	echo "myChart.setAxisNameY('Number of Admissions', true);";
         	echo "myChart.setTitle('Ward 1 Bed Admissions <Total: ".$w1_total.">');";
         	echo "myChart.draw();";
         	echo "myChart.resize(800, 300)";
         	echo "</script>";
         
         
         }
         //Ward 1 bed mortalities
         if (strcmp($my_service, 'er') && strcmp($my_service, 'micu') && !strcmp($my_service, 'All')){
         	$w1_mort = 0;
         	echo "<div id = \"ward1bedmorttable\" align=\"center\"></div>";
         	echo "<script type=\"text/javascript\">";
         	echo "var myData = new Array(";
         	for ($x = 1; $x<=50; $x++){
         		$bedadm = $this->Admission_model->get_ward_bed_admission("Ward 1", $x, "Mortality", $my_date1, $my_date2);
         		$b = count($bedadm);
         		echo "['".$x."', ".$b."]";
         		if ($x<50)
         			echo ", ";
         		$w1_mort = $w1_mort + $b;
         		 
         	}
         	echo ");";
         	echo "var myChart = new JSChart('ward1bedmorttable', 'bar');";
         	echo "myChart.setDataArray(myData);";
         	echo "myChart.setAxisNameX('Bed Number');";
         	echo "myChart.setAxisNameY('Number of Admissions', true);";
         	echo "myChart.setTitle('Ward 1 Bed Mortalities <Total:".$w1_mort." or ".round(($w1_mort/$w1_total*100), 2)."% of All W1 Admissions>');";
         	echo "myChart.draw();";
         	echo "myChart.resize(800, 300)";
         	echo "</script>";
         	 
         	 
         }	     
		 
         //Ward 3 bed admissions
		 $w3_total = 0;
         if (strcmp($my_service, 'er') && strcmp($my_service, 'micu') && !strcmp($my_service, 'All')){
         	echo "<div id = \"ward3bedadmtable\" align=\"center\"></div>";
         	echo "<script type=\"text/javascript\">";
         	echo "var myData = new Array(";     	
         	for ($x = 1; $x<=55; $x++){
         	$bedadm = $this->Admission_model->get_ward_bed_admission("Ward 3", $x, 0, $my_date1, $my_date2);	
         	$b = count($bedadm);
         	echo "['".$x."', ".$b."]";
         	if ($x<55)
         		echo ", ";
			$w3_total = $w3_total + $b;	
         	}
         	echo ");";
         	echo "var myChart = new JSChart('ward3bedadmtable', 'bar');";
         	echo "myChart.setDataArray(myData);";
         	echo "myChart.setAxisNameX('Bed Number');";
         	echo "myChart.setAxisNameY('Number of Admissions', true);";
         	echo "myChart.setTitle('Ward 3 Bed Admissions <Total: ".$w3_total.">');";
         	echo "myChart.draw();";
         	echo "myChart.resize(800, 300)";
         	echo "</script>";
         	
         	
         }
         //Ward 3 bed mortalities
         if (strcmp($my_service, 'er') && strcmp($my_service, 'micu') && !strcmp($my_service, 'All')){
         	$w3_mort = 0;
         	echo "<div id = \"ward3bedmorttable\" align=\"center\"></div>";
         	echo "<script type=\"text/javascript\">";
         	echo "var myData = new Array(";
         	for ($x = 1; $x<=55; $x++){
         		$bedadm = $this->Admission_model->get_ward_bed_admission("Ward 3", $x, "Mortality", $my_date1, $my_date2);
         		$b = count($bedadm);
         		echo "['".$x."', ".$b."]";
         		if ($x<55)
         			echo ", ";
         		$w3_mort = $w3_mort + $b;
         	}
         	echo ");";
         	echo "var myChart = new JSChart('ward3bedmorttable', 'bar');";
         	echo "myChart.setDataArray(myData);";
         	echo "myChart.setAxisNameX('Bed Number');";
         	echo "myChart.setAxisNameY('Number of Admissions', true);";
         	echo "myChart.setTitle('Ward 3 Bed Mortalities <Total: ".$w3_mort." or ".round(($w3_mort/$w3_total*100), 2)."% of All W3 Admissions>');";
         	echo "myChart.draw();";
         	echo "myChart.resize(800, 300)";
         	echo "</script>";
         	 
         	 
         }		 
		 
		          //Micu bed admissions
         if (!strcmp($my_service, 'micu')){
         	echo "<div id = \"micubedadmtable\" align=\"center\"></div>";
         	echo "<script type=\"text/javascript\">";
         	echo "var myData = new Array(";
         	for ($x = 1; $x<=12; $x++){
         		$bedadm = $this->Micu_census_model->get_micu_bed_admission($x, 0, $my_date1, $my_date2);
         		$b = count($bedadm);
         		echo "['".$x."', ".$b."]";
         		if ($x<12)
         			echo ", ";
         	}
         	echo ");";
         	echo "var myChart = new JSChart('micubedadmtable', 'bar');";
         	echo "myChart.setDataArray(myData);";
         	echo "myChart.setAxisNameX('Bed Number');";
         	echo "myChart.setAxisNameY('Number of Admissions', true);";
         	echo "myChart.setTitle('MICU Bed Admissions <Total: ".$row->total.">');";
         	echo "myChart.draw();";
         	echo "myChart.resize(800, 300)";
         	echo "</script>";
         	 
         	 
         }
         //MICU bed mortalities
         if (!strcmp($my_service, 'micu')){
         	$micu_mort = 0;
         	echo "<div id = \"micubedmorttable\" align=\"center\"></div>";
         	echo "<script type=\"text/javascript\">";
         	echo "var myData = new Array(";
         	for ($x = 1; $x<=12; $x++){
         		$bedadm = $this->Micu_census_model->get_micu_bed_admission($x, "Mortality", $my_date1, $my_date2);
         		$b = count($bedadm);
         		echo "['".$x."', ".$b."]";
         		if ($x<12)
         			echo ", ";
         		$micu_mort = $micu_mort + $b;
         
         	}
         	echo ");";
         	echo "var myChart = new JSChart('micubedmorttable', 'bar');";
         	echo "myChart.setDataArray(myData);";
         	echo "myChart.setAxisNameX('Bed Number');";
         	echo "myChart.setAxisNameY('Number of Admissions', true);";
         	echo "myChart.setTitle('MICU Bed Mortalities <".$micu_mort." or ".round($micu_mort/$row->total*100, 2)."% of MICU Admissions>');";
         	echo "myChart.draw();";
         	echo "myChart.resize(800, 300)";
         	echo "</script>";
         	 
         	 
         }         

         //MICU referral and admissions
         if (strcmp($my_service, 'micu')){ 
         	echo "<div id = \"micutable\" align=\"center\"></div>";
            echo "<script type=\"text/javascript\">";
            $unadm = $refs['micu'] - $row->amicu;
         	echo "var myData = new Array(['Admitted to MICU (".round((($row->amicu/$refs['micu'])*100), 2)."%)', ".$row->amicu."], ['Referred to MICU unadmitted (".round((($unadm/$refs['micu'])*100), 2)."%)', ".$unadm."]);";
         	echo "var myChart = new JSChart('micutable', 'pie');";
         	echo "myChart.setDataArray(myData);";
         	echo "myChart.setTitle('MICU Adm: MICU Referrals (".$row->amicu.":".$refs['micu'].")');";
         	echo "myChart.setPieUnitsOffset(20);";
         	echo "myChart.setBackgroundColor('#CCCC99');";
         	echo "myChart.setTitleColor('#000000');";
         	echo "myChart.draw();";
         	echo "myChart.resize(500, 300)";
         	echo "</script>";
			if (!$refs['micu'])
				echo "No referrals to MICU";
         }
         echo "</div>";
		 echo "<hr/>";		 
		 
         //refs graph
         echo "<div id = \"reftable\" align=\"center\"></div>";
         echo "<script type=\"text/javascript\">";
         echo "var myData = new Array(['Allergy', ".$refs['allergy']."], ['Cardio', ".$refs['cardio']."], ['Nephro', ".$refs['nephro']."], ['Endo', ".$refs['endo']."], ['Derma', ".$refs['derma']."], ['Hema', ".$refs['hema']."], ['HTN', ".$refs['htn']."], ['IDS', ".$refs['ids']."], ['GI', ".$refs['gi']."], ['Rheuma', ".$refs['rheuma']."], ['Onco', ".$refs['onco']."], ['Pulmo', ".$refs['pulmo']."] );";
         echo "var myChart = new JSChart('reftable', 'bar');";
         echo "myChart.setDataArray(myData);";
         echo "myChart.setAxisNameX('Sections');";
         echo "myChart.setAxisNameY('Number of referrals', true);";
         echo "myChart.setTitle('Within Department Referrals');";
         echo "myChart.draw();";
         echo "myChart.resize(500, 300)";
         echo "</script>";		 
		 
        //erefs graph
         echo "<div id = \"ereftable\" align=\"center\"></div>";
         echo "<script type=\"text/javascript\">";
         echo "var myData = new Array(['DNET', ".$erefs['dnet']."], ['DECT', ".$erefs['dect']."], ['Dietary', ".$erefs['dietary']."], ['GS', ".$erefs['gs']."], ['Uro', ".$erefs['uro']."], ['Neuro', ".$erefs['neuro']."], ['NSS', ".$erefs['nss']."], ['Ortho', ".$erefs['ortho']."], ['Plastic', ".$erefs['plastic']."], ['TCVS', ".$erefs['tcvs']."], ['Trauma', ".$erefs['trauma']."], ['ORL', ".$erefs['orl']."], ['Ophtha', ".$erefs['ophtha']."], ['Psych', ".$erefs['psych']."], ['Ob-Gyn', ".$erefs['ob-gyn']."], ['Radio', ".$erefs['radio']."], ['Tox', ".$erefs['tox']."], ['ORL', ".$erefs['orl']."], ['Pedia', ".$erefs['pedia']."], ['FM', ".$erefs['fm']."], ['Rehab', ".$erefs['rehab']."] );";
         echo "var myChart = new JSChart('ereftable', 'bar');";
         echo "myChart.setDataArray(myData);";
         echo "myChart.setAxisNameX('Other Departments');";
         echo "myChart.setAxisNameY('Number of referrals', true);";
         echo "myChart.setTitle('Outside Referrals');";
         echo "myChart.draw();";
         echo "myChart.resize(800, 300)"; 
         echo "</script>";
         

		 
		 
			//show sex-age table
			         
			$males = $row->m1 + $row->m2 + $row->m3 + $row->m4 + $row->m5 + $row->m6;
			$females = $row->f1 + $row->f2 + $row->f3 + $row->f4 + $row->f5 + $row->f6;
			$s1 = $row->m1 + $row->f1;
			$s2 = $row->m2 + $row->f2;
			$s3 = $row->m3 + $row->f3;
			$s4 = $row->m4 + $row->f4;
			$s5 = $row->m5 + $row->f5;
			$s6 = $row->m6 + $row->f6;
			$totalsex = $males + $females;
			echo "<div>";
			echo "<div style = \"float:left\"><table><tr><td><table>";
			echo "<tr><th colspan = 3>Admissions Distribution by Sex</th></tr>";
			echo "<tr><th>Sex/Age Group</th><th>Total</th><th>%</th></tr>";
			echo "<tr><td>Males</td><td>".$males."</td><td>".round((($males/$row->total)*100))."</td></tr>";
			echo "<tr><td>Below 18 yrs</td><td>".$row->m1."</td><td>".round((($row->m1/$row->total)*100))."</td></tr>";
			echo "<tr><td>18 to below 40 yrs</td><td>".$row->m2."</td><td>".round((($row->m2/$row->total)*100))."</td></tr>";
			echo "<tr><td>40 to below 60 yrs</td><td>".$row->m3."</td><td>".round((($row->m3/$row->total)*100))."</td></tr>";
			echo "<tr><td>60 to below 70 yrs</td><td>".$row->m4."</td><td>".round((($row->m4/$row->total)*100))."</td></tr>";
			echo "<tr><td>70 to below 80 yrs</td><td>".$row->m5."</td><td>".round((($row->m5/$row->total)*100))."</td></tr>";
			echo "<tr><td>Above 80 yrs</td><td>".$row->m6."</td><td>".round((($row->m6/$row->total)*100))."</td></tr>";
	
			echo "<tr><td>Females</td><td>".$females."</td><td>".round((($females/$row->total)*100))."</td></tr>";
			echo "<tr><td>Below 18 yrs</td><td>".$row->f1."</td><td>".round((($row->f1/$row->total)*100))."</td></tr>";
			echo "<tr><td>18 to below 40 yrs</td><td>".$row->f2."</td><td>".round((($row->f2/$row->total)*100))."</td></tr>";
			echo "<tr><td>40 to below 60 yrs</td><td>".$row->f3."</td><td>".round((($row->f3/$row->total)*100))."</td></tr>";
			echo "<tr><td>60 to below 70 yrs</td><td>".$row->f4."</td><td>".round((($row->f4/$row->total)*100))."</td></tr>";
			echo "<tr><td>70 to below 80 yrs</td><td>".$row->f5."</td><td>".round((($row->f5/$row->total)*100))."</td></tr>";
			echo "<tr><td>Above 80 yrs</td><td>".$row->f6."</td><td>".round((($row->f6/$row->total)*100))."</td></tr></table></td>";

            echo "<td><table><th colspan = 2>Hospital Days and Age Stats</th></tr>";
			echo "<tr><td>HD Max</td><td>".$row->maxhd." days</td></tr>";
            echo "<tr><td>HD Min</td><td>".$row->minhd." days</td></tr>";
            echo "<tr><td>HD Average</td><td>".$row->averagehd." days</td></tr>"; 
			echo "<tr><td>Female Age Min</td><td>".$row->minf." yrs</td></tr>";
			echo "<tr><td>Female Age Max</td><td>".$row->maxf." yrs</td></tr>";
			echo "<tr><td>Male Age Min</td><td>".$row->minm." yrs</td></tr>";
			echo "<tr><td>Male Age Max</td><td>".$row->maxm." yrs</td></tr>";
            echo "<tr><td>Age Min</td><td>".$row->minage." yrs</td></tr>";
            echo "<tr><td>Age Max</td><td>".$row->maxage." yrs</td></tr>";
            echo "<tr><td>Age Ave</td><td>".$row->averageage." yrs</td></tr></table></td>";
			
			echo "<td><table><tr><th colspan = 3>Admissions by Age (Both Sexes Combined)</th></tr>";
			echo "<tr><th>Age Group</th><th>Total</th><th>%</th></tr>";
			echo "<tr><td>Below 18 yrs</td><td>".$s1."</td><td>".round((($s1/$row->total)*100))."</td></tr>";
			echo "<tr><td>18 to below 40 yrs</td><td>".$s2."</td><td>".round((($s2/$row->total)*100))."</td></tr>";
			echo "<tr><td>40 to below 60 yrs</td><td>".$s3."</td><td>".round((($s3/$row->total)*100))."</td></tr>";
			echo "<tr><td>60 to below 70 yrs</td><td>".$s4."</td><td>".round((($s4/$row->total)*100))."</td></tr>";
			echo "<tr><td>70 to below 80 yrs</td><td>".$s5."</td><td>".round((($s5/$row->total)*100))."</td></tr>";
			echo "<tr><td>Above 80 yrs</td><td>".$s6."</td><td>".round((($s6/$row->total)*100))."</td></tr>";
			echo "<tr><th>ALL AGES TOTAL</th><th>".$totalsex."</th><th></th></tr></table></td></tr></table>";
			
            echo "</div>";
         //Both sexes graph
		 //$age_ave = $age_total/$numrows;
         echo "<div id = \"bothsextable\" style = \"float:left\"></div>"; 
         echo "<script type=\"text/javascript\">";
         echo "var myData = new Array(['<18 (".round((($s1/$totalsex)*100), 2)."%)', ".$s1."], ['18-40 (".round((($s2/$totalsex)*100), 2)."%)', ".$s2."], ['40-60 (".round((($s3/$totalsex)*100), 2)."%)', ".$s3."], ['60-70 (".round((($s4/$totalsex)*100), 2)."%)', ".$s4."], ['70-80 (".round((($s5/$totalsex)*100), 2)."%)', ".$s5."], ['>80 (".round((($s6/$totalsex)*100), 2)."%)', ".$s6."] );";
         echo "var myChart = new JSChart('bothsextable', 'bar');";
         echo "myChart.setDataArray(myData);";
         echo "myChart.setAxisNameX('Age Group');";
         echo "myChart.setAxisNameY('Number of Admissions', true);";
         echo "myChart.setTitle('Primary Patients by Age Group <Ave: ".round($row->averageage, 2)." yrs>');";
         echo "myChart.draw();";
         echo "var myColors = new Array('#0f0', '#ff0000', '#00f', '#ff0', '#00ffff', '#9ACD32');";
         echo "myChart.colorizeBars(myColors);";
         echo "myChart.resize(500, 300)";
         echo "</script>";         

		 
		 echo "</div>";
         //females graph
         echo "<div id = \"femaletable\" style = \"float:left\"></div>";
         echo "<script type=\"text/javascript\">";
         echo "var myData = new Array(['<18 (".round((($row->f1/$females)*100), 2)."%)', ".$row->f1."], ['18-40 (".round((($row->f2/$females)*100), 2)."%)', ".$row->f2."], ['40-60 (".round((($row->f3/$females)*100), 2)."%)', ".$row->f3."], ['60-70 (".round((($row->f4/$females)*100), 2)."%)', ".$row->f4."], ['70-80 (".round((($row->f5/$females)*100), 2)."%)', ".$row->f5."], ['>80 (".round((($row->f6/$females)*100), 2)."%)', ".$row->f6."] );";
         echo "var myChart = new JSChart('femaletable', 'bar');";
         echo "myChart.setDataArray(myData);";
         echo "myChart.setAxisNameX('Age Group');";
         echo "myChart.setAxisNameY('Number of Admissions', true);";
         echo "myChart.setTitle('Primary Female Admissions by Age Group <Ave: ".round($row->fagetotal/$females, 2)." yrs>');";
         echo "myChart.draw();";
         echo "var myColors = new Array('#0f0', '#ff0000', '#00f', '#ff0', '#00ffff', '#9ACD32');";
         echo "myChart.colorizeBars(myColors);";
         echo "myChart.resize(600, 300)";
         echo "</script>";
         
         //males graph
         echo "<div id = \"maletable\" style = \"float:left\"></div>";
         echo "<script type=\"text/javascript\">";
         echo "var myData = new Array(['<18 (".round((($row->m1/$males)*100), 2)."%)', ".$row->m1."], ['18-40 (".round((($row->m2/$males)*100), 2)."%)', ".$row->m2."], ['40-60 (".round((($row->m3/$males)*100), 2)."%)', ".$row->m3."], ['60-70 (".round((($row->m4/$males)*100), 2)."%)', ".$row->m4."], ['70-80 (".round((($row->m5/$males)*100), 2)."%)', ".$row->m5."], ['>80 (".round((($row->m6/$males)*100), 2)."%)', ".$row->m6."] );";
         echo "var myChart = new JSChart('maletable', 'bar');";
         echo "myChart.setDataArray(myData);";
         echo "myChart.setAxisNameX('Age Group');";
         echo "myChart.setAxisNameY('Number of Admissions', true);";
         echo "myChart.setTitle('Primary Male Admissions by Age Group <Ave: ".round($row->magetotal/$males, 2)." yrs>');";
         echo "myChart.draw();";
         echo "var myColors = new Array('#0f0', '#ff0000', '#00f', '#ff0', '#00ffff', '#9ACD32');";
         echo "myChart.colorizeBars(myColors);";
         echo "myChart.resize(600, 300)";
         echo "</script>";
		}

	}	 

	else{
		echo "<div align=\"center\"><h2>No admissions found between ".$my_date1." to ".$my_date2.".</h2></div>";
	}  
}	
//if by report
 
else{ 
        if (!strcmp($my_service, 'er')){
            if (!strcmp($one_gm, 'res'))
                echo "<div align=\"center\"><h1>".revert_form_input($rname).": Total of ".$numrows." ER Admissions retrieved</h1></div>"; 
            elseif (!strcmp($one_gm, 'px'))
                echo "<div align=\"center\"><h1>".revert_form_input($pname).": Total of ".$numrows." ER Admissions retrieved</h1></div>";
        }
        elseif (!strcmp($my_service, 'micu')){   
            if (!strcmp($one_gm, 'res'))         
                echo "<div align=\"center\"><h1>".revert_form_input($rname).": Total of ".$numrows." MICU Admissions retrieved</h1></div>";
            elseif (!strcmp($one_gm, 'px'))
                echo "<div align=\"center\"><h1>".revert_form_input($pname).": Total of ".$numrows." MICU Admissions retrieved</h1></div>";
        }
        else{           
            if (!strcmp($one_gm, 'res'))
                echo "<div align=\"center\"><h1>".revert_form_input($rname).": Total of ".$numrows." Ward Admissions retrieved</h1></div>";   
            elseif (!strcmp($one_gm, 'px'))
                echo "<div align=\"center\"><h1>".revert_form_input($pname).": Total of ".$numrows." Ward Admissions retrieved</h1></div>";      
        }    
 
        if ($c_admissions){
            echo "<div align = \"center\" style  = \"clear:left;\"><table><tr><th><a name=\"UP\">No.</a></th>";
        if (strcmp($my_service, 'er'))
            echo "<th>Location</th><th>Bed</th>";
        echo "<th>Admit Date</th>";
        if (!strcmp($one_gm, 'res'))
            echo "<th>Patient Name</th>";
        if (!strcmp($one_gm, 'px'))
            echo "<th>RIC</th>";
        if (strcmp($my_service, 'er'))
            echo "<th>SIC</th>";
        echo "<th>Hosp Days</th></tr>";
        $x =1;
        foreach ($c_admissions as $row){
            echo "<tr><td><a href=\"#p".$x."\">".$x."</a></td>"; 
            if (!strcmp($my_service, 'micu')){
                echo "<td>MICU</td>";
                echo "<td>".$row->bed."</td>";
            }
            elseif (strcmp($my_service, 'er')){
                echo "<td>".$row->location."</td>";
                echo "<td>".$row->bed."</td>";
            }
                echo "<td>".$row->date_in."</td>";
            if (!strcmp($one_gm, 'res')){
                $pdata = $this->Patient_model->get_one_patient($row->p_id);
	            foreach ($pdata as $patient)
                    echo "<td>".revert_form_input($patient->p_name)."</td>";
            }    
            if (!strcmp($one_gm, 'px')){
                echo "<td>";
                if (strcmp($my_service, 'er')){
                    $jr = $this->Resident_model->get_resident_name($row->r_id); 
                    foreach ($jr as $name)
        		  	    echo revert_form_input($name->r_name);
        		  	echo "</td>";    
                }
                else{
                    $jr = $this->Resident_model->get_resident_name($row->pod_id); 
                    foreach ($jr as $name)
    		  	        echo revert_form_input($name->r_name);
    		  	    echo "</td>";    
                }
            }
            if (strcmp($my_service, 'er'))
                echo "</td><td>".revert_form_input($row->sic)."</td>";
            $hd = compute_hd($row->dispo, $row->date_in, $row->date_out);
            echo "<td>".$hd."</td></tr>";
            $x++;
        }
    }
    echo "</table></div>";
    if ($c_admissions){
        $x = 1;
		$y = 1;
        echo "<div align=\"center\"><table ><tr><th>Admission Data</th><th>Problem List and Medications</th><th>In-Referrals</th><th>Out-Referrals</th><th>Docs</th></tr>";
        foreach($c_admissions as $row){  
            if (!strcmp($my_service, 'er'))
		        $vars['eadmission'] = $row->er_id;
            elseif (!strcmp($my_service, 'micu'))
		        $vars['eadmission'] = $row->micu_id;
	        else
	            $vars['eadmission'] = $row->a_id;

	        $hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
	        $vars['num'] = $y;
	    //if not monitor, allow edit date dispo
	    if (strcmp($_SERVER['PHP_AUTH_USER'], 'monitor'))
	      echo form_open('census/edit_date_out');
        echo "<tr><td><table id = \"leftcol\">";
        $pdata = $this->Patient_model->get_one_patient($row->p_id);
	    foreach ($pdata as $patient){
	        $age = compute_age_adm(revert_form_input($row->date_in), revert_form_input($patient->p_bday));	
            if (strcmp($my_service, 'er') && strcmp($my_service, 'micu'))
                make_case_num_row($my_service, $x, $row->type, revert_form_input($patient->cnum));
            else    
                make_case_num_row($my_service, $x, "", revert_form_input($patient->cnum));
            echo "<tr><td colspan = 2>Name: ".revert_form_input($patient->p_name)."</td></tr>";
	        echo "<tr><td colspan = 2>Age:".$age." Sex:".$patient->p_sex."</td></tr>";
        }
        if (strcmp($my_service, 'er'))
            echo "<tr><td colspan = 2>Source: ".$row->source."</td></tr>";
        if (strcmp($my_service, 'er') && strcmp($my_service, 'micu'))
            echo "<tr><td colspan = 2>Type: ".$row->type."</td></tr>";
	    echo "<tr><td colspan = 2>Status: ".$row->dispo."</td></tr>";
	    echo "<tr><td colspan = 2>GM Service: ".$row->service."</td></tr>";
        if (strcmp($my_service, 'er')){
            if (!strcmp($my_service, 'micu'))
                echo "<tr><td colspan = 2>Loc: MICU Bed ".$row->bed."</td></tr>";
            else
                echo "<tr><td colspan = 2>Loc: ".$row->location." Bed ".$row->bed."</td></tr>";
        }
        //POD if ER
        if (!strcmp($my_service, 'er')){
            echo "<tr><td colspan = 2>POD: ";
            if ($row->pod_id){
                $pod = $this->Resident_model->get_resident_name($row->pod_id); 
		        foreach ($pod as $name)
		            echo revert_form_input($name->r_name);
		    }
   		else
		        echo "None";
		echo "</td></tr>";
        }
        //SRIC
        if (strcmp($my_service, 'er')){
		    echo "<tr><td colspan = 2>SRIC: ";
		    if ($row->sr_id){
		            $sr = $this->Resident_model->get_resident_name($row->sr_id); 
		            foreach ($sr as $name)
		                echo revert_form_input($name->r_name);
		   }
		   else
		            echo "None";
		echo "</td></tr>";
		//JRIC
		echo "<tr><td colspan = 2>JRIC: ";

		if ($row->r_id){
		        $jr = $this->Resident_model->get_resident_name($row->r_id); 
		        foreach ($jr as $name)
		             echo revert_form_input($name->r_name);
		}
	 	else
		        echo "None";
		echo "</td></tr>";
        }
        //SIC
        if (strcmp($my_service, 'er'))
               echo "<tr><td colspan = 2>SIC: ".revert_form_input($row->sic)."</td></tr>";
        //Date IN     
	    echo "<tr><td colspan = 2>Date-IN:".revert_form_input($row->date_in)." HD: ".$hd." days</td></tr>";
	    //Date OUT
	    echo "<tr><td>Date-OUT:</td><td>";
        if (strcmp($_SERVER['PHP_AUTH_USER'], 'monitor')){
		      //echo "<input type=\"text\" name = \"date_out\" size = 8 value = \"".revert_form_input($row->date_out)."\">";
		  $donum = "date_out".$y; 	  
	      require_once('calendar/classes/tc_calendar.php');		
		  $myCalendar = new tc_calendar("$donum", true, false);
		  $myCalendar->setIcon(base_url()."calendar/images/iconCalendar.gif");
		  $dd = (int)substr($row->date_out,8, 2);
		  $mm = (int)substr($row->date_out, 5, 2);
		  $yy = (int)substr($row->date_out, 0, 4);
		  $myCalendar->setDate($dd, $mm, $yy);
		  $myCalendar->setPath(base_url()."calendar/");
		  $myCalendar->setYearInterval(1900, 2015);
		  $myCalendar->dateAllow('2012-01-01', '2015-01-01');
		  $myCalendar->setDateFormat('j F Y');
		  $myCalendar->setAlignment('right', 'top');
		  $myCalendar->writeScript();				  
			  
		}	  
	    else
		      echo revert_form_input($row->date_out);
 	    echo "</td></tr>";	  
	    echo "</table>";
	    echo "<td><table>";
        //Plist 
	    echo "<tr><td id = \"mednotes\">Problem List:\n<textarea  rows = \"6\" cols = \"40\" wrap = \"off\" readonly = \"readonly\">".revert_form_input($row->plist)."</textarea></td></tr>";
        //Meds
	    echo "<tr><td id = \"mednotes\">Medications:\n<textarea name = \"meds\" rows = \"6\" cols = \"40\" wrap = \"off\" readonly = \"readonly\">".revert_form_input($row->meds)."</textarea></td></tr>";
	    echo "</td></tr>";  
        //PCP-ICD 
        $cs_pcpdx = explode(",", revert_form_input($row->pcpdx));
	    echo "<tr><td><input type =\"button\" value = \"PCP/ICD\" onClick = \" return showPcp(this.form)\" /><textarea name = \"pcpdx\" rows=\"1\" cols=\"20\" wrap=\"off\" readonly=\"readonly\">";
        foreach ($cs_pcpdx as $pcp)
		      echo $pcp."\n";
	    echo "</textarea></td></tr></table></td>";
        //refs
	    echo "<td class = \"tarea\">In-Referrals:\n<textarea name = \"refs\" rows = \"17\" cols = \"13\" readonly=\"readonly\">";
	    $cs_refs = explode(",", revert_form_input($row->refs));
	    foreach ($cs_refs as $refs) 
		      echo $refs."\n";
	    echo "</textarea></td>";
        //erefs
	    echo "<td class = \"tarea\">Out-Referrals:\n<textarea name = \"erefs\" rows = \"17\" cols = \"13\" readonly=\"readonly\">";
	    $cs_erefs = explode(",", revert_form_input($row->erefs));
	    foreach ($cs_erefs as $erefs)
		      echo $erefs."\n";
	    echo "</textarea></td>";  	  
        echo "<td><table>";
        //notes
        echo "<tr><td class = \"b_col\"><input  type = \"button\" value = \"View Notes\" size = 30 onClick = \"return showNotes(this.form)\"/>";
        echo "<input type = \"hidden\" name = \"pnotes\" value = \"".revert_form_input($row->notes)."\" /></td></tr>";   
	    if (strcmp($_SERVER['PHP_AUTH_USER'], 'monitor')){
                $label = array( 'name'=>"", 'value'=>"Edit Dispo Date", 'class'=>"");
                echo "<tr><td>";
                //make_buttons($my_service, $label, $vars, "center", 'onClick = "return validateAedit(this.form)"');
			    $djs = "onClick = 'return validateDateedit(\"".$row->dispo."\", \"".$row->date_in."\", this.form.".$donum.")'";
				make_buttons($my_service, $label, $vars, "center", $djs);
				echo form_close();
			    echo "</td></tr>";		
	    }
        //Forms Button Column
		  //edit ICD/PCP
          //  
      if (strcmp($one_gm, "px")){    
            echo form_open('show/edit_pcpdx_form');
	        $label = array('value'=>"Edit ICD/PCP");	 
	        echo "<tr><td class = \"b_col\">";
	        make_buttons($my_service, $label, $vars, "center", "");
            echo "</td></tr>";
	        echo form_close();
      }           
		  //clinical notes
	  echo form_open('show/edit_cnotes');
	  $vars['list']= 'yes';
  	  $vars['one_gm'] = $one_gm;                   
	  $label = array('value'=>"Clinical Notes");
	  echo "<tr><td>";
	  make_buttons($my_service, $label, $vars, "center", "");
      echo "</td></tr>";
      echo form_close();
			//abstract
	  echo form_open('show/edit_abstract');
	  $label = array('value'=>"Abstract");
	  echo "<tr><td>";
	  make_buttons($my_service, $label, $vars, "center", "");
	  echo "</td></tr>";
	  echo form_close();
                       
		    //discharge summary       
      echo "<tr><td class = \"b_col\">";
      echo form_open('show/edit_dsummary');
      $label = array('value'=>"D-Summary");
	  make_buttons($my_service, $label, $vars, "center", "");
	  echo form_hidden('date_out', $row->date_out);
      echo form_close();
      echo "<tr><td>";
                      
	        //Sagipbuhay
	  echo "<tr><td>";      
      echo form_open('show/edit_sagipbuhay');
      $label = array('value'=>"Sagip Buhay");
	  make_buttons($my_service, $label, $vars, "center", "");
      echo form_close();
      echo "</td></tr>";

            //home meds 
      echo "<tr><td>";
      echo form_open('show/edit_home');
      $label = array('value'=>"Home Meds");
	  make_buttons($my_service, $label, $vars, "center", "");
      echo form_close();
      echo "</td></tr>";
		    //Lab Flow #1
	  echo "<tr><td>";	      
	  echo form_open('show/lab_forms');
      $label = array('value'=>"Lab Flow #1");
	  make_buttons($my_service, $label, $vars, "center", "");
      echo form_close();
      echo "</td></tr>";
            //Lab Flow #2
      echo "<tr><td>";		       	      
	  echo form_open('show/lab_forms2');
      $label = array('value'=>"Lab Flow #2");
	  make_buttons($my_service, $label, $vars, "center", "");
	  echo form_close();	
      echo "</td></tr>"; 
	  echo "</table></td>";
	  echo "</tr>";
	  $x++;
	  $y++;
     }
	 echo "</table></div>";
	  
  }
}  
  	    
  ?>
  
 
  </body>
</html>
