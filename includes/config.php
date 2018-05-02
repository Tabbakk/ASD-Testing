<?php 
	if(!isset($_SESSION)) { session_start(); }
	if (ini_get('register_globals')) {
		foreach ($_SESSION as $key=>$value)
		{
			if (isset($GLOBALS[$key]))
				unset($GLOBALS[$key]);
		}
	}
	
	$site_name = "DISCAB Testing";
	$date =(date("d-m-y"));

	
	if(!isset($_SESSION['authorized'])) {
		$_SESSION['authorized']=0;
	}