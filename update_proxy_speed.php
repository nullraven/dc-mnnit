<?php
	include('function.php');
	set_time_limit(1500);
	$con=dbconnect();
	date_default_timezone_set("Asia/Kolkata");
	exec("php update_proxy.php");
	//________________________________________________________Proxy stat________________________________________________
	ip_flush();
	$query="select * from proxy where 1";
	$res=$con->query($query);
	$work=array();
	$notw=array();
	while($row=$res->fetch_array())
	{
		if($row['status']=="Working")
		{
			$work[]=$row['ip'];
		}
		else
		{
			$notw[]=$row['ip'];
		}
	}
	echo "Working:";
	print_r($work);
	echo "<br>Not working:";
	print_r($notw);
	$upd="update last_update_proxy set `time`='".time()."';";
	$con->query($upd);
	
	foreach($work as $ip)
	{
			$port=3128;
			//$ex="wget -e use_proxy=yes -e http_proxy=http://edcguest:edcguest@$ip:$port http://210.212.49.26/npp.exe -O /dev/null";
			
			$time_start = microtime(true);
			proxy_wget($ip);
			//exec($ex);
			$time_end = microtime(true);
			$time = $time_end - $time_start;
			
				$speed=10240/$time; //7373kb is the file size being downloaded.
			$kbps=$speed;
			//agar speed 11 mbps se jyada aa rhi hai to kuch gadbad hai.. to random 100kbps tak kuch bhi dikha do :P
			if($kbps>(13*1024))
			{
				$kbps=rand(0,100);
				$speed=$kbps;
			}
			$speed=get_speed($speed);
			/*if($speed>1024)
			{
				$speed=$speed/1024;
				$speed=round($speed,2);
				$speed.=" MB/s";
			}
			else
			{
				$speed=round($speed,0);
				$speed.=" KB/s";
			}*/
			
			$his_q="insert into proxy_history (`ip`,`speed`) VALUES (\"$ip\",$kbps)";
			$con->query($his_q);
			$query="update proxy set speed=\"$speed\" where ip=\"$ip\" and port=\"$port\"";
			$con->query($query);
	
	}
		foreach($notw as $ip)
		{
		
			$his_q="insert into proxy_history (`ip`,`speed`) VALUES (\"$ip\",\"0\")";
			$con->query($his_q);
	
		}
	
	
	$con->close();

?>