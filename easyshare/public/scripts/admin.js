// JavaScript Document
var flagusageValue=0;
function changeUsageData(thisValue)
{	
		//var thisValue=$(this).attr('id');
		var groupId=$("#groupId").val();	
		var thisData=thisValue.split("_");
		var bookId=thisData[0];
		var groupRuleId=thisData[1];
		var memUsageId=thisData[2];
		var usageType=thisData[3];
		var sortIndex=thisData[4];
		var sortState=thisData[5];
		
		window.open('index.php?process=usageInfo_page&param1='+bookId+'&param2='+groupRuleId+'&param3='+memUsageId+'&param4='+sortIndex+'&param5='+sortState+'&param6='+groupId+'&param7='+usageType,'Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=850,height=550,left=430,top=350'); 
		return false;
}
function usageValueValid(startVal,finishVal)
{
	if(parseFloat(finishVal) > parseFloat(startVal))
	{
		var total=finishVal-startVal;
		$("#usageTotal").removeClass('error');
		$("#usageTotal").html(total).addClass('required');
		flagusageValue=2;
	}
	else
	{
		$("#usageTotal").removeClass('error');
		$("#usageTotal").html('Start must be less then Finish').addClass('error');
		flagusageValue=1;
	}
}
function changeCheckStyle(thisElement)
{	
	//var thisElement = document.getElementById(v);
	var thisVal=thisElement.checked;
	if(thisVal==false){
		thisElement.parentNode.className="inActiveRecord";
	} else {
		thisElement.parentNode.className="activeRecord";
	}
}

function sortFields(thisName,currPage)
{
	var groupId=$("#groupId").val();
	//var thisName=$(this).attr('id');
	var thisState=$(this).attr('name');
	
	var sortState='a';
	if(thisState=='a')
	{
		sortState='d';
		$(this).attr('name','d');
	}
	else if(thisState=='d')
	{
		sortState='a';
		$(this).attr('name','a');
	}
	else
	{
		sortState='a';
		$(this).attr('name','a');
	}
	location.href="index.php?process=groupAdmin&param1="+groupId+"&param2="+thisName+"&param3="+sortState+"&pagenum="+currPage+"#adminLog";
}
function checkUncheckAll(thisId,checkListName)
{
	var checkName = document.getElementsByName(checkListName);
	var startLimit=$("#startLimit").val();
	var endLimit=$("#endLimit").val();
	var idValue = thisId.id;
	
	if(idValue=="unCheckAll")
	{
		for(var j=0; j < checkName.length; j++)
		{	
			var thisElement = checkName[j];
			thisElement.checked=false;
			thisElement.parentNode.className="inActiveRecord";
		}
		//thisId.innerHTML="Check";
		thisId.id="checkAll";
		thisId.parentNode.className="inActiveRecord";
	}
	else
	{
		for(var j=0; j < checkName.length; j++)
		{	
			var thisElement = checkName[j];
			thisElement.checked=true;
			thisElement.parentNode.className="activeRecord";
		}
		//thisId.innerHTML="Uncheck";
		thisId.id="unCheckAll";
		thisId.parentNode.className="activeRecord";
	}
	
	//var t =this.id;
	
}
$(document).ready(function () {
	$('#adminLogPage #deleteUsageData').click(  function() {
		var response = confirm("Are you sure to delete this Record(s)?");
		if(response==true)
		{
			$('input[type=checkbox]').each(function(){
				if (this.checked) {
				//checkAdminLog.push($(this).val());
				
				var thisValue=$(this).val();	
				var thisData=thisValue.split("_");
				var bookId=thisData[0];
				var usageInfoId=thisData[1];
				
				$.post("index.php?process=deleteUsageData",{ bookId: bookId , usageInfoId : usageInfoId },function(msg)
				{	//$("#adminLogPage #msgAdminLog").html(msg).addClass('error');
					if((msg==0) || (msg.length==0))
					{
						$("#adminLogPage #msgAdminLog").html("not deleted!!").addClass('error');
					}
					else 
					{
						location.reload();
					}
				});
			}
			});
			return true;
		}
		else 
		{
			return false;
		}
	});
	$('#changeStart').bind("keyup click mouseover mouseout focus blur", function() {
		var pat=/^[\s0-9]{1,16}$/;
		var startVal=$(this).val();
		var finishVal=$('#changeFinish').val();
		var s=startVal.length;
		var f=finishVal.length;
		
		if(startVal=='undefined' || s==0)
		{
			$("#msgChangeStart").html('Mandatory').addClass('error');
			flagusageValue=1;
		} else {
			if((pat.test(startVal))){	
				$("#msgChangeStart").html('Correct').removeClass('error');
				flagusageValue=1;
				
				if(f!=0 && finishVal!='undefined' && pat.test(finishVal))
				{	
					usageValueValid(startVal,finishVal);
				}
			} else {
				$("#msgChangeStart").html('Incorrect').addClass('error');
				flagusageValue=1;
			}
		}
	});
	$('#changeFinish').bind("keyup click mouseover mouseout focus blur", function() {
		var pat=/^[\s0-9]{1,16}$/;
		var finishVal=$(this).val();
		var startVal=$('#changeStart').val();
		var s=startVal.length;
		var f=finishVal.length;
		
		if(finishVal=='undefined' || f==0)
		{
			$("#msgChangeFinish").html('Mandatory').addClass('error');
			flagusageValue=1;
		} else {
			if((pat.test(finishVal))){	
				$("#msgChangeFinish").html('Correct').removeClass('error');
				flagusageValue=1;
				if(s!=0 && startVal!='undefined' && pat.test(startVal))
				{	
					usageValueValid(startVal,finishVal);
				}
			} else {
				$("#msgChangeFinish").html('Incorrect').addClass('error');
				flagusageValue=1;
			}
		}
	});
	$("#changeUsage").submit( function () {			
		var submitError = 0;
		var bookingId = $('#bookId').val();
		$(".usageStartDate").each(function (i) {
			var startTimeHElement = document.getElementById("startH_"+bookingId);
			var startTimeHValue = checkTime(startTimeHElement,'h');
			var startTimeMElement = document.getElementById("startM_"+bookingId);
			var startTimeMValue = checkTime(startTimeMElement,'m');
			var finishTimeHElement = document.getElementById("finishH_"+bookingId);
			var finishTimeHValue = checkTime(finishTimeHElement,'h');
			var finishTimeMElement = document.getElementById("finishM_"+bookingId);
			var finishTimeMValue = checkTime(finishTimeMElement,'m');
			
			if(startTimeHValue==0 || finishTimeHValue==0 || startTimeMValue==0 || finishTimeMValue==0)
			{	
				$('#errorUsage').html('').removeClass();
				$('#errorUsage').html('Please enter correct time values').addClass('error');
				submitError = 1;
			}
		});
		$('#changeStart').keyup();
		$('#changeFinish').keyup();
		if(flagusageValue==2 || submitError==0)
		{	
			return true;
		}
		{
			return false;
		}
	});
});