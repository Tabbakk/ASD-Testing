<?php
	if(!isset($_SESSION)) { session_start(); }
	include '../includes/config.php';
	
	$_SESSION['authorized'] = array();
	$_SESSION['user'] = array();
	$_SESSION['test'] = array();
	unset($_SESSION['authorized'],$_SESSION['user'],$_SESSION['test']);
	
	$error = true;
	$usernameError = ''."\r\n";
	$emailError = ''."\r\n";
	$passError = ''."\r\n";
	$cpassError = ''."\r\n";
	$facError = ''."\r\n";
	$matError = ''."\r\n";
	
	if(isset($_POST['username'])) {
		include '../includes/connessione.php';
		
		$error = false;

		
		// controllo su user
		$username = trim($_POST['username']);
		$username = strip_tags($username);
		if (strlen($username) < 6) {
			$error = true;
			$usernameError = "Lo username deve essere di almeno 6 lettere."."\r\n";
		} 
		else if (!preg_match("/^[a-zA-Z0-9_-]+$/",$username)) {
			$error = true;
			$usernameError = "Lo username contiene caratteri non accettati."."\r\n";
		}
		else {
			// check user exist or not
			$sql = "SELECT username FROM user WHERE username='$username'";
			$result=$mysqli->query($sql);
			if ($result->num_rows > 0) {
				$error = true;
				$usernameError = "Username già presente nel sistema."."\r\n";
			}
		}

		//controllo password
		$password = trim($_POST['password']);
		$password = strip_tags($password);
		if (strlen($password) < 6) {
			$error = true;
			$passError = "La password deve essere di almeno 6 caratteri."."\r\n";
		}
		else if (!preg_match("/^[a-zA-Z0-9àáâãäåèéêëìíîïñòóôõöøùúûüçÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙØÚÛÜ!£$%&?#@_-]+$/",$password)) {
			$error = true;
			$passError = "La password contiene caratteri non accettati."."\r\n";
		}
		//conferma password
		$cpassword = trim($_POST['cpassword']);
		$cpassword = strip_tags($cpassword);
		if ($cpassword != $password) {
			$error = true;
			$cpassError = "Conferma della password non combacia."."\r\n";
		}

		
		// controllo email
		$email = trim($_POST['email']);
		$email = strip_tags($email);
		if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$error = true;
			$emailError = "Inserire un indirizzo email valido."."\r\n";
		}
		else {
			// check email exist or not
			$sql = "SELECT email FROM ext_user WHERE email='$email'";
			$result=$mysqli->query($sql);
			if ($result->num_rows > 0) {
				$error = true;
				$emailError = "Indirizzo email già presente nel sistema."."\r\n";
			}
		}
		
		// controlli su facoltà
		if(isset($faculty) && $faculty!=''){
			if (!preg_match("/^[a-zA-Z0-9\sàáâãäåèéêëìíîïñòóôõöøùúûüç]+$/",$faculty)) {
				$error = true;
				$facError = "La facoltà contiene caratteri non accettati."."\r\n";
			}
		}
		
		// controlli su matricola
		if(isset($matricola) && $matricola != ''){
			if (!preg_match("/^[a-zA-Z0-9-]+$/",$matricola)) {
				$error = true;
				$matError = "La matricola contiene caratteri non accettati."."\r\n";
			}
		}
		
		//inizializzazione altre variabili da post
		$bday = $_POST['bday'];
		$bmonth = $_POST['bmonth'];
		$byear = $_POST['byear'];
		$sesso = $_POST['sex'];
		$scol = $_POST['scholarity'];
		$faculty = $_POST['faculty'];
		$matricola = $_POST['idnum'];
		$a_uni = $_POST['colyear'];

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
			  <li><a href="../login.php">Login</a></li>
			  <li class="active"><a href="../user/eregister.php">Registrati</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="../login_t.php">Login Personale</a></li>
			</ul>
		  </div><!--/.nav-collapse -->
		</div>
	</nav>
	
	
	<?php
		if($error){
	?>
		<form method="post" class="form-horizontal form-registration" id="" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
			<h2 class="form-register-heading" style="text-align:center;">Registrazione</h2>
			<div class="form-group">
				<label for="username" class="col-sm-3 control-label">Username</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="username" id="username" placeholder="Inserire Username" maxlength="50" value="<?php if(isset($username)) {echo $username; } ?>" required />
				</div>
				<div class="col-sm-3">
					<?php echo($usernameError); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-sm-3 control-label">Password</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="password" id="password" placeholder="Inserire Password" maxlength="50" value="<?php if(isset($password)) {echo $password; } ?>" required />
				</div>
				<div class="col-sm-3">
					<?php echo($passError); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="cpassword" class="col-sm-3 control-label">Conferma Password</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="cpassword" id="cpassword" placeholder="Conferma Password" maxlength="50" value="" required />
				</div>
				<div class="col-sm-3">
					<?php echo($cpassError); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="email" class="col-sm-3 control-label">Email</label>
				<div class="col-sm-6">
					<input class="form-control" type="email" name="email" id="email" placeholder="Inserire indirizzo email" maxlength="50" value="<?php if(isset($email)) {echo $email; } ?>" required />
				</div>
				<div class="col-sm-3">
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
				<label for="faculty" class="col-sm-3 control-label">Facoltà</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="faculty" id="faculty" placeholder="Facoltà" maxlength="50" value="<?php if(isset($faculty)) {echo $faculty; } ?>" />
				</div>
				<div class="col-sm-3">
					<?php echo($facError); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="idnum" class="col-sm-3 control-label">Matricola</label>
				<div class="col-sm-6">
					<input class="form-control" type="text" name="idnum" id="idnum" placeholder="Matricola" maxlength="10" value="<?php if(isset($matricola)) {echo $matricola; } ?>" /><?php echo($matError); ?>
				</div>
				<div class="col-sm-3">
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
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-6">
				  <div class="checkbox termsandcond">
					  <input type="checkbox" oninvalid="this.setCustomValidity('Occorre accettare i termini e le condizioni per procedere')" required />
					  <label>Accetto i termini e le condizioni (<a href="javascript:Popup('conditions.php')">leggi</a>)</label>
				  </div>
				</div>
			</div>
			<div class="regconfirm col-sm-offset-4 col-sm-4">
			<button class="btn btn-block btn-lg btn-default" id="submit" type="submit">Completa Registrazione</button>
			</div>
		</form>
	<?php
	}
	else {
		$sql = 'call insert_ext_user(?,?,?,?,?,?,?,?,?,@e)';
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param('ssssissss', $username, $password, $date, $sesso, $scol, $email, $faculty, $a_uni, $matricola);
		$scol = (int)$scol;
		$date = $byear.'-'.$bmonth.'-'.$bday;
		$password = sha1($password);
		$stmt->execute() or die('Errore DB: '.$mysqli->error);
		
		$select = $mysqli->query('SELECT @e');
		$result = $select->fetch_assoc();
		$err = $result['@e'];
		
		mysqli_close($mysqli);
		
		if($err==0) {
		?>			
			<h2 style="text-align:center;">Registrazione avvenuta con successo</h2>
			<div class="row">
				<button class="btn btn-lg btn-default col-sm-offset-4 col-sm-4" id="home" type="button" onclick="window.location.href = '../index.php'">Torna alla Home</button>
			</div>
		<?php	
			}
		else {
		?>
		<h2 style="text-align:center;">È avvenuto un problema con la registrazione</h2>
		<div class="row">
			<button class="btn btn-lg btn-default col-sm-offset-4 col-sm-4" id="home" type="button" onclick="window.location.href = '<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>'">Riprova</button>
			<button class="btn btn-lg btn-default col-sm-offset-4 col-sm-4" id="home" type="button" onclick="window.location.href = '../index.php'">Torna alla Home</button>
		</div>
		<?php	
			}
	}
		?>
	
	</div>
		
		<script type="text/javascript">
		<!--
		var stile = "top=10, left=10, width=700, height=500, status=0, menubar=0, toolbar=0, scrollbars=1";
		function Popup(apri) 
		{
		  window.open(apri, "", stile);
		}
		//-->
		</script>
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