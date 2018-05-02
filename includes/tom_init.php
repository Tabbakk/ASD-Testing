<?php

include '/connessione.php';
include '/ver_auth.php';

if(!isset($_SESSION)){ 
	session_start();
}

$_SESSION['tom'] = array();
$_SESSION['help'] = array();
unset($_SESSION['tom'],$_SESSION['help']);		

$_SESSION['tom']['qtype'] = 1; //indica che sono in forma testuale
$_SESSION['tom']['atype'] = 1; //indica che sono in forma testuale
$_SESSION['tom']['htype'] = 1; //indica che sono in forma testuale

$sql = 'select qnum, intro, q1, q2 from tom_qc where qtype='.$_SESSION['tom']['qtype'];
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['tom']["'".$row['qnum']."'"]['intro']=$row['intro'];
		$_SESSION['tom']["'".$row['qnum']."'"]['q1']=$row['q1'];
		$_SESSION['tom']["'".$row['qnum']."'"]['q2']=$row['q2'];
	}

}

$sql = 'select help from help where test=8 and type='.$_SESSION['tom']['htype'];
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['help']['tom']=$row['help'];
	}
}


$mysqli->close();