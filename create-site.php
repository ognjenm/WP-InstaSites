<?php
function bapi_create_site(){
	if ( ! preg_match( '/bapi-signup\.php$/', $_SERVER['REQUEST_URI'] ) ) {
		return;
	}
	$temail = 'test'.time().'@bookt.com'; 
	$u = wpmu_create_user($temail,'testing',$temail);
	if(is_numeric($u)){
		$meta = array('api_key' => 'c336ba2a-3aab-4d12-b5f6-16809995d490');
		$siteurl = 'wpmutest'.time().'.localhost';
		$s = wpmu_create_blog($siteurl,'/','Test1',$u,$meta);
		//$t = wpmu_create_blog('wpmutest.localhost','/','Test1',1);  //use this one to force a 'blog_taken' failure.
		if(is_numeric($s)){
			//success
			//echo $s; exit();
			header('Location: http://'.$siteurl.'/wp-admin/admin.php?page=bookt-api/setup-sync.php');
		}
		else{
			//fail
			//print_r($s->errors['blog_taken'][0]); exit();  //Not sure if this is the only error returned.  Need a more generic message handler.
			print_r($s); exit();
		}
	}
	else{
		//Failed to create user
	}
	exit();
}
?>