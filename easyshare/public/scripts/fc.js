// JavaScript Document
function printGroupAC(groupId,groupMonth,thisName)
{
	var acMonthYear = 0;
	$('#acMonthYear > option:selected').each(function() {
		acMonthYear = acMonthYear +  "_" + $(this).val();
	});
	
	 if(thisName=='print')
	 {
		 $.post("index.php?process=viewGroupAC&param1="+groupId+"&param2="+groupMonth+"&param3="+thisName,{ allMonthYear : acMonthYear  },function(data)
		  {	
				printIt(data);
		  });
	 }
	 else
	 {
		 location.href="index.php?process=viewGroupAC&param1="+groupId+"&param2="+groupMonth+"&param3="+thisName+"&param4="+acMonthYear;
	 }
}
var win=null;
  function printIt(printThis)
  {
    win = window.open();
   	self.focus();
    win.document.open();
   /* win.document.write('<'+'html'+'><'+'head'+'><'+'style'+'>');
    win.document.write('body { font-family: Arial, Helvetica, Swiss, Geneva, Sans-serif; font-size: 0.8em;padding: 0px;margin: 0px;text-align:center;} .divRow{ float:left;display:inline;} .clear { clear:both;}');
    win.document.write('<'+'/'+'style'+'><'+'/'+'head'+'><'+'body'+'>');*/
    win.document.write(printThis);
    /*win.document.write('<'+'/'+'body'+'><'+'/'+'html'+'>');*/
    win.document.close();
    win.print();
    win.close();
  }
/*function viewGroupAC(groupId,groupMonth)
{
	window.open('index.php?process=viewGroupAC&param1='+groupId+'&param2='+groupMonth,'Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1000,height=850,left=430,top=150'); return false;
}*/

function showValues() {
      var str = $("#invoiceForm").serialize();
	  $.post("index.php?process=store_single_invoice",{ allInvoiceVal : '&'+str },function(data)
	  {	
			$('#ac_page_content').html(data);
	  });
    }
var flagInvoiceUnit = 0;
var flagInvoiceRates = 0;
function removeAnotherDetail(thisObj)
{
	var thisId = thisObj.id;
	var thisIdDetail = thisId.split("_");
	var thisSeqNo = thisIdDetail[1];
	
	var thisCostValue = $('#txtCost_'+thisSeqNo).val();
	var totalInvoiceVal = $('#totalCostIncoice').val();
	
	if(totalInvoiceVal.length!=0)
	{
		totalInvoiceVal = (parseFloat(totalInvoiceVal)) - (parseFloat(thisCostValue));
		$('#totalCostIncoice').val(totalInvoiceVal);
	}
	
	$('#invoice_table_'+thisSeqNo).remove();
}
function invoiceUnits(thisObj)
{
	var pat = /^[\s0-9]{1,16}$/;
	var thisObjId = thisObj.id;
	var thisObjDetail = thisObjId.split("_");
	var thisId = thisObjDetail[1];
	var thisUnitVal = $('#txtRate_'+thisId).val();
	var thisValue = thisObj.value;
	
	if(thisValue.length!=0)
	{
		if(!pat.test(thisValue)){
			$('#invoiceMsg').html('Incorrect Units').addClass('error');
			flagInvoiceUnit = 1;
		}
		else
		{
			flagInvoiceUnit = 0;
			if(thisUnitVal.length!=0)
			{
				$('#invoiceMsg').html('correct').removeClass();
				
				var thisCost = (parseFloat (thisValue)) * (parseFloat (thisUnitVal));
				$('#txtCost_'+thisId).val(thisCost);
				var totalInvoiceVal = 0;
				$('#invoice_table .individualCost').each(  function() {
					var thisCostValue = $(this).val();	
					if(thisCostValue.length!=0)
					{
						totalInvoiceVal = (parseFloat(totalInvoiceVal)) + (parseFloat(thisCostValue));
						$('#totalCostIncoice').val(totalInvoiceVal);
					}
				});
			}
			else
			{
				$('#invoiceMsg').html('Please Enter Rate per Unit Value').addClass('error');
			}
		}
	}
	else
	{
		$('#invoiceMsg').html('Please Enter Units').addClass('error');
	}
}
function invoiceRateCost(thisObj)
{
	var pat = /^[\s0-9.]{1,16}$/;
	var thisObjId = thisObj.id;
	var thisObjDetail = thisObjId.split("_");
	var thisId = thisObjDetail[1];
	var thisUnitVal = $('#txtUnits_'+thisId).val();
	var thisValue = thisObj.value;
	
	if(thisValue.length!=0)
	{
		if(!pat.test(thisValue)){
			$('#invoiceMsg').html('Incorrect Rate per Unit Value').addClass('error');
			flagInvoiceRates = 1;
		}
		else
		{
			flagInvoiceRates = 0;
			if(thisUnitVal.length!=0)
			{
				$('#invoiceMsg').html('correct').removeClass();
				
				var thisCost = (parseFloat (thisValue)) * (parseFloat (thisUnitVal));
				$('#txtCost_'+thisId).val(thisCost);
				var totalInvoiceVal = 0;
				$('#invoice_table .individualCost').each(  function() {
					var thisCostValue = $(this).val();	
					if(thisCostValue.length!=0)
					{
						totalInvoiceVal = (parseFloat(totalInvoiceVal)) + (parseFloat(thisCostValue));
						$('#totalCostIncoice').val(totalInvoiceVal);
					}
				});
			}
			else
			{
				$('#invoiceMsg').html('Please Enter Units').addClass('error');
			}
		}
	}
	else
	{
		$('#invoiceMsg').html('Please Enter Rate per Unit').addClass('error');
	}
}
function submitInvoice()
{
	var thisSubmitFlag = 0;
	var thisCnt = 0 ;
	var groupId = $('#groupId').val();
	var groupMonth = $('#groupMonth').val();
	
	$('#invoice_table input[type=text]').each(  function() {
		var thisVal=$(this).val();
		var thisId=$(this).attr('id');
		thisCnt = thisCnt + 1;
		
		if(thisVal.length==0)
		{
			$('#invoiceMsg').html('Please Enter All values').addClass('error');
			thisSubmitFlag = 1;
			return false;
		}
	});
	
	if(thisCnt==0)
	{
		$('#invoiceMsg').html('No invoice details to submit').addClass('error');
		return false;
	}
	else if(thisSubmitFlag!=0)
	{
		$('#invoiceMsg').html('Please Enter All values').addClass('error');
		return false;
	}
	else if(flagInvoiceUnit != 0 || flagInvoiceRates != 0)
	{
		return false;
	}
	else
	{
		var str = $("#invoiceForm").serialize();
		
	  	$.post("index.php?process=store_single_invoice",{ allInvoiceVal : '&'+str, groupId : groupId, groupMonth : groupMonth },function(data)
	  	{	
			if(data==1)
			{
				//no member selected
				$('#msgInvoice').html('No any member selected').addClass('error');
			}
			else if(data==2)
			{
				//invoice not inserted
				$('#msgInvoice').html('Error!! invoice not created').addClass('error');
			}
			else if(data==3)
			{
				//invoice not inserted
				$('#msgInvoice').html('Error!! email for invoice not sent').addClass('error');
			}
			else
			{
				displayContent(data,'in_pay_content');
				//displayContent(data,'invoice_content');
			}
			//location.reload();
	  	});
	}
}
function displayContent(data,thisId)
{
	$('#'+thisId).html('');
	$('#'+thisId).slideUp();
	$('#'+thisId).fadeOut();
	$('#'+thisId).fadeIn();
	$('#'+thisId).html(data);
	$('#'+thisId).slideDown();
}
function displayCalender(thisObj)
{
	var this_date = thisObj.id;
	$('.'+this_date).DatePicker({
			format:'Y-m-d',
			date: $('#'+this_date).val(),
			current: $('#'+this_date).val(),
			starts: 1,
			position: 'center',
			onBeforeShow: function(){
				$('#'+this_date).DatePickerSetDate($('#'+this_date).val(), true);
			},
			/*onChange: function(formated, dates){
				$('#'+this_date).val(formated);
					$('#'+this_date).DatePickerHide();
			}*/
			
			onChange: function(formated, dates){
				//$('#'+this_date).val(formated);
				var today = new Date();
				var thisDt = formated.split("-");
				
				var thisYear=thisDt[0];
				var thisMonth=thisDt[1]-1;
				var thisDay=thisDt[2];
				
				var s=new Date();
				s.setFullYear(thisYear,thisMonth,thisDay);
				
				if (s < today)
				{
					var td =today.getFullYear()+"-"+(today.getMonth()+1)+"-"+today.getDate();
					$('#'+this_date).val(td);
				}
				else
				{
					$('#'+this_date).val(formated);
				}
				$('#'+this_date).DatePickerHide();
			}
		});
}
function submitRegular_io()
{
	//var dateFlag=0;
	var start_date = $('#regular_io_startDt').val();
	var end_date = $('#regular_io_endDt').val();
	var reg_amt = $('#regular_io_amt').val();
	var reg_des = $('#regular_io_desc').val();
	var reg_units = $('#io_units').val();
	var regular_type = $('#regular_type').val();
	var groupId = $('#groupId').val();
	var groupMonth = $('#groupMonth').val();
	
	var startDt=start_date.split("-");
	var endDt=end_date.split("-");
	
	var pat=/^[\s0-9.]{1,16}$/;
	
	if( (pat.test(reg_amt)) && (reg_amt!=0)){
			$.post("index.php?process=validDatePeriod",{ reccuranceId : reg_units, start_dt : start_date, end_dt : end_date },function(msg)
			{
				if(msg==1)
				{
					$("#msgRegular_io").html('').removeClass();
					$.post("index.php?process=store_regular_InPay",{ reg_startDt : start_date, reg_endDt : end_date, reg_amt : reg_amt, reg_des : reg_des, reg_units : reg_units, regular_type : regular_type, groupId : groupId, groupMonth : groupMonth },function(data)
					{
						displayContent(data,'in_pay_content');
					});
				}
				else
				{
					$("#msgRegular_io").html('Invalid Date(s)!!!').addClass('error');
				}
			});
	} 
	else 
	{
		$("#msgRegular_io").html('Incorrect Amount').addClass('error');
	}
}
function checkdateDiff(thisObj)
{
	var start_dt = $('#regular_io_startDt').val();
	var end_dt = $('#regular_io_endDt').val();
	var reccuranceId = thisObj;
	
	$.post("index.php?process=validDatePeriod",{ reccuranceId : reccuranceId, start_dt : start_dt, end_dt : end_dt },function(data)
	{
		if(data==1)
		{
			$("#msgRegular_io").html('').removeClass();
		}
		else
		{
			$("#msgRegular_io").html('Invalid Date(s)!!!').addClass('error');
		}
	});
}
function submitSingle_pay()
{
	//var sin_amt_curr = $('#io_currency').val();
	var sin_amt = $('#single_io_amt').val();
	var sin_desc = $('#single_io_desc').val();
	//var single_type = $('#single_type').val();
	var groupId = $('#groupId').val();
	var groupMonth = $('#groupMonth').val();
	var pat=/^[\s0-9.]{1,16}$/;
	if((pat.test(sin_amt))){	
		$("#msgSingle_io").html('').removeClass('error');
		$.post("index.php?process=store_single_pay",{ sin_amt : sin_amt, sin_desc : sin_desc, groupId : groupId, groupMonth : groupMonth },function(data)
		{	
			//displayContent(data,'in_pay_content');
			location.reload();
		});
	} else {
		$("#msgSingle_io").html('Incorrect Amount').addClass('error');
	}
}
function addInvoicerecord()
{
	  var totRecs = $('#totalRecords').val();
	  var totalRecords = parseInt(totRecs);
	  var invoiceRecCnt = totalRecords + 1;
	  var invoiceRecord= $('#invoice_block').html();
	  $('#invoice_block').after(invoiceRecord);
	  $('#invoice_table_'+totalRecords).attr('id','invoice_table_'+invoiceRecCnt);
	  $('#txtService_'+totalRecords).attr('name','txtService_'+invoiceRecCnt);
	   $('#txtUnits_'+totalRecords).attr('name','txtUnits_'+invoiceRecCnt);
	   $('#txtRate_'+totalRecords).attr('name','txtRate_'+invoiceRecCnt);
	   $('#txtCost_'+totalRecords).attr('name','txtCost_'+invoiceRecCnt);
	   $('#txtService_'+totalRecords).attr('id','txtService_'+invoiceRecCnt);
	   $('#txtUnits_'+totalRecords).attr('id','txtUnits_'+invoiceRecCnt);
	   $('#txtRate_'+totalRecords).attr('id','txtRate_'+invoiceRecCnt);
	   $('#txtCost_'+totalRecords).attr('id','txtCost_'+invoiceRecCnt);
	   $('#removeInvoice_'+totalRecords).attr('id','removeInvoice_'+invoiceRecCnt);
	  $('#totalRecords').val(parseInt(totalRecords) +1);
}
function newForm(thisObj) {	
		 var thisId = thisObj.id;
		 var thisObj = thisId.split("_");
		 var sgType = thisObj[0];
		 var ioType = thisObj[1];
		 var groupId = thisObj[2];
		 var groupMonth = thisObj[3];
		 if(ioType=='in' && sgType=='regular')
		 {
			 var io = 'i';
			 ioType = 'InPay';
		 }
		 else if(ioType=='pay'  && sgType=='regular')
		 {
			 var io = 'o';
			 ioType = 'InPay';
		 }
		
		 $.post("index.php?process="+sgType+"_"+ioType,{ io : io, groupId : groupId, groupMonth : groupMonth },function(data)
		 {	
			displayContent(data,'in_pay_content');
		 });
	 }
	 function deleteRegInPay(thisType)
	{
		
		var response = confirm("Are you sure to delete this Record(s)?");
		if(response==true)
		{	
			var thisFlag = 0;
			$('input[type=checkbox]').each(function(){
				if (this.checked) {
					thisFlag = 1;
					var updateRecord = 0;
					var thisId=$(this).val();
					if(thisType=='i')
					{
						var thisTypeNm = "Invoice";
					}
					else if(thisType=='o')
					{
						var thisTypeNm = "Payment";
					}
					
					$.post("index.php?process=checkRegPayIn",{ payIn_id: thisId },function(msg)
					{	
						//$("#in_pay_content #errorMsg_"+thisType).html(msg).addClass('error');
						if(msg==1)
						{
							var finalResponse = confirm("Do you want to create final "+thisTypeNm+" ?");
							if(finalResponse==true)
							{
								updateRecord =1;
							}
						}
						
						$.post("index.php?process=deleteRegPayIn",{ payIn_id: thisId , updateRecord : updateRecord },function(msg)
						{
							//$("#in_pay_content #errorMsg_o").html(msg).addClass('error');
							if(msg==0)
							{
								$("#in_pay_content #errorMsg_o").html("not deleted!!").addClass('error');
							}
							else 
							{	
								location.reload();
							}
						});
					});
			}
			});
			if(thisFlag==0)
			{
				alert("Please Select Record(s)");
				return false;
			}
		}
		else 
		{
			return false;
		}
	}
$(document).ready(function () {
	$('.single_reg_io').click(  function() {
		 var thisId = $(this).attr('id');
		 var thisObj = thisId.split("_");
		 var sgType = thisObj[0];
		 var ioType = thisObj[1];
		 var groupId = thisObj[2];
		  var groupMonth = thisObj[3];
		 
		 if(ioType=='In' && sgType=='regular')
		 {
			 var io = 'i';
		 }
		 else if(ioType=='Pay' && sgType=='regular')
		 {
			 var io = 'o';
		 }
		
		 $.post("index.php?process="+sgType+"_"+ioType+"_list",{ io : io, groupId : groupId, groupMonth : groupMonth },function(data)
		{
			displayContent(data,'in_pay_content');
		});
	 });
	
	$('#create_invoice').click(  function() {
		var groupId = $(this).attr('name');
		$.post("index.php?process=create_invoice",{ groupId : groupId },function(data)
		{
			$('#invoice_content').html(data);
		});
	});
	/*$('.sr_io_list').click(  function() {
		var thisId = $(this).attr('id');
		var thisObj = thisId.split("_");
		var groupId = thisObj[0];
		var groupMonth = thisObj[1];
		
		$.post("index.php?process=sr_io_list",{ groupId : groupId, groupMonth : groupMonth },function(data)
		{	
			displayContent(data,'in_pay_content');
		});
	});*/
});