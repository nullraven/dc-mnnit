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
error_reporting(E_ALL);
include_once('function.php');
if(!isset($_SESSION))
	session_start();

/*if(!isset($_SESSION['count']) || (date()-$_SESSION['opentime'])>5*60){
	$_SESSION['count']=0;
	$_SESSION['opentime']=date();
}
else $_SESSION['count']++;
if(true ){//$_SESSION['count']==0
	echo $content=date("H:i:s d/m/y")."~".$_SERVER['HTTP_USER_AGENT'].'~'.$_SERVER['REMOTE_ADDR']."\n";
	file_put_contents("downlog.txt",$content,FILE_APPEND|LOCK_EX); 
}*/
getHeader("index.php");
?>
<h4 class="col-md-offset-2 col-md-6 text-success"><span class="glyphicon glyphicon-info-sign"></span> Status of Hubs. </h4><br><br>


<div class="container col-md-7 col-md-offset-2">
	<table class="table table-bordered table-hover tablesorter" id="hubtab" >
    <thead class="text-center">
    <tr >
    <th>Address</th>
    <th>Hub Name</th>
    <th>Status</th>
	<th>% Online</th>
    <th>Fulfilled/Present</th>
    </tr>
    </thead><tbody>
<?php 
include_once('function.php');
$con=dbconnect();
$query="select * from last_update";
$res=$con->query($query);
$row=$res->fetch_array();
$up=$row[0];
//$totcount=$row[1];
$query="select * from hubs_info order by status desc";
$res=$con->query($query);
while($row=$res->fetch_array())
{
	$port=$row['port'];
	$ip=$row['ip'];
	$name=$row['name'];
	$stat=$row['status'];
	$olcount=$row['olcount'];
	$totcount=$row['totcount'];
	$cqry="select count(*) from dcrequests where fulfilledby in(select user from in_hub where hub like '$name' ) and status in ('Downloading','Fulfilled','Already Present')";
	$cres=$con->query($cqry) or die("Server facing technical problems... :(");
	if($cres=$cres->fetch_array())
		$cnt=$cres[0];
	else 
		$cnt=0;
	if($stat=="ONLINE")
	{
		$sp="<span class=\"glyphicon glyphicon-signal\"></span>";
		echo "<tr class=\"text-success\">";
	}
	else
	{
		$sp="<span class=\" glyphicon glyphicon-remove-circle\"></span>";
		echo "<tr class=\"text-danger\">";
	}
	echo "<td><a href='magnet:xs=dchub://$ip:$port'>$ip<b> : </b>$port</a></td>";
	echo "<td>$name</td>";
	echo "<td><b>$sp $stat</b></td>";
	echo "<td>".min(100,intval(($olcount*100/$totcount)))." % </td>";
	echo "<td><b>$cnt</b></td>";
	echo "</tr>";
}

?>
</tbody>
</table>
<p class="text-muted small">Last Updated:

<?php
//date_default_timezone_set("Asia/Kolkata"); 
echo gmdate("F j, Y, g:i a",$up+5.5*60*60);
getFooter();
 ?>
</p> 
<br/><br/>
</div>
</div>
</body>
</html>