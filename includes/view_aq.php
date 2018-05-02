<?php
	if(!isset($_SESSION)) { session_start(); }	
	include '/ver_auth_t.php';	
	
	if(!isset($_POST['aq'])){
	
	}
	
	$_SESSION['therapist']['aq']=array();
	unset($_SESSION['therapist']['aq']);
	
	include '/connessione.php';
	
	
	$sql = 'Select q.qnum as qnum, q.question as q, a.answer as a from aq_qc as q, aq_a as a where q.qnum=a.qnum and a.coidnum='.$_POST['aq'];
	
	$select = $mysqli->query($sql);
	if(mysqli_num_rows($select)>0) {
		while($res = $select->fetch_assoc()){
			$_SESSION['therapist']['aq'][$res['qnum']]['q']=$res['q'];
			$_SESSION['therapist']['aq'][$res['qnum']]['a']=$res['a'];
		}
	}

	$select->free();
	$mysqli->close();