<?php

include '/connessione.php';
include '/ver_auth.php';

if(!isset($_SESSION)){ 
	session_start();
}

$_SESSION['attint'] = array();
$_SESSION['help'] = array();
unset($_SESSION['attint'],$_SESSION['help']);		

$_SESSION['attint']['qtype'] = 2; 	//indica che sono in forma di immagini
$_SESSION['attint']['atype'] = 2; 	//indica che sono in forma di immagini
$_SESSION['attint']['htype'] = 1;	//indica che l'help è in forma di testo

$sql = 'select qnum, A, B, C from attint_qc where qtype='.$_SESSION['attint']['qtype'].' and series = 1';
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['attint']['1']["'".$row['qnum']."'"]['A']=$row['A'];
		$_SESSION['attint']['1']["'".$row['qnum']."'"]['B']=$row['B'];
		$_SESSION['attint']['1']["'".$row['qnum']."'"]['C']=$row['C'];
	}
}
$sql = 'select qnum, A, B, C from attint_ac where atype='.$_SESSION['attint']['atype'].' and series = 1';
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['attint']['1']["'".$row['qnum']."'"]['D']=$row['A'];
		$_SESSION['attint']['1']["'".$row['qnum']."'"]['E']=$row['B'];
		$_SESSION['attint']['1']["'".$row['qnum']."'"]['F']=$row['C'];
	}
}


$sql = 'select qnum, A, B, C from attint_qc where qtype='.$_SESSION['attint']['qtype'].' and series = 2';
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['attint']['2']["'".$row['qnum']."'"]['A']=$row['A'];
		$_SESSION['attint']['2']["'".$row['qnum']."'"]['B']=$row['B'];
		$_SESSION['attint']['2']["'".$row['qnum']."'"]['C']=$row['C'];
	}
}
$sql = 'select qnum, A, B, C from attint_ac where atype='.$_SESSION['attint']['atype'].' and series = 2';
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['attint']['2']["'".$row['qnum']."'"]['D']=$row['A'];
		$_SESSION['attint']['2']["'".$row['qnum']."'"]['E']=$row['B'];
		$_SESSION['attint']['2']["'".$row['qnum']."'"]['F']=$row['C'];
	}
}


$sql = 'select qnum, A, B, C from attint_qc where qtype='.$_SESSION['attint']['qtype'].' and series = 3';
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['attint']['3']["'".$row['qnum']."'"]['A']=$row['A'];
		$_SESSION['attint']['3']["'".$row['qnum']."'"]['B']=$row['B'];
		$_SESSION['attint']['3']["'".$row['qnum']."'"]['C']=$row['C'];
	}
}
$sql = 'select qnum, A, B, C from attint_ac where atype='.$_SESSION['attint']['atype'].' and series = 3';
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['attint']['3']["'".$row['qnum']."'"]['D']=$row['A'];
		$_SESSION['attint']['3']["'".$row['qnum']."'"]['E']=$row['B'];
		$_SESSION['attint']['3']["'".$row['qnum']."'"]['F']=$row['C'];
	}
}


$sql = 'select qnum, A, B, C from attint_qc where qtype='.$_SESSION['attint']['qtype'].' and series = 4';
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['attint']['4']["'".$row['qnum']."'"]['A']=$row['A'];
		$_SESSION['attint']['4']["'".$row['qnum']."'"]['B']=$row['B'];
		$_SESSION['attint']['4']["'".$row['qnum']."'"]['C']=$row['C'];
	}
}
$sql = 'select qnum, A, B, C from attint_ac where atype='.$_SESSION['attint']['atype'].' and series = 4';
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['attint']['4']["'".$row['qnum']."'"]['D']=$row['A'];
		$_SESSION['attint']['4']["'".$row['qnum']."'"]['E']=$row['B'];
		$_SESSION['attint']['4']["'".$row['qnum']."'"]['F']=$row['C'];
	}
}


$sql = 'select qnum, A, B, C from attint_qc where qtype='.$_SESSION['attint']['qtype'].' and series = 5';
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['attint']['5']["'".$row['qnum']."'"]['A']=$row['A'];
		$_SESSION['attint']['5']["'".$row['qnum']."'"]['B']=$row['B'];
		$_SESSION['attint']['5']["'".$row['qnum']."'"]['C']=$row['C'];
	}
}
$sql = 'select qnum, A, B, C from attint_ac where atype='.$_SESSION['attint']['atype'].' and series = 5';
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['attint']['5']["'".$row['qnum']."'"]['D']=$row['A'];
		$_SESSION['attint']['5']["'".$row['qnum']."'"]['E']=$row['B'];
		$_SESSION['attint']['5']["'".$row['qnum']."'"]['F']=$row['C'];
	}
}

$sql = 'select help from help where test=2 and type='.$_SESSION['attint']['htype'];
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['help']['attint']=$row['help'];
	}
}


$mysqli->close();