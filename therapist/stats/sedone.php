<?php 
	if(!isset($_SESSION)) { session_start(); }
	include '../../includes/config.php';
	include '../../includes/ver_auth_t_2.php';
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
						<li class="dropdown active">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Statistiche <span class="caret"></span></a>
							<ul class="dropdown-menu">
							  <li><a href="../../therapist/stats/spdone.php">Pazienti</a></li>
							  <li class="active"><a href="../../therapist/stats/sedone.php">Esterni</a></li>
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
	if(!isset($_POST['statselect'])){
?>
			<div class="page-header stats">
				<h2>Genera statistiche utenti esterni</h2>
				<form class="form-inline" id="statselect" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" > 
					<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="form-group col-sm-3 col-sm-offset-3">
							<label for="interval">
								Periodo:
							</label>
							<?php
								$a='';
								$b='';
								$c='';
								$d='';
								if(isset($_POST['interval'])){
									if($_POST['interval']=='1m'){$a='selected="selected"';}
									if($_POST['interval']=='6m'){$b='selected="selected"';}
									if($_POST['interval']=='1a'){$c='selected="selected"';}
									if($_POST['interval']=='s'){$d='selected="selected"';}
								}
							?>
							<select class="form-control" name="interval" id="interval" required>
								<option value=''></option>
								<option value='1m' <?php echo($a); ?> >1 mese</option>
								<option value='6m' <?php echo($b); ?> >6 mesi</option>	
								<option value='1a' <?php echo($c); ?> >1 anno</option>
								<option value='s' <?php echo($d); ?> >sempre</option>	
							</select>
						</div>
						<div class="form-group col-sm-3">						
							<input type="submit" class="btn btn-sm btn-default btn-block" name="statselect" id="statselect" value="Genera Statistiche" >
						</div>
					</div>
					</div>
				</form>
			</div>
<?php
	}
	if(isset($_POST['statselect'])){
	include '../../includes/connessione.php';
	$interval='';
	if($_POST['interval']=='1m'){$interval=' and day between date_sub(curdate(),interval 1 month) and curdate() ';$period='nell\'ultimo mese';}
	elseif($_POST['interval']=='6m'){$interval=' and day between date_sub(curdate(),interval 6 month) and curdate() ';$period='negli ultimi 6 mesi';}
	elseif($_POST['interval']=='1a'){$interval=' and day between date_sub(curdate(),interval 1 year) and curdate() ';$period='nell\'ultimo anno';}
	else{$interval='';$period='in tutti i test eseguiti';}
?>
			<div class="page-header stats">
				<h2>Risultati degli utenti esterni <?php echo($period); ?></h2>
			</div>

			<div class="stats">
				<div class="row">
					<div class="test col-sm-6">
						<h3>Quoziene dello spettro di autismo</h3>		
<!-- Test Quoziente di autismo -->
		<?php
			$sql= 'select count(idnumber) as num, avg(total) as avg from aq_c as t, user as u where t.patient=u.id and u.patient=0 '.$interval;
			$sql2= 'select count(distinct t.patient) as pnum from aq_c as t, user as u where t.patient=u.id and u.patient=0 '.$interval;
			$result=$mysqli->query($sql);
			if($result){
				$res=$result->fetch_assoc();
				if(!is_null($res['avg'])){
					$avg=$res['avg'];
					$tnum=$res['num'];
					$result=$mysqli->query($sql2);
					$res=$result->fetch_assoc();
					$pnum=$res['pnum'];
		?>
							<table class="table table-condensed table-bordered">
								<tr>
									<th>Test</th>
									<th>Utenti</th>
									<th>Punteggio Medio</th>
								</tr>
								<tr>
									<td><?php echo($tnum); ?></td>
									<td><?php echo($pnum); ?></td>
									<td><?php echo($avg); ?></td>
								</tr>
							</table>
							<?php
									}
									else {
							?>
									<h4>Nessun test presente</h4>
							<?php
									}
								}
							?>					
					</div>
					<div class="test col-sm-6">
						<h3>Attribuzione delle intenzioni</h3>
<!-- Test Attribuzione intenzioni -->
		<?php
			$sql= 'select count(idnumber) as num, avg(total) as avg from attint_c as t, user as u where t.patient=u.id and u.patient=0 '.$interval;
			$sql2= 'select count(distinct t.patient) as pnum from attint_c as t, user as u where t.patient=u.id and u.patient=0 '.$interval;
			$result=$mysqli->query($sql);
			if($result){
				$res=$result->fetch_assoc();
				if(!is_null($res['avg'])){
					$avg=$res['avg'];
					$tnum=$res['num'];
					$result=$mysqli->query($sql2);
					$res=$result->fetch_assoc();
					$pnum=$res['pnum'];
		?>
							<table class="table table-condensed table-bordered">
							<tr>
								<th>Test</th>
								<th>Utenti</th>
								<th>Punteggio Medio</th>
							</tr>
							<tr>
								<td><?php echo($tnum); ?></td>
								<td><?php echo($pnum); ?></td>
								<td><?php echo($avg); ?></td>
							</tr>
						</table>
							<?php
									}
									else {
							?>
									<h4>Nessun test presente</h4>
							<?php
									}
								}
							?>
					</div>
				</div>
				<div class="row">
					<div class="test col-sm-6">
						<h3>Basic Empathy Scale</h3>			
<!-- Test Basic Empathy Scale -->
		<?php
			$sql= 'select count(idnumber) as num, avg(ce) as avgce, avg(ae) as avgae from bes_c as t, user as u where t.patient=u.id and u.patient=0 '.$interval;
			$sql2= 'select count(distinct t.patient) as pnum from bes_c as t, user as u where t.patient=u.id and u.patient=0 '.$interval;
			$result=$mysqli->query($sql);
			if($result){
				$res=$result->fetch_assoc();
				if(!is_null($res['avgce'])){
					$avgce=$res['avgce'];
					$avgae=$res['avgae'];
					$tnum=$res['num'];
					$result=$mysqli->query($sql2);
					$res=$result->fetch_assoc();
					$pnum=$res['pnum'];
		?>
							<table class="table table-condensed table-bordered">
							<tr>
								<th>Test</th>
								<th>Utenti</th>
								<th>CE Medio</th>
								<th>AE Medio</th>
							</tr>
							<tr>
								<td><?php echo($tnum); ?></td>
								<td><?php echo($pnum); ?></td>
								<td><?php echo($avgce); ?></td>
								<td><?php echo($avgae); ?></td>
							</tr>
						</table>
						<?php
								}
									else {
							?>
									<h4>Nessun test presente</h4>
							<?php
									}
								}
							?>
					</div>
					<div class="test col-sm-6">
						<h3>Test delle Situazioni Sociali</h3>
<!-- Test Situazioni Sociali -->
		<?php
			$sql= 'select count(idnumber) as num, avg(normb) as normb, avg(violation) as violation, avg(severity) as severity from socialsit_c as t, user as u where t.patient=u.id and u.patient=0 '.$interval;
			$sql2= 'select count(distinct t.patient) as pnum from socialsit_c as t, user as u where t.patient=u.id and u.patient=0  '.$interval;
			$result=$mysqli->query($sql);
			if($result){
				$res=$result->fetch_assoc();
				if(!is_null($res['normb'])){
					$normb=$res['normb'];
					$violation=$res['violation'];
					$severity=$res['severity'];
					$tnum=$res['num'];
					$result=$mysqli->query($sql2);
					$res=$result->fetch_assoc();
					$pnum=$res['pnum'];
		?>
							<table class="table table-condensed table-bordered">
							<tr>
								<th>Test</th>
								<th>Utenti</th>
								<th>Comp. Normale</th>
								<th>Violazione</th>
								<th>Severità</th>
							</tr>
							<tr>
								<td><?php echo($tnum); ?></td>
								<td><?php echo($pnum); ?></td>
								<td><?php echo($normb); ?></td>
								<td><?php echo($violation); ?></td>
								<td><?php echo($severity); ?></td>
							</tr>
						</table>
						<?php
								}
								else {
						?>
								<h4>Nessun test presente</h4>
						<?php
								}
							}
						?>
					</div>
				</div>
				<div class="row">
					<div class="test col-sm-6">
						<h3>Eyes Task</h3>
<!-- Test Eyes Task -->
		<?php
			$sql= 'select count(idnumber) as num, avg(total) as avg from eyestask_c as t, user as u where t.patient=u.id and u.patient=0 '.$interval;
			$sql2= 'select count(distinct t.patient) as pnum from eyestask_c as t, user as u where t.patient=u.id and u.patient=0  '.$interval;
			$result=$mysqli->query($sql);
			if($result){
				$res=$result->fetch_assoc();
				if(!is_null($res['avg'])){
					$avg=$res['avg'];
					$tnum=$res['num'];
					$result=$mysqli->query($sql2);
					$res=$result->fetch_assoc();
					$pnum=$res['pnum'];
		?>
							<table class="table table-condensed table-bordered">
							<tr>
								<th>Test</th>
								<th>Utenti</th>
								<th>Punteggio Medio</th>
							</tr>
							<tr>
								<td><?php echo($tnum); ?></td>
								<td><?php echo($pnum); ?></td>
								<td><?php echo($avg); ?></td>
							</tr>
						</table>
						<?php
								}
								else {
						?>
								<h4>Nessun test presente</h4>
						<?php
								}
							}
						?>					
					</div>
					<div class="test col-sm-6">					
						<h3>Test di Quoziene di Empatia</h3>
<!-- Test Quoziente Empatia -->
		<?php
			$sql= 'select count(idnumber) as num, avg(ce) as ce, avg(ss) as ss, avg(ee) as ee, avg(total) as total from qe_c as t, user as u where t.patient=u.id and u.patient=0  '.$interval;
			$sql2= 'select count(distinct t.patient) as pnum from qe_c as t, user as u where t.patient=u.id and u.patient=0 '.$interval;
			$result=$mysqli->query($sql);
			if($result){
				$res=$result->fetch_assoc();
				if(!is_null($res['ce'])){
					$ce=$res['ce'];
					$ss=$res['ss'];
					$ee=$res['ee'];
					$total=$res['total'];
					$tnum=$res['num'];
					$result=$mysqli->query($sql2);
					$res=$result->fetch_assoc();
					$pnum=$res['pnum'];
		?>
							<table class="table table-condensed table-bordered">
							<tr>
								<th>Test</th>
								<th>Utenti</th>
								<th>CE Medio</th>
								<th>SS Medio</th>
								<th>EE Medio</th>
								<th>Media Totale</th>
							</tr>
							<tr>
								<td><?php echo($tnum); ?></td>
								<td><?php echo($pnum); ?></td>
								<td><?php echo($ce); ?></td>
								<td><?php echo($ss); ?></td>
								<td><?php echo($ee); ?></td>
								<td><?php echo($total); ?></td>
							</tr>
						</table>
						<?php
								}
								else {
						?>
								<h4>Nessun test presente</h4>
						<?php
								}
							}
						?>
					</div>
				</div>
<?php /* 	//commento che toglie i test che hanno bisogno di correzioni ?>

				<div class="row">
					<div class="test col-sm-6">
						<h3>Attribuzione delle emozioni</h3>
<!-- Test Attribuzione delle Emozioni -->
		<?php
			$sql= 'select count(idnumber) as num, avg(e1) as e1, avg(e2) as e2, avg(e3) as e3, avg(e4) as e4, avg(e5) as e5, avg(e6) as e6, avg(e7) as e7 from emotatt_c as t, user as u where t.patient=u.id and u.patient=0 and t.completed=1  '.$interval;
			$sql2= 'select count(distinct t.patient) as pnum from emotatt_c as t, user as u where t.patient=u.id and u.patient=0 and t.completed=1 '.$interval;
			$result=$mysqli->query($sql);
			if($result){
				$res=$result->fetch_assoc();
				if(!is_null($res['e1'])){
					$e1=$res['e1'];
					$e2=$res['e2'];
					$e3=$res['e3'];
					$e4=$res['e4'];
					$e5=$res['e5'];
					$e6=$res['e6'];
					$e7=$res['e7'];
					$tnum=$res['num'];
					$result=$mysqli->query($sql2);
					$res=$result->fetch_assoc();
					$pnum=$res['pnum'];
		?>
							<table class="table table-condensed table-bordered">
							<tr>
								<th>Test</th>
								<th>Utenti</th>
								<th>Tristezza</th>
								<th>Paura</th>
								<th>Imbarazzo</th>
								<th>Felicità</th>
								<th>Disgusto</th>
								<th>Rabbia</th>
								<th>Invidia</th>
							</tr>
							<tr>
								<td><?php echo($tnum); ?></td>
								<td><?php echo($pnum); ?></td>
								<td><?php echo($e1); ?></td>
								<td><?php echo($e2); ?></td>
								<td><?php echo($e3); ?></td>
								<td><?php echo($e4); ?></td>
								<td><?php echo($e5); ?></td>
								<td><?php echo($e6); ?></td>
								<td><?php echo($e7); ?></td>
							</tr>
						</table>
						<?php
								}
									else {
							?>
									<h4>Nessun test presente</h4>
							<?php
									}
								}
							?>
					</div>
					<div class="test col-sm-6">
						<h3>Test Teoria della Mente</h3>
<!-- Test Teoria della mente -->
		<?php
			$sql= 'select count(idnumber) as num, avg(total) as avg from tom_c as t, user as u where t.patient=u.id and u.patient=0 and t.completed=1 '.$interval;
			$sql2= 'select count(distinct t.patient) as pnum from tom_c as t, user as u where t.patient=u.id and u.patient=0 and t.completed=1  '.$interval;
			$result=$mysqli->query($sql);
			if($result){
				$res=$result->fetch_assoc();
				if(!is_null($res['avg'])){
					$avg=$res['avg'];
					$tnum=$res['num'];
					$result=$mysqli->query($sql2);
					$res=$result->fetch_assoc();
					$pnum=$res['pnum'];
		?>
							<table class="table table-condensed table-bordered">
							<tr>
								<th>Test</th>
								<th>Utenti</th>
								<th>Punteggio Medio</th>
							</tr>
							<tr>
								<td><?php echo($tnum); ?></td>
								<td><?php echo($pnum); ?></td>
								<td><?php echo($avg); ?></td>
							</tr>
						</table>
						<?php
								}
								else {
						?>
								<h4>Nessun test presente</h4>
						<?php
								}
							}
						?>
					</div>
				</div>
//*/
?>
				<div class="row regenbutton">
					<div class="col-sm-4 col-sm-offset-4">						
						<input type="button" class="btn btn-lg btn-default btn-block" name="newstats" id="newstats" value="Genera nuove statistiche" onclick="window.location.href = '<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' ">
					</div>
				</div>
			</div>
		<?php
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