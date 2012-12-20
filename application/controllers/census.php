<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Census extends CI_Controller {
      
      public function index(){
	
	  }
/*Start of traced functions (3/18/12)*/
	  //by one gm service/er/micu with editing 
function get_selected_admission(){
	$my_service = $this->input->get('my_service', TRUE);
    $my_dispo = $this->input->get('my_dispo', TRUE);
    $one_gm = $this->input->get('one_gm', TRUE);
    $stp1 = $this->input->get('stp1', TRUE); 
    $aid = $this->input->get('aid', TRUE);
	$census = array( 
	   	  		   'my_service'=>$my_service,
				   'my_dispo'=>$my_dispo,
				   'one_gm'=>$one_gm,
				   'stp1'=>$stp1,
		   );
	$this->session->set_userdata($census);
        if (!strcmp($my_service, 'er'))
        {
             	$data['c_admissions'] = $this->Er_census_model->get_one_admission($aid); 
	}
        elseif (!strcmp($my_service, 'micu'))
	{
                $data['c_admissions'] = $this->Micu_census_model->get_one_admission($aid);
	}
        elseif(!strcmp($my_service, 'preop')){
                $data['c_admissions'] = $this->Admission_model->get_one_admission($aid);
        } 
        else{
	        $data['c_admissions'] = $this->Admission_model->get_one_admission($aid); 
        }
	$this->load->view('list/selected_admission', $data); 
    


}
//get resident reports
function get_res_report(){
	$my_service = $this->input->post('my_service', TRUE);
	$my_dispo = $this->input->post('my_dispo', TRUE);
	$one_gm = $this->input->post('one_gm', TRUE);
	$stp1 = $this->input->post('stp1', TRUE);
	$census = array( 
		    	  		   'my_service'=>$my_service,
						   'my_dispo'=>$my_dispo,
						   'one_gm'=>$one_gm,
						   'stp1'=>$stp1
		                );
	$r_id = $this->input->post('eresident'); 
	$rname = $this->input->post('rname'); 
	$census['eresident'] = $r_id;
	$census['rname'] = $rname;
	$this->session->set_userdata($census);	
	$this->load->view('list/res_reports');
}
//show resident report
function show_res_report(){
	$my_service = $this->input->post('my_service', TRUE);
	$my_dispo = $this->input->post('my_dispo', TRUE);
	$one_gm = $this->input->post('one_gm', TRUE);
	$stp1 = $this->input->post('stp1', TRUE);
	$census = array( 
		    	  		   'my_service'=>$my_service,
						   'my_dispo'=>$my_dispo,
						   'one_gm'=>$one_gm,
						   'stp1'=>$stp1
		                );
	$r_id = $this->input->get('rid', TRUE); 
	$census['eresident'] = $r_id;
	$this->session->set_userdata($census);	
	$datea = $this->input->get('datea', TRUE);
	$dateb = $this->input->get('dateb', TRUE);
	$data['wcount'] = $this->Admission_model->count_res_adm($datea, $dateb, $r_id);
	$data['comxcount'] = $this->Admission_model->count_res_comx($datea, $dateb, $r_id);
	$data['preopcount'] = $this->Admission_model->count_res_preop($datea, $dateb, $r_id);
	$data['mcount'] = $this->Micu_census_model->count_res_adm($datea, $dateb, $r_id);
	$data['ecount'] = $this->Er_census_model->count_res_adm($datea, $dateb, $r_id);
	$data['datea'] = $datea;
	$data['dateb'] = $dateb;
	$data['rid'] = $r_id;
	$this->load->view('list/selected_res_report', $data);
}


function get_res_selected_admission(){
	$my_service = $this->input->get('my_service', TRUE);
    $my_dispo = $this->input->get('my_dispo', TRUE);
    $one_gm = $this->input->get('one_gm', TRUE);
    $stp1 = $this->input->get('stp1', TRUE); 
    $aid = $this->input->get('aid', TRUE);
	$census = array( 
	   	  		   'my_service'=>$my_service,
				   'my_dispo'=>$my_dispo,
				   'one_gm'=>$one_gm,
				   'stp1'=>$stp1,
		   );
	$this->session->set_userdata($census);
        if (!strcmp($my_service, 'er'))
             	$data['c_admissions'] = $this->Er_census_model->get_one_admission($aid); 
        elseif (!strcmp($my_service, 'micu'))
                $data['c_admissions'] = $this->Micu_census_model->get_one_admission($aid);
		elseif (!strcmp($my_service, 'comx'))		
				$data['c_admissions'] = $this->Admission_model->get_one_admission($aid);
        elseif(!strcmp($my_service, 'preop')){
                $data['c_admissions'] = $this->Admission_model->get_one_admission($aid);
        } 
        else{
	        $data['c_admissions'] = $this->Admission_model->get_one_admission($aid); 
        }
	$this->load->view('list/selected_resadmission', $data); 
    


}

	  //update ICD/PCP of admission
function update_pcpdx(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  if (!strcmp($one_gm, "res")){    
       	        $r_id = $this->input->post('eresident');
		        $rname = $this->input->post('rname');
                $data['rname'] = $rname;
                $data['eresident'] = $r_id;
          }
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
		       $this->Er_census_model->edit_pcpdx($aid);
          elseif (!strcmp($my_service, 'micu'))
    	       $this->Micu_census_model->edit_pcpdx($aid);
          else
               $this->Admission_model->edit_pcpdx($aid);
		  //wards	   
		  $data['sr_wadmissions'] = $this->Admission_model->get_sresidents_adm($r_id, $my_service);
		  $data['jr_wadmissions'] = $this->Admission_model->get_jresidents_adm($r_id, $my_service);
		  $data['w_admissions'] = array_merge($data['sr_wadmissions'], $data['jr_wadmissions']);
		  //micu
          $data['sr_madmissions'] = $this->Micu_census_model->get_sresidents_adm($r_id);
		  $data['jr_madmissions'] = $this->Micu_census_model->get_jresidents_adm($r_id);
	      $data['m_admissions'] = array_merge($data['sr_madmissions'], $data['jr_madmissions']);		
		  //ER
		  $data['e_admissions'] = $this->Er_census_model->get_erresidents_adm($r_id);		
		  //comx
		  $data['sr_cmadmissions'] = $this->Admission_model->get_sresidents_adm($r_id, "comx");
		  $data['jr_cmadmissions'] = $this->Admission_model->get_jresidents_adm($r_id, "comx");
		  $data['cm_admissions'] = array_merge($data['sr_cmadmissions'], $data['jr_cmadmissions']);		
	      //preop
	      $data['sr_padmissions'] = $this->Admission_model->get_sresidents_adm($r_id, "preop");
	  	  $data['jr_padmissions'] = $this->Admission_model->get_jresidents_adm($r_id, "preop");
	  	  $data['p_admissions'] = array_merge($data['sr_padmissions'], $data['jr_padmissions']);
	      		  
			   
		  $this->load->view('list/show_res_admissions', $data);	      
			   
			   
}			   

	  
function one_gm_census(){
	$my_service = $this->input->post('my_service', TRUE);
    $my_dispo = $this->input->post('my_dispo', TRUE);
    $one_gm = $this->input->post('one_gm', TRUE);
    $stp1 = $this->input->post('stp1', TRUE); 
	$census = array( 
	   	  		   'my_service'=>$my_service,
				   'my_dispo'=>$my_dispo,
				   'one_gm'=>$one_gm,
				   'stp1'=>$stp1,
		   );
	$this->session->set_userdata($census);
        if (!strcmp($my_service, 'er'))
        {
             	$data['c_admissions'] = $this->Er_census_model->get_er_census($my_dispo); 
	}
        elseif (!strcmp($my_service, 'micu'))
	{
                $data['c_admissions'] = $this->Micu_census_model->get_micu_census($my_dispo);
	}
        elseif(!strcmp($my_service, 'preop')){
                $data['c_admissions'] = $this->Admission_model->get_gm_census($my_service, $my_dispo);
        } 
        else{
	        $data['c_admissions'] = $this->Admission_model->get_gm_census($my_service, $my_dispo); 
	        $data['c_micu'] = $this->Micu_census_model->get_micugm_census($my_service);
		    $data['c_er'] = $this->Er_census_model->get_ergm_census($my_service);
        }
	$this->load->view('list/show_onegmadmissions', $data); 
} 
          



//census by GM service (no edit)


function gm_census(){
	    $my_service = $this->input->post('my_service', TRUE);
		foreach ($_POST as $k=>$v){
			if (in_array($k, array('my_datea', 'my_datec', 'my_datee'))) 	
				$my_date1 = $v;
			if (in_array($k, array('my_dateb', 'my_dated', 'my_datef'))) 
				$my_date2 = $v;				
		}
	    $census = array( 
  	  		               'my_service'=>$my_service,
			               'my_dispo'=>'All',
                           'my_date1'=>$my_date1,
                           'my_date2'=>$my_date2,
		   );
       $this->session->set_userdata($census);	
       if (!strcmp($my_service, 'er'))
       {
	      $data['refs'] = array(
					'allergy'=>$this->Er_census_model->count_refs( "Allergy", $my_date1, $my_date2),
		   			'cardio'=>$this->Er_census_model->count_refs("Cardio", $my_date1, $my_date2),
		   			'nephro'=>$this->Er_census_model->count_refs("Nephro", $my_date1, $my_date2),
                   			'endo'=>$this->Er_census_model->count_refs("Endo", $my_date1, $my_date2),
		   			'derma'=>$this->Er_census_model->count_refs("Derma", $my_date1, $my_date2),
		   			'hema'=>$this->Er_census_model->count_refs("Hema", $my_date1, $my_date2),
		   			'htn'=>$this->Er_census_model->count_refs("HTN", $my_date1, $my_date2),
		   			'ids'=>$this->Er_census_model->count_refs("IDS", $my_date1, $my_date2), 
		   			'gi'=>$this->Er_census_model->count_refs("GI", $my_date1, $my_date2),
		   			'rheuma'=>$this->Er_census_model->count_refs("Rheuma", $my_date1, $my_date2),
		  			'onco'=>$this->Er_census_model->count_refs("Onco", $my_date1, $my_date2),
		   			'pulmo'=>$this->Er_census_model->count_refs("Pulmo", $my_date1, $my_date2),
                   			'micu'=>$this->Er_census_model->count_refs("MICU", $my_date1, $my_date2),
				);	
              $data['erefs'] = array(
					'dnet'=>$this->Er_census_model->count_erefs("DNET", $my_date1, $my_date2),
                   			'dect'=>$this->Er_census_model->count_erefs("DECT", $my_date1, $my_date2),
                   			'dietary'=>$this->Er_census_model->count_erefs("Dietary", $my_date1, $my_date2),
		   			'gs'=>$this->Er_census_model->count_erefs("GS", $my_date1, $my_date2),
		   			'uro'=>$this->Er_census_model->count_erefs("Uro", $my_date1, $my_date2),
		   			'neuro'=>$this->Er_census_model->count_erefs("Neuro", $my_date1, $my_date2),
		   			'nss'=>$this->Er_census_model->count_erefs("NSS", $my_date1, $my_date2),
		   			'ortho'=>$this->Er_census_model->count_erefs("Ortho", $my_date1, $my_date2),
		   			'plastic'=>$this->Er_census_model->count_erefs("Plastic", $my_date1, $my_date2),
		   			'tcvs'=>$this->Er_census_model->count_erefs("TCVS", $my_date1, $my_date2),
		   			'trauma'=>$this->Er_census_model->count_erefs("Trauma", $my_date1, $my_date2),
		   			'orl'=>$this->Er_census_model->count_erefs("ORL", $my_date1, $my_date2),
		   			'ophtha'=>$this->Er_census_model->count_erefs("Ophtha", $my_date1, $my_date2),
		   			'psych'=>$this->Er_census_model->count_erefs("Psych", $my_date1, $my_date2),
		   			'ob-gyn'=>$this->Er_census_model->count_erefs("Ob-Gyn", $my_date1, $my_date2),
		   			'radio'=>$this->Er_census_model->count_erefs("Radio", $my_date1, $my_date2),
		   			'tox'=>$this->Er_census_model->count_erefs("Tox", $my_date1, $my_date2),
		   			'pedia'=>$this->Er_census_model->count_erefs("Pedia", $my_date1, $my_date2),
		   			'fm'=>$this->Er_census_model->count_erefs("FM", $my_date1, $my_date2),	
              		'rehab'=>$this->Er_census_model->count_erefs("Rehab", $my_date1, $my_date2),
				);

					$data['c_admissions'] = $this->Er_census_model->er_report($my_service, $my_date1, $my_date2);   
       }
       elseif (!strcmp($my_service, 'micu'))
       {
	      $data['refs'] = array(
					'allergy'=>$this->Micu_census_model->count_refs("Allergy", $my_date1, $my_date2),
		   			'cardio'=>$this->Micu_census_model->count_refs("Cardio", $my_date1, $my_date2),
		   			'nephro'=>$this->Micu_census_model->count_refs("Nephro", $my_date1, $my_date2),
                   			'endo'=>$this->Micu_census_model->count_refs("Endo", $my_date1, $my_date2),
		   			'derma'=>$this->Micu_census_model->count_refs("Derma", $my_date1, $my_date2),
		   			'hema'=>$this->Micu_census_model->count_refs("Hema", $my_date1, $my_date2),
		   			'htn'=>$this->Micu_census_model->count_refs("HTN", $my_date1, $my_date2),
		   			'ids'=>$this->Micu_census_model->count_refs("IDS", $my_date1, $my_date2), 
		   			'gi'=>$this->Micu_census_model->count_refs("GI", $my_date1, $my_date2),
		   			'rheuma'=>$this->Micu_census_model->count_refs("Rheuma", $my_date1, $my_date2),
		  			'onco'=>$this->Micu_census_model->count_refs("Onco", $my_date1, $my_date2),
		   			'pulmo'=>$this->Micu_census_model->count_refs("Pulmo", $my_date1, $my_date2),
                   			'micu'=>$this->Micu_census_model->count_refs("MICU", $my_date1, $my_date2),
				);	
              $data['erefs'] = array(
					'dnet'=>$this->Micu_census_model->count_erefs("DNET", $my_date1, $my_date2),
                   			'dect'=>$this->Micu_census_model->count_erefs("DECT", $my_date1, $my_date2),
                   			'dietary'=>$this->Micu_census_model->count_erefs("Dietary", $my_date1, $my_date2),
		   			'gs'=>$this->Micu_census_model->count_erefs("GS", $my_date1, $my_date2),
		   			'uro'=>$this->Micu_census_model->count_erefs("Uro", $my_date1, $my_date2),
		   			'neuro'=>$this->Micu_census_model->count_erefs("Neuro", $my_date1, $my_date2),
		   			'nss'=>$this->Micu_census_model->count_erefs("NSS", $my_date1, $my_date2),
		   			'ortho'=>$this->Micu_census_model->count_erefs("Ortho", $my_date1, $my_date2),
		   			'plastic'=>$this->Micu_census_model->count_erefs("Plastic", $my_date1, $my_date2),
		   			'tcvs'=>$this->Micu_census_model->count_erefs("TCVS", $my_date1, $my_date2),
		   			'trauma'=>$this->Micu_census_model->count_erefs("Trauma", $my_date1, $my_date2),
		   			'orl'=>$this->Micu_census_model->count_erefs("ORL", $my_date1, $my_date2),
		   			'ophtha'=>$this->Micu_census_model->count_erefs("Ophtha", $my_date1, $my_date2),
		   			'psych'=>$this->Micu_census_model->count_erefs("Psych", $my_date1, $my_date2),
		   			'ob-gyn'=>$this->Micu_census_model->count_erefs("Ob-Gyn", $my_date1, $my_date2),
		   			'radio'=>$this->Micu_census_model->count_erefs("Radio", $my_date1, $my_date2),
		   			'tox'=>$this->Micu_census_model->count_erefs("Tox", $my_date1, $my_date2),
		   			'pedia'=>$this->Micu_census_model->count_erefs("Pedia", $my_date1, $my_date2),
		   			'fm'=>$this->Micu_census_model->count_erefs("FM", $my_date1, $my_date2),
              		'rehab'=>$this->Er_census_model->count_erefs("Rehab", $my_date1, $my_date2),
				);

	      $data['c_admissions'] = $this->Micu_census_model->micu_report($my_service, $my_date1, $my_date2);
       }       
       else
       {
	      $data['refs'] = array(
					'allergy'=>$this->Admission_model->count_refs($my_service, "Allergy", $my_date1, $my_date2),
		   			'cardio'=>$this->Admission_model->count_refs($my_service, "Cardio", $my_date1, $my_date2),
		   			'nephro'=>$this->Admission_model->count_refs($my_service, "Nephro", $my_date1, $my_date2),
                   			'endo'=>$this->Admission_model->count_refs($my_service, "Endo", $my_date1, $my_date2),
		   			'derma'=>$this->Admission_model->count_refs($my_service, "Derma", $my_date1, $my_date2),
		   			'hema'=>$this->Admission_model->count_refs($my_service, "Hema", $my_date1, $my_date2),
		   			'htn'=>$this->Admission_model->count_refs($my_service, "HTN", $my_date1, $my_date2),
		   			'ids'=>$this->Admission_model->count_refs($my_service, "IDS", $my_date1, $my_date2), 
		   			'gi'=>$this->Admission_model->count_refs($my_service, "GI", $my_date1, $my_date2),
		   			'rheuma'=>$this->Admission_model->count_refs($my_service, "Rheuma", $my_date1, $my_date2),
		  			'onco'=>$this->Admission_model->count_refs($my_service, "Onco", $my_date1, $my_date2),
		   			'pulmo'=>$this->Admission_model->count_refs($my_service, "Pulmo", $my_date1, $my_date2),
                   			'micu'=>$this->Admission_model->count_refs($my_service, "MICU", $my_date1, $my_date2),
				);	


              $data['erefs'] = array(
					'dnet'=>$this->Admission_model->count_erefs($my_service, "DNET", $my_date1, $my_date2),
                   			'dect'=>$this->Admission_model->count_erefs($my_service, "DECT", $my_date1, $my_date2),
                   			'dietary'=>$this->Admission_model->count_erefs($my_service, "Dietary", $my_date1, $my_date2),
		   			'gs'=>$this->Admission_model->count_erefs($my_service, "GS", $my_date1, $my_date2),
		   			'uro'=>$this->Admission_model->count_erefs($my_service, "Uro", $my_date1, $my_date2),
		   			'neuro'=>$this->Admission_model->count_erefs($my_service, "Neuro", $my_date1, $my_date2),
		   			'nss'=>$this->Admission_model->count_erefs($my_service, "NSS", $my_date1, $my_date2),
		   			'ortho'=>$this->Admission_model->count_erefs($my_service, "Ortho", $my_date1, $my_date2),
		   			'plastic'=>$this->Admission_model->count_erefs($my_service, "Plastic", $my_date1, $my_date2),
		   			'tcvs'=>$this->Admission_model->count_erefs($my_service, "TCVS", $my_date1, $my_date2),
		   			'trauma'=>$this->Admission_model->count_erefs($my_service, "Trauma", $my_date1, $my_date2),
		   			'orl'=>$this->Admission_model->count_erefs($my_service, "ORL", $my_date1, $my_date2),
		   			'ophtha'=>$this->Admission_model->count_erefs($my_service, "Ophtha", $my_date1, $my_date2),
		   			'psych'=>$this->Admission_model->count_erefs($my_service, "Psych", $my_date1, $my_date2),
		   			'ob-gyn'=>$this->Admission_model->count_erefs($my_service, "Ob-Gyn", $my_date1, $my_date2),
		   			'radio'=>$this->Admission_model->count_erefs($my_service, "Radio", $my_date1, $my_date2),
		   			'tox'=>$this->Admission_model->count_erefs($my_service, "Tox", $my_date1, $my_date2),
		   			'pedia'=>$this->Admission_model->count_erefs($my_service, "Pedia", $my_date1, $my_date2),
		   			'fm'=>$this->Admission_model->count_erefs($my_service, "FM", $my_date1, $my_date2),	
              		'rehab'=>$this->Er_census_model->count_erefs("Rehab", $my_date1, $my_date2),
				);
              $data['c_admissions'] = $this->Admission_model->gm_report($my_service, $my_date1, $my_date2, "All");
              $data['admission_types'] = $this->Admission_model->gm_report($my_service, $my_date1, $my_date2, "Types");
       }
       $this->load->view('list/show_admissions', $data); 
} 	




//end of traced functions

	  //By Resident
          //Get resident census
	  function resident_census(){
	       $my_service = $this->input->post('my_service', TRUE);
		   $my_dispo = $this->input->post('my_dispo', TRUE);
		   $one_gm = $this->input->post('one_gm', TRUE);
		   $stp1 = $this->input->post('stp1', TRUE);
		   $census = array( 
		    	  		   'my_service'=>$my_service,
						   'my_dispo'=>$my_dispo,
						   'one_gm'=>$one_gm,
						   'stp1'=>$stp1
		                );
		  $r_id = $this->input->post('eresident'); 
		  $rname = $this->input->post('rname'); 
		  $census['eresident'] = $r_id;
		  $census['rname'] = $rname;
		  $this->session->set_userdata($census);	
				//wards
		       $data['sr_wadmissions'] = $this->Admission_model->get_sresidents_adm($r_id, "All");
		       $data['jr_wadmissions'] = $this->Admission_model->get_jresidents_adm($r_id, "All");
		       $data['w_admissions'] = array_merge($data['sr_wadmissions'], $data['jr_wadmissions']);
			    //micu
			   $data['sr_madmissions'] = $this->Micu_census_model->get_sresidents_adm($r_id);
		       $data['jr_madmissions'] = $this->Micu_census_model->get_jresidents_adm($r_id);
			   $data['m_admissions'] = array_merge($data['sr_madmissions'], $data['jr_madmissions']);		
				//er
			   $data['e_admissions'] = $this->Er_census_model->get_erresidents_adm($r_id);		
			   //co-mx
			   $data['sr_cmadmissions'] = $this->Admission_model->get_sresidents_adm($r_id, "comx");
		       $data['jr_cmadmissions'] = $this->Admission_model->get_jresidents_adm($r_id, "comx");
		       $data['cm_admissions'] = array_merge($data['sr_cmadmissions'], $data['jr_cmadmissions']);
			   //preop
			   $data['sr_padmissions'] = $this->Admission_model->get_sresidents_adm($r_id, "preop");
		       $data['jr_padmissions'] = $this->Admission_model->get_jresidents_adm($r_id, "preop");
		       $data['p_admissions'] = array_merge($data['sr_padmissions'], $data['jr_padmissions']);
			   
		       $this->load->view('list/show_res_admissions', $data);	   
		}  
		  

	  //By Patients
          //Get ward admissions
	  function get_patient_admissions(){
	       $my_service = $this->input->post('my_service', TRUE);
		   $my_dispo = $this->input->post('my_dispo', TRUE);
		   $one_gm = $this->input->post('one_gm', TRUE);
		   $stp1 = $this->input->post('stp1', TRUE);
		   $census = array( 
		    	  		   'my_service'=>$my_service,
						   'my_dispo'=>$my_dispo,
						   'one_gm'=>$one_gm,
						   'stp1'=>$stp1
		                );
		  $p_id = $this->input->post('epatient'); 
		  $pname = $this->input->post('pname'); 
		  $census['epatient'] = $p_id;
		  $census['pname'] = $pname;
		  $this->session->set_userdata($census);
		  
	      if (!strcmp($my_service, "All"))
	            $data['c_admissions'] = $this->Admission_model->get_patients_adm($p_id);
		  if (!strcmp($my_service, "er"))
                $data['c_admissions'] = $this->Er_census_model->get_patients_adm($p_id);
          if (!strcmp($my_service, "micu"))
                $data['c_admissions'] = $this->Micu_census_model->get_patients_adm($p_id);         
		  $this->load->view('list/show_admissions', $data);   

	  }	  	

	  //delete an admission
	  function delete_admission(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $pid = $this->input->post('p_id');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
		  $now = now();
	      if (!strcmp($my_service, 'er'))
          {
		       
               $this->Er_census_model->insert_one_admission_bu($aid);
		       $data['query'] = $this->Er_census_model->delete_one_admission($aid);
          }
          elseif (!strcmp($my_service, 'micu'))
          {
               $this->Micu_census_model->insert_one_admission_bu($aid);
		       $data['query'] = $this->Micu_census_model->delete_one_admission($aid);
          }
          else
          {
		       $this->Admission_model->insert_one_admission_bu($aid);
		       $data['query'] = $this->Admission_model->delete_one_admission($aid);
          }
		  $this->Patient_model->update_adm_status($pid, "Not Admitted");
     	  $this->load->view('success/deleted_admission', $data); 
	       			    
	  } 	
	  //create printable census of admissions
	   function print_gmcensus(){
	      $my_service = $this->input->post('my_service', TRUE);
		  $my_dispo = $this->input->post('my_dispo', TRUE);
		  $one_gm = $this->input->post('one_gm', TRUE);
                  
		  $census = array( 
		    	  		   'my_service'=>$my_service,
					   'my_dispo'=>$my_dispo,
		    			   'one_gm'=>$one_gm,
					    
						   );
		  $this->session->set_userdata($census);
                  if (!strcmp($my_service, 'micu'))
                       $data['p_admission'] = $this->Micu_census_model->get_micu_census($my_dispo);   
                  else{
		       $data['p_admission'] = $this->Admission_model->get_print_census($my_service, $my_dispo);   
		       $data['p_micu'] = $this->Micu_census_model->get_micugm_census($my_service);
		       $data['p_er'] = $this->Er_census_model->get_ergm_census($my_service);
		  }
		  $this->load->view('list/show_print_census', $data); 
	     
	  } 	
	    	
	 //create ICD-PCP report
	 function count_pcp(){
		foreach ($_POST as $k=>$v){
			if (in_array($k, array('my_dateg', 'my_datei', 'my_datek'))) 	
				$my_date1 = $v;
			if (in_array($k, array('my_dateh', 'my_datej', 'my_datel'))) 
				$my_date2 = $v;				
		}
		
		 $data = array( 
  	  		               
                           'my_date1'=>$my_date1,
                           'my_date2'=>$my_date2,
						   
		   );	
		   
	     
         $data['area'] = $this->input->post('area', TRUE);
		 $this->session->set_userdata($data);	
		 $this->load->view('list/show_pcpcount', $data);
	 
	 }
	 function occupy(){
		$data['er_occupied'] = $this->Er_census_model->get_er_census("Admitted");
		$data['micu_occupied'] = $this->Micu_census_model->get_occupymicu();	
   		$data['w3_occupied'] = $this->Admission_model->get_occupyw3();	
		$data['w1_occupied'] = $this->Admission_model->get_occupyw1();	
         	$this->load->view('list/show_occupy', $data);
	 }
	 function area_reports(){

	        $this->load->view('list/show_area_reports', $data);
	 }

/*
situations where date_out is edited:
1. list admissions by residents
*/
function edit_date_out(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $r_id = $this->input->post('eresident');
		  if (!strcmp($one_gm, "res")){    
       	        $r_id = $this->input->post('eresident');
		        $rname = $this->input->post('rname');
                $data['rname'] = $rname;
                $data['eresident'] = $r_id;
          }

    $my_date1 = $this->input->post('my_date1', TRUE);
    $my_date2 = $this->input->post('my_date2', TRUE);
    $date_out = $this->input->post('date_out', TRUE);
	$dispo = $this->input->post('dispo', TRUE);
	$plist = $this->input->post('plist', TRUE);
    $view_type = $this->input->post('view_type', TRUE);
	$p_id = $this->input->post('epatient', TRUE);
	$one_gm = $this->input->post('one_gm', TRUE);
	$num = $this->input->post('num', TRUE);
	$samp = "date_out".$num;
	foreach ($_POST as $k=>$v){
		if (!strcmp($k, $samp))
			$date_out = $v;
	}
	$census = array( 
		    	  		   'my_service'=>$my_service,
					       'my_dispo'=>'All',
                          'my_date1'=>$my_date1,
                          'my_date2'=>$my_date2,
                       'view_type'=>$view_type,
					   'r_id'=>$r_id,
					   'rname'=>$this->input->post('rname', TRUE),				
						'one_gm'=>$one_gm,
						'p_id'=>$p_id,		
	  			   );
	$this->session->set_userdata($census);	
	if (!strcmp($my_service, 'er'))
	    $this->Er_census_model->edit_dispo_date($aid, $date_out, $dispo, $plist);	
	elseif (!strcmp($my_service, 'micu'))
		$this->Micu_census_model->edit_dispo_date($aid, $date_out, $dispo, $plist);
	else
		$this->Admission_model->edit_dispo_date($aid, $date_out, $dispo, $plist);
	    
	$this->Patient_model->update_adm_status($p_id, $dispo);	
	if (!strcmp($one_gm, 'res')){
            //ward
	 		$data['sr_wadmissions'] = $this->Admission_model->get_sresidents_adm($r_id, $my_service);
		    $data['jr_wadmissions'] = $this->Admission_model->get_jresidents_adm($r_id, $my_service);
		    $data['w_admissions'] = array_merge($data['sr_wadmissions'], $data['jr_wadmissions']);
   
		    //micu
            $data['sr_madmissions'] = $this->Micu_census_model->get_sresidents_adm($r_id);
		    $data['jr_madmissions'] = $this->Micu_census_model->get_jresidents_adm($r_id);
			$data['m_admissions'] = array_merge($data['sr_madmissions'], $data['jr_madmissions']);			
			//er
			$data['e_admissions'] = $this->Er_census_model->get_erresidents_adm($r_id);					
            //comx
            $data['sr_cmadmissions'] = $this->Admission_model->get_sresidents_adm($r_id, "comx");
            $data['jr_cmadmissions'] = $this->Admission_model->get_jresidents_adm($r_id, "comx");
            $data['cm_admissions'] = array_merge($data['sr_cmadmissions'], $data['jr_cmadmissions']);		
			//preop
			$data['sr_padmissions'] = $this->Admission_model->get_sresidents_adm($r_id, "preop");
		    $data['jr_padmissions'] = $this->Admission_model->get_jresidents_adm($r_id, "preop");
		    $data['p_admissions'] = array_merge($data['sr_padmissions'], $data['jr_padmissions']);
						
			
			$this->load->view('list/show_res_admissions', $data);	
	 }
     else{
	 	  if (!strcmp($my_service, "All"))
	            $data['c_admissions'] = $this->Admission_model->get_patients_adm($p_id);
		  if (!strcmp($my_service, "er"))
                $data['c_admissions'] = $this->Er_census_model->get_patients_adm($p_id);
          if (!strcmp($my_service, "micu"))
                $data['c_admissions'] = $this->Micu_census_model->get_patients_adm($p_id);     
		   $this->load->view('list/show_admissions', $data); 
	 }	 
 
   
} 

}


