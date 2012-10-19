
<?php
//pcpdx counters
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


echo "<div align = \"center\"><h2>Report for the period: ".$datea." to ".$dateb."</h2>"; 
echo "<table><tr><th>AREA</th><th>Admitted</th><th>Discharged</th><th>Mortality</th><th>TOS</th><th>HAMA</th><th>Absconded</th><th>Admitted to MICU</th><th>TOTAL</th></tr>";
foreach($wcount as $wrow){
	$wtotal = $wrow->admitted + $wrow->discharged + $wrow->mortality + $wrow->tos + $wrow->hama + $wrow->absconded + $wrow->amicu;
	echo "<tr><td>Primary WARDS</td><td>".$wrow->admitted."</td><td>".$wrow->discharged."</td><td>".$wrow->mortality."</td><td>".$wrow->tos."</td><td>".$wrow->hama."</td><td>".$wrow->absconded."</td><td>".$wrow->amicu."</td><td>".$wtotal."</td><tr>";
}
foreach($comxcount as $crow){
	$comxtotal = $crow->admitted + $crow->discharged + $crow->mortality + $crow->tos + $crow->hama + $crow->absconded + $crow->amicu;
	echo "<tr><td>Co-managed</td><td>".$crow->admitted."</td><td>".$crow->discharged."</td><td>".$crow->mortality."</td><td>".$crow->tos."</td><td>".$crow->hama."</td><td>".$crow->absconded."</td><td>".$crow->amicu."</td><td>".$comxtotal."</td><tr>";
}
foreach($preopcount as $prow){
	$preoptotal = $prow->admitted + $prow->discharged + $prow->mortality + $prow->tos + $prow->hama + $prow->absconded + $prow->amicu;
	echo "<tr><td>Pre-operative</td><td>".$prow->admitted."</td><td>".$prow->discharged."</td><td>".$prow->mortality."</td><td>".$prow->tos."</td><td>".$prow->hama."</td><td>".$prow->absconded."</td><td>".$prow->amicu."</td><td>".$preoptotal."</td><tr>";
}
	echo "</table>";
echo "<table><tr><th>AREA</th><th>Admitted</th><th>Discharged</th><th>Mortality</th><th>TOS</th><th>HAMA</th><th>Absconded</th><th>Admitted to WARDS</th><th>Admitted to MICU</th><th>TOTAL</th></tr>";
foreach($ecount as $erow){
	$etotal = $erow->admitted + $erow->discharged + $erow->mortality + $erow->tos + $erow->hama + $erow->absconded + $erow->award + $erow->amicu;
	echo "<tr><td>ER</td><td>".$erow->admitted."</td><td>".$erow->discharged."</td><td>".$erow->mortality."</td><td>".$erow->tos."</td><td>".$erow->hama."</td><td>".$erow->absconded."</td><td>".$erow->award."</td><td>".$erow->amicu."</td><td>".$etotal."</td><tr>";
}
echo "</table>";	
	
echo "<table><tr><th>AREA</th><th>Admitted</th><th>Discharged</th><th>Mortality</th><th>TOS</th><th>HAMA</th><th>Absconded</th><th>Admitted to WARDS</th><th>TOTAL</th></tr>";
foreach($mcount as $mrow){
	$mtotal = $mrow->admitted + $mrow->discharged + $mrow->mortality + $mrow->tos + $mrow->hama + $mrow->absconded + $mrow->award;
	echo "<tr><td>MICU</td><td>".$mrow->admitted."</td><td>".$mrow->discharged."</td><td>".$mrow->mortality."</td><td>".$mrow->tos."</td><td>".$mrow->hama."</td><td>".$mrow->absconded."</td><td>".$mrow->award."</td><td>".$mtotal."</td><tr>";
}
echo "</table>";
//pcpdx count
echo "<h1>ICD/PCP Case Count for the Period ".$datea." to ".$dateb.".</h1>";
echo "<h1><a name=\"top\" >Summary Table By Disease Category</a></h1>";
echo "<table border = 1>";
echo "<tr><th><a href=\"#pulmo\" >Pulmonary</a></th><th><a href=\"#ids\" >Infectious</a></th><th><a href=\"#gastro\" >Gastroenterology</a></th><th><a href=\"#cardio\" >Cardiology</th></a><th><a href=\"#endo\" >Endocrinology</a></th></tr>";
echo "<tr><th><a href=\"#rheuma\" >Rheumatology</a></th><th><a href=\"#onco\"> Oncology</a></th><th><a href=\"#nephro\" >Nephrology</a></th><th><a href=\"#allergy\" >Allergy</a></th><th><a href=\"#derma\" >Dermatology</a></th></tr>";
echo "<th><a href=\"#hema\" >Hematology</a></th><th><a href=\"#neuro\" >Neurology</a></th><th><a href=\"#tox\" >Toxicology</a></th></tr>";
echo "</table>";
echo "</div>";

echo "<br/>";

echo "<div style=\"float:left\"><table width = \"350px\"><tr><th colspan =2><a name=\"pulmo\"></a> Pulmonary<div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($pulmo_list as $pulmo){
	 $count = 0; 
     $count = $this->Admission_model->count_res_pcpdx($datea, $dateb, $pulmo, $rid) + $this->Er_census_model->count_res_pcpdx($datea, $dateb, $pulmo, $rid) + $this->Micu_census_model->count_res_pcpdx($datea, $dateb, $pulmo, $rid); 
	 echo "<tr><td>".$pulmo."</td><td><font size=5>".$count."</font></td></tr>";
	
}		

echo "<tr><th colspan =2><a name=\"ids\">Infectious</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";

foreach ($ids_list as $ids){
	 $count = 0;  
     $count = $this->Admission_model->count_res_pcpdx($datea, $dateb, $ids, $rid) + $this->Er_census_model->count_res_pcpdx($datea, $dateb, $ids, $rid) + $this->Micu_census_model->count_res_pcpdx($datea, $dateb, $ids, $rid); 
	 echo "<tr><td>".$ids."</td><td><font size=5>".$count."</font></td></tr>";		
	}			
echo "<tr><th colspan =2><a name=\"gastro\">Gastroenterology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th>";
foreach ($gastro_list as $gastro){
     $count = 0; 
     $count = $this->Admission_model->count_res_pcpdx($datea, $dateb, $gastro, $rid) + $this->Er_census_model->count_res_pcpdx($datea, $dateb, $gastro, $rid) + $this->Micu_census_model->count_res_pcpdx($datea, $dateb, $gastro, $rid); 
	 echo "<tr><td>".$gastro."</td><td><font size=5>".$count."</font></td></tr>";	
	}				
echo "</table></div>";

echo "<div style=\"float:left\"><table width = \"300px\"><tr><th colspan =2><a name=\"cardio\">Cardiology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($cardio_list as $cardio){
     $count = 0; 
     $count = $this->Admission_model->count_res_pcpdx($datea, $dateb, $cardio, $rid) + $this->Er_census_model->count_res_pcpdx($datea, $dateb, $cardio, $rid) + $this->Micu_census_model->count_res_pcpdx($datea, $dateb, $cardio, $rid); 
	 echo "<tr><td>".$cardio."</td><td><font size=5>".$count."</font></td></tr>";	
	}	
echo "<tr><th colspan =2><a name=\"endo\">Endocrinology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($endo_list as $endo){
     $count = 0; 
     $count = $this->Admission_model->count_res_pcpdx($datea, $dateb, $endo, $rid) + $this->Er_census_model->count_res_pcpdx($datea, $dateb, $endo, $rid) + $this->Micu_census_model->count_res_pcpdx($datea, $dateb, $endo, $rid); 
	 echo "<tr><td>".$endo."</td><td><font size=5>".$count."</font></td></tr>";	
	}		
echo "<tr><th colspan =2><a name=\"rheuma\">Rheumatology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($rheuma_list as $rheuma){
     $count = 0; 
     $count = $this->Admission_model->count_res_pcpdx($datea, $dateb, $rheuma, $rid) + $this->Er_census_model->count_res_pcpdx($datea, $dateb, $rheuma, $rid) + $this->Micu_census_model->count_res_pcpdx($datea, $dateb, $rheuma, $rid); 
	 echo "<tr><td>".$rheuma."</td><td><font size=5>".$count."</font></td></tr>";	
	}			
echo "<tr><th colspan =2><a name=\"onco\">Oncology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";	
foreach ($onco_list as $onco){
     $count = 0;  
     $count = $this->Admission_model->count_res_pcpdx($datea, $dateb, $onco, $rid) + $this->Er_census_model->count_res_pcpdx($datea, $dateb, $onco, $rid) + $this->Micu_census_model->count_res_pcpdx($datea, $dateb, $onco, $rid); 
	 echo "<tr><td>".$onco."</td><td><font size=5>".$count."</font></td></tr>";	
	}				
echo "</table></div>";
echo "<div style=\"float:left\"><table width = \"300px\"><tr><th colspan =2><a name=\"nephro\">Nephrology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($nephro_list as $nephro){
     $count = 0; 
     $count = $this->Admission_model->count_res_pcpdx($datea, $dateb, $nephro, $rid) + $this->Er_census_model->count_res_pcpdx($datea, $dateb, $nephro, $rid) + $this->Micu_census_model->count_res_pcpdx($datea, $dateb, $nephro, $rid); 
	 echo "<tr><td>".$nephro."</td><td><font size=5>".$count."</font></td></tr>";	
	}	
echo "<tr><th colspan =2><a name=\"allergy\">Allergy</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($allergy_list as $allergy){
     $count = 0; 
     $count = $this->Admission_model->count_res_pcpdx($datea, $dateb, $allergy, $rid) + $this->Er_census_model->count_res_pcpdx($datea, $dateb, $allergy, $rid) + $this->Micu_census_model->count_res_pcpdx($datea, $dateb, $allergy, $rid); 
	 echo "<tr><td>".$allergy."</td><td><font size=5>".$count."</font></td></tr>";	
	}			
echo "<tr><th colspan =2><a name=\"derma\">Dermatology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($derma_list as $derma){
     $count = 0; 
     $count = $this->Admission_model->count_res_pcpdx($datea, $dateb, $derma, $rid) + $this->Er_census_model->count_res_pcpdx($datea, $dateb, $derma, $rid) + $this->Micu_census_model->count_res_pcpdx($datea, $dateb, $derma, $rid); 
	 echo "<tr><td>".$derma."</td><td><font size=5>".$count."</font></td></tr>";	
	}	
echo "<tr><th colspan =2><a name=\"hema\">Hematology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($hema_list as $hema){
     $count = 0; 
     $count = $this->Admission_model->count_res_pcpdx($datea, $dateb, $hema, $rid) + $this->Er_census_model->count_res_pcpdx($datea, $dateb, $hema, $rid) + $this->Micu_census_model->count_res_pcpdx($datea, $dateb, $hema, $rid); 
	 echo "<tr><td>".$hema."</td><td><font size=5>".$count."</font></td></tr>";	
	}	
echo "<tr><th colspan =2><a name=\"neuro\">Neurology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($neuro_list as $neuro){
     $count = 0; 
     $count = $this->Admission_model->count_res_pcpdx($datea, $dateb, $neuro, $rid) + $this->Er_census_model->count_res_pcpdx($datea, $dateb, $neuro, $rid) + $this->Micu_census_model->count_res_pcpdx($datea, $dateb, $neuro, $rid); 
	 echo "<tr><td>".$neuro."</td><td><font size=5>".$count."</font></td></tr>";	
	}			
echo "<tr><th colspan =2><a name=\"tox\">Toxicology</a><div align=\"right\"><a href=\"#top\" >Top</a></div></th></tr>";
foreach ($tox_list as $tox){
     $count = 0; 
     $count = $this->Admission_model->count_res_pcpdx($datea, $dateb, $tox, $rid) + $this->Er_census_model->count_res_pcpdx($datea, $dateb, $tox, $rid) + $this->Micu_census_model->count_res_pcpdx($datea, $dateb, $tox, $rid); 
	 echo "<tr><td>".$tox."</td><td><font size=5>".$count."</font></td></tr>";	
	}			
echo "</table></div>";

//echo "</td></tr>";

//echo "</table>";
//echo "</div>";
?>