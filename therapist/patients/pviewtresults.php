<?php 
	if(!isset($_SESSION)) { session_start(); }
	include '../../includes/config.php';
	include '../../includes/ver_auth_t_2.php';
	
	$_SESSION['therapist']['aq']=array();
	$_SESSION['therapist']['attint']=array();
	$_SESSION['therapist']['bes']=array();
	$_SESSION['therapist']['emotatt']=array();
	$_SESSION['therapist']['eyestask']=array();
	$_SESSION['therapist']['qe']=array();
	$_SESSION['therapist']['socialsit']=array();
	$_SESSION['therapist']['tom']=array();
	
	unset($_SESSION['therapist']['aq'],$_SESSION['therapist']['attint'],$_SESSION['therapist']['bes'],$_SESSION['therapist']['emotatt'],$_SESSION['therapist']['eyestask'],$_SESSION['therapist']['qe'],$_SESSION['therapist']['socialsit'],$_SESSION['therapist']['tom']);
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

			<div class="page-header ptestsview">
				
<!------ Quoziente Empatia ------->
		<?php
			if(isset($_POST['aq'])){
		?>
				<h2>Test Quoziente di empatia</h2>
				<div class="back row">
					<div class="col-sm-4 col-sm-offset-4">
						<input class="btn btn-sm btn-default btn-block" type="button" value="Torna ai test eseguiti" onclick="window.location.href = 'pdone.php' ">
					</div>
				</div>
			</div>
		<div class="aq_view">
		<table class="table table-striped">
			<tr>
				<th></th>
				<th>Risposta</th>
				<th>Domanda</th>
			</tr>
		<?php
			include '../../includes/view_aq.php';
			$i=1;
			foreach($_SESSION['therapist']['aq'] as $num => $dom){
				if($dom['a']=='A'){$risp='Assolutamente d\'accordo';}
				else if ($dom['a']=='B'){$risp='D\'accordo';}
				else if ($dom['a']=='C'){$risp='In disaccordo';}
				else if ($dom['a']=='D'){$risp='Assolutamente in disaccordo';}
		?>
					<tr>
						<td><?php echo($num); ?></td>
						<td><b><?php echo($risp); ?></b></td>
						<td><?php echo($dom['q']); ?></td>
					</tr>
		<?php
			}
		?>
		</table>
		</div>
		<?php
		}
	?>

	
<!---------- Attribuzione Intenzioni --------->
	
	<?php
		if(isset($_POST['attint'])){
			include '../../includes/view_attint.php';
			$nattint = array(1=>'serie A1 ordine 1', 2=>'serie A2 ordine 2', 3=>'serie E', 4=>'serie LP ordine 2', 5=>'serie SLP ordine 3', );
		
	?>
				<h2>Test Attribuzione delle intenzioni <?php echo($nattint[$_SESSION['therapist']['attint']['series']]); ?></h2>
				<div class="back row">
					<div class="col-sm-4 col-sm-offset-4">
						<input class="btn btn-sm btn-default btn-block" type="button" value="Torna ai test eseguiti" onclick="window.location.href = 'pdone.php' ">
					</div>
				</div>
			</div>
		<div class="attint_view">
		<table class="table table-condensed table-striped">
			<tr>
				<div class="row">			
					<th class="col-sm-1">#</th>
					<th class="col-sm-8">Sequenza</th>
					<th class="col-sm-3">Risposta</th>
				</div>
			</tr>
	<?php
			foreach($_SESSION['therapist']['attint'] as $num => $dom){
				if($num!='series'){
	?>
			<tr>
				<div class="row">
					<td class="col-sm-1 num"><?php echo($num); ?></td>
					<td class="seq col-sm-8">
						<img class="img-responsive col-sm-4" src="<?php echo('../../img/attint/'.$dom['A'].'.jpg')?>" alt="Prima Immagine">
						<img class="img-responsive col-sm-4" src="<?php echo('../../img/attint/'.$dom['B'].'.jpg')?>" alt="Prima Immagine">
						<img class="img-responsive col-sm-4" src="<?php echo('../../img/attint/'.$dom['C'].'.jpg')?>" alt="Prima Immagine">
					</td>
					<td class="answ col-sm-3">
						<img class="img-responsive"  src="<?php echo('../../img/attint/'.$dom['answer'].'.jpg')?>" alt="Prima Immagine">
					</td>
				</div>			
			</tr>
	<?php
				}
			}
	?>
		</table>
		</div>
	<?php
		}
	?>


<!----------- Empathy Scale ---------------->	
	<?php
		if(isset($_POST['bes'])){
	?>
				<h2>Test Basic Empathy Scale</h2>
				<div class="back row">
					<div class="col-sm-4 col-sm-offset-4">
						<input class="btn btn-sm btn-default btn-block" type="button" value="Torna ai test eseguiti" onclick="window.location.href = 'pdone.php' ">
					</div>
				</div>
			</div>
		<div class="bes_view">
		<table class="table table-striped">
			<tr>
				<th></th>
				<th>Risposta</th>
				<th>Domanda</th>
			</tr>
	<?php
			include '../../includes/view_bes.php';
			foreach($_SESSION['therapist']['bes'] as $num => $dom){
				if($dom['a']=='A'){$risp='Fortemente in disaccordo';}
				else if ($dom['a']=='B'){$risp='In disaccordo';}
				else if ($dom['a']=='C'){$risp='Ne\' d\'accordo ne\' in disaccordo';}
				else if ($dom['a']=='D'){$risp='D\'accordo';}
				else if ($dom['a']=='E'){$risp='Fortemente d\'accordo';}
		?>
					<tr>
						<td><?php echo($num); ?></td>
						<td><b><?php echo($risp); ?></b></td>
						<td><?php echo($dom['q']); ?></td>
					</tr>
		<?php
			}
		?>
		</table>
		</div>
		<?php
		}
	?>

<!----------- Attribuzione emozioni ------------------->
	<?php
		if(isset($_POST['emotatt'])){
			include '../../includes/view_emotatt.php';
	?>
			<table>
				<tr>
					<td><h3>Test corretto da:</h3></td>
					<td>
						<table>
						
	<?php 
			foreach($_SESSION['therapist']['emotatt']['doc'] as $doc){
				echo('<tr><td><h3>'.$doc.'</h3></td></tr>');
			}
	?>
						</table>
					</td>
				</tr>
			</table>	
		<table>
			<tr>
				<th></th>
				<th>Domanda</th>
				<th>Risposta data</th>
				<th>Risposta attesa</th>
			</tr>
	<?php
			$i=1;
			foreach($_SESSION['therapist']['emotatt'] as $num => $dom){
				if($num!='doc'){
		?>
					<tr>
						<td><?php echo($num.': '); ?></td>
						<td><?php echo($dom['question']); ?></td>
						<td><b><?php echo($dom['answer']); ?></b></td>
						<td><?php echo($dom['ca']); ?></td>
					</tr>
		<?php
				}
			}
		?>
		</table>
		<?php
		}
	?>

	
<!--------------- Eyes Task --------------->	
	<?php
		if(isset($_POST['eyestask'])){
			include '../../includes/view_eyestask.php';			
	?>
				<h2>Eyes task</h2>
				<div class="back row">
					<div class="col-sm-4 col-sm-offset-4">
						<input class="btn btn-sm btn-default btn-block" type="button" value="Torna ai test eseguiti" onclick="window.location.href = 'pdone.php' ">
					</div>
				</div>
			</div>
		<div class="eyestask_view">
		<table class="table table-striped">
			<tr>
				<th></th>
				<th>Immagine</th>
				<th>Risposte</th>
			</tr>
	<?php
		foreach($_SESSION['therapist']['eyestask'] as $num => $dom){
	?>
			<tr>
				<td class="num"><?php echo($num); ?></td>
				<td><img class="img-responsive" src="<?php echo('../../img/eyestask/'.$dom['img'].'.jpg')?>" alt="occhi"></td>
				<td class="answ">
					<ul>
						<?php if($dom['answer']=='A') {echo('<li><b>'.$dom['A'].'</b></li>');} else {echo('<li style="list-style-type:none">'.$dom['A'].'</li>');} ?>
						<?php if($dom['answer']=='B') {echo('<li><b>'.$dom['B'].'</b></li>');} else {echo('<li style="list-style-type:none">'.$dom['B'].'</li>');} ?>
						<?php if($dom['answer']=='C') {echo('<li><b>'.$dom['C'].'</b></li>');} else {echo('<li style="list-style-type:none">'.$dom['C'].'</li>');} ?>
						<?php if($dom['answer']=='D') {echo('<li><b>'.$dom['D'].'</b></li>');} else {echo('<li style="list-style-type:none">'.$dom['D'].'</li>');} ?>
					</ul>
				</td>
			</tr>
	<?php
		}
	?>
		</table>
		</div>
	<?php
		}
	?>

	<?php
		if(isset($_POST['qe'])){
	?>
		<table>
			<tr>
				<th></th>
				<th>Risposta</th>
				<th>Domanda</th>
			</tr>
	<?php
			include '../../includes/view_qe.php';
			foreach($_SESSION['therapist']['qe'] as $num => $dom){
				if($dom['a']=='A'){$risp='Assolutamente d\'accordo';}
				else if ($dom['a']=='B'){$risp='D\'accordo';}
				else if ($dom['a']=='C'){$risp='In disaccordo';}
				else if ($dom['a']=='D'){$risp='Assolutamente in disaccordo';}
		?>
					<tr>
						<td><?php echo($num.': '); ?></td>
						<td><b><?php echo($risp); ?></b></td>
						<td><?php echo($dom['q']); ?></td>
					</tr>
		<?php
			}
		?>
		</table>
		<?php
		}
	?>

	<?php
		if(isset($_POST['socialsit'])){
			include '../../includes/view_socialsit.php';
			$risp = array('A' => 'Comportamento Normale', 'B' => 'Comportamento un po\' strano', 'C' => 'Comportamento abbastanza strano', 'D' => 'Comportamento estremamente strano');
			$style='style="border: 1 0 0 1 solid black;"'
	?>
		<table>
			<tr>
				<th></th>
				<th>Domanda</th>
				<th>Risposta</th>
			</tr>
	<?php
		foreach($_SESSION['therapist']['socialsit'] as $num => $temp){
			foreach($temp as $snum => $dom){
	?>
			<tr>
				<td><?php echo($num.'.'.$snum); ?></td>
				<td><?php echo($dom['intro'].' <b>'.$dom['question'].'</b>'); ?></td>
				<td><?php echo($risp[$dom['answer']]); ?></td>
			</tr>
	<?php
			}
		}
	?>
		</table>
	<?php
		}
	?>	

	<?php
		if(isset($_POST['tom'])){
			include '../../includes/view_tom.php';
	?>
		<h3>Test corretto da: <?php echo($_SESSION['therapist']['tom']['doc']); ?></h3>	
	<?php
			foreach($_SESSION['therapist']['tom'] as $num => $dom){
				if($num!='doc'){
	?>
		<table style="border:1px solid black; width:100%;">
			<tr>
				<th><?php echo($num); ?></th>
			</tr>
			<tr>
				<td><?php echo($dom['intro']); ?></td>
			</tr>
			<tr>
				<td>
					<table>
						<tr>
							<td><?php echo($dom['q1']); ?></td>
							<td><b><?php if($dom['a1']==0){echo('NO');}else{echo('SI\'');} ?></b></td>
						</tr>
						<tr>
							<td><?php echo($dom['q2']); ?></td>
							<td><b><?php echo($dom['a2']); ?></b></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
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