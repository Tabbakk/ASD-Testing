<?php
	if(!isset($_SESSION)) { session_start(); }	
	include '/ver_auth_t.php';	
	
	if(!isset($_POST['tom'])){
	
	}
	
	$_SESSION['therapist']['tom']=array();
	unset($_SESSION['therapist']['tom']);
	
	include '/connessione.php';
	
	$qtype=1;
	
	$sql = 'select q.qnum as qnum, q.intro as intro, q.q1 as q1, q.q2 as q2, a.a1 as a1, a.a2 as a2 from tom_a as a, tom_qc as q where q.qnum = a.qnum and q.qtype='.$qtype.' and a.coidnum='.$_POST['tom'];
	
	$select = $mysqli->query($sql);
	if(mysqli_num_rows($select)>0) {
		while($res = $select->fetch_assoc()){
			$_SESSION['therapist']['tom'][$res['qnum']]['intro']=$res['intro'];
			$_SESSION['therapist']['tom'][$res['qnum']]['q1']=$res['q1'];
			$_SESSION['therapist']['tom'][$res['qnum']]['q2']=$res['q2'];
			$_SESSION['therapist']['tom'][$res['qnum']]['a1']=$res['a1'];
			$_SESSION['therapist']['tom'][$res['qnum']]['a2']=$res['a2'];
		}
	}
	$sql = 'select DISTINCT name, surname from therapist, tom_a where tom_a.docid=therapist.id and tom_a.coidnum='.$_POST['tom'];
	
	$select = $mysqli->query($sql);
	if(mysqli_num_rows($select)>0) {
		while($res = $select->fetch_assoc()){
			$_SESSION['therapist']['tom']['doc']=$res['surname'].', '.$res['name'];
		}
	}	
	

	$select->free();
	$mysqli->close();