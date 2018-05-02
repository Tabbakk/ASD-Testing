<?php

include '/connessione.php';
include '/ver_auth.php';

if(!isset($_SESSION)){ 
	session_start();
}

$_SESSION['socialsit'] = array();
$_SESSION['help'] = array();
unset($_SESSION['socialsit'],$_SESSION['help']);	

$_SESSION['socialsit']['qtype'] = 1; //indica che sono in forma testuale
$_SESSION['socialsit']['atype'] = 1; //indica che sono in forma testuale
$_SESSION['socialsit']['htype'] = 1; //indica che sono in forma testuale

$sql = 'select qnum, subqnum, intro, question from socialsit_qc where qtype='.$_SESSION['socialsit']['qtype'].' order by qnum, subqnum asc';
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['socialsit'][$row['qnum']][$row['subqnum']]['intro']=$row['intro'];
		$_SESSION['socialsit'][$row['qnum']][$row['subqnum']]['question']=$row['question'];
	}

}

$sql = 'select help from help where test=7 and type='.$_SESSION['socialsit']['htype'];
$result=$mysqli->query($sql);
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()){
		$_SESSION['help']['socialsit']=$row['help'];
	}
}

$mysqli->close();