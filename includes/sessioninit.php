<?php
	if(!isset($_SESSION)) { session_start(); }			
	if (ini_get('register_globals')) {
		foreach ($_SESSION as $key=>$value)
		{
			if (isset($GLOBALS[$key]))
				unset($GLOBALS[$key]);
		}
	}
	
	$_SESSION['authorized'] = array();
	$_SESSION['user'] = array();
	$_SESSION['test'] = array();
	$_SESSION['testflag'] = array();
	$_SESSION['testnames'] = array();
	unset($_SESSION['authorized'],$_SESSION['user'],$_SESSION['test'],$_SESSION['testflag'],$_SESSION['testnames']);
	
	
	$_SESSION['authorized'] = 1;
	$_SESSION['user']['id'] = $id;
	$_SESSION['user']['patient'] = $patient; // if set to 1 indicates an internal patient

	$sql = 'Select username, year(birthdate) as byear, month(birthdate) as bmonth, day(birthdate) as bday, sex, scholarity as schol from user where id='.$id;
	$result = $mysqli->query($sql);
	$res=$result->fetch_assoc();
	if ($result->num_rows > 0) {
		$_SESSION['user']['username'] = $res['username'];
		$_SESSION['user']['bday']=$res['bday'];
		$_SESSION['user']['bmonth']=$res['bmonth'];
		$_SESSION['user']['byear']=$res['byear'];
		$_SESSION['user']['sex']=$res['sex'];
		$_SESSION['user']['schol']=$res['schol'];
	}



	if ($patient==0){
		$sql = 'select email, faculty, syear, idnumber from ext_user where id='.$id;
		$result = $mysqli->query($sql);
		$res=$result->fetch_assoc();
		if ($result->num_rows > 0) {
			$_SESSION['user']['email']=$res['email'];
			$_SESSION['user']['faculty']=$res['faculty'];
			$_SESSION['user']['syear']=$res['syear'];
			$_SESSION['user']['idnum']=$res['idnumber'];
		}
		$tests = array('aq','attint','bes','emotatt','eyestask','qe','socialsit','tom');
		$i = 0;
		$sql = 'select active from test order by code asc';
		$result = $mysqli->query($sql);
		while ($res=$result->fetch_assoc()) {
			foreach ($res as $value) {
				$_SESSION['test'][$tests[$i]]=$value;
				$i=$i+1;
			}
		}
		unset($sql,$res,$tests,$i,$value);
		
	}
	else if ($patient==1) {
		$sql = 'select a.name, a.surname, a.cg, b.* from patient as a,  patient_tests as b where a.id='.$id.' and a.id=b.id';
		$result = $mysqli->query($sql);
		$res=$result->fetch_assoc();
		if ($result->num_rows > 0) {
			$_SESSION['user']['name']=$res['name'];
			$_SESSION['user']['surname']=$res['surname'];
			$_SESSION['user']['group']=$res['cg'];
			$_SESSION['test']['aq']=$res['aq'];
			$_SESSION['test']['attint']=$res['attint'];
			$_SESSION['test']['bes']=$res['bes'];
			$_SESSION['test']['emotatt']=$res['emotatt'];
			$_SESSION['test']['eyestask']=$res['eyestask'];
			$_SESSION['test']['qe']=$res['qe'];
			$_SESSION['test']['socialsit']=$res['socialsit'];
			$_SESSION['test']['tom']=$res['tom'];	
		}
		unset($sql,$res);
	}

	
	if($_SESSION['test']['aq'] == 1){
		$sql = 'select timestampdiff(month, day, now()) as t from aq_c where patient='.$_SESSION['user']['id'].' order by day desc limit 1';
		$result = $mysqli->query($sql);
		$res=$result->fetch_assoc();
		if ($result->num_rows > 0) {
			if ($res['t'] < 6) {
				$_SESSION['testflag']['aq'] = 1;
			}
		}
	}

	if($_SESSION['test']['attint'] == 1){
		$sql = 'select timestampdiff(month, day, now()) as t from attint_c where patient='.$_SESSION['user']['id'].' and series = 1 order by day desc limit 1';
		$result = $mysqli->query($sql);
		$res=$result->fetch_assoc();
		if ($result->num_rows > 0) {
			if ($res['t'] < 6) {
				$_SESSION['testflag']['attint'][1] = 1;
			}
		}
	
		$sql = 'select timestampdiff(month, day, now()) as t from attint_c where patient='.$_SESSION['user']['id'].' and series = 2 order by day desc limit 1';
		$result = $mysqli->query($sql);
		$res=$result->fetch_assoc();
		if ($result->num_rows > 0) {
			if ($res['t'] < 6) {
				$_SESSION['testflag']['attint'][2] = 1;
			}
		}
		
		$sql = 'select timestampdiff(month, day, now()) as t from attint_c where patient='.$_SESSION['user']['id'].' and series = 3 order by day desc limit 1';
		$result = $mysqli->query($sql);
		$res=$result->fetch_assoc();
		if ($result->num_rows > 0) {
			if ($res['t'] < 6) {
				$_SESSION['testflag']['attint'][3] = 1;
			}
		}
		
		$sql = 'select timestampdiff(month, day, now()) as t from attint_c where patient='.$_SESSION['user']['id'].' and series = 4 order by day desc limit 1';
		$result = $mysqli->query($sql);
		$res=$result->fetch_assoc();
		if ($result->num_rows > 0) {
			if ($res['t'] < 6) {
				$_SESSION['testflag']['attint'][4] = 1;
			}
		}
		
		$sql = 'select timestampdiff(month, day, now()) as t from attint_c where patient='.$_SESSION['user']['id'].' and series = 5 order by day desc limit 1';
		$result = $mysqli->query($sql);
		$res=$result->fetch_assoc();
		if ($result->num_rows > 0) {
			if ($res['t'] < 6) {
				$_SESSION['testflag']['attint'][5] = 1;
			}
		}
	}				
	
	if($_SESSION['test']['bes'] == 1){
		$sql = 'select timestampdiff(month, day, now()) as t from bes_c where patient='.$_SESSION['user']['id'].' order by day desc limit 1';
		$result = $mysqli->query($sql);
		$res=$result->fetch_assoc();
		if ($result->num_rows > 0) {
			if ($res['t'] < 6) {
				$_SESSION['testflag']['bes'] = 1;
			}
		}
	}
	
	if($_SESSION['test']['emotatt'] == 1){
		$sql = 'select timestampdiff(month, day, now()) as t from emotatt_c where patient='.$_SESSION['user']['id'].' order by day desc limit 1';
		$result = $mysqli->query($sql);
		$res=$result->fetch_assoc();
		if ($result->num_rows > 0) {
			if ($res['t'] < 6) {
				$_SESSION['testflag']['emotatt'] = 1;
			}
		}
	}
	
	if($_SESSION['test']['eyestask'] == 1){
		$sql = 'select timestampdiff(month, day, now()) as t from eyestask_c where patient='.$_SESSION['user']['id'].' order by day desc limit 1';
		$result = $mysqli->query($sql);
		$res=$result->fetch_assoc();
		if ($result->num_rows > 0) {
			if ($res['t'] < 6) {
				$_SESSION['testflag']['eyestask'] = 1;
			}
		}
	}
		
	if($_SESSION['test']['qe'] == 1){
		$sql = 'select timestampdiff(month, day, now()) as t from qe_c where patient='.$_SESSION['user']['id'].' order by day desc limit 1';
		$result = $mysqli->query($sql);
		$res=$result->fetch_assoc();
		if ($result->num_rows > 0) {
			if ($res['t'] < 6) {
				$_SESSION['testflag']['qe'] = 1;
			}
		}
	}
	
	if($_SESSION['test']['socialsit'] == 1){
		$sql = 'select timestampdiff(month, day, now()) as t from socialsit_c where patient='.$_SESSION['user']['id'].' order by day desc limit 1';
		$result = $mysqli->query($sql);
		$res=$result->fetch_assoc();
		if ($result->num_rows > 0) {
			if ($res['t'] < 6) {
				$_SESSION['testflag']['socialsit'] = 1;
			}
		}
	}
	
	if($_SESSION['test']['tom'] == 1){
		$sql = 'select timestampdiff(month, day, now()) as t from tom_c where patient='.$_SESSION['user']['id'].' order by day desc limit 1';
		$result = $mysqli->query($sql);
		$res=$result->fetch_assoc();
		if ($result->num_rows > 0) {
			if ($res['t'] < 6) {
				$_SESSION['testflag']['tom'] = 1;
			}
		}
	}
	
	unset($sql,$res);
	
	$_SESSION['testnames']['aq']='Quoziente di spettro autistico';
	$_SESSION['testnames']['attint']='Test di attribuzione delle intenzioni';
	$_SESSION['testnames']['bes']='Basic Empathy Task';
	$_SESSION['testnames']['emotatt']='Test di attribuzione delle emozioni';
	$_SESSION['testnames']['eyestask']='Eyes task';
	$_SESSION['testnames']['qe']='Quoziente di empatia';
	$_SESSION['testnames']['socialsit']='Test delle situazioni sociali';
	$_SESSION['testnames']['tom']='Test di teoria della mente di livello superiore';

	
	$mysqli->close();