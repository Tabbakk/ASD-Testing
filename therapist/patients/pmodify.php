<?php 
	if(!isset($_SESSION)) { session_start(); }
	include '../../includes/config.php';
	include '../../includes/ver_auth_t_2.php';
	
	if(!isset($_SESSION['therapist']['patient'])) {		//controlla se la sessione contiene i dati di un paziente
		if(isset($_POST['id'])) {						//in caso contrario controlla se è stato inviato via post un id per caricarne uno
				include '../../includes/patient_init.php';	//se c'è una richiesta via post carica il paziente nella sessione php
		}
		else {
			header('Location: ../../index.php');	//se non è caricato un paziente e non c'è una richiesta di carica, reindirizza alla main pazienti
		}
	}
	
	// si inizializza errore a true e si inizializzano vuoti gli errori dei campi
	$error = true;
	$usernameError = '';
	$nameError = '';
	$surnameError = '';
	$passError = '';
	$cpassError = '';
	$numError =  '';

	$id = $_SESSION['therapist']['patient']['id'];
	
	if(isset($_POST['username'])) {						//con questo controllo so che si è arrivati qui tramite compilazione form
		include '../../includes/connessione.php';
		
		$error = false;
		
		// controllo su user		
		$username = trim($_POST['username']);
		$username = strip_tags($username);
		if($_POST['username']!=$_SESSION['therapist']['patient']['username']) {
			if (strlen($username) < 6) {
				$error = true;
				$usernameError = "Lo username deve essere di almeno 6 lettere";
			} 
			else if (!preg_match("/^[a-zA-Z0-9_-]+$/",$username)) {
				$error = true;
				$usernameError = "Lo username contiene caratteri non accettati";
			}
			else {
				// check user exist or not
				$sql = "SELECT username FROM user WHERE username='$username'";
				$result=$mysqli->query($sql);
				if ($result->num_rows > 0) {
					$error = true;
					$usernameError = "Username già presente nel sistema";
				}
			}
		}
		
		// controllo su nome		
		$name = trim($_POST['name']);
		$name = strip_tags($name);
		if (strlen($name) < 3) {
			$error = true;
			$nameError = "Il nome deve essere di almeno 3 lettere";
		} 
		else if (!preg_match("/^[a-zA-Z\sàáâãäåèéêëìíîïñòóôõöøùúûüçÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙØÚÛÜ]+$/",$name)) {
			$error = true;
			$nameError = "Il nome contiene caratteri non accettati";
		}		

		// controllo su cognome
		$surname = trim($_POST['surname']);
		$surname = strip_tags($surname);
		if (strlen($surname) < 3) {
			$error = true;
			$surnameError = "Il cognome deve essere di almeno 3 lettere";
		} 
		else if (!preg_match("/^[a-zA-Z\sàáâãäåèéêëìíîïñòóôõöøùúûüçÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙØÚÛÜ]+$/",$surname)) {
			$error = true;
			$surnameError = "Il cognome contiene caratteri non accettati";
		}		

		//controllo password
		$password = trim($_POST['password']);
		$password = strip_tags($password);
		if(!strlen($_POST['password']==0)){
			if (strlen($password) < 6) {
				$error = true;
				$passError = "La password deve essere di almeno 6 caratteri";
			}
			else if (!preg_match("/^[a-zA-Z0-9àáâãäåèéêëìíîïñòóôõöøùúûüçÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙØÚÛÜ!£$%&?#@_-]+$/",$password)) {
				$error = true;
				$passError = "La password contiene caratteri non accettati";
			}
			//conferma password
			$cpassword = trim($_POST['cpassword']);
			$cpassword = strip_tags($cpassword);
			if ($cpassword != $password) {
				$error = true;
				$cpassError = "Conferma della password non combacia";
			}
		}
		
		//controllo numero di telefono
		$number = $_POST['number'];
		if (!preg_match("/^[0-9]+$/",$number) && $number!='') {
			$error = true;
			$numError = "Il numero deve essere formato solo da caratteri numerici";
		}
		
		//inizializzazione data di nascita, scolarità, sesso e gruppo
		$bday = $_POST['bday'];
		$bmonth = $_POST['bmonth'];
		$byear = $_POST['byear'];
		$scol = $_POST['scholarity'];		
		$sesso = $_POST['sex'];
		$group = $_POST['group'];
	}
	else {	//non si è arrivati qui tramite form, bensì caricando dati di un paziente della sessione php
		$username = $_SESSION['therapist']['patient']['username'];
		$name = $_SESSION['therapist']['patient']['name'];
		$surname = $_SESSION['therapist']['patient']['surname'];
		$bday = $_SESSION['therapist']['patient']['bday'];
		$bmonth = $_SESSION['therapist']['patient']['bmonth'];
		$byear = $_SESSION['therapist']['patient']['byear'];
		$scol = $_SESSION['therapist']['patient']['schol'];
		$sesso = $_SESSION['therapist']['patient']['sex'];
		$group = $_SESSION['therapist']['patient']['gid'];
		$number = $_SESSION['therapist']['patient']['number'];
		$password = '';
	}
?>
<HTML>
	<HEAD>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="description" content="DISCAB Testing">
		<meta name="author" content="Joseph B. D'Ascanio">
		<title><?php echo $site_name ?></title>
		<!-- Bootstrap core CSS -->
		<link href="../../css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="../../css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<link href="../../css/ie10-viewport-bug-workaround.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="../../css/general.css" rel="stylesheet">
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="js/html5shiv.min.js"></script>
		  <script src="js/respond.min.js"></script>
		<![endif]-->
	</HEAD>
	<BODY>
		<div class="container theme-showcase" role="main">
		
			<nav class="navbar navbar-default">
				<div class="container-fluid" id="navfluid">
				  <div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
					  <span class="sr-only">Toggle navigation</span>
					  <span class="icon-bar"></span>
					  <span class="icon-bar"></span>
					  <span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="../../index.php">DISCAB</a>
				  </div>
				  <div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-left">
					  <li><a href="../../index.php">Home</a></li>				  
						<li class="dropdown active">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pazienti <span class="caret"></span></a>
							<ul class="dropdown-menu">
							  <li><a href="../../therapist/patients/search.php">Cerca</a></li>
							  <li><a href="../../therapist/patients/pregister.php">Inserisci nuovo paziente</a></li>
							</ul>
						</li>
						<li><a href="../../therapist/groups.php">Gruppi Clinici</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Test <span class="caret"></span></a>
							<ul class="dropdown-menu">
							  <li><a href="../../therapist/tests/tlistuncorrected.php">Correzione Test</a></li>
							  <li><a href="../../therapist/tests/tactivation.php">Attivazione Test Esterni</a></li>
							</ul>
						</li>
				<?php
					if($_SESSION['authorized']==3) {
				?>
		    <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Terapisti <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="../../therapist/doc/search.php">Cerca</a></li>
                  <li><a href="../../therapist/doc/dregister.php">Inserisci nuovo terapista</a></li>
                </ul>
			</li>
				<?php
					}
				?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Statistiche <span class="caret"></span></a>
							<ul class="dropdown-menu">
							  <li><a href="../../therapist/stats/spdone.php">Pazienti</a></li>
							  <li><a href="../../therapist/stats/sedone.php">Esterni</a></li>
							  <li><a href="../../therapist/stats/spgenerate.php">Genera file Excel</a></li>
							  <li><a href="../../therapist/stats/mategenerate.php">Genera file Matricole</a></li>
							</ul>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="../../logout.php">Logout</a></li>
					</ul>
				  </div><!--/.nav-collapse -->
				</div>
			</nav>
	<?php
		if($error){
	?>
		<form class="form-horizontal form-registration pform" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
			<h2 class="form-register-heading">Modifica Paziente</h2>
			<div class="form-group">
				<label for="username" class="col-sm-3 control-label">Username</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="username" id="username" placeholder="Inserire Username" maxlength="50" value="<?php if(isset($username)) {echo $username; } ?>" required />
				</div>
				<div class="col-sm-3 warning">
					<?php echo($usernameError); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-sm-3 control-label">Password</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="password" id="password" placeholder="Lasciare vuoto per lasciare invariato" maxlength="50" value="<?php if(isset($password)) {echo $password; } ?>" />
				</div>
				<div class="col-sm-3 warning">
					<?php echo($passError); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="cpassword" class="col-sm-3 control-label">Conferma Password</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="cpassword" id="cpassword" placeholder="Lasciare vuoto per lasciare invariato" maxlength="50" value="" />
				</div>
				<div class="col-sm-3 warning">
					<?php echo($cpassError); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="name" class="col-sm-3 control-label">Nome</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="name" id="name" placeholder="Inserire Nome" maxlength="50" value="<?php if(isset($name)) {echo $name; } ?>" required />
				</div>
				<div class="col-sm-3 warning">
					<?php echo($nameError); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="surname" class="col-sm-3 control-label">Cognome</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="surname" id="surname" placeholder="Inserire Cognome" maxlength="50" value="<?php if(isset($surname)) {echo $surname; } ?>" required />
				</div>
				<div class="col-sm-3 warning">
					<?php echo($surnameError); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="bdate" class="col-sm-3 control-label">Data Nascita</label>
				<div class="col-sm-6" id="bdate" name="bdate">
					<div class="col-sm-4">
						<select class="form-control" name="bday" id="bday" required>
							<option value =''>GG</option>
							<?php
								$sel = '';
								for($i = 1; $i < 32; $i++) {
									if (isset($bday)){
										if ($bday == $i) { $sel = ' selected="selected" '; }
										else { $sel = '';}
									}
								echo('<option value="'.$i.'" '.$sel.'>'.$i.'</option>'."\r\n");
								}
							?>
						</select>
					</div>
					<div class="col-sm-4">
						<select class="form-control" name="bmonth" id="bmonth" required>
							<option value =''>MM</option>
							<?php
								$sel = '';
								$months = array('','Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre');
								for($i = 1; $i < 13; $i++) {
									if (isset($bmonth)){
										if ($bmonth == $i) { $sel = ' selected="selected" '; }
										else { $sel = '';}
									}
								echo('<option value="'.$i.'" '.$sel.'>'.$months[$i].'</option>'."\r\n");
								}
							?>
						</select>
					</div>
					<div class="col-sm-4">
						<select class="form-control" name="byear" id="byear" required>
							<option value =''>AAAA</option>
							<?php
								$sel = '';
								$date = (int) date('Y');
								$numYears = 100;
								for ($i=$date; $i >= $date - $numYears; $i--) {
									if (isset($byear)){
										if ($byear == $i) { $sel = ' selected="selected" '; }
										else { $sel = '';}
									}
									echo('<option value="'.$i.'" '.$sel.'>'.$i.'</option>'."\r\n");
								}
							?>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="sex" class="col-sm-3 control-label">Sesso</label>
				<div class="col-sm-6" id="sex" name="sex">
					<div class="radio col-sm-2">
						<label>
							<input type="radio" value="M" name="sex" id="sex" <?php if(isset($sesso)) {if ($sesso=='M') {echo('checked="checked"'); }} ?> required>
							M
						</label>
					</div>
					<div class="radio col-sm-2">
						<label>
							<input type="radio" value="F" name="sex" id="sex" <?php if(isset($sesso)) {if ($sesso=='F') {echo('checked="checked"'); }} ?> required>
							F
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="scholarity" class="col-sm-3 control-label">Anni Scolarità</label>
				<div class="col-sm-2">
					<select class="form-control" name="scholarity" id="scholarity" required>
						<option value=''></option>
					<?php
						$sel = '';
						for ($i=1; $i < 21; $i++) {
							if (isset($scol)){
								if ($scol == $i) { $sel = ' selected="selected" '; }
								else { $sel = '';}
							}
							echo('<option value="'.$i.'" '.$sel.'>'.$i.'</option>'."\r\n");
						}
					?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="group" class="col-sm-3 control-label">Gruppo Clinico</label>
				<div class="col-sm-6">
					<select class="form-control" name="group" id="group" required>
						<option value=''></option>
					<?php
						foreach($_SESSION['group'] as $gid) {
							$sel='';
							if (isset($group)){
								if ($group == $gid['id']) {$sel=' selected="selected" ';}
								else {$sel='';}
							}
							echo('<option value="'.$gid['id'].'"'.$sel.'>'.$gid['name'].'</option>'."\r\n");							
						}
					?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="number" class="col-sm-3 control-label">Numero di Telefono</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="number" id="number" placeholder="Inserire numero di telefono" maxlength="50" value="<?php if(isset($number)) {echo $number; } ?>" />
				</div>
				<div class="col-sm-3 warning">
					<?php echo($numError); ?>
				</div>
			</div>
			<div class="row">
			<div class="regconfirm col-sm-offset-2 col-sm-4">
				<input class="btn btn-block btn-lg btn-default" id="submit" type="submit" value="Conferma modifiche" />
			</div>
			<div class="regconfirm col-sm-4">
				<input class="btn btn-block btn-lg btn-default" type="button" value="Annulla" onclick="window.location.href = 'pview.php' " />
			</div>
			</div>
		</form>
	<?php
	}
	else {
		if ($password!='') { // se si è scelto di modificare la pw occorre chiamare la procedure update_patient_pw
			$sql = 'call update_patient_pw(?,?,?,?,?,?,?,?,?,?,@e)';
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('issssissss', $id ,$username, $password, $date, $sesso, $scol, $name, $surname, $number, $group);		
			$password = sha1($password);
			}
		else { //altrimenti si chiama la procedure update_patient
			$sql = 'call update_patient(?,?,?,?,?,?,?,?,?,@e)';
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('isssissss', $id ,$username, $date, $sesso, $scol, $name, $surname, $number, $group);		
		}
		
		$scol = (int)$scol;
		$date = $byear.'-'.$bmonth.'-'.$bday;
		$stmt->execute() or die('Errore DB: '.$mysqli->error);
		$stmt->close();
		
		$select = $mysqli->query('SELECT @e');
		$result = $select->fetch_assoc();
		$err = $result['@e'];
		
		mysqli_close($mysqli);
		
		if($err==0) {
			$_SESSION['therapist']['patient'] = array();	// se la modifica è avvenuta, distruggo i dati del paziente nella sessione php
			unset($_SESSION['therapist']['patient']);		// e chiamo per la loro reinizializzazione tramite un POST dell'id paziente
		?>			
		<div class="page-header pmodify">
			<h2>Modifica avvenuta con successo</h2>
			<form action="pview.php" method="post">
				<input type="hidden" name="id" value="<?php echo($id); ?>">
			<div class="row">
				<input class="btn btn-lg btn-default col-sm-offset-4 col-sm-4" type="submit" id="submit" value="Visualizza pagina paziente">
			</div>
			</form>
		</div>
		<?php	
			}
		else {
		?>
		<div class="page-header pmodify">
			<h2>Si è verificato un problema con la registrazione</h2>	
		<div class="row">
				<input class="btn btn-block btn-lg btn-default col-sm-offset-4 col-sm-4" type="button" value="Pagina del paziente" onclick="window.location.href = 'pview.php' " />
		</div>
		</div>
		<?php	
			}
	}
	?>

	</body>
</html>