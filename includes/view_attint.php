<?php
	if(!isset($_SESSION)) { session_start(); }	
	include '/ver_auth_t.php';	
	
	if(!isset($_POST['attint'])){
	
	}
	
	$_SESSION['therapist']['attint']=array();
	unset($_SESSION['therapist']['attint']);
	
	include '/connessione.php';
	
	$atype = 2;
	
	$sql = 'call attint_view('.$_POST['attint'].','.$atype.')';
	
	$select = $mysqli->query($sql);
	if(mysqli_num_rows($select)>0) {
		while($res = $select->fetch_assoc()){
			$_SESSION['therapist']['attint']['series']=$res['series'];		
			$_SESSION['therapist']['attint'][$res['qnum']]['A']=$res['A'];
			$_SESSION['therapist']['attint'][$res['qnum']]['B']=$res['B'];
			$_SESSION['therapist']['attint'][$res['qnum']]['C']=$res['C'];
			$_SESSION['therapist']['attint'][$res['qnum']]['answer']=$res['loc'];
		}
	}

	$select->free();
	$mysqli->close();