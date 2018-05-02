<?php

	include 'connessione.php'; 
	 
	//variabili POST con anti sql Injection
	$username=mysql_real_escape_string($_POST['username']); //escape dei caratteri dannosi
	$password=mysql_real_escape_string(sha1($_POST['password'])); //sha1 cifra la password anche qui in questo modo corrisponde con quella del db
	 
	$stmt = $mysqli->prepare("SELECT id, username, password, patient FROM user WHERE username = ? LIMIT 1");
			$stmt->bind_param('s', $username);  // Bind "$username" to parameter.
			$stmt->execute();    // Execute the prepared query.
			$stmt->store_result();

			$stmt->bind_result($id, $username, $db_password, $patient);
			$stmt->fetch();
	 
	 
	 if ($stmt->num_rows == 1) {
		if ($db_password==$password){
					include '/sessioninit.php';
					//Redirect alla pagina riservata
					if ($_SESSION['user']['patient']==1) {
						echo '<script language=javascript>document.location.href="../index.php?patient"</script>'; 
					}
					else {
						echo '<script language=javascript>document.location.href="../index.php?external"</script>'; 
					}

		}
			//Redirect per password sbagliata
			else {echo '<script language=javascript>document.location.href="../index.php?pws"</script>';}
	}
	//Redirect per utente inesistente
	else {echo '<script language=javascript>document.location.href="../index.php?noutente"</script>';}
 
?>