<?php

include '/connessione.php';
include '/ver_auth.php';

if(!isset($_SESSION)){ 
	session_start();
}			

$_SESSION['aq'] = array();
$_SESSION['help'] = array();
unset($_SESSION['aq'],$_SESSION['help']);

$_SESSION['aq']['qtype'] = 1; //indica che le domande sono in forma testuale
$_SESSION['aq']['atype'] = 1; //indica che le risposte sono in forma testuale
$_SESSION['aq']['htype'] = 1; //indica che l'aiuto è in forma testuale

$sql = 'select qnum, question from aq_qc where qtype='.$_SESSION['aq']['qtype'];
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['aq']["'".$row['qnum']."'"]=$row['question'];
	}
}

$sql = 'select help from help where test=1 and type='.$_SESSION['aq']['htype'];
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['help']['aq']=$row['help'];
	}
}



$mysqli->close();