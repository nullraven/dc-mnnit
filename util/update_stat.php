<?php
	require_once('/var/www/html/dc/include/function.php');
	$con=dbconnect();
	date_default_timezone_set("Asia/Kolkata");
	
	//________________________________________________________DC HUB stat________________________________________________
	$query="select * from hubs_info where 1";
	$res=$con->query($query);
	$max=30*24*60*60;
	while($row=$res->fetch_array())
	{
		$port=$row['port'];
		$ip=$row['ip'];
		$arr=array();		
		exec("nmap -p $port $ip -Pn",$arr);
		if(strstr($arr[5],"open"))
		{
			$status="ONLINE";
			$at=time();
			$olcount=" ,olcount=olcount+1 ";
		}
		else
		{
			$status="OFFLINE";
			$at=$row['last_online'];
			$olcount="";
			if($at=='')
			{
				$at=time();
			}
		}
		/*if a hub is not online for more than 1 month, delete it. :)*/
		if((time()-$at)>$max && $at!='')
		{
			$query="insert into hubs_archive (`ip`,`port`,`when`) VALUES (?,?,\"".time()."\") ";
			$stmt=$con->prepare($query);
			$stmt->bind_param("ss",$ip,$port);
			$stmt->execute();
			$stmt->close();
			
			$query="delete from hubs_info where ip=? and port=?";
			$stmt=$con->prepare($query);
			$stmt->bind_param("ss",$ip,$port);
			$stmt->execute();
			$stmt->close();
		}
		echo "$ip:$port => $status <br>";
		
		echo $query="update hubs_info set status=\"$status\",last_online=\"$at\",totcount=totcount+1  $olcount where ip=? and port=?";
		$stmt=$con->prepare($query);
		$stmt->bind_param("ss",$ip,$port);
		$stmt->execute();
		$stmt->close();
		/*$query="TRUNCATE table last_update";
		$con->query($query);
		$query="insert into last_update values (\"".time()."\")";*/
	}
	$upd="update last_update set `time`='".time()."';";
	$con->query	($upd);
	$upd="update dcrequests set status='Pending',fulfilledby='' WHERE (NOW()-timesolv)/(60*60*24)>24 and status like 'Downloading'";
	$con->query	($upd);
	$upd="update `dcrequests` set status='r' WHERE (NOW()-timesolv)/(60*60*24)>24 and status like 'Invalid Request'";
	$con->query	($upd);
/*
update dcrequests set status='Pending',fulfilledby='' WHERE datediff(NOW(),timesolv)>=1 and status like 'Downloading';
		  update `dcrequests` set status='r' WHERE datediff(NOW(),timesolv)>=1 and status like 'Invalid Request'
*/	
	$con->close();
//exec("nmap -p $port $ip -Pn",$arr);

?>