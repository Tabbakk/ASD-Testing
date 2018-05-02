<?php
	if(!isset($_SESSION)) { session_start(); }	
	include '/ver_auth_t.php';	
	
	if(!isset($_POST['eyestask'])){
	
	}
	
	$_SESSION['therapist']['eyestask']=array();
	unset($_SESSION['therapist']['eyestask']);
	
	include '/connessione.php';
	
	$atype=1;
	$qtype=2;
	
	$sql = 'SELECT q.Qnum as qnum, q.eyes as img, ac.A as A, ac.B as B, ac.C as C, ac.D as D, a.answer as answer from eyestask_qc as q, eyestask_ac as ac, eyestask_a as a where q.qnum=a.qnum and ac.qnum=q.qnum and a.coidnum='.$_POST['eyestask'].' and ac.atype='.$atype.' and q.qtype='.$qtype;
	
	$select = $mysqli->query($sql);
	if(mysqli_num_rows($select)>0) {
		while($res = $select->fetch_assoc()){
			$_SESSION['therapist']['eyestask'][$res['qnum']]['A']=$res['A'];
			$_SESSION['therapist']['eyestask'][$res['qnum']]['B']=$res['B'];
			$_SESSION['therapist']['eyestask'][$res['qnum']]['C']=$res['C'];
			$_SESSION['therapist']['eyestask'][$res['qnum']]['D']=$res['D'];
			$_SESSION['therapist']['eyestask'][$res['qnum']]['img']=$res['img'];
			$_SESSION['therapist']['eyestask'][$res['qnum']]['answer']=$res['answer'];
		}
	}

	$select->free();
	$mysqli->close();