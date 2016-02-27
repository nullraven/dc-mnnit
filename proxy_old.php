<html>
<head>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/mainsite.css">
<link rel="stylesheet" href="tablesorter/css/theme.blue.css">
<script src="js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/search.js"></script>
<script>
$(document).ready(function(e) {
    $("#hubtab").tablesorter({sortList: [[2,1],[4,1],[3,1]]});
});
</script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>DC @ MNNIT Allahabad	</title>

</head>
<body onLoad="setInterval('window.location.reload()', 120000);">
<?php 
include_once('function.php');
if(!isset($_SESSION))
	session_start();

if(!isset($_SESSION['count']) || (date()-$_SESSION['opentime'])>5*60){
	$_SESSION['count']=0;
	$_SESSION['opentime']=date();
}
else $_SESSION['count']++;
if($_SESSION['count']==0){
	$content=date("H:i:s d/m/y")." ".json_encode($_SERVER)."\n";
	file_put_contents("./downlog",$content,FILE_APPEND);
}
getHeader("proxy.php");
?>
<h4 class="col-md-offset-2 col-md-6 text-success"><span class="glyphicon glyphicon-info-sign"></span> Status of Proxies</h4><br><br>
<br>


<div class="container col-md-7 col-md-offset-2">
	<table class="table table-bordered table-hover tablesorter" id="hubtab" >
    <thead>
    <tr>
    <th>IP</th>
    <th>Port</th>
    <th>Status</th>
	<th>% Uptime</th>
    <th>Approx. Speed</th>
  <!--  <th>Avg. Speed (All time) </th>-->
    </tr>
    </thead><tbody>
<?php 
include_once('function.php');
if(!isset($_SESSION))
	session_start();

if(!isset($_SESSION['count']) || (date()-$_SESSION['opentime'])>15*60){
	$_SESSION['count']=0;
	$_SESSION['opentime']=date();
}
else $_SESSION['count']++;
if($_SESSION['count']==0){
	$content=date("H:i:s d/m/y")." ".json_encode($_SERVER)."\n";
	file_put_contents("./downlog",$content,FILE_APPEND);
}
$con=dbconnect();
$query="select * from last_update_proxy";
$res=$con->query($query);
$row=$res->fetch_array();
$up=$row[0];
//$totcount=$row[1];
$query="select * from proxy order by status desc,ip asc";
$res=$con->query($query);
while($row=$res->fetch_array())
{
	$port=$row['port'];
	$ip=$row['ip'];
	//$name=$row['name'];
	$stat=$row['status'];
	$olcount=$row['olcount'];
	$totcount=$row['totcount'];
	$speed=$row['speed'];
	$avg_q="select avg(speed) from proxy_history where ip=\"$ip\"";
	$avg_r=$con->query($avg_q);
	$avg_rr=$avg_r->fetch_array();
	$avg=get_speed($avg_rr[0]);
	if($stat=="Working")
	{
		$sp="<span class=\"glyphicon glyphicon-signal\"></span>";
		echo "<tr class=\"text-success\">";
	}
	else
	{
		$speed="--";
		$sp="<span class=\" glyphicon glyphicon-remove-circle\"></span>";
		echo "<tr class=\"text-danger\">";
	}
	echo "<td>$ip</td>";
	echo "<td>$port</td>";
	echo "<td><b>$sp $stat</b></td>";
	echo "<td>".min(100,intval(($olcount*100/$totcount)))." % </td>";
	echo "<td><b>$speed</b> <small>(avg. $avg)</small></td>";
//	echo "<td>$avg</td>";
	echo "</tr>";
}

?>
</tbody>
</table>
<p class="text-muted small">Last Updated:

<?php
//date_default_timezone_set("Asia/Kolkata"); 
echo gmdate("F j, Y, g:i a",$up+5.5*60*60);

?><br>
<br>

<div class="row"><span class="glyphicon glyphicon-warning-sign"></span> Some of the proxies may not work in hostel areas.</div>
<?php
getFooter();
 ?>
</p> 
<br/><br/>
</div>
</div>
</body>
</html>
