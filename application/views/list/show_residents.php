
<?php 
//authorize user with uname and pword
$auth_list = $this->Users_model->get_all_users();
$csess = get_cookie('ci_session');
authorize_user($auth_list, $csess);

//get user session variables
  $my_service = $this->session->userdata('my_service');
  $my_dispo = $this->session->userdata('my_dispo');
  $one_gm = $this->session->userdata('one_gm');
  $stp1 = $this->session->userdata('stp1');
  $vars = array( 
	 	    	    'my_service'=>$my_service,
	    	        'my_dispo'=>$my_dispo,
					'one_gm'=>$one_gm,
					'stp1'=>$stp1,
				 );

//make_page_header($_SERVER['PHP_AUTH_USER']);

echo "<div align=\"center\"><h1>Resident Search Results</h1></div>";  
echo "<div align=\"center\"><font size=\"4\">Note: Only 'Active' Residents will be included in dropdown choice for RIC.</font></div>";  


	$numrows = count($resident);
	echo "<div align=\"center\">";
    echo "<h1>'".$numrows."' resident(s) retrieved for '".$clue."'.</h1>";
    echo "</div>";

  $rw = array('value'=>'Ward Admissions', 'class'=>'menubb');
  $rb = array('value'=>'Edit Resident', 'class'=>'menubb');
  $re = array('value'=>'ER Admissions', 'class'=>'menubb');
  $rm = array('value'=>'MICU Admissions', 'class'=>'menubb');
  if ($resident){
      $x = 1;
      $y = 1;
      echo "<div align=\"center\"><table><tr><th>N0.</th><th>Resident Name</th><th>Date Started</th><th>Status</th><th>Edit/View Admissions</th></tr>";
      foreach($resident as $row){
                  if ($row->r_id == 1){
                        echo "<tr><td width = \"30px\">".$x."</td><td width = \"300px\">".revert_form_input($row->r_name)."</td><td width = \"200px\">".revert_form_input($row->dstart)."</td>";
		                echo "<td width = \"100px\">Active:".revert_form_input($row->status)."<td width = \"150px\">"; 
                  }
                  else{
                       $na = array(
		  	  		            'name'=> 'rname',
					            'value'=> revert_form_input($row->r_name),
					            'size'=> '40'
					        );
                       echo form_open('show/edit_resident'); 
                       echo "<tr><td width = \"30px\">".$x."</td><td width = \"300px\">".form_input($na)."</td>";
					   //<td width = \"200px\">".form_input('dstart',$row->dstart)."</td>";
		               //datepicker
					   echo "<td>";
					   require_once('calendar/classes/tc_calendar.php');
					   $pbnum = "dstart".$y;
					   $myCalendar = new tc_calendar($pbnum, true, false);
					   $myCalendar->setIcon("calendar/images/iconCalendar.gif");
					   $dd = (int)substr($row->dstart,8, 2);
					   $mm = (int)substr($row->dstart, 5, 2);
					   $yy = (int)substr($row->dstart, 0, 4);
					   $myCalendar->setDate($dd, $mm, $yy);
     				   $myCalendar->setPath("calendar/");
					   $myCalendar->setYearInterval(1900, 2015);
					   //$myCalendar->dateAllow('1900-01-01', '2015-01-01');
					   $myCalendar->setDateFormat('j F Y');
					   $myCalendar->setAlignment('right', 'top');
					   $myCalendar->writeScript();					
					   echo "</td>";
					   echo "<td width = \"100px\">Active: <select name = \"status\" ><option value = \"".$row->status."\" >".$row->status."</option><option value = \"Y\">Y</option><option value = \"N\">N</option></select></td>";
                       echo "<td>";
                        echo form_hidden('num', $y).form_hidden('eresident', $row->r_id).form_hidden('my_service', $my_service).form_hidden('my_dispo', $my_dispo).form_hidden('one_gm', $one_gm).form_hidden('stp1', $stp1);
                        echo "<div align = \"center\"><input type = \"submit\"  value = \"Edit Resident\" style = \"font-size:large; color:red; height:30px; width:250px;\" /></div>";
                        echo form_close();
		         }
//ward admissions			
		        echo form_open('census/resident_census');
                $this->db->where('r_id', $row->r_id);
                $this->db->or_where('sr_id', $row->r_id);
                $this->db->from('admissions');
                $wadm = $this->db->count_all_results();
                        echo form_hidden('eresident', $row->r_id).form_hidden('rname', $row->r_name).form_hidden('my_service', "All").form_hidden('my_dispo', $my_dispo).form_hidden('one_gm', $one_gm).form_hidden('stp1', $stp1);
                        echo "<div align = \"center\"><input type = \"submit\"  value = \"Ward (".$wadm.")\" style = \"font-size:large; color:red; height:30px; width:250px;\" /></div>";
		        echo form_close();
//ER admissions
                echo form_open('census/resident_census');
                $this->db->where('pod_id', $row->r_id);
                $this->db->from('er_census');
                $eadm = $this->db->count_all_results();
                        echo form_hidden('eresident', $row->r_id).form_hidden('rname', $row->r_name).form_hidden('my_service', "er").form_hidden('my_dispo', $my_dispo).form_hidden('one_gm', $one_gm).form_hidden('stp1', $stp1);
                        echo "<div align = \"center\"><input type = \"submit\"  value = \"ER (".$eadm.")\" style = \"font-size:large; color:red; height:30px; width:250px;\" /></div>";
		        echo form_close();
//MICU admissions
                echo form_open('census/resident_census');
                $this->db->where('r_id', $row->r_id);
                $this->db->or_where('sr_id', $row->r_id);
                $this->db->from('micu_census');
                $madm = $this->db->count_all_results();
                        echo form_hidden('eresident', $row->r_id).form_hidden('rname', $row->r_name).form_hidden('my_service', $my_service).form_hidden('my_dispo', $my_dispo).form_hidden('one_gm', $one_gm).form_hidden('stp1', $stp1);
                        echo "<div align = \"center\"><input type = \"submit\"  value = \"MICU (".$madm.")\" style = \"font-size:large; color:red; height:30px; width:250px;\" /></div>";
		        echo form_close();    
		        
		        $x++;
				$y++;
		        echo "</td></tr>";
      }
  }
  
?>
  </table>
  </div>
  <hr/>

