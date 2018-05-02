<?php 
	if(!isset($_SESSION)) { session_start(); }
	include '../includes/config.php';
	include '../includes/ver_auth.php';
	include '../includes/connessione.php';
	
	if(!isset($_POST['id'])||$_POST['id']=='') {
		if(!isset($_SESSION['user'])){
			header('Location: index.php');
		}
	}
	
	if(!isset($_SESSION['user'])){
		$id = $_POST['id'];
		$patient = 0;
		include '../includes/sessioninit.php';
	}
	
	if($_SESSION['user']['patient']==1) {
		header('Location: ../index.php');
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
		if(isset($_SESSION['user'])) {
	?>
	
			<div class="page-header uprofile">
				<h2>Profilo utente</h2>
			</div>
			<div class="uprofile row">
				<div class="col-sm-4 col-sm-offset-4">
					<table class="table">
						<tr>
							<th>USERNAME</th>
							<td><?php echo($_SESSION['user']['username']); ?></td>
						</tr>
						<tr>
							<th>EMAIL</td>
							<td><?php echo($_SESSION['user']['email']); ?></td>
						</tr>
						<tr>
							<th>DATA DI NASCITA</th>
							<td><?php echo($_SESSION['user']['bday'].' / '.$_SESSION['user']['bmonth'].' / '.$_SESSION['user']['byear']); ?></td>
						</tr>
						<tr>
							<th>SESSO</th>
							<td><?php echo($_SESSION['user']['sex']); ?></td>
						</tr>
						<tr>
							<th>FACOLTÀ</th>
							<td><?php echo($_SESSION['user']['faculty']); ?></td>
						</tr>
						<tr>
							<th>ANNO CORSO</td>
							<td><?php echo($_SESSION['user']['syear']); ?></td>
						</tr>
						<tr>
							<th><b>SCOLARITÀ</th>
							<td><?php echo($_SESSION['user']['schol']); ?></td>
						</tr>
				<?php
					}
				?>
					</table>
				</div>
			</div>
			<div class="uprofile row">
				<div class="col-sm-2 col-sm-offset-5">
					<input class="btn btn-lg btn-default btn-block" type="button" name="modifica" value="Modifica Profilo" onclick="window.location.href = 'emodify.php'">
				</div>
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
	</body>
</html>