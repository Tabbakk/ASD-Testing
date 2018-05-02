<?php
	if(!isset($_SESSION)) { session_start(); }	
	include '/ver_auth_t.php';	
	
	if(!isset($_POST['bes'])){
	
	}
	
	$_SESSION['therapist']['bes']=array();
	unset($_SESSION['therapist']['bes']);
	
	include '/connessione.php';
	
	
	$sql = 'Select q.qnum as qnum, q.question as q, a.answer as a from bes_qc as q, bes_a as a where q.qnum=a.qnum and a.coidnum='.$_POST['bes'];
	
	$select = $mysqli->query($sql);
	if(mysqli_num_rows($select)>0) {
		while($res = $select->fetch_assoc()){
			$_SESSION['therapist']['bes'][$res['qnum']]['q']=$res['q'];
			$_SESSION['therapist']['bes'][$res['qnum']]['a']=$res['a'];
		}
	}

	$select->free();
	$mysqli->close();