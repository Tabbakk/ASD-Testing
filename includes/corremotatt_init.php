<?php 
	if(!isset($_SESSION)) { session_start(); }
	include '/config.php';
	include '/ver_auth_t.php';
	include '/connessione.php';
	
	$_SESSION['therapist']['emotatt']=array();
	unset($_SESSION['therapist']['emotatt']);
	
	if(!isset($_POST['id'])){
		header("Location: therapist/tests/tlistuncorrected.php");
	}
	
	$_SESSION['therapist']['emotatt']['testid'] = $_POST['id'];
	
	$sql='select a.qnum as qnum, a.answer as answer, q.question as question, ca.answer as ca from emotatt_a as a, emotatt_qc as q, emotatt_ca as ca where a.qnum=q.qnum and a.qnum=ca.qnum and q.qtype=1 and a.verified=0 and a.coidnum='.$_SESSION['therapist']['emotatt']['testid'];
	
	$result = $mysqli->query($sql);
	
	if(mysqli_num_rows($result) > 0){
		while($test = mysqli_fetch_assoc($result)){
			$_SESSION['therapist']['emotatt'][$test['qnum']]['question'] = $test['question'];
			$_SESSION['therapist']['emotatt'][$test['qnum']]['answer'] = $test['answer'];
			$_SESSION['therapist']['emotatt'][$test['qnum']]['ca'] = $test['ca'];
			$_SESSION['therapist']['emotatt'][$test['qnum']]['qnum'] = $test['qnum'];
		}

		$result->free();	
	}
	
	$mysqli->close();
