<?php
	if(!isset($_SESSION)) { session_start(); }			
	include '/ver_auth_t.php';
	
	$sql = 'Select * from therapist where id ='.$_POST['id'];
	
	$select = $mysqli->query($sql);
	if(mysqli_num_rows($select)==1) {
		$res = $select->fetch_assoc();
		$_SESSION['therapist']['doc']['id']=$res['id'];
		$_SESSION['therapist']['doc']['username']=$res['username'];
		$_SESSION['therapist']['doc']['name']=$res['name'];
		$_SESSION['therapist']['doc']['surname']=$res['surname'];
		$_SESSION['therapist']['doc']['admin']=$res['admin'];
	}
	
	$total=0;
	$sql= 'select count(distinct coidnum) as c from emotatt_a where docid ='.$_POST['id'];
	$select = $mysqli->query($sql);
	if(mysqli_num_rows($select)==1) {
		$total=$total+$select->fetch_assoc()['c'];
	}
	$sql= 'select count(distinct coidnum) as c from tom_a where docid ='.$_POST['id'];
	$select = $mysqli->query($sql);
	if(mysqli_num_rows($select)==1) {
		$total=$total+$select->fetch_assoc()['c'];
	}
	
	$_SESSION['therapist']['doc']['ntests'] = $total;
	
	$select->free();
	$mysqli->close();