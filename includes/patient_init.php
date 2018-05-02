<?php
	if(!isset($_SESSION)) { session_start(); }	
	include '/ver_auth_t.php';	
	
	$sql = 'Select a.id as id, a.username as username, TIMESTAMPDIFF(year,a.birthdate,CURDATE()) as age, year(a.birthdate) as byear, month(a.birthdate) as bmonth, day(a.birthdate) as bday, a.sex as sex, a.scholarity as schol, b.name as name, b.surname as surname, b.number as number, b.cg as cg, c.name as gname from user as a, patient as b, clinicalg as c where a.id=b.id and b.cg=c.code and a.id='.$_POST['id'];
	
	$select = $mysqli->query($sql);
	if(mysqli_num_rows($select)==1) {
		$res = $select->fetch_assoc();
		$_SESSION['therapist']['patient']['id']=$res['id'];
		$_SESSION['therapist']['patient']['username']=$res['username'];
		$_SESSION['therapist']['patient']['name']=$res['name'];
		$_SESSION['therapist']['patient']['surname']=$res['surname'];
		$_SESSION['therapist']['patient']['age']=$res['age'];
		$_SESSION['therapist']['patient']['bday']=$res['bday'];
		$_SESSION['therapist']['patient']['bmonth']=$res['bmonth'];
		$_SESSION['therapist']['patient']['byear']=$res['byear'];
		$_SESSION['therapist']['patient']['sex']=$res['sex'];
		$_SESSION['therapist']['patient']['schol']=$res['schol'];
		$_SESSION['therapist']['patient']['gname']=$res['gname'];
		$_SESSION['therapist']['patient']['gid']=$res['cg'];
		$_SESSION['therapist']['patient']['number']=$res['number'];
	}
	$sql = 'select * from patient_tests where id='.$_POST['id'];
	$select = $mysqli->query($sql);
	if(mysqli_num_rows($select)==1) {
		$res = $select->fetch_assoc();
		$abbr = array(1 => 'aq', 2 => 'attint', 3 => 'bes', 4 => 'emotatt', 5 => 'eyestask', 6 => 'qe', 7 => 'socialsit', 8 => 'tom');
		$active = array(1 => $res['aq'], 2 => $res['attint'], 3 => $res['bes'], 4 => $res['emotatt'], 5 => $res['eyestask'], 6 => $res['qe'], 7 => $res['socialsit'], 8 => $res['tom']); 
	}
	$sql = 'select name from test order by code asc';
	$select = $mysqli->query($sql);
	$i = 1;
	while ($res = $select->fetch_assoc()){
		$_SESSION['therapist']['patient']['tests'][$i]['name'] = $res['name'];
		$_SESSION['therapist']['patient']['tests'][$i]['abbr'] = $abbr[$i];
		$_SESSION['therapist']['patient']['tests'][$i]['active'] = $active[$i];
		$i=$i+1;
	}
	
	
	$select->free();
	$mysqli->close();