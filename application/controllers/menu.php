<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Menu extends CI_Controller {

	  public function index(){
	         $my_service = $this->input->post('my_service');
		     $my_dispo = $this->input->post('my_dispo');
		     $one_gm = $this->input->post('one_gm');
             $stp1 = $this->input->post('stp1');
		  
		     $census = array( 
		    	  		        'my_service'=>$my_service,
					            'my_dispo'=>$my_dispo,
					            'one_gm'=>$one_gm,
                                'stp1'=>$stp1
						   );
		     $this->session->set_userdata($census);	
			//Add admission 
	  		 if (!empty($_POST['main_add_a']))
			     $this->load->view('query/search_p');
			//Manage residents	 	    
			 if (!empty($_POST['main_showr']))
	  		     $this->load->view('query/manage_r');
			//Manage patients	 
			 if (!empty($_POST['main_showp']))
	  		     $this->load->view('query/search_p');  	 
			//Manage admissions	 
			 if (!empty($_POST['main_showa']))
			     $this->load->view('query/manage_a'); 
			//Add new patient	 
			 if (!empty($_POST['main_new_p']))
			     $this->load->view('insert/add_new_patient');	
            //Subspec 
             if (!empty($_POST['main_subspec']))
             	 $this->load->view('query/subspec');			 
	  }

function get_like_resident(){
	$my_service = $this->input->get('my_service', TRUE);
    $my_dispo = $this->input->get('my_dispo', TRUE);
    $one_gm = $this->input->get('one_gm', TRUE);
    $stp1 = $this->input->get('stp1', TRUE); 
    $clue = $this->input->get('clue', TRUE);
	$census = array( 
	   	  		   'my_service'=>$my_service,
				   'my_dispo'=>$my_dispo,
				   'one_gm'=>$one_gm,
				   'stp1'=>$stp1,
		   );
	$this->session->set_userdata($census);
    $data['resident'] = $this->Resident_model->get_like_resident($clue);
    $data['clue'] = $clue;
    $this->load->view('list/show_residents', $data);
	  }

function get_like_patient(){
	$my_service = $this->input->get('my_service', TRUE);
    $my_dispo = $this->input->get('my_dispo', TRUE);
    $one_gm = $this->input->get('one_gm', TRUE);
    $stp1 = $this->input->get('stp1', TRUE); 
    $clue = $this->input->get('clue', TRUE);
	$census = array( 
	   	  		   'my_service'=>$my_service,
				   'my_dispo'=>$my_dispo,
				   'one_gm'=>$one_gm,
				   'stp1'=>$stp1,
		   );
	$this->session->set_userdata($census);
    $data['casenums'] = $this->Patient_model->get_like_cnum($clue);
    $data['pnames'] = $this->Patient_model->get_like_pname($clue);
    $data['patient'] = array_merge($data['casenums'], $data['pnames']);
    $data['clue'] = $clue;
    if (strcmp($stp1, 'stp1'))
        $this->load->view('list/show_patients', $data);
    else    
        $this->load->view('list/like_patients', $data);


	  }

}

