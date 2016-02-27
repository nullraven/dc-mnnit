<?php
include_once('function.php');
$con=dbconnect();

	session_start();
	if(!isset($_SESSION['admin']))
	{
		header("location: ./index.php");
		die();
	}
	foreach($_POST as $a=>$b)
		$_POST[$a]=validate($b);
	$rid=$_POST['rid'];
	$admin=htmlentities($_SESSION['user']);
	$stat=$_POST['stat'];
	$link=$_POST['lnk'];
	if(in_array($stat,array("Fulfilled","Already Present")) && empty($link)){
		echo "Enter Magnetic link also!! If it is a folder, link to any of its file.";
		die();
	}
	$qry="update dcrequests set status='$stat',fulfilledby='$admin',link='$link',timesolv=NOW() where id=$rid";
	$con->query($qry) or die("Error updating...Please try after sometime");
	
	echo 'Request Status Updated! :)';
?>
