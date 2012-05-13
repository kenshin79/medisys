<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Show extends CI_Controller {
      
     
	  public function index(){
	
	  }
	  //residents
	  //list all residents
	  function show_residents(){	
		  $data['criteria'] = "All";
		  $data['resident'] = $this->Resident_model->get_allr(); 
	      $this->load->view('list/show_residents', $data);   	     
	  }
	  //search for residents
      function find_resident(){
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
		   $this->session->set_userdata($census);	
	       $rname = $this->input->post('rname', TRUE);
	       $data['rname'] = $rname;
		   $data['criteria'] = "Not";
		   $data['resident'] = $this->Resident_model->get_like_resident($rname);
	       $this->load->view('list/show_residents', $data); 
	  }   
	  //update single resident data
	  function edit_resident(){
		   $id = $this->input->post('eresident', TRUE);
		   $this->Resident_model->edit_one_resident($id);
		   $data['resident'] = $this->Resident_model->get_one_resident($id);
		   $this->load->view('success/new_rform', $data);   	
	  }
	  
	  
	  //patients
	  //List All patients
      function show_patients(){
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
		  $this->session->set_userdata($census);	
		  $data['patient'] = $this->Patient_model->get_allp();
		  $data['criteria'] = "All";				   
		  $this->load->view('list/show_patients',$data); 
	           
	  }
	  function show_patients_step1(){
		  $data['patient'] = $this->Patient_model->get_allp();
		  $data['criteria'] = "All";				   
		  $this->load->view('show/show_patients_header_step2',$data); 
	      $this->load->view('show/show_patients_body', $data);   	     
	  }
	  //update patient data
	  function edit_patient(){
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
		    $this->session->set_userdata($census);	
	        $id = $this->input->post('epatient', TRUE);
		    $this->Patient_model->edit_one_patient($id);
		    $data['patient'] = $this->Patient_model->get_one_patient($id);
		    $this->load->view('success/new_pform', $data);
	  }
	  //find patient by name	   
	  function find_pname(){
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
		   $this->session->set_userdata($census);	
	       $pname = $this->input->post('pname', TRUE);
		   $data['patient'] = $this->Patient_model->get_like_pname($pname);
		   $data['pname'] = $pname;
		   $data['criteria'] = "Not";
		   $this->load->view('list/show_patients', $data); 
	       
	  }
	  
	  //find patient by case number
	  function find_pcnum(){
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
	  	   $cnum = $this->input->post('cnum', TRUE);
		   $data['patient'] = $this->Patient_model->get_like_cnum($cnum);
		   $data['pname'] = $cnum;
		   $data['criteria'] = "Not";
	       $this->load->view('list/show_patients', $data); 	   
	  }
	  
	  
	  //admission
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


	  
	  //create admission form for patient
	  function create_admission_form(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');
  	      $epatient = $this->input->post('epatient', TRUE);
	      $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
					        'epatient'=>$epatient,
						   );
		  $this->session->set_userdata($census);	
	      $data['patients'] = $this->Patient_model->get_one_patient($epatient);
	      $data['residents'] = $this->Resident_model->get_activer();
          $this->load->view('insert/add_admission', $data);   
	  }
	 
	  //update admission data
	  function edit_admission(){  
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');
		  $census = array( 
		    	  		        'my_service'=>$my_service,
					            'my_dispo'=>$my_dispo,
				                'one_gm'=>$one_gm,
				                'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
	      $p_id = $this->input->post('p_id', TRUE);
	      $aid = $this->input->post('eadmission', TRUE);
          //update plist of patient
          $this->Patient_model->update_one_plist($p_id, $this->input->post('plist', TRUE));
		  
          if (!strcmp($my_service, 'er')){
                      $this->Er_census_model->edit_one_admission($aid);
                      $data['c_admissions'] = $this->Er_census_model->get_er_census($my_dispo);
          }
          elseif (!strcmp($my_service, 'micu')){
                      $this->Micu_census_model->edit_one_admission($aid);
                      $data['c_admissions'] = $this->Micu_census_model->get_micu_census($my_dispo);
          }
          else{   
		              $this->Admission_model->edit_one_admission($aid);
		              $data['c_admissions'] = $this->Admission_model->get_gm_census($my_service, $my_dispo); 
		              $data['c_micu'] = $this->Micu_census_model->get_micugm_census($my_service);
			          $data['c_er'] = $this->Er_census_model->get_ergm_census($my_service);
          }
		  $this->load->view('list/show_onegmadmissions', $data); 
	  }
	  //show ICD/PCP edit form
	  function edit_pcpdx_form(){
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
                       $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 7);
          elseif (!strcmp($my_service, 'micu'))
                       $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 7);
          else
        		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 7);
          $this->load->view('list/show_pcpdx', $data);
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
		  if (!strcmp($one_gm, 'y')){
                  	if (!strcmp($my_service, 'er'))
                      		$data['c_admissions'] = $this->Er_census_model->get_er_census($my_dispo); 
                  	elseif (!strcmp($my_service, 'micu'))
                       		$data['c_admissions'] = $this->Micu_census_model->get_micu_census($my_dispo);
                  	else{
		        	        $data['c_admissions'] = $this->Admission_model->get_gm_census($my_service, $my_dispo); 
                            $data['c_micu'] = $this->Micu_census_model->get_micugm_census($my_service);
			                $data['c_er'] = $this->Er_census_model->get_ergm_census($my_service);  
			        }
			        $this->load->view('list/show_onegmadmissions', $data);        
	      }   
		  elseif (!strcmp($one_gm, "res")){
			if (!strcmp($my_service, 'er'))
				$data['c_admissions'] = $this->Er_census_model->get_erresidents_adm($r_id);
			elseif (!strcmp($my_service, 'micu')){
				$data['sr_admissions'] = $this->Micu_census_model->get_sresidents_adm($r_id);
		  		$data['jr_admissions'] = $this->Micu_census_model->get_jresidents_adm($r_id);
		  		$data['c_admissions'] = array_merge($data['sr_admissions'], $data['jr_admissions']);
			}
			else{
				$data['sr_admissions'] = $this->Admission_model->get_sresidents_adm($r_id);
		  		$data['jr_admissions'] = $this->Admission_model->get_jresidents_adm($r_id);
		  		$data['c_admissions'] = array_merge($data['sr_admissions'], $data['jr_admissions']);
	      	}
	      	$this->load->view('list/show_admissions', $data);
		 }
          
      }
	  //show edit notes
	  function edit_cnotes(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');		  
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						  );
		  $this->session->set_userdata($census);	
		  $aid = $this->input->post('eadmission', TRUE);
          if (!strcmp($my_service, 'er'))
                $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 0);
          elseif (!strcmp($my_service, 'micu'))
                $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 0);
          else
		        $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 0);
	      $this->load->view('list/clinical_notes', $data);
	  }
	  //show abstract edit form
	  function edit_abstract(){
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
		  $aid = $this->input->post('eadmission', TRUE);
          if (!strcmp($my_service, 'er'))
                  $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 1);
          elseif (!strcmp($my_service, 'micu'))
                  $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 1);
          else
		          $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 1);
	      $this->load->view('list/abstract_form', $data);
	  }
          //show d-summary edit form
	  function edit_dsummary(){
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
		  $aid = $this->input->post('eadmission', TRUE);
          if (!strcmp($my_service, 'er'))
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 2);
          elseif (!strcmp($my_service, 'micu'))
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 2);
          else
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 2);
	      $this->load->view('list/d_summary', $data);
	  } 
          //show sagip buhay form
	  function edit_sagipbuhay(){
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
		  $aid = $this->input->post('eadmission', TRUE);
          if (!strcmp($my_service, 'er'))
                $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 4 );
          elseif (!strcmp($my_service, 'micu'))
                $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 4);
          else
		        $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 4);
	      $this->load->view('list/sagip_request', $data);
	  } 
          //show home meds form
	  function edit_home(){
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
		  $aid = $this->input->post('eadmission', TRUE);
          if (!strcmp($my_service, 'er'))
                $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 3);
          elseif (!strcmp($my_service, 'micu'))
                $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 3);
          else
		        $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 3);
	      $this->load->view('list/home_meds', $data);
	  } 
	  //update abstract form
	  function update_abstract(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
          /*
		  if (!strcmp($one_gm, "res")){    
       	        $r_id = $this->input->post('eresident');
		        $rname = $this->input->post('rname');
                $data['rname'] = $rname;
                $data['eresident'] = $r_id;
          }
          
          if (!strcmp($one_gm, "px")){    
       	        $p_id = $this->input->post('epatient');
		        $pname = $this->input->post('pname');
                $data['pname'] = $pname;
                $data['epatient'] = $p_id;
          }
          */
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
   		       $this->Er_census_model->edit_abstract($aid);
          elseif (!strcmp($my_service, 'micu'))
   		       $this->Micu_census_model->edit_abstract($aid);
          else
		       $this->Admission_model->edit_abstract($aid);
		  if (!strcmp($one_gm, 'y')){
                  	if (!strcmp($my_service, 'er'))
                      		$data['c_admissions'] = $this->Er_census_model->get_er_census($my_dispo); 
                  	elseif (!strcmp($my_service, 'micu'))
                       		$data['c_admissions'] = $this->Micu_census_model->get_micu_census($my_dispo);
                  	else{
		        	        $data['c_admissions'] = $this->Admission_model->get_gm_census($my_service, $my_dispo); 
                            $data['c_micu'] = $this->Micu_census_model->get_micugm_census($my_service);
			                $data['c_er'] = $this->Er_census_model->get_ergm_census($my_service);  
			        }
			        $this->load->view('list/show_onegmadmissions', $data);        
	      }   
		  elseif (!strcmp($one_gm, "res")){

			if (!strcmp($my_service, 'er'))
				$data['c_admissions'] = $this->Er_census_model->get_erresidents_adm($r_id);
			elseif (!strcmp($my_service, 'micu')){
				$data['sr_admissions'] = $this->Micu_census_model->get_sresidents_adm($r_id);
		  		$data['jr_admissions'] = $this->Micu_census_model->get_jresidents_adm($r_id);
		  		$data['c_admissions'] = array_merge($data['sr_admissions'], $data['jr_admissions']);
			}
			else{
				$data['sr_admissions'] = $this->Admission_model->get_sresidents_adm($r_id);
		  		$data['jr_admissions'] = $this->Admission_model->get_jresidents_adm($r_id);
		  		$data['c_admissions'] = array_merge($data['sr_admissions'], $data['jr_admissions']);
	      	}
	      	$this->load->view('list/show_admissions', $data);
	  }
	}  
	  //update cnotes
	  
	  function update_cnotes(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
						   );
		  $this->session->set_userdata($census);	
		  $aid = $this->input->post('eadmission', TRUE);
          if (!strcmp($my_service, 'er')){
   		                $this->Er_census_model->edit_cnotes($aid);
                        $data['c_admissions'] = $this->Er_census_model->get_er_census($my_dispo);
           }
           elseif (!strcmp($my_service, 'micu')){
   		                $this->Micu_census_model->edit_cnotes($aid);
                        $data['c_admissions'] = $this->Micu_census_model->get_micu_census($my_dispo);
           }
           else{
		       $this->Admission_model->edit_cnotes($aid);
               $data['c_admissions'] = $this->Admission_model->get_gm_census($my_service, $my_dispo); 
		       $data['c_micu'] = $this->Micu_census_model->get_micugm_census($my_service);
			   $data['c_er'] = $this->Er_census_model->get_ergm_census($my_service);
		   }
 		   $this->load->view('list/show_onegmadmissions', $data); 
	  }	

          //update d-summary form
	  function update_dsummary(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er')){
   		       $this->Er_census_model->edit_dsummary($aid);
               $data['c_admissions'] = $this->Er_census_model->get_er_census($my_dispo);
          }
          elseif (!strcmp($my_service, 'micu')){
   		       $this->Micu_census_model->edit_dsummary($aid);
               $data['c_admissions'] = $this->Micu_census_model->get_micu_census($my_dispo);
          }
          else{
		       $this->Admission_model->edit_dsummary($aid);
               $data['c_admissions'] = $this->Admission_model->get_gm_census($my_service, $my_dispo);
		       $data['c_micu'] = $this->Micu_census_model->get_micugm_census($my_service);
			   $data['c_er'] = $this->Er_census_model->get_ergm_census($my_service); 
          }
		  $this->load->view('list/show_onegmadmissions', $data); 
	  }
          //update sagip form
	  function update_sagip(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er')){
   		       $this->Er_census_model->edit_sagip($aid);
               $data['c_admissions'] = $this->Er_census_model->get_er_census($my_dispo);
          }
          elseif (!strcmp($my_service, 'micu')){
   		       $this->Micu_census_model->edit_sagip($aid);
               $data['c_admissions'] = $this->Micu_census_model->get_micu_census($my_dispo);
          }
          else{
		       $this->Admission_model->edit_sagip($aid);
               $data['c_admissions'] = $this->Admission_model->get_gm_census($my_service, $my_dispo); 
		       $data['c_micu'] = $this->Micu_census_model->get_micugm_census($my_service);
			   $data['c_er'] = $this->Er_census_model->get_ergm_census($my_service);
          }
		  $this->load->view('list/show_onegmadmissions', $data); 
	  }
	  //update home form
	  function update_home(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er')){
   		       $this->Er_census_model->edit_home($aid);
               $data['c_admissions'] = $this->Er_census_model->get_er_census($my_dispo);
          }
          elseif (!strcmp($my_service, 'micu')){
   		       $this->Micu_census_model->edit_home($aid);
               $data['c_admissions'] = $this->Micu_census_model->get_micu_census($my_dispo);
          }
          else{
		       $this->Admission_model->edit_home($aid);
               $data['c_admissions'] = $this->Admission_model->get_gm_census($my_service, $my_dispo); 
		       $data['c_micu'] = $this->Micu_census_model->get_micugm_census($my_service);
			   $data['c_er'] = $this->Er_census_model->get_ergm_census($my_service);
		  }
		  $this->load->view('list/show_onegmadmissions', $data); 
	  } 
	  //show lab form
	  function lab_forms(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
              $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 5);
          elseif (!strcmp($my_service, 'micu'))
              $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 5);
          else
		      $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 5);            
	      $this->load->view('list/show_labform', $data);
	  }
	  //show lab form #2
	  function lab_forms2(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
              $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 6);
          elseif (!strcmp($my_service, 'micu'))
              $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 6);
          else
		      $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 6);            
	      $this->load->view('list/show_labform2', $data);
	  }

	  //update cbc flow form
	  function update_cbc(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_cbc($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 5);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_cbc($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 5);
		  }
          else
		  {
		       $this->Admission_model->edit_cbc($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 5);    
		       
		  }   
		  $this->load->view('list/show_labform', $data); 
	  }
	  //update cbc flow form 2
	  function update_cbc2(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_cbc2($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 6);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_cbc2($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 6);
		  }
          else
		  {
		       $this->Admission_model->edit_cbc2($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 6);    
		  }   
		  $this->load->view('list/show_labform2', $data); 
	  }

          //update blood chem flow form
	  function update_bloodchem(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_bloodchem($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 5);
		  }
          elseif (!strcmp($my_service, 'micu'))

		  {
		       $this->Micu_census_model->edit_bloodchem($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 5);
		  }
          else
		  {
		       $this->Admission_model->edit_bloodchem($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 5);        
		  }   
		  $this->load->view('list/show_labform', $data); 
	  } 

          //update blood chem flow form 2
	  function update_bloodchem2(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_bloodchem2($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 6);
		  }
          elseif (!strcmp($my_service, 'micu'))
     	  {
		       $this->Micu_census_model->edit_bloodchem2($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 6);
		  }
          else
		  {
		       $this->Admission_model->edit_bloodchem2($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 6);        
		  }   
		  $this->load->view('list/show_labform2', $data); 
	  } 
	  //update protime/aptt flow form
	  function update_protime(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_protime($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 5);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_protime($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 5);
		  }
          else
		  {
		       $this->Admission_model->edit_protime($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 5);        
		  }   
		  $this->load->view('list/show_labform', $data); 
	  } 
	  //update protime/aptt flow form 2
	  function update_protime2(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_protime2($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 6);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_protime2($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 6);
		  }
          else
		  {
		       $this->Admission_model->edit_protime2($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 6);        
		  }   
		  $this->load->view('list/show_labform2', $data); 
	  } 

	  //update urine flow form
	  function update_urine(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_urine($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 5);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_urine($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 5);
		  }
          else
		  {
		       $this->Admission_model->edit_urine($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 5);        
		  }   
		  $this->load->view('list/show_labform', $data); 
	  } 

	  //update urine flow form 2
	  function update_urine2(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_urine2($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 6);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_urine2($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 6);
		  }
          else
		  {
		       $this->Admission_model->edit_urine2($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 6);        
		  }   
		  $this->load->view('list/show_labform2', $data); 
	  } 

	  //update abg flow form
	  function update_abg(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_abg($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 5);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_abg($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 5);
		  }
          else
		  {
		       $this->Admission_model->edit_abg($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 5);        
		  }   
		  $this->load->view('list/show_labform', $data); 
	  } 

	  //update abg flow form 2
	  function update_abg2(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_abg2($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 6);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_abg2($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 6);
		  }
          else
		  {
		       $this->Admission_model->edit_abg2($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 6);        
		  }   
		  $this->load->view('list/show_labform2', $data); 
	  } 

	  //update fecalysis flow form
	  function update_fecalysis(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_fecalysis($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 5);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_fecalysis($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 5);
		  }
          else
		  {
		       $this->Admission_model->edit_fecalysis($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 5);        
		  }   
		  $this->load->view('list/show_labform', $data); 
	  } 
	  //update fecalysis flow form 2
	  function update_fecalysis2(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_fecalysis2($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 6);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_fecalysis2($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 6);
		  }
          else
		  {
		       $this->Admission_model->edit_fecalysis2($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 6);        
		  }   
		  $this->load->view('list/show_labform2', $data); 
	  } 

	  //update uchem flow form
	  function update_uchem(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_uchem($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 5);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_uchem($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 5);
		  }
          else
		  {
		       $this->Admission_model->edit_uchem($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 5);        
		  }   
		  $this->load->view('list/show_labform', $data); 
	  } 
	  //update uchem flow form 2
	  function update_uchem2(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_uchem2($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 6);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_uchem2($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 6);
		  }
          else
		  {
		       $this->Admission_model->edit_uchem2($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 6);        
		  }   
		  $this->load->view('list/show_labform2', $data); 
	  } 

	 //update culture flow form
	  function update_culture(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_culture($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 5);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_culture($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 5);
		  }
          else
		  {
		       $this->Admission_model->edit_culture($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 5);        
		  }   
		  $this->load->view('list/show_labform', $data); 
	  } 

	 //update culture flow form 2
	  function update_culture2(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_culture2($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 6);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_culture2($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 6);
		  }
          else
		  {
		       $this->Admission_model->edit_culture2($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 6);        
		  }   
		  $this->load->view('list/show_labform2', $data); 
	  } 

	  //update imaging flow form
	  function update_imaging(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_imaging($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 5);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_imaging($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 5);
		  }
          else
		  {
		       $this->Admission_model->edit_imaging($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 5);        
		  }   
		  $this->load->view('list/show_labform', $data); 
	  } 

	  //update imaging flow form 2
	  function update_imaging2(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_imaging2($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 6);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_imaging2($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 6);
		  }
          else
		  {
		       $this->Admission_model->edit_imaging2($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 6);        
		  }   
		  $this->load->view('list/show_labform2', $data); 
	  } 

	  //update ecg flow form
	  function update_ecg(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_ecg($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 5);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_ecg($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 5);
		  }
          else
		  {
		       $this->Admission_model->edit_ecg($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 5);        
		  }   
		  $this->load->view('list/show_labform', $data); 
	  } 

	  //update ecg flow form 2
	  function update_ecg2(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_ecg2($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 6);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_ecg2($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 6);
		  }
          else
		  {
		       $this->Admission_model->edit_ecg2($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 6);        
		  }   
		  $this->load->view('list/show_labform2', $data); 
	  } 

	  //update others flow form
	  function update_others(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_others($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 5);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_others($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 5);
		  }
          else
		  {
		       $this->Admission_model->edit_others($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 5);        
		  }   
		  $this->load->view('list/show_labform', $data); 
	  } 

	  //update others flow form 2
	  function update_others2(){
	      $my_service = $this->input->post('my_service');
		  $my_dispo = $this->input->post('my_dispo');
		  $one_gm = $this->input->post('one_gm');
		  $stp1 = $this->input->post('stp1');  
		  $aid = $this->input->post('eadmission');
		  $census = array( 
		    	  		    'my_service'=>$my_service,
					        'my_dispo'=>$my_dispo,
					        'one_gm'=>$one_gm,
					        'stp1'=>$stp1,
						   );
		  $this->session->set_userdata($census);	
          if (!strcmp($my_service, 'er'))
          {
		       $this->Er_census_model->edit_others2($aid);
               $data['p_admission'] = $this->Er_census_model->get_forms_label($aid, 6);
		  }
          elseif (!strcmp($my_service, 'micu'))
		  {
		       $this->Micu_census_model->edit_others2($aid);
               $data['p_admission'] = $this->Micu_census_model->get_forms_label($aid, 6);
		  }
          else
		  {
		       $this->Admission_model->edit_others2($aid);
		       $data['p_admission'] = $this->Admission_model->get_forms_label($aid, 6);        
		  }   
		  $this->load->view('list/show_labform2', $data); 
	  } 


}
