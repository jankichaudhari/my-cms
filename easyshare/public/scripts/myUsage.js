// JavaScript Document
var flagMaintime = 0;
var maintime = 0;
function addAnotherUsage(bookingId,usageId,usageUnit)
{	//alert(bookingId+"  "+usageId);

	var orgSeqId = $('#ids_'+bookingId+'_'+usageId).val();
	var thisSeqId = parseInt(orgSeqId) + 1;
	var startUsageName ='startValue_'+thisSeqId+'_'+usageId;
	var startUsageId = bookingId+'_s_'+usageId+'_'+thisSeqId;
	var startUsageMsgId = 'msg'+thisSeqId+bookingId+'s'+usageId;
	var finishUsageName = 'finishValue_'+thisSeqId+'_'+usageId;
	var finishUsageId = bookingId+'_f_'+usageId+'_'+thisSeqId;
	var finishUsageMsgId = 'msg'+thisSeqId+bookingId+'f'+usageId;
	var usageDiffId = 'totalUsageDiff_'+thisSeqId+'_'+bookingId+'_'+usageId;
	$('#ids_'+bookingId+'_'+usageId).val(thisSeqId);
	
	$('#addAnotherContent_'+bookingId+'_'+usageId).before('<div class="divRow" style="width:135px;margin-right:10px;"><span style="color:#CCC;text-transform:capitalize;">'+usageUnit+'&nbsp;Start</span><br/><input type="text" size="16" class="inputUsage'+bookingId+'" name="'+startUsageName+'" value="" id="'+startUsageId+'" onkeyup="javascript:checkValues(this);" onmousedown="javascript:checkValues(this);" maxlength="16" style="background:#9D9C9C;border:0;	color:black;padding:3px 3px 3px 5px;font-size:10px;"/><div style="min-height:20px;" id="'+startUsageMsgId+'"></div></div><!-- finish unit --><div class="divRow" style="width:135px;margin-right:10px;"><span style="color:#CCC; text-transform:capitalize;">'+usageUnit+'&nbsp;Finish</span><br/><input type="text" size="16"  class="inputUsage'+bookingId+'" name="'+finishUsageName+'" value="" id="'+finishUsageId+'" onkeyup="javascript:checkValues(this);" onmousedown="javascript:checkValues(this);"  maxlength="16" style="background:#9D9C9C;border:0;	color:black;padding:3px 3px 3px 5px;font-size:10px;"/><div style="min-height:20px;" id="'+finishUsageMsgId+'"></div></div><!-- Total --><div class="divRow" style="width:155px;" align="center"><span style="color:#CCC;" id="totalTime">Total</span><div id="'+usageDiffId+'" style="border:1px solid #333;width:130px;height:18px;"></div></div><!-- Remove Link --><div class="divRow" style="width:100px;"><br/><span id="smallText"><a href="javascript:void(0);" onclick="javascript:removeUsageBlock();" id="removeUsageBlock">remove</a></span></div><div class="clear"></div>');
	
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
	var thisObjId = thisObj.id;
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
						$('#totalUsageDiff_'+thisSeqId+"_"+thisBookId+"_"+thisUsageId).html(totalUsageDiff);
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
function compareDateTime(thisBookId)
{
	var startDateVal = $('#usageStartDate_'+thisBookId).val();
	var finishDateVal = $('#usageFinishDate_'+thisBookId).val();
	var startHVal = $('#startH_'+thisBookId).val();
	var startMVal = $('#startM_'+thisBookId).val();
	var finishHVal = $('#finishH_'+thisBookId).val();
	var finishMVal = $('#finishM_'+thisBookId).val();
	
	if((startHVal.length != 0) && (finishHVal.length != 0) && (startHVal != 'hh') && (finishHVal != 'hh'))
	{
		//thisFlag = 0;
		if((startMVal.length == 0) || (startMVal == 'mm'))
		{
			startMVal =00;
		}
		if((finishMVal.length == 0) || (finishMVal == 'mm'))
		{
			finishMVal=00;
		}
		
		
		
		//setTimeout( function() {
			$.post("index.php?process=checkUsageDT",{ startDt : startDateVal , finishDt : finishDateVal, startH : startHVal, finishH :  finishHVal, startM : startMVal, finishM :  finishMVal } ,function(msg)
			{	$('#errorUsage').html('processing').addClass('required');
				if(msg==0)
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
		//}, 1000 );
		//$('#errorUsage').fadeOut("slow");
	}
	else
	{
		thisFlag = 0;
		$('#errorUsage').html('').removeClass();
		$('#errorUsage').html('Time not entered').addClass('error');
		
	}	//alert("compare"+thisFlag);
	//setTimeout( function() {
						 return thisFlag;
	 //}, 1000 );
						 
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
		{	
			if(thisValue >= s_limit && thisValue <= f_limit)
			{
				//thisFlag = 1;
				$("#msg"+thisName+thisBookId).html('').removeClass();
				thisFlag = compareDateTime(thisBookId);
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
	return thisFlag;
}
function submitForm(bookingId,usageIds)
{	
	//var temp = document.getElementById("startH_"+bookingId).value;
	var submitError = false;
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
		{	//alert("error");
			$('#errorUsage').html('').removeClass();
			$('#errorUsage').html('Please enter correct time values').addClass('error');
			submitError = true;
		}	
	});
		
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
		return false;
	}
}
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