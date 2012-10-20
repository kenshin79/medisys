<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Medisys - ICD/PCP Count</title>
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

//getsession data
$my_date1 = $this->session->userdata('my_date1');
$my_date2 = $this->session->userdata('my_date2');

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
echo "<div align=\"center\"><h1>ICD/PCP Case Count for the Period ".$my_date1." to ".$my_date2.".</h1>";
echo "<div align=\"center\"><h1><a name=\"top\" >Summary Table By Disease Category</a></h1>";
echo "<table border = 1>";
echo "<tr><th><a href=\"#pulmo\" >Pulmonary</a></th><th><a href=\"#ids\" >Infectious</a></th><th><a href=\"#gastro\" >Gastroenterology</a></th><th><a href=\"#cardio\" >Cardiology</th></a><th><a href=\"#endo\" >Endocrinology</a></th></tr>";
echo "<tr><th><a href=\"#rheuma\" >Rheumatology</a></th><th><a href=\"#onco\"> Oncology</a></th><th><a href=\"#nephro\" >Nephrology</a></th><th><a href=\"#allergy\" >Allergy</a></th><th><a href=\"#derma\" >Dermatology</a></th></tr>";
echo "<th><a href=\"#hema\" >Hematology</a></th><th><a href=\"#neuro\" >Neurology</a></th><th><a href=\"#tox\" >Toxicology</a></th></tr>";
echo "</table></div>";

echo "<br/>";

echo "<div style=\"float:left\"><table width = \"1000\"><tr><th><a name=\"pulmo\"></a> Pulmonary<div align=\"right\"><a href=\"#top\" >Top</a></div></th><th>Total</th><th>Mortality</th><th>Mort%</th><th>Referred</th><th>Ref%</th><th>Ave HD</th></tr>";
foreach ($pulmo_list as $pulmo){
        if (!strcmp($area, 'ward'))
			$count = $this->Admission_model->count_all_pcpdx($my_date1, $my_date2, $pulmo, "Pulmo");
		elseif(!strcmp($area, 'er'))
			$count = $this->Er_census_model->count_all_pcpdx($my_date1, $my_date2, $pulmo, "Pulmo");
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_all_pcpdx($my_date1, $my_date2, $pulmo, "Pulmo");
		foreach($count as $row){	
			echo "<tr><td>".$pulmo."</td><td>".$row->numrows."<td>".$row->mortality."</td><td>";
			if ($row->numrows)
				echo round($row->mortality/$row->numrows*100, 2);
			else
				echo "0";
			echo "</td><td>".$row->referred."</td><td>";
			if ($row->numrows)
				echo round($row->referred/$row->numrows*100, 2);
			else 
				echo "0";
			echo "</td><td>";
			if ($row->numrows)
				echo round($row->totaldays/$row->numrows, 2);
			else
				echo "0";
			echo "</td></tr>";
		}
	$pcount = $pcount + $row->numrows;		
	}		
echo "<tr><th class=\"totals\" colspan = 7>Pulmonary Total: ".$pcount."</th></tr>";	
echo "<tr><th><a name=\"ids\">Infectious</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th><th>Total</th><th>Mortality</th><th>Mort%</th><th>Referred</th><th>Ref%</th><th>Ave HD</th></tr>";
foreach ($ids_list as $ids){
        if (!strcmp($area, 'ward'))
			$count = $this->Admission_model->count_all_pcpdx($my_date1, $my_date2, $ids, "IDS");
		elseif(!strcmp($area, 'er'))
			$count = $this->Er_census_model->count_all_pcpdx($my_date1, $my_date2, $ids, "IDS");
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_all_pcpdx($my_date1, $my_date2, $ids, "IDS");
		foreach($count as $row){	
			echo "<tr><td>".$ids."</td><td>".$row->numrows."<td>".$row->mortality."</td><td>";
			if ($row->numrows)
				echo round($row->mortality/$row->numrows*100, 2);
			else
				echo "0";
			echo "</td><td>".$row->referred."</td><td>";
			if ($row->numrows)
				echo round($row->referred/$row->numrows*100, 2);
			else 
				echo "0";
			echo "</td><td>";
			if ($row->numrows)
				echo round($row->totaldays/$row->numrows, 2);
			else
				echo "0";
			echo "</td></tr>";
		}	
		$icount = $icount + $row->numrows;
	}			
echo "<tr><th class=\"totals\" colspan = 7>Infectious Total: ".$icount."</th></tr>";	
				
echo "<tr><th><a name=\"gastro\">Gastroenterology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th><th>Total</th><th>Mortality</th><th>Mort%</th><th>Referred</th><th>Ref%</th><th>Ave HD</th></tr>";
foreach ($gastro_list as $gastro){
        if (!strcmp($area, 'ward'))
			$count = $this->Admission_model->count_all_pcpdx($my_date1, $my_date2, $gastro, "GI");
		elseif(!strcmp($area, 'er'))
			$count = $this->Er_census_model->count_all_pcpdx($my_date1, $my_date2, $gastro, "GI");
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_all_pcpdx($my_date1, $my_date2, $gastro, "GI");
		foreach($count as $row){	
			echo "<tr><td>".$gastro."</td><td>".$row->numrows."<td>".$row->mortality."</td><td>";
			if ($row->numrows)
				echo round($row->mortality/$row->numrows*100, 2);
			else
				echo "0";
			echo "</td><td>".$row->referred."</td><td>";
			if ($row->numrows)
				echo round($row->referred/$row->numrows*100, 2);
			else 
				echo "0";
			echo "</td><td>";
			if ($row->numrows)
				echo round($row->totaldays/$row->numrows, 2);
			else
				echo "0";
			echo "</td></tr>";
		}	
		$gcount = $gcount + $row->numrows;
	}				
echo "<tr><th class=\"totals\" colspan = 7>Gastroenterology Total: ".$gcount."</th></tr>";		
echo "</table></div>";
//next table
echo "<div style=\"float:left\"><table width = \"1000\"><tr><th><a name=\"cardio\">Cardiology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th><th>Total</th><th>Mortality</th><th>Mort%</th><th>Referred</th><th>Ref%</th><th>Ave HD</th></tr>";
foreach ($cardio_list as $cardio){
        if (!strcmp($area, 'ward'))
			$count = $this->Admission_model->count_all_pcpdx($my_date1, $my_date2, $cardio, "Cardio");
		elseif(!strcmp($area, 'er'))
			$count = $this->Er_census_model->count_all_pcpdx($my_date1, $my_date2, $cardio, "Cardio");
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_all_pcpdx($my_date1, $my_date2, $cardio, "Cardio");
		foreach($count as $row){	
			echo "<tr><td>".$cardio."</td><td>".$row->numrows."<td>".$row->mortality."</td><td>";
			if ($row->numrows)
				echo round($row->mortality/$row->numrows*100, 2);
			else
				echo "0";
			echo "</td><td>".$row->referred."</td><td>";
			if ($row->numrows)
				echo round($row->referred/$row->numrows*100, 2);
			else 
				echo "0";
			echo "</td><td>";
			if ($row->numrows)
				echo round($row->totaldays/$row->numrows, 2);
			else
				echo "0";
			echo "</td></tr>";
		}	
		$ccount = $ccount + $row->numrows;
	}	
echo "<tr><th class=\"totals\" colspan = 7>Cardiology Total: ".$ccount."</th></tr>";		
echo "<tr><th><a name=\"endo\">Endocrinology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th><th>Total</th><th>Mortality</th><th>Mort%</th><th>Referred</th><th>Ref%</th><th>Ave HD</th></tr>";
foreach ($endo_list as $endo){
        if (!strcmp($area, 'ward'))
			$count = $this->Admission_model->count_all_pcpdx($my_date1, $my_date2, $endo, "Endo");
		elseif(!strcmp($area, 'er'))
			$count = $this->Er_census_model->count_all_pcpdx($my_date1, $my_date2, $endo, "Endo");
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_all_pcpdx($my_date1, $my_date2, $endo, "Endo");
		foreach($count as $row){	
			echo "<tr><td>".$endo."</td><td>".$row->numrows."<td>".$row->mortality."</td><td>";
			if ($row->numrows)
				echo round($row->mortality/$row->numrows*100, 2);
			else
				echo "0";
			echo "</td><td>".$row->referred."</td><td>";
			if ($row->numrows)
				echo round($row->referred/$row->numrows*100, 2);
			else 
				echo "0";
			echo "</td><td>";
			if ($row->numrows)
				echo round($row->totaldays/$row->numrows, 2);
			else
				echo "0";
			echo "</td></tr>";
		}	
		$ecount = $ecount + $row->numrows;
	}		
echo "<tr><th class=\"totals\" colspan = 7>Endocrinology Total: ".$ecount."</th></tr>";		
echo "<tr><th><a name=\"rheuma\">Rheumatology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th><th>Total</th><th>Mortality</th><th>Mort%</th><th>Referred</th><th>Ref%</th><th>Ave HD</th></tr>";
foreach ($rheuma_list as $rheuma){
        if (!strcmp($area, 'ward'))
			$count = $this->Admission_model->count_all_pcpdx($my_date1, $my_date2, $rheuma, "Rheuma");
		elseif(!strcmp($area, 'er'))
			$count = $this->Er_census_model->count_all_pcpdx($my_date1, $my_date2, $rheuma, "Rheuma");
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_all_pcpdx($my_date1, $my_date2, $rheuma, "Rheuma");
		foreach($count as $row){	
			echo "<tr><td>".$rheuma."</td><td>".$row->numrows."<td>".$row->mortality."</td><td>";
			if ($row->numrows)
				echo round($row->mortality/$row->numrows*100, 2);
			else
				echo "0";
			echo "</td><td>".$row->referred."</td><td>";
			if ($row->numrows)
				echo round($row->referred/$row->numrows*100, 2);
			else 
				echo "0";
			echo "</td><td>";
			if ($row->numrows)
				echo round($row->totaldays/$row->numrows, 2);
			else
				echo "0";
			echo "</td></tr>";
		}	
		$rcount = $rcount + $row->numrows;
	}			
echo "<tr><th class=\"totals\" colspan = 7>Rheumatology Total: ".$rcount."</th></tr>";		
echo "<tr><th><a name=\"onco\">Oncology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th><th>Total</th><th>Mortality</th><th>Mort%</th><th>Referred</th><th>Ref%</th><th>Ave HD</th></tr>";
foreach ($onco_list as $onco){
        if (!strcmp($area, 'ward'))
			$count = $this->Admission_model->count_all_pcpdx($my_date1, $my_date2, $onco, "Onco");
		elseif(!strcmp($area, 'er'))
			$count = $this->Er_census_model->count_all_pcpdx($my_date1, $my_date2, $onco, "Onco");
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_all_pcpdx($my_date1, $my_date2, $onco, "Onco");
		foreach($count as $row){	
			echo "<tr><td>".$onco."</td><td>".$row->numrows."<td>".$row->mortality."</td><td>";
			if ($row->numrows)
				echo round($row->mortality/$row->numrows*100, 2);
			else
				echo "0";
			echo "</td><td>".$row->referred."</td><td>";
			if ($row->numrows)
				echo round($row->referred/$row->numrows*100, 2);
			else 
				echo "0";
			echo "</td><td>";
			if ($row->numrows)
				echo round($row->totaldays/$row->numrows, 2);
			else
				echo "0";
			echo "</td></tr>";
		}	
		$ocount = $ocount + $row->numrows;
	}
echo "<tr><th class=\"totals\" colspan = 7>Oncology Total: ".$ocount."</th></tr>";						
echo "</table></div>";

//next table
echo "<div style=\"float:left\"><table width = \"1000\"><tr><th><a name=\"nephro\">Nephrology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th><th>Total</th><th>Mortality</th><th>Mort%</th><th>Referred</th><th>Ref%</th><th>Ave HD</th></tr>";
foreach ($nephro_list as $nephro){
        if (!strcmp($area, 'ward'))
			$count = $this->Admission_model->count_all_pcpdx($my_date1, $my_date2, $nephro, "Nephro");
		elseif(!strcmp($area, 'er'))
			$count = $this->Er_census_model->count_all_pcpdx($my_date1, $my_date2, $nephro, "Nephro");
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_all_pcpdx($my_date1, $my_date2, $nephro, "Nephro");
		foreach($count as $row){	
			echo "<tr><td>".$nephro."</td><td>".$row->numrows."<td>".$row->mortality."</td><td>";
			if ($row->numrows)
				echo round($row->mortality/$row->numrows*100, 2);
			else
				echo "0";
			echo "</td><td>".$row->referred."</td><td>";
			if ($row->numrows)
				echo round($row->referred/$row->numrows*100, 2);
			else 
				echo "0";
			echo "</td><td>";
			if ($row->numrows)
				echo round($row->totaldays/$row->numrows, 2);
			else
				echo "0";
			echo "</td></tr>";
		}	
		$ncount = $ncount + $row->numrows;
	}	
echo "<tr><th class=\"totals\" colspan = 7>Nephrology Total: ".$ncount."</th></tr>";			
echo "<tr><th><a name=\"allergy\">Allergy</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th><th>Total</th><th>Mortality</th><th>Mort%</th><th>Referred</th><th>Ref%</th><th>Ave HD</th></tr>";
foreach ($allergy_list as $allergy){
        if (!strcmp($area, 'ward'))
			$count = $this->Admission_model->count_all_pcpdx($my_date1, $my_date2, $allergy, "Allergy");
		elseif(!strcmp($area, 'er'))
			$count = $this->Er_census_model->count_all_pcpdx($my_date1, $my_date2, $allergy, "Allergy");
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_all_pcpdx($my_date1, $my_date2, $allergy, "Allergy");
		foreach($count as $row){	
			echo "<tr><td>".$allergy."</td><td>".$row->numrows."<td>".$row->mortality."</td><td>";
			if ($row->numrows)
				echo round($row->mortality/$row->numrows*100, 2);
			else
				echo "0";
			echo "</td><td>".$row->referred."</td><td>";
			if ($row->numrows)
				echo round($row->referred/$row->numrows*100, 2);
			else 
				echo "0";
			echo "</td><td>";
			if ($row->numrows)
				echo round($row->totaldays/$row->numrows, 2);
			else
				echo "0";
			echo "</td></tr>";
		}	
		$acount = $acount + $row->numrows;
	}			
echo "<tr><th class=\"totals\" colspan = 7>Allergy Total: ".$acount."</th></tr>";		
echo "<tr><th><a name=\"derma\">Dermatology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th><th>Total</th><th>Mortality</th><th>Mort%</th><th>Referred</th><th>Ref%</th><th>Ave HD</th></tr>";
foreach ($derma_list as $derma){
        if (!strcmp($area, 'ward'))
			$count = $this->Admission_model->count_all_pcpdx($my_date1, $my_date2, $derma, "Derma");
		elseif(!strcmp($area, 'er'))
			$count = $this->Er_census_model->count_all_pcpdx($my_date1, $my_date2, $derma, "Derma");
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_all_pcpdx($my_date1, $my_date2, $derma, "Derma");
		foreach($count as $row){	
			echo "<tr><td>".$derma."</td><td>".$row->numrows."<td>".$row->mortality."</td><td>";
			if ($row->numrows)
				echo round($row->mortality/$row->numrows*100, 2);
			else
				echo "0";
			echo "</td><td>".$row->referred."</td><td>";
			if ($row->numrows)
				echo round($row->referred/$row->numrows*100, 2);
			else 
				echo "0";
			echo "</td><td>";
			if ($row->numrows)
				echo round($row->totaldays/$row->numrows, 2);
			else
				echo "0";
			echo "</td></tr>";
		}	
		$dcount = $dcount + $row->numrows;
	}	
echo "<tr><th class=\"totals\" colspan = 7>Dermatology Total: ".$dcount."</th></tr>";		
echo "<tr><th><a name=\"hema\">Hematology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th><th>Total</th><th>Mortality</th><th>Mort%</th><th>Referred</th><th>Ref%</th><th>Ave HD</th></tr>";
foreach ($hema_list as $hema){
        if (!strcmp($area, 'ward'))
			$count = $this->Admission_model->count_all_pcpdx($my_date1, $my_date2, $hema, "Hema");
		elseif(!strcmp($area, 'er'))
			$count = $this->Er_census_model->count_all_pcpdx($my_date1, $my_date2, $hema, "Hema");
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_all_pcpdx($my_date1, $my_date2, $hema, "Hema");
		foreach($count as $row){	
			echo "<tr><td>".$hema."</td><td>".$row->numrows."<td>".$row->mortality."</td><td>";
			if ($row->numrows)
				echo round($row->mortality/$row->numrows*100, 2);
			else
				echo "0";
			echo "</td><td>".$row->referred."</td><td>";
			if ($row->numrows)
				echo round($row->referred/$row->numrows*100, 2);
			else 
				echo "0";
			echo "</td><td>";
			if ($row->numrows)
				echo round($row->totaldays/$row->numrows, 2);
			else
				echo "0";
			echo "</td></tr>";
		}	
		$hcount = $hcount + $row->numrows;
	}	
echo "<tr><th class=\"totals\" colspan = 7>Hematology Total: ".$hcount."</th></tr>";	
echo "<tr><th><a name=\"neuro\">Neurology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th><th>Total</th><th>Mortality</th><th>Mort%</th><th>Referred</th><th>Ref%</th><th>Ave HD</th></tr>";
foreach ($neuro_list as $neuro){
        if (!strcmp($area, 'ward'))
			$count = $this->Admission_model->count_all_pcpdx($my_date1, $my_date2, $neuro, "Neuro");
		elseif(!strcmp($area, 'er'))
			$count = $this->Er_census_model->count_all_pcpdx($my_date1, $my_date2, $neuro, "Neuro");
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_all_pcpdx($my_date1, $my_date2, $neuro, "Neuro");
		foreach($count as $row){	
			echo "<tr><td>".$neuro."</td><td>".$row->numrows."<td>".$row->mortality."</td><td>";
			if ($row->numrows)
				echo round($row->mortality/$row->numrows*100, 2);
			else
				echo "0";
			echo "</td><td>".$row->referred."</td><td>";
			if ($row->numrows)
				echo round($row->referred/$row->numrows*100, 2);
			else 
				echo "0";
			echo "</td><td>";
			if ($row->numrows)
				echo round($row->totaldays/$row->numrows, 2);
			else
				echo "0";
			echo "</td></tr>";
		}	
		$necount = $necount + $row->numrows;
	}		
echo "<tr><th class=\"totals\" colspan= 7>Neurology Total: ".$necount."</th></tr>";		
echo "<tr><th><a name=\"tox\">Toxicology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th><th>Total</th><th>Mortality</th><th>Mort%</th><th>Referred</th><th>Ref%</th><th>Ave HD</th></tr>";
foreach ($tox_list as $tox){
        if (!strcmp($area, 'ward'))
			$count = $this->Admission_model->count_all_pcpdx($my_date1, $my_date2, $tox, "Tox");
		elseif(!strcmp($area, 'er'))
			$count = $this->Er_census_model->count_all_pcpdx($my_date1, $my_date2, $tox, "Tox");
        elseif(!strcmp($area, 'micu'))
            $count = $this->Micu_census_model->count_all_pcpdx($my_date1, $my_date2, $tox, "Tox");
		foreach($count as $row){	
			echo "<tr><td>".$tox."</td><td>".$row->numrows."<td>".$row->mortality."</td><td>";
			if ($row->numrows)
				echo round($row->mortality/$row->numrows*100, 2);
			else
				echo "0";
			echo "</td>";
			echo "<td>--</td><td>--</td>";
			
			/*<td>".$row->referred."(";
			if ($row->numrows)
				echo round($row->referred/$row->numrows*100, 2);
			else 
				echo "0";
			echo ")</td>
			*/
			echo "<td>";
			if ($row->numrows)
				echo round($row->totaldays/$row->numrows, 2);
			else
				echo "0";
			echo "</td></tr>";
		}	
		$tcount = $tcount + $row->numrows;
	}		
echo "<tr><th class=\"totals\" colspan = 7>Toxicology Total: ".$tcount."</th></tr>";			
echo "</table></div>";


?>
  </body>
</html>
