// JavaScript Document
function myBookedSlot(thisObj)
{
	var thisId = thisObj.id;
	var currentId=thisObj.value;
	
	if(thisId=='myBookings')
	{
		$.post("index.php?process=checkBookingTime",{ currentId : currentId } ,function(msg)
		{
				if(msg==0)
				{
					$('#removeBooking').show();
				}
				else
				{
					$('#removeBooking').hide();
				}
		});
	}
	else
	{
		$('#removeBooking').hide();
	}
	
	var memberId=$("#memberId").val();
	var groupMC=$("#groupMC").val();
	
	
	var currentVal=$("#"+thisId+" option:selected").attr('id');
	var selectedDate=currentVal.split(",");
	var start_dt=selectedDate[0];
	var finish_dt=selectedDate[1];
	
	var finishDt=finish_dt.split("-");
	var finishTime=finishDt[5];
	if(finishTime>=12) { 
		if(parseFloat(finishTime)==12)
		{	finishTime=finishTime+" pm";
		} else {
			finishTime=(finishTime-12)+" pm"; }
	} else { 
		if(parseFloat(finishTime)==0) {
			finishTime=12+" am";
		} else {
			finishTime=finishTime+" am"; } 
	}
	
	var finishDate=finishDt[4]+". "+finishDt[2]+" "+finishDt[3]+", "+finishDt[0]+" at "+finishTime;
	
	var selectedDate=start_dt.split("-");
	var startTime=selectedDate[5];
	
	if(startTime>=12) {
		if(parseFloat(startTime)==12) {
			startTime=startTime+" pm";
		} else {
		startTime=(startTime-12)+" pm"; }
	} else { 
		if(parseFloat(startTime)==0) {
			startTime=12+" am";
		} else {
			startTime=startTime+" am"; }
	}
	
	if(startTime==0) { startTime = 12; }
	var startDate=selectedDate[4]+". "+selectedDate[2]+" "+selectedDate[3]+", "+selectedDate[0]+" at "+startTime;
	var monthNo=selectedDate[1];
	
	//$('#new_booking #finish_dateTime').html(finishDate);
	//$('#new_booking #start_dateTime').html(startDate);
	
	$.post("index.php?process=calendar_page",{ month:monthNo, start_dt : start_dt, finish_dt : finish_dt, selectedDay : start_dt, memberId : memberId, groupMC : groupMC } ,function(msg)
	{
				$('#calender_page').html(msg);
	});
	
	$.post("index.php?process=time_slots",{ selectedDay : start_dt, memberId : memberId, bookingId : currentId, groupMC : groupMC } ,function(msg)
	{	
				$('#time_class').slideUp("fast");
				$('#time_class').html(msg);
				$('#time_class').slideDown('fast');
	});
}
function finishTime()
{	$('#removeBooking').hide();
	activeInactive("nonActive",'#new_booking #endTime');
	activeInactive("nowActive",'#new_booking #startTime');
	
	var selectedDay=$('.calender_days .daylink_selected').attr("id");
	
	var start_dt=$("#start_dt").val();
	var finish_dt=$("#finish_dt").val();
	var memberId=$("#memberId").val();
	var groupMC=$("#groupMC").val();
	
	var selectedDate=selectedDay.split("-");
	var selectedMonth=selectedDate[1];
	$.post("index.php?process=calendar_page",{ month : selectedMonth, start_dt : start_dt, finish_dt : finish_dt, selectedDay : selectedDay, memberId : memberId, groupMC : groupMC } ,function(msg)
	{
			$('#calender_page').slideUp("fast");
			$('#calender_page').html(msg);
			$('#calender_page').slideDown('fast');
	});
	$.post("index.php?process=time_slots",{ start_dt : start_dt, finish_dt : finish_dt, selectedDay : selectedDay, memberId : memberId, groupMC : groupMC } ,function(msg)
	{
				$('#time_class').slideUp("fast");
				$('#time_class').html(msg);
				$('#time_class').slideDown('fast');
	});
}
function showSubmit()
{	$('#removeBooking').hide();
	var s =$("#start_dt").val();
	var f = $("#finish_dt").val();
	if(s.length!=0 && f.length!=0)
	{
		if(s!='none' && f!='none')
		{
			$('#submitMyBooking').show();
		}
	}
	
}
function month(monthNo) {
	$('#removeBooking').hide();
	var start_dt=$('#start_dt').val();
	var finish_dt=$('#finish_dt').val();
	var memberId=$("#memberId").val();
	var groupMC=$("#groupMC").val();
	
	var selectedDay=$('.calender_days .daylink_selected').attr("id");
	
	$.post("index.php?process=calendar_page",{ month:monthNo, start_dt : start_dt, finish_dt : finish_dt,selectedDay : selectedDay, memberId : memberId, groupMC : groupMC } ,function(msg)
	{
				$('#calender_page').html(msg);
	});
}
function insertdate(day){	
			//$('#removeAllBooking').show();
		  var current_date=$('.calender_days .daylink_selected').attr("id");
		  $('#'+current_date).removeClass().parent().removeClass();
		  $('#'+current_date).addClass('daylink').parent().addClass('day');
		  
		  
		  $('#'+day).removeClass().parent().removeClass();
		  $('#'+day).addClass('daylink_selected').parent().addClass('day_selected');
		// document.getElementById(day).className='daylink_selected';
		 //document.getElementById(day).parentNode.className='day_selected';
		
		  var selectedDay=$('.calender_days .daylink_selected').attr("id");
		  monthNo=$('.month_name').attr('id');
		 
			var memberId=$("#memberId").val();
			var groupMC=$("#groupMC").val();
			var finish_dt=$('#finish_dt').val();
			var start_dt=$('#start_dt').val();
			
			month(monthNo);
			
		  $.post("index.php?process=time_slots",{  start_dt : start_dt, finish_dt : finish_dt , selectedDay : day, memberId : memberId, groupMC : groupMC } ,function(msg)
			{	
						$('#time_class').slideUp("fast");
						$('#time_class').html(msg);
						$('#time_class').slideDown('fast');
			});
			
}
function timeSlot(timeId,time_state)
{	$('#removeBooking').hide();
	var groupMC=$("#groupMC").val();
	if(timeId>12) {
		time=(timeId-12)+" pm";
	} else {
		time=timeId+" am";
	}
	var dt=$("#currentDay").html()+"  at " +time;

	if(time_state=="Finish") {
		$('#new_booking #finish_dateTime').html(dt);
		var d = $(".daylink_selected").attr('id');
		var f = d + "-" + timeId;
		$("#finish_dt").val(f);
	} else {
		$('#new_booking #start_dateTime').html(dt);
		$('#finish_dateTime').html(':<span id="orangeText" style="font-size:11px;"> Please Select a Finish Time</span>');
		
		if(groupMC=='M')
		{
			$('#txtboxMOT').show();
		}
		var d = $(".daylink_selected").attr('id');
		var s = d + "-" + timeId;
		$("#start_dt").val(s);
	}

	$("#time_class .activeText").addClass("divRow inActiveText");
	$("#"+timeId).parent().removeClass();
	$("#"+timeId).parent().addClass("divRow activeText");
	finishTime();
	showSubmit();
}
$(document).ready(function () {
	$("#start_dt").val('');
	$("#finish_dt").val('');
	$('#submitMyBooking').hide();
	$('#removeBooking').hide();
	$('#txtboxMOT').hide();
	activeInactive('nonActive','#new_booking #startTime');
	$('#start_dateTime').html(':<span id="orangeText" style="font-size:11px;"> Please Select a Start Time</span>');
	
	$("#resetBooking").click( function () {
		$('#removeBooking').hide();
		var memberId=$("#memberId").val();
		var groupMC=$("#groupMC").val();
		var selectedDay=$('.calender_days .daylink_selected').attr("id");
		$("#start_dt").val('');
		$("#finish_dt").val('');
		
		//month(selectedMonth);
		$.post("index.php?process=time_slots",{   selectedDay : selectedDay, memberId : memberId, groupMC : groupMC } ,function(msg)
		{
					$('#time_class').slideUp("fast");
					$('#time_class').html(msg);
					$('#time_class').slideDown('fast');
		});
		$('#new_booking #start_dateTime').html(':<span id="orangeText" style="font-size:11px;"> Please Select a Start Time</span>');
		$('#new_booking #finish_dateTime').html('');
	});
	$("#submitMyBooking").click( function () {
		var groupMC=$("#groupMC").val();
		var groupId=$("#groupId").val();
		var memberId=$("#memberId").val();
		var start_dt=$("#start_dt").val();
		var finish_dt=$("#finish_dt").val();
		var txtMOT=$("#txtMOT").val();
		
		$.post("index.php?process=bookingValidation",{ start_dt : start_dt, finish_dt : finish_dt, groupId : groupId, memberId : memberId, groupMC : groupMC, txtMOT : txtMOT  } ,function(date)
			{
				if(date.length < 25)
				{
					var finishDate=date.split("-");
					var finishDt=finishDate[2];
					var finishMonth=finishDate[1];
					month(finishMonth);
					$.post("index.php?process=time_slots",{ selectedDay : date, memberId : memberId, groupMC : groupMC } ,function(data)
					{	
								$('#time_class').slideUp("fast");
								$('#time_class').html(data);
								$('#time_class').slideDown('fast');
					});
					$('#msgMyBooking').slideUp("fast");
					$('#msgMyBooking').html("Booking Done");
					$('#msgMyBooking').slideDown('fast');
					$.post("index.php?process=my_booking_list",{  memberId : memberId, groupMC : groupMC } ,function(data)
					{
						$('#my_booking').slideUp("fast");
						$('#my_booking').html(data);
						$('#my_booking').slideDown('fast');
					});
					$('#submitMyBooking').hide();
				}
				else
				{
					$('#msgMyBooking').html(date).addClass('error');
				}
			});
	});
	$("#removeBooking").click( function () {
		var currentVal=$("#myBookings").val();
		var groupMC=$("#groupMC").val();
		var response = confirm('Are you sure to Delete this Booking?');
		if(response) {
			$('#errorBooking').html('processing').addClass('required');
			$.post("index.php?process=deleteBooking",{ bookId: currentVal} ,function(msg)
			{
				  if(msg == 0)	//if not deleted
				  {
						$('#errorBooking').html('Error!!Booking Record not Deleted').addClass('error');
				  }
				  else 
				  {
					  $('#errorBooking').html('').removeClass();
						var selectedDay=$('.calender_days .daylink_selected').attr("id");
						var selectedDate=selectedDay.split("-");
						var selectedMonth=selectedDate[1];
						var memberId=$("#memberId").val();
						$("#start_dt").val('');
						$("#finish_dt").val('');
						
						month(selectedMonth);
						$.post("index.php?process=time_slots",{   selectedDay : selectedDay, memberId : memberId, groupMC : groupMC } ,function(msg)
						{
									$('#time_class').slideUp("fast");
									$('#time_class').html(msg);
									$('#time_class').slideDown('fast');
						});
						$.post("index.php?process=my_booking_list",{ memberId : memberId, groupMC : groupMC } ,function(msg)
						{
									$('#my_booking').slideUp("fast");
									$('#my_booking').html(msg);
									$('#my_booking').slideDown('fast');
						});
						$('#new_booking #start_dateTime').html(':<span id="orangeText" style="font-size:11px;"> Please Select a Start Time</span>');
						$('#new_booking #finish_dateTime').html('');
				  }
			});
		} else {
			return false;
		}
	});
	$("#removeAllBooking").click( function () {
		var selectedDt=$('.calender_days .daylink_selected').attr("id");
		var selectedDtArr = selectedDt.split("-");
		var groupMC=$("#groupMC").val();
		var flag = 0;
		$('#myBookings option').each(function(index) {
			var thisId = $(this).attr('id');
			var thisIdArray = thisId.split(",");
			var selStartDt = thisIdArray[0];
			var startDtArray = selStartDt.split("-");
			if((startDtArray[0]+startDtArray[1]+startDtArray[2])==(selectedDtArr[0]+selectedDtArr[1]+selectedDtArr[2]))
			{		
					flag=1;
			}
		});
		if(flag==0)
		{
			alert("No Any of your booking on selected day");
		}
		else
		{
			var response = confirm('Are you sure to Delete all booking of '+selectedDtArr[2]+" . "+selectedDtArr[3]+" . "+selectedDtArr[0]+' ?');
			if(response) {
				$('#errorBooking').html('processing').addClass('required');
				
				$.post("index.php?process=deleteBooking",{ bookId: 'none', bookStartDate : selectedDt, groupMC :groupMC} ,function(msg)
				{	//$('#errorBooking').html(msg).addClass('error');
					  if(msg == 0)	//if not deleted
					  {
							$('#errorBooking').html('Error!!Booking Record not Deleted').addClass('error');
					  }
					  else 
					  {
						  $('#errorBooking').html('').removeClass();
							var selectedDay=$('.calender_days .daylink_selected').attr("id");
							var selectedDate=selectedDay.split("-");
							var selectedMonth=selectedDate[1];
							var memberId=$("#memberId").val();
							$("#start_dt").val('');
							$("#finish_dt").val('');
							
							month(selectedMonth);
							$.post("index.php?process=time_slots",{   selectedDay : selectedDay, memberId : memberId, groupMC : groupMC } ,function(msg)
							{
										$('#time_class').slideUp("fast");
										$('#time_class').html(msg);
										$('#time_class').slideDown('fast');
							});
							$.post("index.php?process=my_booking_list",{ memberId : memberId, groupMC : groupMC } ,function(msg)
							{
										$('#my_booking').slideUp("fast");
										$('#my_booking').html(msg);
										$('#my_booking').slideDown('fast');
							});
							$('#new_booking #start_dateTime').html(':<span id="orangeText" style="font-size:11px;"> Please Select a Start Time</span>');
							$('#new_booking #finish_dateTime').html('');
					  }
				});
			} else {
				return false;
			}
		}
	});
});