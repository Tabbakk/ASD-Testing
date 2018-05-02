<?php
	if(!isset($_SESSION)) { session_start(); }
	include '../includes/config.php';
	include '../includes/ver_auth.php';

	if(!isset($_SESSION['user'])) {						//controlla se la sessione contiene i dati utente
		if(isset($_POST['id'])) {						//in caso contrario controlla se è stato inviato via post un id per caricarne uno
				$id = $_POST['id'];
				$patient = 0;
				include '../../includes/sessioninit.php';	//se c'è una richiesta via post carica l'utente nella sessione php
		}
		else {
			header('Location: ../index.php?nope');		//se non è caricato un utente e non c'è una richiesta di carica, reindirizza alla main
		}
	}	
	
	//inizializzazione errore a true ed inizializzazione degl errori
	$error = true;
	$usernameError = '';
	$emailError = '';
	$passError = '';
	$cpassError = '';
	$facError = '';
	$matError = '';
	
	$id = $_SESSION['user']['id'];
	
	if(isset($_POST['username'])) {
		include '../includes/connessione.php';
		
		$error = false;

		
		// controllo su user
		$username = trim($_POST['username']);
		$username = strip_tags($username);
		if($_POST['username']!=$_SESSION['user']['username']) {
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
		}
		//conferma password
		$cpassword = trim($_POST['cpassword']);
		$cpassword = strip_tags($cpassword);
		if ($cpassword != $password) {
			$error = true;
			$cpassError = "Conferma non combacia";
		}

		
		// controllo email
		$email = trim($_POST['email']);
		$email = strip_tags($email);
		if($_POST['email']!=$_SESSION['user']['email']) {
			if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
				$error = true;
				$emailError = "Inserire un indirizzo email valido";
			}
			else {
				// check email exist or not
				$sql = "SELECT email FROM ext_user WHERE email='$email'";
				$result=$mysqli->query($sql);
				if ($result->num_rows > 0) {
					$error = true;
					$emailError = "Indirizzo email già presente nel sistema";
				}
			}
		}
		
		// controlli su facoltà
		$faculty = $_POST['faculty'];
		if(isset($faculty) && $faculty!=''){
			if (!preg_match("/^[a-zA-Z0-9\sàáâãäåèéêëìíîïñòóôõöøùúûüç]+$/",$faculty)) {
				$error = true;
				$facError = "La facoltà contiene caratteri non accettati";
			}
		}
		
		// controlli su matricola
		$matricola = $_POST['idnum'];
		if(isset($matricola) && $matricola != ''){
			if (!preg_match("/^[a-zA-Z0-9-]+$/",$matricola)) {
				$error = true;
				$matError = "La matricola contiene caratteri non accettati";
			}
		}
		
		//inizializzazione altre variabili da post
		$bday = $_POST['bday'];
		$bmonth = $_POST['bmonth'];
		$byear = $_POST['byear'];
		$sesso = $_POST['sex'];
		$scol = $_POST['scholarity'];
		$a_uni = $_POST['colyear'];
	}	
	else {	//non si è arrivati qui tramite form, bensì caricando dati utente della sessione php
		$username = $_SESSION['user']['username'];
		$bday = $_SESSION['user']['bday'];
		$bmonth = $_SESSION['user']['bmonth'];
		$byear = $_SESSION['user']['byear'];
		$scol = $_SESSION['user']['schol'];
		$sesso = $_SESSION['user']['sex'];
		$email = $_SESSION['user']['email'];
		$matricola = $_SESSION['user']['idnum'];
		$faculty = $_SESSION['user']['faculty'];
		$a_uni = $_SESSION['user']['syear'];
		$password = '';
	}
	
?>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="DISCAB Testing">
		<meta name="author" content="Joseph B. D'Ascanio">
		<title><?php echo $site_name ?></title>
		<!-- Bootstrap core CSS -->
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="../css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<link href="../css/ie10-viewport-bug-workaround.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="../css/general.css" rel="stylesheet">
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="js/html5shiv.min.js"></script>
		  <script src="js/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
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
					<a class="navbar-brand" href="../index.php">DISCAB</a>
				  </div>
				  <div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-left">
					  <li><a href="../index.php">Home</a></li>
		    <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Test <span class="caret"></span></a>
                <ul class="dropdown-menu">
		<?php
			
			foreach ($_SESSION['test'] as $testname => $active) {
			if($active == 1) {
		?>
                  <li><a href="../tests/<?php echo($testname.'.php'); ?>"><?php echo($_SESSION['testnames'][$testname]); ?></a></li>
		<?php		
				}
			}

		?>
                </ul>
              </li>
				<?php
					if($_SESSION['user']['patient']==0) {
				?>
					  <li class="active"><a href="profile.php">Profilo</a></li>
				<?php
					}
				?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="../logout.php">Logout</a></li>
					</ul>
				  </div><!--/.nav-collapse -->
				</div>
			</nav>
			

	<?php
		if($error){
	?>			
		<form class="form-horizontal form-registration umodify" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
			<h2 class="form-register-heading">Modifica Profilo</h2>
			<div class="form-group">
				<label for="username" class="col-sm-3 control-label">
					Username
				</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="username" id="username" placeholder="Inserire Username" maxlength="50" value="<?php if(isset($username)) {echo $username; } ?>" required />
				</div>
				<div class="col-sm-3 warning">
					<?php echo($usernameError); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-sm-3 control-label">
					Password
				</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="password" id="password" placeholder="Nuova Password" maxlength="50" value="<?php if(isset($password)) {echo $password; } ?>" />
				</div>
				<div class="col-sm-3 warning">
					<?php echo($passError); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="cpassword" class="col-sm-3 control-label">Conferma Password</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="cpassword" id="cpassword" placeholder="Conferma nuova Password" maxlength="50" value="" />
				</div>
				<div class="col-sm-3 warning">
					<?php echo($cpassError); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="email" class="col-sm-3 control-label">Email</label>
				<div class="col-sm-6">
					<input class="form-control" type="email" name="email" id="email" placeholder="Indirizzo email" maxlength="50" value="<?php if(isset($email)) {echo $email; } ?>" required />
				</div>
				<div class="col-sm-3 warning">
					<?php echo($emailError); ?>
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
						<input type="radio" value="M" name="sex" id="sex" <?php if(isset($sesso)) {if ($sesso=='M') {echo('checked="checked"'); }} ?> required><label for="sex">M</label>
					</div>
					<div class="radio col-sm-2">
						<input type="radio" value="F" name="sex" id="sex" <?php if(isset($sesso)) {if ($sesso=='F') {echo('checked="checked"'); }} ?> required><label for="sex">F</label>
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
				<label for="faculty" class="col-sm-3 control-label">
					Facoltà
				</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="faculty" id="faculty" placeholder="Facoltà" maxlength="50" value="<?php if(isset($faculty)) {echo $faculty; } ?>" />
				</div>
				<div class="col-sm-3 warning">
					<?php echo($facError); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="idnum" class="col-sm-3 control-label">Matricola</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="idnum" id="idnum" placeholder="Matricola" maxlength="10" value="<?php if(isset($matricola)) {echo $matricola; } ?>" />
				</div>
				<div class="col-sm-3 warning">
					<?php echo($matError); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="colyear" class="col-sm-3 control-label">Anno di corso</label>
				<div class="col-sm-2">
					<select class="form-control" name="colyear" id="colyear" >
						<option value=''></option>
						<?php
							$sel = '';
							for ($i=1; $i < 11; $i++) {
								if(isset($a_uni) && $a_uni != ''){
									if ($a_uni == $i) { $sel = ' selected="selected" '; }
									else { $sel = '';}
								}
								echo('<option value="'.$i.'" '.$sel.'>'.$i.'</option>'."\r\n");
							}
						?>
					</select>
				</div>
			</div>
			<div class="regconfirm col-sm-offset-4 col-sm-4">
				<button class="btn btn-block btn-lg btn-default" id="submit" type="submit">Modifica profilo</button>
			</div>
		</form>
	<?php
	}
	else {
		if ($password!='') { // se si è scelto di modificare la pw occorre chiamare la procedure update_ext_user_pw
			$sql = 'call update_ext_user_pw(?,?,?,?,?,?,?,?,?,?,@e)';
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('issssissss', $id, $username, $password, $date, $sesso, $scol, $email, $faculty, $a_uni, $matricola);
			$password = sha1($password);
		}
		else {	//altrimenti si chiama la procedure update_ext_user
			$sql = 'call update_ext_user(?,?,?,?,?,?,?,?,?,@e)';
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('isssissss', $id, $username, $date, $sesso, $scol, $email, $faculty, $a_uni, $matricola);		
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
			$_SESSION['user'] = array();	// se la modifica è avvenuta, distruggo i dati del paziente nella sessione php
			unset($_SESSION['user']);		// e chiamo per la loro reinizializzazione tramite un POST dell'id paziente
		?>			
			<div class="page-header uprofile">
				<h2>Modifica avvenuta con successo</h2>
			<form class="row" action="profile.php" method="post">
				<input type="hidden" name="id" value="<?php echo($id); ?>">
				<div class="col-sm-2 col-sm-offset-5">
				<input class="btn btn-block btn-lg btn-default" id="submit" type="submit" id="submit" value="Torna al profilo"></br> 
			</form>
			</div>
		<?php	
			}
		else {
		?>
			<div class="page-header uprofile">
				<h2>Si è verificato un problema con la modifica</h2>
			<form class="row" action="profile.php" method="post">
				<input type="hidden" name="id" value="<?php echo($id); ?>">
				<div class="col-sm-2 col-sm-offset-5">
				<input class="btn btn-block btn-lg btn-default" id="submit" type="submit" id="submit" value="Torna al profilo"></br> 
			</form>
			</div>
		<?php	
			}
	}
		?>
		</div>
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="../js/jQuery.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/docs.min.js"></script>
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="../js/ie10-viewport-bug-workaround.js"></script>
	</body>
</html>