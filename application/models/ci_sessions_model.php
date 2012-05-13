<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ci_sessions_model extends CI_Model {
      
	  var $session_id="";
	  var $ip_address="";
	  var $user_agent="";
	  var $last_activity="";
	  

      function __construct() {
          parent::__construct();
      }
	  
      function get_sessions(){
	  $query = $this->db->get('ci_sessions'); 
          return $query->result();
	  }
}	  
