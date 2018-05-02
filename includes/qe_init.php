<?php

include '/connessione.php';
include '/ver_auth.php';

if(!isset($_SESSION)){ 
	session_start();
}

$_SESSION['qe'] = array();
$_SESSION['help'] = array();
unset($_SESSION['qe'],$_SESSION['help']);	

$_SESSION['qe']['qtype'] = 1; //indica che sono in forma testuale
$_SESSION['qe']['atype'] = 1; //indica che sono in forma testuale
$_SESSION['qe']['htype'] = 1; //indica che sono in forma testuale

$sql = 'select qnum, question from qe_qc where qtype='.$_SESSION['qe']['qtype'];
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['qe']["'".$row['qnum']."'"]=$row['question'];
	}

}

$sql = 'select help from help where test=6 and type='.$_SESSION['qe']['htype'];
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['help']['qe']=$row['help'];
	}
}


$mysqli->close();