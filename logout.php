<?php
	if(!isset($_SESSION)) { session_start(); }
	if (ini_get('register_globals')) {
		foreach ($_SESSION as $key=>$value)
		{
			if (isset($GLOBALS[$key]))
				unset($GLOBALS[$key]);
		}
	}
	include 'includes/config.php';
	$_SESSION = array();
	session_destroy(); //distruggo tutte le sessioni
	mysqli_close($mysqli);
	 
	//creo una varibiale con un messaggio
	$msg = "logout effettuato con successo.";
	 
	//la codifico via urlencode informazioni-logout-effettuato-con-successo
	$msg = urlencode($msg); // invio il messaggio via get
	 
	//ritorno a index.php usando GET posso recuperare e stampare a schermo il messaggio di avvenuto logout
	header("location: index.php?msg=$msg");
	exit();
?>