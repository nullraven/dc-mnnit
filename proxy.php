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
	$("#predictor").click(function(e) {
        e.stopPropagation();
    });
});
</script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>DC @ MNNIT Allahabad	</title>

</head>
<body onLoad="setInterval('window.location.reload()', 120000);">
<div id="outerpredictor"  onclick="displaynone()">
    <div id="predictor">
       <!--<div id="cross" >X</div>-->
        <h4 style="margin:2px" align="center">Speed Curve</h4>
        <div id="content">
        
            <div class="demo-container">
                <div id="placeholder" class="demo-placeholder"></div>
            </div>
        
         
        
        </div>
    </div>
</div>


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
<h5 class="col-md-offset-2 col-md-6"><img src="new.gif" /> Click on any proxy to see its speed curve in last 24 hours! :D (click anywhere outside to close)</h5>

<div class="container col-md-7 col-md-offset-2">
	<table class="table table-bordered table-hover tablesorter proxtab" id="hubtab">
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
	$addr=$ip.":".$port;
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
		echo "<tr class=\"text-success\" onClick='displaypre(\"$ip\",\"$port\");' style='cursor:pointer'>";
	}
	else
	{
		$speed="--";
		$sp="<span class=\" glyphicon glyphicon-remove-circle\"></span>";
		echo "<tr class=\"text-danger\" onClick='displaypre(\"$ip\",\"$port\");' style='cursor:pointer'>";
	}
	echo "<td>$ip</td>";
	echo "<td>$port</td>";
	echo "<td><b>$sp $stat</b></td>";
	echo "<td>".min(100,intval(($olcount*100/$totcount)))." % </td>";
	echo "<td><b>$speed</b> <small>(avg. $avg)</small></td>";
//	echo "<td>$avg</td>";
	echo "</tr>";
	
//____________________________________________________GRAPH____________________________________________________________________________	
	$avg_q="select speed,hour(timestamp) as h,minute(timestamp) as m from proxy_history where ip='$ip'  order by timestamp desc limit 0,96 ";//and timestampdiff(MINUTE,timestamp,now()) % 2 = 0
	$avg_r=$con->query($avg_q);
	//echo "<h2>$ip:$port</h2>";
	//echo '<table border="1"><tr><th>speed</th><th>time</th></tr>';
	$i=0;
	while($avg_rr=$avg_r->fetch_array())
	{
		//echo "<tr><td>$avg_rr[0]</td><td>$avg_rr[1]</td></tr>";
		if($avg_rr[0]>(13*1024.0))
			{
				$avg_rr[0]=rand(0,100);
				
			}
		$speed_ar[$addr][$i]=intval($avg_rr[0]);
		$tim_ar[$addr][$i++]=sprintf("%02s",$avg_rr[1]).":".sprintf("%02s",$avg_rr[2]);
	}
	$max_speed[$addr]=max($speed_ar[$addr]);
	//echo '</table>';
}
$json=json_encode($speed_ar);
$max_speed=json_encode($max_speed);
$tim_ar=json_encode($tim_ar);
//$tim_ar;
  
//echo $json;
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


<script language="javascript" type="text/javascript" src="flot/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="flot/jquery.flot.symbol.js"></script>
<script language="javascript" type="text/javascript" src="flot/jquery.flot.tooltip.js"></script>
<script type="text/javascript" src="flot/jquery.flot.axislabels.js"></script>
<script>
/*$(document).ready(function(e) {
    $("#hubtab tr").click(function(e) {
        var ip=$(this).children("td:first");
		var port=$(this).children("td:nth-child(2)");
		alert(ip+":"+port);
    });
});*/
var cur_addr;
function displaypre(ip,port){
	//alert(ip+":"+port);
	cur_addr=ip+":"+port;
	plot(ip,port);
	//console.log(tim_ar[cur_addr]);
	//console.log(data[cur_addr]);
	document.getElementById('outerpredictor').style.opacity=1;	
	document.getElementById('outerpredictor').style.pointerEvents="auto";
	$("#predictor h4").html("Speed in last 24 hours of "+cur_addr);
}
function displaynone(){
	document.getElementById('outerpredictor').style.opacity=0;	
	document.getElementById('outerpredictor').style.pointerEvents="none";
}
var data = <?php echo $json; ?>,max_speed=<?php echo $max_speed; ?>,tim_ar=<?php echo $tim_ar; ?>,
		totalPoints = 96;
	
function plot(ip,port) {
	
	
	function getData() {
		// Zip the generated y values with the x values
		
		var res = [];
		for (var i = 95; i >=0; --i) {
			res.push([-i, data[ip+":"+port][i]])
		}

		return res;
	}
	var plot = $.plot("#placeholder", [ getData() ], {
		series: {
			shadowSize: 3,	// Drawing is faster without shadows
			//points:{show:true},
			//lines:{show:true},
			bars: {show: true}
		},
		bars: {
			align: "center",
			barWidth: 1
		},
		yaxis: {
			axisLabel: "Speed (KB/s)",
			min: 0,
			max: max_speed[ip+":"+port]
		},
		xaxis: {
			axisLabel: "Time (15 minutes)",
			show: true
		},
		grid: {
			hoverable: true,
			borderWidth: 3,
			mouseActiveRadius: 50,
			backgroundColor: { colors: ["#ffffff", "#EDF5FF"] },
			axisMargin: 20
		}
		/*tooltip:true,
		
		tooltipOpts: {
			id:             "tooltip"                  //"flotTip"
			//content:        string or function      //"%s | X: %x | Y: %y"
			
			
			onHover:        function(flotItem, $tooltipEl)
		}*/
		
	});
	plot.setData([getData()]);

	// Since the axes don't change, we don't need to call plot.setupGrid()

	plot.draw();
	//$("#placeholder").UseTooltip();
	$("#placeholder").bind("plothover", function (event, pos, item) {
        if (item) {
            if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                $("#tooltip").remove();
                
                var x = item.datapoint[0];
                var y = item.datapoint[1];
                //var date = new Date(x);
                var color = item.series.color;

                showTooltip(item.pageX, item.pageY, color,         
                            "Time (24hr) : <b>"+(tim_ar[cur_addr][-x]) +
                            "</b> <br/>Speed : <strong>" + y + "</strong> (KB/s)");
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
	//setTimeout(update, updateInterval);
	// Add the Flot version string to the footer

	//$("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
}

var previousPoint = null, previousLabel = null;
/*$.fn.UseTooltip = function () {
    
};*/

function showTooltip(x, y, color, contents) {
    $('<div id="tooltip">' + contents + '</div>').css({
        position: 'absolute',
        display: 'none',
        top: y - 40,
        left: x - 120,
        border: '2px solid ' + color,
        padding: '3px',
        'font-size': '9px',
        'border-radius': '5px',
        'background-color': '#fff',
        'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
        opacity: 0.9
    }).appendTo("#predictor").fadeIn(200);
}
</script>


</html>
