<?php
	session_start();
	include_once('function.php');
	
	
	
	$req='%'.$_REQUEST['str'].'%';
	$where_cat="'".implode("','",$_SESSION['filters']['category'])."'";
	$where_status="'".implode("','",$_SESSION['filters']['status'])."'";
	
	$sql=dbconnect();
	$res=array();
	$srch=$sql->prepare("select id,category,name,link,status,fulfilledby from dcrequests where name like ? and status!='r' and category in ($where_cat) and status in ($where_status) order by timeofreq desc");
	$srch->bind_param("s",$req);
	$srch->execute() or die($srch->error);
	$srch->bind_result($id,$category,$name,$link,$status,$fulfil);
	//echo 'hi';
	while($srch->fetch()){
//		echo "<option value='".$name."'></option>";
		
		echo "<tr id='$id'><td class=\"col-md-2\">$category</td><td class=\"col-md-6\">$name";
		if(!empty($link)){
			echo "<a href='$link'> <span class=\"glyphicon glyphicon-magnet\"></span> </a>";	
		}
		echo "</td><td class=\"col-md-4\" style=\"word-wrap: break-word;\">$status";
		if(in_array($status,array("Downloading","Fulfilled","Already Present")))
			echo " by <b>$fulfil</b>";
		echo "</td></th>";
	}
?>
