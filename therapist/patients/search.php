<?php 
	if(!isset($_SESSION)) { session_start(); }
	include '../../includes/config.php';
	include '../../includes/ver_auth_t_2.php';
	include '../../includes/groups_init.php';

	$error = true;
	$surname_error = '';
	$id_error = '';
	$schol_error = '';
	$_SESSION['therapist']['patient']=array();
	unset($_SESSION['therapist']['patient']);
	
	if(isset($_POST['surname'])||isset($_POST['id'])||isset($_POST['group'])||isset($_POST['sex'])||isset($_POST['schol'])) {
		$error = false;
		if($_POST['surname']!=''){
			$surname = trim($_POST['surname']);
			$surname = strip_tags($surname);
			$surname = htmlspecialchars($surname);
			if (!preg_match("/^[a-zA-Z\sàáâãäåèéêëìíîïñòóôõöøùúûüçÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙØÚÛÜ]+$/",$surname)) {
				$error = true;
				$surname_error = "   Il cognome contiene caratteri non accettati.";
			}
			
		}
		if($_POST['id']!=''){
			$id = $_POST['id'];
			if (!preg_match("/^[0-9]+$/",$id)) {
				$error = true;
				$id_error = "   Indicare un numero o lasciare il campo vuoto";
			}
		}			
		
		if($_POST['schol']!=''){
			$schol = $_POST['schol'];
			if (!preg_match("/^[0-9]+$/",$schol)) {
				$error = true;
				$schol_error = "   Indicare un numero o lasciare il campo vuoto";
			}
		}
		
		if($_POST['group']!=''){
			$group = $_POST['group'];
		}		
		if($_POST['sex']!=''){
			$sex = $_POST['sex'];
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
							  <li class="active"><a href="../../therapist/patients/search.php">Cerca</a></li>
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
			if($error) {
			?>
			<div class="page-header psearch">
				<h2>Ricerca Pazienti</h2>
			</div>
			<form id="search" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
				<div class="psearch row">
					<div class="form-group col-sm-4 col-sm-offset-4 row">
						<label class="sr-only" for="surname">Cognome</label>
						<input type="text" class="form-control" placeholder="Cognome" name="surname" id="surname">
					</div>
					<div class="form-group col-sm-4 warning">
						<?php echo($surname_error); ?>
					</div>
					<div class="form-group col-sm-4 col-sm-offset-4 row">
						<label class="sr-only" for="id">ID</label>
						<input type="text" class="form-control" placeholder="ID" name="id" id="id">
					</div>
					<div class="form-group col-sm-4 warning">
						<?php echo($id_error); ?>
					</div>
					<div class="form-group col-sm-4 col-sm-offset-4 row">
						<div class="col-sm-8">
						<select class="form-control" name="group" id="group">
							<option value ='' selected="selected">Gruppo Clinico</option>
						<?php
							foreach($_SESSION['group'] as $gid) {
								echo('<option value="'.$gid['id'].'">'.$gid['name'].'</option>'."\r\n");
							}
						?>
						</select>					
						</div>
						<div class="col-sm-4">
						<select class="form-control" name="sex" id="sex">
							<option value ='' selected="selected">Sesso</option>
							<option value="F">F</option>
							<option value="M">M</option>
						</select>
						</div>
					</div>
					<div class="form-group" style="visibility:hidden;">
						<label for="schol">Scolarità</label>
						<textarea style="WIDTH: 50x; HEIGHT: 20px; resize: none;" rows="1" cols="5" name="schol" id="schol"></textarea><?php echo($schol_error); ?>
					</div>
				</div>
				<div class="psearch buttons row">
					<div class="col-sm-2 col-sm-offset-4">
						<input class="btn btn-sm btn-default btn-block" type="submit" id="submit" value="Cerca">
					</div>
					<div class="col-sm-2">
						<input class="btn btn-sm btn-default btn-block" type="button" value="Torna alla Homepage" onclick="window.location.href = '../../index.php' ">
					</div>
				</div>
			</form>
			<?php
			}
			else {
				include '../../includes/connessione.php';
				
				$sql = 'Select a.id as id, a.username as username, b.name as name, b.surname as surname from user as a, patient as b where ';
				if ($_POST['surname'] != '') { $where[1] = "b.surname like '%".$_POST['surname']."%'"; }
				if ($_POST['id'] != '') { $where[2] = 'a.id = '.$_POST['id']; }
				if ($_POST['schol'] != '') { $where[3] = 'a.scholarity = '.$_POST['schol']; }
				if ($_POST['group'] != '') { $where[4] = 'b.cg = '.$_POST['group']; }
				if ($_POST['sex'] != '') { $where[5] = "a.sex = '".$_POST['sex']."'"; }
				if($_POST['surname']!=''||$_POST['id']!=''||$_POST['group']!=''||$_POST['sex']!=''||$_POST['schol']!='') {
					$sql2 = '';
					foreach($where as $w) {
						if ($w != '') {
							$sql2 = $sql2.$w.' and ';
						}
					}
					$sql2=$sql2."a.id=b.id and a.patient = 1";
				}
				else {
					$sql2 = 'a.id=b.id and a.patient = 1';
				}
				$sql = $sql.$sql2;
				$select = $mysqli->query($sql);
				if(mysqli_num_rows($select)>0) {
			?>	
			<div class="page-header psearch">
				<h2>Risultati ricerca</h2>
				<div class="row">
					<div class="col-sm-4 col-sm-offset-4">
						<input class="btn btn-sm btn-default btn-block" type="button" value="Effettua una nuova ricerca" onclick="window.location.href = 'search.php' ">
					</div>
				</div>
			</div>
					<form class="plist" id="p_list"  action="pview.php" method="post">
					<div class="col-sm-6 col-sm-offset-3">
						<table class="table" id="patients">
								<tr>
									<th></th>
									<th>ID</th>
									<th>NOME</th>
									<th>COGNOME</th>
									<th>USERNAME</th>
								</tr>
				<?php	
					foreach ($select as $res){
				?>
								<tr>
									<td><input class="form-group" type="radio" value="<?php echo($res['id']); ?>" name="id" id="id" required></td>
									<td><?php echo($res['id']); ?></td>
									<td><?php echo($res['name']); ?></td>
									<td><?php echo($res['surname']); ?></td>
									<td><?php echo($res['username']); ?></td>
								</tr>
				<?php
					}
				?>
							</table>
					</div>
					<div class="col-sm-4 col-sm-offset-4">
							<input class="btn btn-sm btn-default btn-block" type="submit" id="submit" value="Visualizza Dettagli">
					</div>
					</form>
		<?php
				}
				else {
		?>
			<div class="page-header psearch">
				<h2>La ricerca non ha prodotto alcun risultato</h2>
				<div class="row">
					<div class="col-sm-4 col-sm-offset-4">
						<input class="btn btn-sm btn-default btn-block" type="button" value="Effettua una nuova ricerca" onclick="window.location.href = 'search.php' ">
					</div>
				</div>
			</div>
				
		<?php
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