<?php 
	if(!isset($_SESSION)) { session_start(); }
	include '../../includes/config.php';
	include '../../includes/ver_auth_t_2.php';
	
	if(!isset($_SESSION['therapist']['patient'])) {
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
		if(!isset($_POST['submit'])){
		?>
			<div class="page-header ptests">
				<h2>Test attivi di <?php echo($_SESSION['therapist']['patient']['surname'].', '.$_SESSION['therapist']['patient']['name']); ?></h2>
			</div>
		<form id='tests' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
				<table class="table">
				<tr>
					<th>TEST</th>
					<th>ACTIVE</th>
				</tr>
			<?php
				foreach($_SESSION['therapist']['patient']['tests'] as $test) {
				?>
				<tr>
					<td><?php echo($test['name']); ?></td>
					<td><input type="checkbox" name="<?php echo($test['abbr']); ?>" value="1" <?php if($test['active']==1){ echo(' checked="checked" ');} ?>></td>
				</tr>
				<?php
				}
			?>
			</table>
			</div>
			</div>
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
					<div class="col-sm-6">
						<input class="btn btn-sm btn-default btn-block correct" type="submit" name="submit" value="Salva cambiamenti">
					</div>
					<div class="col-sm-6">
						<input class="btn btn-sm btn-default btn-block correct" type="button" value="Torna alla pagina del Paziente" onclick="window.location.href = 'pview.php' ">
					</div>
				</div>
			</div>
		</form>
		<?php
		}
		else {
			include '../../includes/connessione.php';
			$id=$_SESSION['therapist']['patient']['id'];
			$sql = 'update patient_tests set ';
			foreach($_SESSION['therapist']['patient']['tests'] as $test) {
				if(isset($_POST[$test['abbr']])){
					$sql=$sql.$test['abbr'].'='.$_POST[$test['abbr']].',';
				}
				else {
					$sql=$sql.$test['abbr'].'=0,';
				}
			}
			$sql=rtrim($sql, ",");
			$sql=$sql.' where id='.$id;
			$ok = $mysqli->query($sql);
			
		if($ok) {
			$_SESSION['therapist']['patient'] = array();	// se la modifica è avvenuta, distruggo i dati del paziente nella sessione php
			unset($_SESSION['therapist']['patient']);		// e chiamo per la loro reinizializzazione tramite un POST dell'id paziente
		?>			
			<div class="page-header ptests">
				<h2>Modifica avvenuta con successo</h2>
			</div>
			<form action="pview.php" method="post">
				<input type="hidden" name="id" value="<?php echo($id); ?>">
				<div class="row">
					<div class="col-sm-4 col-sm-offset-4">
						<input class="btn btn-sm btn-default btn-block correct" type="submit" id="submit" value="Torna alla pagina del paziente">
					</div>
				</div>
			</form>
		<?php	
			}
		else {
		?>
			<div class="page-header ptests">
				<h2>È avvenuto un problema con la modifica</h2>
			</div>
			<form action="pview.php" method="post">
				<input type="hidden" name="id" value="<?php echo($id); ?>">
				<div class="row">
					<div class="col-sm-4 col-sm-offset-4">
						<input class="btn btn-sm btn-default btn-block correct" type="submit" id="submit" value="Torna alla pagina del paziente">
					</div>
				</div>
			</form>
		<?php	
			}
		$mysqli->close();
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