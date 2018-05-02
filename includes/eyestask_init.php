<?php

include '/connessione.php';
include '/ver_auth.php';

if(!isset($_SESSION)){ 
	session_start();
}

$_SESSION['eyestask'] = array();
$_SESSION['help'] = array();
unset($_SESSION['eyestask'],$_SESSION['help']);		

$_SESSION['eyestask']['qtype'] = 2; //indica che sono in forma di immagine
$_SESSION['eyestask']['atype'] = 1; //indica che sono in forma testuale
$_SESSION['eyestask']['htype'] = 1; //indica che sono in forma testuale

$sql = 'select qnum, Eyes from eyestask_qc where qtype='.$_SESSION['eyestask']['qtype'];
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['eyestask']["'".$row['qnum']."'"]['eyes']=$row['Eyes'];
	}

}
$sql = 'select qnum, A, B, C, D from eyestask_ac where atype='.$_SESSION['eyestask']['atype'];
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['eyestask']["'".$row['qnum']."'"]['choice']['A']=$row['A'];
		$_SESSION['eyestask']["'".$row['qnum']."'"]['choice']['B']=$row['B'];
		$_SESSION['eyestask']["'".$row['qnum']."'"]['choice']['C']=$row['C'];
		$_SESSION['eyestask']["'".$row['qnum']."'"]['choice']['D']=$row['D'];
	}

}

$sql = 'select help from help where test=5 and type='.$_SESSION['eyestask']['htype'];
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['help']['eyestask']=$row['help'];
	}
}


$mysqli->close();