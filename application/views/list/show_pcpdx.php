<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Mon, 24 Oct 2011 03:14:15 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Medisys - Edit ICD/PCP</title>
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
//if from manage resident
if (!strcmp($one_gm, "res")){
    $eresident = $this->session->userdata('eresident');
    $rname = $this->session->userdata('rname');
    $vars['eresident'] = $eresident;
    $vars['rname'] = $rname;
}
//greeting user and logout link
make_page_header($_SERVER['PHP_AUTH_USER']);



if (!strcmp($one_gm, 'y')){ 	
    echo form_open('census/one_gm_census');
    $label = array('value'=>"Back to Admissions");
    make_buttons($my_service, $label, $vars, "left", "");
    echo form_close();
}

elseif (!strcmp($one_gm, 'res')){
    echo form_open('census/resident_census');
    $label = array('value'=>"Back to Residents");
    make_buttons($my_service, $label, $vars, "left", "");
    echo form_close();
}
echo "<div align=\"center\">";
foreach ($p_admission as $row){
    echo "<table>";
	if (!strcmp($one_gm, 'res'))
		echo form_open('census/update_pcpdx');
	else	
		echo form_open('show/update_pcpdx');
    if (!strcmp($my_service, 'micu'))
        $vars['eadmission'] = $row->micu_id;
    elseif (!strcmp($my_service, 'er'))
        $vars['eadmission'] = $row->er_id;
    else
        $vars['eadmission'] = $row->a_id;
    $pdata = $this->Patient_model->get_one_patient($row->p_id);
	foreach ($pdata as $patient){    
        echo "<tr><td><table><tr><td><a name=\"top\">Patient Name</a>: ".revert_form_input($patient->p_name)."</td></tr>";
        echo "<tr><td>Case Number: ".revert_form_input($patient->cnum)."</td></tr>";
    }    
    echo "<tr><td>Date of Admission: ".revert_form_input($row->date_in)."</td></tr></table></td>";  
    echo "<td>Problem List<br />";
    echo "<textarea rows=\"10\" cols=\"30\" readonly=\"readonly\">".revert_form_input($row->plist)."</textarea></td></tr></table>";

    //get list of pcp dx
    $pcpdx = explode(",", revert_form_input($row->pcpdx)); 
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

    echo "<table>";
    echo "<tr><th><a href=\"#pulmo\" >Pulmonary</a></th><th><a href=\"#ids\" >Infectious</a></th><th><a href=\"#gastro\" >Gastroenterology</a></th><th><a href=\"#cardio\" >Cardiology</th></a><th><a href=\"#endo\" >Endocrinology</a></th></tr>";
    echo "<tr><th><a href=\"#rheuma\" >Rheumatology</a></th><th><a href=\"#onco\"> Oncology</a></th><th><a href=\"#nephro\" >Nephrology</a></th><th><a href=\"#allergy\" >Allergy</a></th><th><a href=\"#derma\" >Dermatology</a></th></tr>";
    echo "<th><a href=\"#hema\" >Hematology</a></th><th><a href=\"#neuro\" >Neurology</a></th><th><a href=\"#tox\" >Toxicology</a></th></tr>";
    echo "</table>";
    echo "</table>";

    echo "<table>";
    echo "<td>";
    echo "<table><tr><th><div align=\"left\"><a href=\"#top\">UP</a></div></div><a name=\"pulmo\">Pulmonary</a><div align=\"left\"><a href=\"#bottom\"> DOWN</a></div></th>";
    foreach ($pulmo_list as $pulmo){
        if (in_array($pulmo, $pcpdx))
	        echo "<tr><td>".form_checkbox('pcpdx[]', $pulmo, TRUE).$pulmo."</td></tr>";
	    else
	        echo "<tr><td>".form_checkbox('pcpdx[]', $pulmo, FALSE).$pulmo."</td></tr>";
	}		
    echo "<tr><th><div align=\"left\"><a href=\"#top\">UP </a></div><a name=\"ids\">Infectious</a><div align=\"left\"><a href=\"#bottom\"> DOWN</a></div></th>";
    foreach ($ids_list as $ids){
        if (in_array($ids, $pcpdx))
	        echo "<tr><td>".form_checkbox('pcpdx[]', $ids, TRUE).$ids."</td></tr>";
	    else
	        echo "<tr><td>".form_checkbox('pcpdx[]', $ids, FALSE).$ids."</td></tr>";
	}			
    echo "<tr><th><div align=\"left\"><a href=\"#top\">UP </a></div><a name=\"gastro\">Gastroenterology</a><div align=\"left\"><a href=\"#bottom\"> DOWN</a></div></th>";
    foreach ($gastro_list as $gastro){
        if (in_array($gastro, $pcpdx))
	        echo "<tr><td>".form_checkbox('pcpdx[]', $gastro, TRUE).$gastro."</td></tr>";
	    else
	        echo "<tr><td>".form_checkbox('pcpdx[]', $gastro, FALSE).$gastro."</td></tr>";
	}				
    echo "</table></td>";
    echo "<td>";
    echo "<table><tr><th><div align=\"left\"><a href=\"#top\">UP </a></div><a name=\"cardio\">Cardiology</a><div align=\"left\"><a href=\"#bottom\"> DOWN</a></div></th></tr>";
    foreach ($cardio_list as $cardio){
        if (in_array($cardio, $pcpdx))
	        echo "<tr><td>".form_checkbox('pcpdx[]', $cardio, TRUE).$cardio."</td></tr>";
	    else
	        echo "<tr><td>".form_checkbox('pcpdx[]', $cardio, FALSE).$cardio."</td></tr>";
	}	
    echo "<tr><th><div align=\"left\"><a href=\"#top\">UP </a></div><a name=\"endo\">Endocrinology</a><div align=\"left\"><div align=\"left\"><a href=\"#bottom\"> DOWN</a></div></th>";
    foreach ($endo_list as $endo){
        if (in_array($endo, $pcpdx))
	        echo "<tr><td>".form_checkbox('pcpdx[]', $endo, TRUE).$endo."</td></tr>";
	    else
	        echo "<tr><td>".form_checkbox('pcpdx[]', $endo, FALSE).$endo."</td></tr>";
	}		
    echo "<tr><th><div align=\"left\"><a href=\"#top\">UP </a></div><a name=\"rheuma\">Rheumatology</a><div align=\"left\"><a href=\"#bottom\"> DOWN</a></div></th></tr>";
    foreach ($rheuma_list as $rheuma){
        if (in_array($rheuma, $pcpdx))
	        echo "<tr><td>".form_checkbox('pcpdx[]', $rheuma, TRUE).$rheuma."</td></tr>";
	    else
	        echo "<tr><td>".form_checkbox('pcpdx[]', $rheuma, FALSE).$rheuma."</td></tr>";
	}			
    echo "<tr><th><div align=\"left\"><a href=\"#top\">UP </a></div><a name=\"onco\">Oncology</a><div align=\"left\"><a href=\"#bottom\"> DOWN</a></div></th></tr>";
    foreach ($onco_list as $onco){
        if (in_array($onco, $pcpdx))
	        echo "<tr><td>".form_checkbox('pcpdx[]', $onco, TRUE).$onco."</td></tr>";
	    else
	        echo "<tr><td>".form_checkbox('pcpdx[]', $onco, FALSE).$onco."</td></tr>";
	}				
    echo "</table></td>";
    echo "<td>";
    echo "<table><tr><th><div align=\"left\"><a href=\"#top\">UP </a></div><a name=\"nephro\">Nephrology</a><div align=\"left\"><a href=\"#bottom\"> DOWN</a></div></th></tr>";
    foreach ($nephro_list as $nephro){
        if (in_array($nephro, $pcpdx))
	        echo "<tr><td>".form_checkbox('pcpdx[]', $nephro, TRUE).$nephro."</td></tr>";
	    else
	        echo "<tr><td>".form_checkbox('pcpdx[]', $nephro, FALSE).$nephro."</td></tr>";
	}		
    echo "<tr><th><div align=\"left\"><a href=\"#top\">UP </a></div><a name=\"allergy\">Allergy</a><div align=\"left\"><a href=\"#bottom\"> DOWN</a></div></th></tr>";
    foreach ($allergy_list as $allergy){
        if (in_array($nephro, $pcpdx))
	        echo "<tr><td>".form_checkbox('pcpdx[]', $allergy, TRUE).$allergy."</td></tr>";
	    else
	        echo "<tr><td>".form_checkbox('pcpdx[]', $allergy, FALSE).$allergy."</td></tr>";
	}			
    echo "<tr><th><div align=\"left\"><a href=\"#top\">UP </a></div><a name=\"derma\">Dermatology</a><div align=\"left\"><a href=\"#bottom\"> DOWN</a></div></th></tr>";
    foreach ($derma_list as $derma){
        if (in_array($derma, $pcpdx))
	        echo "<tr><td>".form_checkbox('pcpdx[]', $derma, TRUE).$derma."</td></tr>";
	    else
	        echo "<tr><td>".form_checkbox('pcpdx[]', $derma, FALSE).$derma."</td></tr>";
	}	
    echo "<tr><th><div align=\"left\"><a href=\"#top\">UP </a></div><a name=\"hema\">Hematology</a><div align=\"left\"><a href=\"#bottom\"> DOWN</a></div></th></tr>";
    foreach ($hema_list as $hema){
        if (in_array($hema, $pcpdx))
	        echo "<tr><td>".form_checkbox('pcpdx[]', $hema, TRUE).$hema."</td></tr>";
	    else
	        echo "<tr><td>".form_checkbox('pcpdx[]', $hema, FALSE).$hema."</td></tr>";
	}	
    echo "<tr><th><div align=\"left\"><a href=\"#top\">UP </a></div><a name=\"neuro\">Neurology</a><div align=\"left\"><a href=\"#bottom\"> DOWN</a></div></th></tr>";
    foreach ($neuro_list as $neuro){
        if (in_array($neuro, $pcpdx))
	        echo "<tr><td>".form_checkbox('pcpdx[]', $neuro, TRUE).$neuro."</td></tr>";
	    else
	        echo "<tr><td>".form_checkbox('pcpdx[]', $neuro, FALSE).$neuro."</td></tr>";
	}		
    echo "<tr><th><div align=\"left\"><a href=\"#top\">UP </a></div><a name=\"tox\">Toxicology</a><div align=\"left\"><a href=\"#bottom\"> DOWN</a></div></th></tr>";
    foreach ($tox_list as $tox){
        if (in_array($tox, $pcpdx))
	        echo "<tr><td>".form_checkbox('pcpdx[]', $tox, TRUE).$tox."</td></tr>";
	    else
	         echo "<tr><td>".form_checkbox('pcpdx[]', $tox, FALSE).$tox."</td></tr>";
	}			
    echo "</table></td>";
    echo "</table>";
    $ep = array('value'=>'Edit ICD/PCP', 'class'=>'menub');
    echo "<a name=\"bottom\">".make_buttons($my_service, $ep, $vars, "center", "")."</a>";
    echo form_close();
}
echo "<br/><a href=\"#top\">Back to TOP </a>";
echo "</div>";
?>
  </body>
   
</html>
