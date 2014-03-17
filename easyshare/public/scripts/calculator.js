// JavaScript Document
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
function calculator()
{
	var totalMembers = $('#calTotalMembers').val();
	var assetTotalCost = $('#calAssetTotalCost').val();
	var purchasePrice = parseFloat(assetTotalCost) / parseFloat(totalMembers);
	$('#purchasePrice').html(purchasePrice);
	
	var insurance = $('#calInsurance').val();
	var insuFreq = $('#selectCalinsu').val();
	var monthInsu = (parseFloat(insurance) * 30) / (parseFloat(insuFreq));
	
	var storage = $('#calStorage').val();
	var storageFreq = $('#selectCalStorage').val();
	var monthStorage = (parseFloat(storage) * 30) / (parseFloat(storageFreq));
	
	var fixMaintain = $('#calFixMaintain').val();
	var fixMaintFreq = $('#selectCalFixMaintain').val();
	var monthFixMaint = (parseFloat(fixMaintain) * 30) / (parseFloat(fixMaintFreq));
	
	var varMaintain = $('#calVarMaintain').val();
	var varMaintFreq = $('#selectCalVarMaintain').val();
	var monthVarMaint = (parseFloat(varMaintain) * 30) / (parseFloat(varMaintFreq));
	
	var misc1 = $('#calMisc1').val();
	var misc1Freq = $('#selectCalMisc1').val();
	var monthMisc1 = (parseFloat(misc1) * 30) / (parseFloat(misc1Freq));
	
	var misc2 = $('#calMisc2').val();
	var misc2Freq = $('#selectCalMisc2').val();
	var monthMisc2 = (parseFloat(misc2) * 30) / (parseFloat(misc2Freq));
	
	var misc3 = $('#calMisc3').val();
	var misc3Freq = $('#selectCalMisc3').val();
	var monthMisc3 = (parseFloat(misc3) * 30) / (parseFloat(misc3Freq));
	
	var totalMonthCharge = monthInsu + monthStorage +  monthFixMaint + monthVarMaint + monthMisc1 + monthMisc2 + monthMisc3;
	$('#monthlyCharge').html(totalMonthCharge);
	
}