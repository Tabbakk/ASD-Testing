<?php 
	if(!isset($_SESSION)) { session_start(); }
	include '/config.php';
	include '/ver_auth_t.php';
	include '/connessione.php';
	
	$_SESSION['therapist']['uncorrected']=array();
	unset($_SESSION['therapist']['uncorrected']);
	
	$sql='select t.test as tcode, t.idnumber as tid, t.day as d, day(t.day) as day, month(t.day) as month, year(t.day) as year, p.id as pid, p.name as pname, p.surname as psurname, g.name as gname from tom_c as t, patient as p, clinicalg as g where t.completed=0 and t.patient=p.id and p.cg=g.code union select t.test as tcode, t.idnumber as tid, t.day as d, day(t.day) as day, month(t.day) as month, year(t.day) as year, p.id as pid, p.name as pname, p.surname as psurname, g.name as gname from emotatt_c as t, patient as p, clinicalg as g where t.completed=0 and t.patient=p.id and p.cg=g.code order by d asc';
	
	$result = $mysqli->query($sql);
	
	if(mysqli_num_rows($result) > 0){
		$numtom=0;
		$numemotatt=0;
		while($test = mysqli_fetch_assoc($result)){
			if($test['tcode']==8){
				$numtom=$numtom+1;
				$_SESSION['therapist']['uncorrected']['tom'][$numtom]['tid']=$test['tid'];
				$_SESSION['therapist']['uncorrected']['tom'][$numtom]['date']=$test['day']."/".$test['month']."/".$test['year'];
				$_SESSION['therapist']['uncorrected']['tom'][$numtom]['pid']=$test['pid'];
				$_SESSION['therapist']['uncorrected']['tom'][$numtom]['pname']=$test['psurname'].", ".$test['pname'];
				$_SESSION['therapist']['uncorrected']['tom'][$numtom]['gname']=$test['gname'];
			}
			if($test['tcode']==4){
				$numemotatt=$numemotatt+1;
				$_SESSION['therapist']['uncorrected']['emotatt'][$numemotatt]['tid']=$test['tid'];
				$_SESSION['therapist']['uncorrected']['emotatt'][$numemotatt]['date']=$test['day']."/".$test['month']."/".$test['year'];
				$_SESSION['therapist']['uncorrected']['emotatt'][$numemotatt]['pid']=$test['pid'];
				$_SESSION['therapist']['uncorrected']['emotatt'][$numemotatt]['pname']=$test['psurname'].", ".$test['pname'];
				$_SESSION['therapist']['uncorrected']['emotatt'][$numemotatt]['gname']=$test['gname'];			
			}
		}
		$result->free();	
	}
	
	$mysqli->close();
