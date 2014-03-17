<!--<form name="invoiceForm" id="invoiceForm" action="index.php?process=store_single_invoice" method="post" onsubmit="javascript:return submitInvoice();">-->
<form name="invoiceForm" id="invoiceForm">
<input type="hidden" name="totalRecords" id="totalRecords" value="0"/>
<input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId; ?>"/>
<input type="hidden" name="groupMonth" value="<?php echo $groupMonth; ?>" id="groupMonth"/>
<h4>Single Invoice</h4>
<span id="msgInvoice"></span>
<div style="width:650px;border:solid thin #FCCF12;padding:5px 5px 5px 5px;">
<div style="width:650px;">
	<span id="invoiceMsg"></span>
</div>
<div style="width:650px;">
    <p>To:
    <select name="groupMemberList" id="groupMemberList" multiple="multiple" size="1" onblur="javascript:$(this).attr('size',1);" onfocus="javascript:$(this).attr('size',4);">
    <?php
	foreach($groumMembersInfo as $m=>$val)
	{
		$thisMemId=$groumMembersInfo[$m]['id'];
		$user_id=$groumMembersInfo[$m]['user_id'];
		$userInfo=$this->model->getResult("auth_profile",""," WHERE id='$user_id'","");	
		$mem_firstNm=$userInfo[0]['first_name'];
		$mem_LastNm=$userInfo[0]['last_name'];
		$fullUserName=$mem_firstNm ."  " .$mem_LastNm;
		?><option value="<?php echo $thisMemId; ?>"><?php echo $fullUserName; ?></option><?php
	}
	?>
    </select>
    </p>
    <p>
    Date&nbsp;:&nbsp;<?php echo date('d-m-Y'); ?></p>
</div>
	
    <div class="divRow" style="display:table-cell;width:550px;">
    <span style="font-size:16px;font-weight:bold;">Invoice Details</span>
    </div>
    <div class="divRow" style="display:table-cell;width:100px;">
    <span id="smallText"><a href="javascript:void(0);" id="addRecord" onclick="addInvoicerecord();">add another</a></span>
    </div>

<div style="width:650px;border:solid thin #FCCF12;">
    <div class="divRow" style="width:344px;">Service/Description</div>
    <div class="divRow" style="width:155px;">Units</div>
    <div class="divRow" style="width:69px;">Rate/Unit</div>
    <div class="divRow" style="width:70px;">Cost</div>
    <div class="divRow" style="width:12px;"></div>
    <div class="clear"></div>
</div>

<div id="invoice_table">
<div id="invoice_block">
<div style="width:650px;" id="invoice_table_0"><div>
    <div class="divRow" style="width:344px;"><input type="text" name="txtService_0" id="txtService_0" value="" size="37"/></div>
    <div class="divRow" style="width:155px;"><input type="text" name="txtUnits_0" id="txtUnits_0" value="" size="15"  onkeyup="javascript:invoiceUnits(this);"/></div>
    <div class="divRow" style="width:69px;"><input type="text" name="txtRate_0" id="txtRate_0" value="" size="5"  onkeyup="javascript:invoiceRateCost(this);"/></div>
    <div class="divRow" style="width:70px;"><input type="text" name="txtCost_0" id="txtCost_0" value="" readonly="readonly" class="individualCost" size="5"/>
    </div>
    <div class="divRow" style="width:12px;"><a href="javascript:void(0);" id="removeInvoice_0" onclick="removeAnotherDetail(this);">X</a></div>
    <div class="clear"></div>
</div></div>
</div>
</div>

<div style="width:650px;" align="right">
    <div style="width:650px;">Total&nbsp;:&nbsp;<input type="text" name="totalCostIncoice" id="totalCostIncoice" value="" readonly="readonly"/></div>
</div>

<div>Enter Additional Notes&nbsp;:&nbsp;<br/>
    <div style="width:650px;border:solid thin #FCCF12;"><textarea name="addNotes" cols="74" style="background: #D6D6D6;color: #2D2D2D;"></textarea></div>
</div>
</div>
<p align="right"><!--<input type="submit" name="addInvoice" value="Add"/>--></p>
</form>
<input type="button" name="addInvoice" value="Add" onclick="javascript:submitInvoice();"/>