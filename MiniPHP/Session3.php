<?php
	header("Content-Type:text/html;charset=UTF-8");

	require_once './Libs/Session.class.php';

	$session = new Session();
	//启用Session
	session_start();
	//销毁Session
	session_destroy();
?>