<?php

include '/connessione.php';
include '/ver_auth.php';

if(!isset($_SESSION)){ 
	session_start();
}

$_SESSION['emotatt'] = array();
$_SESSION['help'] = array();
unset($_SESSION['emotatt'],$_SESSION['help']);

$_SESSION['emotatt']['qtype'] = 1; //indica che sono in forma testuale
$_SESSION['emotatt']['atype'] = 1; //indica che sono in forma testuale
$_SESSION['emotatt']['htype'] = 1; //indica che sono in forma testuale

$sql = 'select qnum, question from emotatt_qc where qtype='.$_SESSION['emotatt']['qtype'];
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['emotatt']["'".$row['qnum']."'"]=$row['question'];
	}

}

$sql = 'select help from help where test=4 and type='.$_SESSION['emotatt']['htype'];
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['help']['emotatt']=$row['help'];
	}
}


$mysqli->close();