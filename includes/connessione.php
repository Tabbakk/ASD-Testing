<?php
	include '/dbconfig.php';

	# stringa di connessione al DBMS
	// istanza dell'oggetto della classe MySQLi
	$mysqli = new mysqli($host, $db_user['user'], $db_psw['user'], $db_name);

	// verifica su eventuali errori di connessione
	if ($mysqli->connect_errno) {
		echo "Connessione fallita: ". $mysqli->connect_error . ".";
		exit();
	}
