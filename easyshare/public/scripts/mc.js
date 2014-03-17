// JavaScript Document
function new_schdule(groupId,thisType)
{
	if(thisType=='u')
	{
		var processType = 'usage_schdule';
	}
	else if(thisType=='c')
	{
		var processType = 'calendar_schdule';
	}
	$.post("index.php?process="+processType,{ groupId : groupId },function(data)
		 {	
			displayContent(data,'maintenance_schedule');
		 });
}
function selectPeriod(thisObject)
{
	var thisValue = $('#periods option:selected').val();
	var thisId = $('#periods option:selected').attr('id'); 
	
	if(thisId > 7)
	{
		$('#before #day').remove();
		$('#before #week').remove();
		$('#before #month').remove();
		$('#before #2months').remove();
		$('#before').append($("<option></option>").attr("id","day").attr("value",1).text("a day"));
		
		var totalMonths = (parseFloat(thisId)) / 30;
		if(totalMonths > 1)
		{	
			if(totalMonths > 6)
			{
				$('#before').append($("<option></option>").attr("id","week").attr("value",7).text("a week"));
				$('#before').append($("<option></option>").attr("id","month").attr("value",30).text("a month"));
				$('#before').append($("<option></option>").attr("id","2months").attr("value",60).text("2 months"));
			}
			else
			{
				$('#before').append($("<option></option>").attr("id","week").attr("value",7).text("a week"));
				$('#before').append($("<option></option>").attr("id","month").attr("value",30).text("a month"));
				if(totalMonths > 2)
				{
					$('#before').append($("<option></option>").attr("id","2months").attr("value",60).text("2 months"));
				}
			}
		}
		else
		{
			$('#before').append($("<option></option>").attr("id","week").attr("value",7).text("a week"));
		}
	}
	else
	{
		$('#before #day').remove();
		$('#before #week').remove();
		$('#before #month').remove();
		$('#before #2months').remove();
		
		$('#before').append($("<option></option>").attr("id","day").attr("value",1).text("a day"));
	}
}
function submitCalendarSch()
{
	var calDesc = $("#calSch_desc").val();
	
	if(calDesc.length==0)
	{
		$("#msgCalDesc").html('Mandatory').addClass('error');
	}
	else
	{
		$("#msgCalDesc").html('').removeClass();
		var allCalendarVals = $("#calendar_sch").serialize();
		//$('#msgCalSch').html(allCalendarVals);
	  	$.post("index.php?process=store_calendar_schdule",{ allCalendarVals : '&'+allCalendarVals },function(data)
	  	{	
			displayContent(data,'maintenance_schedule');
	  	});
	}
}
function checkUsageValues(thisObject,thisnm)
{	
	var flag = true;
	var pat = /^[\s0-9.]{1,16}$/;
	var thisValue = thisObject.value;
	
	if(thisValue.length!=0)
	{
			if((pat.test(thisValue))){
				$("#"+thisnm).html('').removeClass('error');
			}
			else
			{
				$("#"+thisnm).html('Incorrect').addClass('error');
				flag =false ;
			}
    }
	else
	{
			$("#"+thisnm).html('Mandatory').addClass('error');
			flag =false;
	}
	
	return flag;
}
function dissmissReminder(thisId,thisType)
{
	var thisDivId = thisType+thisId;
	$.post("index.php?process=dissmissReminder",{ thisId : thisId, thisType : thisType },function(msg)
	{	
		//$("#errorSchdule").html(msg).addClass("error");	
		if(msg==0)
		{
			$("#errorSchdule").html("Error!! Reminder not dissmissed..").addClass("error");	
		}
		else
		{
			$("#"+thisDivId).remove();
		}
	});
}
function submitUsageSch()
{	
	var triggerElement = document.getElementById('trigger_value');
	//var beforeElement = document.getElementById('before_value');
	var usageDesc = $("#usageSch_desc").val();	
	var t_check = checkUsageValues(triggerElement,'msgUsageTrigger');	
	//var b_check = checkUsageValues(beforeElement,'msgUsageBefore');
	//var compare_vals = compareCheckValues(triggerElement,beforeElement);
	
	if(usageDesc.length==0)
	{
		$("#msgUsageDesc").html('Mandatory').addClass('error');
	}
	/*else if(t_check==true && b_check==true && compare_vals==true)*/
	else if(t_check==true)
	{
		var allUsageVals = $("#usage_sch").serialize();
	  	$.post("index.php?process=store_usage_schdule",{ allUsageVals : '&'+allUsageVals },function(data)
	  	{	
			displayContent(data,'maintenance_schedule');
	  	});
	}
}

function deleteSchdule(thisType)
{
	var response = confirm("Are you sure to delete this Record(s)?");
	if(response==true)
	{	
		var thisFlag = 0;
		$('input[type=checkbox]').each(function(){
			if (this.checked) {
				thisFlag = 1;
				var thisId=$(this).val();
				if(thisType=='u')
				{
					var processType = 'delete_usage_schdule';
					var msgDiv = 'msg_usage_sch';
				}
				else if(thisType=='c')
				{
					var processType = 'delete_calendar_schdule';
					var msgDiv = 'msg_calendar_sch';
				}
				$.post("index.php?process="+processType,{ thisDeleteId : thisId },function(msg)
				{	
					//$("#maintenance_schedule #"+msgDiv).html(msg).addClass('error');
					if(msg==0)
					{
						$("#maintenance_schedule #"+msgDiv).html("not deleted!!").addClass('error');
					}
					else 
					{
						location.reload();
					}
				});
		}
		});
		if(thisFlag==0)
		{
			alert("Please Select Record(s)");
			return false;
		}
	}
}