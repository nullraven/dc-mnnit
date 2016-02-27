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
		$port=$row['port'];
		$ip=$row['ip'];
		$arr=array();		
		exec("nmap -p $port $ip -Pn",$arr);
		$cnt=0;
		while(strstr($arr[5],"filtered") && $cnt<15)
		{
				$cnt++;
				exec("nmap -p $port $ip -Pn",$arr);
		}
		if(strstr($arr[5],"open"))
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
		
		$query="update proxy set status=\"$status\",totcount=totcount+1  $olcount where ip=\"$ip\" and port=\"$port\"";
		$con->query($query);
		
	}
	
	$con->close();
//exec("nmap -p $port $ip -Pn",$arr);

?>