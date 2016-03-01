<?php
/* Call this script via AJAX with params stat(status desc),lnk(magnet link),rid(request id)
	Updates dcrequests table entry with id=$rid 
*/
include_once('../include/function.php');
$con=dbconnect();

	session_start();
	if(!isset($_SESSION['admin']))
	{
		header("location: ./index.php");
		die();
	}
	//foreach($_POST as $a=>$b)
	//	$_POST[$a]=validate($b);
	$rid=$_POST['rid'];
	$admin=$_SESSION['user'];
	$stat=$_POST['stat'];
	$link=$_POST['lnk']?$_POST['lnk']:null;
	if(in_array($stat,array("Fulfilled","Already Present")) && empty($link)){
		echo "Enter Magnetic link also!! If it is a folder, link to any of its file.";
		die();
	}
	$qry="update dcrequests set status=?,fulfilledby=?,link=?,timesolv=NOW() where id=?";
	$stmt=$con->prepare($qry);
	$stmt->bind_param("ssss",$stat,$admin,$link,$rid);
	
	$stmt->execute();
	echo $stmt->error;
	//$con->query($qry) or 
	$stmt->close();
	$con->close();
	echo 'Request Status Updated! :)';
?>
