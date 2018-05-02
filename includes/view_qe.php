<?php
	if(!isset($_SESSION)) { session_start(); }	
	include '/ver_auth_t.php';	
	
	if(!isset($_POST['qe'])){
	
	}
	
	$_SESSION['therapist']['qe']=array();
	unset($_SESSION['therapist']['qe']);
	
	include '/connessione.php';
	
	
	$sql = 'Select q.qnum as qnum, q.question as q, a.answer as a from qe_qc as q, qe_a as a where q.qnum=a.qnum and a.coidnum='.$_POST['qe'];
	
	$select = $mysqli->query($sql);
	if(mysqli_num_rows($select)>0) {
		while($res = $select->fetch_assoc()){
			$_SESSION['therapist']['qe'][$res['qnum']]['q']=$res['q'];
			$_SESSION['therapist']['qe'][$res['qnum']]['a']=$res['a'];
		}
	}

	$select->free();
	$mysqli->close();