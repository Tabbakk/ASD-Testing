<?php 
	if(!isset($_SESSION)) { session_start(); }
	include '/config.php';
	include '/ver_auth_t.php';
	include '/connessione.php';
	
	$_SESSION['therapist']['tom']=array();
	unset($_SESSION['therapist']['tom']);
	
	if(!isset($_POST['id'])){
		header("Location: therapist/tests/tlistuncorrected.php");
	}
	
	$_SESSION['therapist']['tom']['testid'] = $_POST['id'];
	
	$sql='select t.qnum as qnum, t.intro as intro, t.q1 as q1, t.q2 as q2, ta.a1 as a1, ta.a2 as a2 from tom_qc as t, tom_a as ta where t.qtype=1 and t.qnum=ta.qnum and ta.verified=0 and ta.coidnum='.$_SESSION['therapist']['tom']['testid'];
	
	$result = $mysqli->query($sql);
	
	if(mysqli_num_rows($result) > 0){
		while($test = mysqli_fetch_assoc($result)){
			$_SESSION['therapist']['tom'][$test['qnum']]['intro'] = $test['intro'];
			$_SESSION['therapist']['tom'][$test['qnum']]['q1'] = $test['q1'];
			$_SESSION['therapist']['tom'][$test['qnum']]['q2'] = $test['q2'];
			$_SESSION['therapist']['tom'][$test['qnum']]['a1'] = $test['a1'];
			$_SESSION['therapist']['tom'][$test['qnum']]['a2'] = $test['a2'];
		}

		$result->free();	
	}
	
	$mysqli->close();
