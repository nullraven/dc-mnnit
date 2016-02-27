<html>
<head>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/mainsite.css">

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>DC @ MNNIT Allahabad	</title>

<script src="js/bootstrap.min.js"></script>
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
		$errmsg.="<span class=\"glyphicon glyphicon-alert\"></span> Port cannot be empty<br>";
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
		$ip=$_POST['hubip'];
		$port=$_POST['hubport'];
		
		$err=0;
		$ch_q="select * from proxy where ip=\"$ip\" and port=\"$port\"";
		$ch_r=$con->query($ch_q);
		if($ch_r->num_rows)
		{
			echo "<span class=\"text-danger\"><span class=\"glyphicon glyphicon-alert\"></span> proxy already exists.<br></span></span>";			
			$err=1;
		}
		
		if(!$err)
		{
			$uip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			$ti=time();
			$status="Working";
			
			$query="insert into proxy (`ip`,`port`,`status`) VALUES (\"$ip\",\"$port\",\"$status\")";
			$res=$con->query($query);
			if($res)
			{
					echo "<span class=\"text-success\"><span class=\"glyphicon glyphicon-ok\"></span> Proxy Successfully added!!</span>";
	
			}
		}
		//add in db
	
	}
	else {
		echo $errmsg;	
	}
	
}

 
 
if(!isset($_SESSION['admin']))
{
	if(isset($_POST['user']) && isset($_POST['pass'])){
		$user=trim($con->real_escape_string(htmlentities($_POST['user'])));
		$pass=trim($con->real_escape_string(htmlentities($_POST['pass'])));
		if($user==='admin1' && $pass==='@dm!n@pr0xy'){
				$_SESSION['admin']='1';
				header('location:addproxy.php');
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
<form class="form-horizontal" method="post" action="">
<fieldset>

<!-- Form Name -->
<legend>Add New Proxy</legend>


<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="hubip">Proxy IP</label>  
  <div class="col-md-8">
  <input id="hubip" name="hubip" type="text" placeholder="IP address " class="form-control input-md" irequired="">
   <span class="help-block">e.g. 172.31.38.38</span> 
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="hubport">Proxy Port</label>  
  <div class="col-md-4">
  <input id="hubport" name="hubport" type="text" placeholder="Port" class="form-control input-md" irequired="">
  <span class="help-block">e.g. 3128</span>  
  </div>
</div>

<!-- Text input-->

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
}
 getFooter();
?>
</div>
</body>
</html>
