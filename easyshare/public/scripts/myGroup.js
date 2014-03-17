// JavaScript Document
function changePayStatus(thisobj)
{
	var thisId = thisobj.id;
	var thisValue = String(thisobj.value);
	
	$.post("index.php?process=changePayStatus",{   thisInvoiceId : thisId, thisInvoiceValue : thisValue } ,function(msg)
	{	
			if(msg==0)
			{
				$('#errorInvoices').html('Error!! Status not changed').addClass("error");
			}
			else
			{
				$('#errorInvoices').html('').removeClass();
			}
	});
}
function viewInvoice(thisId,thisType)
{
	if(thisType=='download')
	{
		 location.href="index.php?process=viewInvoice&invoiceId="+thisId+"&type="+thisType;
	}
	else if(thisType=='print')
	{	//window.open('index.php?process=viewInvoice&invoiceId='+thisId+'&type='+thisType);
		 $.post("index.php?process=viewInvoice&invoiceId="+thisId+"&type="+thisType,{  },function(data)
		  {	
				printIt(data);
		  });
	}
	else
	{
		window.open('index.php?process=viewInvoice&invoiceId='+thisId+'&type='+thisType,'Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1000,height=850,left=430,top=150'); 
		return false;
	}
}