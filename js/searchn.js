function search(id){
	var cat=document.getElementById('category').value;
	if(id.value.length<3)
		return false;
	var url="http://sg.media-imdb.com/suggests/"+id.value.substr(0,1)+"/"+id.value+".json";//&type="+cat;
	loadXMLDoc(url);
	return false;
}
function loadXMLDoc(url)
{
var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
		var data=xmlhttp.responseText;
		var obj=JSON.parse(data);
		//alert(obj['Title']);
		if(obj['Response']=="False")
			return false;
		var dlist=document.getElementById("inames");
		//alert(dlist.options.length);
		var found=0;
		for(var i=0;i<dlist.options.length;i++)
			if(dlist.options[i].value==(obj['Title']+'('+obj['Year']+')')){
				found=1;
				break;
			}
		if(found==0)
			document.getElementById('inames').innerHTML+="<option value=\""+obj['Title']+' ('+obj['Year']+')'+"\""+obj['Title']+'('+obj['Year']+')'+"</option>";
//    document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
	
    }
  }
xmlhttp.open("GET",url,true);
xmlhttp.send();
}