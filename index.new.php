<html>
<head>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/mainsite.css">
<script src="js/jquery-1.11.2.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/search.js"></script>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>MNNIT, Allahabad	</title>

<script src="js/bootstrap.min.js"></script>
</head>
<body>
<!--<nav class="navbar navbar-inverse ">
<div class="navbar-header">
      <a class="navbar-brand" href="index.php">MNNIT DC</a>
</div>
<div>
      <ul class="nav navbar-nav">
        <li class="active"><a href="index.php"><span class="glyphicon glyphicon-list-alt"></span> HUBS Status</a></li>
      	  <li><a href="./addhub.php"><span class="glyphicon glyphicon-plus-sign"></span> Add new HUB</a></li>
      	  <li><a href="./request.php"><span class="glyphicon glyphicon-cloud-download"></span> Request File</a></li>
      	<li ><a href="info.php"><span class="glyphicon glyphicon-info-sign"></span> Info</a></li>
      	
      </ul>
    </div>
  </div>
</nav><br>-->
<?php 
include_once('function.php');
if(!isset($_SESSION))
	session_start();

if(!isset($_SESSION['count']) || (date()-$_SESSION['opentime'])>15*60){
	$_SESSION['count']=0;
	$_SESSION['opentime']=date();
}
else $_SESSION['count']++;
getHeader("index.php");
?>
<h4 class="col-md-offset-3 col-md-6 text-info"><span class="glyphicon glyphicon-info-sign"></span> Status of presently known hubs</h4><br><br>


<div class="container col-md-6 col-md-offset-3">
	<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr  bgcolor="#EEE">
    <th>Address</th>
    <th>Name</th>
    <th>Status</th>
    
    </tr>
    </thead>
<?php 
if($_SESSION['count']==0){
	$content=date("H:i:s d/m/y")." ".json_encode($_SERVER)."\n";
	file_put_contents("./downlog",$content,FILE_APPEND);
}
include_once('function.php');
$con=dbconnect();
$query="select * from hubs_info order by status desc";
$res=$con->query($query);
while($row=$res->fetch_array())
{
	$port=$row['port'];
	$ip=$row['ip'];
	$name=$row['name'];
	$stat=$row['status'];
//exec("nmap -p $port $ip -Pn",$arr);

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
	echo "<td>$ip<b> : </b>$port</td>";
	echo "<td>$name</td>";
	echo "<td><b>$sp $stat</b></td>";
	
	echo "</tr>";
}

?>
</table>
<p class="text-muted small">Last Updated:

<?php
$query="select * from last_update";
$res=$con->query($query);
$row=$res->fetch_array();
$up=$row[0];
//date_default_timezone_set("Asia/Kolkata"); 
echo gmdate("F j, Y, g:i a",$up+5.5*60*60);
getFooter();
 ?>
 
</div>
</div>
</body>
</html>