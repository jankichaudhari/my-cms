var xmlhttp;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
			
function addListing(listId)
{
	$.post("index.php?process=list1",{   param1 : listId } ,function(data)
	{
				$('#edit_listing').slideUp("fast");
				$('#listing').html(data);
				$('#listing').slideDown('fast');
	});
}
function Listing2(thisObj,listId)
	{	
		var catId = thisObj.value;
		var selValue = $('#txtOtherId').val();
		var selText = $('#txtOtherNm').val();
		var  setother = $('#hiddenOther').val();
		
		$.post("index.php?process=list2",{ param1 : catId , param2 : listId, param3 : setother } ,function(data)
		{
			if(data==1)
			{
				$('#list3').html('');
				$('#list2').html('');
				$('#catId').val(catId);
				$('#click').html('Click Here to Store Your Progress and Proceed to STEP-2&nbsp;&nbsp;<input type="submit" style="background-image:url(public/images/search-button.gif);width:25px;cursor:hand;" name="submit" value=""/>');
			}
			else
			{
				$('#click').html('');
				$('#list3').html('');
				$('#list2').html(data);
			}
		});
	}
function saveOtherOpt()
{
	var otherVal = $('#txtOtherOpt').val();
	if(otherVal.length!=0)
	{	
		$.post("index.php?process=addOtherOpt",{ thisVal : otherVal } ,function(msg)
		{
			if(msg==0)
			{
				$('#msgSaveOther').html('Error!! Value not added').addClass("error");
			}
			else
			{
				var msgType = msg.substr(1,5);
				var thisMsg = msg.substr(6);
				if(msgType=="_exi_")
				{
					$('#msgSaveOther').html(thisMsg).addClass("error");
				}
				else if(msgType=='_new_')
				{
					$('#hiddenOther').val(thisMsg);
					$('#list3').html('');
					$('#selectList2 #selOtherOpt').remove();
					$('#selectList2').append($("<option></option>").attr("id","selOtherOpt").attr("value",thisMsg).text(otherVal));
				}
			}
		});
	}
	else
	{
		$('#msgSaveOther').html('Please Enter Value').addClass("error");
	}
}
function Listing3(thisObj,listId,thisType)
	{
		var catId = thisObj.value;
		if(catId==0)
		{
			$('#click').html('');
			$('#list3').append($("<input/>").attr("type","text").attr("name","txtOtherOpt").attr("value","").attr("id","txtOtherOpt"));
			$('#list3').append($("<input/>").attr("type","button").attr("name","saveOther").attr("Value","Save").attr("onClick","javascript:saveOtherOpt();")).css("text-align", "right");
			$('#list3').append($('<br/><span id="msgSaveOther" style="text-align:right"><span/>'));
		}
		else
		{
			$.post("index.php?process=list3",{ param1 : catId , param2 : listId } ,function(data)
			{
				if(data==1)
				{
					if(thisType == 'list3')
					{
						$('#list3').html('');
					}
					$('#catId').val(catId);
					$('#click').html('Click Here to Store Your Progress and Proceed to STEP-2&nbsp;&nbsp;<input type="submit" style="background-image:url(public/images/search-button.gif);width:25px;cursor:hand;" name="submit" value=""/>');
				}
				else if(data==2)
				{
					$('#msgStep2').html('Error!!').addClass("error");
				}
				else
				{
						$('#click').html('');
						$('#list3').html(data);
				}
			});
		}
	}
	
	function show_step1(listId)
	{
		location.href="index.php?process=edit_record&param1="+listId;
		//document.list.submit();
		return true;
	}
	function show_step2(listId)
	{
		location.href="index.php?process=show_step2&param1="+listId;
		//document.list.submit();
		return true;
	}
	function show_step3(listId)
	{
		location.href="index.php?process=show_step3&param1="+listId;
		//document.list.submit();
		return true;
	}
	function show_step4(listId)
	{
		location.href="index.php?process=show_step4&param1="+listId;
		//document.list.submit();
		return true;
	}
	function fileType(img)
	{
		var fileTypes=["bmp","gif","png","jpg","jpeg"];
		imgValue=img.value;
		var imagename=img.name;
		//var temp = imagename.split("_");
		
		var ext=imgValue.substring(imgValue.lastIndexOf(".")+1,imgValue.length).toLowerCase();
		
		for (var i=0; i<fileTypes.length; i++)
		{
			if (fileTypes[i]==ext) break;
		}
			if (i >= fileTypes.length)
			{
				document.getElementById("msg"+imagename).innerHTML="Invalid Image";
				document.getElementById("msg").style.color = "red";
				document.getElementById("msg").style.textDecoration="blink";
				return false;
			}
			else
			{
				//document.getElementById("msg"+imagename).innerHTML="";
				//document.getElementById("msg").style.textDecoration="none";
				document.step2.images.value=imagename;
				document.step2.submit();
				return true;
			}
	}
	function checkImage(img)
	{
		var thisFlag = 0;
		var fileTypes=["bmp","gif","png","jpg","jpeg"];
		var imgValue=img.value;
		var imagename=img.name;
		var ext=imgValue.substring(imgValue.lastIndexOf(".")+1,imgValue.length).toLowerCase();
		
		for (var i=0; i<fileTypes.length; i++)
		{
			if (fileTypes[i]==ext) break;
		}
			if (i >= fileTypes.length)
			{
				document.getElementById("infoMessage").innerHTML="Invalid Image";
				document.getElementById("infoMessage").style.color = "red";
				thisFlag =1 ;
			}
			else
			{
				document.getElementById("infoMessage").innerHTML="";
				document.getElementById("infoMessage").style.textDecoration="none";
			}
			
			return thisFlag;
	}
	function deleteImage(imgId,img)
	{
		xmlhttp.onreadystatechange=function()
		{	
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{	
				var data = xmlhttp.responseText;
				data = data.split("-");
				var listId = data[0];
				var errormsg = data[1];
				
				if(errormsg.length==0)
				{
					location.href="index.php?process=show_step2&param1="+listId;
				}
				else
				{
					var msg = "Error!! Image not Deleted.";
					location.href="index.php?process=show_step2&param1="+listId+"&param2="+msg;
				}
			}
		 }
		xmlhttp.open("post","index.php?process=deleteImage&param1="+imgId,true);
		xmlhttp.send();
	}
	
	/*function store_step4(listId,saveType)
	{
		location.href="index.php?process=store_step4&param1="+listId;
		document.step4.submit();
		return true;
	}*/
	function store_step1()
	{
		title=document.step1.txtTitle.value;
	
		if(title=="" || title==" " || title.length==0)
		{
			document.getElementById("enterTitle").innerHTML="Please Enter Title";
			document.step1.txtTitle.focus();
			return false;
		}	
		else
		{
			return true;
		}
	}
function removeHTMLTags(htmlString){
        if(htmlString){
          var mydiv = document.createElement("div");
           mydiv.innerHTML = htmlString;
 
            if (document.all) // IE Stuff
            {
                return mydiv.innerText;
               
            }   
            else // Mozilla does not work with innerText
            {
                return mydiv.textContent;
            }                           
      }
   }
   /*function storeStep4()
   {
	   var desc1ele = $("#desc1 .ui-widget .ui-richtextarea-content").html();
	    var desc2ele = $("#desc2 .ui-widget .ui-richtextarea-content").html();
		$("#description1").val(desc1ele);
		$("#description2").val(desc2ele);
   }*/
	$(document).ready(function () {
		/*$("#description").richtextarea({ toolbar: true });
		$("#description2").richtextarea({ toolbar: false });
	 
		$("#bold").click(function() {
			$("#description1").richtextarea('bold');
		});
		
		$("#desc1 .ui-widget .ui-richtextarea-content").bind('keyup keydown',function() {
			var thisHeight = $(this).css('height');
			var allContent = $(this).html();
			var result = removeHTMLTags(allContent);
			$(this).html(result);
			$(this).focus();
			
			var thisValue = $(this).html();
			var thisLength = thisValue.length;
			var thisLimit = 1100;
			var desc2Limit = 650;
			var desc2Element = $("#desc2 .ui-widget .ui-richtextarea-content");
			var desc2Value = desc2Element.html();
			var desc2Length = desc2Value.length;
			
			if(thisLength > thisLimit)
			{	
					var newValue= thisValue.substring(0, thisLimit);
					$(this).html(newValue);
					var desc2Desc = thisValue.substring(thisLimit,thisLength) + desc2Value;
					var desc2Val = desc2Desc.substring(0,desc2Limit);
					desc2Element.html(desc2Val);
					desc2Element.focus();
			}
		});
		
		$("#desc2 .ui-widget .ui-richtextarea-content").bind('keyup keydown',function() {
			var thisHeight = $(this).css('height');
			var allContent = $(this).html();
			var result = removeHTMLTags(allContent);
			$(this).html(result);
			$(this).focus();
			
			var thisValue = $(this).html();
			var thisLength = thisValue.length;
			var thisLimit = 650;
			var desc1Limit = 1100;
			var desc1Element = $("#desc1 .ui-widget .ui-richtextarea-content");
			var desc1Value = desc1Element.html();
			var desc1Length = desc1Value.length;
			
			if(desc1Length <= desc1Limit)	
			{
				if(thisLength < (desc1Limit-desc1Length))
				{
					var temp = desc1Element.html() + thisValue;
					var desc1Val = temp.substring(0, desc1Limit);
					desc1Element.html(desc1Val);
					desc1Element.focus();
					$(this).html('');
					if(desc1Element.html().length >= desc1Limit)
					{
						var desc2Val = thisValue.substring(desc1Limit, thisLimit);
						$(this).html(desc2Val);
					}
				}
				else
				{
					var temp = desc1Element.html() + thisValue;
					var desc1Val = temp.substring(0, desc1Limit);
					desc1Element.html(desc1Val);
					var desc2Val = temp.substring(desc1Limit, (desc1Limit+thisLimit));
					$(this).html(desc2Val);
					$(this).focus();
				}
			}
			else
			{
					var temp2 = thisObj.value + thisValue;
					var desc2Val = temp2.substring(0, thisLimit);
					$(this).html(desc2Val);
					$(this).focus();
			}
		});*/
		
	});