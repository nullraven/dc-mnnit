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
<script>
function htmlentities(string, quote_style, charset, double_encode) {
  // discuss at: http://phpjs.org/functions/htmlentities/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: nobbler
  // improved by: Jack
  // improved by: Rafał Kukawski (http://blog.kukawski.pl)
  // improved by: Dj (http://phpjs.org/functions/htmlentities:425#comment_134018)
  // bugfixed by: Onno Marsman
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  // input by: Ratheous
  // depends on: get_html_translation_table
  // note: function is compatible with PHP 5.2 and older
  // example 1: htmlentities('Kevin & van Zonneveld');
  // returns 1: 'Kevin &amp; van Zonneveld'
  // example 2: htmlentities("foo'bar","ENT_QUOTES");
  // returns 2: 'foo&#039;bar'
  var hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style),
      symbol = '';

  string = string == null ? '' : string + '';

  if (!hash_map) {
    return false;
  }

  if (quote_style && quote_style === 'ENT_QUOTES') {
    hash_map["'"] = '&#039;';
  }

  double_encode = double_encode == null || !!double_encode;

  var regex = new RegExp("&(?:#\\d+|#x[\\da-f]+|[a-zA-Z][\\da-z]*);|[" +
                Object.keys(hash_map)
                  .join("")
                  // replace regexp special chars
                  .replace(/([()[\]{}\-.*+?^$|\/\\])/g, "\\$1")
                + "]",
              "g");

  return string.replace(regex, function (ent) {
    if (ent.length > 1) {
      return double_encode ? hash_map["&"] + ent.substr(1) : ent;
    }

    return hash_map[ent];
  });
}
</script>
</head>
<body>
<?php

include_once('function.php');
session_start();
getHeader("addhub.php");
$con=dbconnect();
//if(getIP()!="172.31.9.25" && getIP()!="172.31.73.2")
	//die();

if(isset($_SESSION['bakar']))
{
	// a bakar is already running on this session.
}
else
{
	//create a bakar id, insert into db, mark it open and search for another ideal bakarbaaz.
	$_SESSION['bakid']=$bakid=generateRandomString(30); // jab tak is page par hai... ye bakar id chalegi......
	$ip=getIP();
	$ins_q="insert into bakar_clients (`randid`,`status`,`remark`) VALUES (\"$bakid\",0,\"$ip\")";
	$con->query($ins_q);
	$sel_q="select * from bakar_clients where randid=\"$bakid\"";
	$sel_r=$con->query($sel_q);
	$cid=$sel_r->fetch_array();
	$cid=$cid['cid'];
	$_SESSION['cid']=$cid;
	//ab ye cid id wala banda chat karne ke liye ready hai......
	//find anothr client ready to chat.....
	// ladka hai to pref. girls ip  baad ke liye extension.. :P abhi jo pehla mile use pel do...
	$query="start transaction";
	$con->query($query);
	$query="select * from bakar_clients where status=0 and cid!=$cid";
	$res=$con->query($query);
	if($res->num_rows ==0)
	{
		?>
        <script>
		$(document).ready(function(e) {
			var cid1=<?php echo $cid;  ?>;
		
		$.post("is_chat_started.php",
			{
				cid1: cid1
			},
			 function(data, status){
				 if(status=="success")
				 {
					var c2 = jQuery.parseJSON(data);
				 	var chatid=c2[1];
					var cid2=c2[0];
					$("#cid1").val(cid1);
					$("#cid2").val(cid2);
					$("#chatid").val(chatid);
					alert("cid:"+cid1+"cid2"+ cid2+ "chatid" +chatid);
				 }
			});
	
		});
		</script>
        <?php
}
	else
	{
		$cid2=$res->fetch_array();
		$cid2=$cid2['cid'];
		$_SESSION['cid2']=$cid2;                                                
		$stime=time();
		//both clients are ready for chat.....
		$query="insert into bakar_chat (cid1,cid2,stime,randid) VALUES ($cid,$cid2,\"$stime\",\"$bakid\")";
		$con->query($query);
		$query="select chatid from bakar_chat where randid=\"$bakid\"";
		$res=$con->query($query);
		$chatid=$res->fetch_array();
		$chatid=$chatid['chatid'];
		$_SESSION['chatid']=$chatid;       
		$query="update bakar_clients set status=1 where cid=$cid";
		$con->query($query);
		$query="update bakar_clients set status=1 where cid=$cid2";
		$con->query($query);
		$query="commit";
		$con->query($query);
		// chat started between cid and cid2  ..... yipeeeeeee
		echo "$cid is now in chat with $cid2 and chatid $chatid";
		}
}

?>
<div id="upchat">
</div>

<div id="downchat">

<form>
<textarea id="cht"></textarea>
<input type="hidden" id="cid1" value="<?php echo $cid; ?>" />
<input type="hidden" id="cid2" value="<?php echo $cid2; ?>"/>
<input type="hidden" id="chatid" value="<?php echo $chatid; ?>"/>
<script>
$(document).ready(function(){
			var chatid,cid1,cid2;
	$("#cht").keypress(function(e){
		chatid=$("#chatid").val();;
		cid1=$("#cid1").val();
		cid2=$("#cid2").val();
	
		if(e.which==13)
		{
			var msg=htmlentities($("#cht").val());
			$.post("send_chat_msg.php",
			{
				chatid: chatid,
				cid1: cid1,
				cid2: cid2,
				msg: msg
			},
			 function(data, status){
				 if(status=="success")
				 {
        			$("#cht").val("");
					$("#upchat").append("<b>Me:</b>" + msg+"<br>");
				 }
			});
		}
	});
	

	window.setInterval(function(){
		chatid=$("#chatid").val();;
		cid1=$("#cid1").val();
		cid2=$("#cid2").val();
	
	 $.post("get_chat_msg.php",
			{
				chatid: chatid,
				cid1: cid1,
				cid2: cid2,
			},
			 function(data, status){
				 if(status=="success")
				 {
					 var c2 = jQuery.parseJSON(data);
					if(c2.length > 0)
						$("#upchat").append("<b>Random Moti: </b>" + c2[0] +"<br>");
				 }
			});
	}, 1000);


	
});
</script>
</form>
</div>
</body>
</html>