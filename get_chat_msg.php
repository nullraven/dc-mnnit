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
$sent=$_POST['cid2'];
$query="select * from bakar_msg where chatid=$chatid and sentby=$sent and status=0";
$res=$con->query($query);
$msg=array();
while($row=$res->fetch_array())
{
	$id=$row['msgid'];
	$msg[]=$row['message'];
	$up_q="update bakar_msg set status=1 where msgid=$id";
	$con->query($up_q);
}
$msg=json_encode($msg);
echo "$msg";

?>