<?php 
	if(!isset($_SESSION)) { session_start(); }
	include '../../includes/config.php';
	include '../../includes/ver_auth_t_2.php';

if(!isset($_POST['statgenerate'])){	
	$_SESSION['group'] = array();
	unset($_SESSION['group']);
	include '../../includes/groups_init.php';
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
							  <li><a href="../../therapist/stats/sedone.php">Esterni</a></li>
							  <li class="active"><a href="../../therapist/stats/spgenerate.php">Genera file Excel</a></li>
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


			<div class="page-header stats">
				<h2>Genera foglio Excel statistiche</h2>
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
							<input type="submit" class="btn btn-sm btn-default btn-block" name="statgenerate" id="statgenerate" value="Genera Statistiche" >
						</div>
					</div>
					</div>
				</form>
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
<?php
	}
else {
	if(isset($_POST['statgenerate'])){

	$interval='';
	if($_POST['interval']=='1m'){$interval=' and day between date_sub(curdate(),interval 1 month) and curdate() ';$period='_1m';}
	elseif($_POST['interval']=='6m'){$interval=' and day between date_sub(curdate(),interval 6 month) and curdate() ';$period='_6m';}
	elseif($_POST['interval']=='1a'){$interval=' and day between date_sub(curdate(),interval 1 year) and curdate() ';$period='_1a';}
	else{$interval='';$period='_all';}

	header("Content-Type: application/vnd.ms-excel; charset=utf-8");
	$fname="Stats".$period."_".$date.".xls";
	header("Content-disposition: attachment; filename=".$fname);

	echo "GRUPPO CLINICO;NOME;COGNOME;ETÀ;SESSO;SCOLARITÀ;QA TOTALE;QA DATA;INTEZIONI SERIE;INTENZIONI TOTALE;INTENZIONI DATA;BES CE;BES AE;BES DATA;EMOZIONI TRISTEZZA;EMOZIONI PAURA;EMOZIONI IMBARAZZO;EMOZIONI FELICITÀ;EMOZIONI DISGUSTO;EMOZIONI RABBIA;EMOZIONI INVIDIA;EMOZIONI DATA;EYES TOTAL;EYES DATA;QE CE;QE SS;QE EE;QE TOTALE;QE DATA;SOCIAL NORMATIVO;SOCIAL VIOLAZIONE;SOCIAL GRAVITÀ;SOCIAL DATA;TOM TOTALE;TOM DATA"."\r\n";

	
	include '../../includes/connessione.php';

	$sql="select p.id as id, p.name as name, p.surname as surname, g.name as grp, TIMESTAMPDIFF(year,u.birthdate,CURDATE()) as age, u.sex as sex, u.scholarity as schol from user as u, patient as p, clinicalg as g where p.id=u.id and p.cg=g.code";

	$select = $mysqli->query($sql);
	$p = array();
	$i=1;
	foreach ($select as $result){
		$p[$i]=$result;
		$i=$i+1;
	}
	
	$patients=count($p);
	

	$j=1;
	while($j-1 < $patients){

		$tom = array();
		$socialsit = array();
		$qe = array();
		$eyestask = array();
		$emotatt = array();
		$bes = array();
		$attint = array();
		$aq = array();

		$sql="select total, day from aq_c where patient=".$p[$j]['id'].$interval;
		$select = $mysqli->query($sql);
		$i=1;
		foreach ($select as $result){
			$aq[$i]=$result;
			$i=$i+1;
		}	

		$sql="select series, total , day from attint_c where patient=".$p[$j]['id'].$interval;
		$select = $mysqli->query($sql);
		$i=1;
		foreach ($select as $result){
			$attint[$i]=$result;
			$i=$i+1;
		}	
		
		$sql="select ce, ae, day from bes_c where patient=".$p[$j]['id'].$interval;
		$select = $mysqli->query($sql);
		$i=1;
		foreach ($select as $result){
			$bes[$i]=$result;
			$i=$i+1;
		}	
		
		$sql="select e1,e2,e3,e4,e5,e6,e7,day from emotatt_c where completed=1 and patient=".$p[$j]['id'].$interval;
		$select = $mysqli->query($sql);
		$i=1;
		foreach ($select as $result){
			$emotatt[$i]=$result;
			$i=$i+1;
		}	
		
		$sql="select total, day from eyestask_c where patient=".$p[$j]['id'].$interval;
		$select = $mysqli->query($sql);
		$i=1;
		foreach ($select as $result){
			$eyestask[$i]=$result;
			$i=$i+1;
		}	
		
		$sql="select ce,ss,ee,total,day from qe_c where patient=".$p[$j]['id'].$interval;
		$select = $mysqli->query($sql);
		$i=1;
		foreach ($select as $result){
			$qe[$i]=$result;
			$i=$i+1;
		}	

		$sql="select normb, violation, severity, day from socialsit_c where patient=".$p[$j]['id'].$interval;;
		$select = $mysqli->query($sql);
		$i=1;
		foreach ($select as $result){
			$socialsit[$i]=$result;
			$i=$i+1;
		}	
		
		$sql="select total, day from tom_c where completed=1 and patient=".$p[$j]['id'].$interval;
		$select = $mysqli->query($sql);
		$i=1;
		foreach ($select as $result){
			$tom[$i]=$result;
			$i=$i+1;
		}	

		$nmax = max(count($aq), count($attint), count($bes), count($emotatt),count($eyestask), count($qe), count($socialsit), count($tom));


		$i=1;
		
		while($i-1 < $nmax){
			$line="";
			$line=$p[$j]['grp'].";".$p[$j]['name'].";".$p[$j]['surname'].";".$p[$j]['age'].";".$p[$j]['sex'].";".$p[$j]['schol'].";";

			if(isset($aq[$i])){
				$line=$line.$aq[$i]['total'].";".$aq[$i]['day'].";";
			}
			else {
				$line=$line.";;";
			}

			if(isset($attint[$i])){
				$line=$line.$attint[$i]['series'].";".$attint[$i]['total'].";".$attint[$i]['day'].";";
			}
			else {
				$line=$line.";;;";
			}

			if(isset($bes[$i])){
				$line=$line.$bes[$i]['ce'].";".$bes[$i]['ae'].";".$bes[$i]['day'].";";
			}
			else {
				$line=$line.";;;";
			}

			if(isset($emotatt[$i])){
				$line=$line.$emotatt[$i]['e1'].";".$emotatt[$i]['e2'].";".$emotatt[$i]['e3'].";".$emotatt[$i]['e4'].";".$emotatt[$i]['e5'].";".$emotatt[$i]['e6'].";".$emotatt[$i]['e7'].";".$emotatt[$i]['day'].";";
			}
			else {
				$line=$line.";;;;;;;;";
			}

			if(isset($eyestask[$i])){
				$line=$line.$eyestask[$i]['total'].";".$eyestask[$i]['day'].";";
			}
			else {
				$line=$line.";;";
			}

			if(isset($qe[$i])){
				$line=$line.$qe[$i]['ce'].";".$qe[$i]['ss'].";".$qe[$i]['ee'].";".$qe[$i]['total'].";".$qe[$i]['day'].";";
			}
			else {
				$line=$line.";;;;;";
			}

			if(isset($socialsit[$i])){
				$line=$line.$socialsit[$i]['normb'].";".$socialsit[$i]['violation'].";".$socialsit[$i]['severity'].";".$socialsit[$i]['day'].";";
			}
			else {
				$line=$line.";;;;";
			}

			if(isset($tom[$i])){
				$line=$line.$tom[$i]['total'].";".$tom[$i]['day'].";";
			}
			else {
				$line=$line.";;";
			}

			$line=$line."\r\n";
			echo($line);

			$i=$i+1;
		}

		$j=$j+1;
	
	}
	
/*
//initialize spreadsheet

// Error reporting
error_reporting(E_ALL);

// Include path
ini_set('include_path', ini_get('include_path').';../Classes/');  	//da fixare questo path

// PHPExcel
include 'PHPExcel.php';												//da fixare include

// PHPExcel_Writer_Excel2007
include 'PHPExcel/Writer/Excel2007.php';							//da fixare include

// Create new PHPExcel object
echo date('H:i:s') . " Create new PHPExcel object\n";
$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("DISCAB TESTING");
$objPHPExcel->getProperties()->setLastModifiedBy($_SESSION['therapist']['name'].' '.$_SESSION['therapist']['surname']);
$objPHPExcel->getProperties()->setTitle($date." - Statitiche".$period);
$objPHPExcel->getProperties()->setSubject($date.$date." - Statitiche".$period);
$objPHPExcel->getProperties()->setDescription("Statitiche".$period);


// Initialize sheet
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'GRUPPO CLINICO');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'NOME');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'COGNOME');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'ETÀ');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'SESSO');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'SCOLARITÀ');

$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'QA TOTALE');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'QA DATA');

$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'INTEZIONI SERIE');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'INTENZIONI TOTALE');
$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'INTENZIONI DATA');

$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'BES CE');
$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'BES AE');
$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'BES DATA');

$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'EMOZIONI TRISTEZZA');
$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'EMOZIONI PAURA');
$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'EMOZIONI IMBARAZZO');
$objPHPExcel->getActiveSheet()->SetCellValue('R1', 'EMOZIONI FELICITÀ');
$objPHPExcel->getActiveSheet()->SetCellValue('S1', 'EMOZIONI DISGUSTO');
$objPHPExcel->getActiveSheet()->SetCellValue('T1', 'EMOZIONI RABBIA');
$objPHPExcel->getActiveSheet()->SetCellValue('U1', 'EMOZIONI INVIDIA');
$objPHPExcel->getActiveSheet()->SetCellValue('V1', 'EMOZIONI DATA');

$objPHPExcel->getActiveSheet()->SetCellValue('W1', 'EYES TOTAL');
$objPHPExcel->getActiveSheet()->SetCellValue('X1', 'DATA');

$objPHPExcel->getActiveSheet()->SetCellValue('Y1', 'QE CE');
$objPHPExcel->getActiveSheet()->SetCellValue('Z1', 'QE SS');
$objPHPExcel->getActiveSheet()->SetCellValue('AA1', 'QE EE');
$objPHPExcel->getActiveSheet()->SetCellValue('AB1', 'QE TOTALE');
$objPHPExcel->getActiveSheet()->SetCellValue('AC1', 'QE DATA');

$objPHPExcel->getActiveSheet()->SetCellValue('AD1', 'SOCIAL NORMATIVO');
$objPHPExcel->getActiveSheet()->SetCellValue('AE1', 'SOCIAL VIOLAZIONE');
$objPHPExcel->getActiveSheet()->SetCellValue('AF1', 'SOCIAL GRAVITÀ');
$objPHPExcel->getActiveSheet()->SetCellValue('AG1', 'SOCIAL DATA');

$objPHPExcel->getActiveSheet()->SetCellValue('AH1', 'TOM TOTALE');
$objPHPExcel->getActiveSheet()->SetCellValue('AI1', 'TOM DATA');
*/
	
	}

}
	
?>