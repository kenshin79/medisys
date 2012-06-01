<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Patient_model extends CI_Model {
      
	  var $p_name="";
	  var $p_sex="";
	  var $p_bday="";
	  var $cnum ="";
	  var $p_add="";
	  var $p_plist="";
      var $adm_status="";
      function __construct() {
          parent::__construct();
      }
      //clean 
      function clean_px($pid, $pname, $pbday, $psex, $cnum, $plist, $padd, $admstatus) {
      	$data = array(
      		'p_name'=>clean_form_input($pname),
      		'p_bday'=>clean_form_input($pbday),
      		'p_sex'=>clean_form_input($psex),
      		'cnum'=>clean_form_input($cnum),
      		'p_plist'=>clean_form_input($plist),
      		'p_add'=>clean_form_input($padd),
      		'adm_status'=>clean_form_input($admstatus)
      	);	
      		
      	$this->db->where('p_id', $pid);
      	$this->db->update('patients', $data);
      	;
      }
      
      
	  //select
	  
      
	  //select all patients
	  function get_allp(){
	      
		  $this->db->order_by('p_name asc, cnum asc');
		  $query = $this->db->get('patients'); 
          return $query->result(); 
	  }
	  //select patients by name
	  function get_like_pname($pname){
		  $this->db->like('p_name', $pname);
		  $this->db->order_by('p_name asc, cnum asc');
		  $query = $this->db->get('patients');
		  return $query->result();    
	  }
	  function get_like_cnum($cnum){
		  $this->db->like('cnum', $cnum);
		  $this->db->order_by('p_name asc, cnum asc');
		  $query = $this->db->get('patients');
		  return $query->result();    
	  }
	  //select patient by id
	  function get_one_patient($id){
		  $this->db->where('p_id', $id);
		  $query = $this->db->get('patients');
		  return $query->result(); 							  
	  }
	  //get patient name
	  function get_patient_name($id){
	          $this->db->select('p_name');
		  $this->db->where('p_id', $id);
		  $query = $this->db->get('patients');
		  return $query->result(); 
	  }
	  //update one patient
	  function edit_one_patient($id, $num){
	
		 $this->p_name = clean_form_input($this->input->post('p_name', TRUE));
		 $this->cnum = clean_form_input($this->input->post('cnum', TRUE));
		 $this->p_sex = clean_form_input($this->input->post('p_sex', TRUE));
		 $samp = "p_bday".$num;
		 foreach($_POST as $k=>$v){			
			if (!strcmp($k, $samp))
				$this->p_bday = clean_form_input($v);
		 }		
		 $this->p_add = clean_form_input($this->input->post('p_add', TRUE));
		 $this->p_plist = clean_form_input($this->input->post('p_plist', TRUE));
		 $this->adm_status = clean_form_input($this->input->post('adm_status', TRUE));
		 $this->db->where('p_id', $id);
		 $this->db->update('patients',$this);  							  
	  }
	  
	  //insert new patient
	  function insert_one_patient(){  
	       $this->p_name = clean_form_input($this->input->post('p_name', TRUE));
		   $this->p_sex = clean_form_input($this->input->post('p_sex', TRUE));
		   $this->p_bday = clean_form_input($this->input->post('p_bday', TRUE));
		   $this->cnum = clean_form_input($this->input->post('cnum', TRUE));
		   $this->p_add = clean_form_input($this->input->post('p_add', TRUE));
		   $this->p_plist = clean_form_input($this->input->post('p_plist', TRUE));
		   $this->adm_status = clean_form_input($this->input->post('adm_status', TRUE));
		   $this->db->insert('patients', $this);	
		   return $this->db->insert_id();					  
	  }
	  //update plist of patient after editing admission data
	  function update_one_plist($pid, $plist){
	      $this->db->set('p_plist',$plist);
		  $this->db->where('p_id', $pid);
		  $this->db->update('patients');  							  
	  }
	  //update admission status of patient
	  function update_adm_status($pid, $adm_status){
	      $this->db->set('adm_status',$adm_status);
		  $this->db->where('p_id', $pid);
		  $this->db->update('patients');  		
	  }
}	  
