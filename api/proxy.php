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
    $avg_r = $con->query($avg_q);
    $avg_rr = $avg_r->fetch_array();
    $avg = get_speed($avg_rr[0]);

    $row['avgspeed'] = $avg;

    $proxies[] = $row;
  }

  $response = array(
    "time" => $time,
    "proxies" => $proxies
  );

  echo json_encode($response);
?>
