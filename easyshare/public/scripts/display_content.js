// JavaScript Document
/*$(document).ready(function(){
  $("image").click(function(){
    $("txtHint").load('index.php?process=display_home&param="+photo_id');
  });
});*/
$(function() {
	   $('.ajax-link').click( function() {
			 $.post( $(this).attr('onclick'), function(msg) {
				 $('#txtHint').toggle("fast");
				  document.getElementById("txtHint").innerHTML=msg;
				  $('#txtHint').toggle("fast");
				  //$(this).attr('src', imgs[(cntt++) % cnt]).fadeIn("fast");
			 });
			 return false; // don't follow the link!
	   });
	
	});

function dispContent(photo_id)
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
				document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
			}
		 }
		xmlhttp.open("post","index.php?process=display_home&param1="+photo_id,true);
		xmlhttp.send();
	}