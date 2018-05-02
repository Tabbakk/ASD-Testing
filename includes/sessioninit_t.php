<?php
	if(!isset($_SESSION)) { session_start(); }			
	if (ini_get('register_globals')) {
		foreach ($_SESSION as $key=>$value)
		{
			if (isset($GLOBALS[$key]))
				unset($GLOBALS[$key]);
		}
	}
	
	$_SESSION['authorized'] = array();
	$_SESSION['therapist'] = array();
	unset($_SESSION['authorized'],$_SESSION['therapist']);
	
	if ($admin) {
		$_SESSION['authorized'] = 3;
	}
	else {
		$_SESSION['authorized'] = 2;	
	}
	$_SESSION['therapist']['id'] = $id;
	$_SESSION['therapist']['username'] = $username;
	$_SESSION['therapist']['name'] = $name; // if set to 1 indicates an internal patient
	$_SESSION['therapist']['surname'] = $surname; // if set to 1 indicates an internal patient

	$mysqli->close();