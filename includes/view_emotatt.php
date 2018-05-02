<?php
	if(!isset($_SESSION)) { session_start(); }	
	include '/ver_auth_t.php';	
	
	if(!isset($_POST['emotatt'])){
	
	}
	
	$_SESSION['therapist']['emotatt']=array();
	unset($_SESSION['therapist']['emotatt']);
	
	include '/connessione.php';
	
	$qtype=1;
	
	$sql = 'select q.question as question, q.qnum as qnum, a.answer as answer, ca.answer as ca from emotatt_qc as q, emotatt_a as a, emotatt_ca as ca where q.qnum=a.qnum and q.qnum=ca.qnum and q.qtype='.$qtype.' and a.coidnum='.$_POST['emotatt'];
	
	$emot = array(1 => 'Tristezza', 2 => 'Paura', 3 => 'Imbarazzo', 4 => 'Felicita\'', 5 => 'Disgusto', 6 => 'Rabbia', 7 => 'Invidia');
	
	$select = $mysqli->query($sql);
	if(mysqli_num_rows($select)>0) {
		while($res = $select->fetch_assoc()){
			$_SESSION['therapist']['emotatt'][$res['qnum']]['question']=$res['question'];
			$_SESSION['therapist']['emotatt'][$res['qnum']]['answer']=$res['answer'];
			$_SESSION['therapist']['emotatt'][$res['qnum']]['ca']=$emot[$res['ca']];
		}
	}
	$sql = 'select DISTINCT name, surname from therapist, emotatt_a where emotatt_a.docid=therapist.id and emotatt_a.coidnum='.$_POST['emotatt'];
	
	$select = $mysqli->query($sql);
	if(mysqli_num_rows($select)>0) {
		$i=1;
		while($res = $select->fetch_assoc()){
			$_SESSION['therapist']['emotatt']['doc'][$i]=$res['surname'].', '.$res['name'];
			$i=$i+1;
		}
	}	
	

	$select->free();
	$mysqli->close();