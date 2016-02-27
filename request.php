<?php
	session_start();
	include_once('function.php');
	
	$stat_ar=array('Already Present','Fulfilled','Downloading','Pending','Invalid');
	$categories=array();
	
	$mysqli=dbconnect();
	$res=$mysqli->query("select category from item_category");		//category dropdown
	while($arr=$res->fetch_array()){
		array_push($categories,$arr['category']); 
	}
	if(!isset($_SESSION['filters'])){	//first time access
		$_SESSION['filters']=array();
		$_SESSION['filters']['category']=$categories;
		$_SESSION['filters']['status']=$stat_ar;
	}
	//print_r($_SESSION['filters']['status']);
?>
<html>
<head>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/mainsite.css">
<link rel="stylesheet" href="tablesorter/css/theme.blue.css">
<link rel="stylesheet" href="chosen/chosen.min.css">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>DC @ MNNIT Allahabad	</title>
<style>
#inames option{
	height:40px;
	background-color:#F00;	
}
label.no-styl{
	font-weight:normal;
}
</style>
<script src="js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="tablesorter/js/jquery.tablesorter.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/search.js"></script>
<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(e) {
	
	<?php 
	foreach($_SESSION['filters']['category'] as $opt) { ?>
	$("#filter_category option[value='<?=$opt?>'").prop('selected',true);		//select filters 'category'
	<?php } ?>
	
	<?php 
	foreach($_SESSION['filters']['status'] as $opt) { ?>
	$("#filter_status option[value='<?=$opt?>'").prop('selected',true);			//select filters 'status'
	<?php } ?>
	
	$(".chosen-select").chosen();
	
	$(".chosen-select").change(function(e) {
        cats=$("#filter_category").val();
		stats=$("#filter_status").val();
		
		//console.log(cats);
		//console.log(stats);
		$.post("update_filter.php",{category:cats,status:stats},function(dt){
			//console.log(dt);
			location.reload();
		});
    });
    $("input[type=checkbox]").each(function() {
		$(this).attr('checked','checked');        
    });
	$("#reqtab").tablesorter();
	
	$("#sr").keyup(function(e) {
		//console.log(e);
		if(e.keyCode==13)
			get_req($("#sr").val());
       /* str=$(this).val();
		$("table tbody tr").each(function() {			
			if($(this).children("td:nth-child(2)").html().search(str)>=0)
				$(this).show();
			else
				$(this).hide();			
		});*/
    });
	function get_req(str){
		$.get("ajxreq.php?str="+str,function(dt){
			if(dt){
				$("table tbody").html(dt);
			}
		});
	}
	$("#srch").click(function(e) {
        str=$("#sr").val().toUpperCase();
		get_req(str);
    });
	
});


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
</head>
<body>
<?php
$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];

getHeader("request.php");
/*foreach($_POST as $a=>$b)
	$_POST[$a]=validate($b);
foreach($_GET as $a=>$b)
	$_GET[$a]=validate($b);*/

if(isset($_POST['submit'])){
	$errval=0;
	$name=$_POST['inames'];
	if(!isset($_POST['inames'])|| empty($_POST['inames']) ||!isset($_POST['category'])|| empty($_POST['category'])){
			//error message
			$errmsg="Invalid Response";
			$errval++;
			//die('invalid response');
	}
	else if(strlen($name)>128){
		$errmsg="Name cannot be more that 128 characters";
		$errval++;
	}
	else if(strlen($_POST['category'])>20){
		$errmsg="Category cannot be more that 20 characters";
		$errval++;
	}
	else {
		$chkq="select id from dcrequests where name like ?";// and category like '$_POST[category]'
		$stmt=$mysqli->prepare($chkq);
		$stmt->bind_param('s',$name);
		$stmt->execute();
			
		$stmt->bind_result($res);
		if($stmt->fetch()){
			
			$errval++;
			$errmsg="Request already exist!!!";
		}
		else {
			$insertq="INSERT INTO dcrequests (`category`, `name`, `status`,`ip`) VALUES (?, ?,'Pending',?);";
			$stmt=$mysqli->prepare($insertq);
			$stmt->bind_param('sss',$_POST['category'],$_POST['inames'],getIP());
			$stmt->execute() or die($stmt->error);
			
			$stmt->bind_result($res);
			
			//success message
		}
		
	}
}
?>
<h3 class="text-info col-md-offset-3"><span class="glyphicon glyphicon-question-sign"></span> Not available on DC++? Request here</h3>
<div id="request" class="col-md-6 col-md-offset-3">
<hr style="margin-top:-5px"/>
	<form class="form-horizontal" method="post" action="">
<fieldset>

<!-- Form Name -->
<?php
	if(isset($errval)){		//errors on requesting file
		if($errval>0){ ?>
			<span class="text-danger"><span class="glyphicon glyphicon-alert"></span><?=$errmsg?></span><br><br>
        <?php 
		}
		else { 				//proper file request 
		?>
        <span class="text-success"><span class=" glyphicon glyphicon-ok"></span> Request will be entertained soon.</span><br><br>
        <?php }
	}
?>
<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="category">Category</label>
  <div class="col-md-6">
    <select id="category" name="category" class="form-control">
	<?php
		//category dropdown
		foreach($categories as $cat){ ?>
      		<option value="<?=$cat?>"><?=$cat?></option>
   		<?php }
		?>
    </select>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="inames">Name</label>  
  <div class="col-md-6">
  <input list="inames" name="inames" onKeyUp="return search(this);" class="form-control input-md" required placeholder="e.g. Friends S01 720p">
  <datalist id="inames" >
  </datalist>
  <!--<span class="help-block">e.g. Friends S01 720p </span>-->  
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="inames">Torrent Link</label>  
  <div class="col-md-6">
  <input name="tlink" class="form-control input-md" placeholder="(optional)">
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4" style="margin:-5px 0 0 55px">
    <button id="submit" name="submit" class="btn btn-primary" type="submit">Request</button>
  </div>
</div>

</fieldset>
</form>
<hr style="margin-top:-15px"/>
</div>
<br />

<!-------------------------------------------------filters begin--------------------------------------------------------------->
<form class="form-inline col-md-10 col-md-offset-2" id="fil_cat" onSubmit="return false;">
	<div class="row">
  	<b class="col-md-1">Category:</b>		<!------------------category--------------->
      		<div class='form-group' style='margin-left:30px;'>
            	<select class="chosen-select" multiple style="width:50vw" id="filter_category">
                	<?php			
								
						foreach($categories as $cat) { ?>
                        	<option value="<?=$cat?>" ><?=$cat?></option>
                        <?php
						}
					?>
                </select>
            </div>
   </div><br>
  <div class="row">
    <b class="col-md-1">Status:</b>		<!------------------status--------------->
    <div class='form-group' style='margin-left:30px;'>
            	<select class="chosen-select" multiple style="width:50vw" id="filter_status">
                	<?php					
						foreach($stat_ar as $k) { ?>
                        	<option value="<?=$k?>" ><?=$k?></option>
                        <?php
						}
					?>
                </select>
            </div>
  </div><br>
  <div class="row">
      <b class="col-md-1">Search:</b>		<!------------------search--------------->
        <div class="input-group col-md-9" style="margin-left:30px">
          <input list='show_req' class='form-control' id='sr' placeholder="Press enter to search..."/>
          <span class="input-group-btn">
            <button class="btn btn-default" type="button" id="srch">Go!</button>
          </span>
        </div>
  </div>
</form>
<!-------------------------------------------------filters end--------------------------------------------------------------->

<div id="requesteditem" class="col-lg-10 col-lg-offset-1" style="margin-bottom:50px;">
	<table  class=" col-md-10 table  table-striped table-bordered table-hover tablesorter" id="reqtab" style="border-radius:5px 5px 0 0">
	<thead style="background-color:#203D77;color:#fff;"><th>Category</th><th width="65%">Name</th><th>Status</th></thead>
    <tbody>
	<?php
	 $where_cat="'".implode("','",$_SESSION['filters']['category'])."'";
	 $where_status="'".implode("','",$_SESSION['filters']['status'])."'";
	
	$qry="select count(*) from dcrequests where status!='r' and category in ($where_cat) and status in ($where_status)";
	$res=$mysqli->query($qry) or die ("Error counting entries"); 
	$pgcnt=$res->fetch_array();
	$pgcnt=$pgcnt[0];
	$pg=intval($_REQUEST['pg']);
	$showperpg=intval($_REQUEST['showperpg']);
	
	if(!$showperpg) $showperpg=25;	//default 25 entries/page
	
	$pgcnt=max(array(1,$pgcnt));
	if(!$pg||$pg<=0||$pg>=$pgcnt) $pg=1;
	
	
	$l1=($pg*$showperpg)  % $pgcnt;
	//$datasrch="<datalist id='show_req'>";
	$requestq="select * from dcrequests where status!='r' and category in ($where_cat) and status in ($where_status) order by timeofreq desc limit $l1,$showperpg";
	$res=$mysqli->query($requestq) or die($mysqli->error);
	while($req=$res->fetch_array()){
		//$datasrch.="<option value='$req[name]'></option>";
		echo "<tr id='$req[id]'><td class=\"col-md-2\">$req[category]</td><td class=\"col-md-6\">";
		if(!empty($req['torrent_link'])) 
			echo "<a href='$req[torrent_link]'>$req[name]</a>";
		else 
			echo $req['name'];
		if(!empty($req['link'])){
			echo " <a href='$req[link]'> <span class=\"glyphicon glyphicon-magnet\"></span> </a>";	
		}
		echo "</td><td class=\"col-md-4\" style=\"word-wrap: break-word;\">$req[status]";
		if(in_array($req['status'],array("Downloading","Fulfilled","Already Present")))
			echo " by <b>$req[fulfilledby]</b>";
		echo "</td></th>";
	}
	//$datasrch.="</datalist>";
	
	?>
	</tbody></table>
    
    <nav class="col-sm-12 col-sm-offset-0" style="text-align:center">
      <ul class="pagination pagination-sm">
        <li>
          <a href="request.php?pg=<?=abs($pg-1)?>&showperpg=<?=$showperpg?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        <?php
		$t=0;
		while(($t*$showperpg)<=$pgcnt){
		if($pg==$t)
			echo '<li class="active"><a href="request.php?pg='.$t.'&showperpg='.$showperpg.'">'.($t+1).'</a></li>';	
		else
        	echo '<li><a href="request.php?pg='.$t.'&showperpg='.$showperpg.'">'.($t+1).'</a></li>';
		$t++;
		}
		?>
        <li>
          <a href="request.php?pg=<?=($pg+1)?>&showperpg=<?=$showperpg?>" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>
</div>
<br>
<br>
<br>
<br>

<?php 
echo "<datalist id='show_req'></datalist>";
getFooter(); ?>
</body>
</html>