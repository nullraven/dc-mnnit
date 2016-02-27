<html>
<head>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/mainsite.css">

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>DC @ MNNIT Allahabad	</title>
<script src="js/jquery-1.11.2.js"></script>
<script src="js/bootstrap.min.js"></script>
</head>
<body>
<?php
include_once('function.php');
session_start();
getHeader("addhub.php");
$con=dbconnect();
if(isset($_POST['submitbtn'])){
	foreach($_POST as $a=>$b)
		$_POST[$a]=validate($b);
	$errmsg="<span class=\"text-danger\">"; 
	$errval=0;
	if(!isset($_POST['hubname']) || empty($_POST['hubname'])){
		$errmsg.="<span class=\"glyphicon glyphicon-alert\"></span> hub Name cannot be empty<br>";
		$errval++;
	}
	else
	{
		if(strlen($_POST['hubname'])>50)
		{
				$errmsg.="<span class=\"glyphicon glyphicon-alert\"></span> hub Name length cannot be > 50<br>";
				$errval++;
		}
	}
	if(!isset($_POST['hubip']) || empty($_POST['hubip'])){
		$errmsg.="<span class=\"glyphicon glyphicon-alert\"></span> hub IP cannot be empty<br>";
		$errval++;
	}
	else
	{
		if(!filter_var($_POST['hubip'], FILTER_VALIDATE_IP))
		{
				$errmsg.="<span class=\"glyphicon glyphicon-alert\"></span> Incorrect IP Address<br>";
				$errval++;
	
		}
	}
	if(!isset($_POST['hubport']) || empty($_POST['hubport'])){
		$errmsg.="<span class=\"glyphicon glyphicon-alert\"></span> hub Port cannot be empty<br>";
		$errval++;
	}
	else
	{
		if(!is_numeric($_POST['hubport']) || ($_POST['hubport']<1 || $_POST['hubport']>65536))
		{
		$errmsg.="<span class=\"glyphicon glyphicon-alert\"></span> Invalid Port<br>";
		$errval++;
	
		}
	}
	if(!isset($_POST['uname']) || empty($_POST['uname'])){
		$errmsg.="<span class=\"glyphicon glyphicon-alert\"></span> userName cannot be empty<br>";
		$errval++;
	}
	else
	{
		if(strlen($_POST['uname'])>50)
		{
				$errmsg.="<span class=\"glyphicon glyphicon-alert\"></span> userName length cannot be > 50<br>";
				$errval++;
		}
		else{
			$chkunameq="select * from addrequest where username like '$_POST[uname]'";
			$res=$con->query($chkunameq);
			if($res->num_rows>0){
				$errmsg.="<span class=\"glyphicon glyphicon-alert\"></span> userName already exists!! try another.<br>";
				$errval++;
			}
		}
	}
	if(!isset($_POST['passwd']) || empty($_POST['passwd'])){
		$errmsg.="<span class=\"glyphicon glyphicon-alert\"></span> password cannot be empty<br>";
		$errval++;
	}
	else
	{
		$passlen=strlen($_POST['passwd']);
		if($passlen>50 || $passlen<8)
		{
				$errmsg.="<span class=\"glyphicon glyphicon-alert\"></span> password length cannot be &gt; 50 and &lt; 8 <br>";
				$errval++;
		}
	}

	$errmsg.="</span>";
}
?>
<!--<nav class="navbar navbar-inverse ">
<div class="navbar-header">
      <a class="navbar-brand" href="index.php">MNNIT DC</a>
</div>
<div>
      <ul class="nav navbar-nav">
         <li><a href="./index.php">HUBS Status</a></li>
      	 <li class="active"><a href="#">Add new HUB</a></li>
         <li><a href="./request.new.php">Request File</a></li>
         	<li><a href="info.php">Info</a></li>
      	
   </ul>
    </div>
  </div>
</nav>-->
<div class="well col-md-6 col-md-offset-3">
<?php
if(isset($errval)){
	if($errval==0){
		$name=$_POST['hubname'];
		$ip=$_POST['hubip'];
		$port=$_POST['hubport'];
		$uname=$_POST['uname'];
		$passwd=$_POST['passwd'];
		$err=0;
		$ch_q="select * from hubs_info where ip=\"$ip\" and port=\"$port\"";
		$ch_r=$con->query($ch_q);
		if($ch_r->num_rows)
		{
			echo "<span class=\"text-danger\"><span class=\"glyphicon glyphicon-alert\"></span> hub already exists.<br></span></span>";			
			$err=1;
		}
		$ch_q="select * from addrequest where ip=\"$ip\" and port=\"$port\"";
		$ch_r=$con->query($ch_q);
		if($ch_r->num_rows)
		{
			echo "<span class=\"text-danger\"><span class=\"glyphicon glyphicon-alert\"></span> hub already requested.<br></span></span>";			
			$err=1;
		}
		if(!$err)
		{
			$uip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			$ti=time();
			$status="WAITING";
			$ow=$_POST['ownername'];
			$query="insert into addrequest (`name`,`ip`,`port`,`owner`,`time`,`status`,`username`,`password`,`remark`) VALUES (\"$name\",\"$ip\",\"$port\",\"$ow\",\"$ti\",\"$status\",\"$uname\",\"$passwd\",\"$uip\")";
			$res=$con->query($query);
			if($res)
			{
					echo "<span class=\"text-success\"><span class=\"glyphicon glyphicon-ok\"></span> hub Successfully added!! It will be added to the main list shortly. :)</span>";
	
			}
		}
		//add in db
	
	}
	else {
		echo $errmsg;	
	}
	
}

 ?>
	<form class="form-horizontal" method="post" action="">
<fieldset>

<!-- Form Name -->
<legend>add New hub</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="hubname">hub Name</label>  
  <div class="col-md-5">
  <input id="hubname" name="hubname" type="text" placeholder="Enter hub Name" class="form-control input-md" riequired="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="hubip">hub IP</label>  
  <div class="col-md-5">
  <input id="hubip" name="hubip" type="text" placeholder="IP address of hub" class="form-control input-md" irequired="">
   <span class="help-block">e.g. 172.31.38.38</span> 
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="hubport">hub Port</label>  
  <div class="col-md-2">
  <input id="hubport" name="hubport" type="text" placeholder="Port" class="form-control input-md" irequired="">
  <span class="help-block">e.g. 1111</span>  
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="ownername">hub Owner Name</label>  
  <div class="col-md-5">
  <input id="ownername" name="ownername" type="text" placeholder="Name" class="form-control input-md">
  <span class="help-block">This name will not be revealed</span>  
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="uname">userName</label>  
  <div class="col-md-5">
  <input id="uname" name="uname" type="text" placeholder="username" class="form-control input-md">
  <span class="help-block">This name will not be revealed. For managing purpose</span>  
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="passwd">password</label>  
  <div class="col-md-5">
  <input id="passwd" name="passwd" type="password" placeholder="Password" class="form-control input-md">
  <span class="help-block">Atleast 6 characters long</span>  
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submitbtn"></label>
  <div class="col-md-4">
    <button id="submitbtn" name="submitbtn" class="btn btn-primary">Submit</button>
  </div>
</div>

</fieldset>
</form>
<?php
 getFooter();
?>
</div>
</body>
</html>