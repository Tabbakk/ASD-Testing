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
	
	$count=0;
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

			<div class="page-header ptests">
				<h2>Test eseguiti da <?php echo($_SESSION['therapist']['patient']['surname'].', '.$_SESSION['therapist']['patient']['name']); ?></h2>
				<div class="row">
					<div class="retpatient col-sm-4 col-sm-offset-4">
						<input class="btn btn-sm btn-default btn-block" type="button" value="Torna alla pagina del Paziente" onclick="window.location.href = 'pview.php' ">
					</div>
				</div>
			</div>
			<div class="testslist row">
<!-- Test Quoziente di autismo -->
		<?php
			$sql= 'Select idnumber, total, day(day) as d, month(day) as m, year(day) as y, day from aq_c where patient='.$_SESSION['therapist']['patient']['id'].' order by day asc';
			
			$select=$mysqli->query($sql);
			if(mysqli_num_rows($select) > 0){
				$count=$count+1;
				$sql2='select ROUND(avg(total),2) as media from aq_c where patient='.$_SESSION['therapist']['patient']['id'];
				$select2=$mysqli->query($sql2);
				$res=$select2->fetch_assoc();
				$select2->free();
		?>
		<div class="test col-sm-6 col-sm-offset-3">
			<div class="list col-sm-12">
			<h3>Quoziene dello spettro di autismo</h3>
			<div class="row last">
				<p class="col-sm-4">Punteggio medio:</p>
				<p class="col-sm-3"><?php echo($res['media']); ?></p>
			</div>
			<form id="aq" method="post" action="pviewtresults.php">
			<table class="table table-compact table-striped" >
				<tr>
					<th></th>
					<th>Punteggio</th>
					<th>Data</th>
				</tr>
			<?php
				while($res = $select->fetch_assoc()){
			?>
				<tr>
					<td><input type="radio" name="aq" value="<?php echo($res['idnumber']); ?>" required ></td>
					<td><?php echo($res['total']); ?></td>
					<td><?php echo($res['d'].'/'.$res['m'].'/'.$res['y']); ?></td>
				</tr>
			<?php
				}
			?>
			</table>
			</div>
			<div class="col-sm-4 col-sm-offset-4">
				<input class="btn btn-sm btn-default btn-block" type="submit" name="submit" value="Visualizza test" >
			</div>
			</form>
		</div>
		<?php
		}
		?>

<!-- Test Attribuzione intenzioni -->
		<?php
			$sql= 'Select idnumber, total, series, day(day) as d, month(day) as m, year(day) as y, day from attint_c where patient='.$_SESSION['therapist']['patient']['id'].' order by day asc';
			
			$select=$mysqli->query($sql);
			if(mysqli_num_rows($select) > 0){
				$count=$count+1;
				$i=1;
				$num=0;
				while($i<6){
					$sql2='select ROUND(avg(total),2) as media from attint_c where series='.$i.' and patient='.$_SESSION['therapist']['patient']['id'];
					$select2=$mysqli->query($sql2);
					$avg[$i]=$select2->fetch_assoc()['media'];
					if(!is_null($avg[$i])){$num=$num+1;}
					$select2->free();
					$i=$i+1;
				}
				$nattint = array(1=>'serie A1 ordine 1', 2=>'serie A2 ordine 2', 3=>'serie E', 4=>'serie LP ordine 2', 5=>'serie SLP ordine 3', );

		?>
		<div class="test col-sm-6 col-sm-offset-3">
			<div class="list col-sm-12">
				<h3>Attribuzione delle intenzioni</h3>
				<?php 
					$i=1;
					$first=true;
					while($i<6) {
						if(!is_null($avg[$i])){
							if($first){
				?>
					<div class="row<?php if($num==1){echo(' last');} ?>">
						<p class="col-sm-4">Punteggio medio:</p>
						<p class="col-sm-4"><?php echo($nattint[$i]); ?>:</p>
						<p class="col-sm-3"><?php echo($avg[$i]); ?></p>
					</div>
				<?php
						$first=false;
						}
						else{
				?>
					<div class="last row">
						<p class="col-sm-4 col-sm-offset-4"><?php echo($nattint[$i]); ?>:</p>
						<p class="col-sm-3"><?php echo($avg[$i]); ?></p>
					</div>
				<?php	
						}
						}
					$i=$i+1;
					}
				?>
				<form id="attint" method="post" action="pviewtresults.php">
				<table class="table table-compact table-striped" >
					<tr>
						<th></th>
						<th>Serie</th>
						<th>Punteggio</th>
						<th>Data</th>
					</tr>
				<?php
					while($res = $select->fetch_assoc()){
				?>
					<tr>
						<td><input type="radio" name="attint" value="<?php echo($res['idnumber']); ?>" required ></td>
						<td><?php echo($nattint[$res['series']]); ?></td>
						<td><?php echo($res['total']); ?></td>
						<td><?php echo($res['d'].'/'.$res['m'].'/'.$res['y']); ?></td>
					</tr>
				<?php
					}
				?>
				</table>
			</div>
			<div class="col-sm-4 col-sm-offset-4">
				<input class="btn btn-sm btn-default btn-block" type="submit" name="submit" value="Visualizza test" >
			</div>
			</form>
		</div>
		<?php
		}
		?>

<!-- Test Basic Empathy Scale -->
		<?php
			$sql= 'Select idnumber, CE, AE, day(day) as d, month(day) as m, year(day) as y, day from bes_c where patient='.$_SESSION['therapist']['patient']['id'].' order by day asc';
			
			$select=$mysqli->query($sql);
			if(mysqli_num_rows($select) > 0){
				$count=$count+1;
				$sql2='select ROUND(avg(CE),2) as mCE, ROUND(avg(AE),2) as mAE from bes_c where patient='.$_SESSION['therapist']['patient']['id'];
				$select2=$mysqli->query($sql2);
				$res=$select2->fetch_assoc();
				$select2->free();
		?>
		<div class="test col-sm-6 col-sm-offset-3">
			<div class="list col-sm-12">
				<h3>Empathy Scale</h3>
					<div class="row">
						<p class="col-sm-4">Punteggio medio:</p>
						<p class="col-sm-4">Emp. Cognitiva:</p>
						<p class="col-sm-3"><?php echo($res['mCE']); ?></p>
					</div>
					<div class="row last">
						<p class="col-sm-4 col-sm-offset-4">Emp. Affettiva:</p>
						<p class="col-sm-3"><?php echo($res['mAE']); ?></p>
					</div>
				<form id="bes" method="post" action="pviewtresults.php">
				<table class="table table-compact table-striped" >
					<tr>
						<th></th>
						<th>Punteggio Totale</th>
						<th>Punteggio Cognitivo</th>
						<th>Punteggio Affettivo</th>
						<th>Data</th>
					</tr>
				<?php
					while($res = $select->fetch_assoc()){
				?>
					<tr>
						<td><input type="radio" name="bes" value="<?php echo($res['idnumber']); ?>" required ></td>
						<td><?php echo($res['CE']+$res['AE']); ?></td>
						<td><?php echo($res['CE']); ?></td>
						<td><?php echo($res['AE']); ?></td>
						<td><?php echo($res['d'].'/'.$res['m'].'/'.$res['y']); ?></td>
					</tr>
				<?php
					}
				?>
				</table>
			</div>
				<div class="col-sm-4 col-sm-offset-4">
					<input class="btn btn-sm btn-default btn-block" type="submit" name="submit" value="Visualizza test" >
				</div>
			</form>
		</div>
		<?php
		}
		?>


<!-- Test Attribuzione Emozioni -->
		<?php
			$sql= 'Select idnumber, E1, E2, E3, E4, E5, E6, E7, day(day) as d, month(day) as m, year(day) as y, day from emotatt_c where completed=1 and patient='.$_SESSION['therapist']['patient']['id'].' order by day asc';
			
			$select=$mysqli->query($sql);
			if(mysqli_num_rows($select) > 0){
				$count=$count+1;
				$sql2='select ROUND(avg(E1),2) as mE1, ROUND(avg(E2),2) as mE2, ROUND(avg(E3),2) as mE3, ROUND(avg(E4),2) as mE4, ROUND(avg(E5),2) as mE5, ROUND(avg(E6),2) as mE6, ROUND(avg(E7),2) as mE7 from emotatt_c where completed=1 and patient='.$_SESSION['therapist']['patient']['id'];
				$select2=$mysqli->query($sql2);
				$res=$select2->fetch_assoc();
				$select2->free();
		?>
		<div class="test col-sm-6 col-sm-offset-3">
			<div class="list col-sm-12">
				<h3>Attribuzione delle emozioni</h3>
					<div class="row">
						<p class="col-sm-4">Punteggio medio:</p>
						<p class="col-sm-4">Tristezza:</p>
						<p class="col-sm-3"><?php echo($res['mE1']); ?></p>
					</div>
					<div class="row">
						<p class="col-sm-4 col-sm-offset-4">Paura:</p>
						<p class="col-sm-3"><?php echo($res['mE2']); ?></p>
					</div>
					<div class="row">
						<p class="col-sm-4 col-sm-offset-4">Imbarazzo:</p>
						<p class="col-sm-3"><?php echo($res['mE3']); ?></p>
					</div>
					<div class="row">
						<p class="col-sm-4 col-sm-offset-4">Disgusto:</p>
						<p class="col-sm-3"><?php echo($res['mE4']); ?></p>
					</div>
					<div class="row">
						<p class="col-sm-4 col-sm-offset-4">Felicità:</p>
						<p class="col-sm-3"><?php echo($res['mE5']); ?></p>
					</div>
					<div class="row">
						<p class="col-sm-4 col-sm-offset-4">Rabbia:</p>
						<p class="col-sm-3"><?php echo($res['mE6']); ?></p>
					</div>
					<div class="row last">
						<p class="col-sm-4 col-sm-offset-4">Invidia:</p>
						<p class="col-sm-3"><?php echo($res['mE7']); ?></p>
					</div>
				<form id="emotatt" method="post" action="pviewtresults.php">
				<table class="table table-compact table-striped" >
					<tr>
						<th></th>
						<th>Tristezza</th>
						<th>Paura</th>
						<th>Imbarazzo</th>
						<th>Disgusto</th>
						<th>Felicita'</th>
						<th>Rabbia</th>
						<th>Invidia</th>
						<th>Data</th>
					</tr>
				<?php
					while($res = $select->fetch_assoc()){
				?>
					<tr>
						<td><input type="radio" name="emotatt" value="<?php echo($res['idnumber']); ?>" required ></td>
						<td><?php echo($res['E1']); ?></td>
						<td><?php echo($res['E2']); ?></td>
						<td><?php echo($res['E3']); ?></td>
						<td><?php echo($res['E4']); ?></td>
						<td><?php echo($res['E5']); ?></td>
						<td><?php echo($res['E6']); ?></td>
						<td><?php echo($res['E7']); ?></td>
						<td><?php echo($res['d'].'/'.$res['m'].'/'.$res['y']); ?></td>
					</tr>
				<?php
					}
				?>
				</table>
			</div>
				<div class="col-sm-4 col-sm-offset-4">
					<input class="btn btn-sm btn-default btn-block" type="submit" name="submit" value="Visualizza test" >
				</div>
			</form>
		</div>
		<?php
		}
		?>

<!-- Test Eyes Task -->
		<?php
			$sql= 'Select idnumber, total, day(day) as d, month(day) as m, year(day) as y, day from eyestask_c where patient='.$_SESSION['therapist']['patient']['id'].' order by day asc';
			
			$select=$mysqli->query($sql);
			if(mysqli_num_rows($select) > 0){
				$count=$count+1;
				$sql2='select ROUND(avg(total),2) as media from eyestask_c where patient='.$_SESSION['therapist']['patient']['id'];
				$select2=$mysqli->query($sql2);
				$res=$select2->fetch_assoc();
				$select2->free();
		?>
		<div class="test col-sm-6 col-sm-offset-3">
			<div class="list col-sm-12">
				<h3>Eyes Task</h3>
			<div class="row last">
				<p class="col-sm-4">Punteggio medio:</p>
				<p class="col-sm-3"><?php echo($res['media']); ?></p>
			</div>
				<form id="eyestask" method="post" action="pviewtresults.php">
				<table class="table table-compact table-striped" >
					<tr>
						<th></th>
						<th>Punteggio</th>
						<th>Data</th>
					</tr>
				<?php
					while($res = $select->fetch_assoc()){
				?>
					<tr>
						<td><input type="radio" name="eyestask" value="<?php echo($res['idnumber']); ?>" required ></td>
						<td><?php echo($res['total']); ?></td>
						<td><?php echo($res['d'].'/'.$res['m'].'/'.$res['y']); ?></td>
					</tr>
				<?php
					}
				?>
				</table>
			</div>
				<div class="col-sm-4 col-sm-offset-4">
					<input class="btn btn-sm btn-default btn-block" type="submit" name="submit" value="Visualizza test" >
				</div>
			</form>
		</div>
		<?php
		}
		?>


<!-- Test Quoziente Empatia -->
		<?php
			$sql= 'Select idnumber, CE, SS, EE, total, day(day) as d, month(day) as m, year(day) as y, day from qe_c where patient='.$_SESSION['therapist']['patient']['id'].' order by day asc';
			
			$select=$mysqli->query($sql);
			if(mysqli_num_rows($select) > 0){
				$count=$count+1;
				$sql2='select ROUND(avg(CE),2) as mCE, ROUND(avg(SS),2) as mSS, ROUND(avg(EE),2) as mEE, ROUND(avg(total),2) as mtot from qe_c where patient='.$_SESSION['therapist']['patient']['id'];
				$select2=$mysqli->query($sql2);
				$res=$select2->fetch_assoc();
				$select2->free();
		?>
		<div class="test col-sm-6 col-sm-offset-3">
			<div class="list col-sm-12">
				<h3>Quoziene di Empatia</h3>
					<div class="row">
						<p class="col-sm-4">Punteggio medio:</p>
						<p class="col-sm-4">Cognitiva:</p>
						<p class="col-sm-3"><?php echo($res['mCE']); ?></p>
					</div>
					<div class="row">
						<p class="col-sm-4 col-sm-offset-4">Social Skills:</p>
						<p class="col-sm-3"><?php echo($res['mSS']); ?></p>
					</div>
					<div class="row">
						<p class="col-sm-4 col-sm-offset-4">Emozionale:</p>
						<p class="col-sm-3"><?php echo($res['mEE']); ?></p>
					</div>
					<div class="row last">
						<p class="col-sm-4 col-sm-offset-4">Totale:</p>
						<p class="col-sm-3"><?php echo($res['mtot']); ?></p>
					</div>
				<form id="qe" method="post" action="pviewtresults.php">
				<table class="table table-compact table-striped" >
					<tr>
						<th></th>
						<th>Cognitiva</th>
						<th>Social Skills</th>
						<th>Emozionale</th>
						<th>Totale</th>
						<th>Data</th>
					</tr>
				<?php
					while($res = $select->fetch_assoc()){
				?>
					<tr>
						<td><input type="radio" name="qe" value="<?php echo($res['idnumber']); ?>" required ></td>
						<td><?php echo($res['CE']); ?></td>
						<td><?php echo($res['SS']); ?></td>
						<td><?php echo($res['EE']); ?></td>
						<td><?php echo($res['total']); ?></td>
						<td><?php echo($res['d'].'/'.$res['m'].'/'.$res['y']); ?></td>
					</tr>
				<?php
					}
				?>
				</table>
			</div>
				<div class="col-sm-4 col-sm-offset-4">
					<input class="btn btn-sm btn-default btn-block" type="submit" name="submit" value="Visualizza test" >
				</div>
			</form>
		</div>
		<?php
		}
		?>


<!-- Test Situazioni Sociali -->
		<?php
			$sql= 'Select idnumber, NormB, Violation, Severity, day(day) as d, month(day) as m, year(day) as y, day from socialsit_c where patient='.$_SESSION['therapist']['patient']['id'].' order by day asc';
			
			$select=$mysqli->query($sql);
			if(mysqli_num_rows($select) > 0){
				$count=$count+1;
				$sql2='select ROUND(avg(NormB),2) as mNorm, ROUND(avg(Violation),2) as mViol, ROUND(avg(Severity),2) as mSev from socialsit_c where patient='.$_SESSION['therapist']['patient']['id'];
				$select2=$mysqli->query($sql2);
				$res=$select2->fetch_assoc();
				$select2->free();
		?>
		<div class="test col-sm-6 col-sm-offset-3">
			<div class="list col-sm-12">
				<h3>Situazioni Sociali</h3>
					<div class="row">
						<p class="col-sm-4">Punteggio medio:</p>
						<p class="col-sm-4">C. Normale:</p>
						<p class="col-sm-3"><?php echo($res['mNorm']); ?></p>
					</div>
					<div class="row">
						<p class="col-sm-4 col-sm-offset-4">Violazione:</p>
						<p class="col-sm-3"><?php echo($res['mViol']); ?></p>
					</div>
					<div class="row last">
						<p class="col-sm-4 col-sm-offset-4">Gravità:</p>
						<p class="col-sm-3"><?php echo($res['mSev']); ?></p>
					</div>
				<form id="socialsit" method="post" action="pviewtresults.php">
				<table class="table table-compact table-striped" >
					<tr>
						<th></th>
						<th>Normale</th>
						<th>Violazione</th>
						<th>Severità</th>
						<th>Data</th>
					</tr>
				<?php
					while($res = $select->fetch_assoc()){
				?>
					<tr>
						<td><input type="radio" name="socialsit" value="<?php echo($res['idnumber']); ?>" required ></td>
						<td><?php echo($res['NormB']); ?></td>
						<td><?php echo($res['Violation']); ?></td>
						<td><?php echo($res['Severity']); ?></td>
						<td><?php echo($res['d'].'/'.$res['m'].'/'.$res['y']); ?></td>
					</tr>
				<?php
					}
				?>
				</table>
			</div>
				<div class="col-sm-4 col-sm-offset-4">
					<input class="btn btn-sm btn-default btn-block" type="submit" name="submit" value="Visualizza test" >
				</div>
			</form>
		</div>
		<?php
		}
		?>

<!-- Test Teoria della mente -->
		<?php
			$sql= 'Select idnumber, total, day(day) as d, month(day) as m, year(day) as y, day from tom_c where completed=1 and patient='.$_SESSION['therapist']['patient']['id'].' order by day asc';
			
			$select=$mysqli->query($sql);
			if(mysqli_num_rows($select) > 0){
				$count=$count+1;
				$sql2='select ROUND(avg(total),2) as media from tom_c where completed=1 and patient='.$_SESSION['therapist']['patient']['id'];
				$select2=$mysqli->query($sql2);
				$res=$select2->fetch_assoc();
				$select2->free();
		?>
		<div class="test col-sm-6 col-sm-offset-3">
			<div class="list col-sm-12">
				<h3>Teoria della mente</h3>
			<div class="row last">
				<p class="col-sm-4">Punteggio medio:</p>
				<p class="col-sm-3"><?php echo($res['media']); ?></p>
			</div>
				<form id="tom" method="post" action="pviewtresults.php">
				<table class="table table-compact table-striped" >
					<tr>
						<th></th>
						<th>Punteggio</th>
						<th>Data</th>
					</tr>
				<?php
					while($res = $select->fetch_assoc()){
				?>
					<tr>
						<td><input type="radio" name="tom" value="<?php echo($res['idnumber']); ?>" required ></td>
						<td><?php echo($res['total']); ?></td>
						<td><?php echo($res['d'].'/'.$res['m'].'/'.$res['y']); ?></td>
					</tr>
				<?php
					}
				?>
				</table>
			</div>
				<div class="col-sm-4 col-sm-offset-4">
					<input class="btn btn-sm btn-default btn-block" type="submit" name="submit" value="Visualizza test" >
				</div>
			</form>
		</div>
		</div>
		<?php
		}
		
		$select->free();
		$mysqli->close();
		
		if($count==0){echo('<p>Questo paziente non ha eseguito alcun test.</p>');}
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