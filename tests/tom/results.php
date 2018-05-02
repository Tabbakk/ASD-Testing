<?php
	include '../../includes/connessione.php';
	include '../../includes/config.php';
	include '../../includes/ver_auth_2.php';
	if(!isset($_SESSION)){ 
			session_start();
		}

		if(!isset($_POST['q1_13'])) {
		$_SESSION = array();
		session_destroy(); //distruggo tutte le sessioni
		header("Location: ../../index.php");
		die();
	}
	$_SESSION['tom_a']['13']['q1']=$_POST['q1_13'];
	$_SESSION['tom_a']['13']['q2']=$_POST['q2_13'];

?>
<html>
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
		  <script src="../../js/html5shiv.min.js"></script>
		  <script src="../../js/respond.min.js"></script>
		<![endif]-->
		<script type="text/javascript">
			function textCounter( field, countfield, maxlimit ) {
				if ( field.value.length > maxlimit ) {
					field.value = field.value.substring( 0, maxlimit );
					field.blur();
					field.focus();
					return false;
				} 
				else {
					countfield.value = maxlimit - field.value.length;
				}
			}
			$(document).ready(function(){
				$('#emotatt').keydown(function(e){
				  if(e.which == 13){
					   $('#emotatt').submit();
				   }
				});
			});
		</script>
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
					<a class="navbar-brand" href="../../index.php">DISCAB</a>
				  </div>
				  <div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-left">
					  <li><a href="../../index.php">Home</a></li>
		    <li class="dropdown active">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Test <span class="caret"></span></a>
                <ul class="dropdown-menu">
		<?php
			
			foreach ($_SESSION['test'] as $testname => $active) {
			if($active == 1) {
		?>
                  <li <?php if($testname=='tom'){echo('class="active"');} ?>><a href="../../tests/<?php echo($testname.'.php'); ?>"><?php echo($_SESSION['testnames'][$testname]); ?></a></li>
		<?php		
				}
			}

		?>
                </ul>
              </li>
				<?php
					if($_SESSION['user']['patient']==0) {
				?>
					  <li><a href="../../user/profile.php">Profilo</a></li>
				<?php
					}
				?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="../../logout.php">Logout</a></li>
					</ul>
				  </div><!--/.nav-collapse -->
				</div>
			</nav>

			<div class="page-header row tomresults">			

		<?php
			$q1resp = '';
			$q2resp = '';
			foreach ($_SESSION['tom_a'] as $dom) {
				$q1resp = $q1resp.$dom['q1'].',';
				$temp = str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a", ","), array(' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '),$dom['q2']);
				$q2resp = $q2resp.$temp.',';
			}
			
			$q1resp=rtrim($q1resp, ",");
			$q2resp=rtrim($q2resp, ",");
			
//*			
			$stmt = $mysqli->prepare('call tom_insert(?,?,?,?,?,@e)');
			$stmt->bind_param('ssiii', $q1resp, $q2resp, $_SESSION['user']['id'], $_SESSION['tom']['qtype'], $_SESSION['tom']['atype']);
			$stmt->execute() or die('Errore DB: '.$mysqli->error);
			$select = $mysqli->query('SELECT @e');
			$result = $select->fetch_assoc();
			$err     = $result['@e'];
			
			if($err==0) {
			?>
				<h3>Operazione avvenuta con successo</h3>
				<div class="col-sm-4 col-sm-offset-4">
					<button class="btn btn-lg btn-default btn-block" id="gohome" onclick="window.location.href = '../../index.php'">Torna alla Home</button>
				</div>
			<?php
			}
			$_SESSION['testflag']['tom']=1;
			else {
			?>
				<h3>Si è verificato un problema con il test!</h3>
				<div class="col-sm-3 col-sm-offset-2">
					<button class="btn btn-lg btn-default btn-block " id="retry" onclick="window.location.href = '../tom.php'">Riprova</button>
				</div>
				<div class="col-sm-3 col-sm-offset-2">
					<button class="btn btn-lg btn-default btn-block" id="gohome" onclick="window.location.href = '../../index.php'">Torna alla Home</button>
				</div>
			<?php
			}

			$_SESSION['tom'] = array();
			$_SESSION['tom_a'] = array();
			unset($_SESSION['tom']);
			unset($_SESSION['tom_a']);
//*/
		?>
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
	</body>
</html>	
