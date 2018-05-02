<?php 
	if(!isset($_SESSION)) { session_start(); }
	include '../../includes/config.php';
	include '../../includes/ver_auth_t_2.php';

	if($_SESSION['authorized']!=3){
		header('Location: ../../index.php');
	}	
	
	if(!isset($_SESSION['therapist']['doc'])) {
		header('Location: ../../index.php');
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
						<li class="dropdown">
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
		    <li class="dropdown active">
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
			if(!isset($_POST['submit'])){
		?>
		<div class="page-header deliminate">
			<h2>Eliminazione Paziente</h2>	
		
			<div class="confirm">
			<p>Confermare l'eliminzaione del terapista <h4><?php echo($_SESSION['therapist']['doc']['name'].' '.$_SESSION['therapist']['doc']['surname']); ?>?</h4> <b class="warning">Attenzione: i test che ha corretto non saranno più legati a lui/lei</b></p>
			</div>
			<form id="conferma" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
			<div class="row">
			<div class="regconfirm col-sm-offset-2 col-sm-4">
				<input class="btn btn-block btn-lg btn-default" id="submit" name="submit" type="submit" value="Conferma Eliminazione" />
			</div>
			<div class="regconfirm col-sm-4">
				<input class="btn btn-block btn-lg btn-default" type="button" value="Annulla" onclick="window.location.href = 'dview.php' " />
			</div>
			</div>
			</form>
		<?php
			}
			else {
				include '../../includes/connessione.php';

				$ultimo = 0;
				
				if($_SESSION['therapist']['doc']['admin']) {
					$sql='Select count(*) as c from therapist where admin=1';
					$res = $mysqli->query($sql);
					$num=$res->fetch_assoc()['c'];
					if ($num=2) {$ultimo=1; }	
					$res->free();
				}
				if(!$ultimo){
					$sql='call delete_therapist(?,@e)';
					$stmt = $mysqli->prepare($sql);
					$stmt->bind_param('i', $_SESSION['therapist']['doc']['id']);
					$stmt->execute() or die('Errore DB: '.$mysqli->error);
					$stmt->close();
					$select = $mysqli->query('SELECT @e');
					$result = $select->fetch_assoc();
					$err = $result['@e'];
					if(!$err){
		?>
		<div class="page-header deliminate">
			<h2>Eliminazione avvenuta con successo</h2>	
		<div class="row">
				<input class="btn btn-lg btn-default col-sm-offset-4 col-sm-4" type="button" value="Torna alla Homepage" onclick="window.location.href = '../../index.php' " />
		</div>
		</div>
		<?php
					}
					else {
		?>
		<div class="page-header deliminate">
			<h2>È avvenuto un problema con l'eliminazione</h2>	
		<div class="row">
				<input class="btn btn-lg btn-default col-sm-offset-4 col-sm-4" type="button" value="Pagina del paziente" onclick="window.location.href = 'dview.php' " />
		</div>
		</div>
		<?php
					}
					$select->free();
					$mysqli->close();
				}
				else {
			?>
		<div class="page-header deliminate">
			<h2>Impossibile eliminare questo dottore: è l'ultimo amministratore di sistema</h2>	
		<div class="row">
				<input class="btn btn-lg btn-default col-sm-offset-4 col-sm-4" type="button" value="Torna alla Homepage" onclick="window.location.href = '../../index.php' " />
		</div>
		</div>
			<?php
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