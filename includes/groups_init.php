<?php

	include 'connessione.php'; 
	include '/ver_auth_t.php';
	 
	$select = $mysqli->query('SELECT a.code, a.name, count(b.id) as num from clinicalg as a, patient as b where b.cg = a.code group by a.code union select c.code, c.name, 0 as num from clinicalg as c where c.code not in (select distinct cg from patient where cg is not null)');
	$i = 1;
	
	$_SESSION['group'] = array();
	unset($_SESSION['group']);
	
	foreach ($select as $result){
		$_SESSION['group'][$i]['id'] = $result['code'];
		$_SESSION['group'][$i]['name'] = $result['name'];
		$_SESSION['group'][$i]['num'] = $result['num'];
		$i = $i+1;
	}
	
	$select->free();
	$mysqli->close();
