<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Er_census_model extends CI_Model {
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

function __construct() {
          parent::__construct();
}

//get all sic
function get_allclean(){
	$this->db->select('er_id, date_in, date_out, plist, meds, refs, erefs, notes, pcpdx');
	$query = $this->db->get('er_census');
	return $query->result();
}
//clean sic
function clean_adm($aid, $date_in, $date_out, $plist, $meds, $refs, $erefs, $notes, $pcpdx){
	$data = array(
    	
    	'date_in'=>clean_form_input($date_in),
    	'date_out'=>clean_form_input($date_out),
    	'plist'=>clean_form_input($plist),
    	'meds'=>clean_form_input($meds),
    	'refs'=>clean_form_input($refs),
    	'erefs'=>clean_form_input($erefs),
    	'notes'=>clean_form_input($notes),
    	'pcpdx'=>clean_form_input($pcpdx),		
	);
	$this->db->where('er_id', $aid);
	$this->db->update('er_census', $data);

}



//get one selected admission
function get_one_admission($aid){
		 $this->db->select('er_id, p_id, pod_id, date_in, date_out, service, dispo, plist, meds, notes, pcpdx, refs, erefs');
         $this->db->where('er_id', $aid);
         $query = $this->db->get('er_census');
         return $query->result();
}


//get forms label data
// #form : 0 - cnotes, 1 - abstract, 2 - d-sumary, 3 - home meds, 4 - sagip, 5 - lab1, 6 - lab2, 7 - pcp  
function get_forms_label($aid, $forms){
      
      $this->db->select('er_id, date_in, date_out, pod_id, p_id, service, plist, meds');
      switch ($forms){
            case 0:
                $this->db->select('c_notes');
                break;
            case 1:
                $this->db->select('abstract');
                break;
            case 2:
                $this->db->select('dsummary, abstract');
                break;
            case 3:
                $this->db->select('home');
                break;
            case 4:
                $this->db->select('sagip');
                break;
            case 5:
                $this->db->select('cbc, bloodchem, protime, urine, abg, fecalysis, uchem, culture, imaging, ecg, others');
                break;
            case 6:
                $this->db->select('cbc2, bloodchem2, protime2, urine2, abg2, fecalysis2, uchem2, culture2, imaging2, ecg2, others2');
                break;
            case 7:
                $this->db->select('pcpdx');
                break;
      }
      $this->db->where('er_id', $aid);      
      $query = $this->db->get('er_census');
      return $query->result();  
}


//counting functions for reports
	//returns number of dispo between d1 and d2
function count_dispo($disp, $date1, $date2){
        $this->db->where('date_in >=', $date1); 
	$this->db->where('date_in <=', $date2);
	$this->db->where('dispo', $disp);
	$query = $this->db->get('er_census');
	return $query->num_rows();
}	

	//returns number of ref between d1 and d2
function count_refs($refs, $date1, $date2){
        $this->db->where('date_in >=', $date1); 
	$this->db->where('date_in <=', $date2);
	$this->db->like('refs', $refs);
	$query = $this->db->get('er_census');
	return $query->num_rows();
}
	//returns number of eref between d1 and d2
function count_erefs($erefs, $date1, $date2){
        $this->db->where('date_in >=', $date1); 
	$this->db->where('date_in <=', $date2);
	$this->db->like('erefs', $erefs);
	$query = $this->db->get('er_census');
	return $query->num_rows();
}
//census functions

      //select admitted er census
      function get_er_census($disp){
	      		  	
			 $this->db->select('er_id, p_id, pod_id, date_in, date_out, service, dispo, plist, meds, notes, pcpdx, refs, erefs');
			 $this->db->where('dispo', $disp);
			 if (!strcmp($disp, "Admitted"))
			 	$this->db->order_by('service asc,  date_in desc');
			 else
			 	$this->db->order_by('service asc, date_out desc');
			 $query = $this->db->get('er_census');
			 return $query->result();
}  


//get er admission data by id
function get_erdata_by_id($aid){
	$this->db->from('er_census');
	$this->db->where('er_id', $aid);
	$query = $this->db->get();
	return $query->result(); 
}	
 //delete an admission
function delete_one_admission($a_id){
        $this->db->where('er_id', $a_id);
        $query = $this->db->delete('er_census'); 
        //log delete task in text file
        $file = "er_admission_log.txt"; 
        $handle = fopen($file, 'a+');
        date_default_timezone_set('Asia/Hong_Kong');
        $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: delete er admission id#".$a_id."\n"; 
        fwrite($handle, $data);  
        return True; 
}	  	   	 

//update functions
    //edit dispo date	
function edit_dispo_date($aid, $ddate){
    $data = array( 'date_out'=>clean_form_input($ddate)); 
    $this->db->where('er_id', $aid);
    $this->db->update('er_census', $data);
    //log update task in text file
    $file = "er_admission_log.txt"; 
    $handle = fopen($file, 'a+');
    date_default_timezone_set('Asia/Hong_Kong');
    $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit dispo date id#".$aid.", date_in:".$ddate."\r\n"; 
    fwrite($handle, $data);  	
}  

//insert one admission in backup before delete operation
function insert_one_admission_bu($aid){
          $er_data = $this->Er_census_model->get_erdata_by_id($aid);
          foreach ($er_data as $row){
            $erdata = array(
                             'eb_id'=>$row->er_id,
	                         'date_in'=>$row->date_in,
	                         'date_out'=>$row->date_out,
	                         'dispo'=>$row->dispo,
	                         'p_id'=>$row->p_id,
                             'pod_id'=>$row->pod_id, 
	                         'service'=>$row->service,
	                         'plist'=>$row->plist,
	                         'meds'=>$row->meds,
	                         'refs'=>$row->refs,
	                         'erefs'=>$row->erefs,
	                         'notes'=>$row->notes,
	                         'pcpdx'=>$row->pcpdx,
                             'abstract'=>$row->abstract,
                             'dsummary'=>$row->dsummary,
	                         'sagip'=>$row->sagip,
                             'home'=>$row->home,
	                         'cbc'=>$row->cbc,
	                         'bloodchem'=>$row->bloodchem,
	                         'protime'=>$row->protime,
	                         'urine'=>$row->urine,
	                         'abg'=>$row->abg,
	                         'fecalysis'=>$row->fecalysis,
	                         'uchem'=>$row->uchem,
	                         'culture'=>$row->culture,
	                         'imaging'=>$row->imaging,
	                         'ecg'=>$row->ecg,
	                         'others'=>$row->others,
	                         'c_notes'=>$row->c_notes,
	                         'cbc2'=>$row->cbc2,
	                         'bloodchem2'=>$row->bloodchem2,
	                         'protime2'=>$row->protime2,
	                         'urine2'=>$row->urine2,
	                         'abg2'=>$row->abg2,
	                         'fecalysis2'=>$row->fecalysis2,
	                         'uchem2'=>$row->uchem2,
	                         'culture2'=>$row->culture2,
                             'imaging2'=>$row->imaging2,
	                         'ecg2'=>$row->ecg2,
	                         'others2'=>$row->others2,
	            );
          }
          
		  $this->db->insert('er_census_bu', $erdata);
                  //log backup delete task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: backup er admission id#".$erdata['eb_id']."\n"; 
                     fwrite($handle, $data);  	
		  return $this->db->insert_id();					  
}  


      //er admissions in gen med census
      function get_ergm_census($svc){
	      	 $this->db->select('p_id, pod_id, service, date_in, date_out, dispo');
			 $this->db->where('dispo', 'Admitted');
			 $this->db->where('service', $svc);
			 $this->db->order_by('date_in desc');
			 $query = $this->db->get('er_census');
			 return $query->result();
	  }  	
      //ER service report
       function er_report($service, $date1, $date2){
             $datewhere = array(
		  	               		   'date_in >=' => $date1,
			            		   'date_in <=' => $date2
			        	   );
           
             $this->db->from('er_census');
	         $this->db->join('patients', 'patients.p_id = er_census.p_id', 'inner');
	         $this->db->join('residents', 'residents.r_id = er_census.pod_id', 'inner');
             $this->db->where($datewhere);
	         $this->db->order_by('dispo asc, service asc, date_in desc');
	         $query = $this->db->get();
	         return $query->result();
           

          }	  
      
      //insert new er admission
      function insert_one_admission(){  
	        $pid = $this->input->post('epatient', TRUE);
	        $plist = clean_form_input($this->input->post('plist',TRUE));
            //process refs and erefs
	        $unrefs = $this->input->post('refs', TRUE);
			$unerefs = $this->input->post('erefs', TRUE);
			$refs = clean_form_input(implode(",", $unrefs));
			$erefs = clean_form_input(implode(",", $unerefs));

	        //update patient adm_status 
            $this->Patient_model->update_adm_status($pid, "Admitted"); 
            $this->Patient_model->update_one_plist($pid, $plist);  
            $data = array(
                                'p_id' => $pid,
	        	                'pod_id' => $this->input->post('pod_id', TRUE),
			                    'date_in' => clean_form_input($this->input->post('date_in',TRUE)),
		  	                    'date_out' => clean_form_input($this->input->post('date_out',TRUE)),
		  	                    'dispo' => $this->input->post('dispo', TRUE),
			                    'service' => $this->input->post('service', TRUE),
		  	                    'plist' => $plist,
		  	                    'meds' => clean_form_input($this->input->post('meds',TRUE)),
			                    'notes' => clean_form_input($this->input->post('notes', TRUE)),
                                'refs'=>$refs,
                                'erefs' =>$erefs,
                          );          
            $query = $this->db->insert('er_census', $data);	
            $id = $this->db->insert_id();
            //log insert task in text file
            $file = "er_admission_log.txt"; 
            $handle = fopen($file, 'a+');
            date_default_timezone_set('Asia/Hong_Kong');
            $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: insert er admission id#".$id." patient id#".$data['p_id']."\r\n"; 
            fwrite($handle, $data);  	
		    return $id;		
		        
								  
	  }
          //get er admission details for display
	  function get_admission_data($aid){
	     $this->db->select('pod_id, p_id, date_in, date_out, dispo, service, plist, meds, notes, refs, erefs, pcpdx');
		 $this->db->where('er_id', $aid);
		 $query = $this->db->get('er_census');
		 return $query->result();
	  }   
         //update one admission
	  function edit_one_admission($id){
	          $refs = $this->input->post('refs', TRUE);
		  $erefs = $this->input->post('erefs', TRUE);
		  $pcpdx = $this->input->post('pcpdx', TRUE);
	      if (is_array($refs))
	           $cs_refs = clean_form_input(implode(",", $refs));
		  else
		  	   $cs_refs = "none";	
		  if (is_array($erefs))
	           $cs_erefs = clean_form_input(implode(",", $erefs));
		  else
		  	   $cs_erefs = "none";   
		  if (is_array($pcpdx))
	           $cs_pcpdx = clean_form_input(implode(",", $pcpdx));
		  else
		  	   $cs_pcpdx = "none";	
                  $mynotes = clean_form_input($this->input->post('pnotes', TRUE))."\n".clean_form_input($this->input->post('notes', TRUE));

		  $data = array(
				'p_id'=> $this->input->post('p_id', TRUE),
				'pod_id'=> $this->input->post('pod_id', TRUE),
				'date_in'=>$this->input->post('date_in', TRUE),
				'date_out'=>$this->input->post('date_out', TRUE),
				'dispo'=> $this->input->post('dispo', TRUE),
				'service'=> $this->input->post('service', TRUE),		 
				'plist'=> clean_form_input($this->input->post('plist', TRUE)),
	        	'meds'=> clean_form_input($this->input->post('meds', TRUE)),
				'refs'=> $cs_refs,
				'erefs'=> $cs_erefs,
				'notes'=> $mynotes,
			);
		
		  $this->db->where('er_id', $id);
		  $query = $this->db->update('er_census',$data);
		  if (!strcmp($this->dispo, "Admitted"))
		      $adm_status = "Admitted";
		  else
		      $adm_status = "Not Admitted";	  
		  $this->Patient_model->update_adm_status($data['p_id'], $adm_status);
		  $this->Patient_model->update_one_plist($data['p_id'], $data['plist']); 	
                  //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit er admission id#".$id.", patient#:".$data['p_id'].", dispo:".$data['dispo']."\r\n"; 
                     fwrite($handle, $data);  						  
	  }
           
         
          //get admissions of patients   
	  function get_patients_adm($p_id){
		  $this->db->select('er_id, pod_id, p_id, date_in, date_out, service, dispo, plist, meds, notes, refs, erefs, pcpdx');
		  $this->db->where('p_id', $p_id);
		  $this->db->order_by('date_in desc, dispo asc');
		  $query = $this->db->get('er_census');
          return $query->result(); 
	  }	
          //get admissions of a erresident by id  
	  function get_erresidents_adm($r_id){
		  $this->db->select('er_id, pod_id, p_id, date_in, date_out, service, dispo, plist, meds, notes, refs, erefs, pcpdx');
		  $this->db->where('pod_id', $r_id);
		  $this->db->order_by('date_in desc, dispo asc');
		  $query = $this->db->get('er_census');
          return $query->result(); 
	  }	  
      function edit_pcpdx($aid){
		 $pcpdx = $this->input->post('pcpdx', TRUE);
		 if (is_array($pcpdx))
	           $cs_pcpdx = clean_form_input(implode(",", $pcpdx));
		 else
		  	   $cs_pcpdx = "none";	
         $data = array( 'pcpdx'=>$cs_pcpdx); 
      	 $this->db->where('er_id', $aid);
         $this->db->update('er_census', $data);
                 //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit pcpdx id#".$aid."\r\n"; 
                     fwrite($handle, $data);  	
       }  
          function edit_cnotes($aid){
		 $c_notes = clean_form_input($this->input->post('c_notes', TRUE));
		  
                  $data = array( 'c_notes'=>$c_notes); 
      		  $this->db->where('er_id', $aid);
                  $this->db->update('er_census', $data);
                 //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit cnotes id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);  	
       }  	
          function edit_abstract($aid){
                 $a_time = clean_form_input($this->input->post('a_time', TRUE));
                 $plname = clean_form_input($this->input->post('plname', TRUE));
                 $pfname = clean_form_input($this->input->post('pfname', TRUE));
                 $pmname = clean_form_input($this->input->post('pmname', TRUE));
                 $admdx = clean_form_input($this->input->post('admdx', TRUE));
                 $cc = clean_form_input($this->input->post('cc', TRUE));
                 $reason = clean_form_input($this->input->post('reason', TRUE));
                 $history = clean_form_input($this->input->post('history', TRUE));
                 $survey = clean_form_input($this->input->post('survey', TRUE));
                 $bp = clean_form_input($this->input->post('bp', TRUE));
                 $hr = clean_form_input($this->input->post('hr', TRUE));
                 $rr = clean_form_input($this->input->post('rr', TRUE));
                 $temp = clean_form_input($this->input->post('temp', TRUE));
                 $heente = clean_form_input($this->input->post('heente', TRUE));
                 $cheste = clean_form_input($this->input->post('cheste', TRUE));
                 $cvse = clean_form_input($this->input->post('cvse', TRUE));
                 $abdomene = clean_form_input($this->input->post('abdomene', TRUE));
                 $gue = clean_form_input($this->input->post('gue', TRUE));
                 $skine = clean_form_input($this->input->post('skine', TRUE));
                 $neuroe = clean_form_input($this->input->post('neuroe', TRUE));
                 $cwards = clean_form_input($this->input->post('cwards', TRUE));
                 $plabs = clean_form_input($this->input->post('plabs', TRUE));
                 $surgop = clean_form_input($this->input->post('surgop', TRUE));
                 $surgeon = clean_form_input($this->input->post('surgeon', TRUE));
                 $dateop = clean_form_input($this->input->post('dateop', TRUE));
                 $timeop = clean_form_input($this->input->post('timeop', TRUE));
                 $distime = clean_form_input($this->input->post('distime', TRUE));
                 $d_condition = clean_form_input($this->input->post('d_condition', TRUE));
                 $anestype = clean_form_input($this->input->post('anestype', TRUE));
                 $anes = clean_form_input($this->input->post('anes', TRUE));
		 $abstract = array(
			            'a_time'=>$a_time,
                        'plname'=>$plname,
                        'pfname'=>$pfname,
                        'pmname'=>$pmname,
                        'admdx'=>$admdx,
                        'cc'=>$cc,
                        'reason'=>$reason,
                        'history'=>$history,
                        'survey'=>$survey,
                        'bp'=>$bp,
                        'hr'=>$hr,
                        'rr'=>$rr,
                        'temp'=>$temp,
                        'heente'=>$heente,
                        'cheste'=>$cheste,
                        'cvse'=>$cvse,
                        'abdomene'=>$abdomene,
                        'gue'=>$gue,
                        'skine'=>$skine,
                        'neuroe'=>$neuroe,
                        'cwards'=>$cwards,
                        'plabs'=>$plabs,
                        'surgop'=>$surgop,
                        'surgeon'=>$surgeon,
                        'dateop'=>$dateop,
                        'timeop'=>$timeop,
                        'distime'=>$distime,
                        'd_condition'=>$d_condition,
                        'anestype'=>$anestype,
                        'anes'=>$anes,
                 );
                 $cs_abstract = implode(",", $abstract);
                 $data = array('abstract'=>$cs_abstract);
 		         $this->db->where('er_id', $aid);
                 $this->db->update('er_census', $data);
                 //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit abstract id#".$aid."\r\n"; 
                     fwrite($handle, $data);  	
       }
       function edit_dsummary($aid){
            $plname = clean_form_input($this->input->post('plname', TRUE));
            $pfname = clean_form_input($this->input->post('pfname', TRUE));
            $pmname = clean_form_input($this->input->post('pmname', TRUE));
            $disdiagnosis = clean_form_input($this->input->post('disdiagnosis', TRUE));
            $xnum = clean_form_input($this->input->post('xnum', TRUE));
            $disdate = clean_form_input($this->input->post('disdate', TRUE));
            $hisnum = clean_form_input($this->input->post('hisnum', TRUE));
            $mailadd = clean_form_input($this->input->post('mailadd', TRUE));
            $ptelnum = clean_form_input($this->input->post('ptelnum', TRUE));
            $relative = clean_form_input($this->input->post('relative', TRUE));
            $reladd = clean_form_input($this->input->post('reladd', TRUE));
            $rtelnum = clean_form_input($this->input->post('rtelnum', TRUE));
            $dispe = clean_form_input($this->input->post('dispe', TRUE));
            $hgb = clean_form_input($this->input->post('hgb', TRUE));
            $hct = clean_form_input($this->input->post('hct', TRUE));
            $btype = clean_form_input($this->input->post('btype', TRUE));
            $wbc = clean_form_input($this->input->post('wbc', TRUE));
            $esr = clean_form_input($this->input->post('esr', TRUE));
            $aphos = clean_form_input($this->input->post('aphos', TRUE));
            $culture = clean_form_input($this->input->post('culture', TRUE));
            $xray = clean_form_input($this->input->post('xray', TRUE));
            $postop = clean_form_input($this->input->post('postop', TRUE));
            $or1 = clean_form_input($this->input->post('or1', TRUE));
            $surg1 = clean_form_input($this->input->post('surg1', TRUE));
            $ordate1 = clean_form_input($this->input->post('ordate1', TRUE));
            $or2 = clean_form_input($this->input->post('or2', TRUE));
            $surg2 = clean_form_input($this->input->post('surg2', TRUE));
            $ordate2 = clean_form_input($this->input->post('ordate2', TRUE));
            $or3 = clean_form_input($this->input->post('or3', TRUE));
            $surg3 = clean_form_input($this->input->post('surg3', TRUE));
            $ordate3 = clean_form_input($this->input->post('ordate3', TRUE));
            $or4 = clean_form_input($this->input->post('or4', TRUE));
            $surg4 = clean_form_input($this->input->post('surg4', TRUE));
            $ordate4 = clean_form_input($this->input->post('ordate4', TRUE));
            $or5 = clean_form_input($this->input->post('or5', TRUE));
            $surg5 = clean_form_input($this->input->post('surg5', TRUE));
            $ordate5 = clean_form_input($this->input->post('ordate5', TRUE));
            $orfind = clean_form_input($this->input->post('orfind', TRUE));
            $cwards = clean_form_input($this->input->post('cwards', TRUE));
            $morbid = clean_form_input($this->input->post('morbid', TRUE));
            $mspecify = clean_form_input($this->input->post('mspecify', TRUE));
            $displans = clean_form_input($this->input->post('displans', TRUE));
            $prepby = clean_form_input($this->input->post('prepby', TRUE));
            $ric = clean_form_input($this->input->post('ric', TRUE));

            $dsummary = array(
                               'plname'=>$plname, 
            			'pfname'=>$pfname,
           			'pmname'=>$pmname,
            			'disdiagnosis'=>$disdiagnosis, 
            			'xnum'=>$xnum,
            			'disdate'=>$disdate,
            			'hisnum'=>$hisnum,
            			'mailadd'=>$mailadd,
            			'ptelnum'=>$ptelnum,
            			'relative'=>$relative,
                                'reladd'=>$reladd,
            			'rtelnum'=>$rtelnum,
            			'dispe'=>$dispe,
            			'hgb'=>	$hgb,
            			'hct'=>	$hct,
            			'btype'=>$btype,
            			'wbc'=>	$wbc,
            			'esr'=>	$esr,
            			'aphos'=>$aphos,
            			'culture'=>$culture,
            			'xray'=>$xray,
            			'postop'=>$postop,
            			'or1'=>	$or1,
            			'surg1'=>$surg1,
            			'ordate1'=>$ordate1,
            			'or2'=>	$or2,
            			'surg2'=>$surg2,
           			'ordate2'=>$ordate2,
            			'or3'=>	$or3,
            			'surg3'=>$surg3,
            			'ordate3'=>$ordate3,
            			'or4'=>	$or4,
            			'surg4'=>$surg4,
            			'ordate4'=>$ordate4,
            			'or5'=>	$or5,
            			'surg5'=>$surg5,
            			'ordate5'=>$ordate5,
            			'orfind'=>$orfind,
            			'cwards'=>$cwards,
            			'morbid'=>$morbid,
            			'mspecify'=>$mspecify,
            			'displans'=>$displans,
            			'prepby'=>$prepby,
            			'ric'=>	$ric,
                      );

                 $cs_dsummary = implode(",", $dsummary);
                 $data = array(
                               'dsummary'=>$cs_dsummary
                 );
 		 $this->db->where('er_id', $aid);
                 $this->db->update('er_census', $data);
                 //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit dsummary id#".$aid."\r\n"; 
                     fwrite($handle, $data);  	


       }
       function edit_sagip($aid)
       {
            $occupation = clean_form_input($this->input->post('occupation', TRUE));   
            $mss = clean_form_input($this->input->post('mss', TRUE)); 
	    $housepip = clean_form_input($this->input->post('housepip', TRUE));
            $housedep = clean_form_input($this->input->post('housedep', TRUE));
            $req1 = clean_form_input($this->input->post('req1', TRUE));
            $cost1 = clean_form_input($this->input->post('cost1', TRUE));
            $numitem1 = clean_form_input($this->input->post('numitem1', TRUE));
            $req2 = clean_form_input($this->input->post('req2', TRUE));
            $cost2 = clean_form_input($this->input->post('cost2', TRUE));
            $numitem2 = clean_form_input($this->input->post('numitem2', TRUE));
            $req3 = clean_form_input($this->input->post('req3', TRUE));
            $cost3 = clean_form_input($this->input->post('cost3', TRUE));
            $numitem3 = clean_form_input($this->input->post('numitem3', TRUE));
            $req4 = clean_form_input($this->input->post('req4', TRUE));
            $cost4 = clean_form_input($this->input->post('cost4', TRUE));
            $numitem4 = clean_form_input($this->input->post('numitem4', TRUE));
        
            $sagip = array(
                           'occupation'=>$occupation,
                           'mss'=>$mss,
                           'housepip'=>$housepip,
                           'housedep'=>$housedep,
                           'req1'=>$req1,
                           'cost1'=>$cost1,
                           'numitem1'=>$numitem1,
                           'req2'=>$req2,
                           'cost2'=>$cost2,
                           'numitem2'=>$numitem2,
                           'req3'=>$req3,
                           'cost3'=>$cost3,
                           'numitem3'=>$numitem3,
                           'req4'=>$req4,
                           'cost4'=>$cost4,
                           'numitem4'=>$numitem4,
                     );
              $cs_sagip = implode(",", $sagip);
                 $data = array(
                               'sagip'=>$cs_sagip
                 );
              $this->db->where('er_id', $aid);

              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit sagip id#".$aid."\r\n"; 
                     fwrite($handle, $data);                
       
       }
    function edit_home($aid){
        $out_date = clean_form_input($this->input->post('out_date', TRUE));
        $med1 = clean_form_input($this->input->post('med1', TRUE));
        $a6am = clean_form_input($this->input->post('a6am', TRUE));
        $a8am = clean_form_input($this->input->post('a8am', TRUE));
        $a1pm = clean_form_input($this->input->post('a1pm', TRUE));
	$a6pm = clean_form_input($this->input->post('a6pm', TRUE));
	$a8pm = clean_form_input($this->input->post('a8pm', TRUE));
	$a10pm = clean_form_input($this->input->post('a10pm', TRUE));
	$long1 = clean_form_input($this->input->post('long1', TRUE));    
	$med2 = clean_form_input($this->input->post('med2', TRUE));
        $b6am = clean_form_input($this->input->post('b6am', TRUE));
        $b8am = clean_form_input($this->input->post('b8am', TRUE));
        $b1pm = clean_form_input($this->input->post('b1pm', TRUE));
	$b6pm = clean_form_input($this->input->post('b6pm', TRUE));
	$b8pm = clean_form_input($this->input->post('b8pm', TRUE));
	$b10pm = clean_form_input($this->input->post('b10pm', TRUE));
	$long2= clean_form_input($this->input->post('long2', TRUE));    
	$med3 = clean_form_input($this->input->post('med3', TRUE));
        $c6am = clean_form_input($this->input->post('c6am', TRUE));
        $c8am = clean_form_input($this->input->post('c8am', TRUE));
        $c1pm = clean_form_input($this->input->post('c1pm', TRUE));
	$c6pm = clean_form_input($this->input->post('c6pm', TRUE));
	$c8pm = clean_form_input($this->input->post('c8pm', TRUE));
	$c10pm = clean_form_input($this->input->post('c10pm', TRUE));
	$long3= clean_form_input($this->input->post('long3', TRUE));    
	$med4 = clean_form_input($this->input->post('med4', TRUE));
        $d6am = clean_form_input($this->input->post('d6am', TRUE));
        $d8am = clean_form_input($this->input->post('d8am', TRUE));
        $d1pm = clean_form_input($this->input->post('d1pm', TRUE));
	$d6pm = clean_form_input($this->input->post('d6pm', TRUE));
	$d8pm = clean_form_input($this->input->post('d8pm', TRUE));
	$d10pm = clean_form_input($this->input->post('d10pm', TRUE));
	$long4= clean_form_input($this->input->post('long4', TRUE));    
	$med5 = clean_form_input($this->input->post('med5', TRUE));
        $e6am = clean_form_input($this->input->post('e6am', TRUE));
        $e8am = clean_form_input($this->input->post('e8am', TRUE));
        $e1pm = clean_form_input($this->input->post('e1pm', TRUE));
	$e6pm = clean_form_input($this->input->post('e6pm', TRUE));
	$e8pm = clean_form_input($this->input->post('e8pm', TRUE));
	$e10pm = clean_form_input($this->input->post('e10pm', TRUE));
	$long5= clean_form_input($this->input->post('long5', TRUE));    
	$med6 = clean_form_input($this->input->post('med6', TRUE));
        $f6am = clean_form_input($this->input->post('f6am', TRUE));
        $f8am = clean_form_input($this->input->post('f8am', TRUE));
        $f1pm = clean_form_input($this->input->post('f1pm', TRUE));
	$f6pm = clean_form_input($this->input->post('f6pm', TRUE));
	$f8pm = clean_form_input($this->input->post('f8pm', TRUE));
	$f10pm = clean_form_input($this->input->post('f10pm', TRUE));
	$long6= clean_form_input($this->input->post('long6', TRUE));    
	$med7 = clean_form_input($this->input->post('med7', TRUE));
        $g6am = clean_form_input($this->input->post('g6am', TRUE));
        $g8am = clean_form_input($this->input->post('g8am', TRUE));
        $g1pm = clean_form_input($this->input->post('g1pm', TRUE));
	$g6pm = clean_form_input($this->input->post('g6pm', TRUE));
	$g8pm = clean_form_input($this->input->post('g8pm', TRUE));
	$g10pm = clean_form_input($this->input->post('g10pm', TRUE));
	$long7= clean_form_input($this->input->post('long7', TRUE));    
	$med8 = clean_form_input($this->input->post('med8', TRUE));
        $h6am = clean_form_input($this->input->post('h6am', TRUE));
        $h8am = clean_form_input($this->input->post('h8am', TRUE));
        $h1pm = clean_form_input($this->input->post('h1pm', TRUE));
	$h6pm = clean_form_input($this->input->post('h6pm', TRUE));
	$h8pm = clean_form_input($this->input->post('h8pm', TRUE));
	$h10pm = clean_form_input($this->input->post('h10pm', TRUE));
	$long8= clean_form_input($this->input->post('long8', TRUE));    
    //meds9
        $med9 = clean_form_input($this->input->post('med9', TRUE));
        $i6am = clean_form_input($this->input->post('i6am', TRUE));
        $i8am = clean_form_input($this->input->post('i8am', TRUE));
        $i1pm = clean_form_input($this->input->post('i1pm', TRUE));
        $i6pm = clean_form_input($this->input->post('i6pm', TRUE));
        $i8pm = clean_form_input($this->input->post('i8pm', TRUE));
        $i10pm = clean_form_input($this->input->post('i10pm', TRUE));
        $long9 = clean_form_input($this->input->post('long9', TRUE));
        //meds10
        $med10 = clean_form_input($this->input->post('med10', TRUE));
        $j6am = clean_form_input($this->input->post('j6am', TRUE));
        $j8am = clean_form_input($this->input->post('j8am', TRUE));
        $j1pm = clean_form_input($this->input->post('j1pm', TRUE));
        $j6pm = clean_form_input($this->input->post('j6pm', TRUE));
        $j8pm = clean_form_input($this->input->post('j8pm', TRUE));
        $j10pm = clean_form_input($this->input->post('j10pm', TRUE));
        $long10= clean_form_input($this->input->post('long10', TRUE));
        //meds11
        $med11 = clean_form_input($this->input->post('med11', TRUE));
        $k6am = clean_form_input($this->input->post('k6am', TRUE));
        $k8am = clean_form_input($this->input->post('k8am', TRUE));
        $k1pm = clean_form_input($this->input->post('k1pm', TRUE));
        $k6pm = clean_form_input($this->input->post('k6pm', TRUE));
        $k8pm = clean_form_input($this->input->post('k8pm', TRUE));
        $k10pm = clean_form_input($this->input->post('k10pm', TRUE));
        $long11= clean_form_input($this->input->post('long11', TRUE));
        //meds12
        $med12 = clean_form_input($this->input->post('med12', TRUE));
        $l6am = clean_form_input($this->input->post('l6am', TRUE));
        $l8am = clean_form_input($this->input->post('l8am', TRUE));
        $l1pm = clean_form_input($this->input->post('l1pm', TRUE));
        $l6pm = clean_form_input($this->input->post('l6pm', TRUE));
        $l8pm = clean_form_input($this->input->post('l8pm', TRUE));
        $l10pm = clean_form_input($this->input->post('l10pm', TRUE));
        $long12= clean_form_input($this->input->post('long12', TRUE));
        //meds13
        $med13 = clean_form_input($this->input->post('med13', TRUE));
        $m6am = clean_form_input($this->input->post('m6am', TRUE));
        $m8am = clean_form_input($this->input->post('m8am', TRUE));
        $m1pm = clean_form_input($this->input->post('m1pm', TRUE));
        $m6pm = clean_form_input($this->input->post('m6pm', TRUE));
        $m8pm = clean_form_input($this->input->post('m8pm', TRUE));
        $m10pm = clean_form_input($this->input->post('m10pm', TRUE));
        $long13= clean_form_input($this->input->post('long13', TRUE));
        //meds14
        $med14 = clean_form_input($this->input->post('med14', TRUE));
        $n6am = clean_form_input($this->input->post('n6am', TRUE));
        $n8am = clean_form_input($this->input->post('n8am', TRUE));
        $n1pm = clean_form_input($this->input->post('n1pm', TRUE));
        $n6pm = clean_form_input($this->input->post('n6pm', TRUE));
        $n8pm = clean_form_input($this->input->post('n8pm', TRUE));
        $n10pm = clean_form_input($this->input->post('n10pm', TRUE));
        $long14= clean_form_input($this->input->post('long14', TRUE));
        //meds15
        $med15 = clean_form_input($this->input->post('med15', TRUE));
        $o6am = clean_form_input($this->input->post('o6am', TRUE));
        $o8am = clean_form_input($this->input->post('o8am', TRUE));
        $o1pm = clean_form_input($this->input->post('o1pm', TRUE));
        $o6pm = clean_form_input($this->input->post('o6pm', TRUE));
        $o8pm = clean_form_input($this->input->post('o8pm', TRUE));
        $o10pm = clean_form_input($this->input->post('o10pm', TRUE));
        $long15= clean_form_input($this->input->post('long15', TRUE));
        //meds16
        $med16 = clean_form_input($this->input->post('med16', TRUE));
        $p6am = clean_form_input($this->input->post('p6am', TRUE));
        $p8am = clean_form_input($this->input->post('p8am', TRUE));
        $p1pm = clean_form_input($this->input->post('p1pm', TRUE));
        $p6pm = clean_form_input($this->input->post('p6pm', TRUE));
        $p8pm = clean_form_input($this->input->post('p8pm', TRUE));
        $p10pm = clean_form_input($this->input->post('p10pm', TRUE));
        $long16= clean_form_input($this->input->post('long16', TRUE));    
        $home = array(
		'out_date'=>$out_date,
		'med1'=>$med1,
		'a6am'=>$a6am,
		'a8am'=>$a8am,
		'a1pm'=>$a1pm,
		'a6pm'=>$a6pm,
		'a8pm'=>$a8pm,
		'a10pm'=>$a10pm,
		'long1'=>$long1,    
		'med2'=>$med2,
		'b6am'=>$b6am,
		'b8am'=>$b8am,
		'b1pm'=>$b1pm,
		'b6pm'=>$b6pm,
		'b8pm'=>$b8pm,
		'b10pm'=>$b10pm,
		'long2'=>$long2,    
		'med3'=>$med3,
		'c6am'=>$c6am,
		'c8am'=>$c8am,
		'c1pm'=>$c1pm,
		'c6pm'=>$c6pm,
		'c8pm'=>$c8pm,
		'c10pm'=>$c10pm,
		'long3'=>$long3,    
		'med4'=>$med4,
		'd6am'=>$d6am,
		'd8am'=>$d8am,
		'd1pm'=>$d1pm,
		'd6pm'=>$d6pm,
		'd8pm'=>$d8pm,
		'd10pm'=>$d10pm,
		'long4'=>$long4,    
		'med5'=>$med5,
		'e6am'=>$e6am,
		'e8am'=>$e8am,
		'e1pm'=>$e1pm,
		'e6pm'=>$e6pm,
		'e8pm'=>$e8pm,
		'e10pm'=>$e10pm,
		'long5'=>$long5,   
		'med6'=>$med6,
		'f6am'=>$f6am,
		'f8am'=>$f8am,
		'f1pm'=>$f1pm,
		'f6pm'=>$f6pm,
		'f8pm'=>$f8pm,
		'f10pm'=>$f10pm,
		'long6'=>$long6,   
		'med7'=>$med7,
		'g6am'=>$g6am,
		'g8am'=>$g8am,
		'g1pm'=>$g1pm,
		'g6pm'=>$g6pm,
		'g8pm'=>$g8pm,
		'g10pm'=>$g10pm,
		'long7'=>$long7,  
		'med8'=>$med8,
		'h6am'=>$h6am,
		'h8am'=>$h8am,
		'h1pm'=>$h1pm,
		'h6pm'=>$h6pm,
		'h8pm'=>$h8pm,
		'h10pm'=>$h10pm,
		'long8'=>$long8,   
		//next 8
		'med9'=>$med9,
		'i6am'=>$i6am,
		'i8am'=>$i8am,
		'i1pm'=>$i1pm,
		'i6pm'=>$i6pm,
		'i8pm'=>$i8pm,
		'i10pm'=>$i10pm,
		'long9'=>$long9,
		'med10'=>$med10,
		'j6am'=>$j6am,
		'j8am'=>$j8am,
		'j1pm'=>$j1pm,
		'j6pm'=>$j6pm,
		'j8pm'=>$j8pm,
		'j10pm'=>$j10pm,
		'long10'=>$long10,
		'med11'=>$med11,
		'k6am'=>$k6am,
		'k8am'=>$k8am,
		'k1pm'=>$k1pm,
		'k6pm'=>$k6pm,
		'k8pm'=>$k8pm,
		'k10pm'=>$k10pm,
		'long11'=>$long11,
		'med12'=>$med12,
		'l6am'=>$l6am,
		'l8am'=>$l8am,
		'l1pm'=>$l1pm,
		'l6pm'=>$l6pm,
		'l8pm'=>$l8pm,
		'l10pm'=>$l10pm,
		'long12'=>$long12,
		'med13'=>$med13,
		'm6am'=>$m6am,
		'm8am'=>$m8am,
		'm1pm'=>$m1pm,
		'm6pm'=>$m6pm,
		'm8pm'=>$m8pm,
		'm10pm'=>$m10pm,
		'long13'=>$long13,
		'med14'=>$med14,
		'n6am'=>$n6am,
		'n8am'=>$n8am,
		'n1pm'=>$n1pm,
		'n6pm'=>$n6pm,
		'n8pm'=>$n8pm,
		'n10pm'=>$n10pm,
		'long14'=>$long14,
		'med15'=>$med15,
		'o6am'=>$o6am,
		'o8am'=>$o8am,
		'o1pm'=>$o1pm,
		'o6pm'=>$o6pm,
		'o8pm'=>$o8pm,
		'o10pm'=>$o10pm,
		'long15'=>$long15,
		'med16'=>$med16,
		'p6am'=>$p6am,
		'p8am'=>$p8am,
		'p1pm'=>$p1pm,
		'p6pm'=>$p6pm,
		'p8pm'=>$p8pm,
		'p10pm'=>$p10pm,
		'long16'=>$long16,	
        );    
	$cs_home = implode(",", $home);
    $data = array('home'=>$cs_home);
    $this->db->where('er_id', $aid);
    $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit home id#".$aid."\r\n"; 
                     fwrite($handle, $data);                
       }
       function count_pcpdx($date1, $date2, $dx){
	         $datewhere = array(
		  			   'date_in >=' => $date1,
					   'date_in <=' => $date2
					   );
	         $this->db->from('er_census');
			 $this->db->where($datewhere);
			 $this->db->like('pcpdx', $dx);
	         $query = $this->db->get();
			 return $query->num_rows();
	}	  
        function edit_cbc($aid)
       {
	$cbc = array(
                //col 1
        	'Date1' => clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=>clean_form_input($this->input->post('Time1', TRUE)),
		'WBC1' => clean_form_input($this->input->post('WBC1', TRUE)),
		'RBC1' => clean_form_input($this->input->post('RBC1', TRUE)),
		'Hgb1' => clean_form_input($this->input->post('Hgb1', TRUE)),
		'Hct1' => clean_form_input($this->input->post('Hct1', TRUE)),
		'MCV1' => clean_form_input($this->input->post('MCV1', TRUE)),
		'MCH1' => clean_form_input($this->input->post('MCH1', TRUE)),
		'MCHC1' => clean_form_input($this->input->post('MCHC1', TRUE)),
		'RDWCV1' => clean_form_input($this->input->post('RDWCV1', TRUE)),
 		'Platelets1' => clean_form_input($this->input->post('Platelets1', TRUE)),
		'Neut1' => clean_form_input($this->input->post('Neut1', TRUE)),
		'Lymph1' => clean_form_input($this->input->post('Lymph1', TRUE)),
		'Mono1' => clean_form_input($this->input->post('Mono1', TRUE)),
		'Eo1' => clean_form_input($this->input->post('Eo1', TRUE)),	
		'Baso1' => clean_form_input($this->input->post('Baso1', TRUE)),
		'Pro_Mye_Jv1' => clean_form_input($this->input->post('Pro_Mye_Jv1', TRUE)),
		'Stabs1' => clean_form_input($this->input->post('Stabs1', TRUE)),
		'Blasts1' => clean_form_input($this->input->post('Blasts1', TRUE)),
		'NRBC1' => clean_form_input($this->input->post('NRBC1', TRUE)),
                //col 2
		'Date2' => clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=>clean_form_input($this->input->post('Time2', TRUE)),
		'WBC2' => clean_form_input($this->input->post('WBC2', TRUE)),
		'RBC2' => clean_form_input($this->input->post('RBC2', TRUE)),
		'Hgb2' => clean_form_input($this->input->post('Hgb2', TRUE)),
		'Hct2' => clean_form_input($this->input->post('Hct2', TRUE)),
		'MCV2' => clean_form_input($this->input->post('MCV2', TRUE)),
		'MCH2' => clean_form_input($this->input->post('MCH2', TRUE)),
		'MCHC2' => clean_form_input($this->input->post('MCHC2', TRUE)),
		'RDWCV2' => clean_form_input($this->input->post('RDWCV2', TRUE)),
 		'Platelets2' => clean_form_input($this->input->post('Platelets2', TRUE)),
		'Neut2' => clean_form_input($this->input->post('Neut2', TRUE)),
		'Lymph2' => clean_form_input($this->input->post('Lymph2', TRUE)),
		'Mono2' => clean_form_input($this->input->post('Mono2', TRUE)),
		'Eo2' => clean_form_input($this->input->post('Eo2', TRUE)),	
		'Baso2' => clean_form_input($this->input->post('Baso2', TRUE)),
		'Pro_Mye_Jv2' => clean_form_input($this->input->post('Pro_Mye_Jv2', TRUE)),
		'Stabs2' => clean_form_input($this->input->post('Stabs2', TRUE)),
		'Blasts2' => clean_form_input($this->input->post('Blasts2', TRUE)),
		'NRBC2' => clean_form_input($this->input->post('NRBC2', TRUE)),
                //col 3
		'Date3' => clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=>clean_form_input($this->input->post('Time3', TRUE)),
		'WBC3' => clean_form_input($this->input->post('WBC3', TRUE)),
		'RBC3' => clean_form_input($this->input->post('RBC3', TRUE)),
		'Hgb3' => clean_form_input($this->input->post('Hgb3', TRUE)),
		'Hct3' => clean_form_input($this->input->post('Hct3', TRUE)),
		'MCV3' => clean_form_input($this->input->post('MCV3', TRUE)),
		'MCH3' => clean_form_input($this->input->post('MCH3', TRUE)),
		'MCHC3' => clean_form_input($this->input->post('MCHC3', TRUE)),
		'RDWCV3' => clean_form_input($this->input->post('RDWCV3', TRUE)),
 		'Platelets3' => clean_form_input($this->input->post('Platelets3', TRUE)),
		'Neut3' => clean_form_input($this->input->post('Neut3', TRUE)),
		'Lymph3' => clean_form_input($this->input->post('Lymph3', TRUE)),
		'Mono3' => clean_form_input($this->input->post('Mono3', TRUE)),
		'Eo3' => clean_form_input($this->input->post('Eo3', TRUE)),	
		'Baso3' => clean_form_input($this->input->post('Baso3', TRUE)),
		'Pro_Mye_Jv3' => clean_form_input($this->input->post('Pro_Mye_Jv3', TRUE)),
		'Stabs3' => clean_form_input($this->input->post('Stabs3', TRUE)),
		'Blasts3' => clean_form_input($this->input->post('Blasts3', TRUE)),
		'NRBC3' => clean_form_input($this->input->post('NRBC3', TRUE)),
		//col 4
		'Date4' => clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=>clean_form_input($this->input->post('Time4', TRUE)),
		'WBC4' => clean_form_input($this->input->post('WBC4', TRUE)),
		'RBC4' => clean_form_input($this->input->post('RBC4', TRUE)),
		'Hgb4' => clean_form_input($this->input->post('Hgb4', TRUE)),
		'Hct4' => clean_form_input($this->input->post('Hct4', TRUE)),
		'MCV4' => clean_form_input($this->input->post('MCV4', TRUE)),
		'MCH4' => clean_form_input($this->input->post('MCH4', TRUE)),
		'MCHC4' => clean_form_input($this->input->post('MCHC4', TRUE)),
		'RDWCV4' => clean_form_input($this->input->post('RDWCV4', TRUE)),
 		'Platelets4' => clean_form_input($this->input->post('Platelets4', TRUE)),
		'Neut4' => clean_form_input($this->input->post('Neut4', TRUE)),
		'Lymph4' => clean_form_input($this->input->post('Lymph4', TRUE)),
		'Mono4' => clean_form_input($this->input->post('Mono4', TRUE)),
		'Eo4' => clean_form_input($this->input->post('Eo4', TRUE)),	
		'Baso4' => clean_form_input($this->input->post('Baso4', TRUE)),
		'Pro_Mye_Jv4' => clean_form_input($this->input->post('Pro_Mye_Jv4', TRUE)),
		'Stabs4' => clean_form_input($this->input->post('Stabs4', TRUE)),
		'Blasts4' => clean_form_input($this->input->post('Blasts4', TRUE)),
		'NRBC4' => clean_form_input($this->input->post('NRBC4', TRUE)),
		//col 5
		'Date5' => clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=>clean_form_input($this->input->post('Time5', TRUE)),
		'WBC5' => clean_form_input($this->input->post('WBC5', TRUE)),
		'RBC5' => clean_form_input($this->input->post('RBC5', TRUE)),
		'Hgb5' => clean_form_input($this->input->post('Hgb5', TRUE)),
		'Hct5' => clean_form_input($this->input->post('Hct5', TRUE)),
		'MCV5' => clean_form_input($this->input->post('MCV5', TRUE)),
		'MCH5' => clean_form_input($this->input->post('MCH5', TRUE)),
		'MCHC5' => clean_form_input($this->input->post('MCHC5', TRUE)),
		'RDWCV5' => clean_form_input($this->input->post('RDWCV5', TRUE)),
 		'Platelets5' => clean_form_input($this->input->post('Platelets5', TRUE)),
		'Neut5' => clean_form_input($this->input->post('Neut5', TRUE)),
		'Lymph5' => clean_form_input($this->input->post('Lymph5', TRUE)),
		'Mono5' => clean_form_input($this->input->post('Mono5', TRUE)),
		'Eo5' => clean_form_input($this->input->post('Eo5', TRUE)),	
		'Baso5' => clean_form_input($this->input->post('Baso5', TRUE)),
		'Pro_Mye_Jv5' => clean_form_input($this->input->post('Pro_Mye_Jv5', TRUE)),
		'Stabs5' => clean_form_input($this->input->post('Stabs5', TRUE)),
		'Blasts5' => clean_form_input($this->input->post('Blasts5', TRUE)),
		'NRBC5' => clean_form_input($this->input->post('NRBC5', TRUE)),
		//col 6
		'Date6' => clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=>clean_form_input($this->input->post('Time6', TRUE)),
		'WBC6' => clean_form_input($this->input->post('WBC6', TRUE)),
		'RBC6' => clean_form_input($this->input->post('RBC6', TRUE)),
		'Hgb6' => clean_form_input($this->input->post('Hgb6', TRUE)),
		'Hct6' => clean_form_input($this->input->post('Hct6', TRUE)),
		'MCV6' => clean_form_input($this->input->post('MCV6', TRUE)),
		'MCH6' => clean_form_input($this->input->post('MCH6', TRUE)),
		'MCHC6' => clean_form_input($this->input->post('MCHC6', TRUE)),
		'RDWCV6' => clean_form_input($this->input->post('RDWCV6', TRUE)),
 		'Platelets6' => clean_form_input($this->input->post('Platelets6', TRUE)),
		'Neut6' => clean_form_input($this->input->post('Neut6', TRUE)),
		'Lymph6' => clean_form_input($this->input->post('Lymph6', TRUE)),
		'Mono6' => clean_form_input($this->input->post('Mono6', TRUE)),
		'Eo6' => clean_form_input($this->input->post('Eo6', TRUE)),	
		'Baso6' => clean_form_input($this->input->post('Baso6', TRUE)),
		'Pro_Mye_Jv6' => clean_form_input($this->input->post('Pro_Mye_Jv6', TRUE)),
		'Stabs6' => clean_form_input($this->input->post('Stabs6', TRUE)),
		'Blasts6' => clean_form_input($this->input->post('Blasts6', TRUE)),
		'NRBC6' => clean_form_input($this->input->post('NRBC6', TRUE)),
		//col 7
		'Date7' => clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=>clean_form_input($this->input->post('Time7', TRUE)),
		'WBC7' => clean_form_input($this->input->post('WBC7', TRUE)),
		'RBC7' => clean_form_input($this->input->post('RBC7', TRUE)),
		'Hgb7' => clean_form_input($this->input->post('Hgb7', TRUE)),
		'Hct7' => clean_form_input($this->input->post('Hct7', TRUE)),
		'MCV7' => clean_form_input($this->input->post('MCV7', TRUE)),
		'MCH7' => clean_form_input($this->input->post('MCH7', TRUE)),
		'MCHC7' => clean_form_input($this->input->post('MCHC7', TRUE)),
		'RDWCV7' => clean_form_input($this->input->post('RDWCV7', TRUE)),
 		'Platelets7' => clean_form_input($this->input->post('Platelets7', TRUE)),
		'Neut7' => clean_form_input($this->input->post('Neut7', TRUE)),
		'Lymph7' => clean_form_input($this->input->post('Lymph7', TRUE)),
		'Mono7' => clean_form_input($this->input->post('Mono7', TRUE)),
		'Eo7' => clean_form_input($this->input->post('Eo7', TRUE)),	
		'Baso7' => clean_form_input($this->input->post('Baso7', TRUE)),
		'Pro_Mye_Jv7' => clean_form_input($this->input->post('Pro_Mye_Jv7', TRUE)),
		'Stabs7' => clean_form_input($this->input->post('Stabs7', TRUE)),
		'Blasts7' => clean_form_input($this->input->post('Blasts7', TRUE)),
		'NRBC7' => clean_form_input($this->input->post('NRBC7', TRUE)),
		//col 8
		'Date8' => clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=>clean_form_input($this->input->post('Time8', TRUE)),
		'WBC8' => clean_form_input($this->input->post('WBC8', TRUE)),
		'RBC8' => clean_form_input($this->input->post('RBC8', TRUE)),
		'Hgb8' => clean_form_input($this->input->post('Hgb8', TRUE)),
		'Hct8' => clean_form_input($this->input->post('Hct8', TRUE)),
		'MCV8' => clean_form_input($this->input->post('MCV8', TRUE)),
		'MCH8' => clean_form_input($this->input->post('MCH8', TRUE)),
		'MCHC8' => clean_form_input($this->input->post('MCHC8', TRUE)),
		'RDWCV8' => clean_form_input($this->input->post('RDWCV8', TRUE)),
 		'Platelets8' => clean_form_input($this->input->post('Platelets8', TRUE)),
		'Neut8' => clean_form_input($this->input->post('Neut8', TRUE)),
		'Lymph8' => clean_form_input($this->input->post('Lymph8', TRUE)),
		'Mono8' => clean_form_input($this->input->post('Mono8', TRUE)),
		'Eo8' => clean_form_input($this->input->post('Eo8', TRUE)),	
		'Baso8' => clean_form_input($this->input->post('Baso8', TRUE)),
		'Pro_Mye_Jv8' => clean_form_input($this->input->post('Pro_Mye_Jv8', TRUE)),
		'Stabs8' => clean_form_input($this->input->post('Stabs8', TRUE)),
		'Blasts8' => clean_form_input($this->input->post('Blasts8', TRUE)),
		'NRBC8' => clean_form_input($this->input->post('NRBC8', TRUE)),
		//col 9
		'Date9' => clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=>clean_form_input($this->input->post('Time9', TRUE)),
		'WBC9' => clean_form_input($this->input->post('WBC9', TRUE)),
		'RBC9' => clean_form_input($this->input->post('RBC9', TRUE)),
		'Hgb9' => clean_form_input($this->input->post('Hgb9', TRUE)),
		'Hct9' => clean_form_input($this->input->post('Hct9', TRUE)),
		'MCV9' => clean_form_input($this->input->post('MCV9', TRUE)),
		'MCH9' => clean_form_input($this->input->post('MCH9', TRUE)),
		'MCHC9' => clean_form_input($this->input->post('MCHC9', TRUE)),
		'RDWCV9' => clean_form_input($this->input->post('RDWCV9', TRUE)),
 		'Platelets9' => clean_form_input($this->input->post('Platelets9', TRUE)),
		'Neut9' => clean_form_input($this->input->post('Neut9', TRUE)),
		'Lymph9' => clean_form_input($this->input->post('Lymph9', TRUE)),
		'Mono9' => clean_form_input($this->input->post('Mono9', TRUE)),
		'Eo9' => clean_form_input($this->input->post('Eo9', TRUE)),	
		'Baso9' => clean_form_input($this->input->post('Baso9', TRUE)),
		'Pro_Mye_Jv9' => clean_form_input($this->input->post('Pro_Mye_Jv9', TRUE)),
		'Stabs9' => clean_form_input($this->input->post('Stabs9', TRUE)),
		'Blasts9' => clean_form_input($this->input->post('Blasts9', TRUE)),
		'NRBC9' => clean_form_input($this->input->post('NRBC9', TRUE)),
        	//col 10
		'Date10' => clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=>clean_form_input($this->input->post('Time10', TRUE)),
		'WBC10' => clean_form_input($this->input->post('WBC10', TRUE)),
		'RBC10' => clean_form_input($this->input->post('RBC10', TRUE)),
		'Hgb10' => clean_form_input($this->input->post('Hgb10', TRUE)),
		'Hct10' => clean_form_input($this->input->post('Hct10', TRUE)),
		'MCV10' => clean_form_input($this->input->post('MCV10', TRUE)),
		'MCH10' => clean_form_input($this->input->post('MCH10', TRUE)),
		'MCHC10' => clean_form_input($this->input->post('MCHC10', TRUE)),
		'RDWCV10' => clean_form_input($this->input->post('RDWCV10', TRUE)),

 		'Platelets10' => clean_form_input($this->input->post('Platelets10', TRUE)),
		'Neut10' => clean_form_input($this->input->post('Neut10', TRUE)),
		'Lymph10' => clean_form_input($this->input->post('Lymph10', TRUE)),
		'Mono10' => clean_form_input($this->input->post('Mono10', TRUE)),
		'Eo10' => clean_form_input($this->input->post('Eo10', TRUE)),	
		'Baso10' => clean_form_input($this->input->post('Baso10', TRUE)),
		'Pro_Mye_Jv10' => clean_form_input($this->input->post('Pro_Mye_Jv10', TRUE)),
		'Stabs10' => clean_form_input($this->input->post('Stabs10', TRUE)),
		'Blasts10' => clean_form_input($this->input->post('Blasts10', TRUE)),
		'NRBC10' => clean_form_input($this->input->post('NRBC10', TRUE)),
		//col 11
		'Date11' => clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=>clean_form_input($this->input->post('Time11', TRUE)),

		'WBC11' => clean_form_input($this->input->post('WBC11', TRUE)),
		'RBC11' => clean_form_input($this->input->post('RBC11', TRUE)),
		'Hgb11' => clean_form_input($this->input->post('Hgb11', TRUE)),
		'Hct11' => clean_form_input($this->input->post('Hct11', TRUE)),
		'MCV11' => clean_form_input($this->input->post('MCV11', TRUE)),
		'MCH11' => clean_form_input($this->input->post('MCH11', TRUE)),
		'MCHC11' => clean_form_input($this->input->post('MCHC11', TRUE)),
		'RDWCV11' => clean_form_input($this->input->post('RDWCV11', TRUE)),
 		'Platelets11' => clean_form_input($this->input->post('Platelets11', TRUE)),
		'Neut11' => clean_form_input($this->input->post('Neut11', TRUE)),
		'Lymph11' => clean_form_input($this->input->post('Lymph11', TRUE)),
		'Mono11' => clean_form_input($this->input->post('Mono11', TRUE)),
		'Eo11' => clean_form_input($this->input->post('Eo11', TRUE)),	
		'Baso11' => clean_form_input($this->input->post('Baso11', TRUE)),
		'Pro_Mye_Jv11' => clean_form_input($this->input->post('Pro_Mye_Jv11', TRUE)),
		'Stabs11' => clean_form_input($this->input->post('Stabs11', TRUE)),
		'Blasts11' => clean_form_input($this->input->post('Blasts11', TRUE)),
		'NRBC11' => clean_form_input($this->input->post('NRBC11', TRUE)),
		//col 12

		'Date12' => clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=>clean_form_input($this->input->post('Time12', TRUE)),
		'WBC12' => clean_form_input($this->input->post('WBC12', TRUE)),
		'RBC12' => clean_form_input($this->input->post('RBC12', TRUE)),
		'Hgb12' => clean_form_input($this->input->post('Hgb12', TRUE)),
		'Hct12' => clean_form_input($this->input->post('Hct12', TRUE)),
		'MCV12' => clean_form_input($this->input->post('MCV12', TRUE)),
		'MCH12' => clean_form_input($this->input->post('MCH12', TRUE)),
		'MCHC12' => clean_form_input($this->input->post('MCHC12', TRUE)),
		'RDWCV12' => clean_form_input($this->input->post('RDWCV12', TRUE)),
 		'Platelets12' => clean_form_input($this->input->post('Platelets12', TRUE)),
		'Neut12' => clean_form_input($this->input->post('Neut12', TRUE)),
		'Lymph12' => clean_form_input($this->input->post('Lymph12', TRUE)),
		'Mono12' => clean_form_input($this->input->post('Mono12', TRUE)),
		'Eo12' => clean_form_input($this->input->post('Eo12', TRUE)),	
		'Baso12' => clean_form_input($this->input->post('Baso12', TRUE)),
		'Pro_Mye_Jv12' => clean_form_input($this->input->post('Pro_Mye_Jv12', TRUE)),
		'Stabs12' => clean_form_input($this->input->post('Stabs12', TRUE)),
		'Blasts12' => clean_form_input($this->input->post('Blasts12', TRUE)),
		'NRBC12' => clean_form_input($this->input->post('NRBC12', TRUE)),
		//col 13
		'Date13' => clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=>clean_form_input($this->input->post('Time13', TRUE)),
		'WBC13' => clean_form_input($this->input->post('WBC13', TRUE)),
		'RBC13' => clean_form_input($this->input->post('RBC13', TRUE)),
		'Hgb13' => clean_form_input($this->input->post('Hgb13', TRUE)),
		'Hct13' => clean_form_input($this->input->post('Hct13', TRUE)),
		'MCV13' => clean_form_input($this->input->post('MCV13', TRUE)),
		'MCH13' => clean_form_input($this->input->post('MCH13', TRUE)),
		'MCHC13' => clean_form_input($this->input->post('MCHC13', TRUE)),
		'RDWCV13' => clean_form_input($this->input->post('RDWCV13', TRUE)),
 		'Platelets13' => clean_form_input($this->input->post('Platelets13', TRUE)),
		'Neut13' => clean_form_input($this->input->post('Neut13', TRUE)),
		'Lymph13' => clean_form_input($this->input->post('Lymph13', TRUE)),
		'Mono13' => clean_form_input($this->input->post('Mono13', TRUE)),
		'Eo13' => clean_form_input($this->input->post('Eo13', TRUE)),	
		'Baso13' => clean_form_input($this->input->post('Baso13', TRUE)),
		'Pro_Mye_Jv13' => clean_form_input($this->input->post('Pro_Mye_Jv13', TRUE)),
		'Stabs13' => clean_form_input($this->input->post('Stabs13', TRUE)),
		'Blasts13' => clean_form_input($this->input->post('Blasts13', TRUE)),
		'NRBC13' => clean_form_input($this->input->post('NRBC13', TRUE)),
		//col 14
		'Date14' => clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=>clean_form_input($this->input->post('Time14', TRUE)),
		'WBC14' => clean_form_input($this->input->post('WBC14', TRUE)),
		'RBC14' => clean_form_input($this->input->post('RBC14', TRUE)),
		'Hgb14' => clean_form_input($this->input->post('Hgb14', TRUE)),
		'Hct14' => clean_form_input($this->input->post('Hct14', TRUE)),
		'MCV14' => clean_form_input($this->input->post('MCV14', TRUE)),
		'MCH14' => clean_form_input($this->input->post('MCH14', TRUE)),
		'MCHC14' => clean_form_input($this->input->post('MCHC14', TRUE)),
		'RDWCV14' => clean_form_input($this->input->post('RDWCV14', TRUE)),
 		'Platelets14' => clean_form_input($this->input->post('Platelets14', TRUE)),
		'Neut14' => clean_form_input($this->input->post('Neut14', TRUE)),
		'Lymph14' => clean_form_input($this->input->post('Lymph14', TRUE)),
		'Mono14' => clean_form_input($this->input->post('Mono14', TRUE)),
		'Eo14' => clean_form_input($this->input->post('Eo14', TRUE)),	
		'Baso14' => clean_form_input($this->input->post('Baso14', TRUE)),
		'Pro_Mye_Jv14' => clean_form_input($this->input->post('Pro_Mye_Jv14', TRUE)),
		'Stabs14' => clean_form_input($this->input->post('Stabs14', TRUE)),
		'Blasts14' => clean_form_input($this->input->post('Blasts14', TRUE)),
		'NRBC14' => clean_form_input($this->input->post('NRBC14', TRUE)),
		//col 15
		'Date15' => clean_form_input($this->input->post('Date15', TRUE)),
		'Time15'=>clean_form_input($this->input->post('Time15', TRUE)),
		'WBC15' => clean_form_input($this->input->post('WBC15', TRUE)),
		'RBC15' => clean_form_input($this->input->post('RBC15', TRUE)),
		'Hgb15' => clean_form_input($this->input->post('Hgb15', TRUE)),
		'Hct15' => clean_form_input($this->input->post('Hct15', TRUE)),
		'MCV15' => clean_form_input($this->input->post('MCV15', TRUE)),
		'MCH15' => clean_form_input($this->input->post('MCH15', TRUE)),
		'MCHC15' => clean_form_input($this->input->post('MCHC15', TRUE)),
		'RDWCV15' => clean_form_input($this->input->post('RDWCV15', TRUE)),
 		'Platelets15' => clean_form_input($this->input->post('Platelets15', TRUE)),
		'Neut15' => clean_form_input($this->input->post('Neut15', TRUE)),
		'Lymph15' => clean_form_input($this->input->post('Lymph15', TRUE)),
		'Mono15' => clean_form_input($this->input->post('Mono15', TRUE)),
		'Eo15' => clean_form_input($this->input->post('Eo15', TRUE)),	
		'Baso15' => clean_form_input($this->input->post('Baso15', TRUE)),
		'Pro_Mye_Jv15' => clean_form_input($this->input->post('Pro_Mye_Jv15', TRUE)),
		'Stabs15' => clean_form_input($this->input->post('Stabs15', TRUE)),
		'Blasts15' => clean_form_input($this->input->post('Blasts15', TRUE)),
		'NRBC15' => clean_form_input($this->input->post('NRBC15', TRUE)),
		);
		$cs_cbc = implode(",", $cbc);
                 $data = array(
                               'cbc'=>$cs_cbc
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit cbc id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);                

       }
       function edit_bloodchem($aid)
       {
	$bloodchem = array(
                //col 1
                'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Glucose1'=> clean_form_input($this->input->post('Glucose1', TRUE)),
		'BUN1'=> clean_form_input($this->input->post('BUN1', TRUE)),
		'Creatinine1'=> clean_form_input($this->input->post('Creatinine1', TRUE)),
		'Sodium1'=> clean_form_input($this->input->post('Sodium1', TRUE)),
		'Potassium1'=> clean_form_input($this->input->post('Potassium1', TRUE)),
		'Chloride1'=> clean_form_input($this->input->post('Chloride1', TRUE)),
		'Calcium1'=> clean_form_input($this->input->post('Calcium1', TRUE)),
		'Magnesium1'=> clean_form_input($this->input->post('Magnesium1', TRUE)),
 		'Phosphorus1'=> clean_form_input($this->input->post('Phosphorus1', TRUE)),
		'Total_Protein1'=> clean_form_input($this->input->post('Total_Protein1', TRUE)),
		'Albumin1'=> clean_form_input($this->input->post('Albumin1', TRUE)),
		'Globulin1'=> clean_form_input($this->input->post('Globulin1', TRUE)),
		'AST_SGOT1'=> clean_form_input($this->input->post('AST_SGOT1', TRUE)),
		'ALT_SGPT1'=> clean_form_input($this->input->post('ALT_SGPT1', TRUE)),
		'Alk_Phos1'=> clean_form_input($this->input->post('Alk_Phos1', TRUE)),
		'Total_Bilirubin1'=> clean_form_input($this->input->post('Total_Bilirubin1', TRUE)),
		'Direct_Bilirubin1'=> clean_form_input($this->input->post('Direct_Bilirubin1', TRUE)),
		'Indirect_Bilirubin1'=> clean_form_input($this->input->post('Indirect_Bilirubin1', TRUE)),
                'HDL1'=> clean_form_input($this->input->post('HDL1', TRUE)),
		'LDL1'=> clean_form_input($this->input->post('LDL1', TRUE)),
		'Cholesterol1'=> clean_form_input($this->input->post('Cholesterol1', TRUE)),
		'Triglycerides1'=> clean_form_input($this->input->post('Triglycerides1', TRUE)),
		'Uric_Acid1'=> clean_form_input($this->input->post('Uric_Acid1', TRUE)),
		'Amylase1'=> clean_form_input($this->input->post('Amylase1', TRUE)),
                'Lipase1'=> clean_form_input($this->input->post('Lipase1', TRUE)),
		'CK_Total1'=> clean_form_input($this->input->post('CK_Total1', TRUE)),
		'CK_MB1'=> clean_form_input($this->input->post('CK_MB1', TRUE)),
		'CK_MM1'=> clean_form_input($this->input->post('CK_MM1', TRUE)),
		'Trop_I1'=> clean_form_input($this->input->post('Trop_I1', TRUE)),
                'Myoglobin1'=> clean_form_input($this->input->post('Myoglobin1', TRUE)),

                //col 2
                'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Glucose2'=> clean_form_input($this->input->post('Glucose2', TRUE)),
		'BUN2'=> clean_form_input($this->input->post('BUN2', TRUE)),
		'Creatinine2'=> clean_form_input($this->input->post('Creatinine2', TRUE)),
		'Sodium2'=> clean_form_input($this->input->post('Sodium2', TRUE)),
		'Potassium2'=> clean_form_input($this->input->post('Potassium2', TRUE)),
		'Chloride2'=> clean_form_input($this->input->post('Chloride2', TRUE)),
		'Calcium2'=> clean_form_input($this->input->post('Calcium2', TRUE)),
		'Magnesium2'=> clean_form_input($this->input->post('Magnesium2', TRUE)),
 		'Phosphorus2'=> clean_form_input($this->input->post('Phosphorus2', TRUE)),
		'Total_Protein2'=> clean_form_input($this->input->post('Total_Protein2', TRUE)),
		'Albumin2'=> clean_form_input($this->input->post('Albumin2', TRUE)),
		'Globulin2'=> clean_form_input($this->input->post('Globulin2', TRUE)),
		'AST_SGOT2'=> clean_form_input($this->input->post('AST_SGOT2', TRUE)),
		'ALT_SGPT2'=> clean_form_input($this->input->post('ALT_SGPT2', TRUE)),
		'Alk_Phos2'=> clean_form_input($this->input->post('Alk_Phos2', TRUE)),
		'Total_Bilirubin2'=> clean_form_input($this->input->post('Total_Bilirubin2', TRUE)),
		'Direct_Bilirubin2'=> clean_form_input($this->input->post('Direct_Bilirubin2', TRUE)),
		'Indirect_Bilirubin2'=> clean_form_input($this->input->post('Indirect_Bilirubin2', TRUE)),
                'HDL2'=> clean_form_input($this->input->post('HDL2', TRUE)),
		'LDL2'=> clean_form_input($this->input->post('LDL2', TRUE)),
		'Cholesterol2'=> clean_form_input($this->input->post('Cholesterol2', TRUE)),
		'Triglycerides2'=> clean_form_input($this->input->post('Triglycerides2', TRUE)),
		'Uric_Acid2'=> clean_form_input($this->input->post('Uric_Acid2', TRUE)),
		'Amylase2'=> clean_form_input($this->input->post('Amylase2', TRUE)),
                'Lipase2'=> clean_form_input($this->input->post('Lipase2', TRUE)),
		'CK_Total2'=> clean_form_input($this->input->post('CK_Total2', TRUE)),
		'CK_MB2'=> clean_form_input($this->input->post('CK_MB2', TRUE)),
		'CK_MM2'=> clean_form_input($this->input->post('CK_MM2', TRUE)),
		'Trop_I2'=> clean_form_input($this->input->post('Trop_I2', TRUE)),
                'Myoglobin2'=> clean_form_input($this->input->post('Myoglobin2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Glucose3'=> clean_form_input($this->input->post('Glucose3', TRUE)),
		'BUN3'=> clean_form_input($this->input->post('BUN3', TRUE)),
		'Creatinine3'=> clean_form_input($this->input->post('Creatinine3', TRUE)),
		'Sodium3'=> clean_form_input($this->input->post('Sodium3', TRUE)),
		'Potassium3'=> clean_form_input($this->input->post('Potassium3', TRUE)),
		'Chloride3'=> clean_form_input($this->input->post('Chloride3', TRUE)),
		'Calcium3'=> clean_form_input($this->input->post('Calcium3', TRUE)),
		'Magnesium3'=> clean_form_input($this->input->post('Magnesium3', TRUE)),
 		'Phosphorus3'=> clean_form_input($this->input->post('Phosphorus3', TRUE)),
		'Total_Protein3'=> clean_form_input($this->input->post('Total_Protein3', TRUE)),
		'Albumin3'=> clean_form_input($this->input->post('Albumin3', TRUE)),
		'Globulin3'=> clean_form_input($this->input->post('Globulin3', TRUE)),
		'AST_SGOT3'=> clean_form_input($this->input->post('AST_SGOT3', TRUE)),
		'ALT_SGPT3'=> clean_form_input($this->input->post('ALT_SGPT3', TRUE)),
		'Alk_Phos3'=> clean_form_input($this->input->post('Alk_Phos3', TRUE)),
		'Total_Bilirubin3'=> clean_form_input($this->input->post('Total_Bilirubin3', TRUE)),
		'Direct_Bilirubin3'=> clean_form_input($this->input->post('Direct_Bilirubin3', TRUE)),
		'Indirect_Bilirubin3'=> clean_form_input($this->input->post('Indirect_Bilirubin3', TRUE)),
                'HDL3'=> clean_form_input($this->input->post('HDL3', TRUE)),
		'LDL3'=> clean_form_input($this->input->post('LDL3', TRUE)),
		'Cholesterol3'=> clean_form_input($this->input->post('Cholesterol3', TRUE)),
		'Triglycerides3'=> clean_form_input($this->input->post('Triglycerides3', TRUE)),
		'Uric_Acid3'=> clean_form_input($this->input->post('Uric_Acid3', TRUE)),
		'Amylase3'=> clean_form_input($this->input->post('Amylase3', TRUE)),
                'Lipase3'=> clean_form_input($this->input->post('Lipase3', TRUE)),
		'CK_Total3'=> clean_form_input($this->input->post('CK_Total3', TRUE)),
		'CK_MB3'=> clean_form_input($this->input->post('CK_MB3', TRUE)),
		'CK_MM3'=> clean_form_input($this->input->post('CK_MM3', TRUE)),
		'Trop_I3'=> clean_form_input($this->input->post('Trop_I3', TRUE)),
                'Myoglobin3'=> clean_form_input($this->input->post('Myoglobin3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Glucose4'=> clean_form_input($this->input->post('Glucose4', TRUE)),
		'BUN4'=> clean_form_input($this->input->post('BUN4', TRUE)),
		'Creatinine4'=> clean_form_input($this->input->post('Creatinine4', TRUE)),
		'Sodium4'=> clean_form_input($this->input->post('Sodium4', TRUE)),
		'Potassium4'=> clean_form_input($this->input->post('Potassium4', TRUE)),
		'Chloride4'=> clean_form_input($this->input->post('Chloride4', TRUE)),
		'Calcium4'=> clean_form_input($this->input->post('Calcium4', TRUE)),
		'Magnesium4'=> clean_form_input($this->input->post('Magnesium4', TRUE)),
 		'Phosphorus4'=> clean_form_input($this->input->post('Phosphorus4', TRUE)),
		'Total_Protein4'=> clean_form_input($this->input->post('Total_Protein4', TRUE)),
		'Albumin4'=> clean_form_input($this->input->post('Albumin4', TRUE)),
		'Globulin4'=> clean_form_input($this->input->post('Globulin4', TRUE)),
		'AST_SGOT4'=> clean_form_input($this->input->post('AST_SGOT4', TRUE)),
		'ALT_SGPT4'=> clean_form_input($this->input->post('ALT_SGPT4', TRUE)),
		'Alk_Phos4'=> clean_form_input($this->input->post('Alk_Phos4', TRUE)),
		'Total_Bilirubin4'=> clean_form_input($this->input->post('Total_Bilirubin4', TRUE)),
		'Direct_Bilirubin4'=> clean_form_input($this->input->post('Direct_Bilirubin4', TRUE)),
		'Indirect_Bilirubin4'=> clean_form_input($this->input->post('Indirect_Bilirubin4', TRUE)),
                'HDL4'=> clean_form_input($this->input->post('HDL4', TRUE)),
		'LDL4'=> clean_form_input($this->input->post('LDL4', TRUE)),
		'Cholesterol4'=> clean_form_input($this->input->post('Cholesterol4', TRUE)),
		'Triglycerides4'=> clean_form_input($this->input->post('Triglycerides4', TRUE)),
		'Uric_Acid4'=> clean_form_input($this->input->post('Uric_Acid4', TRUE)),
		'Amylase4'=> clean_form_input($this->input->post('Amylase4', TRUE)),
                'Lipase4'=> clean_form_input($this->input->post('Lipase4', TRUE)),
		'CK_Total4'=> clean_form_input($this->input->post('CK_Total4', TRUE)),
		'CK_MB4'=> clean_form_input($this->input->post('CK_MB4', TRUE)),
		'CK_MM4'=> clean_form_input($this->input->post('CK_MM4', TRUE)),
		'Trop_I4'=> clean_form_input($this->input->post('Trop_I4', TRUE)),
                'Myoglobin4'=> clean_form_input($this->input->post('Myoglobin4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Glucose5'=> clean_form_input($this->input->post('Glucose5', TRUE)),
		'BUN5'=> clean_form_input($this->input->post('BUN5', TRUE)),
		'Creatinine5'=> clean_form_input($this->input->post('Creatinine5', TRUE)),
		'Sodium5'=> clean_form_input($this->input->post('Sodium5', TRUE)),
		'Potassium5'=> clean_form_input($this->input->post('Potassium5', TRUE)),
		'Chloride5'=> clean_form_input($this->input->post('Chloride5', TRUE)),
		'Calcium5'=> clean_form_input($this->input->post('Calcium5', TRUE)),
		'Magnesium5'=> clean_form_input($this->input->post('Magnesium5', TRUE)),
 		'Phosphorus5'=> clean_form_input($this->input->post('Phosphorus5', TRUE)),
		'Total_Protein5'=> clean_form_input($this->input->post('Total_Protein5', TRUE)),
		'Albumin5'=> clean_form_input($this->input->post('Albumin5', TRUE)),
		'Globulin5'=> clean_form_input($this->input->post('Globulin5', TRUE)),
		'AST_SGOT5'=> clean_form_input($this->input->post('AST_SGOT5', TRUE)),
		'ALT_SGPT5'=> clean_form_input($this->input->post('ALT_SGPT5', TRUE)),
		'Alk_Phos5'=> clean_form_input($this->input->post('Alk_Phos5', TRUE)),
		'Total_Bilirubin5'=> clean_form_input($this->input->post('Total_Bilirubin5', TRUE)),
		'Direct_Bilirubin5'=> clean_form_input($this->input->post('Direct_Bilirubin5', TRUE)),
		'Indirect_Bilirubin5'=> clean_form_input($this->input->post('Indirect_Bilirubin5', TRUE)),
                'HDL5'=> clean_form_input($this->input->post('HDL5', TRUE)),
		'LDL5'=> clean_form_input($this->input->post('LDL5', TRUE)),
		'Cholesterol5'=> clean_form_input($this->input->post('Cholesterol5', TRUE)),
		'Triglycerides5'=> clean_form_input($this->input->post('Triglycerides5', TRUE)),
		'Uric_Acid5'=> clean_form_input($this->input->post('Uric_Acid5', TRUE)),
		'Amylase5'=> clean_form_input($this->input->post('Amylase5', TRUE)),
                'Lipase5'=> clean_form_input($this->input->post('Lipase5', TRUE)),
		'CK_Total5'=> clean_form_input($this->input->post('CK_Total5', TRUE)),
		'CK_MB5'=> clean_form_input($this->input->post('CK_MB5', TRUE)),
		'CK_MM5'=> clean_form_input($this->input->post('CK_MM5', TRUE)),
		'Trop_I5'=> clean_form_input($this->input->post('Trop_I5', TRUE)),
                'Myoglobin5'=> clean_form_input($this->input->post('Myoglobin5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Glucose6'=> clean_form_input($this->input->post('Glucose6', TRUE)),
		'BUN6'=> clean_form_input($this->input->post('BUN6', TRUE)),
		'Creatinine6'=> clean_form_input($this->input->post('Creatinine6', TRUE)),
		'Sodium6'=> clean_form_input($this->input->post('Sodium6', TRUE)),
		'Potassium6'=> clean_form_input($this->input->post('Potassium6', TRUE)),
		'Chloride6'=> clean_form_input($this->input->post('Chloride6', TRUE)),
		'Calcium6'=> clean_form_input($this->input->post('Calcium6', TRUE)),
		'Magnesium6'=> clean_form_input($this->input->post('Magnesium6', TRUE)),
 		'Phosphorus6'=> clean_form_input($this->input->post('Phosphorus6', TRUE)),
		'Total_Protein6'=> clean_form_input($this->input->post('Total_Protein6', TRUE)),
		'Albumin6'=> clean_form_input($this->input->post('Albumin6', TRUE)),
		'Globulin6'=> clean_form_input($this->input->post('Globulin6', TRUE)),
		'AST_SGOT6'=> clean_form_input($this->input->post('AST_SGOT6', TRUE)),
		'ALT_SGPT6'=> clean_form_input($this->input->post('ALT_SGPT6', TRUE)),
		'Alk_Phos6'=> clean_form_input($this->input->post('Alk_Phos6', TRUE)),
		'Total_Bilirubin6'=> clean_form_input($this->input->post('Total_Bilirubin6', TRUE)),
		'Direct_Bilirubin6'=> clean_form_input($this->input->post('Direct_Bilirubin6', TRUE)),
		'Indirect_Bilirubin6'=> clean_form_input($this->input->post('Indirect_Bilirubin6', TRUE)),
                'HDL6'=> clean_form_input($this->input->post('HDL6', TRUE)),
		'LDL6'=> clean_form_input($this->input->post('LDL6', TRUE)),
		'Cholesterol6'=> clean_form_input($this->input->post('Cholesterol6', TRUE)),
		'Triglycerides6'=> clean_form_input($this->input->post('Triglycerides6', TRUE)),
		'Uric_Acid6'=> clean_form_input($this->input->post('Uric_Acid6', TRUE)),
		'Amylase6'=> clean_form_input($this->input->post('Amylase6', TRUE)),
                'Lipase6'=> clean_form_input($this->input->post('Lipase6', TRUE)),
		'CK_Total6'=> clean_form_input($this->input->post('CK_Total6', TRUE)),
		'CK_MB6'=> clean_form_input($this->input->post('CK_MB6', TRUE)),
		'CK_MM6'=> clean_form_input($this->input->post('CK_MM6', TRUE)),
		'Trop_I6'=> clean_form_input($this->input->post('Trop_I6', TRUE)),
                'Myoglobin6'=> clean_form_input($this->input->post('Myoglobin6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Glucose7'=> clean_form_input($this->input->post('Glucose7', TRUE)),
		'BUN7'=> clean_form_input($this->input->post('BUN7', TRUE)),
		'Creatinine7'=> clean_form_input($this->input->post('Creatinine7', TRUE)),
		'Sodium7'=> clean_form_input($this->input->post('Sodium7', TRUE)),
		'Potassium7'=> clean_form_input($this->input->post('Potassium7', TRUE)),
		'Chloride7'=> clean_form_input($this->input->post('Chloride7', TRUE)),
		'Calcium7'=> clean_form_input($this->input->post('Calcium7', TRUE)),
		'Magnesium7'=> clean_form_input($this->input->post('Magnesium7', TRUE)),
 		'Phosphorus7'=> clean_form_input($this->input->post('Phosphorus7', TRUE)),
		'Total_Protein7'=> clean_form_input($this->input->post('Total_Protein7', TRUE)),
		'Albumin7'=> clean_form_input($this->input->post('Albumin7', TRUE)),
		'Globulin7'=> clean_form_input($this->input->post('Globulin7', TRUE)),
		'AST_SGOT7'=> clean_form_input($this->input->post('AST_SGOT7', TRUE)),
		'ALT_SGPT7'=> clean_form_input($this->input->post('ALT_SGPT7', TRUE)),
		'Alk_Phos7'=> clean_form_input($this->input->post('Alk_Phos7', TRUE)),
		'Total_Bilirubin7'=> clean_form_input($this->input->post('Total_Bilirubin7', TRUE)),
		'Direct_Bilirubin7'=> clean_form_input($this->input->post('Direct_Bilirubin7', TRUE)),
		'Indirect_Bilirubin7'=> clean_form_input($this->input->post('Indirect_Bilirubin7', TRUE)),
                'HDL7'=> clean_form_input($this->input->post('HDL7', TRUE)),
		'LDL7'=> clean_form_input($this->input->post('LDL7', TRUE)),
		'Cholesterol7'=> clean_form_input($this->input->post('Cholesterol7', TRUE)),
		'Triglycerides7'=> clean_form_input($this->input->post('Triglycerides7', TRUE)),
		'Uric_Acid7'=> clean_form_input($this->input->post('Uric_Acid7', TRUE)),
		'Amylase7'=> clean_form_input($this->input->post('Amylase7', TRUE)),
                'Lipase7'=> clean_form_input($this->input->post('Lipase7', TRUE)),
		'CK_Total7'=> clean_form_input($this->input->post('CK_Total7', TRUE)),
		'CK_MB7'=> clean_form_input($this->input->post('CK_MB7', TRUE)),
		'CK_MM7'=> clean_form_input($this->input->post('CK_MM7', TRUE)),
		'Trop_I7'=> clean_form_input($this->input->post('Trop_I7', TRUE)),
                'Myoglobin7'=> clean_form_input($this->input->post('Myoglobin7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Glucose8'=> clean_form_input($this->input->post('Glucose8', TRUE)),
		'BUN8'=> clean_form_input($this->input->post('BUN8', TRUE)),
		'Creatinine8'=> clean_form_input($this->input->post('Creatinine8', TRUE)),
		'Sodium8'=> clean_form_input($this->input->post('Sodium8', TRUE)),
		'Potassium8'=> clean_form_input($this->input->post('Potassium8', TRUE)),
		'Chloride8'=> clean_form_input($this->input->post('Chloride8', TRUE)),
		'Calcium8'=> clean_form_input($this->input->post('Calcium8', TRUE)),
		'Magnesium8'=> clean_form_input($this->input->post('Magnesium8', TRUE)),
 		'Phosphorus8'=> clean_form_input($this->input->post('Phosphorus8', TRUE)),
		'Total_Protein8'=> clean_form_input($this->input->post('Total_Protein8', TRUE)),
		'Albumin8'=> clean_form_input($this->input->post('Albumin8', TRUE)),
		'Globulin8'=> clean_form_input($this->input->post('Globulin8', TRUE)),
		'AST_SGOT8'=> clean_form_input($this->input->post('AST_SGOT8', TRUE)),
		'ALT_SGPT8'=> clean_form_input($this->input->post('ALT_SGPT8', TRUE)),
		'Alk_Phos8'=> clean_form_input($this->input->post('Alk_Phos8', TRUE)),
		'Total_Bilirubin8'=> clean_form_input($this->input->post('Total_Bilirubin8', TRUE)),
		'Direct_Bilirubin8'=> clean_form_input($this->input->post('Direct_Bilirubin8', TRUE)),
		'Indirect_Bilirubin8'=> clean_form_input($this->input->post('Indirect_Bilirubin8', TRUE)),
                'HDL8'=> clean_form_input($this->input->post('HDL8', TRUE)),
		'LDL8'=> clean_form_input($this->input->post('LDL8', TRUE)),
		'Cholesterol8'=> clean_form_input($this->input->post('Cholesterol8', TRUE)),
		'Triglycerides8'=> clean_form_input($this->input->post('Triglycerides8', TRUE)),
		'Uric_Acid8'=> clean_form_input($this->input->post('Uric_Acid8', TRUE)),
		'Amylase8'=> clean_form_input($this->input->post('Amylase8', TRUE)),
                'Lipase8'=> clean_form_input($this->input->post('Lipase8', TRUE)),
		'CK_Total8'=> clean_form_input($this->input->post('CK_Total8', TRUE)),
		'CK_MB8'=> clean_form_input($this->input->post('CK_MB8', TRUE)),
		'CK_MM8'=> clean_form_input($this->input->post('CK_MM8', TRUE)),
		'Trop_I8'=> clean_form_input($this->input->post('Trop_I8', TRUE)),
                'Myoglobin8'=> clean_form_input($this->input->post('Myoglobin8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Glucose9'=> clean_form_input($this->input->post('Glucose9', TRUE)),
		'BUN9'=> clean_form_input($this->input->post('BUN9', TRUE)),
		'Creatinine9'=> clean_form_input($this->input->post('Creatinine9', TRUE)),
		'Sodium9'=> clean_form_input($this->input->post('Sodium9', TRUE)),
		'Potassium9'=> clean_form_input($this->input->post('Potassium9', TRUE)),
		'Chloride9'=> clean_form_input($this->input->post('Chloride9', TRUE)),
		'Calcium9'=> clean_form_input($this->input->post('Calcium9', TRUE)),
		'Magnesium9'=> clean_form_input($this->input->post('Magnesium9', TRUE)),
 		'Phosphorus9'=> clean_form_input($this->input->post('Phosphorus9', TRUE)),
		'Total_Protein9'=> clean_form_input($this->input->post('Total_Protein9', TRUE)),
		'Albumin9'=> clean_form_input($this->input->post('Albumin9', TRUE)),
		'Globulin9'=> clean_form_input($this->input->post('Globulin9', TRUE)),
		'AST_SGOT9'=> clean_form_input($this->input->post('AST_SGOT9', TRUE)),
		'ALT_SGPT9'=> clean_form_input($this->input->post('ALT_SGPT9', TRUE)),
		'Alk_Phos9'=> clean_form_input($this->input->post('Alk_Phos9', TRUE)),
		'Total_Bilirubin9'=> clean_form_input($this->input->post('Total_Bilirubin9', TRUE)),
		'Direct_Bilirubin9'=> clean_form_input($this->input->post('Direct_Bilirubin9', TRUE)),
		'Indirect_Bilirubin9'=> clean_form_input($this->input->post('Indirect_Bilirubin9', TRUE)),
                'HDL9'=> clean_form_input($this->input->post('HDL9', TRUE)),
		'LDL9'=> clean_form_input($this->input->post('LDL9', TRUE)),
		'Cholesterol9'=> clean_form_input($this->input->post('Cholesterol9', TRUE)),
		'Triglycerides9'=> clean_form_input($this->input->post('Triglycerides9', TRUE)),
		'Uric_Acid9'=> clean_form_input($this->input->post('Uric_Acid9', TRUE)),
		'Amylase9'=> clean_form_input($this->input->post('Amylase9', TRUE)),
                'Lipase9'=> clean_form_input($this->input->post('Lipase9', TRUE)),
		'CK_Total9'=> clean_form_input($this->input->post('CK_Total9', TRUE)),
		'CK_MB9'=> clean_form_input($this->input->post('CK_MB9', TRUE)),
		'CK_MM9'=> clean_form_input($this->input->post('CK_MM9', TRUE)),
		'Trop_I9'=> clean_form_input($this->input->post('Trop_I9', TRUE)),
                'Myoglobin9'=> clean_form_input($this->input->post('Myoglobin9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Glucose10'=> clean_form_input($this->input->post('Glucose10', TRUE)),
		'BUN10'=> clean_form_input($this->input->post('BUN10', TRUE)),
		'Creatinine10'=> clean_form_input($this->input->post('Creatinine10', TRUE)),

		'Sodium10'=> clean_form_input($this->input->post('Sodium10', TRUE)),
		'Potassium10'=> clean_form_input($this->input->post('Potassium10', TRUE)),
		'Chloride10'=> clean_form_input($this->input->post('Chloride10', TRUE)),
		'Calcium10'=> clean_form_input($this->input->post('Calcium10', TRUE)),
		'Magnesium10'=> clean_form_input($this->input->post('Magnesium10', TRUE)),
 		'Phosphorus10'=> clean_form_input($this->input->post('Phosphorus10', TRUE)),
		'Total_Protein10'=> clean_form_input($this->input->post('Total_Protein10', TRUE)),
		'Albumin10'=> clean_form_input($this->input->post('Albumin10', TRUE)),
		'Globulin10'=> clean_form_input($this->input->post('Globulin10', TRUE)),
		'AST_SGOT10'=> clean_form_input($this->input->post('AST_SGOT10', TRUE)),
		'ALT_SGPT10'=> clean_form_input($this->input->post('ALT_SGPT10', TRUE)),
		'Alk_Phos10'=> clean_form_input($this->input->post('Alk_Phos10', TRUE)),
		'Total_Bilirubin10'=> clean_form_input($this->input->post('Total_Bilirubin10', TRUE)),
		'Direct_Bilirubin10'=> clean_form_input($this->input->post('Direct_Bilirubin10', TRUE)),
		'Indirect_Bilirubin10'=> clean_form_input($this->input->post('Indirect_Bilirubin10', TRUE)),
                'HDL10'=> clean_form_input($this->input->post('HDL10', TRUE)),
		'LDL10'=> clean_form_input($this->input->post('LDL10', TRUE)),
		'Cholesterol10'=> clean_form_input($this->input->post('Cholesterol10', TRUE)),
		'Triglycerides10'=> clean_form_input($this->input->post('Triglycerides10', TRUE)),
		'Uric_Acid10'=> clean_form_input($this->input->post('Uric_Acid10', TRUE)),
		'Amylase10'=> clean_form_input($this->input->post('Amylase10', TRUE)),
                'Lipase10'=> clean_form_input($this->input->post('Lipase10', TRUE)),
		'CK_Total10'=> clean_form_input($this->input->post('CK_Total10', TRUE)),
		'CK_MB10'=> clean_form_input($this->input->post('CK_MB10', TRUE)),
		'CK_MM10'=> clean_form_input($this->input->post('CK_MM10', TRUE)),
		'Trop_I10'=> clean_form_input($this->input->post('Trop_I10', TRUE)),
                'Myoglobin10'=> clean_form_input($this->input->post('Myoglobin10', TRUE)),
		//col 11
		'Date11'=> clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=> clean_form_input($this->input->post('Time11', TRUE)),
		'Glucose11'=> clean_form_input($this->input->post('Glucose11', TRUE)),
		'BUN11'=> clean_form_input($this->input->post('BUN11', TRUE)),
		'Creatinine11'=> clean_form_input($this->input->post('Creatinine11', TRUE)),
		'Sodium11'=> clean_form_input($this->input->post('Sodium11', TRUE)),
		'Potassium11'=> clean_form_input($this->input->post('Potassium11', TRUE)),
		'Chloride11'=> clean_form_input($this->input->post('Chloride11', TRUE)),
		'Calcium11'=> clean_form_input($this->input->post('Calcium11', TRUE)),
		'Magnesium11'=> clean_form_input($this->input->post('Magnesium11', TRUE)),
 		'Phosphorus11'=> clean_form_input($this->input->post('Phosphorus11', TRUE)),
		'Total_Protein11'=> clean_form_input($this->input->post('Total_Protein11', TRUE)),
		'Albumin11'=> clean_form_input($this->input->post('Albumin11', TRUE)),
		'Globulin11'=> clean_form_input($this->input->post('Globulin11', TRUE)),
		'AST_SGOT11'=> clean_form_input($this->input->post('AST_SGOT11', TRUE)),
		'ALT_SGPT11'=> clean_form_input($this->input->post('ALT_SGPT11', TRUE)),
		'Alk_Phos11'=> clean_form_input($this->input->post('Alk_Phos11', TRUE)),
		'Total_Bilirubin11'=> clean_form_input($this->input->post('Total_Bilirubin11', TRUE)),
		'Direct_Bilirubin11'=> clean_form_input($this->input->post('Direct_Bilirubin11', TRUE)),
		'Indirect_Bilirubin11'=> clean_form_input($this->input->post('Indirect_Bilirubin11', TRUE)),
                'HDL11'=> clean_form_input($this->input->post('HDL11', TRUE)),
		'LDL11'=> clean_form_input($this->input->post('LDL11', TRUE)),
		'Cholesterol11'=> clean_form_input($this->input->post('Cholesterol11', TRUE)),
		'Triglycerides11'=> clean_form_input($this->input->post('Triglycerides11', TRUE)),
		'Uric_Acid11'=> clean_form_input($this->input->post('Uric_Acid11', TRUE)),
		'Amylase11'=> clean_form_input($this->input->post('Amylase11', TRUE)),
                'Lipase11'=> clean_form_input($this->input->post('Lipase11', TRUE)),
		'CK_Total11'=> clean_form_input($this->input->post('CK_Total11', TRUE)),
		'CK_MB11'=> clean_form_input($this->input->post('CK_MB11', TRUE)),
		'CK_MM11'=> clean_form_input($this->input->post('CK_MM11', TRUE)),
		'Trop_I11'=> clean_form_input($this->input->post('Trop_I11', TRUE)),
                'Myoglobin11'=> clean_form_input($this->input->post('Myoglobin11', TRUE)),
		//col 12
		'Date12'=> clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=> clean_form_input($this->input->post('Time12', TRUE)),
		'Glucose12'=> clean_form_input($this->input->post('Glucose12', TRUE)),
		'BUN12'=> clean_form_input($this->input->post('BUN12', TRUE)),
		'Creatinine12'=> clean_form_input($this->input->post('Creatinine12', TRUE)),
		'Sodium12'=> clean_form_input($this->input->post('Sodium12', TRUE)),
		'Potassium12'=> clean_form_input($this->input->post('Potassium12', TRUE)),
		'Chloride12'=> clean_form_input($this->input->post('Chloride12', TRUE)),
		'Calcium12'=> clean_form_input($this->input->post('Calcium12', TRUE)),
		'Magnesium12'=> clean_form_input($this->input->post('Magnesium12', TRUE)),
 		'Phosphorus12'=> clean_form_input($this->input->post('Phosphorus12', TRUE)),
		'Total_Protein12'=> clean_form_input($this->input->post('Total_Protein12', TRUE)),
		'Albumin12'=> clean_form_input($this->input->post('Albumin12', TRUE)),
		'Globulin12'=> clean_form_input($this->input->post('Globulin12', TRUE)),
		'AST_SGOT12'=> clean_form_input($this->input->post('AST_SGOT12', TRUE)),
		'ALT_SGPT12'=> clean_form_input($this->input->post('ALT_SGPT12', TRUE)),
		'Alk_Phos12'=> clean_form_input($this->input->post('Alk_Phos12', TRUE)),
		'Total_Bilirubin12'=> clean_form_input($this->input->post('Total_Bilirubin12', TRUE)),
		'Direct_Bilirubin12'=> clean_form_input($this->input->post('Direct_Bilirubin12', TRUE)),
		'Indirect_Bilirubin12'=> clean_form_input($this->input->post('Indirect_Bilirubin12', TRUE)),
                'HDL12'=> clean_form_input($this->input->post('HDL12', TRUE)),
		'LDL12'=> clean_form_input($this->input->post('LDL12', TRUE)),
		'Cholesterol12'=> clean_form_input($this->input->post('Cholesterol12', TRUE)),
		'Triglycerides12'=> clean_form_input($this->input->post('Triglycerides12', TRUE)),
		'Uric_Acid12'=> clean_form_input($this->input->post('Uric_Acid12', TRUE)),
		'Amylase12'=> clean_form_input($this->input->post('Amylase12', TRUE)),
                'Lipase12'=> clean_form_input($this->input->post('Lipase12', TRUE)),
		'CK_Total12'=> clean_form_input($this->input->post('CK_Total12', TRUE)),
		'CK_MB12'=> clean_form_input($this->input->post('CK_MB12', TRUE)),
		'CK_MM12'=> clean_form_input($this->input->post('CK_MM12', TRUE)),
		'Trop_I12'=> clean_form_input($this->input->post('Trop_I12', TRUE)),
                'Myoglobin12'=> clean_form_input($this->input->post('Myoglobin12', TRUE)),
		//col 13
		'Date13'=> clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=> clean_form_input($this->input->post('Time13', TRUE)),
		'Glucose13'=> clean_form_input($this->input->post('Glucose13', TRUE)),
		'BUN13'=> clean_form_input($this->input->post('BUN13', TRUE)),
		'Creatinine13'=> clean_form_input($this->input->post('Creatinine13', TRUE)),
		'Sodium13'=> clean_form_input($this->input->post('Sodium13', TRUE)),
		'Potassium13'=> clean_form_input($this->input->post('Potassium13', TRUE)),
		'Chloride13'=> clean_form_input($this->input->post('Chloride13', TRUE)),
		'Calcium13'=> clean_form_input($this->input->post('Calcium13', TRUE)),
		'Magnesium13'=> clean_form_input($this->input->post('Magnesium13', TRUE)),
 		'Phosphorus13'=> clean_form_input($this->input->post('Phosphorus13', TRUE)),
		'Total_Protein13'=> clean_form_input($this->input->post('Total_Protein13', TRUE)),
		'Albumin13'=> clean_form_input($this->input->post('Albumin13', TRUE)),
		'Globulin13'=> clean_form_input($this->input->post('Globulin13', TRUE)),
		'AST_SGOT13'=> clean_form_input($this->input->post('AST_SGOT13', TRUE)),
		'ALT_SGPT13'=> clean_form_input($this->input->post('ALT_SGPT13', TRUE)),
		'Alk_Phos13'=> clean_form_input($this->input->post('Alk_Phos13', TRUE)),
		'Total_Bilirubin13'=> clean_form_input($this->input->post('Total_Bilirubin13', TRUE)),
		'Direct_Bilirubin13'=> clean_form_input($this->input->post('Direct_Bilirubin13', TRUE)),
		'Indirect_Bilirubin13'=> clean_form_input($this->input->post('Indirect_Bilirubin13', TRUE)),
                'HDL13'=> clean_form_input($this->input->post('HDL13', TRUE)),
		'LDL13'=> clean_form_input($this->input->post('LDL13', TRUE)),
		'Cholesterol13'=> clean_form_input($this->input->post('Cholesterol13', TRUE)),
		'Triglycerides13'=> clean_form_input($this->input->post('Triglycerides13', TRUE)),
		'Uric_Acid13'=> clean_form_input($this->input->post('Uric_Acid13', TRUE)),
		'Amylase13'=> clean_form_input($this->input->post('Amylase13', TRUE)),
                'Lipase13'=> clean_form_input($this->input->post('Lipase13', TRUE)),
		'CK_Total13'=> clean_form_input($this->input->post('CK_Total13', TRUE)),
		'CK_MB13'=> clean_form_input($this->input->post('CK_MB13', TRUE)),
		'CK_MM13'=> clean_form_input($this->input->post('CK_MM13', TRUE)),
		'Trop_I13'=> clean_form_input($this->input->post('Trop_I13', TRUE)),
                'Myoglobin13'=> clean_form_input($this->input->post('Myoglobin13', TRUE)),
		//col 14
		'Date14'=> clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=> clean_form_input($this->input->post('Time14', TRUE)),
		'Glucose14'=> clean_form_input($this->input->post('Glucose14', TRUE)),
		'BUN14'=> clean_form_input($this->input->post('BUN14', TRUE)),
		'Creatinine14'=> clean_form_input($this->input->post('Creatinine14', TRUE)),
		'Sodium14'=> clean_form_input($this->input->post('Sodium14', TRUE)),
		'Potassium14'=> clean_form_input($this->input->post('Potassium14', TRUE)),
		'Chloride14'=> clean_form_input($this->input->post('Chloride14', TRUE)),
		'Calcium14'=> clean_form_input($this->input->post('Calcium14', TRUE)),
		'Magnesium14'=> clean_form_input($this->input->post('Magnesium14', TRUE)),
 		'Phosphorus14'=> clean_form_input($this->input->post('Phosphorus14', TRUE)),
		'Total_Protein14'=> clean_form_input($this->input->post('Total_Protein14', TRUE)),
		'Albumin14'=> clean_form_input($this->input->post('Albumin14', TRUE)),
		'Globulin14'=> clean_form_input($this->input->post('Globulin14', TRUE)),
		'AST_SGOT14'=> clean_form_input($this->input->post('AST_SGOT14', TRUE)),
		'ALT_SGPT14'=> clean_form_input($this->input->post('ALT_SGPT14', TRUE)),
		'Alk_Phos14'=> clean_form_input($this->input->post('Alk_Phos14', TRUE)),
		'Total_Bilirubin14'=> clean_form_input($this->input->post('Total_Bilirubin14', TRUE)),
		'Direct_Bilirubin14'=> clean_form_input($this->input->post('Direct_Bilirubin14', TRUE)),
		'Indirect_Bilirubin14'=> clean_form_input($this->input->post('Indirect_Bilirubin14', TRUE)),
                'HDL14'=> clean_form_input($this->input->post('HDL14', TRUE)),
		'LDL14'=> clean_form_input($this->input->post('LDL14', TRUE)),
		'Cholesterol14'=> clean_form_input($this->input->post('Cholesterol14', TRUE)),
		'Triglycerides14'=> clean_form_input($this->input->post('Triglycerides14', TRUE)),
		'Uric_Acid14'=> clean_form_input($this->input->post('Uric_Acid14', TRUE)),
		'Amylase14'=> clean_form_input($this->input->post('Amylase14', TRUE)),
                'Lipase14'=> clean_form_input($this->input->post('Lipase14', TRUE)),
		'CK_Total14'=> clean_form_input($this->input->post('CK_Total14', TRUE)),
		'CK_MB14'=> clean_form_input($this->input->post('CK_MB14', TRUE)),
		'CK_MM14'=> clean_form_input($this->input->post('CK_MM14', TRUE)),
		'Trop_I14'=> clean_form_input($this->input->post('Trop_I14', TRUE)),
                'Myoglobin14'=> clean_form_input($this->input->post('Myoglobin14', TRUE)),
		//col 15
		'Date15'=> clean_form_input($this->input->post('Date15', TRUE)),
		'Time15'=> clean_form_input($this->input->post('Time15', TRUE)),
		'Glucose15'=> clean_form_input($this->input->post('Glucose15', TRUE)),
		'BUN15'=> clean_form_input($this->input->post('BUN15', TRUE)),
		'Creatinine15'=> clean_form_input($this->input->post('Creatinine15', TRUE)),
		'Sodium15'=> clean_form_input($this->input->post('Sodium15', TRUE)),
		'Potassium15'=> clean_form_input($this->input->post('Potassium15', TRUE)),
		'Chloride15'=> clean_form_input($this->input->post('Chloride15', TRUE)),
		'Calcium15'=> clean_form_input($this->input->post('Calcium15', TRUE)),
		'Magnesium15'=> clean_form_input($this->input->post('Magnesium15', TRUE)),
 		'Phosphorus15'=> clean_form_input($this->input->post('Phosphorus15', TRUE)),
		'Total_Protein15'=> clean_form_input($this->input->post('Total_Protein15', TRUE)),
		'Albumin15'=> clean_form_input($this->input->post('Albumin15', TRUE)),
		'Globulin15'=> clean_form_input($this->input->post('Globulin15', TRUE)),
		'AST_SGOT15'=> clean_form_input($this->input->post('AST_SGOT15', TRUE)),
		'ALT_SGPT15'=> clean_form_input($this->input->post('ALT_SGPT15', TRUE)),
		'Alk_Phos15'=> clean_form_input($this->input->post('Alk_Phos15', TRUE)),
		'Total_Bilirubin15'=> clean_form_input($this->input->post('Total_Bilirubin15', TRUE)),
		'Direct_Bilirubin15'=> clean_form_input($this->input->post('Direct_Bilirubin15', TRUE)),
		'Indirect_Bilirubin15'=> clean_form_input($this->input->post('Indirect_Bilirubin15', TRUE)),
                'HDL15'=> clean_form_input($this->input->post('HDL15', TRUE)),
		'LDL15'=> clean_form_input($this->input->post('LDL15', TRUE)),
		'Cholesterol15'=> clean_form_input($this->input->post('Cholesterol15', TRUE)),
		'Triglycerides15'=> clean_form_input($this->input->post('Triglycerides15', TRUE)),
		'Uric_Acid15'=> clean_form_input($this->input->post('Uric_Acid15', TRUE)),
		'Amylase15'=> clean_form_input($this->input->post('Amylase15', TRUE)),
                'Lipase15'=> clean_form_input($this->input->post('Lipase15', TRUE)),
		'CK_Total15'=> clean_form_input($this->input->post('CK_Total15', TRUE)),
		'CK_MB15'=> clean_form_input($this->input->post('CK_MB15', TRUE)),
		'CK_MM15'=> clean_form_input($this->input->post('CK_MM15', TRUE)),
		'Trop_I15'=> clean_form_input($this->input->post('Trop_I15', TRUE)),
                'Myoglobin15'=> clean_form_input($this->input->post('Myoglobin15', TRUE)),


                );
		$cs_bloodchem = implode(",", $bloodchem);
                $data = array(
                               'bloodchem'=>$cs_bloodchem
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit bloodchem id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);           
	}	
	function edit_protime($aid)
       	{
	 $protime = array(
                //col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Protime_Ctrl1'=> clean_form_input($this->input->post('Protime_Ctrl1', TRUE)),
		'Protime_Patient1'=> clean_form_input($this->input->post('Protime_Patient1', TRUE)),
		'Protime_Activity1'=> clean_form_input($this->input->post('Protime_Activity1', TRUE)),
		'Protime_INR1'=> clean_form_input($this->input->post('Protime_INR1', TRUE)),
		'aPTT_Ctrl1'=> clean_form_input($this->input->post('aPTT_Ctrl1', TRUE)),
		'aPTT_Patient1'=> clean_form_input($this->input->post('aPTT_Patient1', TRUE)),
                //col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Protime_Ctrl2'=> clean_form_input($this->input->post('Protime_Ctrl2', TRUE)),
		'Protime_Patient2'=> clean_form_input($this->input->post('Protime_Patient2', TRUE)),
		'Protime_Activity2'=> clean_form_input($this->input->post('Protime_Activity2', TRUE)),
		'Protime_INR2'=> clean_form_input($this->input->post('Protime_INR2', TRUE)),
		'aPTT_Ctrl2'=> clean_form_input($this->input->post('aPTT_Ctrl2', TRUE)),
		'aPTT_Patient2'=> clean_form_input($this->input->post('aPTT_Patient2', TRUE)),	
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Protime_Ctrl3'=> clean_form_input($this->input->post('Protime_Ctrl3', TRUE)),
		'Protime_Patient3'=> clean_form_input($this->input->post('Protime_Patient3', TRUE)),
		'Protime_Activity3'=> clean_form_input($this->input->post('Protime_Activity3', TRUE)),
		'Protime_INR3'=> clean_form_input($this->input->post('Protime_INR3', TRUE)),
		'aPTT_Ctrl3'=> clean_form_input($this->input->post('aPTT_Ctrl3', TRUE)),
		'aPTT_Patient3'=> clean_form_input($this->input->post('aPTT_Patient3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Protime_Ctrl4'=> clean_form_input($this->input->post('Protime_Ctrl4', TRUE)),
		'Protime_Patient4'=> clean_form_input($this->input->post('Protime_Patient4', TRUE)),
		'Protime_Activity4'=> clean_form_input($this->input->post('Protime_Activity4', TRUE)),
		'Protime_INR4'=> clean_form_input($this->input->post('Protime_INR4', TRUE)),
		'aPTT_Ctrl4'=> clean_form_input($this->input->post('aPTT_Ctrl4', TRUE)),
		'aPTT_Patient4'=> clean_form_input($this->input->post('aPTT_Patient4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Protime_Ctrl5'=> clean_form_input($this->input->post('Protime_Ctrl5', TRUE)),
		'Protime_Patient5'=> clean_form_input($this->input->post('Protime_Patient5', TRUE)),
		'Protime_Activity5'=> clean_form_input($this->input->post('Protime_Activity5', TRUE)),
		'Protime_INR5'=> clean_form_input($this->input->post('Protime_INR5', TRUE)),
		'aPTT_Ctrl5'=> clean_form_input($this->input->post('aPTT_Ctrl5', TRUE)),
		'aPTT_Patient5'=> clean_form_input($this->input->post('aPTT_Patient5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Protime_Ctrl6'=> clean_form_input($this->input->post('Protime_Ctrl6', TRUE)),
		'Protime_Patient6'=> clean_form_input($this->input->post('Protime_Patient6', TRUE)),
		'Protime_Activity6'=> clean_form_input($this->input->post('Protime_Activity6', TRUE)),
		'Protime_INR6'=> clean_form_input($this->input->post('Protime_INR6', TRUE)),
		'aPTT_Ctrl6'=> clean_form_input($this->input->post('aPTT_Ctrl6', TRUE)),
		'aPTT_Patient6'=> clean_form_input($this->input->post('aPTT_Patient6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Protime_Ctrl7'=> clean_form_input($this->input->post('Protime_Ctrl7', TRUE)),
		'Protime_Patient7'=> clean_form_input($this->input->post('Protime_Patient7', TRUE)),
		'Protime_Activity7'=> clean_form_input($this->input->post('Protime_Activity7', TRUE)),
		'Protime_INR7'=> clean_form_input($this->input->post('Protime_INR7', TRUE)),
		'aPTT_Ctrl7'=> clean_form_input($this->input->post('aPTT_Ctrl7', TRUE)),
		'aPTT_Patient7'=> clean_form_input($this->input->post('aPTT_Patient7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Protime_Ctrl8'=> clean_form_input($this->input->post('Protime_Ctrl8', TRUE)),
		'Protime_Patient8'=> clean_form_input($this->input->post('Protime_Patient8', TRUE)),
		'Protime_Activity8'=> clean_form_input($this->input->post('Protime_Activity8', TRUE)),
		'Protime_INR8'=> clean_form_input($this->input->post('Protime_INR8', TRUE)),
		'aPTT_Ctrl8'=> clean_form_input($this->input->post('aPTT_Ctrl8', TRUE)),
		'aPTT_Patient8'=> clean_form_input($this->input->post('aPTT_Patient8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Protime_Ctrl9'=> clean_form_input($this->input->post('Protime_Ctrl9', TRUE)),
		'Protime_Patient9'=> clean_form_input($this->input->post('Protime_Patient9', TRUE)),
		'Protime_Activity9'=> clean_form_input($this->input->post('Protime_Activity9', TRUE)),
		'Protime_INR9'=> clean_form_input($this->input->post('Protime_INR9', TRUE)),
		'aPTT_Ctrl9'=> clean_form_input($this->input->post('aPTT_Ctrl9', TRUE)),
		'aPTT_Patient9'=> clean_form_input($this->input->post('aPTT_Patient9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Protime_Ctrl10'=> clean_form_input($this->input->post('Protime_Ctrl10', TRUE)),
		'Protime_Patient10'=> clean_form_input($this->input->post('Protime_Patient10', TRUE)),
		'Protime_Activity10'=> clean_form_input($this->input->post('Protime_Activity10', TRUE)),
		'Protime_INR10'=> clean_form_input($this->input->post('Protime_INR10', TRUE)),
		'aPTT_Ctrl10'=> clean_form_input($this->input->post('aPTT_Ctrl10', TRUE)),
		'aPTT_Patient10'=> clean_form_input($this->input->post('aPTT_Patient10', TRUE)),
		//col 11
		'Date11'=> clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=> clean_form_input($this->input->post('Time11', TRUE)),
		'Protime_Ctrl11'=> clean_form_input($this->input->post('Protime_Ctrl11', TRUE)),
		'Protime_Patient11'=> clean_form_input($this->input->post('Protime_Patient11', TRUE)),
		'Protime_Activity11'=> clean_form_input($this->input->post('Protime_Activity11', TRUE)),
		'Protime_INR11'=> clean_form_input($this->input->post('Protime_INR11', TRUE)),
		'aPTT_Ctrl11'=> clean_form_input($this->input->post('aPTT_Ctrl11', TRUE)),
		'aPTT_Patient11'=> clean_form_input($this->input->post('aPTT_Patient11', TRUE)),
		//col 12
		'Date12'=> clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=> clean_form_input($this->input->post('Time12', TRUE)),
		'Protime_Ctrl12'=> clean_form_input($this->input->post('Protime_Ctrl12', TRUE)),
		'Protime_Patient12'=> clean_form_input($this->input->post('Protime_Patient12', TRUE)),
		'Protime_Activity12'=> clean_form_input($this->input->post('Protime_Activity12', TRUE)),
		'Protime_INR12'=> clean_form_input($this->input->post('Protime_INR12', TRUE)),
		'aPTT_Ctrl12'=> clean_form_input($this->input->post('aPTT_Ctrl12', TRUE)),
		'aPTT_Patient12'=> clean_form_input($this->input->post('aPTT_Patient12', TRUE)),
		//col 13
		'Date13'=> clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=> clean_form_input($this->input->post('Time13', TRUE)),
		'Protime_Ctrl13'=> clean_form_input($this->input->post('Protime_Ctrl13', TRUE)),
		'Protime_Patient13'=> clean_form_input($this->input->post('Protime_Patient13', TRUE)),
		'Protime_Activity13'=> clean_form_input($this->input->post('Protime_Activity13', TRUE)),
		'Protime_INR13'=> clean_form_input($this->input->post('Protime_INR13', TRUE)),
		'aPTT_Ctrl13'=> clean_form_input($this->input->post('aPTT_Ctrl13', TRUE)),
		'aPTT_Patient13'=> clean_form_input($this->input->post('aPTT_Patient13', TRUE)),	
		//col 14
		'Date14'=> clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=> clean_form_input($this->input->post('Time14', TRUE)),
		'Protime_Ctrl14'=> clean_form_input($this->input->post('Protime_Ctrl14', TRUE)),
		'Protime_Patient14'=> clean_form_input($this->input->post('Protime_Patient14', TRUE)),
		'Protime_Activity14'=> clean_form_input($this->input->post('Protime_Activity14', TRUE)),
		'Protime_INR14'=> clean_form_input($this->input->post('Protime_INR14', TRUE)),
		'aPTT_Ctrl14'=> clean_form_input($this->input->post('aPTT_Ctrl14', TRUE)),
		'aPTT_Patient14'=> clean_form_input($this->input->post('aPTT_Patient14', TRUE)),
		//col 15
		'Date15'=> clean_form_input($this->input->post('Date15', TRUE)),
		'Time15'=> clean_form_input($this->input->post('Time15', TRUE)),
		'Protime_Ctrl15'=> clean_form_input($this->input->post('Protime_Ctrl15', TRUE)),
		'Protime_Patient15'=> clean_form_input($this->input->post('Protime_Patient15', TRUE)),
		'Protime_Activity15'=> clean_form_input($this->input->post('Protime_Activity15', TRUE)),
		'Protime_INR15'=> clean_form_input($this->input->post('Protime_INR15', TRUE)),
		'aPTT_Ctrl15'=> clean_form_input($this->input->post('aPTT_Ctrl15', TRUE)),
		'aPTT_Patient15'=> clean_form_input($this->input->post('aPTT_Patient15', TRUE)),
		);
		$cs_protime = implode(",", $protime);
                $data = array(
                               'protime'=>$cs_protime
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit protime id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   


	}
	function edit_urine($aid)
       	{
	 $urine = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
                'Color1'=> clean_form_input($this->input->post('Color1', TRUE)),
		'Transparency1'=> clean_form_input($this->input->post('Transparency1', TRUE)),
		'SG1'=> clean_form_input($this->input->post('SG1', TRUE)),
		'pH1'=> clean_form_input($this->input->post('pH1', TRUE)),
		'Sugar1'=> clean_form_input($this->input->post('Sugar1', TRUE)),
		'Albumin1'=> clean_form_input($this->input->post('Albumin1', TRUE)),
		'RBC1'=> clean_form_input($this->input->post('RBC1', TRUE)),
		'WBC1'=> clean_form_input($this->input->post('WBC1', TRUE)),
		'Casts1'=> clean_form_input($this->input->post('Casts1', TRUE)),
		'Crystals1'=> clean_form_input($this->input->post('Crystals1', TRUE)),
		'Epith_Cells1'=> clean_form_input($this->input->post('Epith_Cells1', TRUE)),
		'Bacteria1'=> clean_form_input($this->input->post('Bacteria1', TRUE)),
		'Mucus_Threads1'=> clean_form_input($this->input->post('Mucus_Threads1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
                'Color2'=> clean_form_input($this->input->post('Color2', TRUE)),
		'Transparency2'=> clean_form_input($this->input->post('Transparency2', TRUE)),
		'SG2'=> clean_form_input($this->input->post('SG2', TRUE)),
		'pH2'=> clean_form_input($this->input->post('pH2', TRUE)),
		'Sugar2'=> clean_form_input($this->input->post('Sugar2', TRUE)),
		'Albumin2'=> clean_form_input($this->input->post('Albumin2', TRUE)),
		'RBC2'=> clean_form_input($this->input->post('RBC2', TRUE)),
		'WBC2'=> clean_form_input($this->input->post('WBC2', TRUE)),
		'Casts2'=> clean_form_input($this->input->post('Casts2', TRUE)),
		'Crystals2'=> clean_form_input($this->input->post('Crystals2', TRUE)),
		'Epith_Cells2'=> clean_form_input($this->input->post('Epith_Cells2', TRUE)),
		'Bacteria2'=> clean_form_input($this->input->post('Bacteria2', TRUE)),
		'Mucus_Threads2'=> clean_form_input($this->input->post('Mucus_Threads2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
                'Color3'=> clean_form_input($this->input->post('Color3', TRUE)),
		'Transparency3'=> clean_form_input($this->input->post('Transparency3', TRUE)),
		'SG3'=> clean_form_input($this->input->post('SG3', TRUE)),
		'pH3'=> clean_form_input($this->input->post('pH3', TRUE)),
		'Sugar3'=> clean_form_input($this->input->post('Sugar3', TRUE)),
		'Albumin3'=> clean_form_input($this->input->post('Albumin3', TRUE)),
		'RBC3'=> clean_form_input($this->input->post('RBC3', TRUE)),
		'WBC3'=> clean_form_input($this->input->post('WBC3', TRUE)),
		'Casts3'=> clean_form_input($this->input->post('Casts3', TRUE)),
		'Crystals3'=> clean_form_input($this->input->post('Crystals3', TRUE)),
		'Epith_Cells3'=> clean_form_input($this->input->post('Epith_Cells3', TRUE)),
		'Bacteria3'=> clean_form_input($this->input->post('Bacteria3', TRUE)),
		'Mucus_Threads3'=> clean_form_input($this->input->post('Mucus_Threads3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
                'Color4'=> clean_form_input($this->input->post('Color4', TRUE)),
		'Transparency4'=> clean_form_input($this->input->post('Transparency4', TRUE)),
		'SG4'=> clean_form_input($this->input->post('SG4', TRUE)),
		'pH4'=> clean_form_input($this->input->post('pH4', TRUE)),
		'Sugar4'=> clean_form_input($this->input->post('Sugar4', TRUE)),
		'Albumin4'=> clean_form_input($this->input->post('Albumin4', TRUE)),
		'RBC4'=> clean_form_input($this->input->post('RBC4', TRUE)),
		'WBC4'=> clean_form_input($this->input->post('WBC4', TRUE)),
		'Casts4'=> clean_form_input($this->input->post('Casts4', TRUE)),
		'Crystals4'=> clean_form_input($this->input->post('Crystals4', TRUE)),
		'Epith_Cells4'=> clean_form_input($this->input->post('Epith_Cells4', TRUE)),
		'Bacteria4'=> clean_form_input($this->input->post('Bacteria4', TRUE)),
		'Mucus_Threads4'=> clean_form_input($this->input->post('Mucus_Threads4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
                'Color5'=> clean_form_input($this->input->post('Color5', TRUE)),
		'Transparency5'=> clean_form_input($this->input->post('Transparency5', TRUE)),
		'SG5'=> clean_form_input($this->input->post('SG5', TRUE)),
		'pH5'=> clean_form_input($this->input->post('pH5', TRUE)),
		'Sugar5'=> clean_form_input($this->input->post('Sugar5', TRUE)),
		'Albumin5'=> clean_form_input($this->input->post('Albumin5', TRUE)),
		'RBC5'=> clean_form_input($this->input->post('RBC5', TRUE)),
		'WBC5'=> clean_form_input($this->input->post('WBC5', TRUE)),
		'Casts5'=> clean_form_input($this->input->post('Casts5', TRUE)),
		'Crystals5'=> clean_form_input($this->input->post('Crystals5', TRUE)),
		'Epith_Cells5'=> clean_form_input($this->input->post('Epith_Cells5', TRUE)),
		'Bacteria5'=> clean_form_input($this->input->post('Bacteria5', TRUE)),
		'Mucus_Threads5'=> clean_form_input($this->input->post('Mucus_Threads5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
                'Color6'=> clean_form_input($this->input->post('Color6', TRUE)),
		'Transparency6'=> clean_form_input($this->input->post('Transparency6', TRUE)),
		'SG6'=> clean_form_input($this->input->post('SG6', TRUE)),
		'pH6'=> clean_form_input($this->input->post('pH6', TRUE)),
		'Sugar6'=> clean_form_input($this->input->post('Sugar6', TRUE)),
		'Albumin6'=> clean_form_input($this->input->post('Albumin6', TRUE)),
		'RBC6'=> clean_form_input($this->input->post('RBC6', TRUE)),
		'WBC6'=> clean_form_input($this->input->post('WBC6', TRUE)),
		'Casts6'=> clean_form_input($this->input->post('Casts6', TRUE)),
		'Crystals6'=> clean_form_input($this->input->post('Crystals6', TRUE)),
		'Epith_Cells6'=> clean_form_input($this->input->post('Epith_Cells6', TRUE)),
		'Bacteria6'=> clean_form_input($this->input->post('Bacteria6', TRUE)),
		'Mucus_Threads6'=> clean_form_input($this->input->post('Mucus_Threads6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
                'Color7'=> clean_form_input($this->input->post('Color7', TRUE)),
		'Transparency7'=> clean_form_input($this->input->post('Transparency7', TRUE)),
		'SG7'=> clean_form_input($this->input->post('SG7', TRUE)),
		'pH7'=> clean_form_input($this->input->post('pH7', TRUE)),
		'Sugar7'=> clean_form_input($this->input->post('Sugar7', TRUE)),
		'Albumin7'=> clean_form_input($this->input->post('Albumin7', TRUE)),
		'RBC7'=> clean_form_input($this->input->post('RBC7', TRUE)),
		'WBC7'=> clean_form_input($this->input->post('WBC7', TRUE)),
		'Casts7'=> clean_form_input($this->input->post('Casts7', TRUE)),
		'Crystals7'=> clean_form_input($this->input->post('Crystals7', TRUE)),
		'Epith_Cells7'=> clean_form_input($this->input->post('Epith_Cells7', TRUE)),
		'Bacteria7'=> clean_form_input($this->input->post('Bacteria7', TRUE)),
		'Mucus_Threads7'=> clean_form_input($this->input->post('Mucus_Threads7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
                'Color8'=> clean_form_input($this->input->post('Color8', TRUE)),
		'Transparency8'=> clean_form_input($this->input->post('Transparency8', TRUE)),
		'SG8'=> clean_form_input($this->input->post('SG8', TRUE)),
		'pH8'=> clean_form_input($this->input->post('pH8', TRUE)),
		'Sugar8'=> clean_form_input($this->input->post('Sugar8', TRUE)),
		'Albumin8'=> clean_form_input($this->input->post('Albumin8', TRUE)),
		'RBC8'=> clean_form_input($this->input->post('RBC8', TRUE)),
		'WBC8'=> clean_form_input($this->input->post('WBC8', TRUE)),
		'Casts8'=> clean_form_input($this->input->post('Casts8', TRUE)),
		'Crystals8'=> clean_form_input($this->input->post('Crystals8', TRUE)),
		'Epith_Cells8'=> clean_form_input($this->input->post('Epith_Cells8', TRUE)),
		'Bacteria8'=> clean_form_input($this->input->post('Bacteria8', TRUE)),
		'Mucus_Threads8'=> clean_form_input($this->input->post('Mucus_Threads8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
                'Color9'=> clean_form_input($this->input->post('Color9', TRUE)),
		'Transparency9'=> clean_form_input($this->input->post('Transparency9', TRUE)),
		'SG9'=> clean_form_input($this->input->post('SG9', TRUE)),
		'pH9'=> clean_form_input($this->input->post('pH9', TRUE)),
		'Sugar9'=> clean_form_input($this->input->post('Sugar9', TRUE)),
		'Albumin9'=> clean_form_input($this->input->post('Albumin9', TRUE)),
		'RBC9'=> clean_form_input($this->input->post('RBC9', TRUE)),
		'WBC9'=> clean_form_input($this->input->post('WBC9', TRUE)),
		'Casts9'=> clean_form_input($this->input->post('Casts9', TRUE)),
		'Crystals9'=> clean_form_input($this->input->post('Crystals9', TRUE)),
		'Epith_Cells9'=> clean_form_input($this->input->post('Epith_Cells9', TRUE)),
		'Bacteria9'=> clean_form_input($this->input->post('Bacteria9', TRUE)),
		'Mucus_Threads9'=> clean_form_input($this->input->post('Mucus_Threads9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
                'Color10'=> clean_form_input($this->input->post('Color10', TRUE)),
		'Transparency10'=> clean_form_input($this->input->post('Transparency10', TRUE)),
		'SG10'=> clean_form_input($this->input->post('SG10', TRUE)),
		'pH10'=> clean_form_input($this->input->post('pH10', TRUE)),
		'Sugar10'=> clean_form_input($this->input->post('Sugar10', TRUE)),
		'Albumin10'=> clean_form_input($this->input->post('Albumin10', TRUE)),
		'RBC10'=> clean_form_input($this->input->post('RBC10', TRUE)),
		'WBC10'=> clean_form_input($this->input->post('WBC10', TRUE)),
		'Casts10'=> clean_form_input($this->input->post('Casts10', TRUE)),
		'Crystals10'=> clean_form_input($this->input->post('Crystals10', TRUE)),
		'Epith_Cells10'=> clean_form_input($this->input->post('Epith_Cells10', TRUE)),
		'Bacteria10'=> clean_form_input($this->input->post('Bacteria10', TRUE)),
		'Mucus_Threads10'=> clean_form_input($this->input->post('Mucus_Threads10', TRUE)),
		//col 11
		'Date11'=> clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=> clean_form_input($this->input->post('Time11', TRUE)),
                'Color11'=> clean_form_input($this->input->post('Color11', TRUE)),
		'Transparency11'=> clean_form_input($this->input->post('Transparency11', TRUE)),
		'SG11'=> clean_form_input($this->input->post('SG11', TRUE)),
		'pH11'=> clean_form_input($this->input->post('pH11', TRUE)),
		'Sugar11'=> clean_form_input($this->input->post('Sugar11', TRUE)),
		'Albumin11'=> clean_form_input($this->input->post('Albumin11', TRUE)),
		'RBC11'=> clean_form_input($this->input->post('RBC11', TRUE)),
		'WBC11'=> clean_form_input($this->input->post('WBC11', TRUE)),
		'Casts11'=> clean_form_input($this->input->post('Casts11', TRUE)),
		'Crystals11'=> clean_form_input($this->input->post('Crystals11', TRUE)),
		'Epith_Cells11'=> clean_form_input($this->input->post('Epith_Cells11', TRUE)),
		'Bacteria11'=> clean_form_input($this->input->post('Bacteria11', TRUE)),
		'Mucus_Threads11'=> clean_form_input($this->input->post('Mucus_Threads11', TRUE)),
		//col 12
		'Date12'=> clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=> clean_form_input($this->input->post('Time12', TRUE)),
                'Color12'=> clean_form_input($this->input->post('Color12', TRUE)),
		'Transparency12'=> clean_form_input($this->input->post('Transparency12', TRUE)),
		'SG12'=> clean_form_input($this->input->post('SG12', TRUE)),
		'pH12'=> clean_form_input($this->input->post('pH12', TRUE)),
		'Sugar12'=> clean_form_input($this->input->post('Sugar12', TRUE)),
		'Albumin12'=> clean_form_input($this->input->post('Albumin12', TRUE)),
		'RBC12'=> clean_form_input($this->input->post('RBC12', TRUE)),
		'WBC12'=> clean_form_input($this->input->post('WBC12', TRUE)),
		'Casts12'=> clean_form_input($this->input->post('Casts12', TRUE)),
		'Crystals12'=> clean_form_input($this->input->post('Crystals12', TRUE)),
		'Epith_Cells12'=> clean_form_input($this->input->post('Epith_Cells12', TRUE)),
		'Bacteria12'=> clean_form_input($this->input->post('Bacteria12', TRUE)),
		'Mucus_Threads12'=> clean_form_input($this->input->post('Mucus_Threads12', TRUE)),
		//col 13
		'Date13'=> clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=> clean_form_input($this->input->post('Time13', TRUE)),
                'Color13'=> clean_form_input($this->input->post('Color13', TRUE)),
		'Transparency13'=> clean_form_input($this->input->post('Transparency13', TRUE)),
		'SG13'=> clean_form_input($this->input->post('SG13', TRUE)),
		'pH13'=> clean_form_input($this->input->post('pH13', TRUE)),
		'Sugar13'=> clean_form_input($this->input->post('Sugar13', TRUE)),
		'Albumin13'=> clean_form_input($this->input->post('Albumin13', TRUE)),
		'RBC13'=> clean_form_input($this->input->post('RBC13', TRUE)),
		'WBC13'=> clean_form_input($this->input->post('WBC13', TRUE)),
		'Casts13'=> clean_form_input($this->input->post('Casts13', TRUE)),
		'Crystals13'=> clean_form_input($this->input->post('Crystals13', TRUE)),
		'Epith_Cells13'=> clean_form_input($this->input->post('Epith_Cells13', TRUE)),
		'Bacteria13'=> clean_form_input($this->input->post('Bacteria13', TRUE)),
		'Mucus_Threads13'=> clean_form_input($this->input->post('Mucus_Threads13', TRUE)),
		//col 14
		'Date14'=> clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=> clean_form_input($this->input->post('Time14', TRUE)),
                'Color14'=> clean_form_input($this->input->post('Color14', TRUE)),
		'Transparency14'=> clean_form_input($this->input->post('Transparency14', TRUE)),
		'SG14'=> clean_form_input($this->input->post('SG14', TRUE)),

		'pH14'=> clean_form_input($this->input->post('pH14', TRUE)),
		'Sugar14'=> clean_form_input($this->input->post('Sugar14', TRUE)),
		'Albumin14'=> clean_form_input($this->input->post('Albumin14', TRUE)),
		'RBC14'=> clean_form_input($this->input->post('RBC14', TRUE)),
		'WBC14'=> clean_form_input($this->input->post('WBC14', TRUE)),
		'Casts14'=> clean_form_input($this->input->post('Casts14', TRUE)),
		'Crystals14'=> clean_form_input($this->input->post('Crystals14', TRUE)),
		'Epith_Cells14'=> clean_form_input($this->input->post('Epith_Cells14', TRUE)),
		'Bacteria14'=> clean_form_input($this->input->post('Bacteria14', TRUE)),
		'Mucus_Threads14'=> clean_form_input($this->input->post('Mucus_Threads14', TRUE)),
		//col 15
		'Date15'=> clean_form_input($this->input->post('Date15', TRUE)),

		'Time15'=> clean_form_input($this->input->post('Time15', TRUE)),
                'Color15'=> clean_form_input($this->input->post('Color15', TRUE)),
		'Transparency15'=> clean_form_input($this->input->post('Transparency15', TRUE)),
		'SG15'=> clean_form_input($this->input->post('SG15', TRUE)),
		'pH15'=> clean_form_input($this->input->post('pH15', TRUE)),
		'Sugar15'=> clean_form_input($this->input->post('Sugar15', TRUE)),
		'Albumin15'=> clean_form_input($this->input->post('Albumin15', TRUE)),
		'RBC15'=> clean_form_input($this->input->post('RBC15', TRUE)),
		'WBC15'=> clean_form_input($this->input->post('WBC15', TRUE)),
		'Casts15'=> clean_form_input($this->input->post('Casts15', TRUE)),
		'Crystals15'=> clean_form_input($this->input->post('Crystals15', TRUE)),
		'Epith_Cells15'=> clean_form_input($this->input->post('Epith_Cells15', TRUE)),
		'Bacteria15'=> clean_form_input($this->input->post('Bacteria15', TRUE)),
		'Mucus_Threads15'=> clean_form_input($this->input->post('Mucus_Threads15', TRUE)),
         );


         $cs_urine = implode(",", $urine);
                $data = array(
                               'urine'=>$cs_urine
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit urinalysis id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   

	}
	function edit_abg($aid)
       	{
	 $abg = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'FiO21'=> clean_form_input($this->input->post('FiO21', TRUE)),
		'Temp1'=> clean_form_input($this->input->post('Temp1', TRUE)),
		'pH1'=> clean_form_input($this->input->post('pH1', TRUE)),
		'pCO21'=> clean_form_input($this->input->post('pCO21', TRUE)),
		'pO21'=> clean_form_input($this->input->post('pO21', TRUE)),
		'HCO31'=> clean_form_input($this->input->post('HCO31', TRUE)),
		'TCO21'=> clean_form_input($this->input->post('TCO21', TRUE)),
		'O2Sats1'=> clean_form_input($this->input->post('O2Sats1', TRUE)),
		'BE1'=> clean_form_input($this->input->post('BE1', TRUE)),
		'Na1'=> clean_form_input($this->input->post('Na1', TRUE)),
		'K1'=> clean_form_input($this->input->post('K1', TRUE)),
		'Cl1'=> clean_form_input($this->input->post('Cl1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'FiO22'=> clean_form_input($this->input->post('FiO22', TRUE)),
		'Temp2'=> clean_form_input($this->input->post('Temp2', TRUE)),
		'pH2'=> clean_form_input($this->input->post('pH2', TRUE)),
		'pCO22'=> clean_form_input($this->input->post('pCO22', TRUE)),
		'pO22'=> clean_form_input($this->input->post('pO22', TRUE)),
		'HCO32'=> clean_form_input($this->input->post('HCO32', TRUE)),
		'TCO22'=> clean_form_input($this->input->post('TCO22', TRUE)),
		'O2Sats2'=> clean_form_input($this->input->post('O2Sats2', TRUE)),
		'BE2'=> clean_form_input($this->input->post('BE2', TRUE)),
		'Na2'=> clean_form_input($this->input->post('Na2', TRUE)),
		'K2'=> clean_form_input($this->input->post('K2', TRUE)),
		'Cl2'=> clean_form_input($this->input->post('Cl2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'FiO23'=> clean_form_input($this->input->post('FiO23', TRUE)),
		'Temp3'=> clean_form_input($this->input->post('Temp3', TRUE)),
		'pH3'=> clean_form_input($this->input->post('pH3', TRUE)),
		'pCO23'=> clean_form_input($this->input->post('pCO23', TRUE)),
		'pO23'=> clean_form_input($this->input->post('pO23', TRUE)),
		'HCO33'=> clean_form_input($this->input->post('HCO33', TRUE)),
		'TCO23'=> clean_form_input($this->input->post('TCO23', TRUE)),
		'O2Sats3'=> clean_form_input($this->input->post('O2Sats3', TRUE)),
		'BE3'=> clean_form_input($this->input->post('BE3', TRUE)),
		'Na3'=> clean_form_input($this->input->post('Na3', TRUE)),
		'K3'=> clean_form_input($this->input->post('K3', TRUE)),
		'Cl3'=> clean_form_input($this->input->post('Cl3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'FiO24'=> clean_form_input($this->input->post('FiO24', TRUE)),
		'Temp4'=> clean_form_input($this->input->post('Temp4', TRUE)),
		'pH4'=> clean_form_input($this->input->post('pH4', TRUE)),
		'pCO24'=> clean_form_input($this->input->post('pCO24', TRUE)),
		'pO24'=> clean_form_input($this->input->post('pO24', TRUE)),
		'HCO34'=> clean_form_input($this->input->post('HCO34', TRUE)),
		'TCO24'=> clean_form_input($this->input->post('TCO24', TRUE)),
		'O2Sats4'=> clean_form_input($this->input->post('O2Sats4', TRUE)),
		'BE4'=> clean_form_input($this->input->post('BE4', TRUE)),
		'Na4'=> clean_form_input($this->input->post('Na4', TRUE)),
		'K4'=> clean_form_input($this->input->post('K4', TRUE)),
		'Cl4'=> clean_form_input($this->input->post('Cl4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'FiO25'=> clean_form_input($this->input->post('FiO25', TRUE)),
		'Temp5'=> clean_form_input($this->input->post('Temp5', TRUE)),
		'pH5'=> clean_form_input($this->input->post('pH5', TRUE)),
		'pCO25'=> clean_form_input($this->input->post('pCO25', TRUE)),
		'pO25'=> clean_form_input($this->input->post('pO25', TRUE)),
		'HCO35'=> clean_form_input($this->input->post('HCO35', TRUE)),
		'TCO25'=> clean_form_input($this->input->post('TCO25', TRUE)),
		'O2Sats5'=> clean_form_input($this->input->post('O2Sats5', TRUE)),
		'BE5'=> clean_form_input($this->input->post('BE5', TRUE)),
		'Na5'=> clean_form_input($this->input->post('Na5', TRUE)),
		'K5'=> clean_form_input($this->input->post('K5', TRUE)),
		'Cl5'=> clean_form_input($this->input->post('Cl5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'FiO26'=> clean_form_input($this->input->post('FiO26', TRUE)),
		'Temp6'=> clean_form_input($this->input->post('Temp6', TRUE)),
		'pH6'=> clean_form_input($this->input->post('pH6', TRUE)),
		'pCO26'=> clean_form_input($this->input->post('pCO26', TRUE)),
		'pO26'=> clean_form_input($this->input->post('pO26', TRUE)),
		'HCO36'=> clean_form_input($this->input->post('HCO36', TRUE)),
		'TCO26'=> clean_form_input($this->input->post('TCO26', TRUE)),
		'O2Sats6'=> clean_form_input($this->input->post('O2Sats6', TRUE)),
		'BE6'=> clean_form_input($this->input->post('BE6', TRUE)),
		'Na6'=> clean_form_input($this->input->post('Na6', TRUE)),
		'K6'=> clean_form_input($this->input->post('K6', TRUE)),
		'Cl6'=> clean_form_input($this->input->post('Cl6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'FiO27'=> clean_form_input($this->input->post('FiO27', TRUE)),
		'Temp7'=> clean_form_input($this->input->post('Temp7', TRUE)),
		'pH7'=> clean_form_input($this->input->post('pH7', TRUE)),
		'pCO27'=> clean_form_input($this->input->post('pCO27', TRUE)),
		'pO27'=> clean_form_input($this->input->post('pO27', TRUE)),
		'HCO37'=> clean_form_input($this->input->post('HCO37', TRUE)),
		'TCO27'=> clean_form_input($this->input->post('TCO27', TRUE)),
		'O2Sats7'=> clean_form_input($this->input->post('O2Sats7', TRUE)),
		'BE7'=> clean_form_input($this->input->post('BE7', TRUE)),
		'Na7'=> clean_form_input($this->input->post('Na7', TRUE)),
		'K7'=> clean_form_input($this->input->post('K7', TRUE)),
		'Cl7'=> clean_form_input($this->input->post('Cl7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'FiO28'=> clean_form_input($this->input->post('FiO28', TRUE)),
		'Temp8'=> clean_form_input($this->input->post('Temp8', TRUE)),
		'pH8'=> clean_form_input($this->input->post('pH8', TRUE)),
		'pCO28'=> clean_form_input($this->input->post('pCO28', TRUE)),
		'pO28'=> clean_form_input($this->input->post('pO28', TRUE)),
		'HCO38'=> clean_form_input($this->input->post('HCO38', TRUE)),
		'TCO28'=> clean_form_input($this->input->post('TCO28', TRUE)),
		'O2Sats8'=> clean_form_input($this->input->post('O2Sats8', TRUE)),
		'BE8'=> clean_form_input($this->input->post('BE8', TRUE)),
		'Na8'=> clean_form_input($this->input->post('Na8', TRUE)),
		'K8'=> clean_form_input($this->input->post('K8', TRUE)),
		'Cl8'=> clean_form_input($this->input->post('Cl8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'FiO29'=> clean_form_input($this->input->post('FiO29', TRUE)),
		'Temp9'=> clean_form_input($this->input->post('Temp9', TRUE)),
		'pH9'=> clean_form_input($this->input->post('pH9', TRUE)),
		'pCO29'=> clean_form_input($this->input->post('pCO29', TRUE)),
		'pO29'=> clean_form_input($this->input->post('pO29', TRUE)),
		'HCO39'=> clean_form_input($this->input->post('HCO39', TRUE)),
		'TCO29'=> clean_form_input($this->input->post('TCO29', TRUE)),
		'O2Sats9'=> clean_form_input($this->input->post('O2Sats9', TRUE)),
		'BE9'=> clean_form_input($this->input->post('BE9', TRUE)),
		'Na9'=> clean_form_input($this->input->post('Na9', TRUE)),
		'K9'=> clean_form_input($this->input->post('K9', TRUE)),
		'Cl9'=> clean_form_input($this->input->post('Cl9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'FiO210'=> clean_form_input($this->input->post('FiO210', TRUE)),
		'Temp10'=> clean_form_input($this->input->post('Temp10', TRUE)),
		'pH10'=> clean_form_input($this->input->post('pH10', TRUE)),
		'pCO210'=> clean_form_input($this->input->post('pCO210', TRUE)),
		'pO210'=> clean_form_input($this->input->post('pO210', TRUE)),
		'HCO310'=> clean_form_input($this->input->post('HCO310', TRUE)),
		'TCO210'=> clean_form_input($this->input->post('TCO210', TRUE)),
		'O2Sats10'=> clean_form_input($this->input->post('O2Sats10', TRUE)),
		'BE10'=> clean_form_input($this->input->post('BE10', TRUE)),
		'Na10'=> clean_form_input($this->input->post('Na10', TRUE)),
		'K10'=> clean_form_input($this->input->post('K10', TRUE)),

		'Cl10'=> clean_form_input($this->input->post('Cl10', TRUE)),
		//col 11
		'Date11'=> clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=> clean_form_input($this->input->post('Time11', TRUE)),
		'FiO211'=> clean_form_input($this->input->post('FiO211', TRUE)),
		'Temp11'=> clean_form_input($this->input->post('Temp11', TRUE)),
		'pH11'=> clean_form_input($this->input->post('pH11', TRUE)),
		'pCO211'=> clean_form_input($this->input->post('pCO211', TRUE)),
		'pO211'=> clean_form_input($this->input->post('pO211', TRUE)),
		'HCO311'=> clean_form_input($this->input->post('HCO311', TRUE)),
		'TCO211'=> clean_form_input($this->input->post('TCO211', TRUE)),
		'O2Sats11'=> clean_form_input($this->input->post('O2Sats11', TRUE)),
		'BE11'=> clean_form_input($this->input->post('BE11', TRUE)),
		'Na11'=> clean_form_input($this->input->post('Na11', TRUE)),
		'K11'=> clean_form_input($this->input->post('K11', TRUE)),
		'Cl11'=> clean_form_input($this->input->post('Cl11', TRUE)),
		//col 12
		'Date12'=> clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=> clean_form_input($this->input->post('Time12', TRUE)),
		'FiO212'=> clean_form_input($this->input->post('FiO212', TRUE)),
		'Temp12'=> clean_form_input($this->input->post('Temp12', TRUE)),
		'pH12'=> clean_form_input($this->input->post('pH12', TRUE)),
		'pCO212'=> clean_form_input($this->input->post('pCO212', TRUE)),
		'pO212'=> clean_form_input($this->input->post('pO212', TRUE)),
		'HCO312'=> clean_form_input($this->input->post('HCO312', TRUE)),
		'TCO212'=> clean_form_input($this->input->post('TCO212', TRUE)),
		'O2Sats12'=> clean_form_input($this->input->post('O2Sats12', TRUE)),
		'BE12'=> clean_form_input($this->input->post('BE12', TRUE)),
		'Na12'=> clean_form_input($this->input->post('Na12', TRUE)),
		'K12'=> clean_form_input($this->input->post('K12', TRUE)),
		'Cl12'=> clean_form_input($this->input->post('Cl12', TRUE)),
		//col 13
		'Date13'=> clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=> clean_form_input($this->input->post('Time13', TRUE)),
		'FiO213'=> clean_form_input($this->input->post('FiO213', TRUE)),
		'Temp13'=> clean_form_input($this->input->post('Temp13', TRUE)),
		'pH13'=> clean_form_input($this->input->post('pH13', TRUE)),
		'pCO213'=> clean_form_input($this->input->post('pCO213', TRUE)),
		'pO213'=> clean_form_input($this->input->post('pO213', TRUE)),
		'HCO313'=> clean_form_input($this->input->post('HCO313', TRUE)),
		'TCO213'=> clean_form_input($this->input->post('TCO213', TRUE)),
		'O2Sats13'=> clean_form_input($this->input->post('O2Sats13', TRUE)),
		'BE13'=> clean_form_input($this->input->post('BE13', TRUE)),
		'Na13'=> clean_form_input($this->input->post('Na13', TRUE)),
		'K13'=> clean_form_input($this->input->post('K13', TRUE)),
		'Cl13'=> clean_form_input($this->input->post('Cl13', TRUE)),
		//col 14
		'Date14'=> clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=> clean_form_input($this->input->post('Time14', TRUE)),
		'FiO214'=> clean_form_input($this->input->post('FiO214', TRUE)),
		'Temp14'=> clean_form_input($this->input->post('Temp14', TRUE)),
		'pH14'=> clean_form_input($this->input->post('pH14', TRUE)),
		'pCO214'=> clean_form_input($this->input->post('pCO214', TRUE)),
		'pO214'=> clean_form_input($this->input->post('pO214', TRUE)),
		'HCO314'=> clean_form_input($this->input->post('HCO314', TRUE)),
		'TCO214'=> clean_form_input($this->input->post('TCO214', TRUE)),
		'O2Sats14'=> clean_form_input($this->input->post('O2Sats14', TRUE)),
		'BE14'=> clean_form_input($this->input->post('BE14', TRUE)),
		'Na14'=> clean_form_input($this->input->post('Na14', TRUE)),
		'K14'=> clean_form_input($this->input->post('K14', TRUE)),
		'Cl14'=> clean_form_input($this->input->post('Cl14', TRUE)),
		//col 15
		'Date15'=> clean_form_input($this->input->post('Date15', TRUE)),
		'Time15'=> clean_form_input($this->input->post('Time15', TRUE)),
		'FiO215'=> clean_form_input($this->input->post('FiO215', TRUE)),
		'Temp15'=> clean_form_input($this->input->post('Temp15', TRUE)),
		'pH15'=> clean_form_input($this->input->post('pH15', TRUE)),
		'pCO215'=> clean_form_input($this->input->post('pCO215', TRUE)),
		'pO215'=> clean_form_input($this->input->post('pO215', TRUE)),
		'HCO315'=> clean_form_input($this->input->post('HCO315', TRUE)),
		'TCO215'=> clean_form_input($this->input->post('TCO215', TRUE)),
		'O2Sats15'=> clean_form_input($this->input->post('O2Sats15', TRUE)),
		'BE15'=> clean_form_input($this->input->post('BE15', TRUE)),
		'Na15'=> clean_form_input($this->input->post('Na15', TRUE)),
		'K15'=> clean_form_input($this->input->post('K15', TRUE)),
		'Cl15'=> clean_form_input($this->input->post('Cl15', TRUE)),
		);


	 $cs_abg = implode(",", $abg);
                $data = array(
                               'abg'=>$cs_abg
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit abg id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   

	}
	function edit_fecalysis($aid)
       	{
	 $fecalysis = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Appearance1'=> clean_form_input($this->input->post('Appearance1', TRUE)),
		'Ova1'=> clean_form_input($this->input->post('Ova1', TRUE)),
		'RBC1'=> clean_form_input($this->input->post('RBC1', TRUE)),
		'WBC1'=> clean_form_input($this->input->post('WBC1', TRUE)),
		'Occult_Blood1'=> clean_form_input($this->input->post('Occult_Blood1', TRUE)),
		'Others1'=> clean_form_input($this->input->post('Others1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Appearance2'=> clean_form_input($this->input->post('Appearance2', TRUE)),
		'Ova2'=> clean_form_input($this->input->post('Ova2', TRUE)),
		'RBC2'=> clean_form_input($this->input->post('RBC2', TRUE)),
		'WBC2'=> clean_form_input($this->input->post('WBC2', TRUE)),
		'Occult_Blood2'=> clean_form_input($this->input->post('Occult_Blood2', TRUE)),
		'Others2'=> clean_form_input($this->input->post('Others2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Appearance3'=> clean_form_input($this->input->post('Appearance3', TRUE)),
		'Ova3'=> clean_form_input($this->input->post('Ova3', TRUE)),
		'RBC3'=> clean_form_input($this->input->post('RBC3', TRUE)),
		'WBC3'=> clean_form_input($this->input->post('WBC3', TRUE)),
		'Occult_Blood3'=> clean_form_input($this->input->post('Occult_Blood3', TRUE)),
		'Others3'=> clean_form_input($this->input->post('Others3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Appearance4'=> clean_form_input($this->input->post('Appearance4', TRUE)),
		'Ova4'=> clean_form_input($this->input->post('Ova4', TRUE)),
		'RBC4'=> clean_form_input($this->input->post('RBC4', TRUE)),
		'WBC4'=> clean_form_input($this->input->post('WBC4', TRUE)),
		'Occult_Blood4'=> clean_form_input($this->input->post('Occult_Blood4', TRUE)),
		'Others4'=> clean_form_input($this->input->post('Others4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Appearance5'=> clean_form_input($this->input->post('Appearance5', TRUE)),
		'Ova5'=> clean_form_input($this->input->post('Ova5', TRUE)),
		'RBC5'=> clean_form_input($this->input->post('RBC5', TRUE)),
		'WBC5'=> clean_form_input($this->input->post('WBC5', TRUE)),
		'Occult_Blood5'=> clean_form_input($this->input->post('Occult_Blood5', TRUE)),
		'Others5'=> clean_form_input($this->input->post('Others5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Appearance6'=> clean_form_input($this->input->post('Appearance6', TRUE)),
		'Ova6'=> clean_form_input($this->input->post('Ova6', TRUE)),
		'RBC6'=> clean_form_input($this->input->post('RBC6', TRUE)),
		'WBC6'=> clean_form_input($this->input->post('WBC6', TRUE)),
		'Occult_Blood6'=> clean_form_input($this->input->post('Occult_Blood6', TRUE)),
		'Others6'=> clean_form_input($this->input->post('Others6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Appearance7'=> clean_form_input($this->input->post('Appearance7', TRUE)),
		'Ova7'=> clean_form_input($this->input->post('Ova7', TRUE)),
		'RBC7'=> clean_form_input($this->input->post('RBC7', TRUE)),
		'WBC7'=> clean_form_input($this->input->post('WBC7', TRUE)),
		'Occult_Blood7'=> clean_form_input($this->input->post('Occult_Blood7', TRUE)),
		'Others7'=> clean_form_input($this->input->post('Others7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Appearance8'=> clean_form_input($this->input->post('Appearance8', TRUE)),
		'Ova8'=> clean_form_input($this->input->post('Ova8', TRUE)),
		'RBC8'=> clean_form_input($this->input->post('RBC8', TRUE)),
		'WBC8'=> clean_form_input($this->input->post('WBC8', TRUE)),
		'Occult_Blood8'=> clean_form_input($this->input->post('Occult_Blood8', TRUE)),
		'Others8'=> clean_form_input($this->input->post('Others8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Appearance9'=> clean_form_input($this->input->post('Appearance9', TRUE)),
		'Ova9'=> clean_form_input($this->input->post('Ova9', TRUE)),
		'RBC9'=> clean_form_input($this->input->post('RBC9', TRUE)),
		'WBC9'=> clean_form_input($this->input->post('WBC9', TRUE)),
		'Occult_Blood9'=> clean_form_input($this->input->post('Occult_Blood9', TRUE)),
		'Others9'=> clean_form_input($this->input->post('Others9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Appearance10'=> clean_form_input($this->input->post('Appearance10', TRUE)),
		'Ova10'=> clean_form_input($this->input->post('Ova10', TRUE)),
		'RBC10'=> clean_form_input($this->input->post('RBC10', TRUE)),
		'WBC10'=> clean_form_input($this->input->post('WBC10', TRUE)),
		'Occult_Blood10'=> clean_form_input($this->input->post('Occult_Blood10', TRUE)),
		'Others10'=> clean_form_input($this->input->post('Others10', TRUE)),

		);

	 $cs_fecalysis = implode(",", $fecalysis);
                $data = array(
                               'fecalysis'=>$cs_fecalysis
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit fecalysis id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   


	}
	function edit_uchem($aid)
       	{
	 $uchem = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Total_Volume1'=> clean_form_input($this->input->post('Total_Volume1', TRUE)), 
		'Creatinine1'=> clean_form_input($this->input->post('Creatinine1', TRUE)),
		'Total_Protein1'=> clean_form_input($this->input->post('Total_Protein1', TRUE)),
		'Na1'=> clean_form_input($this->input->post('Na1', TRUE)),
		'K1'=> clean_form_input($this->input->post('K1', TRUE)),
		'Cl1'=> clean_form_input($this->input->post('Cl1', TRUE)),
		'Uric_Acid1'=> clean_form_input($this->input->post('Uric_Acid1', TRUE)),
		'Ca1'=> clean_form_input($this->input->post('Ca1', TRUE)),
		'Phosphorus1'=> clean_form_input($this->input->post('Phosphorus1', TRUE)),
		'Amylase1'=> clean_form_input($this->input->post('Amylase1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Total_Volume2'=> clean_form_input($this->input->post('Total_Volume2', TRUE)), 
		'Creatinine2'=> clean_form_input($this->input->post('Creatinine2', TRUE)),
		'Total_Protein2'=> clean_form_input($this->input->post('Total_Protein2', TRUE)),
		'Na2'=> clean_form_input($this->input->post('Na2', TRUE)),
		'K2'=> clean_form_input($this->input->post('K2', TRUE)),
		'Cl2'=> clean_form_input($this->input->post('Cl2', TRUE)),
		'Uric_Acid2'=> clean_form_input($this->input->post('Uric_Acid2', TRUE)),
		'Ca2'=> clean_form_input($this->input->post('Ca2', TRUE)),
		'Phosphorus2'=> clean_form_input($this->input->post('Phosphorus2', TRUE)),
		'Amylase2'=> clean_form_input($this->input->post('Amylase2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Total_Volume3'=> clean_form_input($this->input->post('Total_Volume3', TRUE)), 
		'Creatinine3'=> clean_form_input($this->input->post('Creatinine3', TRUE)),
		'Total_Protein3'=> clean_form_input($this->input->post('Total_Protein3', TRUE)),
		'Na3'=> clean_form_input($this->input->post('Na3', TRUE)),
		'K3'=> clean_form_input($this->input->post('K3', TRUE)),
		'Cl3'=> clean_form_input($this->input->post('Cl3', TRUE)),
		'Uric_Acid3'=> clean_form_input($this->input->post('Uric_Acid3', TRUE)),
		'Ca3'=> clean_form_input($this->input->post('Ca3', TRUE)),
		'Phosphorus3'=> clean_form_input($this->input->post('Phosphorus3', TRUE)),
		'Amylase3'=> clean_form_input($this->input->post('Amylase3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Total_Volume4'=> clean_form_input($this->input->post('Total_Volume4', TRUE)), 
		'Creatinine4'=> clean_form_input($this->input->post('Creatinine4', TRUE)),
		'Total_Protein4'=> clean_form_input($this->input->post('Total_Protein4', TRUE)),
		'Na4'=> clean_form_input($this->input->post('Na4', TRUE)),
		'K4'=> clean_form_input($this->input->post('K4', TRUE)),
		'Cl4'=> clean_form_input($this->input->post('Cl4', TRUE)),
		'Uric_Acid4'=> clean_form_input($this->input->post('Uric_Acid4', TRUE)),
		'Ca4'=> clean_form_input($this->input->post('Ca4', TRUE)),
		'Phosphorus4'=> clean_form_input($this->input->post('Phosphorus4', TRUE)),
		'Amylase4'=> clean_form_input($this->input->post('Amylase4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Total_Volume5'=> clean_form_input($this->input->post('Total_Volume5', TRUE)), 
		'Creatinine5'=> clean_form_input($this->input->post('Creatinine5', TRUE)),
		'Total_Protein5'=> clean_form_input($this->input->post('Total_Protein5', TRUE)),
		'Na5'=> clean_form_input($this->input->post('Na5', TRUE)),
		'K5'=> clean_form_input($this->input->post('K5', TRUE)),
		'Cl5'=> clean_form_input($this->input->post('Cl5', TRUE)),
		'Uric_Acid5'=> clean_form_input($this->input->post('Uric_Acid5', TRUE)),
		'Ca5'=> clean_form_input($this->input->post('Ca5', TRUE)),
		'Phosphorus5'=> clean_form_input($this->input->post('Phosphorus5', TRUE)),
		'Amylase5'=> clean_form_input($this->input->post('Amylase5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Total_Volume6'=> clean_form_input($this->input->post('Total_Volume6', TRUE)), 
		'Creatinine6'=> clean_form_input($this->input->post('Creatinine6', TRUE)),
		'Total_Protein6'=> clean_form_input($this->input->post('Total_Protein6', TRUE)),
		'Na6'=> clean_form_input($this->input->post('Na6', TRUE)),
		'K6'=> clean_form_input($this->input->post('K6', TRUE)),
		'Cl6'=> clean_form_input($this->input->post('Cl6', TRUE)),
		'Uric_Acid6'=> clean_form_input($this->input->post('Uric_Acid6', TRUE)),
		'Ca6'=> clean_form_input($this->input->post('Ca6', TRUE)),
		'Phosphorus6'=> clean_form_input($this->input->post('Phosphorus6', TRUE)),
		'Amylase6'=> clean_form_input($this->input->post('Amylase6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Total_Volume7'=> clean_form_input($this->input->post('Total_Volume7', TRUE)), 
		'Creatinine7'=> clean_form_input($this->input->post('Creatinine7', TRUE)),
		'Total_Protein7'=> clean_form_input($this->input->post('Total_Protein7', TRUE)),
		'Na7'=> clean_form_input($this->input->post('Na7', TRUE)),
		'K7'=> clean_form_input($this->input->post('K7', TRUE)),
		'Cl7'=> clean_form_input($this->input->post('Cl7', TRUE)),
		'Uric_Acid7'=> clean_form_input($this->input->post('Uric_Acid7', TRUE)),
		'Ca7'=> clean_form_input($this->input->post('Ca7', TRUE)),
		'Phosphorus7'=> clean_form_input($this->input->post('Phosphorus7', TRUE)),
		'Amylase7'=> clean_form_input($this->input->post('Amylase7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Total_Volume8'=> clean_form_input($this->input->post('Total_Volume8', TRUE)), 
		'Creatinine8'=> clean_form_input($this->input->post('Creatinine8', TRUE)),
		'Total_Protein8'=> clean_form_input($this->input->post('Total_Protein8', TRUE)),
		'Na8'=> clean_form_input($this->input->post('Na8', TRUE)),
		'K8'=> clean_form_input($this->input->post('K8', TRUE)),
		'Cl8'=> clean_form_input($this->input->post('Cl8', TRUE)),
		'Uric_Acid8'=> clean_form_input($this->input->post('Uric_Acid8', TRUE)),
		'Ca8'=> clean_form_input($this->input->post('Ca8', TRUE)),
		'Phosphorus8'=> clean_form_input($this->input->post('Phosphorus8', TRUE)),
		'Amylase8'=> clean_form_input($this->input->post('Amylase8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Total_Volume9'=> clean_form_input($this->input->post('Total_Volume9', TRUE)), 
		'Creatinine9'=> clean_form_input($this->input->post('Creatinine9', TRUE)),
		'Total_Protein9'=> clean_form_input($this->input->post('Total_Protein9', TRUE)),
		'Na9'=> clean_form_input($this->input->post('Na9', TRUE)),
		'K9'=> clean_form_input($this->input->post('K9', TRUE)),
		'Cl9'=> clean_form_input($this->input->post('Cl9', TRUE)),
		'Uric_Acid9'=> clean_form_input($this->input->post('Uric_Acid9', TRUE)),
		'Ca9'=> clean_form_input($this->input->post('Ca9', TRUE)),
		'Phosphorus9'=> clean_form_input($this->input->post('Phosphorus9', TRUE)),
		'Amylase9'=> clean_form_input($this->input->post('Amylase9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Total_Volume10'=> clean_form_input($this->input->post('Total_Volume10', TRUE)), 
		'Creatinine10'=> clean_form_input($this->input->post('Creatinine10', TRUE)),
		'Total_Protein10'=> clean_form_input($this->input->post('Total_Protein10', TRUE)),
		'Na10'=> clean_form_input($this->input->post('Na10', TRUE)),
		'K10'=> clean_form_input($this->input->post('K10', TRUE)),
		'Cl10'=> clean_form_input($this->input->post('Cl10', TRUE)),
		'Uric_Acid10'=> clean_form_input($this->input->post('Uric_Acid10', TRUE)),
		'Ca10'=> clean_form_input($this->input->post('Ca10', TRUE)),
		'Phosphorus10'=> clean_form_input($this->input->post('Phosphorus10', TRUE)),
		'Amylase10'=> clean_form_input($this->input->post('Amylase10', TRUE)),

		);
	 $cs_uchem = implode(",", $uchem);
                $data = array(
                               'uchem'=>$cs_uchem
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit uchem id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   
	}
	function edit_culture($aid)
       	{
	 $culture = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Specimen1'=> clean_form_input($this->input->post('Specimen1', TRUE)),
		'PMN1'=> clean_form_input($this->input->post('PMN1', TRUE)),
		'Epith_Cell1'=> clean_form_input($this->input->post('Epith_Cell1', TRUE)),
		'Gram_Stain1'=> clean_form_input($this->input->post('Gram_Stain1', TRUE)),
		'Growth1'=> clean_form_input($this->input->post('Growth1', TRUE)),
		'Organisms1'=> clean_form_input($this->input->post('Organisms1', TRUE)),
		'Susceptible1'=> clean_form_input($this->input->post('Susceptible1', TRUE)),
		'Intermediate1'=> clean_form_input($this->input->post('Intermediate1', TRUE)),
		'Resistant1'=> clean_form_input($this->input->post('Resistant1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Specimen2'=> clean_form_input($this->input->post('Specimen2', TRUE)),
		'PMN2'=> clean_form_input($this->input->post('PMN2', TRUE)),
		'Epith_Cell2'=> clean_form_input($this->input->post('Epith_Cell2', TRUE)),
		'Gram_Stain2'=> clean_form_input($this->input->post('Gram_Stain2', TRUE)),
		'Growth2'=> clean_form_input($this->input->post('Growth2', TRUE)),
		'Organisms2'=> clean_form_input($this->input->post('Organisms2', TRUE)),
		'Susceptible2'=> clean_form_input($this->input->post('Susceptible2', TRUE)),
		'Intermediate2'=> clean_form_input($this->input->post('Intermediate2', TRUE)),
		'Resistant2'=> clean_form_input($this->input->post('Resistant2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Specimen3'=> clean_form_input($this->input->post('Specimen3', TRUE)),
		'PMN3'=> clean_form_input($this->input->post('PMN3', TRUE)),
		'Epith_Cell3'=> clean_form_input($this->input->post('Epith_Cell3', TRUE)),
		'Gram_Stain3'=> clean_form_input($this->input->post('Gram_Stain3', TRUE)),
		'Growth3'=> clean_form_input($this->input->post('Growth3', TRUE)),
		'Organisms3'=> clean_form_input($this->input->post('Organisms3', TRUE)),
		'Susceptible3'=> clean_form_input($this->input->post('Susceptible3', TRUE)),
		'Intermediate3'=> clean_form_input($this->input->post('Intermediate3', TRUE)),
		'Resistant3'=> clean_form_input($this->input->post('Resistant3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Specimen4'=> clean_form_input($this->input->post('Specimen4', TRUE)),
		'PMN4'=> clean_form_input($this->input->post('PMN4', TRUE)),
		'Epith_Cell4'=> clean_form_input($this->input->post('Epith_Cell4', TRUE)),
		'Gram_Stain4'=> clean_form_input($this->input->post('Gram_Stain4', TRUE)),
		'Growth4'=> clean_form_input($this->input->post('Growth4', TRUE)),
		'Organisms4'=> clean_form_input($this->input->post('Organisms4', TRUE)),
		'Susceptible4'=> clean_form_input($this->input->post('Susceptible4', TRUE)),
		'Intermediate4'=> clean_form_input($this->input->post('Intermediate4', TRUE)),
		'Resistant4'=> clean_form_input($this->input->post('Resistant4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Specimen5'=> clean_form_input($this->input->post('Specimen5', TRUE)),
		'PMN5'=> clean_form_input($this->input->post('PMN5', TRUE)),
		'Epith_Cell5'=> clean_form_input($this->input->post('Epith_Cell5', TRUE)),
		'Gram_Stain5'=> clean_form_input($this->input->post('Gram_Stain5', TRUE)),
		'Growth5'=> clean_form_input($this->input->post('Growth5', TRUE)),
		'Organisms5'=> clean_form_input($this->input->post('Organisms5', TRUE)),
		'Susceptible5'=> clean_form_input($this->input->post('Susceptible5', TRUE)),
		'Intermediate5'=> clean_form_input($this->input->post('Intermediate5', TRUE)),
		'Resistant5'=> clean_form_input($this->input->post('Resistant5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Specimen6'=> clean_form_input($this->input->post('Specimen6', TRUE)),
		'PMN6'=> clean_form_input($this->input->post('PMN6', TRUE)),
		'Epith_Cell6'=> clean_form_input($this->input->post('Epith_Cell6', TRUE)),
		'Gram_Stain6'=> clean_form_input($this->input->post('Gram_Stain6', TRUE)),
		'Growth6'=> clean_form_input($this->input->post('Growth6', TRUE)),
		'Organisms6'=> clean_form_input($this->input->post('Organisms6', TRUE)),
		'Susceptible6'=> clean_form_input($this->input->post('Susceptible6', TRUE)),
		'Intermediate6'=> clean_form_input($this->input->post('Intermediate6', TRUE)),
		'Resistant6'=> clean_form_input($this->input->post('Resistant6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Specimen7'=> clean_form_input($this->input->post('Specimen7', TRUE)),
		'PMN7'=> clean_form_input($this->input->post('PMN7', TRUE)),
		'Epith_Cell7'=> clean_form_input($this->input->post('Epith_Cell7', TRUE)),
		'Gram_Stain7'=> clean_form_input($this->input->post('Gram_Stain7', TRUE)),
		'Growth7'=> clean_form_input($this->input->post('Growth7', TRUE)),
		'Organisms7'=> clean_form_input($this->input->post('Organisms7', TRUE)),
		'Susceptible7'=> clean_form_input($this->input->post('Susceptible7', TRUE)),
		'Intermediate7'=> clean_form_input($this->input->post('Intermediate7', TRUE)),
		'Resistant7'=> clean_form_input($this->input->post('Resistant7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Specimen8'=> clean_form_input($this->input->post('Specimen8', TRUE)),
		'PMN8'=> clean_form_input($this->input->post('PMN8', TRUE)),
		'Epith_Cell8'=> clean_form_input($this->input->post('Epith_Cell8', TRUE)),
		'Gram_Stain8'=> clean_form_input($this->input->post('Gram_Stain8', TRUE)),
		'Growth8'=> clean_form_input($this->input->post('Growth8', TRUE)),
		'Organisms8'=> clean_form_input($this->input->post('Organisms8', TRUE)),
		'Susceptible8'=> clean_form_input($this->input->post('Susceptible8', TRUE)),
		'Intermediate8'=> clean_form_input($this->input->post('Intermediate8', TRUE)),
		'Resistant8'=> clean_form_input($this->input->post('Resistant8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Specimen9'=> clean_form_input($this->input->post('Specimen9', TRUE)),
		'PMN9'=> clean_form_input($this->input->post('PMN9', TRUE)),
		'Epith_Cell9'=> clean_form_input($this->input->post('Epith_Cell9', TRUE)),
		'Gram_Stain9'=> clean_form_input($this->input->post('Gram_Stain9', TRUE)),
		'Growth9'=> clean_form_input($this->input->post('Growth9', TRUE)),
		'Organisms9'=> clean_form_input($this->input->post('Organisms9', TRUE)),
		'Susceptible9'=> clean_form_input($this->input->post('Susceptible9', TRUE)),
		'Intermediate9'=> clean_form_input($this->input->post('Intermediate9', TRUE)),
		'Resistant9'=> clean_form_input($this->input->post('Resistant9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Specimen10'=> clean_form_input($this->input->post('Specimen10', TRUE)),
		'PMN10'=> clean_form_input($this->input->post('PMN10', TRUE)),
		'Epith_Cell10'=> clean_form_input($this->input->post('Epith_Cell10', TRUE)),
		'Gram_Stain10'=> clean_form_input($this->input->post('Gram_Stain10', TRUE)),
		'Growth10'=> clean_form_input($this->input->post('Growth10', TRUE)),
		'Organisms10'=> clean_form_input($this->input->post('Organisms10', TRUE)),
		'Susceptible10'=> clean_form_input($this->input->post('Susceptible10', TRUE)),
		'Intermediate10'=> clean_form_input($this->input->post('Intermediate10', TRUE)),
		'Resistant10'=> clean_form_input($this->input->post('Resistant10', TRUE)),

		);
	 $cs_culture = implode(",", $culture);
                $data = array(
                               'culture'=>$cs_culture
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit culture id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   

	 
	}
	function edit_imaging($aid)
       	{
	 $imaging = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Test1'=> clean_form_input($this->input->post('Test1', TRUE)),
		'Body_Part1'=> clean_form_input($this->input->post('Body_Part1', TRUE)),
		'Reading1'=> clean_form_input($this->input->post('Reading1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Test2'=> clean_form_input($this->input->post('Test2', TRUE)),
		'Body_Part2'=> clean_form_input($this->input->post('Body_Part2', TRUE)),
		'Reading2'=> clean_form_input($this->input->post('Reading2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Test3'=> clean_form_input($this->input->post('Test3', TRUE)),
		'Body_Part3'=> clean_form_input($this->input->post('Body_Part3', TRUE)),
		'Reading3'=> clean_form_input($this->input->post('Reading3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Test4'=> clean_form_input($this->input->post('Test4', TRUE)),
		'Body_Part4'=> clean_form_input($this->input->post('Body_Part4', TRUE)),
		'Reading4'=> clean_form_input($this->input->post('Reading4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Test5'=> clean_form_input($this->input->post('Test5', TRUE)),
		'Body_Part5'=> clean_form_input($this->input->post('Body_Part5', TRUE)),
		'Reading5'=> clean_form_input($this->input->post('Reading5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Test6'=> clean_form_input($this->input->post('Test6', TRUE)),
		'Body_Part6'=> clean_form_input($this->input->post('Body_Part6', TRUE)),
		'Reading6'=> clean_form_input($this->input->post('Reading6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),

		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Test7'=> clean_form_input($this->input->post('Test7', TRUE)),
		'Body_Part7'=> clean_form_input($this->input->post('Body_Part7', TRUE)),
		'Reading7'=> clean_form_input($this->input->post('Reading7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Test8'=> clean_form_input($this->input->post('Test8', TRUE)),
		'Body_Part8'=> clean_form_input($this->input->post('Body_Part8', TRUE)),
		'Reading8'=> clean_form_input($this->input->post('Reading8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Test9'=> clean_form_input($this->input->post('Test9', TRUE)),
		'Body_Part9'=> clean_form_input($this->input->post('Body_Part9', TRUE)),
		'Reading9'=> clean_form_input($this->input->post('Reading9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Test10'=> clean_form_input($this->input->post('Test10', TRUE)),
		'Body_Part10'=> clean_form_input($this->input->post('Body_Part10', TRUE)),
		'Reading10'=> clean_form_input($this->input->post('Reading10', TRUE)),
		//col 11
		'Date11'=> clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=> clean_form_input($this->input->post('Time11', TRUE)),
		'Test11'=> clean_form_input($this->input->post('Test11', TRUE)),
		'Body_Part11'=> clean_form_input($this->input->post('Body_Part11', TRUE)),
		'Reading11'=> clean_form_input($this->input->post('Reading11', TRUE)),
		//col 12
		'Date12'=> clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=> clean_form_input($this->input->post('Time12', TRUE)),
		'Test12'=> clean_form_input($this->input->post('Test12', TRUE)),
		'Body_Part12'=> clean_form_input($this->input->post('Body_Part12', TRUE)),
		'Reading12'=> clean_form_input($this->input->post('Reading12', TRUE)),
		//col 13
		'Date13'=> clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=> clean_form_input($this->input->post('Time13', TRUE)),
		'Test13'=> clean_form_input($this->input->post('Test13', TRUE)),
		'Body_Part13'=> clean_form_input($this->input->post('Body_Part13', TRUE)),
		'Reading13'=> clean_form_input($this->input->post('Reading13', TRUE)),
		//col 14
		'Date14'=> clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=> clean_form_input($this->input->post('Time14', TRUE)),
		'Test14'=> clean_form_input($this->input->post('Test14', TRUE)),
		'Body_Part14'=> clean_form_input($this->input->post('Body_Part14', TRUE)),
		'Reading14'=> clean_form_input($this->input->post('Reading14', TRUE)),
		//col 15
		'Date15'=> clean_form_input($this->input->post('Date15', TRUE)),
		'Time15'=> clean_form_input($this->input->post('Time15', TRUE)),
		'Test15'=> clean_form_input($this->input->post('Test15', TRUE)),
		'Body_Part15'=> clean_form_input($this->input->post('Body_Part15', TRUE)),
		'Reading15'=> clean_form_input($this->input->post('Reading15', TRUE)),

		);
	  $cs_imaging = implode(",", $imaging);
                $data = array(
                               'imaging'=>$cs_imaging
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit imaging id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   
	 }
	function edit_ecg($aid)
       	{
	 $ecg = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Test1'=> clean_form_input($this->input->post('Test1', TRUE)),
		'Reading1'=> clean_form_input($this->input->post('Reading1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Test2'=> clean_form_input($this->input->post('Test2', TRUE)),
		'Reading2'=> clean_form_input($this->input->post('Reading2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Test3'=> clean_form_input($this->input->post('Test3', TRUE)),
		'Reading3'=> clean_form_input($this->input->post('Reading3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Test4'=> clean_form_input($this->input->post('Test4', TRUE)),
		'Reading4'=> clean_form_input($this->input->post('Reading4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Test5'=> clean_form_input($this->input->post('Test5', TRUE)),
		'Reading5'=> clean_form_input($this->input->post('Reading5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Test6'=> clean_form_input($this->input->post('Test6', TRUE)),
		'Reading6'=> clean_form_input($this->input->post('Reading6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Test7'=> clean_form_input($this->input->post('Test7', TRUE)),
		'Reading7'=> clean_form_input($this->input->post('Reading7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Test8'=> clean_form_input($this->input->post('Test8', TRUE)),
		'Reading8'=> clean_form_input($this->input->post('Reading8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Test9'=> clean_form_input($this->input->post('Test9', TRUE)),
		'Reading9'=> clean_form_input($this->input->post('Reading9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Test10'=> clean_form_input($this->input->post('Test10', TRUE)),
		'Reading10'=> clean_form_input($this->input->post('Reading10', TRUE)),
		//col 11
		'Date11'=> clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=> clean_form_input($this->input->post('Time11', TRUE)),
		'Test11'=> clean_form_input($this->input->post('Test11', TRUE)),
		'Reading11'=> clean_form_input($this->input->post('Reading11', TRUE)),
		//col 12
		'Date12'=> clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=> clean_form_input($this->input->post('Time12', TRUE)),
		'Test12'=> clean_form_input($this->input->post('Test12', TRUE)),
		'Reading12'=> clean_form_input($this->input->post('Reading12', TRUE)),
		//col 13
		'Date13'=> clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=> clean_form_input($this->input->post('Time13', TRUE)),
		'Test13'=> clean_form_input($this->input->post('Test13', TRUE)),
		'Reading13'=> clean_form_input($this->input->post('Reading13', TRUE)),
		//col 14
		'Date14'=> clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=> clean_form_input($this->input->post('Time14', TRUE)),
		'Test14'=> clean_form_input($this->input->post('Test14', TRUE)),
		'Reading14'=> clean_form_input($this->input->post('Reading14', TRUE)),
		//col 15
		'Date15'=> clean_form_input($this->input->post('Date15', TRUE)),
		'Time15'=> clean_form_input($this->input->post('Time15', TRUE)),
		'Test15'=> clean_form_input($this->input->post('Test15', TRUE)),

		'Reading15'=> clean_form_input($this->input->post('Reading15', TRUE)),
		);
	  $cs_ecg = implode(",", $ecg);
                $data = array(
                               'ecg'=>$cs_ecg
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit ecg id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   
	 }
	 function edit_others($aid)
       	{
	 $others = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Test1'=> clean_form_input($this->input->post('Test1', TRUE)),
		'Results1'=> clean_form_input($this->input->post('Results1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Test2'=> clean_form_input($this->input->post('Test2', TRUE)),
		'Results2'=> clean_form_input($this->input->post('Results2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Test3'=> clean_form_input($this->input->post('Test3', TRUE)),
		'Results3'=> clean_form_input($this->input->post('Results3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Test4'=> clean_form_input($this->input->post('Test4', TRUE)),
		'Results4'=> clean_form_input($this->input->post('Results4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Test5'=> clean_form_input($this->input->post('Test5', TRUE)),
		'Results5'=> clean_form_input($this->input->post('Results5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Test6'=> clean_form_input($this->input->post('Test6', TRUE)),
		'Results6'=> clean_form_input($this->input->post('Results6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Test7'=> clean_form_input($this->input->post('Test7', TRUE)),
		'Results7'=> clean_form_input($this->input->post('Results7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Test8'=> clean_form_input($this->input->post('Test8', TRUE)),
		'Results8'=> clean_form_input($this->input->post('Results8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Test9'=> clean_form_input($this->input->post('Test9', TRUE)),
		'Results9'=> clean_form_input($this->input->post('Results9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Test10'=> clean_form_input($this->input->post('Test10', TRUE)),
		'Results10'=> clean_form_input($this->input->post('Results10', TRUE)),
		//col 11
		'Date11'=> clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=> clean_form_input($this->input->post('Time11', TRUE)),
		'Test11'=> clean_form_input($this->input->post('Test11', TRUE)),
		'Results11'=> clean_form_input($this->input->post('Results11', TRUE)),
		//col 12
		'Date12'=> clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=> clean_form_input($this->input->post('Time12', TRUE)),
		'Test12'=> clean_form_input($this->input->post('Test12', TRUE)),
		'Results12'=> clean_form_input($this->input->post('Results12', TRUE)),
		//col 13
		'Date13'=> clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=> clean_form_input($this->input->post('Time13', TRUE)),
		'Test13'=> clean_form_input($this->input->post('Test13', TRUE)),
		'Results13'=> clean_form_input($this->input->post('Results13', TRUE)),
		//col 14
		'Date14'=> clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=> clean_form_input($this->input->post('Time14', TRUE)),
		'Test14'=> clean_form_input($this->input->post('Test14', TRUE)),
		'Results14'=> clean_form_input($this->input->post('Results14', TRUE)),
		//col 15
		'Date15'=> clean_form_input($this->input->post('Date15', TRUE)),
		'Time15'=> clean_form_input($this->input->post('Time15', TRUE)),
		'Test15'=> clean_form_input($this->input->post('Test15', TRUE)),
		'Results15'=> clean_form_input($this->input->post('Results15', TRUE)),
		//col 16
		'Date16'=> clean_form_input($this->input->post('Date16', TRUE)),
		'Time16'=> clean_form_input($this->input->post('Time16', TRUE)),
		'Test16'=> clean_form_input($this->input->post('Test16', TRUE)),
		'Results16'=> clean_form_input($this->input->post('Results16', TRUE)),
		//col 17
		'Date17'=> clean_form_input($this->input->post('Date17', TRUE)),
		'Time17'=> clean_form_input($this->input->post('Time17', TRUE)),
		'Test17'=> clean_form_input($this->input->post('Test17', TRUE)),
		'Results17'=> clean_form_input($this->input->post('Results17', TRUE)),
		//col 18
		'Date18'=> clean_form_input($this->input->post('Date18', TRUE)),
		'Time18'=> clean_form_input($this->input->post('Time18', TRUE)),
		'Test18'=> clean_form_input($this->input->post('Test18', TRUE)),
		'Results18'=> clean_form_input($this->input->post('Results18', TRUE)),
		//col 19
		'Date19'=> clean_form_input($this->input->post('Date19', TRUE)),
		'Time19'=> clean_form_input($this->input->post('Time19', TRUE)),
		'Test19'=> clean_form_input($this->input->post('Test19', TRUE)),
		'Results19'=> clean_form_input($this->input->post('Results19', TRUE)),
		//col 20
		'Date20'=> clean_form_input($this->input->post('Date20', TRUE)),
		'Time20'=> clean_form_input($this->input->post('Time20', TRUE)),
		'Test20'=> clean_form_input($this->input->post('Test20', TRUE)),
		'Results20'=> clean_form_input($this->input->post('Results20', TRUE)),
		//col 21
		'Date21'=> clean_form_input($this->input->post('Date21', TRUE)),
		'Time21'=> clean_form_input($this->input->post('Time21', TRUE)),
		'Test21'=> clean_form_input($this->input->post('Test21', TRUE)),
		'Results21'=> clean_form_input($this->input->post('Results21', TRUE)),
		//col 22
		'Date22'=> clean_form_input($this->input->post('Date22', TRUE)),
		'Time22'=> clean_form_input($this->input->post('Time22', TRUE)),
		'Test22'=> clean_form_input($this->input->post('Test22', TRUE)),
		'Results22'=> clean_form_input($this->input->post('Results22', TRUE)),
		//col 23
		'Date23'=> clean_form_input($this->input->post('Date23', TRUE)),
		'Time23'=> clean_form_input($this->input->post('Time23', TRUE)),
		'Test23'=> clean_form_input($this->input->post('Test23', TRUE)),
		'Results23'=> clean_form_input($this->input->post('Results23', TRUE)),
		//col 24
		'Date24'=> clean_form_input($this->input->post('Date24', TRUE)),
		'Time24'=> clean_form_input($this->input->post('Time24', TRUE)),
		'Test24'=> clean_form_input($this->input->post('Test24', TRUE)),
		'Results24'=> clean_form_input($this->input->post('Results24', TRUE)),
		//col 25
		'Date25'=> clean_form_input($this->input->post('Date25', TRUE)),
		'Time25'=> clean_form_input($this->input->post('Time25', TRUE)),
		'Test25'=> clean_form_input($this->input->post('Test25', TRUE)),
		'Results25'=> clean_form_input($this->input->post('Results25', TRUE)),
		//col 26
		'Date26'=> clean_form_input($this->input->post('Date26', TRUE)),
		'Time26'=> clean_form_input($this->input->post('Time26', TRUE)),
		'Test26'=> clean_form_input($this->input->post('Test26', TRUE)),
		'Results26'=> clean_form_input($this->input->post('Results26', TRUE)),
		//col 27
		'Date27'=> clean_form_input($this->input->post('Date27', TRUE)),
		'Time27'=> clean_form_input($this->input->post('Time27', TRUE)),
		'Test27'=> clean_form_input($this->input->post('Test27', TRUE)),
		'Results27'=> clean_form_input($this->input->post('Results27', TRUE)),
		//col 28
		'Date28'=> clean_form_input($this->input->post('Date28', TRUE)),
		'Time28'=> clean_form_input($this->input->post('Time28', TRUE)),
		'Test28'=> clean_form_input($this->input->post('Test28', TRUE)),
		'Results28'=> clean_form_input($this->input->post('Results28', TRUE)),
		//col 29
		'Date29'=> clean_form_input($this->input->post('Date29', TRUE)),
		'Time29'=> clean_form_input($this->input->post('Time29', TRUE)),
		'Test29'=> clean_form_input($this->input->post('Test29', TRUE)),
		'Results29'=> clean_form_input($this->input->post('Results29', TRUE)),
		//col 30
		'Date30'=> clean_form_input($this->input->post('Date30', TRUE)),
		'Time30'=> clean_form_input($this->input->post('Time30', TRUE)),
		'Test30'=> clean_form_input($this->input->post('Test30', TRUE)),
		'Results30'=> clean_form_input($this->input->post('Results30', TRUE)),

		);
	  $cs_others = implode(",", $others);
                $data = array(
                               'others'=>$cs_others
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit others id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   

	}


//edit labs #2
        function edit_cbc2($aid)
       {
	$cbc = array(
                //col 1
        	'Date1' => clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=>clean_form_input($this->input->post('Time1', TRUE)),
		'WBC1' => clean_form_input($this->input->post('WBC1', TRUE)),
		'RBC1' => clean_form_input($this->input->post('RBC1', TRUE)),
		'Hgb1' => clean_form_input($this->input->post('Hgb1', TRUE)),
		'Hct1' => clean_form_input($this->input->post('Hct1', TRUE)),
		'MCV1' => clean_form_input($this->input->post('MCV1', TRUE)),
		'MCH1' => clean_form_input($this->input->post('MCH1', TRUE)),
		'MCHC1' => clean_form_input($this->input->post('MCHC1', TRUE)),
		'RDWCV1' => clean_form_input($this->input->post('RDWCV1', TRUE)),
 		'Platelets1' => clean_form_input($this->input->post('Platelets1', TRUE)),
		'Neut1' => clean_form_input($this->input->post('Neut1', TRUE)),
		'Lymph1' => clean_form_input($this->input->post('Lymph1', TRUE)),
		'Mono1' => clean_form_input($this->input->post('Mono1', TRUE)),
		'Eo1' => clean_form_input($this->input->post('Eo1', TRUE)),	
		'Baso1' => clean_form_input($this->input->post('Baso1', TRUE)),
		'Pro_Mye_Jv1' => clean_form_input($this->input->post('Pro_Mye_Jv1', TRUE)),
		'Stabs1' => clean_form_input($this->input->post('Stabs1', TRUE)),
		'Blasts1' => clean_form_input($this->input->post('Blasts1', TRUE)),
		'NRBC1' => clean_form_input($this->input->post('NRBC1', TRUE)),
                //col 2
		'Date2' => clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=>clean_form_input($this->input->post('Time2', TRUE)),
		'WBC2' => clean_form_input($this->input->post('WBC2', TRUE)),
		'RBC2' => clean_form_input($this->input->post('RBC2', TRUE)),
		'Hgb2' => clean_form_input($this->input->post('Hgb2', TRUE)),
		'Hct2' => clean_form_input($this->input->post('Hct2', TRUE)),
		'MCV2' => clean_form_input($this->input->post('MCV2', TRUE)),
		'MCH2' => clean_form_input($this->input->post('MCH2', TRUE)),
		'MCHC2' => clean_form_input($this->input->post('MCHC2', TRUE)),
		'RDWCV2' => clean_form_input($this->input->post('RDWCV2', TRUE)),
 		'Platelets2' => clean_form_input($this->input->post('Platelets2', TRUE)),
		'Neut2' => clean_form_input($this->input->post('Neut2', TRUE)),
		'Lymph2' => clean_form_input($this->input->post('Lymph2', TRUE)),
		'Mono2' => clean_form_input($this->input->post('Mono2', TRUE)),
		'Eo2' => clean_form_input($this->input->post('Eo2', TRUE)),	
		'Baso2' => clean_form_input($this->input->post('Baso2', TRUE)),
		'Pro_Mye_Jv2' => clean_form_input($this->input->post('Pro_Mye_Jv2', TRUE)),
		'Stabs2' => clean_form_input($this->input->post('Stabs2', TRUE)),
		'Blasts2' => clean_form_input($this->input->post('Blasts2', TRUE)),
		'NRBC2' => clean_form_input($this->input->post('NRBC2', TRUE)),
                //col 3
		'Date3' => clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=>clean_form_input($this->input->post('Time3', TRUE)),
		'WBC3' => clean_form_input($this->input->post('WBC3', TRUE)),
		'RBC3' => clean_form_input($this->input->post('RBC3', TRUE)),
		'Hgb3' => clean_form_input($this->input->post('Hgb3', TRUE)),
		'Hct3' => clean_form_input($this->input->post('Hct3', TRUE)),
		'MCV3' => clean_form_input($this->input->post('MCV3', TRUE)),
		'MCH3' => clean_form_input($this->input->post('MCH3', TRUE)),
		'MCHC3' => clean_form_input($this->input->post('MCHC3', TRUE)),
		'RDWCV3' => clean_form_input($this->input->post('RDWCV3', TRUE)),
 		'Platelets3' => clean_form_input($this->input->post('Platelets3', TRUE)),
		'Neut3' => clean_form_input($this->input->post('Neut3', TRUE)),
		'Lymph3' => clean_form_input($this->input->post('Lymph3', TRUE)),
		'Mono3' => clean_form_input($this->input->post('Mono3', TRUE)),
		'Eo3' => clean_form_input($this->input->post('Eo3', TRUE)),	
		'Baso3' => clean_form_input($this->input->post('Baso3', TRUE)),
		'Pro_Mye_Jv3' => clean_form_input($this->input->post('Pro_Mye_Jv3', TRUE)),
		'Stabs3' => clean_form_input($this->input->post('Stabs3', TRUE)),
		'Blasts3' => clean_form_input($this->input->post('Blasts3', TRUE)),
		'NRBC3' => clean_form_input($this->input->post('NRBC3', TRUE)),
		//col 4
		'Date4' => clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=>clean_form_input($this->input->post('Time4', TRUE)),
		'WBC4' => clean_form_input($this->input->post('WBC4', TRUE)),
		'RBC4' => clean_form_input($this->input->post('RBC4', TRUE)),
		'Hgb4' => clean_form_input($this->input->post('Hgb4', TRUE)),
		'Hct4' => clean_form_input($this->input->post('Hct4', TRUE)),
		'MCV4' => clean_form_input($this->input->post('MCV4', TRUE)),
		'MCH4' => clean_form_input($this->input->post('MCH4', TRUE)),
		'MCHC4' => clean_form_input($this->input->post('MCHC4', TRUE)),
		'RDWCV4' => clean_form_input($this->input->post('RDWCV4', TRUE)),
 		'Platelets4' => clean_form_input($this->input->post('Platelets4', TRUE)),
		'Neut4' => clean_form_input($this->input->post('Neut4', TRUE)),
		'Lymph4' => clean_form_input($this->input->post('Lymph4', TRUE)),
		'Mono4' => clean_form_input($this->input->post('Mono4', TRUE)),
		'Eo4' => clean_form_input($this->input->post('Eo4', TRUE)),	
		'Baso4' => clean_form_input($this->input->post('Baso4', TRUE)),
		'Pro_Mye_Jv4' => clean_form_input($this->input->post('Pro_Mye_Jv4', TRUE)),
		'Stabs4' => clean_form_input($this->input->post('Stabs4', TRUE)),
		'Blasts4' => clean_form_input($this->input->post('Blasts4', TRUE)),
		'NRBC4' => clean_form_input($this->input->post('NRBC4', TRUE)),
		//col 5
		'Date5' => clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=>clean_form_input($this->input->post('Time5', TRUE)),
		'WBC5' => clean_form_input($this->input->post('WBC5', TRUE)),
		'RBC5' => clean_form_input($this->input->post('RBC5', TRUE)),
		'Hgb5' => clean_form_input($this->input->post('Hgb5', TRUE)),
		'Hct5' => clean_form_input($this->input->post('Hct5', TRUE)),
		'MCV5' => clean_form_input($this->input->post('MCV5', TRUE)),
		'MCH5' => clean_form_input($this->input->post('MCH5', TRUE)),
		'MCHC5' => clean_form_input($this->input->post('MCHC5', TRUE)),
		'RDWCV5' => clean_form_input($this->input->post('RDWCV5', TRUE)),
 		'Platelets5' => clean_form_input($this->input->post('Platelets5', TRUE)),
		'Neut5' => clean_form_input($this->input->post('Neut5', TRUE)),
		'Lymph5' => clean_form_input($this->input->post('Lymph5', TRUE)),
		'Mono5' => clean_form_input($this->input->post('Mono5', TRUE)),
		'Eo5' => clean_form_input($this->input->post('Eo5', TRUE)),	
		'Baso5' => clean_form_input($this->input->post('Baso5', TRUE)),
		'Pro_Mye_Jv5' => clean_form_input($this->input->post('Pro_Mye_Jv5', TRUE)),
		'Stabs5' => clean_form_input($this->input->post('Stabs5', TRUE)),
		'Blasts5' => clean_form_input($this->input->post('Blasts5', TRUE)),
		'NRBC5' => clean_form_input($this->input->post('NRBC5', TRUE)),
		//col 6
		'Date6' => clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=>clean_form_input($this->input->post('Time6', TRUE)),
		'WBC6' => clean_form_input($this->input->post('WBC6', TRUE)),
		'RBC6' => clean_form_input($this->input->post('RBC6', TRUE)),
		'Hgb6' => clean_form_input($this->input->post('Hgb6', TRUE)),
		'Hct6' => clean_form_input($this->input->post('Hct6', TRUE)),
		'MCV6' => clean_form_input($this->input->post('MCV6', TRUE)),
		'MCH6' => clean_form_input($this->input->post('MCH6', TRUE)),
		'MCHC6' => clean_form_input($this->input->post('MCHC6', TRUE)),
		'RDWCV6' => clean_form_input($this->input->post('RDWCV6', TRUE)),
 		'Platelets6' => clean_form_input($this->input->post('Platelets6', TRUE)),
		'Neut6' => clean_form_input($this->input->post('Neut6', TRUE)),
		'Lymph6' => clean_form_input($this->input->post('Lymph6', TRUE)),
		'Mono6' => clean_form_input($this->input->post('Mono6', TRUE)),
		'Eo6' => clean_form_input($this->input->post('Eo6', TRUE)),	
		'Baso6' => clean_form_input($this->input->post('Baso6', TRUE)),
		'Pro_Mye_Jv6' => clean_form_input($this->input->post('Pro_Mye_Jv6', TRUE)),
		'Stabs6' => clean_form_input($this->input->post('Stabs6', TRUE)),
		'Blasts6' => clean_form_input($this->input->post('Blasts6', TRUE)),
		'NRBC6' => clean_form_input($this->input->post('NRBC6', TRUE)),
		//col 7
		'Date7' => clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=>clean_form_input($this->input->post('Time7', TRUE)),
		'WBC7' => clean_form_input($this->input->post('WBC7', TRUE)),
		'RBC7' => clean_form_input($this->input->post('RBC7', TRUE)),
		'Hgb7' => clean_form_input($this->input->post('Hgb7', TRUE)),
		'Hct7' => clean_form_input($this->input->post('Hct7', TRUE)),
		'MCV7' => clean_form_input($this->input->post('MCV7', TRUE)),
		'MCH7' => clean_form_input($this->input->post('MCH7', TRUE)),
		'MCHC7' => clean_form_input($this->input->post('MCHC7', TRUE)),
		'RDWCV7' => clean_form_input($this->input->post('RDWCV7', TRUE)),
 		'Platelets7' => clean_form_input($this->input->post('Platelets7', TRUE)),
		'Neut7' => clean_form_input($this->input->post('Neut7', TRUE)),
		'Lymph7' => clean_form_input($this->input->post('Lymph7', TRUE)),
		'Mono7' => clean_form_input($this->input->post('Mono7', TRUE)),
		'Eo7' => clean_form_input($this->input->post('Eo7', TRUE)),	
		'Baso7' => clean_form_input($this->input->post('Baso7', TRUE)),
		'Pro_Mye_Jv7' => clean_form_input($this->input->post('Pro_Mye_Jv7', TRUE)),
		'Stabs7' => clean_form_input($this->input->post('Stabs7', TRUE)),
		'Blasts7' => clean_form_input($this->input->post('Blasts7', TRUE)),
		'NRBC7' => clean_form_input($this->input->post('NRBC7', TRUE)),
		//col 8
		'Date8' => clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=>clean_form_input($this->input->post('Time8', TRUE)),
		'WBC8' => clean_form_input($this->input->post('WBC8', TRUE)),
		'RBC8' => clean_form_input($this->input->post('RBC8', TRUE)),
		'Hgb8' => clean_form_input($this->input->post('Hgb8', TRUE)),
		'Hct8' => clean_form_input($this->input->post('Hct8', TRUE)),
		'MCV8' => clean_form_input($this->input->post('MCV8', TRUE)),
		'MCH8' => clean_form_input($this->input->post('MCH8', TRUE)),
		'MCHC8' => clean_form_input($this->input->post('MCHC8', TRUE)),
		'RDWCV8' => clean_form_input($this->input->post('RDWCV8', TRUE)),
 		'Platelets8' => clean_form_input($this->input->post('Platelets8', TRUE)),
		'Neut8' => clean_form_input($this->input->post('Neut8', TRUE)),
		'Lymph8' => clean_form_input($this->input->post('Lymph8', TRUE)),
		'Mono8' => clean_form_input($this->input->post('Mono8', TRUE)),
		'Eo8' => clean_form_input($this->input->post('Eo8', TRUE)),	
		'Baso8' => clean_form_input($this->input->post('Baso8', TRUE)),
		'Pro_Mye_Jv8' => clean_form_input($this->input->post('Pro_Mye_Jv8', TRUE)),
		'Stabs8' => clean_form_input($this->input->post('Stabs8', TRUE)),
		'Blasts8' => clean_form_input($this->input->post('Blasts8', TRUE)),
		'NRBC8' => clean_form_input($this->input->post('NRBC8', TRUE)),
		//col 9
		'Date9' => clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=>clean_form_input($this->input->post('Time9', TRUE)),
		'WBC9' => clean_form_input($this->input->post('WBC9', TRUE)),
		'RBC9' => clean_form_input($this->input->post('RBC9', TRUE)),
		'Hgb9' => clean_form_input($this->input->post('Hgb9', TRUE)),
		'Hct9' => clean_form_input($this->input->post('Hct9', TRUE)),
		'MCV9' => clean_form_input($this->input->post('MCV9', TRUE)),
		'MCH9' => clean_form_input($this->input->post('MCH9', TRUE)),
		'MCHC9' => clean_form_input($this->input->post('MCHC9', TRUE)),
		'RDWCV9' => clean_form_input($this->input->post('RDWCV9', TRUE)),
 		'Platelets9' => clean_form_input($this->input->post('Platelets9', TRUE)),
		'Neut9' => clean_form_input($this->input->post('Neut9', TRUE)),
		'Lymph9' => clean_form_input($this->input->post('Lymph9', TRUE)),
		'Mono9' => clean_form_input($this->input->post('Mono9', TRUE)),
		'Eo9' => clean_form_input($this->input->post('Eo9', TRUE)),	
		'Baso9' => clean_form_input($this->input->post('Baso9', TRUE)),
		'Pro_Mye_Jv9' => clean_form_input($this->input->post('Pro_Mye_Jv9', TRUE)),
		'Stabs9' => clean_form_input($this->input->post('Stabs9', TRUE)),
		'Blasts9' => clean_form_input($this->input->post('Blasts9', TRUE)),
		'NRBC9' => clean_form_input($this->input->post('NRBC9', TRUE)),
        	//col 10
		'Date10' => clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=>clean_form_input($this->input->post('Time10', TRUE)),
		'WBC10' => clean_form_input($this->input->post('WBC10', TRUE)),
		'RBC10' => clean_form_input($this->input->post('RBC10', TRUE)),
		'Hgb10' => clean_form_input($this->input->post('Hgb10', TRUE)),
		'Hct10' => clean_form_input($this->input->post('Hct10', TRUE)),
		'MCV10' => clean_form_input($this->input->post('MCV10', TRUE)),
		'MCH10' => clean_form_input($this->input->post('MCH10', TRUE)),
		'MCHC10' => clean_form_input($this->input->post('MCHC10', TRUE)),
		'RDWCV10' => clean_form_input($this->input->post('RDWCV10', TRUE)),
 		'Platelets10' => clean_form_input($this->input->post('Platelets10', TRUE)),
		'Neut10' => clean_form_input($this->input->post('Neut10', TRUE)),
		'Lymph10' => clean_form_input($this->input->post('Lymph10', TRUE)),
		'Mono10' => clean_form_input($this->input->post('Mono10', TRUE)),
		'Eo10' => clean_form_input($this->input->post('Eo10', TRUE)),	
		'Baso10' => clean_form_input($this->input->post('Baso10', TRUE)),
		'Pro_Mye_Jv10' => clean_form_input($this->input->post('Pro_Mye_Jv10', TRUE)),
		'Stabs10' => clean_form_input($this->input->post('Stabs10', TRUE)),
		'Blasts10' => clean_form_input($this->input->post('Blasts10', TRUE)),
		'NRBC10' => clean_form_input($this->input->post('NRBC10', TRUE)),
		//col 11
		'Date11' => clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=>clean_form_input($this->input->post('Time11', TRUE)),
		'WBC11' => clean_form_input($this->input->post('WBC11', TRUE)),
		'RBC11' => clean_form_input($this->input->post('RBC11', TRUE)),
		'Hgb11' => clean_form_input($this->input->post('Hgb11', TRUE)),
		'Hct11' => clean_form_input($this->input->post('Hct11', TRUE)),
		'MCV11' => clean_form_input($this->input->post('MCV11', TRUE)),
		'MCH11' => clean_form_input($this->input->post('MCH11', TRUE)),
		'MCHC11' => clean_form_input($this->input->post('MCHC11', TRUE)),
		'RDWCV11' => clean_form_input($this->input->post('RDWCV11', TRUE)),
 		'Platelets11' => clean_form_input($this->input->post('Platelets11', TRUE)),
		'Neut11' => clean_form_input($this->input->post('Neut11', TRUE)),
		'Lymph11' => clean_form_input($this->input->post('Lymph11', TRUE)),
		'Mono11' => clean_form_input($this->input->post('Mono11', TRUE)),
		'Eo11' => clean_form_input($this->input->post('Eo11', TRUE)),	
		'Baso11' => clean_form_input($this->input->post('Baso11', TRUE)),
		'Pro_Mye_Jv11' => clean_form_input($this->input->post('Pro_Mye_Jv11', TRUE)),
		'Stabs11' => clean_form_input($this->input->post('Stabs11', TRUE)),
		'Blasts11' => clean_form_input($this->input->post('Blasts11', TRUE)),
		'NRBC11' => clean_form_input($this->input->post('NRBC11', TRUE)),
		//col 12

		'Date12' => clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=>clean_form_input($this->input->post('Time12', TRUE)),
		'WBC12' => clean_form_input($this->input->post('WBC12', TRUE)),
		'RBC12' => clean_form_input($this->input->post('RBC12', TRUE)),
		'Hgb12' => clean_form_input($this->input->post('Hgb12', TRUE)),
		'Hct12' => clean_form_input($this->input->post('Hct12', TRUE)),
		'MCV12' => clean_form_input($this->input->post('MCV12', TRUE)),
		'MCH12' => clean_form_input($this->input->post('MCH12', TRUE)),
		'MCHC12' => clean_form_input($this->input->post('MCHC12', TRUE)),
		'RDWCV12' => clean_form_input($this->input->post('RDWCV12', TRUE)),
 		'Platelets12' => clean_form_input($this->input->post('Platelets12', TRUE)),
		'Neut12' => clean_form_input($this->input->post('Neut12', TRUE)),
		'Lymph12' => clean_form_input($this->input->post('Lymph12', TRUE)),
		'Mono12' => clean_form_input($this->input->post('Mono12', TRUE)),
		'Eo12' => clean_form_input($this->input->post('Eo12', TRUE)),	
		'Baso12' => clean_form_input($this->input->post('Baso12', TRUE)),
		'Pro_Mye_Jv12' => clean_form_input($this->input->post('Pro_Mye_Jv12', TRUE)),
		'Stabs12' => clean_form_input($this->input->post('Stabs12', TRUE)),
		'Blasts12' => clean_form_input($this->input->post('Blasts12', TRUE)),
		'NRBC12' => clean_form_input($this->input->post('NRBC12', TRUE)),
		//col 13
		'Date13' => clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=>clean_form_input($this->input->post('Time13', TRUE)),
		'WBC13' => clean_form_input($this->input->post('WBC13', TRUE)),
		'RBC13' => clean_form_input($this->input->post('RBC13', TRUE)),
		'Hgb13' => clean_form_input($this->input->post('Hgb13', TRUE)),
		'Hct13' => clean_form_input($this->input->post('Hct13', TRUE)),
		'MCV13' => clean_form_input($this->input->post('MCV13', TRUE)),
		'MCH13' => clean_form_input($this->input->post('MCH13', TRUE)),
		'MCHC13' => clean_form_input($this->input->post('MCHC13', TRUE)),
		'RDWCV13' => clean_form_input($this->input->post('RDWCV13', TRUE)),
 		'Platelets13' => clean_form_input($this->input->post('Platelets13', TRUE)),
		'Neut13' => clean_form_input($this->input->post('Neut13', TRUE)),
		'Lymph13' => clean_form_input($this->input->post('Lymph13', TRUE)),
		'Mono13' => clean_form_input($this->input->post('Mono13', TRUE)),
		'Eo13' => clean_form_input($this->input->post('Eo13', TRUE)),	
		'Baso13' => clean_form_input($this->input->post('Baso13', TRUE)),
		'Pro_Mye_Jv13' => clean_form_input($this->input->post('Pro_Mye_Jv13', TRUE)),
		'Stabs13' => clean_form_input($this->input->post('Stabs13', TRUE)),
		'Blasts13' => clean_form_input($this->input->post('Blasts13', TRUE)),
		'NRBC13' => clean_form_input($this->input->post('NRBC13', TRUE)),
		//col 14
		'Date14' => clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=>clean_form_input($this->input->post('Time14', TRUE)),
		'WBC14' => clean_form_input($this->input->post('WBC14', TRUE)),
		'RBC14' => clean_form_input($this->input->post('RBC14', TRUE)),
		'Hgb14' => clean_form_input($this->input->post('Hgb14', TRUE)),
		'Hct14' => clean_form_input($this->input->post('Hct14', TRUE)),
		'MCV14' => clean_form_input($this->input->post('MCV14', TRUE)),
		'MCH14' => clean_form_input($this->input->post('MCH14', TRUE)),
		'MCHC14' => clean_form_input($this->input->post('MCHC14', TRUE)),
		'RDWCV14' => clean_form_input($this->input->post('RDWCV14', TRUE)),
 		'Platelets14' => clean_form_input($this->input->post('Platelets14', TRUE)),
		'Neut14' => clean_form_input($this->input->post('Neut14', TRUE)),
		'Lymph14' => clean_form_input($this->input->post('Lymph14', TRUE)),
		'Mono14' => clean_form_input($this->input->post('Mono14', TRUE)),
		'Eo14' => clean_form_input($this->input->post('Eo14', TRUE)),	
		'Baso14' => clean_form_input($this->input->post('Baso14', TRUE)),
		'Pro_Mye_Jv14' => clean_form_input($this->input->post('Pro_Mye_Jv14', TRUE)),
		'Stabs14' => clean_form_input($this->input->post('Stabs14', TRUE)),
		'Blasts14' => clean_form_input($this->input->post('Blasts14', TRUE)),
		'NRBC14' => clean_form_input($this->input->post('NRBC14', TRUE)),
		//col 15
		'Date15' => clean_form_input($this->input->post('Date15', TRUE)),
		'Time15'=>clean_form_input($this->input->post('Time15', TRUE)),
		'WBC15' => clean_form_input($this->input->post('WBC15', TRUE)),
		'RBC15' => clean_form_input($this->input->post('RBC15', TRUE)),
		'Hgb15' => clean_form_input($this->input->post('Hgb15', TRUE)),
		'Hct15' => clean_form_input($this->input->post('Hct15', TRUE)),
		'MCV15' => clean_form_input($this->input->post('MCV15', TRUE)),
		'MCH15' => clean_form_input($this->input->post('MCH15', TRUE)),
		'MCHC15' => clean_form_input($this->input->post('MCHC15', TRUE)),
		'RDWCV15' => clean_form_input($this->input->post('RDWCV15', TRUE)),
 		'Platelets15' => clean_form_input($this->input->post('Platelets15', TRUE)),
		'Neut15' => clean_form_input($this->input->post('Neut15', TRUE)),
		'Lymph15' => clean_form_input($this->input->post('Lymph15', TRUE)),
		'Mono15' => clean_form_input($this->input->post('Mono15', TRUE)),
		'Eo15' => clean_form_input($this->input->post('Eo15', TRUE)),	
		'Baso15' => clean_form_input($this->input->post('Baso15', TRUE)),
		'Pro_Mye_Jv15' => clean_form_input($this->input->post('Pro_Mye_Jv15', TRUE)),
		'Stabs15' => clean_form_input($this->input->post('Stabs15', TRUE)),
		'Blasts15' => clean_form_input($this->input->post('Blasts15', TRUE)),
		'NRBC15' => clean_form_input($this->input->post('NRBC15', TRUE)),
		);
		$cs_cbc = implode(",", $cbc);
                 $data = array(
                               'cbc2'=>$cs_cbc
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit cbc2 id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);                

       }
       function edit_bloodchem2($aid)
       {
	$bloodchem = array(

                //col 1
                'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Glucose1'=> clean_form_input($this->input->post('Glucose1', TRUE)),
		'BUN1'=> clean_form_input($this->input->post('BUN1', TRUE)),
		'Creatinine1'=> clean_form_input($this->input->post('Creatinine1', TRUE)),
		'Sodium1'=> clean_form_input($this->input->post('Sodium1', TRUE)),
		'Potassium1'=> clean_form_input($this->input->post('Potassium1', TRUE)),
		'Chloride1'=> clean_form_input($this->input->post('Chloride1', TRUE)),
		'Calcium1'=> clean_form_input($this->input->post('Calcium1', TRUE)),
		'Magnesium1'=> clean_form_input($this->input->post('Magnesium1', TRUE)),
 		'Phosphorus1'=> clean_form_input($this->input->post('Phosphorus1', TRUE)),
		'Total_Protein1'=> clean_form_input($this->input->post('Total_Protein1', TRUE)),
		'Albumin1'=> clean_form_input($this->input->post('Albumin1', TRUE)),
		'Globulin1'=> clean_form_input($this->input->post('Globulin1', TRUE)),
		'AST_SGOT1'=> clean_form_input($this->input->post('AST_SGOT1', TRUE)),
		'ALT_SGPT1'=> clean_form_input($this->input->post('ALT_SGPT1', TRUE)),
		'Alk_Phos1'=> clean_form_input($this->input->post('Alk_Phos1', TRUE)),
		'Total_Bilirubin1'=> clean_form_input($this->input->post('Total_Bilirubin1', TRUE)),
		'Direct_Bilirubin1'=> clean_form_input($this->input->post('Direct_Bilirubin1', TRUE)),
		'Indirect_Bilirubin1'=> clean_form_input($this->input->post('Indirect_Bilirubin1', TRUE)),
                'HDL1'=> clean_form_input($this->input->post('HDL1', TRUE)),
		'LDL1'=> clean_form_input($this->input->post('LDL1', TRUE)),
		'Cholesterol1'=> clean_form_input($this->input->post('Cholesterol1', TRUE)),
		'Triglycerides1'=> clean_form_input($this->input->post('Triglycerides1', TRUE)),
		'Uric_Acid1'=> clean_form_input($this->input->post('Uric_Acid1', TRUE)),
		'Amylase1'=> clean_form_input($this->input->post('Amylase1', TRUE)),
                'Lipase1'=> clean_form_input($this->input->post('Lipase1', TRUE)),
		'CK_Total1'=> clean_form_input($this->input->post('CK_Total1', TRUE)),
		'CK_MB1'=> clean_form_input($this->input->post('CK_MB1', TRUE)),
		'CK_MM1'=> clean_form_input($this->input->post('CK_MM1', TRUE)),
		'Trop_I1'=> clean_form_input($this->input->post('Trop_I1', TRUE)),
                'Myoglobin1'=> clean_form_input($this->input->post('Myoglobin1', TRUE)),

                //col 2
                'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Glucose2'=> clean_form_input($this->input->post('Glucose2', TRUE)),
		'BUN2'=> clean_form_input($this->input->post('BUN2', TRUE)),
		'Creatinine2'=> clean_form_input($this->input->post('Creatinine2', TRUE)),
		'Sodium2'=> clean_form_input($this->input->post('Sodium2', TRUE)),
		'Potassium2'=> clean_form_input($this->input->post('Potassium2', TRUE)),
		'Chloride2'=> clean_form_input($this->input->post('Chloride2', TRUE)),
		'Calcium2'=> clean_form_input($this->input->post('Calcium2', TRUE)),
		'Magnesium2'=> clean_form_input($this->input->post('Magnesium2', TRUE)),
 		'Phosphorus2'=> clean_form_input($this->input->post('Phosphorus2', TRUE)),
		'Total_Protein2'=> clean_form_input($this->input->post('Total_Protein2', TRUE)),
		'Albumin2'=> clean_form_input($this->input->post('Albumin2', TRUE)),
		'Globulin2'=> clean_form_input($this->input->post('Globulin2', TRUE)),
		'AST_SGOT2'=> clean_form_input($this->input->post('AST_SGOT2', TRUE)),
		'ALT_SGPT2'=> clean_form_input($this->input->post('ALT_SGPT2', TRUE)),
		'Alk_Phos2'=> clean_form_input($this->input->post('Alk_Phos2', TRUE)),
		'Total_Bilirubin2'=> clean_form_input($this->input->post('Total_Bilirubin2', TRUE)),
		'Direct_Bilirubin2'=> clean_form_input($this->input->post('Direct_Bilirubin2', TRUE)),
		'Indirect_Bilirubin2'=> clean_form_input($this->input->post('Indirect_Bilirubin2', TRUE)),
                'HDL2'=> clean_form_input($this->input->post('HDL2', TRUE)),
		'LDL2'=> clean_form_input($this->input->post('LDL2', TRUE)),
		'Cholesterol2'=> clean_form_input($this->input->post('Cholesterol2', TRUE)),
		'Triglycerides2'=> clean_form_input($this->input->post('Triglycerides2', TRUE)),
		'Uric_Acid2'=> clean_form_input($this->input->post('Uric_Acid2', TRUE)),
		'Amylase2'=> clean_form_input($this->input->post('Amylase2', TRUE)),
                'Lipase2'=> clean_form_input($this->input->post('Lipase2', TRUE)),
		'CK_Total2'=> clean_form_input($this->input->post('CK_Total2', TRUE)),
		'CK_MB2'=> clean_form_input($this->input->post('CK_MB2', TRUE)),
		'CK_MM2'=> clean_form_input($this->input->post('CK_MM2', TRUE)),
		'Trop_I2'=> clean_form_input($this->input->post('Trop_I2', TRUE)),
                'Myoglobin2'=> clean_form_input($this->input->post('Myoglobin2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Glucose3'=> clean_form_input($this->input->post('Glucose3', TRUE)),
		'BUN3'=> clean_form_input($this->input->post('BUN3', TRUE)),
		'Creatinine3'=> clean_form_input($this->input->post('Creatinine3', TRUE)),
		'Sodium3'=> clean_form_input($this->input->post('Sodium3', TRUE)),
		'Potassium3'=> clean_form_input($this->input->post('Potassium3', TRUE)),
		'Chloride3'=> clean_form_input($this->input->post('Chloride3', TRUE)),
		'Calcium3'=> clean_form_input($this->input->post('Calcium3', TRUE)),
		'Magnesium3'=> clean_form_input($this->input->post('Magnesium3', TRUE)),
 		'Phosphorus3'=> clean_form_input($this->input->post('Phosphorus3', TRUE)),
		'Total_Protein3'=> clean_form_input($this->input->post('Total_Protein3', TRUE)),
		'Albumin3'=> clean_form_input($this->input->post('Albumin3', TRUE)),
		'Globulin3'=> clean_form_input($this->input->post('Globulin3', TRUE)),
		'AST_SGOT3'=> clean_form_input($this->input->post('AST_SGOT3', TRUE)),
		'ALT_SGPT3'=> clean_form_input($this->input->post('ALT_SGPT3', TRUE)),
		'Alk_Phos3'=> clean_form_input($this->input->post('Alk_Phos3', TRUE)),
		'Total_Bilirubin3'=> clean_form_input($this->input->post('Total_Bilirubin3', TRUE)),
		'Direct_Bilirubin3'=> clean_form_input($this->input->post('Direct_Bilirubin3', TRUE)),
		'Indirect_Bilirubin3'=> clean_form_input($this->input->post('Indirect_Bilirubin3', TRUE)),
                'HDL3'=> clean_form_input($this->input->post('HDL3', TRUE)),
		'LDL3'=> clean_form_input($this->input->post('LDL3', TRUE)),
		'Cholesterol3'=> clean_form_input($this->input->post('Cholesterol3', TRUE)),
		'Triglycerides3'=> clean_form_input($this->input->post('Triglycerides3', TRUE)),
		'Uric_Acid3'=> clean_form_input($this->input->post('Uric_Acid3', TRUE)),
		'Amylase3'=> clean_form_input($this->input->post('Amylase3', TRUE)),
                'Lipase3'=> clean_form_input($this->input->post('Lipase3', TRUE)),
		'CK_Total3'=> clean_form_input($this->input->post('CK_Total3', TRUE)),
		'CK_MB3'=> clean_form_input($this->input->post('CK_MB3', TRUE)),
		'CK_MM3'=> clean_form_input($this->input->post('CK_MM3', TRUE)),
		'Trop_I3'=> clean_form_input($this->input->post('Trop_I3', TRUE)),
                'Myoglobin3'=> clean_form_input($this->input->post('Myoglobin3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Glucose4'=> clean_form_input($this->input->post('Glucose4', TRUE)),
		'BUN4'=> clean_form_input($this->input->post('BUN4', TRUE)),
		'Creatinine4'=> clean_form_input($this->input->post('Creatinine4', TRUE)),
		'Sodium4'=> clean_form_input($this->input->post('Sodium4', TRUE)),
		'Potassium4'=> clean_form_input($this->input->post('Potassium4', TRUE)),
		'Chloride4'=> clean_form_input($this->input->post('Chloride4', TRUE)),
		'Calcium4'=> clean_form_input($this->input->post('Calcium4', TRUE)),
		'Magnesium4'=> clean_form_input($this->input->post('Magnesium4', TRUE)),
 		'Phosphorus4'=> clean_form_input($this->input->post('Phosphorus4', TRUE)),
		'Total_Protein4'=> clean_form_input($this->input->post('Total_Protein4', TRUE)),
		'Albumin4'=> clean_form_input($this->input->post('Albumin4', TRUE)),
		'Globulin4'=> clean_form_input($this->input->post('Globulin4', TRUE)),
		'AST_SGOT4'=> clean_form_input($this->input->post('AST_SGOT4', TRUE)),
		'ALT_SGPT4'=> clean_form_input($this->input->post('ALT_SGPT4', TRUE)),
		'Alk_Phos4'=> clean_form_input($this->input->post('Alk_Phos4', TRUE)),
		'Total_Bilirubin4'=> clean_form_input($this->input->post('Total_Bilirubin4', TRUE)),
		'Direct_Bilirubin4'=> clean_form_input($this->input->post('Direct_Bilirubin4', TRUE)),
		'Indirect_Bilirubin4'=> clean_form_input($this->input->post('Indirect_Bilirubin4', TRUE)),
                'HDL4'=> clean_form_input($this->input->post('HDL4', TRUE)),
		'LDL4'=> clean_form_input($this->input->post('LDL4', TRUE)),
		'Cholesterol4'=> clean_form_input($this->input->post('Cholesterol4', TRUE)),
		'Triglycerides4'=> clean_form_input($this->input->post('Triglycerides4', TRUE)),
		'Uric_Acid4'=> clean_form_input($this->input->post('Uric_Acid4', TRUE)),
		'Amylase4'=> clean_form_input($this->input->post('Amylase4', TRUE)),
                'Lipase4'=> clean_form_input($this->input->post('Lipase4', TRUE)),
		'CK_Total4'=> clean_form_input($this->input->post('CK_Total4', TRUE)),
		'CK_MB4'=> clean_form_input($this->input->post('CK_MB4', TRUE)),
		'CK_MM4'=> clean_form_input($this->input->post('CK_MM4', TRUE)),
		'Trop_I4'=> clean_form_input($this->input->post('Trop_I4', TRUE)),
                'Myoglobin4'=> clean_form_input($this->input->post('Myoglobin4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Glucose5'=> clean_form_input($this->input->post('Glucose5', TRUE)),
		'BUN5'=> clean_form_input($this->input->post('BUN5', TRUE)),
		'Creatinine5'=> clean_form_input($this->input->post('Creatinine5', TRUE)),
		'Sodium5'=> clean_form_input($this->input->post('Sodium5', TRUE)),
		'Potassium5'=> clean_form_input($this->input->post('Potassium5', TRUE)),
		'Chloride5'=> clean_form_input($this->input->post('Chloride5', TRUE)),
		'Calcium5'=> clean_form_input($this->input->post('Calcium5', TRUE)),
		'Magnesium5'=> clean_form_input($this->input->post('Magnesium5', TRUE)),
 		'Phosphorus5'=> clean_form_input($this->input->post('Phosphorus5', TRUE)),
		'Total_Protein5'=> clean_form_input($this->input->post('Total_Protein5', TRUE)),
		'Albumin5'=> clean_form_input($this->input->post('Albumin5', TRUE)),
		'Globulin5'=> clean_form_input($this->input->post('Globulin5', TRUE)),
		'AST_SGOT5'=> clean_form_input($this->input->post('AST_SGOT5', TRUE)),
		'ALT_SGPT5'=> clean_form_input($this->input->post('ALT_SGPT5', TRUE)),
		'Alk_Phos5'=> clean_form_input($this->input->post('Alk_Phos5', TRUE)),
		'Total_Bilirubin5'=> clean_form_input($this->input->post('Total_Bilirubin5', TRUE)),
		'Direct_Bilirubin5'=> clean_form_input($this->input->post('Direct_Bilirubin5', TRUE)),
		'Indirect_Bilirubin5'=> clean_form_input($this->input->post('Indirect_Bilirubin5', TRUE)),
                'HDL5'=> clean_form_input($this->input->post('HDL5', TRUE)),
		'LDL5'=> clean_form_input($this->input->post('LDL5', TRUE)),
		'Cholesterol5'=> clean_form_input($this->input->post('Cholesterol5', TRUE)),
		'Triglycerides5'=> clean_form_input($this->input->post('Triglycerides5', TRUE)),
		'Uric_Acid5'=> clean_form_input($this->input->post('Uric_Acid5', TRUE)),
		'Amylase5'=> clean_form_input($this->input->post('Amylase5', TRUE)),
                'Lipase5'=> clean_form_input($this->input->post('Lipase5', TRUE)),
		'CK_Total5'=> clean_form_input($this->input->post('CK_Total5', TRUE)),
		'CK_MB5'=> clean_form_input($this->input->post('CK_MB5', TRUE)),
		'CK_MM5'=> clean_form_input($this->input->post('CK_MM5', TRUE)),
		'Trop_I5'=> clean_form_input($this->input->post('Trop_I5', TRUE)),
                'Myoglobin5'=> clean_form_input($this->input->post('Myoglobin5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Glucose6'=> clean_form_input($this->input->post('Glucose6', TRUE)),
		'BUN6'=> clean_form_input($this->input->post('BUN6', TRUE)),
		'Creatinine6'=> clean_form_input($this->input->post('Creatinine6', TRUE)),
		'Sodium6'=> clean_form_input($this->input->post('Sodium6', TRUE)),
		'Potassium6'=> clean_form_input($this->input->post('Potassium6', TRUE)),
		'Chloride6'=> clean_form_input($this->input->post('Chloride6', TRUE)),
		'Calcium6'=> clean_form_input($this->input->post('Calcium6', TRUE)),
		'Magnesium6'=> clean_form_input($this->input->post('Magnesium6', TRUE)),
 		'Phosphorus6'=> clean_form_input($this->input->post('Phosphorus6', TRUE)),
		'Total_Protein6'=> clean_form_input($this->input->post('Total_Protein6', TRUE)),
		'Albumin6'=> clean_form_input($this->input->post('Albumin6', TRUE)),
		'Globulin6'=> clean_form_input($this->input->post('Globulin6', TRUE)),
		'AST_SGOT6'=> clean_form_input($this->input->post('AST_SGOT6', TRUE)),
		'ALT_SGPT6'=> clean_form_input($this->input->post('ALT_SGPT6', TRUE)),
		'Alk_Phos6'=> clean_form_input($this->input->post('Alk_Phos6', TRUE)),
		'Total_Bilirubin6'=> clean_form_input($this->input->post('Total_Bilirubin6', TRUE)),
		'Direct_Bilirubin6'=> clean_form_input($this->input->post('Direct_Bilirubin6', TRUE)),
		'Indirect_Bilirubin6'=> clean_form_input($this->input->post('Indirect_Bilirubin6', TRUE)),
                'HDL6'=> clean_form_input($this->input->post('HDL6', TRUE)),
		'LDL6'=> clean_form_input($this->input->post('LDL6', TRUE)),
		'Cholesterol6'=> clean_form_input($this->input->post('Cholesterol6', TRUE)),
		'Triglycerides6'=> clean_form_input($this->input->post('Triglycerides6', TRUE)),
		'Uric_Acid6'=> clean_form_input($this->input->post('Uric_Acid6', TRUE)),
		'Amylase6'=> clean_form_input($this->input->post('Amylase6', TRUE)),
                'Lipase6'=> clean_form_input($this->input->post('Lipase6', TRUE)),
		'CK_Total6'=> clean_form_input($this->input->post('CK_Total6', TRUE)),
		'CK_MB6'=> clean_form_input($this->input->post('CK_MB6', TRUE)),
		'CK_MM6'=> clean_form_input($this->input->post('CK_MM6', TRUE)),
		'Trop_I6'=> clean_form_input($this->input->post('Trop_I6', TRUE)),
                'Myoglobin6'=> clean_form_input($this->input->post('Myoglobin6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Glucose7'=> clean_form_input($this->input->post('Glucose7', TRUE)),
		'BUN7'=> clean_form_input($this->input->post('BUN7', TRUE)),
		'Creatinine7'=> clean_form_input($this->input->post('Creatinine7', TRUE)),
		'Sodium7'=> clean_form_input($this->input->post('Sodium7', TRUE)),
		'Potassium7'=> clean_form_input($this->input->post('Potassium7', TRUE)),
		'Chloride7'=> clean_form_input($this->input->post('Chloride7', TRUE)),
		'Calcium7'=> clean_form_input($this->input->post('Calcium7', TRUE)),
		'Magnesium7'=> clean_form_input($this->input->post('Magnesium7', TRUE)),
 		'Phosphorus7'=> clean_form_input($this->input->post('Phosphorus7', TRUE)),
		'Total_Protein7'=> clean_form_input($this->input->post('Total_Protein7', TRUE)),
		'Albumin7'=> clean_form_input($this->input->post('Albumin7', TRUE)),
		'Globulin7'=> clean_form_input($this->input->post('Globulin7', TRUE)),
		'AST_SGOT7'=> clean_form_input($this->input->post('AST_SGOT7', TRUE)),
		'ALT_SGPT7'=> clean_form_input($this->input->post('ALT_SGPT7', TRUE)),
		'Alk_Phos7'=> clean_form_input($this->input->post('Alk_Phos7', TRUE)),
		'Total_Bilirubin7'=> clean_form_input($this->input->post('Total_Bilirubin7', TRUE)),
		'Direct_Bilirubin7'=> clean_form_input($this->input->post('Direct_Bilirubin7', TRUE)),
		'Indirect_Bilirubin7'=> clean_form_input($this->input->post('Indirect_Bilirubin7', TRUE)),
                'HDL7'=> clean_form_input($this->input->post('HDL7', TRUE)),
		'LDL7'=> clean_form_input($this->input->post('LDL7', TRUE)),
		'Cholesterol7'=> clean_form_input($this->input->post('Cholesterol7', TRUE)),
		'Triglycerides7'=> clean_form_input($this->input->post('Triglycerides7', TRUE)),
		'Uric_Acid7'=> clean_form_input($this->input->post('Uric_Acid7', TRUE)),
		'Amylase7'=> clean_form_input($this->input->post('Amylase7', TRUE)),
                'Lipase7'=> clean_form_input($this->input->post('Lipase7', TRUE)),
		'CK_Total7'=> clean_form_input($this->input->post('CK_Total7', TRUE)),
		'CK_MB7'=> clean_form_input($this->input->post('CK_MB7', TRUE)),
		'CK_MM7'=> clean_form_input($this->input->post('CK_MM7', TRUE)),
		'Trop_I7'=> clean_form_input($this->input->post('Trop_I7', TRUE)),
                'Myoglobin7'=> clean_form_input($this->input->post('Myoglobin7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Glucose8'=> clean_form_input($this->input->post('Glucose8', TRUE)),
		'BUN8'=> clean_form_input($this->input->post('BUN8', TRUE)),
		'Creatinine8'=> clean_form_input($this->input->post('Creatinine8', TRUE)),
		'Sodium8'=> clean_form_input($this->input->post('Sodium8', TRUE)),
		'Potassium8'=> clean_form_input($this->input->post('Potassium8', TRUE)),
		'Chloride8'=> clean_form_input($this->input->post('Chloride8', TRUE)),
		'Calcium8'=> clean_form_input($this->input->post('Calcium8', TRUE)),
		'Magnesium8'=> clean_form_input($this->input->post('Magnesium8', TRUE)),
 		'Phosphorus8'=> clean_form_input($this->input->post('Phosphorus8', TRUE)),
		'Total_Protein8'=> clean_form_input($this->input->post('Total_Protein8', TRUE)),
		'Albumin8'=> clean_form_input($this->input->post('Albumin8', TRUE)),
		'Globulin8'=> clean_form_input($this->input->post('Globulin8', TRUE)),
		'AST_SGOT8'=> clean_form_input($this->input->post('AST_SGOT8', TRUE)),
		'ALT_SGPT8'=> clean_form_input($this->input->post('ALT_SGPT8', TRUE)),
		'Alk_Phos8'=> clean_form_input($this->input->post('Alk_Phos8', TRUE)),
		'Total_Bilirubin8'=> clean_form_input($this->input->post('Total_Bilirubin8', TRUE)),
		'Direct_Bilirubin8'=> clean_form_input($this->input->post('Direct_Bilirubin8', TRUE)),
		'Indirect_Bilirubin8'=> clean_form_input($this->input->post('Indirect_Bilirubin8', TRUE)),
                'HDL8'=> clean_form_input($this->input->post('HDL8', TRUE)),
		'LDL8'=> clean_form_input($this->input->post('LDL8', TRUE)),
		'Cholesterol8'=> clean_form_input($this->input->post('Cholesterol8', TRUE)),
		'Triglycerides8'=> clean_form_input($this->input->post('Triglycerides8', TRUE)),
		'Uric_Acid8'=> clean_form_input($this->input->post('Uric_Acid8', TRUE)),
		'Amylase8'=> clean_form_input($this->input->post('Amylase8', TRUE)),
                'Lipase8'=> clean_form_input($this->input->post('Lipase8', TRUE)),
		'CK_Total8'=> clean_form_input($this->input->post('CK_Total8', TRUE)),
		'CK_MB8'=> clean_form_input($this->input->post('CK_MB8', TRUE)),
		'CK_MM8'=> clean_form_input($this->input->post('CK_MM8', TRUE)),
		'Trop_I8'=> clean_form_input($this->input->post('Trop_I8', TRUE)),
                'Myoglobin8'=> clean_form_input($this->input->post('Myoglobin8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Glucose9'=> clean_form_input($this->input->post('Glucose9', TRUE)),
		'BUN9'=> clean_form_input($this->input->post('BUN9', TRUE)),
		'Creatinine9'=> clean_form_input($this->input->post('Creatinine9', TRUE)),
		'Sodium9'=> clean_form_input($this->input->post('Sodium9', TRUE)),
		'Potassium9'=> clean_form_input($this->input->post('Potassium9', TRUE)),
		'Chloride9'=> clean_form_input($this->input->post('Chloride9', TRUE)),
		'Calcium9'=> clean_form_input($this->input->post('Calcium9', TRUE)),
		'Magnesium9'=> clean_form_input($this->input->post('Magnesium9', TRUE)),
 		'Phosphorus9'=> clean_form_input($this->input->post('Phosphorus9', TRUE)),
		'Total_Protein9'=> clean_form_input($this->input->post('Total_Protein9', TRUE)),
		'Albumin9'=> clean_form_input($this->input->post('Albumin9', TRUE)),
		'Globulin9'=> clean_form_input($this->input->post('Globulin9', TRUE)),
		'AST_SGOT9'=> clean_form_input($this->input->post('AST_SGOT9', TRUE)),
		'ALT_SGPT9'=> clean_form_input($this->input->post('ALT_SGPT9', TRUE)),
		'Alk_Phos9'=> clean_form_input($this->input->post('Alk_Phos9', TRUE)),
		'Total_Bilirubin9'=> clean_form_input($this->input->post('Total_Bilirubin9', TRUE)),
		'Direct_Bilirubin9'=> clean_form_input($this->input->post('Direct_Bilirubin9', TRUE)),
		'Indirect_Bilirubin9'=> clean_form_input($this->input->post('Indirect_Bilirubin9', TRUE)),
                'HDL9'=> clean_form_input($this->input->post('HDL9', TRUE)),
		'LDL9'=> clean_form_input($this->input->post('LDL9', TRUE)),
		'Cholesterol9'=> clean_form_input($this->input->post('Cholesterol9', TRUE)),
		'Triglycerides9'=> clean_form_input($this->input->post('Triglycerides9', TRUE)),
		'Uric_Acid9'=> clean_form_input($this->input->post('Uric_Acid9', TRUE)),
		'Amylase9'=> clean_form_input($this->input->post('Amylase9', TRUE)),
                'Lipase9'=> clean_form_input($this->input->post('Lipase9', TRUE)),
		'CK_Total9'=> clean_form_input($this->input->post('CK_Total9', TRUE)),
		'CK_MB9'=> clean_form_input($this->input->post('CK_MB9', TRUE)),
		'CK_MM9'=> clean_form_input($this->input->post('CK_MM9', TRUE)),
		'Trop_I9'=> clean_form_input($this->input->post('Trop_I9', TRUE)),
                'Myoglobin9'=> clean_form_input($this->input->post('Myoglobin9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Glucose10'=> clean_form_input($this->input->post('Glucose10', TRUE)),
		'BUN10'=> clean_form_input($this->input->post('BUN10', TRUE)),
		'Creatinine10'=> clean_form_input($this->input->post('Creatinine10', TRUE)),
		'Sodium10'=> clean_form_input($this->input->post('Sodium10', TRUE)),
		'Potassium10'=> clean_form_input($this->input->post('Potassium10', TRUE)),
		'Chloride10'=> clean_form_input($this->input->post('Chloride10', TRUE)),
		'Calcium10'=> clean_form_input($this->input->post('Calcium10', TRUE)),
		'Magnesium10'=> clean_form_input($this->input->post('Magnesium10', TRUE)),
 		'Phosphorus10'=> clean_form_input($this->input->post('Phosphorus10', TRUE)),
		'Total_Protein10'=> clean_form_input($this->input->post('Total_Protein10', TRUE)),
		'Albumin10'=> clean_form_input($this->input->post('Albumin10', TRUE)),
		'Globulin10'=> clean_form_input($this->input->post('Globulin10', TRUE)),
		'AST_SGOT10'=> clean_form_input($this->input->post('AST_SGOT10', TRUE)),
		'ALT_SGPT10'=> clean_form_input($this->input->post('ALT_SGPT10', TRUE)),
		'Alk_Phos10'=> clean_form_input($this->input->post('Alk_Phos10', TRUE)),
		'Total_Bilirubin10'=> clean_form_input($this->input->post('Total_Bilirubin10', TRUE)),
		'Direct_Bilirubin10'=> clean_form_input($this->input->post('Direct_Bilirubin10', TRUE)),
		'Indirect_Bilirubin10'=> clean_form_input($this->input->post('Indirect_Bilirubin10', TRUE)),
                'HDL10'=> clean_form_input($this->input->post('HDL10', TRUE)),
		'LDL10'=> clean_form_input($this->input->post('LDL10', TRUE)),
		'Cholesterol10'=> clean_form_input($this->input->post('Cholesterol10', TRUE)),
		'Triglycerides10'=> clean_form_input($this->input->post('Triglycerides10', TRUE)),
		'Uric_Acid10'=> clean_form_input($this->input->post('Uric_Acid10', TRUE)),
		'Amylase10'=> clean_form_input($this->input->post('Amylase10', TRUE)),
                'Lipase10'=> clean_form_input($this->input->post('Lipase10', TRUE)),
		'CK_Total10'=> clean_form_input($this->input->post('CK_Total10', TRUE)),
		'CK_MB10'=> clean_form_input($this->input->post('CK_MB10', TRUE)),
		'CK_MM10'=> clean_form_input($this->input->post('CK_MM10', TRUE)),
		'Trop_I10'=> clean_form_input($this->input->post('Trop_I10', TRUE)),
                'Myoglobin10'=> clean_form_input($this->input->post('Myoglobin10', TRUE)),
		//col 11
		'Date11'=> clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=> clean_form_input($this->input->post('Time11', TRUE)),
		'Glucose11'=> clean_form_input($this->input->post('Glucose11', TRUE)),
		'BUN11'=> clean_form_input($this->input->post('BUN11', TRUE)),
		'Creatinine11'=> clean_form_input($this->input->post('Creatinine11', TRUE)),
		'Sodium11'=> clean_form_input($this->input->post('Sodium11', TRUE)),
		'Potassium11'=> clean_form_input($this->input->post('Potassium11', TRUE)),
		'Chloride11'=> clean_form_input($this->input->post('Chloride11', TRUE)),
		'Calcium11'=> clean_form_input($this->input->post('Calcium11', TRUE)),
		'Magnesium11'=> clean_form_input($this->input->post('Magnesium11', TRUE)),
 		'Phosphorus11'=> clean_form_input($this->input->post('Phosphorus11', TRUE)),
		'Total_Protein11'=> clean_form_input($this->input->post('Total_Protein11', TRUE)),
		'Albumin11'=> clean_form_input($this->input->post('Albumin11', TRUE)),
		'Globulin11'=> clean_form_input($this->input->post('Globulin11', TRUE)),
		'AST_SGOT11'=> clean_form_input($this->input->post('AST_SGOT11', TRUE)),
		'ALT_SGPT11'=> clean_form_input($this->input->post('ALT_SGPT11', TRUE)),
		'Alk_Phos11'=> clean_form_input($this->input->post('Alk_Phos11', TRUE)),
		'Total_Bilirubin11'=> clean_form_input($this->input->post('Total_Bilirubin11', TRUE)),
		'Direct_Bilirubin11'=> clean_form_input($this->input->post('Direct_Bilirubin11', TRUE)),
		'Indirect_Bilirubin11'=> clean_form_input($this->input->post('Indirect_Bilirubin11', TRUE)),
                'HDL11'=> clean_form_input($this->input->post('HDL11', TRUE)),
		'LDL11'=> clean_form_input($this->input->post('LDL11', TRUE)),
		'Cholesterol11'=> clean_form_input($this->input->post('Cholesterol11', TRUE)),
		'Triglycerides11'=> clean_form_input($this->input->post('Triglycerides11', TRUE)),
		'Uric_Acid11'=> clean_form_input($this->input->post('Uric_Acid11', TRUE)),
		'Amylase11'=> clean_form_input($this->input->post('Amylase11', TRUE)),
                'Lipase11'=> clean_form_input($this->input->post('Lipase11', TRUE)),
		'CK_Total11'=> clean_form_input($this->input->post('CK_Total11', TRUE)),
		'CK_MB11'=> clean_form_input($this->input->post('CK_MB11', TRUE)),
		'CK_MM11'=> clean_form_input($this->input->post('CK_MM11', TRUE)),
		'Trop_I11'=> clean_form_input($this->input->post('Trop_I11', TRUE)),
                'Myoglobin11'=> clean_form_input($this->input->post('Myoglobin11', TRUE)),
		//col 12
		'Date12'=> clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=> clean_form_input($this->input->post('Time12', TRUE)),
		'Glucose12'=> clean_form_input($this->input->post('Glucose12', TRUE)),
		'BUN12'=> clean_form_input($this->input->post('BUN12', TRUE)),
		'Creatinine12'=> clean_form_input($this->input->post('Creatinine12', TRUE)),
		'Sodium12'=> clean_form_input($this->input->post('Sodium12', TRUE)),
		'Potassium12'=> clean_form_input($this->input->post('Potassium12', TRUE)),
		'Chloride12'=> clean_form_input($this->input->post('Chloride12', TRUE)),
		'Calcium12'=> clean_form_input($this->input->post('Calcium12', TRUE)),
		'Magnesium12'=> clean_form_input($this->input->post('Magnesium12', TRUE)),
 		'Phosphorus12'=> clean_form_input($this->input->post('Phosphorus12', TRUE)),
		'Total_Protein12'=> clean_form_input($this->input->post('Total_Protein12', TRUE)),
		'Albumin12'=> clean_form_input($this->input->post('Albumin12', TRUE)),
		'Globulin12'=> clean_form_input($this->input->post('Globulin12', TRUE)),
		'AST_SGOT12'=> clean_form_input($this->input->post('AST_SGOT12', TRUE)),
		'ALT_SGPT12'=> clean_form_input($this->input->post('ALT_SGPT12', TRUE)),
		'Alk_Phos12'=> clean_form_input($this->input->post('Alk_Phos12', TRUE)),
		'Total_Bilirubin12'=> clean_form_input($this->input->post('Total_Bilirubin12', TRUE)),
		'Direct_Bilirubin12'=> clean_form_input($this->input->post('Direct_Bilirubin12', TRUE)),
		'Indirect_Bilirubin12'=> clean_form_input($this->input->post('Indirect_Bilirubin12', TRUE)),
                'HDL12'=> clean_form_input($this->input->post('HDL12', TRUE)),
		'LDL12'=> clean_form_input($this->input->post('LDL12', TRUE)),
		'Cholesterol12'=> clean_form_input($this->input->post('Cholesterol12', TRUE)),
		'Triglycerides12'=> clean_form_input($this->input->post('Triglycerides12', TRUE)),
		'Uric_Acid12'=> clean_form_input($this->input->post('Uric_Acid12', TRUE)),
		'Amylase12'=> clean_form_input($this->input->post('Amylase12', TRUE)),
                'Lipase12'=> clean_form_input($this->input->post('Lipase12', TRUE)),
		'CK_Total12'=> clean_form_input($this->input->post('CK_Total12', TRUE)),
		'CK_MB12'=> clean_form_input($this->input->post('CK_MB12', TRUE)),
		'CK_MM12'=> clean_form_input($this->input->post('CK_MM12', TRUE)),
		'Trop_I12'=> clean_form_input($this->input->post('Trop_I12', TRUE)),
                'Myoglobin12'=> clean_form_input($this->input->post('Myoglobin12', TRUE)),
		//col 13
		'Date13'=> clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=> clean_form_input($this->input->post('Time13', TRUE)),
		'Glucose13'=> clean_form_input($this->input->post('Glucose13', TRUE)),
		'BUN13'=> clean_form_input($this->input->post('BUN13', TRUE)),
		'Creatinine13'=> clean_form_input($this->input->post('Creatinine13', TRUE)),
		'Sodium13'=> clean_form_input($this->input->post('Sodium13', TRUE)),
		'Potassium13'=> clean_form_input($this->input->post('Potassium13', TRUE)),
		'Chloride13'=> clean_form_input($this->input->post('Chloride13', TRUE)),
		'Calcium13'=> clean_form_input($this->input->post('Calcium13', TRUE)),
		'Magnesium13'=> clean_form_input($this->input->post('Magnesium13', TRUE)),
 		'Phosphorus13'=> clean_form_input($this->input->post('Phosphorus13', TRUE)),
		'Total_Protein13'=> clean_form_input($this->input->post('Total_Protein13', TRUE)),
		'Albumin13'=> clean_form_input($this->input->post('Albumin13', TRUE)),
		'Globulin13'=> clean_form_input($this->input->post('Globulin13', TRUE)),
		'AST_SGOT13'=> clean_form_input($this->input->post('AST_SGOT13', TRUE)),
		'ALT_SGPT13'=> clean_form_input($this->input->post('ALT_SGPT13', TRUE)),
		'Alk_Phos13'=> clean_form_input($this->input->post('Alk_Phos13', TRUE)),
		'Total_Bilirubin13'=> clean_form_input($this->input->post('Total_Bilirubin13', TRUE)),
		'Direct_Bilirubin13'=> clean_form_input($this->input->post('Direct_Bilirubin13', TRUE)),
		'Indirect_Bilirubin13'=> clean_form_input($this->input->post('Indirect_Bilirubin13', TRUE)),
                'HDL13'=> clean_form_input($this->input->post('HDL13', TRUE)),
		'LDL13'=> clean_form_input($this->input->post('LDL13', TRUE)),
		'Cholesterol13'=> clean_form_input($this->input->post('Cholesterol13', TRUE)),
		'Triglycerides13'=> clean_form_input($this->input->post('Triglycerides13', TRUE)),
		'Uric_Acid13'=> clean_form_input($this->input->post('Uric_Acid13', TRUE)),
		'Amylase13'=> clean_form_input($this->input->post('Amylase13', TRUE)),
                'Lipase13'=> clean_form_input($this->input->post('Lipase13', TRUE)),
		'CK_Total13'=> clean_form_input($this->input->post('CK_Total13', TRUE)),
		'CK_MB13'=> clean_form_input($this->input->post('CK_MB13', TRUE)),
		'CK_MM13'=> clean_form_input($this->input->post('CK_MM13', TRUE)),
		'Trop_I13'=> clean_form_input($this->input->post('Trop_I13', TRUE)),
                'Myoglobin13'=> clean_form_input($this->input->post('Myoglobin13', TRUE)),
		//col 14
		'Date14'=> clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=> clean_form_input($this->input->post('Time14', TRUE)),
		'Glucose14'=> clean_form_input($this->input->post('Glucose14', TRUE)),
		'BUN14'=> clean_form_input($this->input->post('BUN14', TRUE)),
		'Creatinine14'=> clean_form_input($this->input->post('Creatinine14', TRUE)),
		'Sodium14'=> clean_form_input($this->input->post('Sodium14', TRUE)),
		'Potassium14'=> clean_form_input($this->input->post('Potassium14', TRUE)),
		'Chloride14'=> clean_form_input($this->input->post('Chloride14', TRUE)),
		'Calcium14'=> clean_form_input($this->input->post('Calcium14', TRUE)),
		'Magnesium14'=> clean_form_input($this->input->post('Magnesium14', TRUE)),
 		'Phosphorus14'=> clean_form_input($this->input->post('Phosphorus14', TRUE)),
		'Total_Protein14'=> clean_form_input($this->input->post('Total_Protein14', TRUE)),
		'Albumin14'=> clean_form_input($this->input->post('Albumin14', TRUE)),
		'Globulin14'=> clean_form_input($this->input->post('Globulin14', TRUE)),
		'AST_SGOT14'=> clean_form_input($this->input->post('AST_SGOT14', TRUE)),
		'ALT_SGPT14'=> clean_form_input($this->input->post('ALT_SGPT14', TRUE)),
		'Alk_Phos14'=> clean_form_input($this->input->post('Alk_Phos14', TRUE)),
		'Total_Bilirubin14'=> clean_form_input($this->input->post('Total_Bilirubin14', TRUE)),
		'Direct_Bilirubin14'=> clean_form_input($this->input->post('Direct_Bilirubin14', TRUE)),
		'Indirect_Bilirubin14'=> clean_form_input($this->input->post('Indirect_Bilirubin14', TRUE)),
                'HDL14'=> clean_form_input($this->input->post('HDL14', TRUE)),
		'LDL14'=> clean_form_input($this->input->post('LDL14', TRUE)),
		'Cholesterol14'=> clean_form_input($this->input->post('Cholesterol14', TRUE)),
		'Triglycerides14'=> clean_form_input($this->input->post('Triglycerides14', TRUE)),
		'Uric_Acid14'=> clean_form_input($this->input->post('Uric_Acid14', TRUE)),
		'Amylase14'=> clean_form_input($this->input->post('Amylase14', TRUE)),
                'Lipase14'=> clean_form_input($this->input->post('Lipase14', TRUE)),
		'CK_Total14'=> clean_form_input($this->input->post('CK_Total14', TRUE)),
		'CK_MB14'=> clean_form_input($this->input->post('CK_MB14', TRUE)),
		'CK_MM14'=> clean_form_input($this->input->post('CK_MM14', TRUE)),
		'Trop_I14'=> clean_form_input($this->input->post('Trop_I14', TRUE)),
                'Myoglobin14'=> clean_form_input($this->input->post('Myoglobin14', TRUE)),
		//col 15
		'Date15'=> clean_form_input($this->input->post('Date15', TRUE)),
		'Time15'=> clean_form_input($this->input->post('Time15', TRUE)),
		'Glucose15'=> clean_form_input($this->input->post('Glucose15', TRUE)),
		'BUN15'=> clean_form_input($this->input->post('BUN15', TRUE)),
		'Creatinine15'=> clean_form_input($this->input->post('Creatinine15', TRUE)),
		'Sodium15'=> clean_form_input($this->input->post('Sodium15', TRUE)),
		'Potassium15'=> clean_form_input($this->input->post('Potassium15', TRUE)),
		'Chloride15'=> clean_form_input($this->input->post('Chloride15', TRUE)),
		'Calcium15'=> clean_form_input($this->input->post('Calcium15', TRUE)),
		'Magnesium15'=> clean_form_input($this->input->post('Magnesium15', TRUE)),
 		'Phosphorus15'=> clean_form_input($this->input->post('Phosphorus15', TRUE)),
		'Total_Protein15'=> clean_form_input($this->input->post('Total_Protein15', TRUE)),
		'Albumin15'=> clean_form_input($this->input->post('Albumin15', TRUE)),
		'Globulin15'=> clean_form_input($this->input->post('Globulin15', TRUE)),
		'AST_SGOT15'=> clean_form_input($this->input->post('AST_SGOT15', TRUE)),
		'ALT_SGPT15'=> clean_form_input($this->input->post('ALT_SGPT15', TRUE)),
		'Alk_Phos15'=> clean_form_input($this->input->post('Alk_Phos15', TRUE)),
		'Total_Bilirubin15'=> clean_form_input($this->input->post('Total_Bilirubin15', TRUE)),
		'Direct_Bilirubin15'=> clean_form_input($this->input->post('Direct_Bilirubin15', TRUE)),
		'Indirect_Bilirubin15'=> clean_form_input($this->input->post('Indirect_Bilirubin15', TRUE)),
                'HDL15'=> clean_form_input($this->input->post('HDL15', TRUE)),
		'LDL15'=> clean_form_input($this->input->post('LDL15', TRUE)),
		'Cholesterol15'=> clean_form_input($this->input->post('Cholesterol15', TRUE)),
		'Triglycerides15'=> clean_form_input($this->input->post('Triglycerides15', TRUE)),
		'Uric_Acid15'=> clean_form_input($this->input->post('Uric_Acid15', TRUE)),
		'Amylase15'=> clean_form_input($this->input->post('Amylase15', TRUE)),
                'Lipase15'=> clean_form_input($this->input->post('Lipase15', TRUE)),
		'CK_Total15'=> clean_form_input($this->input->post('CK_Total15', TRUE)),
		'CK_MB15'=> clean_form_input($this->input->post('CK_MB15', TRUE)),
		'CK_MM15'=> clean_form_input($this->input->post('CK_MM15', TRUE)),
		'Trop_I15'=> clean_form_input($this->input->post('Trop_I15', TRUE)),
                'Myoglobin15'=> clean_form_input($this->input->post('Myoglobin15', TRUE)),


                );
		$cs_bloodchem = implode(",", $bloodchem);
                $data = array(
                               'bloodchem2'=>$cs_bloodchem
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit bloodchem2 id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);           
	}	
	function edit_protime2($aid)
       	{
	 $protime = array(
                //col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Protime_Ctrl1'=> clean_form_input($this->input->post('Protime_Ctrl1', TRUE)),
		'Protime_Patient1'=> clean_form_input($this->input->post('Protime_Patient1', TRUE)),
		'Protime_Activity1'=> clean_form_input($this->input->post('Protime_Activity1', TRUE)),
		'Protime_INR1'=> clean_form_input($this->input->post('Protime_INR1', TRUE)),
		'aPTT_Ctrl1'=> clean_form_input($this->input->post('aPTT_Ctrl1', TRUE)),
		'aPTT_Patient1'=> clean_form_input($this->input->post('aPTT_Patient1', TRUE)),
                //col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Protime_Ctrl2'=> clean_form_input($this->input->post('Protime_Ctrl2', TRUE)),
		'Protime_Patient2'=> clean_form_input($this->input->post('Protime_Patient2', TRUE)),
		'Protime_Activity2'=> clean_form_input($this->input->post('Protime_Activity2', TRUE)),
		'Protime_INR2'=> clean_form_input($this->input->post('Protime_INR2', TRUE)),
		'aPTT_Ctrl2'=> clean_form_input($this->input->post('aPTT_Ctrl2', TRUE)),
		'aPTT_Patient2'=> clean_form_input($this->input->post('aPTT_Patient2', TRUE)),	
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Protime_Ctrl3'=> clean_form_input($this->input->post('Protime_Ctrl3', TRUE)),
		'Protime_Patient3'=> clean_form_input($this->input->post('Protime_Patient3', TRUE)),
		'Protime_Activity3'=> clean_form_input($this->input->post('Protime_Activity3', TRUE)),
		'Protime_INR3'=> clean_form_input($this->input->post('Protime_INR3', TRUE)),
		'aPTT_Ctrl3'=> clean_form_input($this->input->post('aPTT_Ctrl3', TRUE)),
		'aPTT_Patient3'=> clean_form_input($this->input->post('aPTT_Patient3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Protime_Ctrl4'=> clean_form_input($this->input->post('Protime_Ctrl4', TRUE)),
		'Protime_Patient4'=> clean_form_input($this->input->post('Protime_Patient4', TRUE)),
		'Protime_Activity4'=> clean_form_input($this->input->post('Protime_Activity4', TRUE)),
		'Protime_INR4'=> clean_form_input($this->input->post('Protime_INR4', TRUE)),
		'aPTT_Ctrl4'=> clean_form_input($this->input->post('aPTT_Ctrl4', TRUE)),
		'aPTT_Patient4'=> clean_form_input($this->input->post('aPTT_Patient4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Protime_Ctrl5'=> clean_form_input($this->input->post('Protime_Ctrl5', TRUE)),
		'Protime_Patient5'=> clean_form_input($this->input->post('Protime_Patient5', TRUE)),
		'Protime_Activity5'=> clean_form_input($this->input->post('Protime_Activity5', TRUE)),
		'Protime_INR5'=> clean_form_input($this->input->post('Protime_INR5', TRUE)),
		'aPTT_Ctrl5'=> clean_form_input($this->input->post('aPTT_Ctrl5', TRUE)),
		'aPTT_Patient5'=> clean_form_input($this->input->post('aPTT_Patient5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Protime_Ctrl6'=> clean_form_input($this->input->post('Protime_Ctrl6', TRUE)),
		'Protime_Patient6'=> clean_form_input($this->input->post('Protime_Patient6', TRUE)),
		'Protime_Activity6'=> clean_form_input($this->input->post('Protime_Activity6', TRUE)),
		'Protime_INR6'=> clean_form_input($this->input->post('Protime_INR6', TRUE)),
		'aPTT_Ctrl6'=> clean_form_input($this->input->post('aPTT_Ctrl6', TRUE)),
		'aPTT_Patient6'=> clean_form_input($this->input->post('aPTT_Patient6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Protime_Ctrl7'=> clean_form_input($this->input->post('Protime_Ctrl7', TRUE)),
		'Protime_Patient7'=> clean_form_input($this->input->post('Protime_Patient7', TRUE)),
		'Protime_Activity7'=> clean_form_input($this->input->post('Protime_Activity7', TRUE)),
		'Protime_INR7'=> clean_form_input($this->input->post('Protime_INR7', TRUE)),
		'aPTT_Ctrl7'=> clean_form_input($this->input->post('aPTT_Ctrl7', TRUE)),
		'aPTT_Patient7'=> clean_form_input($this->input->post('aPTT_Patient7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Protime_Ctrl8'=> clean_form_input($this->input->post('Protime_Ctrl8', TRUE)),
		'Protime_Patient8'=> clean_form_input($this->input->post('Protime_Patient8', TRUE)),
		'Protime_Activity8'=> clean_form_input($this->input->post('Protime_Activity8', TRUE)),
		'Protime_INR8'=> clean_form_input($this->input->post('Protime_INR8', TRUE)),
		'aPTT_Ctrl8'=> clean_form_input($this->input->post('aPTT_Ctrl8', TRUE)),
		'aPTT_Patient8'=> clean_form_input($this->input->post('aPTT_Patient8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Protime_Ctrl9'=> clean_form_input($this->input->post('Protime_Ctrl9', TRUE)),
		'Protime_Patient9'=> clean_form_input($this->input->post('Protime_Patient9', TRUE)),
		'Protime_Activity9'=> clean_form_input($this->input->post('Protime_Activity9', TRUE)),
		'Protime_INR9'=> clean_form_input($this->input->post('Protime_INR9', TRUE)),
		'aPTT_Ctrl9'=> clean_form_input($this->input->post('aPTT_Ctrl9', TRUE)),
		'aPTT_Patient9'=> clean_form_input($this->input->post('aPTT_Patient9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Protime_Ctrl10'=> clean_form_input($this->input->post('Protime_Ctrl10', TRUE)),
		'Protime_Patient10'=> clean_form_input($this->input->post('Protime_Patient10', TRUE)),
		'Protime_Activity10'=> clean_form_input($this->input->post('Protime_Activity10', TRUE)),
		'Protime_INR10'=> clean_form_input($this->input->post('Protime_INR10', TRUE)),
		'aPTT_Ctrl10'=> clean_form_input($this->input->post('aPTT_Ctrl10', TRUE)),
		'aPTT_Patient10'=> clean_form_input($this->input->post('aPTT_Patient10', TRUE)),
		//col 11
		'Date11'=> clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=> clean_form_input($this->input->post('Time11', TRUE)),
		'Protime_Ctrl11'=> clean_form_input($this->input->post('Protime_Ctrl11', TRUE)),
		'Protime_Patient11'=> clean_form_input($this->input->post('Protime_Patient11', TRUE)),
		'Protime_Activity11'=> clean_form_input($this->input->post('Protime_Activity11', TRUE)),
		'Protime_INR11'=> clean_form_input($this->input->post('Protime_INR11', TRUE)),
		'aPTT_Ctrl11'=> clean_form_input($this->input->post('aPTT_Ctrl11', TRUE)),
		'aPTT_Patient11'=> clean_form_input($this->input->post('aPTT_Patient11', TRUE)),
		//col 12
		'Date12'=> clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=> clean_form_input($this->input->post('Time12', TRUE)),
		'Protime_Ctrl12'=> clean_form_input($this->input->post('Protime_Ctrl12', TRUE)),
		'Protime_Patient12'=> clean_form_input($this->input->post('Protime_Patient12', TRUE)),
		'Protime_Activity12'=> clean_form_input($this->input->post('Protime_Activity12', TRUE)),
		'Protime_INR12'=> clean_form_input($this->input->post('Protime_INR12', TRUE)),
		'aPTT_Ctrl12'=> clean_form_input($this->input->post('aPTT_Ctrl12', TRUE)),
		'aPTT_Patient12'=> clean_form_input($this->input->post('aPTT_Patient12', TRUE)),
		//col 13
		'Date13'=> clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=> clean_form_input($this->input->post('Time13', TRUE)),
		'Protime_Ctrl13'=> clean_form_input($this->input->post('Protime_Ctrl13', TRUE)),
		'Protime_Patient13'=> clean_form_input($this->input->post('Protime_Patient13', TRUE)),
		'Protime_Activity13'=> clean_form_input($this->input->post('Protime_Activity13', TRUE)),
		'Protime_INR13'=> clean_form_input($this->input->post('Protime_INR13', TRUE)),
		'aPTT_Ctrl13'=> clean_form_input($this->input->post('aPTT_Ctrl13', TRUE)),
		'aPTT_Patient13'=> clean_form_input($this->input->post('aPTT_Patient13', TRUE)),	
		//col 14
		'Date14'=> clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=> clean_form_input($this->input->post('Time14', TRUE)),
		'Protime_Ctrl14'=> clean_form_input($this->input->post('Protime_Ctrl14', TRUE)),
		'Protime_Patient14'=> clean_form_input($this->input->post('Protime_Patient14', TRUE)),
		'Protime_Activity14'=> clean_form_input($this->input->post('Protime_Activity14', TRUE)),
		'Protime_INR14'=> clean_form_input($this->input->post('Protime_INR14', TRUE)),
		'aPTT_Ctrl14'=> clean_form_input($this->input->post('aPTT_Ctrl14', TRUE)),
		'aPTT_Patient14'=> clean_form_input($this->input->post('aPTT_Patient14', TRUE)),
		//col 15
		'Date15'=> clean_form_input($this->input->post('Date15', TRUE)),
		'Time15'=> clean_form_input($this->input->post('Time15', TRUE)),
		'Protime_Ctrl15'=> clean_form_input($this->input->post('Protime_Ctrl15', TRUE)),
		'Protime_Patient15'=> clean_form_input($this->input->post('Protime_Patient15', TRUE)),
		'Protime_Activity15'=> clean_form_input($this->input->post('Protime_Activity15', TRUE)),
		'Protime_INR15'=> clean_form_input($this->input->post('Protime_INR15', TRUE)),
		'aPTT_Ctrl15'=> clean_form_input($this->input->post('aPTT_Ctrl15', TRUE)),
		'aPTT_Patient15'=> clean_form_input($this->input->post('aPTT_Patient15', TRUE)),
		);
		$cs_protime = implode(",", $protime);
                $data = array(
                               'protime2'=>$cs_protime
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit protime2 id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   


	}
	function edit_urine2($aid)
       	{
	 $urine = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
                'Color1'=> clean_form_input($this->input->post('Color1', TRUE)),
		'Transparency1'=> clean_form_input($this->input->post('Transparency1', TRUE)),
		'SG1'=> clean_form_input($this->input->post('SG1', TRUE)),
		'pH1'=> clean_form_input($this->input->post('pH1', TRUE)),
		'Sugar1'=> clean_form_input($this->input->post('Sugar1', TRUE)),
		'Albumin1'=> clean_form_input($this->input->post('Albumin1', TRUE)),
		'RBC1'=> clean_form_input($this->input->post('RBC1', TRUE)),
		'WBC1'=> clean_form_input($this->input->post('WBC1', TRUE)),
		'Casts1'=> clean_form_input($this->input->post('Casts1', TRUE)),
		'Crystals1'=> clean_form_input($this->input->post('Crystals1', TRUE)),
		'Epith_Cells1'=> clean_form_input($this->input->post('Epith_Cells1', TRUE)),
		'Bacteria1'=> clean_form_input($this->input->post('Bacteria1', TRUE)),
		'Mucus_Threads1'=> clean_form_input($this->input->post('Mucus_Threads1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
                'Color2'=> clean_form_input($this->input->post('Color2', TRUE)),
		'Transparency2'=> clean_form_input($this->input->post('Transparency2', TRUE)),
		'SG2'=> clean_form_input($this->input->post('SG2', TRUE)),
		'pH2'=> clean_form_input($this->input->post('pH2', TRUE)),
		'Sugar2'=> clean_form_input($this->input->post('Sugar2', TRUE)),
		'Albumin2'=> clean_form_input($this->input->post('Albumin2', TRUE)),
		'RBC2'=> clean_form_input($this->input->post('RBC2', TRUE)),
		'WBC2'=> clean_form_input($this->input->post('WBC2', TRUE)),
		'Casts2'=> clean_form_input($this->input->post('Casts2', TRUE)),
		'Crystals2'=> clean_form_input($this->input->post('Crystals2', TRUE)),
		'Epith_Cells2'=> clean_form_input($this->input->post('Epith_Cells2', TRUE)),
		'Bacteria2'=> clean_form_input($this->input->post('Bacteria2', TRUE)),
		'Mucus_Threads2'=> clean_form_input($this->input->post('Mucus_Threads2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
                'Color3'=> clean_form_input($this->input->post('Color3', TRUE)),
		'Transparency3'=> clean_form_input($this->input->post('Transparency3', TRUE)),
		'SG3'=> clean_form_input($this->input->post('SG3', TRUE)),
		'pH3'=> clean_form_input($this->input->post('pH3', TRUE)),
		'Sugar3'=> clean_form_input($this->input->post('Sugar3', TRUE)),
		'Albumin3'=> clean_form_input($this->input->post('Albumin3', TRUE)),
		'RBC3'=> clean_form_input($this->input->post('RBC3', TRUE)),
		'WBC3'=> clean_form_input($this->input->post('WBC3', TRUE)),
		'Casts3'=> clean_form_input($this->input->post('Casts3', TRUE)),
		'Crystals3'=> clean_form_input($this->input->post('Crystals3', TRUE)),
		'Epith_Cells3'=> clean_form_input($this->input->post('Epith_Cells3', TRUE)),
		'Bacteria3'=> clean_form_input($this->input->post('Bacteria3', TRUE)),
		'Mucus_Threads3'=> clean_form_input($this->input->post('Mucus_Threads3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
                'Color4'=> clean_form_input($this->input->post('Color4', TRUE)),
		'Transparency4'=> clean_form_input($this->input->post('Transparency4', TRUE)),
		'SG4'=> clean_form_input($this->input->post('SG4', TRUE)),
		'pH4'=> clean_form_input($this->input->post('pH4', TRUE)),
		'Sugar4'=> clean_form_input($this->input->post('Sugar4', TRUE)),
		'Albumin4'=> clean_form_input($this->input->post('Albumin4', TRUE)),
		'RBC4'=> clean_form_input($this->input->post('RBC4', TRUE)),
		'WBC4'=> clean_form_input($this->input->post('WBC4', TRUE)),
		'Casts4'=> clean_form_input($this->input->post('Casts4', TRUE)),
		'Crystals4'=> clean_form_input($this->input->post('Crystals4', TRUE)),
		'Epith_Cells4'=> clean_form_input($this->input->post('Epith_Cells4', TRUE)),
		'Bacteria4'=> clean_form_input($this->input->post('Bacteria4', TRUE)),
		'Mucus_Threads4'=> clean_form_input($this->input->post('Mucus_Threads4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
                'Color5'=> clean_form_input($this->input->post('Color5', TRUE)),
		'Transparency5'=> clean_form_input($this->input->post('Transparency5', TRUE)),
		'SG5'=> clean_form_input($this->input->post('SG5', TRUE)),
		'pH5'=> clean_form_input($this->input->post('pH5', TRUE)),
		'Sugar5'=> clean_form_input($this->input->post('Sugar5', TRUE)),
		'Albumin5'=> clean_form_input($this->input->post('Albumin5', TRUE)),
		'RBC5'=> clean_form_input($this->input->post('RBC5', TRUE)),
		'WBC5'=> clean_form_input($this->input->post('WBC5', TRUE)),
		'Casts5'=> clean_form_input($this->input->post('Casts5', TRUE)),
		'Crystals5'=> clean_form_input($this->input->post('Crystals5', TRUE)),
		'Epith_Cells5'=> clean_form_input($this->input->post('Epith_Cells5', TRUE)),
		'Bacteria5'=> clean_form_input($this->input->post('Bacteria5', TRUE)),
		'Mucus_Threads5'=> clean_form_input($this->input->post('Mucus_Threads5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
                'Color6'=> clean_form_input($this->input->post('Color6', TRUE)),
		'Transparency6'=> clean_form_input($this->input->post('Transparency6', TRUE)),
		'SG6'=> clean_form_input($this->input->post('SG6', TRUE)),
		'pH6'=> clean_form_input($this->input->post('pH6', TRUE)),
		'Sugar6'=> clean_form_input($this->input->post('Sugar6', TRUE)),
		'Albumin6'=> clean_form_input($this->input->post('Albumin6', TRUE)),
		'RBC6'=> clean_form_input($this->input->post('RBC6', TRUE)),
		'WBC6'=> clean_form_input($this->input->post('WBC6', TRUE)),
		'Casts6'=> clean_form_input($this->input->post('Casts6', TRUE)),
		'Crystals6'=> clean_form_input($this->input->post('Crystals6', TRUE)),
		'Epith_Cells6'=> clean_form_input($this->input->post('Epith_Cells6', TRUE)),
		'Bacteria6'=> clean_form_input($this->input->post('Bacteria6', TRUE)),
		'Mucus_Threads6'=> clean_form_input($this->input->post('Mucus_Threads6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
                'Color7'=> clean_form_input($this->input->post('Color7', TRUE)),
		'Transparency7'=> clean_form_input($this->input->post('Transparency7', TRUE)),
		'SG7'=> clean_form_input($this->input->post('SG7', TRUE)),
		'pH7'=> clean_form_input($this->input->post('pH7', TRUE)),
		'Sugar7'=> clean_form_input($this->input->post('Sugar7', TRUE)),
		'Albumin7'=> clean_form_input($this->input->post('Albumin7', TRUE)),
		'RBC7'=> clean_form_input($this->input->post('RBC7', TRUE)),
		'WBC7'=> clean_form_input($this->input->post('WBC7', TRUE)),
		'Casts7'=> clean_form_input($this->input->post('Casts7', TRUE)),
		'Crystals7'=> clean_form_input($this->input->post('Crystals7', TRUE)),
		'Epith_Cells7'=> clean_form_input($this->input->post('Epith_Cells7', TRUE)),
		'Bacteria7'=> clean_form_input($this->input->post('Bacteria7', TRUE)),
		'Mucus_Threads7'=> clean_form_input($this->input->post('Mucus_Threads7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
                'Color8'=> clean_form_input($this->input->post('Color8', TRUE)),
		'Transparency8'=> clean_form_input($this->input->post('Transparency8', TRUE)),
		'SG8'=> clean_form_input($this->input->post('SG8', TRUE)),
		'pH8'=> clean_form_input($this->input->post('pH8', TRUE)),
		'Sugar8'=> clean_form_input($this->input->post('Sugar8', TRUE)),
		'Albumin8'=> clean_form_input($this->input->post('Albumin8', TRUE)),
		'RBC8'=> clean_form_input($this->input->post('RBC8', TRUE)),
		'WBC8'=> clean_form_input($this->input->post('WBC8', TRUE)),
		'Casts8'=> clean_form_input($this->input->post('Casts8', TRUE)),
		'Crystals8'=> clean_form_input($this->input->post('Crystals8', TRUE)),
		'Epith_Cells8'=> clean_form_input($this->input->post('Epith_Cells8', TRUE)),
		'Bacteria8'=> clean_form_input($this->input->post('Bacteria8', TRUE)),
		'Mucus_Threads8'=> clean_form_input($this->input->post('Mucus_Threads8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
                'Color9'=> clean_form_input($this->input->post('Color9', TRUE)),
		'Transparency9'=> clean_form_input($this->input->post('Transparency9', TRUE)),
		'SG9'=> clean_form_input($this->input->post('SG9', TRUE)),
		'pH9'=> clean_form_input($this->input->post('pH9', TRUE)),
		'Sugar9'=> clean_form_input($this->input->post('Sugar9', TRUE)),
		'Albumin9'=> clean_form_input($this->input->post('Albumin9', TRUE)),
		'RBC9'=> clean_form_input($this->input->post('RBC9', TRUE)),
		'WBC9'=> clean_form_input($this->input->post('WBC9', TRUE)),
		'Casts9'=> clean_form_input($this->input->post('Casts9', TRUE)),
		'Crystals9'=> clean_form_input($this->input->post('Crystals9', TRUE)),
		'Epith_Cells9'=> clean_form_input($this->input->post('Epith_Cells9', TRUE)),
		'Bacteria9'=> clean_form_input($this->input->post('Bacteria9', TRUE)),
		'Mucus_Threads9'=> clean_form_input($this->input->post('Mucus_Threads9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
                'Color10'=> clean_form_input($this->input->post('Color10', TRUE)),
		'Transparency10'=> clean_form_input($this->input->post('Transparency10', TRUE)),
		'SG10'=> clean_form_input($this->input->post('SG10', TRUE)),
		'pH10'=> clean_form_input($this->input->post('pH10', TRUE)),
		'Sugar10'=> clean_form_input($this->input->post('Sugar10', TRUE)),
		'Albumin10'=> clean_form_input($this->input->post('Albumin10', TRUE)),
		'RBC10'=> clean_form_input($this->input->post('RBC10', TRUE)),
		'WBC10'=> clean_form_input($this->input->post('WBC10', TRUE)),
		'Casts10'=> clean_form_input($this->input->post('Casts10', TRUE)),
		'Crystals10'=> clean_form_input($this->input->post('Crystals10', TRUE)),
		'Epith_Cells10'=> clean_form_input($this->input->post('Epith_Cells10', TRUE)),
		'Bacteria10'=> clean_form_input($this->input->post('Bacteria10', TRUE)),
		'Mucus_Threads10'=> clean_form_input($this->input->post('Mucus_Threads10', TRUE)),
		//col 11
		'Date11'=> clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=> clean_form_input($this->input->post('Time11', TRUE)),
                'Color11'=> clean_form_input($this->input->post('Color11', TRUE)),
		'Transparency11'=> clean_form_input($this->input->post('Transparency11', TRUE)),
		'SG11'=> clean_form_input($this->input->post('SG11', TRUE)),
		'pH11'=> clean_form_input($this->input->post('pH11', TRUE)),
		'Sugar11'=> clean_form_input($this->input->post('Sugar11', TRUE)),
		'Albumin11'=> clean_form_input($this->input->post('Albumin11', TRUE)),
		'RBC11'=> clean_form_input($this->input->post('RBC11', TRUE)),
		'WBC11'=> clean_form_input($this->input->post('WBC11', TRUE)),
		'Casts11'=> clean_form_input($this->input->post('Casts11', TRUE)),
		'Crystals11'=> clean_form_input($this->input->post('Crystals11', TRUE)),
		'Epith_Cells11'=> clean_form_input($this->input->post('Epith_Cells11', TRUE)),
		'Bacteria11'=> clean_form_input($this->input->post('Bacteria11', TRUE)),
		'Mucus_Threads11'=> clean_form_input($this->input->post('Mucus_Threads11', TRUE)),
		//col 12
		'Date12'=> clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=> clean_form_input($this->input->post('Time12', TRUE)),
                'Color12'=> clean_form_input($this->input->post('Color12', TRUE)),
		'Transparency12'=> clean_form_input($this->input->post('Transparency12', TRUE)),
		'SG12'=> clean_form_input($this->input->post('SG12', TRUE)),
		'pH12'=> clean_form_input($this->input->post('pH12', TRUE)),
		'Sugar12'=> clean_form_input($this->input->post('Sugar12', TRUE)),
		'Albumin12'=> clean_form_input($this->input->post('Albumin12', TRUE)),
		'RBC12'=> clean_form_input($this->input->post('RBC12', TRUE)),
		'WBC12'=> clean_form_input($this->input->post('WBC12', TRUE)),
		'Casts12'=> clean_form_input($this->input->post('Casts12', TRUE)),
		'Crystals12'=> clean_form_input($this->input->post('Crystals12', TRUE)),
		'Epith_Cells12'=> clean_form_input($this->input->post('Epith_Cells12', TRUE)),
		'Bacteria12'=> clean_form_input($this->input->post('Bacteria12', TRUE)),
		'Mucus_Threads12'=> clean_form_input($this->input->post('Mucus_Threads12', TRUE)),
		//col 13
		'Date13'=> clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=> clean_form_input($this->input->post('Time13', TRUE)),
                'Color13'=> clean_form_input($this->input->post('Color13', TRUE)),
		'Transparency13'=> clean_form_input($this->input->post('Transparency13', TRUE)),
		'SG13'=> clean_form_input($this->input->post('SG13', TRUE)),
		'pH13'=> clean_form_input($this->input->post('pH13', TRUE)),
		'Sugar13'=> clean_form_input($this->input->post('Sugar13', TRUE)),
		'Albumin13'=> clean_form_input($this->input->post('Albumin13', TRUE)),
		'RBC13'=> clean_form_input($this->input->post('RBC13', TRUE)),
		'WBC13'=> clean_form_input($this->input->post('WBC13', TRUE)),
		'Casts13'=> clean_form_input($this->input->post('Casts13', TRUE)),
		'Crystals13'=> clean_form_input($this->input->post('Crystals13', TRUE)),
		'Epith_Cells13'=> clean_form_input($this->input->post('Epith_Cells13', TRUE)),
		'Bacteria13'=> clean_form_input($this->input->post('Bacteria13', TRUE)),
		'Mucus_Threads13'=> clean_form_input($this->input->post('Mucus_Threads13', TRUE)),
		//col 14
		'Date14'=> clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=> clean_form_input($this->input->post('Time14', TRUE)),
                'Color14'=> clean_form_input($this->input->post('Color14', TRUE)),
		'Transparency14'=> clean_form_input($this->input->post('Transparency14', TRUE)),
		'SG14'=> clean_form_input($this->input->post('SG14', TRUE)),

		'pH14'=> clean_form_input($this->input->post('pH14', TRUE)),
		'Sugar14'=> clean_form_input($this->input->post('Sugar14', TRUE)),
		'Albumin14'=> clean_form_input($this->input->post('Albumin14', TRUE)),
		'RBC14'=> clean_form_input($this->input->post('RBC14', TRUE)),
		'WBC14'=> clean_form_input($this->input->post('WBC14', TRUE)),
		'Casts14'=> clean_form_input($this->input->post('Casts14', TRUE)),
		'Crystals14'=> clean_form_input($this->input->post('Crystals14', TRUE)),
		'Epith_Cells14'=> clean_form_input($this->input->post('Epith_Cells14', TRUE)),
		'Bacteria14'=> clean_form_input($this->input->post('Bacteria14', TRUE)),
		'Mucus_Threads14'=> clean_form_input($this->input->post('Mucus_Threads14', TRUE)),
		//col 15
		'Date15'=> clean_form_input($this->input->post('Date15', TRUE)),

		'Time15'=> clean_form_input($this->input->post('Time15', TRUE)),
                'Color15'=> clean_form_input($this->input->post('Color15', TRUE)),
		'Transparency15'=> clean_form_input($this->input->post('Transparency15', TRUE)),
		'SG15'=> clean_form_input($this->input->post('SG15', TRUE)),
		'pH15'=> clean_form_input($this->input->post('pH15', TRUE)),
		'Sugar15'=> clean_form_input($this->input->post('Sugar15', TRUE)),
		'Albumin15'=> clean_form_input($this->input->post('Albumin15', TRUE)),
		'RBC15'=> clean_form_input($this->input->post('RBC15', TRUE)),
		'WBC15'=> clean_form_input($this->input->post('WBC15', TRUE)),
		'Casts15'=> clean_form_input($this->input->post('Casts15', TRUE)),
		'Crystals15'=> clean_form_input($this->input->post('Crystals15', TRUE)),
		'Epith_Cells15'=> clean_form_input($this->input->post('Epith_Cells15', TRUE)),
		'Bacteria15'=> clean_form_input($this->input->post('Bacteria15', TRUE)),
		'Mucus_Threads15'=> clean_form_input($this->input->post('Mucus_Threads15', TRUE)),
         );


         $cs_urine = implode(",", $urine);
                $data = array(
                               'urine2'=>$cs_urine
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit urinalysis2 id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   

	}
	function edit_abg2($aid)
       	{
	 $abg = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'FiO21'=> clean_form_input($this->input->post('FiO21', TRUE)),
		'Temp1'=> clean_form_input($this->input->post('Temp1', TRUE)),
		'pH1'=> clean_form_input($this->input->post('pH1', TRUE)),
		'pCO21'=> clean_form_input($this->input->post('pCO21', TRUE)),
		'pO21'=> clean_form_input($this->input->post('pO21', TRUE)),
		'HCO31'=> clean_form_input($this->input->post('HCO31', TRUE)),
		'TCO21'=> clean_form_input($this->input->post('TCO21', TRUE)),
		'O2Sats1'=> clean_form_input($this->input->post('O2Sats1', TRUE)),
		'BE1'=> clean_form_input($this->input->post('BE1', TRUE)),
		'Na1'=> clean_form_input($this->input->post('Na1', TRUE)),
		'K1'=> clean_form_input($this->input->post('K1', TRUE)),
		'Cl1'=> clean_form_input($this->input->post('Cl1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'FiO22'=> clean_form_input($this->input->post('FiO22', TRUE)),
		'Temp2'=> clean_form_input($this->input->post('Temp2', TRUE)),
		'pH2'=> clean_form_input($this->input->post('pH2', TRUE)),
		'pCO22'=> clean_form_input($this->input->post('pCO22', TRUE)),
		'pO22'=> clean_form_input($this->input->post('pO22', TRUE)),
		'HCO32'=> clean_form_input($this->input->post('HCO32', TRUE)),
		'TCO22'=> clean_form_input($this->input->post('TCO22', TRUE)),
		'O2Sats2'=> clean_form_input($this->input->post('O2Sats2', TRUE)),
		'BE2'=> clean_form_input($this->input->post('BE2', TRUE)),
		'Na2'=> clean_form_input($this->input->post('Na2', TRUE)),
		'K2'=> clean_form_input($this->input->post('K2', TRUE)),
		'Cl2'=> clean_form_input($this->input->post('Cl2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'FiO23'=> clean_form_input($this->input->post('FiO23', TRUE)),
		'Temp3'=> clean_form_input($this->input->post('Temp3', TRUE)),
		'pH3'=> clean_form_input($this->input->post('pH3', TRUE)),
		'pCO23'=> clean_form_input($this->input->post('pCO23', TRUE)),
		'pO23'=> clean_form_input($this->input->post('pO23', TRUE)),
		'HCO33'=> clean_form_input($this->input->post('HCO33', TRUE)),
		'TCO23'=> clean_form_input($this->input->post('TCO23', TRUE)),
		'O2Sats3'=> clean_form_input($this->input->post('O2Sats3', TRUE)),
		'BE3'=> clean_form_input($this->input->post('BE3', TRUE)),
		'Na3'=> clean_form_input($this->input->post('Na3', TRUE)),
		'K3'=> clean_form_input($this->input->post('K3', TRUE)),
		'Cl3'=> clean_form_input($this->input->post('Cl3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'FiO24'=> clean_form_input($this->input->post('FiO24', TRUE)),
		'Temp4'=> clean_form_input($this->input->post('Temp4', TRUE)),
		'pH4'=> clean_form_input($this->input->post('pH4', TRUE)),
		'pCO24'=> clean_form_input($this->input->post('pCO24', TRUE)),
		'pO24'=> clean_form_input($this->input->post('pO24', TRUE)),
		'HCO34'=> clean_form_input($this->input->post('HCO34', TRUE)),
		'TCO24'=> clean_form_input($this->input->post('TCO24', TRUE)),
		'O2Sats4'=> clean_form_input($this->input->post('O2Sats4', TRUE)),
		'BE4'=> clean_form_input($this->input->post('BE4', TRUE)),
		'Na4'=> clean_form_input($this->input->post('Na4', TRUE)),
		'K4'=> clean_form_input($this->input->post('K4', TRUE)),
		'Cl4'=> clean_form_input($this->input->post('Cl4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'FiO25'=> clean_form_input($this->input->post('FiO25', TRUE)),
		'Temp5'=> clean_form_input($this->input->post('Temp5', TRUE)),
		'pH5'=> clean_form_input($this->input->post('pH5', TRUE)),
		'pCO25'=> clean_form_input($this->input->post('pCO25', TRUE)),
		'pO25'=> clean_form_input($this->input->post('pO25', TRUE)),
		'HCO35'=> clean_form_input($this->input->post('HCO35', TRUE)),
		'TCO25'=> clean_form_input($this->input->post('TCO25', TRUE)),
		'O2Sats5'=> clean_form_input($this->input->post('O2Sats5', TRUE)),
		'BE5'=> clean_form_input($this->input->post('BE5', TRUE)),
		'Na5'=> clean_form_input($this->input->post('Na5', TRUE)),
		'K5'=> clean_form_input($this->input->post('K5', TRUE)),
		'Cl5'=> clean_form_input($this->input->post('Cl5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'FiO26'=> clean_form_input($this->input->post('FiO26', TRUE)),
		'Temp6'=> clean_form_input($this->input->post('Temp6', TRUE)),
		'pH6'=> clean_form_input($this->input->post('pH6', TRUE)),
		'pCO26'=> clean_form_input($this->input->post('pCO26', TRUE)),
		'pO26'=> clean_form_input($this->input->post('pO26', TRUE)),
		'HCO36'=> clean_form_input($this->input->post('HCO36', TRUE)),
		'TCO26'=> clean_form_input($this->input->post('TCO26', TRUE)),
		'O2Sats6'=> clean_form_input($this->input->post('O2Sats6', TRUE)),
		'BE6'=> clean_form_input($this->input->post('BE6', TRUE)),
		'Na6'=> clean_form_input($this->input->post('Na6', TRUE)),
		'K6'=> clean_form_input($this->input->post('K6', TRUE)),
		'Cl6'=> clean_form_input($this->input->post('Cl6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'FiO27'=> clean_form_input($this->input->post('FiO27', TRUE)),
		'Temp7'=> clean_form_input($this->input->post('Temp7', TRUE)),
		'pH7'=> clean_form_input($this->input->post('pH7', TRUE)),
		'pCO27'=> clean_form_input($this->input->post('pCO27', TRUE)),
		'pO27'=> clean_form_input($this->input->post('pO27', TRUE)),
		'HCO37'=> clean_form_input($this->input->post('HCO37', TRUE)),
		'TCO27'=> clean_form_input($this->input->post('TCO27', TRUE)),
		'O2Sats7'=> clean_form_input($this->input->post('O2Sats7', TRUE)),
		'BE7'=> clean_form_input($this->input->post('BE7', TRUE)),
		'Na7'=> clean_form_input($this->input->post('Na7', TRUE)),
		'K7'=> clean_form_input($this->input->post('K7', TRUE)),
		'Cl7'=> clean_form_input($this->input->post('Cl7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'FiO28'=> clean_form_input($this->input->post('FiO28', TRUE)),
		'Temp8'=> clean_form_input($this->input->post('Temp8', TRUE)),
		'pH8'=> clean_form_input($this->input->post('pH8', TRUE)),
		'pCO28'=> clean_form_input($this->input->post('pCO28', TRUE)),
		'pO28'=> clean_form_input($this->input->post('pO28', TRUE)),
		'HCO38'=> clean_form_input($this->input->post('HCO38', TRUE)),
		'TCO28'=> clean_form_input($this->input->post('TCO28', TRUE)),
		'O2Sats8'=> clean_form_input($this->input->post('O2Sats8', TRUE)),
		'BE8'=> clean_form_input($this->input->post('BE8', TRUE)),
		'Na8'=> clean_form_input($this->input->post('Na8', TRUE)),
		'K8'=> clean_form_input($this->input->post('K8', TRUE)),
		'Cl8'=> clean_form_input($this->input->post('Cl8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'FiO29'=> clean_form_input($this->input->post('FiO29', TRUE)),
		'Temp9'=> clean_form_input($this->input->post('Temp9', TRUE)),
		'pH9'=> clean_form_input($this->input->post('pH9', TRUE)),
		'pCO29'=> clean_form_input($this->input->post('pCO29', TRUE)),
		'pO29'=> clean_form_input($this->input->post('pO29', TRUE)),
		'HCO39'=> clean_form_input($this->input->post('HCO39', TRUE)),
		'TCO29'=> clean_form_input($this->input->post('TCO29', TRUE)),
		'O2Sats9'=> clean_form_input($this->input->post('O2Sats9', TRUE)),
		'BE9'=> clean_form_input($this->input->post('BE9', TRUE)),
		'Na9'=> clean_form_input($this->input->post('Na9', TRUE)),
		'K9'=> clean_form_input($this->input->post('K9', TRUE)),
		'Cl9'=> clean_form_input($this->input->post('Cl9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'FiO210'=> clean_form_input($this->input->post('FiO210', TRUE)),
		'Temp10'=> clean_form_input($this->input->post('Temp10', TRUE)),
		'pH10'=> clean_form_input($this->input->post('pH10', TRUE)),
		'pCO210'=> clean_form_input($this->input->post('pCO210', TRUE)),
		'pO210'=> clean_form_input($this->input->post('pO210', TRUE)),
		'HCO310'=> clean_form_input($this->input->post('HCO310', TRUE)),
		'TCO210'=> clean_form_input($this->input->post('TCO210', TRUE)),
		'O2Sats10'=> clean_form_input($this->input->post('O2Sats10', TRUE)),
		'BE10'=> clean_form_input($this->input->post('BE10', TRUE)),
		'Na10'=> clean_form_input($this->input->post('Na10', TRUE)),
		'K10'=> clean_form_input($this->input->post('K10', TRUE)),
		'Cl10'=> clean_form_input($this->input->post('Cl10', TRUE)),
		//col 11
		'Date11'=> clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=> clean_form_input($this->input->post('Time11', TRUE)),
		'FiO211'=> clean_form_input($this->input->post('FiO211', TRUE)),
		'Temp11'=> clean_form_input($this->input->post('Temp11', TRUE)),
		'pH11'=> clean_form_input($this->input->post('pH11', TRUE)),
		'pCO211'=> clean_form_input($this->input->post('pCO211', TRUE)),
		'pO211'=> clean_form_input($this->input->post('pO211', TRUE)),
		'HCO311'=> clean_form_input($this->input->post('HCO311', TRUE)),
		'TCO211'=> clean_form_input($this->input->post('TCO211', TRUE)),
		'O2Sats11'=> clean_form_input($this->input->post('O2Sats11', TRUE)),
		'BE11'=> clean_form_input($this->input->post('BE11', TRUE)),
		'Na11'=> clean_form_input($this->input->post('Na11', TRUE)),
		'K11'=> clean_form_input($this->input->post('K11', TRUE)),
		'Cl11'=> clean_form_input($this->input->post('Cl11', TRUE)),
		//col 12
		'Date12'=> clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=> clean_form_input($this->input->post('Time12', TRUE)),
		'FiO212'=> clean_form_input($this->input->post('FiO212', TRUE)),
		'Temp12'=> clean_form_input($this->input->post('Temp12', TRUE)),
		'pH12'=> clean_form_input($this->input->post('pH12', TRUE)),
		'pCO212'=> clean_form_input($this->input->post('pCO212', TRUE)),
		'pO212'=> clean_form_input($this->input->post('pO212', TRUE)),
		'HCO312'=> clean_form_input($this->input->post('HCO312', TRUE)),
		'TCO212'=> clean_form_input($this->input->post('TCO212', TRUE)),
		'O2Sats12'=> clean_form_input($this->input->post('O2Sats12', TRUE)),
		'BE12'=> clean_form_input($this->input->post('BE12', TRUE)),
		'Na12'=> clean_form_input($this->input->post('Na12', TRUE)),
		'K12'=> clean_form_input($this->input->post('K12', TRUE)),
		'Cl12'=> clean_form_input($this->input->post('Cl12', TRUE)),
		//col 13
		'Date13'=> clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=> clean_form_input($this->input->post('Time13', TRUE)),
		'FiO213'=> clean_form_input($this->input->post('FiO213', TRUE)),
		'Temp13'=> clean_form_input($this->input->post('Temp13', TRUE)),
		'pH13'=> clean_form_input($this->input->post('pH13', TRUE)),
		'pCO213'=> clean_form_input($this->input->post('pCO213', TRUE)),
		'pO213'=> clean_form_input($this->input->post('pO213', TRUE)),
		'HCO313'=> clean_form_input($this->input->post('HCO313', TRUE)),
		'TCO213'=> clean_form_input($this->input->post('TCO213', TRUE)),
		'O2Sats13'=> clean_form_input($this->input->post('O2Sats13', TRUE)),
		'BE13'=> clean_form_input($this->input->post('BE13', TRUE)),
		'Na13'=> clean_form_input($this->input->post('Na13', TRUE)),
		'K13'=> clean_form_input($this->input->post('K13', TRUE)),
		'Cl13'=> clean_form_input($this->input->post('Cl13', TRUE)),
		//col 14
		'Date14'=> clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=> clean_form_input($this->input->post('Time14', TRUE)),
		'FiO214'=> clean_form_input($this->input->post('FiO214', TRUE)),
		'Temp14'=> clean_form_input($this->input->post('Temp14', TRUE)),
		'pH14'=> clean_form_input($this->input->post('pH14', TRUE)),
		'pCO214'=> clean_form_input($this->input->post('pCO214', TRUE)),
		'pO214'=> clean_form_input($this->input->post('pO214', TRUE)),
		'HCO314'=> clean_form_input($this->input->post('HCO314', TRUE)),
		'TCO214'=> clean_form_input($this->input->post('TCO214', TRUE)),
		'O2Sats14'=> clean_form_input($this->input->post('O2Sats14', TRUE)),
		'BE14'=> clean_form_input($this->input->post('BE14', TRUE)),
		'Na14'=> clean_form_input($this->input->post('Na14', TRUE)),
		'K14'=> clean_form_input($this->input->post('K14', TRUE)),
		'Cl14'=> clean_form_input($this->input->post('Cl14', TRUE)),
		//col 15
		'Date15'=> clean_form_input($this->input->post('Date15', TRUE)),
		'Time15'=> clean_form_input($this->input->post('Time15', TRUE)),
		'FiO215'=> clean_form_input($this->input->post('FiO215', TRUE)),
		'Temp15'=> clean_form_input($this->input->post('Temp15', TRUE)),
		'pH15'=> clean_form_input($this->input->post('pH15', TRUE)),
		'pCO215'=> clean_form_input($this->input->post('pCO215', TRUE)),
		'pO215'=> clean_form_input($this->input->post('pO215', TRUE)),
		'HCO315'=> clean_form_input($this->input->post('HCO315', TRUE)),
		'TCO215'=> clean_form_input($this->input->post('TCO215', TRUE)),
		'O2Sats15'=> clean_form_input($this->input->post('O2Sats15', TRUE)),
		'BE15'=> clean_form_input($this->input->post('BE15', TRUE)),
		'Na15'=> clean_form_input($this->input->post('Na15', TRUE)),
		'K15'=> clean_form_input($this->input->post('K15', TRUE)),
		'Cl15'=> clean_form_input($this->input->post('Cl15', TRUE)),
		);


	 $cs_abg = implode(",", $abg);
                $data = array(
                               'abg2'=>$cs_abg
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit abg2 id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   

	}
	function edit_fecalysis2($aid)
       	{
	 $fecalysis = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Appearance1'=> clean_form_input($this->input->post('Appearance1', TRUE)),
		'Ova1'=> clean_form_input($this->input->post('Ova1', TRUE)),
		'RBC1'=> clean_form_input($this->input->post('RBC1', TRUE)),
		'WBC1'=> clean_form_input($this->input->post('WBC1', TRUE)),
		'Occult_Blood1'=> clean_form_input($this->input->post('Occult_Blood1', TRUE)),
		'Others1'=> clean_form_input($this->input->post('Others1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Appearance2'=> clean_form_input($this->input->post('Appearance2', TRUE)),
		'Ova2'=> clean_form_input($this->input->post('Ova2', TRUE)),
		'RBC2'=> clean_form_input($this->input->post('RBC2', TRUE)),
		'WBC2'=> clean_form_input($this->input->post('WBC2', TRUE)),
		'Occult_Blood2'=> clean_form_input($this->input->post('Occult_Blood2', TRUE)),
		'Others2'=> clean_form_input($this->input->post('Others2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Appearance3'=> clean_form_input($this->input->post('Appearance3', TRUE)),
		'Ova3'=> clean_form_input($this->input->post('Ova3', TRUE)),
		'RBC3'=> clean_form_input($this->input->post('RBC3', TRUE)),
		'WBC3'=> clean_form_input($this->input->post('WBC3', TRUE)),
		'Occult_Blood3'=> clean_form_input($this->input->post('Occult_Blood3', TRUE)),
		'Others3'=> clean_form_input($this->input->post('Others3', TRUE)),
		//col 4

		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Appearance4'=> clean_form_input($this->input->post('Appearance4', TRUE)),
		'Ova4'=> clean_form_input($this->input->post('Ova4', TRUE)),
		'RBC4'=> clean_form_input($this->input->post('RBC4', TRUE)),
		'WBC4'=> clean_form_input($this->input->post('WBC4', TRUE)),
		'Occult_Blood4'=> clean_form_input($this->input->post('Occult_Blood4', TRUE)),
		'Others4'=> clean_form_input($this->input->post('Others4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Appearance5'=> clean_form_input($this->input->post('Appearance5', TRUE)),
		'Ova5'=> clean_form_input($this->input->post('Ova5', TRUE)),
		'RBC5'=> clean_form_input($this->input->post('RBC5', TRUE)),
		'WBC5'=> clean_form_input($this->input->post('WBC5', TRUE)),
		'Occult_Blood5'=> clean_form_input($this->input->post('Occult_Blood5', TRUE)),
		'Others5'=> clean_form_input($this->input->post('Others5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Appearance6'=> clean_form_input($this->input->post('Appearance6', TRUE)),
		'Ova6'=> clean_form_input($this->input->post('Ova6', TRUE)),
		'RBC6'=> clean_form_input($this->input->post('RBC6', TRUE)),
		'WBC6'=> clean_form_input($this->input->post('WBC6', TRUE)),
		'Occult_Blood6'=> clean_form_input($this->input->post('Occult_Blood6', TRUE)),
		'Others6'=> clean_form_input($this->input->post('Others6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Appearance7'=> clean_form_input($this->input->post('Appearance7', TRUE)),
		'Ova7'=> clean_form_input($this->input->post('Ova7', TRUE)),
		'RBC7'=> clean_form_input($this->input->post('RBC7', TRUE)),
		'WBC7'=> clean_form_input($this->input->post('WBC7', TRUE)),
		'Occult_Blood7'=> clean_form_input($this->input->post('Occult_Blood7', TRUE)),
		'Others7'=> clean_form_input($this->input->post('Others7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Appearance8'=> clean_form_input($this->input->post('Appearance8', TRUE)),
		'Ova8'=> clean_form_input($this->input->post('Ova8', TRUE)),
		'RBC8'=> clean_form_input($this->input->post('RBC8', TRUE)),
		'WBC8'=> clean_form_input($this->input->post('WBC8', TRUE)),
		'Occult_Blood8'=> clean_form_input($this->input->post('Occult_Blood8', TRUE)),
		'Others8'=> clean_form_input($this->input->post('Others8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Appearance9'=> clean_form_input($this->input->post('Appearance9', TRUE)),
		'Ova9'=> clean_form_input($this->input->post('Ova9', TRUE)),
		'RBC9'=> clean_form_input($this->input->post('RBC9', TRUE)),
		'WBC9'=> clean_form_input($this->input->post('WBC9', TRUE)),
		'Occult_Blood9'=> clean_form_input($this->input->post('Occult_Blood9', TRUE)),
		'Others9'=> clean_form_input($this->input->post('Others9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Appearance10'=> clean_form_input($this->input->post('Appearance10', TRUE)),
		'Ova10'=> clean_form_input($this->input->post('Ova10', TRUE)),
		'RBC10'=> clean_form_input($this->input->post('RBC10', TRUE)),
		'WBC10'=> clean_form_input($this->input->post('WBC10', TRUE)),
		'Occult_Blood10'=> clean_form_input($this->input->post('Occult_Blood10', TRUE)),
		'Others10'=> clean_form_input($this->input->post('Others10', TRUE)),

		);

	 $cs_fecalysis = implode(",", $fecalysis);
                $data = array(
                               'fecalysis2'=>$cs_fecalysis
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit fecalysis2 id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   


	}
	function edit_uchem2($aid)
       	{
	 $uchem = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Total_Volume1'=> clean_form_input($this->input->post('Total_Volume1', TRUE)), 
		'Creatinine1'=> clean_form_input($this->input->post('Creatinine1', TRUE)),
		'Total_Protein1'=> clean_form_input($this->input->post('Total_Protein1', TRUE)),
		'Na1'=> clean_form_input($this->input->post('Na1', TRUE)),
		'K1'=> clean_form_input($this->input->post('K1', TRUE)),
		'Cl1'=> clean_form_input($this->input->post('Cl1', TRUE)),
		'Uric_Acid1'=> clean_form_input($this->input->post('Uric_Acid1', TRUE)),
		'Ca1'=> clean_form_input($this->input->post('Ca1', TRUE)),
		'Phosphorus1'=> clean_form_input($this->input->post('Phosphorus1', TRUE)),
		'Amylase1'=> clean_form_input($this->input->post('Amylase1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Total_Volume2'=> clean_form_input($this->input->post('Total_Volume2', TRUE)), 
		'Creatinine2'=> clean_form_input($this->input->post('Creatinine2', TRUE)),
		'Total_Protein2'=> clean_form_input($this->input->post('Total_Protein2', TRUE)),
		'Na2'=> clean_form_input($this->input->post('Na2', TRUE)),
		'K2'=> clean_form_input($this->input->post('K2', TRUE)),
		'Cl2'=> clean_form_input($this->input->post('Cl2', TRUE)),
		'Uric_Acid2'=> clean_form_input($this->input->post('Uric_Acid2', TRUE)),
		'Ca2'=> clean_form_input($this->input->post('Ca2', TRUE)),
		'Phosphorus2'=> clean_form_input($this->input->post('Phosphorus2', TRUE)),
		'Amylase2'=> clean_form_input($this->input->post('Amylase2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Total_Volume3'=> clean_form_input($this->input->post('Total_Volume3', TRUE)), 
		'Creatinine3'=> clean_form_input($this->input->post('Creatinine3', TRUE)),
		'Total_Protein3'=> clean_form_input($this->input->post('Total_Protein3', TRUE)),
		'Na3'=> clean_form_input($this->input->post('Na3', TRUE)),
		'K3'=> clean_form_input($this->input->post('K3', TRUE)),
		'Cl3'=> clean_form_input($this->input->post('Cl3', TRUE)),
		'Uric_Acid3'=> clean_form_input($this->input->post('Uric_Acid3', TRUE)),
		'Ca3'=> clean_form_input($this->input->post('Ca3', TRUE)),
		'Phosphorus3'=> clean_form_input($this->input->post('Phosphorus3', TRUE)),
		'Amylase3'=> clean_form_input($this->input->post('Amylase3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Total_Volume4'=> clean_form_input($this->input->post('Total_Volume4', TRUE)), 
		'Creatinine4'=> clean_form_input($this->input->post('Creatinine4', TRUE)),
		'Total_Protein4'=> clean_form_input($this->input->post('Total_Protein4', TRUE)),
		'Na4'=> clean_form_input($this->input->post('Na4', TRUE)),
		'K4'=> clean_form_input($this->input->post('K4', TRUE)),
		'Cl4'=> clean_form_input($this->input->post('Cl4', TRUE)),
		'Uric_Acid4'=> clean_form_input($this->input->post('Uric_Acid4', TRUE)),
		'Ca4'=> clean_form_input($this->input->post('Ca4', TRUE)),
		'Phosphorus4'=> clean_form_input($this->input->post('Phosphorus4', TRUE)),
		'Amylase4'=> clean_form_input($this->input->post('Amylase4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Total_Volume5'=> clean_form_input($this->input->post('Total_Volume5', TRUE)), 
		'Creatinine5'=> clean_form_input($this->input->post('Creatinine5', TRUE)),
		'Total_Protein5'=> clean_form_input($this->input->post('Total_Protein5', TRUE)),
		'Na5'=> clean_form_input($this->input->post('Na5', TRUE)),
		'K5'=> clean_form_input($this->input->post('K5', TRUE)),
		'Cl5'=> clean_form_input($this->input->post('Cl5', TRUE)),
		'Uric_Acid5'=> clean_form_input($this->input->post('Uric_Acid5', TRUE)),
		'Ca5'=> clean_form_input($this->input->post('Ca5', TRUE)),
		'Phosphorus5'=> clean_form_input($this->input->post('Phosphorus5', TRUE)),
		'Amylase5'=> clean_form_input($this->input->post('Amylase5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Total_Volume6'=> clean_form_input($this->input->post('Total_Volume6', TRUE)), 
		'Creatinine6'=> clean_form_input($this->input->post('Creatinine6', TRUE)),
		'Total_Protein6'=> clean_form_input($this->input->post('Total_Protein6', TRUE)),
		'Na6'=> clean_form_input($this->input->post('Na6', TRUE)),
		'K6'=> clean_form_input($this->input->post('K6', TRUE)),
		'Cl6'=> clean_form_input($this->input->post('Cl6', TRUE)),
		'Uric_Acid6'=> clean_form_input($this->input->post('Uric_Acid6', TRUE)),
		'Ca6'=> clean_form_input($this->input->post('Ca6', TRUE)),
		'Phosphorus6'=> clean_form_input($this->input->post('Phosphorus6', TRUE)),
		'Amylase6'=> clean_form_input($this->input->post('Amylase6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Total_Volume7'=> clean_form_input($this->input->post('Total_Volume7', TRUE)), 
		'Creatinine7'=> clean_form_input($this->input->post('Creatinine7', TRUE)),
		'Total_Protein7'=> clean_form_input($this->input->post('Total_Protein7', TRUE)),
		'Na7'=> clean_form_input($this->input->post('Na7', TRUE)),
		'K7'=> clean_form_input($this->input->post('K7', TRUE)),
		'Cl7'=> clean_form_input($this->input->post('Cl7', TRUE)),
		'Uric_Acid7'=> clean_form_input($this->input->post('Uric_Acid7', TRUE)),
		'Ca7'=> clean_form_input($this->input->post('Ca7', TRUE)),
		'Phosphorus7'=> clean_form_input($this->input->post('Phosphorus7', TRUE)),
		'Amylase7'=> clean_form_input($this->input->post('Amylase7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Total_Volume8'=> clean_form_input($this->input->post('Total_Volume8', TRUE)), 
		'Creatinine8'=> clean_form_input($this->input->post('Creatinine8', TRUE)),
		'Total_Protein8'=> clean_form_input($this->input->post('Total_Protein8', TRUE)),
		'Na8'=> clean_form_input($this->input->post('Na8', TRUE)),
		'K8'=> clean_form_input($this->input->post('K8', TRUE)),
		'Cl8'=> clean_form_input($this->input->post('Cl8', TRUE)),
		'Uric_Acid8'=> clean_form_input($this->input->post('Uric_Acid8', TRUE)),
		'Ca8'=> clean_form_input($this->input->post('Ca8', TRUE)),
		'Phosphorus8'=> clean_form_input($this->input->post('Phosphorus8', TRUE)),
		'Amylase8'=> clean_form_input($this->input->post('Amylase8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Total_Volume9'=> clean_form_input($this->input->post('Total_Volume9', TRUE)), 
		'Creatinine9'=> clean_form_input($this->input->post('Creatinine9', TRUE)),
		'Total_Protein9'=> clean_form_input($this->input->post('Total_Protein9', TRUE)),
		'Na9'=> clean_form_input($this->input->post('Na9', TRUE)),
		'K9'=> clean_form_input($this->input->post('K9', TRUE)),
		'Cl9'=> clean_form_input($this->input->post('Cl9', TRUE)),
		'Uric_Acid9'=> clean_form_input($this->input->post('Uric_Acid9', TRUE)),
		'Ca9'=> clean_form_input($this->input->post('Ca9', TRUE)),
		'Phosphorus9'=> clean_form_input($this->input->post('Phosphorus9', TRUE)),
		'Amylase9'=> clean_form_input($this->input->post('Amylase9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Total_Volume10'=> clean_form_input($this->input->post('Total_Volume10', TRUE)), 
		'Creatinine10'=> clean_form_input($this->input->post('Creatinine10', TRUE)),
		'Total_Protein10'=> clean_form_input($this->input->post('Total_Protein10', TRUE)),
		'Na10'=> clean_form_input($this->input->post('Na10', TRUE)),
		'K10'=> clean_form_input($this->input->post('K10', TRUE)),
		'Cl10'=> clean_form_input($this->input->post('Cl10', TRUE)),
		'Uric_Acid10'=> clean_form_input($this->input->post('Uric_Acid10', TRUE)),
		'Ca10'=> clean_form_input($this->input->post('Ca10', TRUE)),
		'Phosphorus10'=> clean_form_input($this->input->post('Phosphorus10', TRUE)),
		'Amylase10'=> clean_form_input($this->input->post('Amylase10', TRUE)),

		);
	 $cs_uchem = implode(",", $uchem);
                $data = array(
                               'uchem2'=>$cs_uchem
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit uchem2 id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   
	}
	function edit_culture2($aid)
       	{
	 $culture = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Specimen1'=> clean_form_input($this->input->post('Specimen1', TRUE)),
		'PMN1'=> clean_form_input($this->input->post('PMN1', TRUE)),
		'Epith_Cell1'=> clean_form_input($this->input->post('Epith_Cell1', TRUE)),
		'Gram_Stain1'=> clean_form_input($this->input->post('Gram_Stain1', TRUE)),
		'Growth1'=> clean_form_input($this->input->post('Growth1', TRUE)),
		'Organisms1'=> clean_form_input($this->input->post('Organisms1', TRUE)),
		'Susceptible1'=> clean_form_input($this->input->post('Susceptible1', TRUE)),
		'Intermediate1'=> clean_form_input($this->input->post('Intermediate1', TRUE)),
		'Resistant1'=> clean_form_input($this->input->post('Resistant1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Specimen2'=> clean_form_input($this->input->post('Specimen2', TRUE)),
		'PMN2'=> clean_form_input($this->input->post('PMN2', TRUE)),
		'Epith_Cell2'=> clean_form_input($this->input->post('Epith_Cell2', TRUE)),
		'Gram_Stain2'=> clean_form_input($this->input->post('Gram_Stain2', TRUE)),
		'Growth2'=> clean_form_input($this->input->post('Growth2', TRUE)),
		'Organisms2'=> clean_form_input($this->input->post('Organisms2', TRUE)),
		'Susceptible2'=> clean_form_input($this->input->post('Susceptible2', TRUE)),
		'Intermediate2'=> clean_form_input($this->input->post('Intermediate2', TRUE)),
		'Resistant2'=> clean_form_input($this->input->post('Resistant2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Specimen3'=> clean_form_input($this->input->post('Specimen3', TRUE)),
		'PMN3'=> clean_form_input($this->input->post('PMN3', TRUE)),
		'Epith_Cell3'=> clean_form_input($this->input->post('Epith_Cell3', TRUE)),
		'Gram_Stain3'=> clean_form_input($this->input->post('Gram_Stain3', TRUE)),
		'Growth3'=> clean_form_input($this->input->post('Growth3', TRUE)),
		'Organisms3'=> clean_form_input($this->input->post('Organisms3', TRUE)),
		'Susceptible3'=> clean_form_input($this->input->post('Susceptible3', TRUE)),
		'Intermediate3'=> clean_form_input($this->input->post('Intermediate3', TRUE)),
		'Resistant3'=> clean_form_input($this->input->post('Resistant3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Specimen4'=> clean_form_input($this->input->post('Specimen4', TRUE)),
		'PMN4'=> clean_form_input($this->input->post('PMN4', TRUE)),
		'Epith_Cell4'=> clean_form_input($this->input->post('Epith_Cell4', TRUE)),
		'Gram_Stain4'=> clean_form_input($this->input->post('Gram_Stain4', TRUE)),
		'Growth4'=> clean_form_input($this->input->post('Growth4', TRUE)),
		'Organisms4'=> clean_form_input($this->input->post('Organisms4', TRUE)),
		'Susceptible4'=> clean_form_input($this->input->post('Susceptible4', TRUE)),
		'Intermediate4'=> clean_form_input($this->input->post('Intermediate4', TRUE)),
		'Resistant4'=> clean_form_input($this->input->post('Resistant4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Specimen5'=> clean_form_input($this->input->post('Specimen5', TRUE)),
		'PMN5'=> clean_form_input($this->input->post('PMN5', TRUE)),
		'Epith_Cell5'=> clean_form_input($this->input->post('Epith_Cell5', TRUE)),
		'Gram_Stain5'=> clean_form_input($this->input->post('Gram_Stain5', TRUE)),
		'Growth5'=> clean_form_input($this->input->post('Growth5', TRUE)),
		'Organisms5'=> clean_form_input($this->input->post('Organisms5', TRUE)),
		'Susceptible5'=> clean_form_input($this->input->post('Susceptible5', TRUE)),
		'Intermediate5'=> clean_form_input($this->input->post('Intermediate5', TRUE)),
		'Resistant5'=> clean_form_input($this->input->post('Resistant5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Specimen6'=> clean_form_input($this->input->post('Specimen6', TRUE)),
		'PMN6'=> clean_form_input($this->input->post('PMN6', TRUE)),
		'Epith_Cell6'=> clean_form_input($this->input->post('Epith_Cell6', TRUE)),
		'Gram_Stain6'=> clean_form_input($this->input->post('Gram_Stain6', TRUE)),
		'Growth6'=> clean_form_input($this->input->post('Growth6', TRUE)),
		'Organisms6'=> clean_form_input($this->input->post('Organisms6', TRUE)),
		'Susceptible6'=> clean_form_input($this->input->post('Susceptible6', TRUE)),
		'Intermediate6'=> clean_form_input($this->input->post('Intermediate6', TRUE)),
		'Resistant6'=> clean_form_input($this->input->post('Resistant6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Specimen7'=> clean_form_input($this->input->post('Specimen7', TRUE)),
		'PMN7'=> clean_form_input($this->input->post('PMN7', TRUE)),
		'Epith_Cell7'=> clean_form_input($this->input->post('Epith_Cell7', TRUE)),
		'Gram_Stain7'=> clean_form_input($this->input->post('Gram_Stain7', TRUE)),
		'Growth7'=> clean_form_input($this->input->post('Growth7', TRUE)),
		'Organisms7'=> clean_form_input($this->input->post('Organisms7', TRUE)),
		'Susceptible7'=> clean_form_input($this->input->post('Susceptible7', TRUE)),
		'Intermediate7'=> clean_form_input($this->input->post('Intermediate7', TRUE)),
		'Resistant7'=> clean_form_input($this->input->post('Resistant7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Specimen8'=> clean_form_input($this->input->post('Specimen8', TRUE)),
		'PMN8'=> clean_form_input($this->input->post('PMN8', TRUE)),
		'Epith_Cell8'=> clean_form_input($this->input->post('Epith_Cell8', TRUE)),
		'Gram_Stain8'=> clean_form_input($this->input->post('Gram_Stain8', TRUE)),
		'Growth8'=> clean_form_input($this->input->post('Growth8', TRUE)),
		'Organisms8'=> clean_form_input($this->input->post('Organisms8', TRUE)),
		'Susceptible8'=> clean_form_input($this->input->post('Susceptible8', TRUE)),
		'Intermediate8'=> clean_form_input($this->input->post('Intermediate8', TRUE)),
		'Resistant8'=> clean_form_input($this->input->post('Resistant8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Specimen9'=> clean_form_input($this->input->post('Specimen9', TRUE)),
		'PMN9'=> clean_form_input($this->input->post('PMN9', TRUE)),
		'Epith_Cell9'=> clean_form_input($this->input->post('Epith_Cell9', TRUE)),
		'Gram_Stain9'=> clean_form_input($this->input->post('Gram_Stain9', TRUE)),
		'Growth9'=> clean_form_input($this->input->post('Growth9', TRUE)),
		'Organisms9'=> clean_form_input($this->input->post('Organisms9', TRUE)),
		'Susceptible9'=> clean_form_input($this->input->post('Susceptible9', TRUE)),
		'Intermediate9'=> clean_form_input($this->input->post('Intermediate9', TRUE)),
		'Resistant9'=> clean_form_input($this->input->post('Resistant9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Specimen10'=> clean_form_input($this->input->post('Specimen10', TRUE)),
		'PMN10'=> clean_form_input($this->input->post('PMN10', TRUE)),
		'Epith_Cell10'=> clean_form_input($this->input->post('Epith_Cell10', TRUE)),
		'Gram_Stain10'=> clean_form_input($this->input->post('Gram_Stain10', TRUE)),
		'Growth10'=> clean_form_input($this->input->post('Growth10', TRUE)),
		'Organisms10'=> clean_form_input($this->input->post('Organisms10', TRUE)),
		'Susceptible10'=> clean_form_input($this->input->post('Susceptible10', TRUE)),
		'Intermediate10'=> clean_form_input($this->input->post('Intermediate10', TRUE)),
		'Resistant10'=> clean_form_input($this->input->post('Resistant10', TRUE)),

		);
	 $cs_culture = implode(",", $culture);
                $data = array(
                               'culture2'=>$cs_culture
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit culture2 id#".$aid.", date_in:".$this->date_in."\r\n"; 

	 
	}
	function edit_imaging2($aid)
       	{
	 $imaging = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Test1'=> clean_form_input($this->input->post('Test1', TRUE)),
		'Body_Part1'=> clean_form_input($this->input->post('Body_Part1', TRUE)),
		'Reading1'=> clean_form_input($this->input->post('Reading1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Test2'=> clean_form_input($this->input->post('Test2', TRUE)),
		'Body_Part2'=> clean_form_input($this->input->post('Body_Part2', TRUE)),
		'Reading2'=> clean_form_input($this->input->post('Reading2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Test3'=> clean_form_input($this->input->post('Test3', TRUE)),
		'Body_Part3'=> clean_form_input($this->input->post('Body_Part3', TRUE)),
		'Reading3'=> clean_form_input($this->input->post('Reading3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Test4'=> clean_form_input($this->input->post('Test4', TRUE)),
		'Body_Part4'=> clean_form_input($this->input->post('Body_Part4', TRUE)),
		'Reading4'=> clean_form_input($this->input->post('Reading4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Test5'=> clean_form_input($this->input->post('Test5', TRUE)),
		'Body_Part5'=> clean_form_input($this->input->post('Body_Part5', TRUE)),
		'Reading5'=> clean_form_input($this->input->post('Reading5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Test6'=> clean_form_input($this->input->post('Test6', TRUE)),
		'Body_Part6'=> clean_form_input($this->input->post('Body_Part6', TRUE)),
		'Reading6'=> clean_form_input($this->input->post('Reading6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Test7'=> clean_form_input($this->input->post('Test7', TRUE)),
		'Body_Part7'=> clean_form_input($this->input->post('Body_Part7', TRUE)),
		'Reading7'=> clean_form_input($this->input->post('Reading7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Test8'=> clean_form_input($this->input->post('Test8', TRUE)),
		'Body_Part8'=> clean_form_input($this->input->post('Body_Part8', TRUE)),
		'Reading8'=> clean_form_input($this->input->post('Reading8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Test9'=> clean_form_input($this->input->post('Test9', TRUE)),
		'Body_Part9'=> clean_form_input($this->input->post('Body_Part9', TRUE)),
		'Reading9'=> clean_form_input($this->input->post('Reading9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Test10'=> clean_form_input($this->input->post('Test10', TRUE)),
		'Body_Part10'=> clean_form_input($this->input->post('Body_Part10', TRUE)),
		'Reading10'=> clean_form_input($this->input->post('Reading10', TRUE)),
		//col 11
		'Date11'=> clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=> clean_form_input($this->input->post('Time11', TRUE)),
		'Test11'=> clean_form_input($this->input->post('Test11', TRUE)),
		'Body_Part11'=> clean_form_input($this->input->post('Body_Part11', TRUE)),
		'Reading11'=> clean_form_input($this->input->post('Reading11', TRUE)),
		//col 12
		'Date12'=> clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=> clean_form_input($this->input->post('Time12', TRUE)),
		'Test12'=> clean_form_input($this->input->post('Test12', TRUE)),
		'Body_Part12'=> clean_form_input($this->input->post('Body_Part12', TRUE)),
		'Reading12'=> clean_form_input($this->input->post('Reading12', TRUE)),
		//col 13
		'Date13'=> clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=> clean_form_input($this->input->post('Time13', TRUE)),
		'Test13'=> clean_form_input($this->input->post('Test13', TRUE)),
		'Body_Part13'=> clean_form_input($this->input->post('Body_Part13', TRUE)),
		'Reading13'=> clean_form_input($this->input->post('Reading13', TRUE)),
		//col 14
		'Date14'=> clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=> clean_form_input($this->input->post('Time14', TRUE)),
		'Test14'=> clean_form_input($this->input->post('Test14', TRUE)),
		'Body_Part14'=> clean_form_input($this->input->post('Body_Part14', TRUE)),
		'Reading14'=> clean_form_input($this->input->post('Reading14', TRUE)),
		//col 15
		'Date15'=> clean_form_input($this->input->post('Date15', TRUE)),
		'Time15'=> clean_form_input($this->input->post('Time15', TRUE)),
		'Test15'=> clean_form_input($this->input->post('Test15', TRUE)),
		'Body_Part15'=> clean_form_input($this->input->post('Body_Part15', TRUE)),
		'Reading15'=> clean_form_input($this->input->post('Reading15', TRUE)),

		);
	  $cs_imaging = implode(",", $imaging);
                $data = array(
                               'imaging2'=>$cs_imaging
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit imaging2 id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   
	 }
	function edit_ecg2($aid)
       	{
	 $ecg = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Test1'=> clean_form_input($this->input->post('Test1', TRUE)),
		'Reading1'=> clean_form_input($this->input->post('Reading1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Test2'=> clean_form_input($this->input->post('Test2', TRUE)),
		'Reading2'=> clean_form_input($this->input->post('Reading2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Test3'=> clean_form_input($this->input->post('Test3', TRUE)),
		'Reading3'=> clean_form_input($this->input->post('Reading3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Test4'=> clean_form_input($this->input->post('Test4', TRUE)),
		'Reading4'=> clean_form_input($this->input->post('Reading4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Test5'=> clean_form_input($this->input->post('Test5', TRUE)),
		'Reading5'=> clean_form_input($this->input->post('Reading5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Test6'=> clean_form_input($this->input->post('Test6', TRUE)),
		'Reading6'=> clean_form_input($this->input->post('Reading6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Test7'=> clean_form_input($this->input->post('Test7', TRUE)),
		'Reading7'=> clean_form_input($this->input->post('Reading7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Test8'=> clean_form_input($this->input->post('Test8', TRUE)),
		'Reading8'=> clean_form_input($this->input->post('Reading8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Test9'=> clean_form_input($this->input->post('Test9', TRUE)),
		'Reading9'=> clean_form_input($this->input->post('Reading9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Test10'=> clean_form_input($this->input->post('Test10', TRUE)),
		'Reading10'=> clean_form_input($this->input->post('Reading10', TRUE)),
		//col 11
		'Date11'=> clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=> clean_form_input($this->input->post('Time11', TRUE)),
		'Test11'=> clean_form_input($this->input->post('Test11', TRUE)),
		'Reading11'=> clean_form_input($this->input->post('Reading11', TRUE)),
		//col 12
		'Date12'=> clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=> clean_form_input($this->input->post('Time12', TRUE)),
		'Test12'=> clean_form_input($this->input->post('Test12', TRUE)),
		'Reading12'=> clean_form_input($this->input->post('Reading12', TRUE)),
		//col 13
		'Date13'=> clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=> clean_form_input($this->input->post('Time13', TRUE)),
		'Test13'=> clean_form_input($this->input->post('Test13', TRUE)),
		'Reading13'=> clean_form_input($this->input->post('Reading13', TRUE)),
		//col 14
		'Date14'=> clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=> clean_form_input($this->input->post('Time14', TRUE)),
		'Test14'=> clean_form_input($this->input->post('Test14', TRUE)),
		'Reading14'=> clean_form_input($this->input->post('Reading14', TRUE)),
		//col 15
		'Date15'=> clean_form_input($this->input->post('Date15', TRUE)),
		'Time15'=> clean_form_input($this->input->post('Time15', TRUE)),
		'Test15'=> clean_form_input($this->input->post('Test15', TRUE)),
		'Reading15'=> clean_form_input($this->input->post('Reading15', TRUE)),
		);
	  $cs_ecg = implode(",", $ecg);
                $data = array(
                               'ecg2'=>$cs_ecg
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit ecg2 id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   
	 }
	 function edit_others2($aid)
       	{
	 $others = array(
		//col 1
		'Date1'=> clean_form_input($this->input->post('Date1', TRUE)),
		'Time1'=> clean_form_input($this->input->post('Time1', TRUE)),
		'Test1'=> clean_form_input($this->input->post('Test1', TRUE)),
		'Results1'=> clean_form_input($this->input->post('Results1', TRUE)),
		//col 2
		'Date2'=> clean_form_input($this->input->post('Date2', TRUE)),
		'Time2'=> clean_form_input($this->input->post('Time2', TRUE)),
		'Test2'=> clean_form_input($this->input->post('Test2', TRUE)),
		'Results2'=> clean_form_input($this->input->post('Results2', TRUE)),
		//col 3
		'Date3'=> clean_form_input($this->input->post('Date3', TRUE)),
		'Time3'=> clean_form_input($this->input->post('Time3', TRUE)),
		'Test3'=> clean_form_input($this->input->post('Test3', TRUE)),
		'Results3'=> clean_form_input($this->input->post('Results3', TRUE)),
		//col 4
		'Date4'=> clean_form_input($this->input->post('Date4', TRUE)),
		'Time4'=> clean_form_input($this->input->post('Time4', TRUE)),
		'Test4'=> clean_form_input($this->input->post('Test4', TRUE)),
		'Results4'=> clean_form_input($this->input->post('Results4', TRUE)),
		//col 5
		'Date5'=> clean_form_input($this->input->post('Date5', TRUE)),
		'Time5'=> clean_form_input($this->input->post('Time5', TRUE)),
		'Test5'=> clean_form_input($this->input->post('Test5', TRUE)),
		'Results5'=> clean_form_input($this->input->post('Results5', TRUE)),
		//col 6
		'Date6'=> clean_form_input($this->input->post('Date6', TRUE)),
		'Time6'=> clean_form_input($this->input->post('Time6', TRUE)),
		'Test6'=> clean_form_input($this->input->post('Test6', TRUE)),
		'Results6'=> clean_form_input($this->input->post('Results6', TRUE)),
		//col 7
		'Date7'=> clean_form_input($this->input->post('Date7', TRUE)),
		'Time7'=> clean_form_input($this->input->post('Time7', TRUE)),
		'Test7'=> clean_form_input($this->input->post('Test7', TRUE)),
		'Results7'=> clean_form_input($this->input->post('Results7', TRUE)),
		//col 8
		'Date8'=> clean_form_input($this->input->post('Date8', TRUE)),
		'Time8'=> clean_form_input($this->input->post('Time8', TRUE)),
		'Test8'=> clean_form_input($this->input->post('Test8', TRUE)),
		'Results8'=> clean_form_input($this->input->post('Results8', TRUE)),
		//col 9
		'Date9'=> clean_form_input($this->input->post('Date9', TRUE)),
		'Time9'=> clean_form_input($this->input->post('Time9', TRUE)),
		'Test9'=> clean_form_input($this->input->post('Test9', TRUE)),
		'Results9'=> clean_form_input($this->input->post('Results9', TRUE)),
		//col 10
		'Date10'=> clean_form_input($this->input->post('Date10', TRUE)),
		'Time10'=> clean_form_input($this->input->post('Time10', TRUE)),
		'Test10'=> clean_form_input($this->input->post('Test10', TRUE)),
		'Results10'=> clean_form_input($this->input->post('Results10', TRUE)),
		//col 11
		'Date11'=> clean_form_input($this->input->post('Date11', TRUE)),
		'Time11'=> clean_form_input($this->input->post('Time11', TRUE)),
		'Test11'=> clean_form_input($this->input->post('Test11', TRUE)),
		'Results11'=> clean_form_input($this->input->post('Results11', TRUE)),
		//col 12
		'Date12'=> clean_form_input($this->input->post('Date12', TRUE)),
		'Time12'=> clean_form_input($this->input->post('Time12', TRUE)),
		'Test12'=> clean_form_input($this->input->post('Test12', TRUE)),
		'Results12'=> clean_form_input($this->input->post('Results12', TRUE)),
		//col 13
		'Date13'=> clean_form_input($this->input->post('Date13', TRUE)),
		'Time13'=> clean_form_input($this->input->post('Time13', TRUE)),
		'Test13'=> clean_form_input($this->input->post('Test13', TRUE)),
		'Results13'=> clean_form_input($this->input->post('Results13', TRUE)),
		//col 14
		'Date14'=> clean_form_input($this->input->post('Date14', TRUE)),
		'Time14'=> clean_form_input($this->input->post('Time14', TRUE)),
		'Test14'=> clean_form_input($this->input->post('Test14', TRUE)),
		'Results14'=> clean_form_input($this->input->post('Results14', TRUE)),
		//col 15
		'Date15'=> clean_form_input($this->input->post('Date15', TRUE)),
		'Time15'=> clean_form_input($this->input->post('Time15', TRUE)),
		'Test15'=> clean_form_input($this->input->post('Test15', TRUE)),
		'Results15'=> clean_form_input($this->input->post('Results15', TRUE)),
		//col 16
		'Date16'=> clean_form_input($this->input->post('Date16', TRUE)),
		'Time16'=> clean_form_input($this->input->post('Time16', TRUE)),
		'Test16'=> clean_form_input($this->input->post('Test16', TRUE)),
		'Results16'=> clean_form_input($this->input->post('Results16', TRUE)),
		//col 17
		'Date17'=> clean_form_input($this->input->post('Date17', TRUE)),
		'Time17'=> clean_form_input($this->input->post('Time17', TRUE)),
		'Test17'=> clean_form_input($this->input->post('Test17', TRUE)),
		'Results17'=> clean_form_input($this->input->post('Results17', TRUE)),
		//col 18
		'Date18'=> clean_form_input($this->input->post('Date18', TRUE)),
		'Time18'=> clean_form_input($this->input->post('Time18', TRUE)),
		'Test18'=> clean_form_input($this->input->post('Test18', TRUE)),
		'Results18'=> clean_form_input($this->input->post('Results18', TRUE)),
		//col 19
		'Date19'=> clean_form_input($this->input->post('Date19', TRUE)),
		'Time19'=> clean_form_input($this->input->post('Time19', TRUE)),
		'Test19'=> clean_form_input($this->input->post('Test19', TRUE)),
		'Results19'=> clean_form_input($this->input->post('Results19', TRUE)),
		//col 20
		'Date20'=> clean_form_input($this->input->post('Date20', TRUE)),
		'Time20'=> clean_form_input($this->input->post('Time20', TRUE)),
		'Test20'=> clean_form_input($this->input->post('Test20', TRUE)),
		'Results20'=> clean_form_input($this->input->post('Results20', TRUE)),
		//col 21
		'Date21'=> clean_form_input($this->input->post('Date21', TRUE)),
		'Time21'=> clean_form_input($this->input->post('Time21', TRUE)),
		'Test21'=> clean_form_input($this->input->post('Test21', TRUE)),
		'Results21'=> clean_form_input($this->input->post('Results21', TRUE)),
		//col 22
		'Date22'=> clean_form_input($this->input->post('Date22', TRUE)),
		'Time22'=> clean_form_input($this->input->post('Time22', TRUE)),
		'Test22'=> clean_form_input($this->input->post('Test22', TRUE)),
		'Results22'=> clean_form_input($this->input->post('Results22', TRUE)),
		//col 23
		'Date23'=> clean_form_input($this->input->post('Date23', TRUE)),
		'Time23'=> clean_form_input($this->input->post('Time23', TRUE)),
		'Test23'=> clean_form_input($this->input->post('Test23', TRUE)),
		'Results23'=> clean_form_input($this->input->post('Results23', TRUE)),
		//col 24
		'Date24'=> clean_form_input($this->input->post('Date24', TRUE)),
		'Time24'=> clean_form_input($this->input->post('Time24', TRUE)),
		'Test24'=> clean_form_input($this->input->post('Test24', TRUE)),
		'Results24'=> clean_form_input($this->input->post('Results24', TRUE)),
		//col 25
		'Date25'=> clean_form_input($this->input->post('Date25', TRUE)),
		'Time25'=> clean_form_input($this->input->post('Time25', TRUE)),
		'Test25'=> clean_form_input($this->input->post('Test25', TRUE)),
		'Results25'=> clean_form_input($this->input->post('Results25', TRUE)),
		//col 26
		'Date26'=> clean_form_input($this->input->post('Date26', TRUE)),
		'Time26'=> clean_form_input($this->input->post('Time26', TRUE)),
		'Test26'=> clean_form_input($this->input->post('Test26', TRUE)),
		'Results26'=> clean_form_input($this->input->post('Results26', TRUE)),
		//col 27
		'Date27'=> clean_form_input($this->input->post('Date27', TRUE)),
		'Time27'=> clean_form_input($this->input->post('Time27', TRUE)),
		'Test27'=> clean_form_input($this->input->post('Test27', TRUE)),
		'Results27'=> clean_form_input($this->input->post('Results27', TRUE)),
		//col 28
		'Date28'=> clean_form_input($this->input->post('Date28', TRUE)),
		'Time28'=> clean_form_input($this->input->post('Time28', TRUE)),
		'Test28'=> clean_form_input($this->input->post('Test28', TRUE)),
		'Results28'=> clean_form_input($this->input->post('Results28', TRUE)),
		//col 29
		'Date29'=> clean_form_input($this->input->post('Date29', TRUE)),
		'Time29'=> clean_form_input($this->input->post('Time29', TRUE)),
		'Test29'=> clean_form_input($this->input->post('Test29', TRUE)),
		'Results29'=> clean_form_input($this->input->post('Results29', TRUE)),
		//col 30
		'Date30'=> clean_form_input($this->input->post('Date30', TRUE)),
		'Time30'=> clean_form_input($this->input->post('Time30', TRUE)),
		'Test30'=> clean_form_input($this->input->post('Test30', TRUE)),
		'Results30'=> clean_form_input($this->input->post('Results30', TRUE)),

		);
	  $cs_others = implode(",", $others);
                $data = array(
                               'others2'=>$cs_others
                 );
              $this->db->where('er_id', $aid);
              $this->db->update('er_census', $data);
             //log update task in text file
                     $file = "er_admission_log.txt"; 
                     $handle = fopen($file, 'a+');
                     date_default_timezone_set('Asia/Hong_Kong');
                     $data = date('m/d/Y h:i:s a', time()).", IP Add: ".$this->session->userdata('ip_address').", user:".$_SERVER['PHP_AUTH_USER'].", task: edit others2 id#".$aid.", date_in:".$this->date_in."\r\n"; 
                     fwrite($handle, $data);   

	}


}
