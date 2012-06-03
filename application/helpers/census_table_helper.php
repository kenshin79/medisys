<?php

//Counter functions


       

/*
*$form_type legend
*  0 - new form
*  1 - editable form (old)
*  2 - non-editable
*/

//start of gendata column functions
function make_case_num_row($serv, $x, $type, $cnum){
	echo "<tr><td colspan = 2>UP";
	echo "[No. ".$x."]Case No: ".revert_form_input($cnum)."</a></td></tr>";
}

function make_source_row($form_type, $serv, $source, $loc_list){
	if (strcmp($serv, 'er')){
		if ($form_type == 0)
			echo "<tr><td colspan = 2>Source: ".form_dropdown('source', $loc_list, "ER")."</td></tr>";
		elseif ($form_type == 1)
        		echo "<tr><td colspan = 2>Source: ".form_dropdown('source', $loc_list, $source)."</td></tr>";
		else
			echo "<tr><td colspan = 2>Source: ".$source."</td></tr>";
	}
}
function make_type_row($form_type, $serv, $type, $type_list){
	if (strcmp($serv, 'er') && strcmp($serv, 'micu'))
        	echo "<tr><td colspan = 2>Type:".form_dropdown('type', $type_list, $type)."</td></tr>";
}
function make_status_row($form_type, $serv, $dispo, $dispo_list){
	echo "<tr><td colspan = 2>Status:".form_dropdown('dispo', $dispo_list, $dispo)."</td></tr>";

}
function make_pname_row($pname){
	echo "<tr><td class = \"allCaps\" colspan = 2>Name: ".revert_form_input($pname)."</td></tr>";
}
function make_agesex_row($age, $psex){
	echo "<tr><td colspan = 2>Age:".$age." / ".$psex."</td></tr>";
}
function make_gmservice_row($form_type, $service, $service_list){
	echo "<tr><td colspan = 2>GM Service: ".form_dropdown('service', $service_list, $service)."</td></tr>";
}
function make_micu_location_row($form_type, $bed, $loc_list, $mbed_list){
	echo "<tr><td colspan = 2>Loc: MICU Bed ".form_dropdown('bed', $mbed_list, $bed)."</td></tr>";
}	
function make_ward_location_row($form_type, $location, $bed, $loc_list, $bed_list){
	echo "<tr><td colspan = 2>Loc:".form_dropdown('location', $loc_list, $location)."Bed".form_dropdown('bed', $bed_list, $bed)."</td></tr>";
}
function make_pod_row($form_type, $podid, $pod, $residents){
	echo "<tr><td colspan = 2>POD: <select name = \"pod_id\">";
	echo "<option value =\"".$podid."\">";
	foreach ($pod as $row)
		echo revert_form_input($row->r_name);
	echo "</option>";
	foreach($residents as $res)
		 echo "<option value=\"".$res->r_id."\">".revert_form_input($res->r_name)."</option>";
	echo "</select></td></tr>";
}
function make_sric_row($form_type, $srid, $srname, $residents){
	echo "<tr><td colspan = 2>SRIC: <select name = \"sr_id\">";
	echo "<option value =\"".$srid."\">";
	foreach ($srname as $row)
		echo revert_form_input($row->r_name);
	echo "</option>";
	foreach($residents as $row)
		echo "<option value=\"".$row->r_id."\">".revert_form_input($row->r_name)."</option>";
	echo "</select></td></tr>";
}
function make_jric_row($form_type, $jrid, $jrname, $residents){
	echo "<tr><td colspan = 2>JRIC/SCU-SAPOD: <select name = \"r_id\">";
	echo "<option value =\"".$jrid."\">";
	foreach ($jrname as $row)
		echo revert_form_input($row->r_name);
	echo "</option>";	
	foreach($residents as $row)
		echo "<option value=\"".$row->r_id."\">".revert_form_input($row->r_name)."</option>";
	echo "</select></td></tr>";
}
function make_sic_row($form_type, $sic){
	echo "<tr><td colspan = 2>SIC:".form_input('sic', revert_form_input($sic))."</td></tr>";
}
function make_datein_row($form_type, $date_in, $hd){
	$a_datein = array(
				      'name'=>'date_in',
		           	      'size'=>'6',
			  	      'value'=>revert_form_input($date_in),
		      	  );
	echo "<tr><td>Date-IN:".form_input($a_datein)."<font size=\"2\">(yyyy-mm-dd)</font><br />HD: ".$hd." days</td></tr>";
	
}
function make_dateout_row($form_type, $date_out){
	$a_dateout = array(
				      'name'=>'date_out',
		                      'size'=>'6',
			  	      'value'=>revert_form_input($date_out),
		      	   );
	echo "<tr><td>Date-OUT:".form_input($a_dateout)."<font size=\"2\">(yyyy-mm-dd)</font></td></tr>";
}
//end of gendata column functions

//start of plist column functions

function make_plist_row($form_type, $plist){
	echo "<tr><td id = \"mednotes\">Problem List:\n<textarea name = \"plist\" rows = \"6\" cols = \"55\" wrap = \"off\">".revert_form_input($plist)."</textarea></td></tr>";
} 
function make_pcpdx_row($pcpdx){
	$cs_pcpdx = explode(",", revert_form_input($pcpdx));
	echo "<tr><td id = \"mednotes\">ICD/PCP:<textarea rows=\"1\" cols=\"55\" wrap=\"off\" readonly=\"readonly\">";
	foreach ($cs_pcpdx as $pcp)
		echo $pcp."\n";
	echo "</textarea></td></tr>";
}
//end of plist column functions

//start of meds column functions
function make_meds_row($form_type, $meds){
	echo "<tr><td id = \"mednotes\">Medications:\n<textarea  name = \"meds\" rows = \"3\" cols = \"55\" wrap = \"off\">".revert_form_input($meds)."</textarea></td></tr>";
}
function make_oldnotes_row($notes){
	echo "<tr><th>Notes <input type=\"button\" value = \"View\" onClick = \"return showNotes(this.form)\"/></th></tr>";
	echo "<tr><td id = \"mednotes\"><textarea rows =\"2\" cols = \"55\"   name =\"pnotes\"  wrap = \"off\">".revert_form_input($notes)."</textarea></td></tr>";
}
function make_newnotes_row(){
	echo "<tr><td id = \"mednotes\">Add New Notes Below<textarea rows =\"8\" cols = \"55\" name = \"notes\" wrap = \"off\"></textarea></td></tr>";
}

function make_notes($notes){
	echo "<tr><td>Old Notes <input style = \"align:top\" type=\"button\" value = \"View\" onClick = \"return showNotes(this.form)\"/><textarea rows =\"1\" cols = \"55\"   name =\"pnotes\"  wrap = \"off\">".revert_form_input($notes)."</textarea></td><tr>";
	echo "<tr><td>Add New Notes<textarea rows =\"1\" cols = \"55\" name = \"notes\" wrap = \"off\"></textarea></td></tr>";
}

//end of meds column functions

//start refs and erefs column
function make_refs_row($serv, $def_cb, $ref_array, $refs){
	$cs_refs = explode(",", revert_form_input($refs));
	echo form_checkbox($def_cb)."Check<br />";
	if (strcmp($serv, 'micu'))
	{
	 	foreach ($ref_array as $r) 
		{
	         	if (in_array($r, $cs_refs))
	   		   	     echo "<span>".form_checkbox('refs[]', $r, TRUE).$r."</span><br />";
			else 
                                     echo "<span>".form_checkbox('refs[]', $r, FALSE).$r."</span><br />"; 	  
		 }
	}
        if (!strcmp($serv, 'micu'))
	{
                 foreach ($ref_array as $r) 
		 {
                      	if (strcmp($r, 'MICU'))
			{
                       		if (in_array($r, $cs_refs))
                       			echo "<span>".form_checkbox('refs[]', $r, TRUE).$r."</span><br />";
				else 
                       			echo "<span>".form_checkbox('refs[]', $r, FALSE).$r."</span><br />"; 
                         }	  
		  }
         }
}
function make_erefs_row($def_cb1, $eref_array, $erefs){ 
	$cs_erefs = explode(",", revert_form_input($erefs));
	echo form_checkbox($def_cb1)."Check<br />";
	foreach ($eref_array as $er) 
	{
	  	if (in_array($er, $cs_erefs))
		      echo "<span>".form_checkbox('erefs[]', $er, TRUE).$er."</span><br />";
	 	else 
		      echo "<span>".form_checkbox('erefs[]', $er, FALSE).$er."</span><br />"; 	  
		  		    
	}
}
//end of refs and erefs column

//make demographic-census tables
function make_dispo_table($serv, $dispos, $r_micu, $numrows){
	 echo "<table><tr><th>Dispositions</th><th>Total</th><th>%</th></tr>"; 
         echo "<tr><td>Admitted</td><td>".$dispos['admit']."</td><td>".round((($dispos['admit']/$numrows)*100), 2)."</td></tr>";
         echo "<tr><td>Discharged</td><td>".$dispos['disch']."</td><td>".round((($dispos['disch']/$numrows)*100), 2)."</td></tr>";
         echo "<tr><td>TOS</td><td>".$dispos['tos']."</td><td>".round((($dispos['tos']/$numrows)*100), 2)."</td></tr>";
         echo "<tr><td>Mortality</td><td>".$dispos['mort']."</td><td>".round((($dispos['mort']/$numrows)*100), 2)."</td></tr>";
	 echo "<tr><td>HAMA</td><td>".$dispos['hama']."</td><td>".round((($dispos['hama']/$numrows)*100), 2)."</td></tr>";
         if ((!strcmp($serv, 'er')) || (!strcmp($serv, 'micu')))
              echo "<tr><td>Admitted to Wards</td><td>".$dispos['a_wards']."</td><td>".round((($dispos['a_wards']/$numrows)*100), 2)."</td></tr>";
         if(strcmp($serv, 'micu'))
         {
         	echo "<tr><td>Admitted to MICU</td><td>".$dispos['a_micu']."</td><td>".round((($dispos['a_micu']/$numrows)*100), 2)."</td></tr>";
                echo "<tr><td>Referred to MICU</td><td>".$r_micu."</td><td>".round((($r_micu/$numrows)*100), 2)."</td></tr>";
	 }
         if (strcmp($serv, 'micu') && ($r_micu > 0))
                echo "<tr><td>MICU adm: ref</td><td>".$dispos['a_micu']."/".$r_micu."</td><td>".round((($dispos['a_micu']/$r_micu)*100), 2)."</td></tr>";
         echo "</table>";
}
function make_refs_table($refs){
	 echo "<table><tr><th>Referral</th><th>Total</th><th>Referral</th><th>Total</th><th>Referral</th><th>Total</th></tr>";
         echo "<tr><td>Allergy</td><td>".$refs['allergy']."</td><td>Cardio</td><td>".$refs['cardio']."</td><td>Nephro</td><td>".$refs['nephro']."</td></tr>";
         echo "<tr><td>Endo</td><td>".$refs['endo']."</td><td>Derma</td><td>".$refs['derma']."</td><td>Hema</td><td>".$refs['hema']."</td></tr>";
         echo "<tr><td>HTN</td><td>".$refs['htn']."</td><td>IDS</td><td>".$refs['ids']."</td><td>GI</td><td>".$refs['gi']."</td></tr>";
         echo "<tr><td>Rheuma</td><td>".$refs['rheuma']."</td><td>Onco</td><td>".$refs['onco']."</td><td>Pulmo</td><td>".$refs['pulmo']."</td></tr>";
         echo "</table>";
}
function make_erefs_table($erefs){
echo "<table><tr><th>Referral</th><th>Total</th><th>Referral</th><th>Total</th><th>Referral</th><th>Total</th></tr>";
         echo "<tr><td>DNET</td><td>".$erefs['dnet']."</td><td>DECT</td><td>".$erefs['dect']."</td><td>Dietary</td><td>".$erefs['dietary']."</td></tr>";
         echo "<tr><td>GS</td><td>".$erefs['gs']."</td><td>Uro</td><td>".$erefs['uro']."</td><td>Neuro</td><td>".$erefs['neuro']."</td></tr>";
         echo "<tr><td>NSS</td><td>".$erefs['nss']."</td><td>Ortho</td><td>".$erefs['ortho']."</td><td>Plastic</td><td>".$erefs['plastic']."</td></tr>";
         echo "<tr><td>TCVS</td><td>".$erefs['tcvs']."</td><td>Trauma</td><td>".$erefs['trauma']."</td><td>ORL</td><td>".$erefs['orl']."</td></tr>";
         echo "<tr><td>Ophtha</td><td>".$erefs['ophtha']."</td><td>Psych</td><td>".$erefs['psych']."</td><td>Ob-Gyn</td><td>".$erefs['ob-gyn']."</td></tr>";
         echo "<tr><td>Radio</td><td>".$erefs['radio']."</td><td>Tox</td><td>".$erefs['tox']."</td><td>Pedia</td><td>".$erefs['pedia']."</td></tr>";
         echo "<tr><td>FM</td><td>".$erefs['fm']."</td><td>Rehab</td><td>".$erefs['rehab']."</td><td></td><td></td></tr>";
}

