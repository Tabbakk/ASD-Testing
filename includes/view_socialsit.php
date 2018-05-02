<?php
	if(!isset($_SESSION)) { session_start(); }	
	include '/ver_auth_t.php';	
	
	if(!isset($_POST['socialsit'])){
	
	}
	
	$_SESSION['therapist']['socialsit']=array();
	unset($_SESSION['therapist']['socialsit']);
	
	include '/connessione.php';
	
	$qtype=1;
	
	$sql = 'select a.qnum as qnum, a.subqnum as subqnum, a.answer as answer, q.A as A, q.B as B, q.C as C, q.D as D, q.type as type, qc.intro as intro, qc.question as question from socialsit_a as a, socialsit_ca as q, socialsit_qc as qc where a.qnum=q.qnum and a.qnum=qc.qnum and a.subqnum=q.subqnum and a.subqnum=qc.subqnum and qc.qtype='.$qtype.' and a.coidnum='.$_POST['socialsit'];
	
	$select = $mysqli->query($sql);
	if(mysqli_num_rows($select)>0) {
		while($res = $select->fetch_assoc()){
			$_SESSION['therapist']['socialsit'][$res['qnum']][$res['subqnum']]['A']=$res['A'];
			$_SESSION['therapist']['socialsit'][$res['qnum']][$res['subqnum']]['B']=$res['B'];
			$_SESSION['therapist']['socialsit'][$res['qnum']][$res['subqnum']]['C']=$res['C'];
			$_SESSION['therapist']['socialsit'][$res['qnum']][$res['subqnum']]['D']=$res['D'];
			$_SESSION['therapist']['socialsit'][$res['qnum']][$res['subqnum']]['answer']=$res['answer'];
			$_SESSION['therapist']['socialsit'][$res['qnum']][$res['subqnum']]['type']=$res['type'];
			$_SESSION['therapist']['socialsit'][$res['qnum']][$res['subqnum']]['intro']=$res['intro'];
			$_SESSION['therapist']['socialsit'][$res['qnum']][$res['subqnum']]['question']=$res['question'];
		}
	}

	$select->free();
	$mysqli->close();