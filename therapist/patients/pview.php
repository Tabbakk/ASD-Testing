<?php 
	if(!isset($_SESSION)) { session_start(); }
	include '../../includes/config.php';
	include '../../includes/ver_auth_t_2.php';
	include '../../includes/connessione.php';
	
	if(!isset($_POST['id'])||$_POST['id']=='') {
		if(!isset($_SESSION['therapist']['patient'])){
			header('Location: ../../index.php');
		}
	}
	
	if(!isset($_SESSION['therapist']['patient'])){
		include '../../includes/patient_init.php';
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
		if(isset($_SESSION['therapist']['patient'])) {
	?>

			<div class="page-header pview">
				<h2><?php echo($_SESSION['therapist']['patient']['surname'].', '.$_SESSION['therapist']['patient']['name']); ?></h2>
				<div class="patient row">	
					<div class="col-sm-8 col-sm-offset-2">
					<table class="table table-bordered table-condensed table-striped">
						<tr>
							<th>ID</th>
							<th>USERNAME</th>
							<th>ETÀ</th>
							<th>SESSO</th>
							<th>SCOLARITÀ</th>
							<th>GRUPPO CLINICO</th>
							<th>CONTATTO</th>
						</tr>
						<tr>
							<td><?php echo($_SESSION['therapist']['patient']['id']); ?></td>
							<td><?php echo($_SESSION['therapist']['patient']['username']); ?></td>
							<td><?php echo($_SESSION['therapist']['patient']['age']); ?></td>
							<td><?php echo($_SESSION['therapist']['patient']['sex']); ?></td>
							<td><?php echo($_SESSION['therapist']['patient']['schol']); ?></td>
							<td><?php echo($_SESSION['therapist']['patient']['gname']); ?></td>
							<td><?php echo($_SESSION['therapist']['patient']['number']); ?></td>
						</tr>
					</table>
					</div>
				</div>
				<div class="poptions row">
					<div class="col-sm-2 col-sm-offset-3">
						<input class="btn btn-sm btn-default btn-block" type="button" value="Visualizza test svolti" onclick="window.location.href = 'pdone.php' ">
					</div>
					<div class="col-sm-2">
						<input class="btn btn-sm btn-default btn-block" type="submit" name="modifica" value="Modifica Paziente" onclick="window.location.href = 'pmodify.php'">
					</div>
					<div class="col-sm-2">
						<input class="btn btn-sm btn-default btn-block" type="submit" name="elimina" value="Elimina Paziente" onclick="window.location.href = 'peliminate.php'">
					</div>
				</div>
			</div>
			<div class="ptests row">
				<div class="col-sm-6 col-sm-offset-3">
					<table class="table table-condensed active">
						<tr>
							<th>TEST</th>
							<th>STATO</th>
						</tr>	
				<?php
					foreach ($_SESSION['therapist']['patient']['tests'] as $res) {
				?>		
						<tr>
							<td><?php echo($res['name']); ?></td>
							<td <?php if($res['active']==0){echo('class="nactive"');} ?> ><?php if($res['active']==1){echo('Attivo');}else{echo('Non Attivo');} ?></td>
						</tr>
				<?php	
					}
				}
			?>		
					</table>
				</div>
			</div>
			<div class="ptoptions row">
				<div class="col-sm-2 col-sm-offset-5">
					<input class="btn btn-sm btn-default btn-block type="submit" name="tests" value="Attiva/Disattiva test" onclick="window.location.href = 'ptests.php'">
				</div>
			</div>
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