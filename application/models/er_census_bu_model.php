<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Er_census_bu_model extends CI_Model {
          var $er_id="";
	  var $date_in="";
	  var $date_out="";
	  var $dispo="";
	  var $p_id="";
          var $pod_id=""; 
	  var $service="";
	  var $plist="";
	  var $meds="";
	  var $refs="";
	  var $erefs="";
	  var $notes="";
	  var $pcpdx="";
          var $abstract="";
          var $dsummary="";
	  var $sagip="";
          var $home="";
	  var $cbc="";
	  var $bloodchem="";
	  var $protime="";
	  var $urine="";
	  var $abg="";
	  var $fecalysis="";
	  var $uchem="";
	  var $culture="";
	  var $imaging="";
	  var $ecg="";
	  var $others="";
	  var $c_notes="";
	  var $cbc2="";
	  var $bloodchem2="";
	  var $protime2="";
	  var $urine2="";
	  var $abg2="";
	  var $fecalysis2="";
	  var $uchem2="";
	  var $culture2="";
	  var $imaging2="";
	  var $ecg2="";
	  var $others2="";
          var $er_bu_array = array(
					$eb_id,
	   				$date_in,
	   				$date_out,
	   				$dispo,
	   				$p_id,
           				$pod_id, 
	   				$service,
	   				$plist,
	   				$meds,
	   				$refs,
	   				$erefs,
	   				$notes,
	   				$pcpdx,
           				$abstract,
           				$dsummary,
	   				$sagip,
           				$home,
	   				$cbc,
	   				$bloodchem,
	   				$protime,
	   				$urine,
	   				$abg,
	   				$fecalysis,
	   				$uchem,
	   				$culture,
	   				$imaging,
	   				$ecg,
	   				$others,
	   				$c_notes,
	   				$cbc2,
	   				$bloodchem2,
	   				$protime2,
	   				$urine2,
	   				$abg2,
	   				$fecalysis2,
	   				$uchem2,
	   				$culture2,
	   				$imaging2,
	   				$ecg2,
	   				$others2,
			);



function __construct() {
          parent::__construct();
}
function insert_one_admission_bu($er_data){
	//$er_data[$eb_id] = $er_data[$er_id];
	//unset $er_data[$er_id];
	$this->db->insert($er_data);
                  //log backup delete task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: backup er admission id#".$this->input->post('a_id', TRUE)."\n"; 
                     fwrite($handle, $data);  	
		  return $this->db->insert_id();					  
}  



