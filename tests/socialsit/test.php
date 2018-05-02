<?php
	include '../../includes/config.php';
	include '../../includes/ver_auth_2.php';
	if(!isset($_SESSION)){ 
			session_start();
		}
	if(!isset($_GET['p'])){
	
		// se non c'è il parametro p che indica la pagina, distruggo tutto e vado alla home.
		
		$_SESSION = array();
		session_destroy(); //distruggo tutte le sessioni
		header("Location: ../../index.php?nientep");
		die();	
	}
	if((int)($_GET['p'])){
	
		// questa verifica valuta se si è saltato ad una pagina  p inesistente o oltre a quelli già effettuati  
		
		$num=(int)($_GET['p']);
		if($num>1) {
			if(!isset($_POST['q'.($num-1).'_1']) && !isset($_SESSION['socialsit_a'][($num-1)])) {
				$_SESSION = array();
				session_destroy(); //distruggo tutte le sessioni
				header("Location: ../../index.php?saltato");
				die();
			}
			if(isset($_POST['q'.($num-1).'_1'])) {
				$_SESSION['socialsit_a'][($num-1)][1]=$_POST['q'.($num-1).'_1'];
				if(isset($_POST['q'.($num-1).'_2'])) {
					$_SESSION['socialsit_a'][($num-1)][2]=$_POST['q'.($num-1).'_2'];
				}
				if(isset($_POST['q'.($num-1).'_3'])) {
					$_SESSION['socialsit_a'][($num-1)][3]=$_POST['q'.($num-1).'_3'];
				}
				
			}
		}
	}
	else {
		$_SESSION = array();
		session_destroy(); //distruggo tutte le sessioni
		header("Location: ../../index.php?nonum");
		die();	
	}
	if ($num<25){
		$link='test.php?p='.($num+1);
		$button='Avanti';
	}
	else {
		$link='results.php';
		$button='Concludi';
	}
	$back=$_SERVER['PHP_SELF'].'?p='.($num-1);
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
	</HEAD>
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
                  <li <?php if($testname=='socialsit'){echo('class="active"');} ?>><a href="../../tests/<?php echo($testname.'.php'); ?>"><?php echo($_SESSION['testnames'][$testname]); ?></a></li>
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
			<form id="qe" action="<?php echo($link); ?>" method="post">
			<?php
				foreach ($_SESSION['socialsit'][$num] as $subnum => $subd) {
			?>
			<div class="page-header socialsittest">
			<?php 
				if($subnum == 1) {
			?>
				<h4>Storia numero <?php echo($num); ?></h4>				
			<?php
				}
			?>
				<p class="question"><?php echo($subd['intro']) ?>. <b><?php echo($subd['question']); ?></b></p>
			</div>
			<div class="socialsittest">			
					<div class="row">
						<div class="radio">
							<div class="col-sm-3 answ">
								<label>
									<input type="radio" value="A" name="q<?php echo($num.'_'.$subnum); ?>" id="q<?php echo($num.'_'.$subnum); ?>a" <?php if(isset($_SESSION['socialsit_a'][$num][$subnum])){if($_SESSION['socialsit_a'][$num][$subnum]=='A'){echo('checked="checked"');}} ?> required>
									Comportamento normale
								</label>
							</div>
							<div class="col-sm-3 answ">
								<label>
									<input type="radio" value="B" name="q<?php echo($num.'_'.$subnum); ?>" id="q<?php echo($num.'_'.$subnum); ?>b" <?php if(isset($_SESSION['socialsit_a'][$num][$subnum])){if($_SESSION['socialsit_a'][$num][$subnum]=='B'){echo('checked="checked"');}} ?> required>
									Comportamento un po' strano
								</label>
							</div>
							<div class="col-sm-3 answ">
								<label>
									<input type="radio" value="C" name="q<?php echo($num.'_'.$subnum); ?>" id="q<?php echo($num.'_'.$subnum); ?>c" <?php if(isset($_SESSION['socialsit_a'][$num][$subnum])){if($_SESSION['socialsit_a'][$num][$subnum]=='C'){echo('checked="checked"');}} ?> required>
									Comportamento abbastanza strano
								</label>
							</div>
							<div class="col-sm-3 answ">
								<label>
									<input type="radio" value="D" name="q<?php echo($num.'_'.$subnum); ?>" id="q<?php echo($num.'_'.$subnum); ?>d" <?php if(isset($_SESSION['socialsit_a'][$num][$subnum])){if($_SESSION['socialsit_a'][$num][$subnum]=='D'){echo('checked="checked"');}} ?> required>
									Comportamento estremamente strano
								</label>
							</div>
						</div>
					</div>
				</div>
			<?php
			}
			?>
				<div class="row">
					<div class="col-sm-6 col-sm-offset-3">	
						<div class="col-sm-4 col-sm-offset-2">
							<input type="button" class="btn btn-lg btn-default btn-block" <?php if($num==1){echo('disabled="disabled"');}?> onclick="window.location.href = '<?php echo($back) ?>'" value="Indietro" >
						</div>
						<div class="col-sm-4">
							<button class="btn btn-lg btn-default btn-block col-sm-3 col-sm-offset-2" type="submit" id="submit"><?php echo($button); ?></button>
						</div>
					</div>
				</div>
			</form>  
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
