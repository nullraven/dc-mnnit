<?php

include('function.php');
foreach($_POST as $a=>$b)
	$_POST[$a]=validate($b);
//msg, chatid, cid1,cid2;
session_start();
$con=dbconnect();
//if(!isset($_SESSION['bakid']))
	//die();
$chatid=$_POST['chatid'];
$sent=$_POST['cid1'];
$msg=$_POST['msg'];
echo $query="insert into bakar_msg (chatid,sentby,message,status) VALUES ($chatid,$sent,\"$msg\",0)";
$con->query($query);
echo "hi";

?>