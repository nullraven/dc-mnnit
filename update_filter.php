<?php
	session_start();
	include_once('function.php');
	validate($_POST);
	print_r($_POST);
	$_SESSION['filters']['category']=$_POST['category'];
	$_SESSION['filters']['status']=$_POST['status'];
?>