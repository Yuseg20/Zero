<?php
	header("Content-Type:text/html;charset=UTF-8");

	require_once './Libs/Session.class.php';

	$session = new Session();
	//启用Session
	session_start();
	$_SESSION['data1'] = 'data1';
	$_SESSION['data2'] = 'data2';
	$_SESSION['data3'] = 'data3';
?>