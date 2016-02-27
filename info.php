<html>
<head>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<link rel="stylesheet" href="css/mainsite.css">

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>DC @ MNNIT Allahabad </title>
<script src="js/jquery-1.11.2.js"></script>
<script src="js/bootstrap.min.js"></script>
</head>
<body>
<!--<nav class="navbar navbar-inverse ">
<div class="navbar-header">
      <a class="navbar-brand" href="index.php">MNNIT DC</a>
</div>
<div>
      <ul class="nav navbar-nav">
        <li><a href="index.php">HUBS Status</a></li>
      	  <li><a href="./addhub.php">Add new HUB</a></li>
      	  <li><a href="./request.new.php">Request File</a></li>
      		<li class="active"><a href="info.php">Info</a></li>
      	
      </ul>
    </div>
  </div>
</nav>-->
<?php
include_once('function.php');
session_start();
getHeader("info.php");

?>
<br>
<h4 class="col-md-offset-2 col-md-6 text-info"><span class="glyphicon glyphicon-info-sign"></span> Important Information</h4><br><br>


<div class="container col-md-6 col-md-offset-2">
<ul>
<li>You can directly add hub directly to your DC++ by just clicking on the link.</li>
<li><!--<img src="new.gif" />-->You can click on table headers to sort them. Shift+click to sort multiple columns :)</li>
<li>The status of HUBS are updated periodically.</li>
<li>If you are a hub admin, you can add your hub directly.</li>
<li>The hub will be shown in the list after approval.</li>
<li>If any file which is not available on DC++ can be requested and be fulfilled by the hub admins. </li>
<li>The search box for requesting file is synchronized with IMDb server, so better if you choose name from the hints during search. </li>
<li><!--<img src="new.gif" />-->A Hub admin who has set status to 'Downloading' will have 24 hours to change status from 'Downloading' to 'Fulfilled', or status will revert to 'Pending'.</li>
<li><!--<img src="new.gif" />-->All 'Invalid Requests' will be deleted after 24 hours unless changed by any Hub Admin</li>
<li> pm suggestions/reviews...constuctive/destructive... to -Bloodraven- , prim or kejriwal on DC++ ;) <!--<img src="new.gif" />--></li>
</ul>	

</div>
<?php
getFooter();
?>
</body>
</html>