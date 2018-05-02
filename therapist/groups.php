<?php 
	if(!isset($_SESSION)) { session_start(); }
	include '../includes/config.php';
	include '../includes/ver_auth_t.php';
	include '../includes/groups_init.php';
?>

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
					<a class="navbar-brand" href="../index.php">DISCAB</a>
				  </div>
				  <div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-left">
					  <li><a href="../index.php">Home</a></li>				  
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pazienti <span class="caret"></span></a>
							<ul class="dropdown-menu">
							  <li><a href="../therapist/patients/search.php">Cerca</a></li>
							  <li><a href="../therapist/patients/pregister.php">Inserisci nuovo paziente</a></li>
							</ul>
						</li>
						<li class="active"><a href="../therapist/groups.php">Gruppi Clinici</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Test <span class="caret"></span></a>
							<ul class="dropdown-menu">
							  <li><a href="../therapist/tests/tlistuncorrected.php">Correzione Test</a></li>
							  <li><a href="../therapist/tests/tactivation.php">Attivazione Test Esterni</a></li>
							</ul>
						</li>
				<?php
					if($_SESSION['authorized']==3) {
				?>
		    <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Terapisti <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="../therapist/doc/search.php">Cerca</a></li>
                  <li><a href="../therapist/doc/dregister.php">Inserisci nuovo terapista</a></li>
                </ul>
			</li>
				<?php
					}
				?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Statistiche <span class="caret"></span></a>
							<ul class="dropdown-menu">
							  <li><a href="../therapist/stats/spdone.php">Pazienti</a></li>
							  <li><a href="../therapist/stats/sedone.php">Esterni</a></li>
							  <li><a href="../therapist/stats/spgenerate.php">Genera file Excel</a></li>
							  <li><a href="../therapist/stats/mategenerate.php">Genera file Matricole</a></li>
							</ul>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="../logout.php">Logout</a></li>
					</ul>
				  </div><!--/.nav-collapse -->
				</div>
			</nav>

		<div class="page-header cgroup">
			<h2>Gruppi Clinici</h2>

			<form id="" method="post" action="cgroup/gmodify.php" >
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3 cglis">
				<table class="table table-striped">
					<tr>
						<th></th>
						<th>ID</th>
						<th>Nome</th>
						<th>Numero Pazienti</th>
					</tr>
				<?php
					foreach($_SESSION['group'] as $gid) {
					if($gid['id']!=-1){
				?>
					<tr>
						<td><input type="radio" name="id" value="<?php echo($gid['id']); ?>" required></td>
						<td><?php echo($gid['id']); ?></td>
						<td><?php echo($gid['name']); ?></td>
						<td><?php echo($gid['num']); ?></td>
					</tr>
				<?php
					}
					}
				?>
				</table>
				</div>
			</div>
		</div>
		<div class="row cgopts">
			<div class="col-sm-2 col-sm-offset-3">
				<input class="btn btn-sm btn-default btn-block" type="submit" name="mod" value="Modifica">
			</div>
			<div class="col-sm-2">
				<input class="btn btn-sm btn-default btn-block" type="submit" name="del" value="Elimina">
			</div>
			<div class="col-sm-2">
				<input class="btn btn-sm btn-default btn-block" type="button" value="Nuovo Gruppo Clinico" onclick="window.location.href = 'cgroup/gregister.php' ">
			</div>
		</form>
		</div>
		</div>
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="../js/jQuery.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/docs.min.js"></script>
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="../js/ie10-viewport-bug-workaround.js"></script>
	</BODY>
</HTML>