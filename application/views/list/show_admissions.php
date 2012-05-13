<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="created" content="Sun, 25 Sep 2011 04:34:35 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Search Admissions</title>
	<style type="text/css">
	a{text-align:left; font-size:x-large; font-weight:bold;
	   }
	</style>
	<link rel="stylesheet" type="text/css" href="/medisys/css/show.css" />
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

echo "<div align=\"center\"><h1>Admissions Search Results</h1></div>";

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
    $males = 0;
    $females = 0;	 	
    $m1 = 0;
    $m2 = 0;
    $m3 = 0;
    $m4 = 0;
    $m5 = 0;
    $m6 = 0;
    $f1 = 0;
    $f2 = 0;
    $f3 = 0;
    $f4 = 0;
    $f5 = 0;
    $f6 = 0;

    $primary = 0;
    $comx = 0;
    $preop = 0;

    $age_total = 0;
    $hd_total = 0;
    $hd1 = 0;
    $hd2 = 0;
    $hd3 = 0;
    $hd4 = 0;
    $hd_max = 1;
    $hd_min = 1;
    $age_max = 1;
    $age_min = 200;
	 if (!strcmp($my_service, 'er'))
         echo "<div align=\"center\"><h1>ER Admissions from ".$my_date1." to ".$my_date2.": Total - ".$numrows."</h1></div>";
     elseif  (!strcmp($my_service, 'micu'))
         echo "<div align=\"center\"><h1>MICU Admissions from ".$my_date1." to ".$my_date2.": Total - ".$numrows."</h1></div>";
     else
         echo "<div align=\"center\"><h1>Gen Med Svc '".$my_service."' Admissions from ".$my_date1." to ".$my_date2.": Total - ".$numrows."</h1></div>";

    
    foreach ($c_admissions as $row){
         $age = compute_age_adm($row->date_in, $row->p_bday);
         
         $hd = compute_hd($row->dispo, $row->date_in, $row->date_out);
         $eref_array = explode(",", $row->erefs);
         $ref_array = explode(",", $row->refs);
         //if (in_array("MICU", $ref_array))
           //  $micu_refer++;
//age count	 
         if (!strcmp($row->p_sex, 'M'))
         {
             $males++;
             if ($age < 18)
                 $m1++;
             elseif ($age >= 18 && $age < 40)
                 $m2++;
             elseif ($age >= 40 && $age <60)
                 $m3++;
             elseif ($age >= 60 && $age <70)
                 $m4++;
             elseif ($age >= 70 && $age <80)
                 $m5++;
             elseif ($age >= 80)
                 $m6++;
                 
         }
         else
         {
             $females++;
             if ($age < 18)
                 $f1++;
             elseif ($age >= 18 && $age < 40)
                 $f2++;
             elseif ($age >= 40 && $age <60)
                 $f3++;
             elseif ($age >= 60 && $age <70)
                 $f4++;
             elseif ($age >= 70 && $age <80)
                 $f5++;
             elseif ($age >= 80)
                 $f6++;
         }
         if (strcmp($my_service, 'er') && strcmp($my_service, 'micu'))
         {
              if (!strcmp($row->type, 'Primary'))
                  $primary++;
              elseif (!strcmp($row->type, 'Co-Managed'))
                  $comx++;
              elseif (!strcmp($row->type, 'Pre-operative'))
                  $preop++;
         }       
         
         if ($age >= $age_max)
             $age_max = $age;
         if ($age_min > $age)
             $age_min = $age;
         if ($hd_max < $hd)
             $hd_max = $hd;
         if ($hd_min > $hd)
             $hd_min = $hd;
         if ($hd <= 1)
             $hd1++;
         elseif ($hd >1 && $hd < 3)
             $hd2++;
         elseif ($hd >=3 && $hd < 7)
             $hd3++;
         elseif ($hd > 7)
             $hd4++; 
         $age_total = $age_total + $age;	
	     $hd_total = $hd_total + $hd;
    }
        //dispo table
    echo "<div style = \"float:left\">"; 
    make_dispo_table($my_service, $dispos, $refs['micu'], $numrows);
    echo "</div>";
     if ($numrows > 0){  
         echo "<div align=\"center\" style=\"width:1800px\">";
         if (strcmp($my_service, 'er') && strcmp($my_service, 'micu'))
         {
              echo "<div style = \"float:left\">"; 
              echo "<table><tr><th>Admission Type</th><th>Total</th><th>%</th></tr>";
              echo "<tr><td>Primary</td><td>".$primary."</td><td>".round((($primary/$numrows)*100), 2)."</td></tr>";
              echo "<tr><td>Co-Managed</td><td>".$comx."</td><td>".round((($comx/$numrows)*100), 2)."</td></tr>"; 
              echo "<tr><td>Peri-Operative</td><td>".$preop."</td><td>".round((($preop/$numrows)*100), 2)."</td></tr></table>";
              echo "</div>"; 
         }
         echo "<div style = \"float:left\">";
         echo "<table><tr><td>HD Max</td><td>".$hd_max." days</td></tr>";
         echo "<tr><td>HD Min</td><td>".$hd_min." days</td></tr>";
         echo "<tr><td>HD Average</td><td>".round(($hd_total/$numrows), 2)." days</td></tr>"; 
         echo "<tr><td>Age Max</td><td>".$age_max." yrs</td></tr>";
         echo "<tr><td>Age Min</td><td>".$age_min." yrs</td></tr>";
         echo "<tr><td>Age Ave</td><td>".round(($age_total/$numrows), 2)." yrs</td></tr></table>"; 
         echo "</div>";
         echo "<div style = \"float:left\">";
         echo "<table><tr><th></th><th>Total</th><th>%</th></tr>";
            //show sex-age table
         echo "<tr><td>Males</td><td>".$males."</td><td>".round((($males/$numrows)*100),2)."</td></tr>";
         echo "<tr><td>Below 18 yrs</td><td>".$m1."</td><td>".round((($m1/$numrows)*100),2)."</td></tr>";
         echo "<tr><td>18 to below 40 yrs</td><td>".$m2."</td><td>".round((($m2/$numrows)*100),2)."</td></tr>";
         echo "<tr><td>40 to below 60 yrs</td><td>".$m3."</td><td>".round((($m3/$numrows)*100),2)."</td></tr>";
         echo "<tr><td>60 to below 70 yrs</td><td>".$m4."</td><td>".round((($m4/$numrows)*100),2)."</td></tr>";
         echo "<tr><td>70 to below 80 yrs</td><td>".$m5."</td><td>".round((($m5/$numrows)*100),2)."</td></tr>";
         echo "<tr><td>Above 80 yrs</td><td>".$m6."</td><td>".round((($m6/$numrows)*100),2)."</td></tr></table>";
         echo "</div>";
         echo "<div style = \"float:left\">";
         echo "<table><tr><th></th><th>Total</th><th>%</th></tr>";
         echo "<tr><td>Females</td><td>".$females."</td><td>".round((($females/$numrows)*100),2)."</td></tr>";
         echo "<tr><td>Below 18 yrs</td><td>".$f1."</td><td>".round((($f1/$numrows)*100),2)."</td></tr>";
         echo "<tr><td>18 to below 40 yrs</td><td>".$f2."</td><td>".round((($f2/$numrows)*100),2)."</td></tr>";
         echo "<tr><td>40 to below 60 yrs</td><td>".$f3."</td><td>".round((($f3/$numrows)*100),2)."</td></tr>";
         echo "<tr><td>60 to below 70 yrs</td><td>".$f4."</td><td>".round((($f4/$numrows)*100),2)."</td></tr>";
         echo "<tr><td>70 to below 80 yrs</td><td>".$f5."</td><td>".round((($f5/$numrows)*100),2)."</td></tr>";
         echo "<tr><td>Above 80 yrs</td><td>".$f6."</td><td>".round((($f6/$numrows)*100),2)."</td></tr></table>";
         echo "</div>";
         echo "<div style = \"float:left\">";
         echo "<table><tr><th>Hospital days</th><th>Total</th><th>%</th></tr>";
         echo "<tr><td>1 day or less</td><td>".$hd1."</td><td>".round((($hd1/$numrows)*100),2)."</td></tr>";
         echo "<tr><td>1 to below 3 days</td><td>".$hd2."</td><td>".round((($hd2/$numrows)*100),2)."</td></tr>";
         echo "<tr><td>3 to below 7 days</td><td>".$hd3."</td><td>".round((($hd3/$numrows)*100),2)."</td></tr>";
         echo "<tr><td>7 days or more</td><td>".$hd4."</td><td>".round((($hd4/$numrows)*100),2)."</td></tr></table>";
         echo "</div>";
         echo "</div>";
            //referrals table
         echo "<div align = \"center\" style = \"clear:left\">";
	     make_refs_table($refs);
         echo "</div>";
            //ereferrals table
         echo "<div align = \"center\" style = \"clear:left\">";
	     make_erefs_table($erefs);
         echo "</table></div>";
         
         //females graph
         echo "<div id = \"femaletable\" align=\"center\"></div>";
         echo "<script type=\"text/javascript\">";
         echo "var myData = new Array(['<18', ".$f1."], ['18-40', ".$f2."], ['40-60', ".$f3."], ['60-70', ".$f4."], ['70-80', ".$f5."], ['>80', ".$f6."] );";
         echo "var myChart = new JSChart('femaletable', 'bar');";
         echo "myChart.setDataArray(myData);";
         echo "myChart.setAxisNameX('Age Group');";
         echo "myChart.setAxisNameY('Number of Admissions', true);";
         echo "myChart.setTitle('Admitted Females by Age Group');";
         echo "myChart.draw();";
         echo "var myColors = new Array('#0f0', '#ff0000', '#00f', '#ff0', '#00ffff', '#9ACD32');";
         echo "myChart.colorizeBars(myColors);";
         echo "myChart.resize(600, 300)";
         echo "</script>";
         
         //males graph
         echo "<div id = \"maletable\" align=\"center\"></div>";
         echo "<script type=\"text/javascript\">";
         echo "var myData = new Array(['<18', ".$m1."], ['18-40', ".$m2."], ['40-60', ".$m3."], ['60-70', ".$m4."], ['70-80', ".$m5."], ['>80', ".$m6."] );";
         echo "var myChart = new JSChart('maletable', 'bar');";
         echo "myChart.setDataArray(myData);";
         echo "myChart.setAxisNameX('Age Group');";
         echo "myChart.setAxisNameY('Number of Admissions', true);";
         echo "myChart.setTitle('Admitted Males by Age Group');";
         echo "myChart.draw();";
         echo "var myColors = new Array('#0f0', '#ff0000', '#00f', '#ff0', '#00ffff', '#9ACD32');";
         echo "myChart.colorizeBars(myColors);";
         echo "myChart.resize(600, 300)";
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
     }
}//if by report
 
else{ 
        if (!strcmp($my_service, 'er')){
            if (!strcmp($one_gm, 'res'))
                echo "<div align=\"center\"><h1>".$rname.": Total of ".$numrows." ER Admissions retrieved</h1></div>"; 
            elseif (!strcmp($one_gm, 'px'))
                echo "<div align=\"center\"><h1>".$pname.": Total of ".$numrows." ER Admissions retrieved</h1></div>";
        }
        elseif (!strcmp($my_service, 'micu')){   
            if (!strcmp($one_gm, 'res'))         
                echo "<div align=\"center\"><h1>".$rname.": Total of ".$numrows." MICU Admissions retrieved</h1></div>";
            elseif (!strcmp($one_gm, 'px'))
                echo "<div align=\"center\"><h1>".$pname.": Total of ".$numrows." MICU Admissions retrieved</h1></div>";
        }
        else{           
            if (!strcmp($one_gm, 'res'))
                echo "<div align=\"center\"><h1>".$rname.": Total of ".$numrows." Ward Admissions retrieved</h1></div>";   
            elseif (!strcmp($one_gm, 'px'))
                echo "<div align=\"center\"><h1>".$pname.": Total of ".$numrows." Ward Admissions retrieved</h1></div>";      
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
                    echo "<td>".$patient->p_name."</td>";
            }    
            if (!strcmp($one_gm, 'px')){
                echo "<td>";
                if (strcmp($my_service, 'er')){
                    $jr = $this->Resident_model->get_resident_name($row->r_id); 
                    foreach ($jr as $name)
        		  	    echo $name->r_name;
        		  	echo "</td>";    
                }
                else{
                    $jr = $this->Resident_model->get_resident_name($row->pod_id); 
                    foreach ($jr as $name)
    		  	        echo $name->r_name;
    		  	    echo "</td>";    
                }
            }
            if (strcmp($my_service, 'er'))
                echo "</td><td>".$row->sic."</td>";
            $hd = compute_hd($row->dispo, $row->date_in, $row->date_out);
            echo "<td>".$hd."</td></tr>";
            $x++;
        }
    }
    echo "</table></div>";
    if ($c_admissions){
        $x = 1;
        echo "<div align=\"center\"><table ><tr><th>Admission Data</th><th>Problem List and Medications</th><th>In-Referrals</th><th>Out-Referrals</th><th>Docs</th></tr>";
        foreach($c_admissions as $row){  
            if (!strcmp($my_service, 'er'))
		        $vars['eadmission'] = $row->er_id;
            elseif (!strcmp($my_service, 'micu'))
		        $vars['eadmission'] = $row->micu_id;
	        else
	            $vars['eadmission'] = $row->a_id;

	        $hd = compute_hd($row->dispo, revert_form_input($row->date_in), revert_form_input($row->date_out));
	        
	    //if not monitor, allow edit date dispo
	    if (strcmp($_SERVER['PHP_AUTH_USER'], 'monitor') && strcmp($one_gm, 'px'))
	      echo form_open('census/edit_date_out');
        echo "<tr><td><table id = \"leftcol\">";
        $pdata = $this->Patient_model->get_one_patient($row->p_id);
	    foreach ($pdata as $patient){
	        $age = compute_age_adm(revert_form_input($row->date_in), revert_form_input($patient->p_bday));	
            if (strcmp($my_service, 'er') && strcmp($my_service, 'micu'))
                make_case_num_row($my_service, $x, $row->type, revert_form_input($patient->cnum));
            else    
                make_case_num_row($my_service, $x, "", revert_form_input($patient->cnum));
            echo "<tr><td>Name: ".revert_form_input($patient->p_name)."</td></tr>";
	        echo "<tr><td>Age:".$age." Sex:".$patient->p_sex."</td></tr>";
        }
        if (strcmp($my_service, 'er'))
            echo "<tr><td>Source: ".$row->source."</td></tr>";
        if (strcmp($my_service, 'er') && strcmp($my_service, 'micu'))
            echo "<tr><td>Type: ".$row->type."</td></tr>";
	    echo "<tr><td>Status: ".$row->dispo."</td></tr>";
	    echo "<tr><td>GM Service: ".$row->service."</td></tr>";
        if (strcmp($my_service, 'er')){
            if (!strcmp($my_service, 'micu'))
                echo "<tr><td>Loc: MICU Bed ".$row->bed."</td></tr>";
            else
                echo "<tr><td>Loc: ".$row->location." Bed ".$row->bed."</td></tr>";
        }
        //POD if ER
        if (!strcmp($my_service, 'er')){
            echo "<tr><td>POD: ";
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
		    echo "<tr><td>SRIC: ";
		    if ($row->sr_id){
		            $sr = $this->Resident_model->get_resident_name($row->sr_id); 
		            foreach ($sr as $name)
		                echo revert_form_input($name->r_name);
		   }
		   else
		            echo "None";
		echo "</td></tr>";
		//JRIC
		echo "<tr><td>JRIC: ";

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
               echo "<tr><td>SIC: ".revert_form_input($row->sic)."</td></tr>";
        //Date IN     
	    echo "<tr><td>Date-IN: ".revert_form_input($row->date_in)." HD: ".$hd." days</td></tr>";
	    //Date OUT
	    echo "<tr><td>Date-OUT: ";
        if (strcmp($_SERVER['PHP_AUTH_USER'], 'monitor'))
		      echo "<input type=\"text\" name = \"date_out\" size = 8 value = \"".revert_form_input($row->date_out)."\">";
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
	    if (strcmp($_SERVER['PHP_AUTH_USER'], 'monitor') && strcmp($one_gm, 'px')){
                $label = array( 'name'=>"", 'value'=>"Edit Dispo Date", 'class'=>"");
                echo "<tr><td>";
                make_buttons($my_service, $label, $vars, "center", 'onClick = "return validateAedit(this.form)"');
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
     }
	 echo "</table></div>";
	  
  }
}  
  	    
  ?>
  
  </table>
  </body>
</html>
