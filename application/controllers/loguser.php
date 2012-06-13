<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loguser extends CI_Controller {
      
     
	  function index(){
	  }

	    
	  function log_out(){
			
              $oldsession = $this->Ci_sessions_model->get_sessions();
              delete_cookie('ci_session');
	      $this->session->sess_destroy();
              //log update task in text file
                                  $file = "userlogin_log.txt"; 
                                  $handle = fopen($file, 'a');
                                  date_default_timezone_set('Asia/Hong_Kong');
                                  $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: logged-out \r\n"; 
                                  fwrite($handle, $data);  	
              redirect('/webpage/log_out.htm');
	      
              
	  }
	  
}	  
	      
