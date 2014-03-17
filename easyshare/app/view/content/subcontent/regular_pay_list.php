<div style="width:650px;">
<div style="width:650px;" id="regular_pay_log"></div>
<div style="width:650px;"><h4>Regular Payments</h4></div>
<div style="width:650px;" id="errorMsg_o"></div>
<!--Title Row-->
<div style="width:650px;" id="smallText" align="right">
	<a href="javascript:void(0);" onclick="javascript:newForm(this);" id="regular_pay_<?php echo $groupId."_".$groupMonth; ?>" class="newForm">Add</a>
    <?php if(count($memberBillingInfo)!=0) { ?>
    <span id="orangeText">|</span>
    <a href="javascript:void(0);" onclick="javascript:deleteRegInPay('o');"  class="newForm">Remove</a>
    <?php } ?>
</div>

<?php if(count($memberBillingInfo)!=0) { ?>
<div style="width:650px;">
	<div class="divRow" style="width:20px;padding-top:20px;" align="center"><span class="inActiveRecord">
    	<input type="checkbox" id="checkAll" onchange="javascript:checkUncheckAll(this,'checkRegPay');" class="checkBoxOpt"/></span></div>
    <div class="divRow" style="width:85px;" align="center"><h5>Date</h5></div>
    <div class="divRow" style="width:170px;" align="center"><h5>Amount</h5></div>
    <div class="divRow" style="width:170px;" align="center"><h5>Description</h5></div>
    <div class="divRow" style="width:140px;" align="center"><h5>Period</h5></div>
    <div class="divRow" style="width:65px;" align="center"><h5>Recurrence</h5></div>
    <div class="clear"></div>
</div>
<!--end Title Row-->
<!--Data Rows-->
<div id="invoiceLog">
<form name="singlePayLog" id="singlePayLog">
<input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId; ?>" />
<input type="hidden" name="startLimit" id="startLimit" value="<?php echo $startLimit; ?>" />
<input type="hidden" name="endLimit" id="endLimit" value="<?php echo $endLimit; ?>" />
<div style="width:635px;">
<?php 

//foreach($memberBillingInfo as $mb=>$memBillValue)
for($mb=$startLimit;$mb<$endLimit;$mb++)
{
	if($mb%2==0){
		$bgColor="#D6D6D6";
	} else {
		$bgColor="#FFFFFF";
	}
	$pay_id=$memberBillingInfo[$mb]['id'];
	$payment_desc=$memberBillingInfo[$mb]['amt_desc'];
	if(!isset($payment_desc) || empty($payment_desc))
	{
		$payment_desc = "--";
	}
	$created_dt=strtotime($memberBillingInfo[$mb]['created_dt']);
	$pay_date = date('d M Y ha',$created_dt);
	$pay_amount = $memberBillingInfo[$mb]['amount'];
	$pay_type_id = $memberBillingInfo[$mb]['type_id'];
	$pay_Info = $this->model->getResult("AC_regular_IO",""," WHERE id='$pay_type_id' "," ORDER BY id DESC ");
	$start_date = strtotime($pay_Info[0]['start_date']);
	$sDt = date('d m Y  ha',$start_date);
	$end_date = strtotime($pay_Info[0]['end_date']);
	$eDt = date('d m Y  ha',$end_date);
	$recurrenceId = $pay_Info[0]['recurrence'];
	$recurrence_Info = $this->model->getResult("units",""," WHERE id='$recurrenceId' "," ORDER BY id DESC ");
	$recurrence = $recurrence_Info[0]['name'];
?>
<div style="width:650px;background-color:<?php echo $bgColor; ?>;" align="center" class="invoice_list">
	<div class="divRow" style="width:20px;" align="center"><span class="inActiveRecord">
    	<input type="checkbox" value="<?php echo $pay_id;  ?>" id="<?php echo $pay_id;  ?>" onchange="changeCheckStyle(this);" name="checkRegPay" class="checkBoxOpt"/>
    </span></div>
	<div class="divRow" style="width:85px;"><?php echo $pay_date; ?></div>
    <div class="divRow" style="width:170px;"><?php echo $pay_amount; ?></div>
    <div class="divRow" style="width:170px;text-transform:capitalize;" id="smallText"><?php echo $payment_desc; ?></div>
    <div class="divRow" style="width:140px;" id="smallText"><?php echo $sDt; ?> to <?php echo $eDt; ?></div>
    <div class="divRow" style="width:65px;text-transform:capitalize;"><?php echo $recurrence; ?></div>
    <div class="clear"></div>
</div>
<?php 

}?>
</div>
<div style="width:650px;font-size:12px;padding-top:5px;" align="right">
	<?php echo $paging_html_text; ?>
</div>
</form>
</div>
<!--end Data Rows-->
<?php } else { ?>
	No Any Regular Payment Found.
<?php } ?>
</div>