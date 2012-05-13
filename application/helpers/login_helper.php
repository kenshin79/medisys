<?php


function authorize_user($auth_list, $csess){
    
    $authorized = FALSE;
    
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
	{
	      $user = $_SERVER['PHP_AUTH_USER'];
		  $pword = md5($_SERVER['PHP_AUTH_PW']);
	      
		 foreach ($auth_list as $al)
		 {
		      if (!strcmp($al->uname, $user) && !strcmp($al->passwd, $pword))
			  {
			      	   
				  $authorized = TRUE;
                                  
			  	  break;
			  }	    
		 }
	}
    	
	if (!$authorized || !$csess)
	{
	 header('WWW-Authenticate: Basic Realm="Medicine Admissions Database"');
	 header("HTTP/1.1 401 Unauthorized");
	 print('Authorized Username and Password Required!');
	 exit;
	}




}
