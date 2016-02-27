<?php

include('../include/function.php');
$con=dbconnect();
//if(!isset($_SESSION['bakid']))
	//die();
$cid=$_POST['cid1'];
$ar=array();
$query="select * from bakar_clients where status=1 and cid=$cid";
while(1)
{
	$res=$con->query($query);
	if($res->num_rows > 0)
	{
		$sel_q="select * from bakar_chat where cid2=$cid and etime=\"\"";
		$sel_r=$con->query($sel_q);
		$row=$sel_r->fetch_array();
		$ar[]=$_SESSION['cid2']=$row['cid1'];
		$ar[]=$_SESSION['chatid']=$row['chatid'];
		echo json_encode($ar);
		break;
	}
}
?>