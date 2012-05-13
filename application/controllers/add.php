<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add extends CI_Controller {
      
      public function index(){
	
	  }
	  //insert new patient
	  function insert_patient(){
	         $my_service = $this->input->post('my_service', TRUE);
		     $my_dispo = $this->input->post('my_dispo', TRUE);
		     $one_gm = $this->input->post('one_gm', TRUE);
		  
		     $census = array( 
		    	  		   'my_service'=>$my_service,
						   'my_dispo'=>$my_dispo,
						   'one_gm'=>$one_gm
						   );
		     $this->session->set_userdata($census);	
	       
		     $data['epatient']= $this->Patient_model->insert_one_patient();
		     $this->load->view('success/new_padded',$data); 
       }
	  
	  //insert new resident
	  function insert_resident(){
	       $rname = clean_form_input($this->input->post('rname', TRUE));
		   $dstart = clean_form_input($this->input->post('dstart', TRUE));
		   $st = clean_form_input($this->input->post('status', TRUE));
	       $resident = array ( 'r_name'=>$rname, 'dstart'=>$dstart, 'status'=>$st);
		   $query = $this->Resident_model->insert_one_resident($resident);
		   $this->load->view('success/new_radded',$resident); 
	   
	  }
	  
	  //insert new admission
	  function insert_admission(){
	         $my_service = $this->input->post('my_service');
		     $my_dispo = $this->input->post('my_dispo');
		     $one_gm = $this->input->post('one_gm');
		     $stp1 = $this->input->post('stp1');
		     $pod_id = $this->input->post('pod_id');
		     $epatient = $this->input->post('epatient'); 
		     $census = array( 
		    	  		           'my_service'=>$my_service,
						           'my_dispo'=>$my_dispo,
						           'one_gm'=>$one_gm,
						           'stp1'=>$stp1,
						   );
		     $this->session->set_userdata($census);
                   if (!strcmp($my_service, 'er'))
                   {
                        $insert_id = $this->Er_census_model->insert_one_admission();
		                $data['admission'] = $this->Er_census_model->get_admission_data($insert_id);
                   }
                   elseif (!strcmp($my_service, 'micu'))
                   {
                        $insert_id = $this->Micu_census_model->insert_one_admission();
		                $data['admission'] = $this->Micu_census_model->get_admission_data($insert_id);
                   } 
                   else
                   {
		                $insert_id = $this->Admission_model->insert_one_admission();
		                $data['admission'] = $this->Admission_model->get_admission_data($insert_id);
		            }
             $this->load->view('success/new_aadded',$data); 
				
	      		   
		  
		  
	   }  
	  
	 
	  
}	  
