<?php
if(!isset($_SESSION)){
	session_start();
}
if(!isset($_SESSION['authorized'])){
	$_SESSION['authorized']=0;
}
//se non c'è la sessione registrata
if ($_SESSION['authorized']!=2){
	if($_SESSION['authorized']!=3){
	if(!isset($_SESSION)) { session_start(); }
	if (ini_get('register_globals')) {
		foreach ($_SESSION as $key=>$value)
		{
			if (isset($GLOBALS[$key]))
				unset($GLOBALS[$key]);
		}
	}
 				
?>
<HTML>
	<HEAD>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="description" content="CRRA Testing">
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
		  <script src="../js/html5shiv.min.js"></script>
		  <script src="../js/respond.min.js"></script>
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
					<a class="navbar-brand" href="../index.php">CRRA</a>
				  </div>
				  <div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-left">
					  <li><a href="../index.php">Home</a></li>
					  <li><a href="../login.php">Login</a></li>
					  <li><a href="../user/eregister.php">Registrati</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="../login_t.php">Login Personale CRRA</a></li>
					</ul>
				  </div><!--/.nav-collapse -->
				</div>
			</nav>
			
			<div class="page-header homepage">
				<h1>Accesso riservato al personale CRRA</h1>
				<p>Effettuare il login per visualizzare questa pagina.</p>
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
	
<?php
	die;
	}
}