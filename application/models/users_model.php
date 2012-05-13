<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {
      
	  var $uname="";
	  var $passwd="";
	  var $svc="";

      function __construct() {
          parent::__construct();
      }
	  
      function get_all_users(){
	  $query = $this->db->get('users'); 
          return $query->result();
      }
      function get_svc($uname){
          $this->db->select('svc');
          $this->db->where('uname', $uname); 
          $query = $this->db->get('users');
          return $query->result();
      }	  
       
	  
	    


}	  
