var flagusageValue=0;
var flagBilling=0;
function activeInactive(clickOption,opt)
{	
	if(clickOption=='nonActive'){
		// Remove all parent and links inactive class.
		$(opt).removeClass().parent().removeClass();
		//  add active state to the link and parent item
		$(opt).addClass("nowActive").parent().addClass("nowActive");
	} else {
		// Remove all parent and links active class.
		$(opt).removeClass().parent().removeClass();
		// Now add inactive state to the link and parent item
		$(opt).addClass("nonActive").parent().addClass("nonActive");
	}
}
function removeTempUsage(thisObj)
{
	/*var t = thisObj.id;
	var removeObj = t .split("_");
	var parObj = removeObj[1];
	$('#parUsageUnit_'+parObj).remove();*/
	$('#usageUnitBlock').hide();
	$('#usageUnit').show();
}
function removeTempSE(thisObj)
{
	var t = thisObj.id;
	var removeObj = t .split("_");
	var parObj = removeObj[1];
	$('#parSEUnit_'+parObj).remove();
}
function removeMonthCost(thisObj)
{
	var t = thisObj.id;
	var removeObj = t .split("_");
	var parObj = removeObj[1];
	$('#paraMonthCost_'+parObj).remove();
}
function removeVariantCost(thisObj)
{
	var t = thisObj.id;
	var removeObj = t .split("_");
	var parObj = removeObj[1];
	$('#paraVariantCost_'+parObj).remove();
}
function currentValValid(thisValue)
{
	var pat = /^[\s0-9.]{1,16}$/;
	
	if((pat.test(thisValue))){
		$('#errorUsage').html('').removeClass();
		flagusageValue=0;
	} else {
		$('#errorUsage').html('').removeClass();
		$('#errorUsage').html('Invalid').addClass('error');
		flagusageValue=1;
	}
}
function billCostValid(thisValue)
{	
	var pat = /^[\s0-9.]{1,16}$/;
	
	if((pat.test(thisValue))){
		$('#errorBilling').html('').removeClass();
		flagBilling=0;
	} else {
		$('#errorBilling').html('').removeClass();
		$('#errorBilling').html('Invalid').addClass('error');
		flagBilling=1;
	}
}
$(document).ready(function () {
	$("#createGroupOpt #exist").click( function (event) {
        // First disable the normal link click
			var clickOption=$(this).parent().attr('class');
			activeInactive("nowActive",'#createGroupOpt #scratch');
			activeInactive(clickOption,'#createGroupOpt #exist');
			if($("#listing").val()==0)
			{
				$("#submitGropuOpt").hide();
			} else {
				$("#submitGropuOpt").show();
			}
			$('#existBlock').slideToggle("normal");
        	$('#groupOpt').val('exist');
			return false;
    });
	$("#createGroupOpt #scratch").click( function () {
        
		 var clickOption=$(this).parent().attr('class');
		 
		 $("#submitGropuOpt").show();
		 $('#existBlock').slideUp("fast");
		 activeInactive("nowActive",'#createGroupOpt #exist');
		activeInactive(clickOption,'#createGroupOpt #scratch');
		$('#groupOpt').val('scratch');
		return false;
    });
	
	  $("#groupImg").change( function () {
		  //$('#groupImage').submit();
		  //var photo = document.getElementById("groupImg"); 
			//var file  = photo.files[0];
			//var h = jQuery("#groupImg").files[0].getAsDataURL();  
			
			//alert(file.getAsDataURL());
			
			//$('#groupImage').submit();
			var img = document.getElementById("groupImg");
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
					$("msgimage").html("Invalid Image");
					$("msgimage").addClass('error');
					return false;
				}
				else
				{
					$("msgimage").html("");
					$("msgimage").removeClass();
					 $('#groupImage').submit();
					return true;
				}
	  });
	  $(".deleteGroupImage").click( function (event) {
		  // First disable the normal link click
		  event.preventDefault();
		  var imageId=$(this).attr('id');
		  $.post("index.php?process=deleteImage",{ imageId: imageId} ,function(msg)
			{
				var data = xmlhttp.responseText;
				data = msg.split("-");
				var groupId = data[0];
				var errormsg = data[1];
				
				if(errormsg.length==0)
				  {
					  location.reload();
					  //var img = document.getElementById('groupImg');
						//img.src='public/images/noImage.jpg';
				  }
				  else 
				  {
						$('#msgimage').html('Image not not Deleted!!').addClass('error');
				  }
			});
	  })
	 
    $("#rulesList li > a").click( function (event) {
        // First disable the normal link click
        event.preventDefault();
		
		var optRule=$(this).attr('id');
		var clickOption=$(this).parent().attr('class');
		
		if(optRule=='rota')
		{
			activeInactive("nowActive",'#rulesList #rule');
			activeInactive(clickOption,'#rulesList #rota');
			
			$('.txtBookingRules #rule').slideUp("fast");
			$('.txtBookingRules #ruleActive').slideUp("fast");
			$('.txtBookingRules #rota').slideToggle("normal");
			$('.txtBookingRules #rotaActive').slideToggle("normal");
		}
		if(optRule=='rule')
		{	
			activeInactive("nowActive",'#rulesList #rota');
			activeInactive(clickOption,'#rulesList #rule');
			
			$('.txtBookingRules #rota').slideUp("fast");
			$('.txtBookingRules #rotaActive').slideUp("fast");
			$('.txtBookingRules #rule').slideToggle("normal");
			$('.txtBookingRules #ruleActive').slideToggle("normal");
		}

        return false;
    });
	$('#limittotalBooking').click(  function(event) {
		var clickOption = $(this).parent().attr('class');
		activeInactive(clickOption,this);
		$('.txtBookingRules #limittotalblock').slideToggle("normal");
	 });
	$('#limitWeekendBooking').click(  function(event) {
		var clickOption = $(this).parent().attr('class');
		activeInactive(clickOption,this);
		$('.txtBookingRules #limitWeekendblock').slideToggle("normal");
	 });
	 $('#limitExtendBooking').click(  function(event) {
		var clickOption = $(this).parent().attr('class');
		activeInactive(clickOption,this);
		$('.txtBookingRules #limitExtendblock').slideToggle("normal");
	 });
	 
	 /* Member Previliges */
	$('#memberList input[type=radio]').click(  function(event) {
		var name;
		if($(this).attr('name')=='adminRadio')
		{
			name='adminRadio';
		}
		if($(this).attr('name')=='fcRadio')
		{
			name='fcRadio';
		}
		 if($(this).attr('name')=='mcRadio')
		{
			name='mcRadio';
		}
		$('#memberList input[name='+name+']').parent().removeClass("activeOption");
		$('#memberList input[name='+name+']').parent().addClass("inActiveOption");
		if($(this).is(':checked')==false){
			$(this).parent().removeClass("activeOption");
			$(this).addClass("memopt").parent().addClass("inActiveOption");
		} else {
			$(this).parent().removeClass("inActiveOption");
			$(this).addClass("memopt").parent().addClass("activeOption");
		}
		return true;
  });
  /* Member Previliges */
  
  /* start usage monitors */
  
 /* $("#usageUnit").click(function () {
	  var thisUsages = $('#usage').val();
	  var thisTotal = 0;
	  if(thisUsages!='none')
	  {
		  thisTotal = parseInt (thisUsages);
	  }
	  var usageUnitCnt = thisTotal + 1;
	  var usageUnitBlock= $("#usageUnitBlock").html();
	  $(this).before(usageUnitBlock);
	  $('#txtUsageUnit_'+thisTotal).attr('name','txtUsageUnit_'+usageUnitCnt);
	  $('#txtUsageUnit_'+thisTotal).attr('id','txtUsageUnit_'+usageUnitCnt);
	  $('#remove_'+thisTotal).attr('id','remove_'+usageUnitCnt);
	  $('#parUsageUnit_'+thisTotal).attr('id','parUsageUnit_'+usageUnitCnt);
	  $('#usage').val(usageUnitCnt);
    });*/
	
	 $("#usageUnit").click(function () {
		 $('#timeUsage').val(1);
		 $('#usageUnitBlock').show();
		 $(this).hide();
	 });
	
	$("#startEndUnit").click(function () {
		
	  var thisUsages = $('#startEnd').val();
	  var thisTotal = 0;
	  if(thisUsages!='none')
	  {
		  thisTotal = parseInt (thisUsages);
	  }
	  var startEndCnt = thisTotal + 1;	
	  var startEndUnitBlock= $("#startEndUnitBlock").html();	
	  $('#addUsageMonitorLink').before(startEndUnitBlock);
	  $('#txtStartEndUnit_'+thisTotal).attr('name','txtStartEndUnit_'+startEndCnt);
	  $('#currentMonitor_'+thisTotal).attr('name','currentMonitor_'+startEndCnt);
	  $('#txtStartEndUnit_'+thisTotal).attr('id','txtStartEndUnit_'+startEndCnt);
	  $('#currentMonitor_'+thisTotal).attr('id','currentMonitor_'+startEndCnt);
	  $('#removeSE_'+thisTotal).attr('id','removeSE_'+startEndCnt);
	  $('#parSEUnit_'+thisTotal).attr('id','parSEUnit_'+startEndCnt);
	  $('#startEnd').val(startEndCnt);
    });
	/* end usage monitors */
	
	/* billing preferences */
	 $("#monthlyUsage").click(function () {
		  var thisUsages = $('#monthBill').val();
		  var thisTotal = 0;
		  if(thisUsages!='none')
		  {
			  thisTotal = parseInt (thisUsages);
		  }
		 var fixCnt = thisTotal + 1;
		 var monthCharge= $("#monthCharge").html();
	  	 $(this).before(monthCharge);
		 $('#monthCurrency_'+thisTotal).attr('name','monthCurrency_'+fixCnt);
		 $('#monthCost_'+thisTotal).attr('name','monthCost_'+fixCnt);
		 $('#monthDesc_'+thisTotal).attr('name','monthDesc_'+fixCnt);
		 $('#monthCurrency_'+thisTotal).attr('id','monthCurrency_'+fixCnt);
		 $('#monthCost_'+thisTotal).attr('id','monthCost_'+fixCnt);
		 $('#monthDesc_'+thisTotal).attr('id','monthDesc_'+fixCnt);
		 $('#paraMonthCost_'+thisTotal).attr('id','paraMonthCost_'+fixCnt);
		 $('#removeMonthCost_'+thisTotal).attr('id','removeMonthCost_'+fixCnt);
		 $('#monthBill').val(fixCnt);
    });
	
	$("#variantUsage").click(function () {
	  var thisUsages = $('#usageBill').val();
	  var thisTotal = 0;
	  if(thisUsages!='none')
	  {
		  thisTotal = parseInt (thisUsages);
	  }
	  var usageCnt = thisTotal + 1;
	  var usageCharge= $("#usageCharge").html();
	  $('#monthlyUsage').before(usageCharge);
	  $('#usageList_'+thisTotal).attr('name','usageList_'+usageCnt);
	  $('#usageCurrency_'+thisTotal).attr('name','usageCurrency_'+usageCnt);
	  $('#usageCost_'+thisTotal).attr('name','usageCost_'+usageCnt);
	  $('#usageList_'+thisTotal).attr('id','usageList_'+usageCnt);
	  $('#usageCurrency_'+thisTotal).attr('id','usageCurrency_'+usageCnt);
	  $('#usageCost_'+thisTotal).attr('id','usageCost_'+usageCnt);
	  $('#paraVariantCost_'+thisTotal).attr('id','paraVariantCost_'+usageCnt);
	  $('#removeVariantCost_'+thisTotal).attr('id','removeVariantCost_'+usageCnt);
	  $('#usageBill').val(usageCnt);
    });
	/* end billing preferences */
	
	/* Search Member */
	$('#searchMember').click(function () {
		var searchText = $('#memSearch').val();
		var searchField=$('#searchFields').val();
		var groupId = $('#groupId').val();
		
		$.post("index.php?process=searchMember",{ searchText: $('#memSearch').val(),  searchField:$('#searchFields').val(), groupId:$('#groupId').val()} ,function(data)
		{
			 $('#searchResult').html(data);
		});
		return false;
	}); 
	/* Search Member*/
	
	/* change member */
	$('.change').click(function () {
		var memId = $(this).attr('id');
		var groupId = $('#groupId').val();
		//return false;
		window.open('index.php?process=member_page&param1='+groupId+'&param2='+memId,'Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=850,height=850,left=430,top=350'); return false; 
		//location.href='index.php?process=addMember_page';
	}); 
	
	/* delete member */
	$('.removeMember').click(function () {
		var memId = $(this).attr('id');
		var groupId = $('#groupId').val();
		if($('#memberList input[id='+memId+'_a]').is(':checked')==true)	{
			var prev_a = memId;	} else {
				prev_a = 0; }
		if($('#memberList input[id='+memId+'_m]').is(':checked')==true){
			var prev_m = memId;  } else {
				prev_m = 0; }
		if($('#memberList input[id='+memId+'_f]').is(':checked')==true)	{
			var prev_f = memId;    } else {
				prev_f = 0; }
		$.post("index.php?process=deleteMember",{ memId: memId, groupId:groupId, prev_a:prev_a, prev_m:prev_m, prev_f:prev_f} ,function(msg)
		{		//$('#errorMsg').html(msg).addClass('error');
			  if(msg == 1)	//if not deleted
			  {
					location.reload();
			  }
			  else 
			  {
					$('#errorMsg').html('Member Record not Deleted!!').addClass('error');
			  }
		});
	}); 
	
	/* delete usage monitors */
	$('.removeUsage').click(function () {
		var thisId = $(this).attr('id');
		var thisIdArray = thisId.split("_");
		var usageId = thisIdArray[0];
		var thisActive = thisIdArray[1];
		var groupId = $('#groupId').val();
		var response = confirm("Are you sure to delete this Record(s)?");
		if(response==true)
			{
			$.post("index.php?process=checkUsageBooking",{ usageId: usageId, groupId : groupId } ,function(msg)
			{	  
				//$('#errorUsage').html(msg).addClass('error');
				var response2 = true;
				if(msg==1 && thisActive=='y')
				{
				  var response2 = confirm("All usage data are not filled up, deletion will delete all previous usage data realated to this usage monitor.");
				}
				  //if(msg == 0)	//delete
				  if(response2==true)
				  {
						$.post("index.php?process=deleteUsageMonitor",{ usageMonitorId: usageId, usageActive : thisActive } ,function(msg)
						{	  //$('#errorUsage').html(msg).addClass('error');
							  if(msg == 0)	//if not deleted
							  {
									$('#errorUsage').html('Usage Record not Deleted!!').addClass('error');
							  }
							  else 
							  {
									location.reload();
							  }
						});
				  }
				  /*else 
				  {
						$('#errorUsage').html('Usage Record can not delete because all usage data are not filled up!!!').addClass('error');
				  }*/
			});
		}
	}); 
	/*$('.activeUsage').click(function () {
		var usageId = $(this).attr('id');
		
		$.post("index.php?process=activeUsage",{ usageId: usageId} ,function(msg)
		{
			location.reload();
		});
	}); */
	$('.removeStartEnd').click(function () {
		var thisId = $(this).attr('id');
		var thisIdArray = thisId.split("_");
		var seId = thisIdArray[0];
		var thisActive = thisIdArray[1];
		var groupId = $('#groupId').val();
		var response = confirm("Are you sure to delete this Record(s)?");
		if(response==true)
		{	
			$.post("index.php?process=checkUsageBooking",{ usageId: seId, groupId : groupId } ,function(msg)
			{	  
				//$('#errorUsage').html(msg).addClass('error');
				var deleteFlag = 0;
				 if(msg == 1 && thisActive=='y')	//delete
				 {
					 var response = confirm("All usage data are not filled up, deletion will delete all previous usage data realated to this usage monitor.");
					 if(response==true)
					 {	
					 	deleteFlag =1;
					 }
				 }
				  if(msg == 0 || deleteFlag==1)	//delete
				  {
						$.post("index.php?process=deleteUsageMonitor",{ usageMonitorId: seId , usageActive : thisActive} ,function(msg)
						{	//$('#errorUsage').html(msg).addClass('error');
							  if(msg >= 1)	//if not deleted
							  {
									location.reload();
							  }
							  else 
							  {
									$('#errorUsage').html('Error!! Usage Record not Deleted!!').addClass('error');
							  }
						});
				  }
			});
		}
	}); 
	/*$('.activeStartEnd').click(function () {
		var seId = $(this).attr('id');
		$.post("index.php?process=activeStartEnd",{ startEndId: seId} ,function(msg)
		{
			location.reload();
		});
	}); */
	$('.removeMonthBill').click(function () {
		var monthBillId = $(this).attr('id');
		$.post("index.php?process=deleteMonthBill",{ monthBillId: monthBillId} ,function(msg)
		{
			  if(msg == 0)	//if not deleted
			  {
					$('#errorUsage').html('Usage Record not Deleted!!').addClass('error');
			  }
			  else 
			  {
					location.reload();
			  }
		});
	}); 
	$('.removeUsageBill').click(function () {
		var usageBillId = $(this).attr('id');
		$.post("index.php?process=deleteUsageBill",{ usageBillId: usageBillId} ,function(msg)
		{
			  if(msg == 0)	//if not deleted
			  {
					$('#errorUsage').html('Usage Record not Deleted!!').addClass('error');
			  }
			  else 
			  {
					location.reload();
			  }
		});
	}); 
	
	$('#addMember').submit( function() {
		//alert("addmem");
		
		$('#addMember').submit();
		//window.close();
	});
	$('#groupBookRules').submit( function() {
		var groupFlag = 1;
		if($('#rulesOptions #rule').parent().attr('class')=='nowActive'){
			$('#bookRule').val('rule');
			$('#msgRules').html('').removeClass('error');
			if($('#limittotalBooking').parent().attr('class')=='nowActive' || $('#limitWeekendBooking').parent().attr('class')=='nowActive' || $('#limitExtendBooking').parent().attr('class')=='nowActive'){
				if($('#limittotalBooking').parent().attr('class')=='nowActive'){
					if($('#txttotalBooking').val().length==0){
						var response = confirm('Are you sure to not enter Total Booking Days?');
						if(response) {
							$('#msgTotalBooking').html('').removeClass('error');
							$('#txttotalBooking').removeClass('error_focus_required');
							groupFlag = 1;  return true; 
						} else {
							$('#msgTotalBooking').html('Please Enter days').addClass('error');
							$('#txttotalBooking').addClass('error_focus_required');
							groupFlag = 0;
						}
					}else{ groupFlag = 1;  return true; }
				}
				if($('#limitWeekendBooking').parent().attr('class')=='nowActive'){
					if($('#txtWeekBooking').val().length==0){
						var response = confirm('Are you sure to not enter Week Booking Days?');
						if(response) {
							$('#msgWeekBooking').html('').removeClass('error');
							$('#txtWeekBooking').removeClass('error_focus_required');
							groupFlag = 1;  return true; 
						} else {
							$('#msgWeekBooking').html('Please Enter days').addClass('error');
							$('#txtWeekBooking').addClass('error_focus_required');
							groupFlag = 0;
						}
					}else{ groupFlag = 1;  return true; }
				}
				if($('#limitExtendBooking').parent().attr('class')=='nowActive'){
					if($('#txtTotalHolidays').val().length==0 || $('#txtHolidays').val().length==0){
						var response = confirm('Are you sure to not enter Holiday Booking Days information?');
						if(response) { 
							$('#msgHolidayBooking').html('').removeClass('error');
							$('#txtTotalHolidays').removeClass('error_focus_required');
							$('#msgEachHolidayBooking').html('').removeClass('error');
							$('#txtHolidays').removeClass('error_focus_required');
							groupFlag = 1;  return true;  	
						}else{
							if($('#txtTotalHolidays').val().length==0){
								$('#msgHolidayBooking').html('Please Enter days').addClass('error');
								$('#txtTotalHolidays').addClass('error_focus_required');
								groupFlag = 0;
							}
							if($('#txtHolidays').val().length==0){
								$('#msgEachHolidayBooking').html('Please Enter days').addClass('error');
								$('#txtHolidays').addClass('error_focus_required');
								groupFlag = 0;
							}	
						}
					}
					else
					{ groupFlag = 1;  return true;  }
				}
			} else {
				var response = confirm('Are you sure to not enter Booking Rules information?');
				if(response) { groupFlag = 1; return true; }else{
					$('#msgRules').html('Please Select your Rules').addClass('error');
					groupFlag = 0;
				}
				//return false;
			}
		}
		if($('#rulesOptions #rota').parent().attr('class')=='nowActive'){
				$('#bookRule').val('rota');
				if($('#selectTime').val().length==0){
					var response = confirm('Are you sure to not enter Allocation Rota information?');
					if(response) { groupFlag = 1;  return true;  }else{
						$('#msgRules').html('Please Select your Allocation Rota').addClass('error');
						groupFlag = 0;
					}
				}else{ groupFlag = 1;  return true;  }
		}
		
		
			
		if(groupFlag == 0){ return false; } else { 
			$('#groupBookRules').submit();
			return true; 
		}
		return false;
	});
	$('#groupBasicInfo').submit( function() {	
			var storedImageSrc = document.getElementById('storedImageSrc').value;
			var imageInfoSrc = document.getElementById('groupImageFiles').innerHTML;
			if($('#groupTitle').val().length==0)
			{
				$('#msgGroupTitle').html('Title Required').addClass('error'); 
				return false;
			}
			else if(imageInfoSrc.length==0 && storedImageSrc=='public/images/noImage.jpg')
			{	
				$('#imageError').html('Image Mandatory').addClass('error'); 
				return false;
			}
			else
			{
				if(imageInfoSrc.length!=0)
				{
					  var groupImgName = $('#groupImgName').val();
					  var groupImgSize = $('#groupImgSize').val();
					  if(groupImgName.length!=0  && groupImgSize.length!=0 && groupImgName!='undefined' && groupImgSize!='undefined')
					  {
						  $('#groupImageName').val(groupImgName);
						  $('#groupImageSize').val(groupImgSize);
					  }
				}
				$('#groupBasicInfo').submit();
				return true;
			}
	 });
	$('#groupUsageMonitor').submit( function() {
		 if(flagusageValue!=0)
		 {
			 return false;
		 }
		 else
		 {
			 return true;
		 }
	 });
	$('#groupBillingRules').submit( function() {
		 if(flagBilling!=0)
		 {
			 return false;
		 }
		 else
		 {
			 return true;
		 }
	 });
	
	$('.btnGroupSetUp').click( function() {
		if($('#groupTitle').val().length==0)
		{
			$('#msgGroupTitle').html('Title Required').addClass('error'); 
			return false;
		}
		else
		{
			$('#storeGroupBasic').click();
			//$('#groupBasicInfo').submit();
			 $('#storeBookRules').click();
			//$('#groupBookRules').submit();
			$('#storeusageMonitors').click();
			//$('#groupUsageMonitor').submit();
			$('#storeBillingRules').click();
			//$('#groupBillingRules').submit();
			var thisStatus = $(this).attr('name');
			var groupId = $('.btnGroupSetUp').attr('id');
			location.href="index.php?process=group_activation&param1="+groupId+"&param2="+thisStatus;
		}
	});
});