<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Mon, 24 Oct 2011 14:21:25 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Medisina - ICD/PCP Count</title>
    <link rel="stylesheet" type="text/css" href="/medisys/css/menu.css" />
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

//greet user
make_page_header($_SERVER['PHP_AUTH_USER']);

$ab = array('name'=>'main_showa', 'value'=>'Manage Admissions', 'class'=>'menub');

    echo form_open('menu');
    echo form_submit($ab);
	echo form_close();
$pulmo_list = $this->config->item('pulmodx_list');
$cardio_list = $this->config->item('cardiodx_list');
$nephro_list = $this->config->item('nephrodx_list');
$ids_list = $this->config->item('idsdx_list');
$rheuma_list = $this->config->item('rheumadx_list');
$allergy_list = $this->config->item('allergydx_list');
$derma_list = $this->config->item('dermadx_list');
$endo_list = $this->config->item('endodx_list');
$onco_list = $this->config->item('oncodx_list');
$hema_list = $this->config->item('hemadx_list');
$neuro_list = $this->config->item('neurodx_list');
$gastro_list = $this->config->item('gastrodx_list');
$tox_list = $this->config->item('toxdx_list');
$pcount = 0;
$ccount = 0;
$ncount = 0;
$icount = 0;
$rcount = 0;
$acount = 0;
$dcount = 0;
$ecount = 0;
$ocount = 0;
$hcount = 0;
$necount = 0;
$gcount = 0;
$tcount = 0;
//count cases
foreach ($pulmo_list as $pulmo){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $pulmo);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $pulmo);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $pulmo); 
	 $pcount = $pcount + $count;
	}		
foreach ($ids_list as $ids){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $ids);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $ids);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $ids); 
	 $icount = $icount + $count;
	}			
foreach ($gastro_list as $gastro){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $gastro);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $gastro);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $gastro); 
	$gcount = $gcount + $count;
	}
foreach ($cardio_list as $cardio){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $cardio);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $cardio);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $cardio); 
	$ccount = $ccount + $count;
	}	
foreach ($endo_list as $endo){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $endo);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $endo);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $endo); 
	$ecount = $ecount + $count;
	}	
foreach ($rheuma_list as $rheuma){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $rheuma);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $rheuma);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $rheuma); 
	$rcount = $rcount + $count;
	}	
foreach ($onco_list as $onco){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $onco);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $onco);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $onco); 
	$ocount = $ocount + $count;
	}
foreach ($nephro_list as $nephro){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $nephro);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $nephro);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $nephro); 
	$ncount = $ncount + $count;
	}
foreach ($allergy_list as $allergy){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $allergy);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $allergy);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $allergy); 
	$acount = $acount + $count;
	}
foreach ($derma_list as $derma){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $derma);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $derma);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $derma); 
	$dcount = $dcount + $count;
	}	
foreach ($hema_list as $hema){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $hema);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $hema);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $hema); 
	$hcount = $hcount + $count;
	}
foreach ($neuro_list as $neuro){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $neuro);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $neuro);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $neuro); 
	$necount = $necount + $count;
	}
foreach ($tox_list as $tox){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $tox);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $tox);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $tox); 
	$tcount = $tcount + $count;
	}	
echo "<div align=\"center\"><h1>ICD/PCP Case Count for the Period ".$my_date1." to ".$my_date2.".</h1>";
echo "<div align=\"center\"><h1>Summary Table By Disease Category</h1>";
echo "<table border = 1>";
echo "<tr><th><a href=\"#pulmo\" ><a name=\"top\" >Pulmonary</a></a></th><td><font size=5>".$pcount."</font></td><th><a href=\"#ids\" >Infectious</a></th><td><font size=5>".$icount."</font></td><th><a href=\"#gastro\" >Gastroenterology</a></th><td><font size=5>".$gcount."</font></td><th><a href=\"#cardio\" >Cardiology</th></a><td><font size=5>".$ccount."</font></td><th><a href=\"#endo\" >Endocrinology</a></th><td><font size=5>".$ecount."</font></td></tr>";
echo "<tr><th><a href=\"#rheuma\" >Rheumatology</a></th><td><font size=5>".$rcount."</font></td><th><a href=\"#onco\"> Oncology</a></th><td><font size=5>".$ocount."</font></td><th><a href=\"#nephro\" >Nephrology</a></th><td><font size=5>".$ncount."</font></td><th><a href=\"#allergy\" >Allergy</a></th><td><font size=5>".$acount."</font></td><th><a href=\"#derma\" >Dermatology</a></th><td><font size=5>".$dcount."</font></td></tr>";
echo "<th><a href=\"#hema\" >Hematology</a></th><td><font size=5>".$hcount."</font></td><th><a href=\"#neuro\" >Neurology</a></th><td><font size=5>".$necount."</font></td><th><a href=\"#tox\" >Toxicology</a></th><td><font size=5>".$tcount."</font></td></tr>";
echo "</table></div>";

echo "<br/>";

echo "<div align=\"center\" ><div style=\"float:left\"><table><td><table><tr><th><a name=\"pulmo\"></a> Pulmonary<div align=\"right\"><a href=\"#top\" >Top</a></div></th>";
foreach ($pulmo_list as $pulmo){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $pulmo);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $pulmo);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $pulmo); 
	 echo "<tr><td>".$pulmo."</td><td><font size=5>".$count."</font></td></tr>";
	
	}		
echo "<tr><th class=\"totals\">Pulmonary Total: ".$pcount."</th></tr>";	
echo "<tr><th><a name=\"ids\">Infectious</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th>";
foreach ($ids_list as $ids){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $ids);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $ids);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $ids); 
	    echo "<tr><td>".$ids."</td><td><font size=5>".$count."</font></td></tr>";
		
	}			
echo "<tr><th class=\"totals\">Infectious Total: ".$icount."</th></tr>";	
				
echo "<tr><th><a name=\"gastro\">Gastroenterology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th>";
foreach ($gastro_list as $gastro){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $gastro);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $gastro);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $gastro); 
	    echo "<tr><td>".$gastro."</td><td><font size=5>".$count."</font></td></tr>";
		
	}				
echo "<tr><th class=\"totals\">Gastroenterology Total: ".$gcount."</th></tr>";	
	
	
echo "</table></td>";
echo "<td>";
echo "<table><tr><th><a name=\"cardio\">Cardiology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($cardio_list as $cardio){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $cardio);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $cardio);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $cardio); 
	    echo "<tr><td>".$cardio."</td><td><font size=5>".$count."</font></td></tr>";
		
	}	
echo "<tr><th class=\"totals\">Cardiology Total: ".$ccount."</th></tr>";		
echo "<tr><th><a name=\"endo\">Endocrinology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th>";
foreach ($endo_list as $endo){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $endo);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $endo);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $endo); 
	    echo "<tr><td>".$endo."</td><td><font size=5>".$count."</font></td></tr>";
		
	}		
echo "<tr><th class=\"totals\">Endocrinology Total: ".$ecount."</th></tr>";		
echo "<tr><th><a name=\"rheuma\">Rheumatology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($rheuma_list as $rheuma){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $rheuma);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $rheuma);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $rheuma); 
	    echo "<tr><td>".$rheuma."</td><td><font size=5>".$count."</font></td></tr>";
		
	}			
echo "<tr><th class=\"totals\">Rheumatology Total: ".$rcount."</th></tr>";		
echo "<tr><th><a name=\"onco\">Oncology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($onco_list as $onco){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $onco);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $onco);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $onco); 
	    echo "<tr><td>".$onco."</td><td><font size=5>".$count."</font></td></tr>";
		
	}
echo "<tr><th class=\"totals\">Oncology Total: ".$ocount."</th></tr>";						
echo "</table></td>";

echo "<td>";
echo "<table><tr><th><a name=\"nephro\">Nephrology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($nephro_list as $nephro){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $nephro);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $nephro);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $nephro); 
	    echo "<tr><td>".$nephro."</td><td><font size=5>".$count."</font></td></tr>";
		
	}	
echo "<tr><th class=\"totals\">Nephrology Total: ".$ncount."</th></tr>";			
echo "<tr><th><a name=\"allergy\">Allergy</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($allergy_list as $allergy){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $allergy);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $allergy);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $allergy); 
	    echo "<tr><td>".$allergy."</td><td><font size=5>".$count."</font></td></tr>";
		
	}			
echo "<tr><th class=\"totals\">Allergy Total: ".$acount."</th></tr>";		
echo "<tr><th><a name=\"derma\">Dermatology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($derma_list as $derma){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $derma);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $derma);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $derma); 
	    echo "<tr><td>".$derma."</td><td><font size=5>".$count."</font></td></tr>";
		
	}	
echo "<tr><th class=\"totals\">Dermatology Total: ".$dcount."</th></tr>";		
echo "<tr><th><a name=\"hema\">Hematology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($hema_list as $hema){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $hema);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $hema);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $hema); 
	    echo "<tr><td>".$hema."</td><td><font size=5>".$count."</font></td></tr>";
		
	}	
echo "<tr><th class=\"totals\">Hematology Total: ".$hcount."</th></tr>";	
echo "<tr><th><a name=\"neuro\">Neurology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($neuro_list as $neuro){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $neuro);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $neuro);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $neuro); 
	    echo "<tr><td>".$neuro."</td><td><font size=5>".$count."</font></td></tr>";
		
	}		
echo "<tr><th class=\"totals\">Neurology Total: ".$necount."</th></tr>";		
echo "<tr><th><a name=\"tox\">Toxicology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($tox_list as $tox){
        if (!strcmp($area, 'ward'))
            $count = $this->Admission_model->count_pcpdx($my_date1, $my_date2, $tox);
        elseif(!strcmp($area, 'er'))
	    $count = $this->Er_census_model->count_pcpdx($my_date1, $my_date2, $tox);
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_pcpdx($my_date1, $my_date2, $tox); 
	    echo "<tr><td>".$tox."</td><td><font size=5>".$count."</font></td></tr>";
		
	}		
echo "<tr><th class=\"totals\">Toxicology Total: ".$tcount."</th></tr>";			
echo "</table></td>";




echo "</table></div>";

?>
  </body>
</html>
