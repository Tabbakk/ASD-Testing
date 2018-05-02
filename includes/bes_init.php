<?php

include '/connessione.php';
include '/ver_auth.php';

if(!isset($_SESSION)){ 
	session_start();
}	

$_SESSION['bes'] = array();
$_SESSION['help'] = array();
unset($_SESSION['bes'],$_SESSION['help']);	

$_SESSION['bes']['qtype'] = 1; //indica che sono in forma testuale
$_SESSION['bes']['atype'] = 1; //indica che sono in forma testuale
$_SESSION['bes']['htype'] = 1; //indica che l'help è in forma testuale

$sql = 'select qnum, question from bes_qc where qtype='.$_SESSION['bes']['qtype'];
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['bes']["'".$row['qnum']."'"]=$row['question'];
	}

}

$sql = 'select help from help where test=3 and type='.$_SESSION['bes']['htype'];
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['help']['bes']=$row['help'];
	}
}


$mysqli->close();