<?php
	ini_set('display_errors', FALSE);
	ini_set( 'session.cookie_httponly', 1 ); 
	include_once "./init.php";	
	include_once "./application.php";
	session_start();
	$app = new Application();
	$app->run();
?>