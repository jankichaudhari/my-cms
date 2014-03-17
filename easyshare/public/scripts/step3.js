// JavaScript Document
$(document).ready(function() { 
						   
	$('#txtCostPerShare').bind("keyup focus  blur", function() {	
			var thisShareValue = $('#txtCostPerShare').val();
			checkNumeric(thisShareValue,'msgCostShare','c');
	 });
			
	$('#txtGroupSize').bind("keyup focus  blur", function() {	
			var thisGroupVal = $('#txtGroupSize').val();
			checkNumeric(thisGroupVal,'msgGroupSize','g');
	 });
	
	$('#txtFees').bind("keyup focus  blur", function() {
			var thisFeesValue = $('#txtFees').val();
			checkNumeric(thisFeesValue,'msgFees','c');
	 });
	
	$('#txtUsage').bind("keyup focus  blur", function() {	
			var thisUsageValue = $('#txtUsage').val();
			checkNumeric(thisUsageValue,'msgUsageCharge','c');
	 });

});
function checkNumeric(thisValue,msgId,type)
{	//alert(thisValue+"  "+msgId+"  "+type);
	var thisFlag = true;
	if(type=='g')
	{
		var pat = /^[\s0-9]{1,16}$/;
	}
	else
	{
		var pat = /^[\s0-9.]{1,16}$/;
	}
	if(thisValue.length!=0)
	{	
		if(pat.test(thisValue))
		{
			$('#'+msgId).html("").removeClass();
		}
		else
		{
			$('#'+msgId).html("Incorrect").addClass("error");
			thisFlag = false;
		}
	}
	else
	{
		$('#'+msgId).html("").removeClass();
	}
	
	return thisFlag;
}
function addNewOption(thisObj)
{
	var thisId = thisObj.id;
	var thisVal =thisObj.value;
	
	if(thisVal==0)
	{
		$('#other'+thisId).append($("<input/>").attr("type","text").attr("name","txtOther"+thisId).attr("value","").attr("id","txtOther"+thisId).attr("maxlength","50"));
		$('#other'+thisId).append($("<input/>").attr("type","button").attr("name","saveOther"+thisId).attr("Value","Add").attr("onClick","javascript:saveOtherOption('"+thisId+"');")).css("text-align", "center");
		$('#other'+thisId).append($('<br/><span id="msgSave'+thisId+'" style="text-align:right"><span/>'));
	}
	else
	{
		$('#other'+thisId).html('');
	}
}
function saveOtherOption(thisId)
{	
	var otherVal = $('#txtOther'+thisId).val();
	
	if(otherVal.length!=0)
	{
		$.post("index.php?process=addOtherOption",{ thisType : thisId, thisVal : otherVal } ,function(msg)
		{
			if(msg==0)
			{
				$('#msgSave'+thisId).html('Error!! Value not added').addClass("error");
			}
			else
			{
				var msgType = msg.substr(1,5);
				var thisMsg = msg.substr(6);
				if(msgType=="_exi_")
				{
					$('#msgSave'+thisId).html('"'+otherVal+'" is already exist').addClass("error");
				}
				else if(msgType=='_new_')
				{
					//$('#hiddenFees').val(thisMsg);
					$('#other'+thisId).html('');
					$('#'+thisId+' #selOther'+thisId).remove();
					$('#'+thisId).append($("<option></option>").attr("id","selOther"+thisId).attr("value",thisMsg).text(otherVal));
				}
			}
		});
	}
	else
	{
		$('#msgSave'+thisId).html('Please Enter Value').addClass("error");
	}
}
function locationPostcode(thisObj)
{
	$('#townName').val('');
	$('#selectPostcode').val('');
	$('#suggestTown').val('');
	$('#postCodeId').val('');
	if(thisObj.value==225)
	{
		$('#postcodeSpan').show();
		$('#townSpan').hide();
	}
	else
	{
		$('#postcodeSpan').hide();
		$('#townSpan').show();
	}
}
function postcodeTown(thisValue)
{
	var thisValArry = thisValue.split(" ");
	var postcodeVal = thisValArry[0];
	$.post("index.php?process=checkPostCode",{postcodeVal : postcodeVal },function(data)
	{
		if(data==0)
		{
			$('#msgPostcode').html('Invalid').addClass('error');
			$('#townSpan').hide();
		}
		else
		{	
			var thisResult = data.split("_");
			if(thisResult[0].length!=0)
			{
				$('#msgPostcode').html('');
				$('#townSpan').show();
				$('#suggestTown').val(thisResult[1]);
				$('#postCodeId').val(thisResult[0]);
			}
			else
			{
				$('#msgPostcode').html('Invalid').addClass('error');
				$('#townSpan').hide();
			}
		}
	});
	//$('#townName').val(thisTownName);
}
function calculateStep3()
{
	var calMainAmt = $('#calMainAmt').val();
	var calDivAmt = $('#calDivAmt').val();
	var result = (parseFloat(calMainAmt)) / (parseFloat(calDivAmt));
	var calMainAmt = $('#calResAmt').val(result);
}
function checkDigits(thisObj)
{
	var thisVal = thisObj.value;
	var thisValLen = ((thisVal.length)-1);
	var pat = /^[\s0-9.]{1,16}$/;
	if(!pat.test(thisVal))
	{
		var tempVal = '';
		for(var i=0; i<thisValLen;i++)
		{
			tempVal=tempVal+thisVal[i];
		}
		 thisObj.value = tempVal;
	}
}
function store_step3(listId)
{	
	//validation
	var postCodeFlag =1 ;
	var countryId = $('#selectCountry').val();
	if(countryId==225)
	{
		var postcodeVal = $('#postCodeId').val();
		if(postcodeVal.length==0 )
		{
			postCodeFlag = 0;
		}
	}
	
	var thisShareValue = $('#txtCostPerShare').val();
	var shareValue = checkNumeric(thisShareValue,'msgCostShare','c');
	var thisGroupVal = $('#txtGroupSize').val();
	var groupSzValue = checkNumeric(thisGroupVal,'msgGroupSize','g');
	var thisFeesValue = $('#txtFees').val();
	var feesValue = checkNumeric(thisFeesValue,'msgFees','c');
	var thisUsageValue = $('#txtUsage').val();
	var usageValue = checkNumeric(thisUsageValue,'msgUsageCharge','c');
	
	if(shareValue==true && groupSzValue==true && feesValue==true && usageValue==true)
	{
		var otherFlag = 0;
		var selectFees = $('#Fees').val();
		if(selectFees==0)
		{
			otherFlag =1 ;
			$('#msgSaveFees').html('Please Enter Fees').addClass("error");
		}
		var selectUsageCharge = $('#UsageCharge').val();
		if(selectUsageCharge==0)
		{
			otherFlag = 1;
			$('#msgSaveUsageCharge').html('Please Enter Usage Charge').addClass("error");
		}
		if(otherFlag==1 || postCodeFlag==0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		return false;
	}
}