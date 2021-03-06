<?php 
	if(!isset($_SESSION)) { session_start(); }
	include '../../includes/config.php';
	include '../../includes/ver_auth_t_2.php';


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
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pazienti <span class="caret"></span></a>
							<ul class="dropdown-menu">
							  <li><a href="../../therapist/patients/search.php">Cerca</a></li>
							  <li><a href="../../therapist/patients/pregister.php">Inserisci nuovo paziente</a></li>
							</ul>
						</li>
						<li class="active"><a href="../../therapist/groups.php">Gruppi Clinici</a></li>
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

	if(!isset($_POST['id'])) {
		header('Location: ../groups.php?nope');
	}
	
	include '../../includes/connessione.php';
	
	// si inizializza errore a true e si inizializzano vuoti gli errori dei campi
	$error = true;
	$nameError = '</br></br>'."\r\n";
	
	if(isset($_POST['mod'])){
		if(isset($_POST['name'])) {
			
			$error = false;
			
			// controllo su nome		
			$name = trim($_POST['name']);
			$name = strip_tags($name);
			if (strlen($name) < 3) {
				$error = true;
				$nameError = "Il nome deve essere di almeno 3 lettere.</br></br>"."\r\n";
			} 
			else if (!preg_match("/^[a-zA-Z0-9\sàáâãäåèéêëìíîïñòóôõöøùúûüçÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙØÚÛÜ]+$/",$name)) {
				$error = true;
				$nameError = "Il nome contiene caratteri non accettati.</br></br>"."\r\n";
			}		
			$sql = "select count(code) as c from clinicalg where name like '".$name."'";
			$err = $mysqli->query($sql);
			$num = $err->fetch_assoc()['c'];
			if($num !=0 ) {
				$error = true;
				$nameError = "Esiste già un gruppo con questo nome.</br></br>"."\r\n";		
			}
		}
		else {	//non si è arrivati qui tramite form, bensì caricando dati di un dottore della sessione php
			foreach($_SESSION['group'] as $gid){
				if($gid['id']==$_POST['id']) {$name=$gid['name'];}
			}
		}

		if($error){
	?>

		<div class="page-header cgroup">
			<h2>Modifica gruppo</h2>

		<form class="form-horizontal form-registration" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
			<div class="row">
			<div class="form-group">
				<label for="name" class="col-sm-3 col-sm-offset-1 control-label">
					Nome Gruppo
				</label>
				<div class="col-sm-4">
					<input class="form-control" type="text" name="name" id="name" placeholder="Inserire Nome" maxlength="50" value="<?php if(isset($name)) {echo $name; } ?>" />
				</div>
				<div class="col-sm-4">
					<?php echo($nameError); ?>
				</div>
			</div>
			</div>
				<input type="hidden" name="id" value="<?php echo($_POST['id']); ?>" >
				<input type="hidden" name="mod" value="del">
			<div class="row">
				<div class="col-sm-4 col-sm-offset-2">
					<input class="btn btn-lg btn-default btn-block" type="submit" id="submit" value="Modifica Nome Gruppo">
				</div>
				<div class="col-sm-4">
					<input  class="btn btn-lg btn-default btn-block" type="button" value="Annulla" onclick="window.location.href = '../groups.php' ">
				</div>
			</div>
		</form>
		</div>
	<?php
		}
		else {
			$sql = 'update clinicalg set name=? where code=?';
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('si', $name, $_POST['id']);		

			$err = $stmt->execute() or die('Errore DB: '.$mysqli->error);
			$stmt->close();
			
			mysqli_close($mysqli);
			
			if($err) {
			?>			
		<div class="page-header cgroup">
			<h2>Modifica avvenuta con successo</h2>
			<div class="row">
				<div class="col-sm-4 col-sm-offset-4">
					<input class="btn btn-lg btn-default btn-block" type="button" value="Torna ai gruppi clinici" onclick="window.location.href = '../groups.php' ">
				</div>
			</div>
		</div>
			<?php	
				}
			else {
			echo($sql);
			?>
		<div class="page-header cgroup">
			<h2>si è verificato un problema con la modifica</h2>
			Clicca <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">QUI</a> per riprovare
			<?php	
				}
		}
	}
	else if (isset($_POST['del'])){
		foreach($_SESSION['group'] as $gid){
			if($gid['id']==$_POST['id']) {$name=$gid['name']; $id=$gid['id'];}
		}
		if(!isset($_POST['confirm'])){
		?>
		<div class="page-header cgroup">
			<h2>Confermi di voler eliminare il gruppo "<?php echo ($name); ?>"?</h2>
			<div class="row">
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  >
			<input type="hidden" name="del" value="del">
			<input type="hidden" name="id" value="<?php echo($_POST['id']); ?>" >
				<div class="col-sm-3 col-sm-offset-3">
					<input class="btn btn-lg btn-default btn-block" type="submit" name="confirm" value="Elimina" >
				</div>
				<div class="col-sm-3">
					<input class="btn btn-lg btn-default btn-block" type="button" value="Annulla" onclick="window.location.href = '../groups.php' ">
				</div>
		</form>
			</div>
		</div>

		<?php
		}
		else {
			$sql = "call delete_clinicalg(?,@e)";
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('i', $id);
			$stmt->execute() or die('Errore DB: '.$mysqli->error);
			$stmt->close();
			
			$select = $mysqli->query('SELECT @e');
			$result = $select->fetch_assoc();
			$err = $result['@e'];	
			
			if(!$err){
			?>
		<div class="page-header cgroup">
			<h2>Eliminazione avvenuta con successo</h2>
			<div class="row">
				<div class="col-sm-4 col-sm-offset-4">
					<input class="btn btn-lg btn-default btn-block" type="button" value="Torna ai gruppi clinici" onclick="window.location.href = '../groups.php' ">
				</div>
			</div>
		</div>
			<?php
			}
			else {
			?>
		<div class="page-header cgroup">
			<h2>Si è presentato un problema durante l'eliminazione</h2>
			<div class="row">
				<div class="col-sm-4 col-sm-offset-4">
					<input class="btn btn-lg btn-default btn-block" type="button" value="Torna ai gruppi clinici" onclick="window.location.href = '../groups.php' ">
				</div>
			</div>
		</div>
			<?php
			}
		}
	}
	?>

		</div>
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="../../js/jQuery.js"></script>
		<script src="../../js/bootstrap.min.js"></script>
		<script src="../../js/docs.min.js"></script>
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="../../js/ie10-viewport-bug-workaround.js"></script>
	</BODY>
</HTML>