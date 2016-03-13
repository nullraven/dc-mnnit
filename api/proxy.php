<?php

require_once("../include/function.php");

header("Content-type: application/json");

  $con = dbconnect();

  $query = "select * from last_update_proxy";
  $res = $con->query($query);
  $row = $res->fetch_array();
  $time = $row[0];

  $query = "select * from proxy order by status desc,ip asc";
  $res = $con->query($query);

  $proxies = array();
  while($row = $res->fetch_assoc())
  {
    $ip = $row['ip'];

    $avg_q = "select avg(speed) from proxy_history where ip=\"$ip\"";
	$last_spd = "select speed from proxy_history where ip=\"$ip\" order by timestamp desc limit 0,1";
	
    $avg_r = $con->query($avg_q);
	$last_spd = $con->query($last_spd);
	
    $avg_rr = $avg_r->fetch_array();
	$last_spd = $last_spd->fetch_array();
	
    $avg = get_speed($avg_rr[0]);

    $row['avgspeed_mbps'] = $avg;
	$row['avgspeed_kbps'] = $avg_rr[0];
	$row['speed_kbps'] = $last_spd[0];
	
    $proxies[] = $row;
  }

  $response = array(
    "time" => $time,
    "proxies" => $proxies
  );

  echo json_encode($response);
?>
