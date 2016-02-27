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
<script src="js/chart.js"></script>
<script>
$(document).ready(function(e) {
    $("#hubtab").tablesorter({sortList: [[2,1],[4,1],[3,1]]});
});
$(document).ready(function() {
	var c1 = document.getElementById("c1");
	var parent = document.getElementById("p1");
	c1.width = parent.offsetWidth - 40;
	c1.height = parent.offsetHeight - 40;

	var data = {
	  labels : [<?php global $ip_arr; echo "$ip_arr[0]";  ?>, "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Apr", "Sep", "Oct", "Nov", "Dec"],
	  datasets : [
	    {
	      fillColor : "#59BAFF",
	      strokeColor : "#59BAFF",
	      data : [10, 20, 30, 40, 60, 70, 130, 103, 107, 91, 113, 100 ]
	    }
	  ]
	}
	var max = Math.max.apply(Math,data.datasets[0].data);
	maxl = (max/10).toFixed(2);

	var options = {
  scaleBeginAtZero : true,
  scaleShowGridLines : false,		
  scaleOverlay : false,
  scaleOverride : true,
  scaleSteps : 10,
  scaleStepWidth : maxl,
  scaleStartValue : 0,
  scaleLineColor : "rgba(0,0,0,.1)",	
  scaleLineWidth : 0,	
  scaleShowLabels : true,
  scaleFontFamily : "'Arial'",	
  scaleFontSize : 12,	
  scaleFontStyle : "normal",	
  scaleFontColor : "#000",	
  scaleShowGridLines : false,
  scaleGridLineColor : "rgba(0,0,0,.05)",
  scaleGridLineWidth : 1,		
  barShowStroke : false,
  barStrokeWidth : 0,
  barValueSpacing : 2,
  barDatasetSpacing : 0,
  animation : true,
  animationSteps : 60,
  animationEasing : "easeOutQuart",
  onAnimationComplete : null

}

	new Chart(c1.getContext("2d")).Bar(data, options);
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

if(!isset($_SESSION['count']) || (date()-$_SESSION['opentime'])>15*60){
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
<h4 class="col-md-offset-2 col-md-6 text-info"><span class="glyphicon glyphicon-info-sign"></span> Working proxies and approx. browsing speed.</h4><br><br>
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
$ip_arr=array();
$speed_arr=array();
//$totcount=$row[1];
$query="select * from proxy order by status desc,ip asc";
$res=$con->query($query);
while($row=$res->fetch_array())
{
	$port=$row['port'];
	$ip_arr[]=$ip=$row['ip'];
	//$name=$row['name'];
	$stat=$row['status'];
	$olcount=$row['olcount'];
	$totcount=$row['totcount'];
	$speed_arr[]=$speed=$row['speed'];
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

<div class="chart" id="p1">
  <canvas id="c1" width="872" height="560"></canvas>
</div>  

<?php
getFooter();
 ?>
</p> 
<br/><br/>
</div>
</div>
</body>
</html>
