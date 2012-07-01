<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resident_model extends CI_Model {
      
	  var $r_name="";
	  var $dstart="";
	  var $status="";

      function __construct() {
          parent::__construct();
      }
      
      //clean rname
      function clean_res($rid, $rname, $dstart) {
      	    $data = array('r_name'=>clean_form_input($rname), 'dstart'=>clean_form_input($dstart));
    		$this->db->where('r_id', $rid);
    		$this->db->update('residents', $data);
      	;
      }
	  //select resident name
	  function get_resident_name($id){
	          $this->db->select('r_name');
		  $this->db->where('r_id', $id);
		  $query = $this->db->get('residents');
		  return $query->result(); 
	  }
	  function get_allr(){
	      
		  $this->db->order_by('status desc, r_name asc, dstart desc');
		  $query = $this->db->get('residents'); 
          return $query->result(); 
	  }
	  //select active residents list
	  function get_activer(){
	      $this->db->where('status', 'Y');
		  $this->db->order_by('r_name asc, dstart desc');
		  $query = $this->db->get('residents'); 
          return $query->result(); 
	  }
	  //get one resident's data
      	  function get_one_resident($id){
                  $this->db->where('r_id', $id);
		  $query = $this->db->get('residents');
		  return $query->result(); 							  
	  }
	  //for searching residents by name
	  function get_like_resident($rname){
		  $this->db->like('r_name', $rname);
		  $query = $this->db->get('residents');
		  return $query->result();    
	  }
	  
	  //edit
	  //update single resident data
	  function edit_one_resident($id, $num){
	      $samp = "dstart".$num;
		  foreach($_POST as $k=>$v){			
			if (!strcmp($k, $samp))
				$this->dstart = clean_form_input($v);
		 }	
	      $this->r_name = clean_form_input($this->input->post('rname', TRUE));
		  //$this->dstart = clean_form_input($this->input->post('dstart', TRUE));
		  $this->status = clean_form_input($this->input->post('status', TRUE)); 
		  $this->db->where('r_id', $id);
		  $query = $this->db->update('residents',$this);  							  
	  }
	  //insert new resident
	  function insert_one_resident($resident){  
	      
		  $query = $this->db->insert('residents', $resident);						  
	  }
	  
}
