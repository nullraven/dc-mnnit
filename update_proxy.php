<?php
	include('function.php');
	$con=dbconnect();
	date_default_timezone_set("Asia/Kolkata");
	
	//________________________________________________________Proxy stat________________________________________________
	$query="select * from proxy where 1";
	$res=$con->query($query);
	$max=30*24*60*60;
	while($row=$res->fetch_array())
	{
		echo $port=$row['port'];
		echo $ip=$row['ip'];
		$arr=array();		
		$arr=proxy_check($ip,$port);
		
		if(strstr($arr[6],"200 OK"))
		{
			$status="Working";
			$olcount=" ,olcount=olcount+1";
		}
		else
		{
			$status="Down";
			$olcount="";	
		}
		echo $ip." ".$status."<br>";
		$his_q="insert into proxy_history_run (`ip`,`status`) VALUES (\"$ip\",\"$status\")";
		$con->query($his_q);
			
		$query="update proxy set status=\"$status\",totcount=totcount+1  $olcount where ip=\"$ip\" and port=\"$port\"";
		$con->query($query);
		
	}
	
	$con->close();
//exec("nmap -p $port $ip -Pn",$arr);

?>