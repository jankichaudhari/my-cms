// JavaScript Document
var flagMaintime = 0;
var maintime = 0;
function addAnotherUsage(bookingId,usageId)
{	//alert(bookingId+"  "+usageId);

	var orgSeqId = $('#ids_'+bookingId+'_'+usageId).val();
	var thisSeqId = parseInt(orgSeqId) + 1;
	var divContent = $('#addAnotherContent_'+bookingId+'_'+usageId).html();
	$('#addAnotherContent_'+bookingId+'_'+usageId).before(divContent);
	$('#startId').attr('name','startValue_'+thisSeqId+'_'+usageId);
	$('#startId').attr('id',bookingId+'_s_'+usageId);
	$('#msgStartId').attr('id','msg'+thisSeqId+bookingId+'s'+usageId);
	$('#finishId').attr('name','finishValue_'+thisSeqId+'_'+usageId);
	$('#finishId').attr('id',bookingId+'_f_'+usageId);
	$('#msgFinishId').attr('id','msg'+thisSeqId+bookingId+'f'+usageId);
	$('#ids_'+bookingId+'_'+usageId).val(thisSeqId);
	
	/*$.post("index.php?process=addAnother",{ bookingId : bookingId, groupId : groupId , params : params } ,function(msg)
	{
				$('#myUsageLink'+bookingId).html(msg);
	});*/
	
}
function didNotUse(bookingId) {
	//var bookingId=$(this).attr('id');
	var groupId=$("#groupId").val();
	$.post("index.php?process=inactivateMyUsage",{   bookingId : bookingId, groupId : groupId } ,function(msg)
	{
		if(msg.length!=0)
		{
	   		$('#myUsageBlock'+bookingId).slideUp("normal");
		}
	});
}
function months(thisObj)
{	
	var thisVal=$(thisObj).val();
	var groupId=$("#groupId").val();
	$.post("index.php?process=myUsageLog",{   groupId : groupId, currMonth : thisVal } ,function(msg)
	{
				$('#myUsagePage #myUsageLog').slideUp("fast");
				$('#myUsagePage #myUsageLog').html(msg);
				$('#myUsagePage #myUsageLog').slideDown('fast');
	});
}
function submitForm(bookingId,usageIds)
{	
	var submitError = false;
	var startTimeElement = document.getElementById("startH_"+bookingId);
	var startTimeValue = checkTime(startTimeElement,'h');
	var startTimeElement = document.getElementById("startM_"+bookingId);
	var startTimeValue = checkTime(startTimeElement,'m');
	var finishTimeElement = document.getElementById("finishH_"+bookingId);
	var finishTimeValue = checkTime(finishTimeElement,'h');
	var finishTimeElement = document.getElementById("finishM_"+bookingId);
	var finishTimeValue = checkTime(finishTimeElement,'m');
	
	if(startTimeValue==0 || finishTimeValue==0)
	{
		$('#errorUsage').html('').removeClass();
		$('#errorUsage').html('Please enter correct time values').addClass('error');
		submitError = true;
	}	
		
	$(".inputUsage"+bookingId).each(function (i) {
		var thisId =$(this).attr('id');
		var thisArray = thisId.split("_");
		var thisType = thisArray[1];
		var thisUsageId = thisArray[2];
		var thisElement = document.getElementById(thisId);
		
		if(checkValues(thisElement)==0)
		{
			$('#errorUsage').html('').removeClass();
			$('#errorUsage').html('Please enter values correct').addClass('error');
			submitError = true;
		}
	});
	
	if(submitError == true)
	{
		return false;
	} else {
		var response = confirm('Are you sure to Submit ??');
		if(response) {
			var bookUsage = $("#myUsage"+bookingId).submit();
			return true;
		} else {
			return false;
		}
	}
}
function timeFocus(thisObj,type)
{	
	if(thisObj.value.length==0 || thisObj.value=='hh' || thisObj.value=='mm')
	{
		thisObj.value='';
	}
}
function timeBlur(thisObj,type)
{
	if(thisObj.value.length==0)
	{
		if(type=='h')
		{
			thisObj.value='hh';
		}
		if(type=='m')
		{
			thisObj.value='mm';
		}
	}
	else if(thisObj.value.length==1)
	{
		var thisVal = thisObj.value;
		thisObj.value=00+thisVal;
	}
}
function checkValues(thisObj)
{
	var thisFlag = 0;
	var thisObjId = thisObj.id;	alert(thisObjId);
	var thisObjInfo = thisObjId.split("_");
	var thisBookId = thisObjInfo[0];
	var thisObjName = thisObjInfo[1];
	var thisUsageId = thisObjInfo[2];
	var thisSeqId = thisObjInfo[3];
	var thisValue = thisObj.value;
	var pat = /^[\s0-9.]{1,16}$/;
	
	if(thisValue.length!=0)
	{	
		if((pat.test(thisValue))){
			$("#msg"+thisSeqId+thisBookId+thisObjName+thisUsageId).html('').removeClass();
			
			if(thisObjName=='s' || thisObjName=='f')
			{
				var startVal = $("#"+thisBookId+"_s_"+thisUsageId+"_"+thisSeqId).val();
				var finishVal = $("#"+thisBookId+"_f_"+thisUsageId+"_"+thisSeqId).val();
				
				if(finishVal.length != 0 && startVal.length != 0)
				{
					if(parseInt(finishVal) < parseInt(startVal))
					{
						thisFlag = 0;
						$('#errorUsage').html('').removeClass();
						$('#errorUsage').html('Finish Value must be greater then start value').addClass('error');
						submitError = true;
					}
					else
					{
						thisFlag = 1;
						$('#errorUsage').html('').removeClass();
						$("#msg"+thisSeqId+thisBookId+"s"+thisUsageId).html('').removeClass();
						$("#msg"+thisSeqId+thisBookId+"f"+thisUsageId).html('').removeClass();
						
						var totalUsageDiff = (parseInt(finishVal)) - (parseInt(startVal));
						$('#totalUsageDiff'+thisSeqId+"_"+thisBookId).html(totalUsageDiff);
					}
				}
			}
			if(thisObjName=='u')
			{
				thisFlag = 1;
				$('#errorUsage').html('').removeClass();
				$("#msg"+thisSeqId+thisBookId+thisObjName+thisUsageId).html('').removeClass();
			}
		} else {
			thisFlag = 0;
			$("#msg"+thisSeqId+thisBookId+thisObjName+thisUsageId).html('').removeClass();
			$("#msg"+thisSeqId+thisBookId+thisObjName+thisUsageId).html('Incorrect').addClass('error');
		}
	} else {
		$("#msg"+thisSeqId+thisBookId+thisObjName+thisUsageId).html('').removeClass();
		$("#msg"+thisSeqId+thisBookId+thisObjName+thisUsageId).html('Mandatory').addClass('required');
		thisFlag = 0 ;
	}
	return thisFlag;
}
function compareDateTime(thisFlag,thisBookId)
{
	var startDateVal = $('#usageStartDate_'+thisBookId).val();
	var finishDateVal = $('#usageFinishDate_'+thisBookId).val();
	var startTimeVal = $('#start_'+thisBookId).val();
	var finishTimeVal = $('#finish_'+thisBookId).val();
	var startHVal = $('#startH_'+thisBookId).val();
	var startMVal = $('#startM_'+thisBookId).val();
	var finishHVal = $('#finishH_'+thisBookId).val();
	var finishMVal = $('#finishM_'+thisBookId).val();
	
	if((startHVal.length != 0) && (finishHVal.length != 0) && (startHVal != 'hh') && (finishHVal != 'hh'))
	{	
		if((startMVal.length == 0) || (startMVal == 'mm'))
		{
			startMVal =00;
		}
		if((finishMVal.length == 0) || (finishMVal == 'mm'))
		{
			finishMVal=00;
		}
		
		$.post("index.php?process=checkUsageDT",{ startDt : startDateVal , finishDt : finishDateVal, startH : startHVal, finishH :  finishHVal, startM : startMVal, finishM :  finishMVal } ,function(msg)
		{	
			if(msg==-1)
			{
				thisFlag = 0;
				$('#errorUsage').html('').removeClass();
				$('#errorUsage').html('Finish time must be greater then start time').addClass('error');
			}
			else
			{
				thisFlag = 1;
				$('#totalTimeDiff'+thisBookId).html(msg);
				$('#errorUsage').html('').removeClass();
				$("#msgstartTime"+thisBookId).html('').removeClass();
				$("#msgfinishTime"+thisBookId).html('').removeClass();
			}
		});
	}
	else
	{
		thisFlag = 0;
		$('#errorUsage').html('').removeClass();
		$('#errorUsage').html('Time not entered').addClass('error');
	}
	return thisFlag;
}
function checkTime(thisObj,type)
{
	var thisFlag = 0;
	var thisObjId = thisObj.id;
	var thisName = thisObj.name;
	var thisObjInfo = thisObjId.split("_");
	var thisBookId = thisObjInfo[1];
	var thisObjName = thisObjInfo[0];
	var thisValue = thisObj.value;	
	//var pat = /(^([0-9]|[0alert("");
	if(type=='h')
	{
		var defaultVal = 'hh';
		var s_limit = 0;
		var f_limit = 23;
		
	}
	else if(type='m')
	{
		var defaultVal = 'mm';
		var s_limit = 0;
		var f_limit = 59;
	}
		
		if(thisValue.length!=0  && thisValue!=defaultVal)
		{	//alert(thisValue);
			if(thisValue >= s_limit && thisValue <= f_limit)
			{
				thisFlag = 1;
				$("#msg"+thisName+thisBookId).html('').removeClass();
				thisFlag = compareDateTime(thisFlag,thisBookId);
			}
			else {
				thisFlag = 0;
				$("#msg"+thisName+thisBookId).html('').removeClass();
				$("#msg"+thisName+thisBookId).html('X').addClass('error');
			}
		}
		else
		{
			if(type=='m')
			{
				thisFlag = 1;
				$("#msg"+thisName+thisBookId).html('').removeClass();
			}
			else
			{	
				thisFlag = 0;
				$("#msg"+thisName+thisBookId).html('').removeClass();
				$("#msg"+thisName+thisBookId).html('Mandatory').addClass('required');
			}
		}
	
	/* if(thisValue.length!=0  && thisValue!='hh:mm')
	{
		if((pat.test(thisValue)))
		{
			thisFlag = 1;
			$("#msg"+thisName+thisBookId).html('Correct').removeClass();
			thisFlag = compareDateTime(thisFlag,thisBookId);
		} else {
			thisFlag = 0;
			$("#msg"+thisName+thisBookId).html('Incorrect').addClass('error');
		}
	} else {
		thisFlag = 0;
		$("#msg"+thisName+thisBookId).html('Mandatory').addClass('error');
	}*/
	return thisFlag;
}
/*function liveEvent(thisObj,b)
{	
	var thisName=$(thisObj).attr('name');
	 //var thisAttr=$('.myUsage input[name='+thisName+']');
	 var pat=/(^([0-9]|[0-1][0-9]|[2][0-3]):([0-5][0-9])(\s{0,1})$)|(^([0-9]|[1][0-9]|[2][0-3])(\s{0,1})$)/;
	 //var pat=/(^([0-9]|[0-1][0-9]|[2][0-3]):([0-5][0-9])(\s{0,1})$)|(^([0-9]|[1][0-9]|[2][0-3])(\s{0,1})$)/;
	 
	 if(thisObj.value.length!=0  || thisObj.value!='hh:mm')
	{	
		if((pat.test(thisObj.value))){
			flagMaintime = 1;
			maintime =1 ;
			$("#"+"msg"+thisName+b).html('Correct').removeClass();
		} else {
			$("#"+"msg"+thisName+b).html('Incorrect').addClass('error');
			flagMaintime = 3;
		}
	} else {
		$("#"+"msg"+thisName+b).html('Mandatory').addClass('error');
		flagMaintime = 2 ;
	}
}*/
function pastDatePicker(thisObj)
{
	var thisId = thisObj.className;
	
	$('.'+thisId).DatePicker({
		format:'Y-m-d',
		date: $('.'+thisId).val(),
		current: $('.'+thisId).val(),
		starts: 1,
		position: 'center',
		onBeforeShow: function(){
			$('.'+thisId).DatePickerSetDate($('.'+thisId).val(), true);
		},
		onChange: function(formated, dates){
			
			var today = new Date();
			var thisDt = formated.split("-");
			
			var thisYear=thisDt[0];
			var thisMonth=thisDt[1]-1;
			var thisDay=thisDt[2];
			
			var s=new Date();
			s.setFullYear(thisYear,thisMonth,thisDay);
			
			if (s > today)
			{
				var td =today.getFullYear()+"-"+(today.getMonth()+1)+"-"+today.getDate();
				$('.'+thisId).val(td);
			}
			else
			{
				$('.'+thisId).val(formated);
				$('.'+thisId).attr('title',formated);
			}
			$('.'+thisId).DatePickerHide();
			
			if(thisId=='usageStartDate' || thisId=='usageFinishDate')
			{
				thisBookIdVal = thisObj.id;
				thisBookIdArray = thisBookIdVal.split("_");
				thisBookId = thisBookIdArray[1];
				compareDateTime(1,thisBookId);
			}
		}
	});
}
$(document).ready(function () {
	var flag=0;
	var totalBookings=$('#totalBookings').val();
	$(".myUsage .inputUsage").val('');
	$(".myUsage .timeInputH").val('hh');
	$(".myUsage .timeInputM").val('mm');
});