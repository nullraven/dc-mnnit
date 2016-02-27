<?php
	require_once("../include/function.php");
?>

<html>
<?=get_head()?>

<script type="text/javascript">
$(document).ready(function(e) {
	
    $("input[type=checkbox]").each(function() {
		$(this).attr('checked','checked');        
    });
	$("#reqtab").tablesorter();
	
});
function fun1(sid)
{
	var st=document.getElementById('stat_'+sid).value;
	var magnet=document.getElementById('mag_'+sid).value;
	if(confirm('Are you sure you want to do this?'))
	$.post('update_req.php',{rid:sid,stat:st,lnk:magnet},function(data){
		alert(data);	
	});
}
function fun2(sid)
{
	opv=document.getElementById('stat_'+sid).value;
	//alert(opv);
	document.getElementById('mag_'+sid).disabled=(opv=='Fulfilled'||opv=='Already Present')?false:true;
}
function fun3(cat)
{
	if($("#c_"+cat).is(':checked'))
	{
		fl=true;
		//$("#c_"+cat).attr('checked','checked');
		//alert('check');
	}
	else{
		fl=false;//$("#c_"+cat).attr('checked','checked');
		//alert('uncheck');
	}
	$("table tbody tr").each(function() {
		//alert(cat+" -> "+$(this).children("td:first").html());
        if($(this).children("td:first").html()===cat){
			if(fl)
				$(this).show();
			else
				$(this).hide();
		}
    });
}
</script>



<style>
fieldset{
	width:300px;
	text-align:center;
	margin:10px auto;
}
fieldset ul li{
	list-style-type:none;
	margin-top:10px;
}
.form-control{
	
}
</style>

<body>

<?php 
$con=dbconnect();
getHeader("admin.php");
if(!isset($_SESSION['admin']))
{
	if(isset($_POST['user']) && isset($_POST['pass'])){
		$user=trim($con->real_escape_string(htmlentities($_POST['user'])));
		$pass=trim($con->real_escape_string(htmlentities($_POST['pass'])));
		$logqry="select passw,status from admin where usr='".$user."'";
		$res=$con->query($logqry);
		if($pwd=$res->fetch_array())
		{
			if($pwd['passw']==$pass)
			{
				$_SESSION['user']=$_POST['user'];
				$_SESSION['admin']=1;
				$_SESSION['status']=htmlentities($pwd['status']);
				/*echo '<script>alert("Logged in Succesfully");</script>';*/
				palert("Login success!","admin.php");
			}
			else 
			{
				echo '<script>alert("Invalid Username or password!");</script>';
				//header('location:admin.php');
			}
		}
	else
		{
			echo '<script>alert("Invalid Username or password!");</script>';
			//header('location:admin.php');
		}
	}
?>
<fieldset>
<legend>Admin Login</legend>
<form action="#" method="post" >
<ul>
<li>
	<input type="text" name="user" placeholder="Username" class="form-control" required/>
</li>
<li>
	<input type="password" name="pass" placeholder="Password" class="form-control" required/>
</li>
<li>
	<input type="submit" name="submit" value="Login" class="btn btn-primary"/>
</li>
</ul>
</form>
</fieldset>
<?php	
}
else{ 
?>
	
<h4 class="col-md-offset-3 col-md-6 text-info"><span class="glyphicon glyphicon-info-sign"></span> All Requests (click on headers to sort ;) )</h4><br><br>
<h5 class="col-md-offset-3 col-md-6 text-info"><span class="glyphicon glyphicon-info-sign"></span>Total Requests fulfilled/being fulfilled by you: 
<?php
	$cqry="select count(*) from dcrequests where fulfilledby like '".$_SESSION['user']."' and status in ('Downloading','Fulfilled')";
	$res=$con->query($cqry) or die("Server facing technical problems... :(");
	if($res=$res->fetch_array())
		echo $res[0];
	else
		echo 'N/A';
?>
</h5><br/><br/><br/><br/>

<form class="form-inline" style="float:left;" id="fil_cat">
  
  	<?php
    $res=$con->query("select * from item_category");
		while($arr=$res->fetch_array())
      		echo "<div class='form-group' style='margin-left:30px'><input type='checkbox' class='form-control' id='c_$arr[category]' value='$arr[category] ' onclick='fun3(\"$arr[category]\")' ><label for='c_$arr[category]'>&nbsp;&nbsp;$arr[category] </label></div>";
	?>
  
</form>
	<div id="requesteditem" class="col-lg-12" style="margin-bottom:50px;">
        <table class="table  table-striped table-bordered table-condensed tablesorter" id="reqtab"><thead style="background-color:#29348B;color:#fff">
        <!--<tr >--><th>Category</th><th>Name</th><th>Status</th><th>Magnet Link</th><th width="5px">Change</th></thead><tbody>
        <?php
		
        $requestq="select * from dcrequests where status in ('Pending','Invalid Request') or (status like 'Downloading' and fulfilledby like '".htmlentities($_SESSION['user'])."') order by timeofreq desc";
		//echo $requestq;
        $res=$con->query($requestq) or die($con->error);
		$star=array('Pending','Downloading','Fulfilled','Already Present','Invalid Request');
        while($req=$res->fetch_array()){
			$st=$req['status'];
			
			$selbox='<select id="stat_'.$req['id'].'" class="form-control" onChange="fun2('.$req['id'].')" style="font-size:12px">';
			foreach($star as $i){
				$sl=($req['status']==$i)?'selected':'';
				$selbox.='<option value="'.$i.'" '.$sl.'>'.$i.'</option>';
			}
			$st.='</select>';
            echo "<tr><td>$req[category]</td><td>$req[name] ".($reg['torrent_link']?"<a href='' taget='_blank'>Torrent link</a>":"")."</td><td class='tdsel'>$selbox</td><td><input type='text' id='mag_".$req['id']."' class='form-control' value='".$req['link']."' disabled/></td><td><button type='submit' class='btn btn-success' onclick='fun1(".$req['id'].")'><span class='glyphicon glyphicon-check'></span></button></td></tr>";
        }
        ?></tbody>
        </table>
    </div>

<?php }
getFooter();
?>
</body>
</html>