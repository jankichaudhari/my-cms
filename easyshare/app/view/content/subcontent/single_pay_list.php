<div style="width:650px;">
<div style="width:650px;" id="invoice_log"></div>
<div style="width:650px;"><h4>Single Payments</h4></div>
<!--Title Row-->
<div style="width:650px;" id="smallText" align="right">
	<a href="javascript:void(0);" onclick="javascript:newForm(this);" id="single_pay_<?php echo $groupId."_".$groupMonth; ?>" class="newForm">Add Single Payment</a>
</div>

<?php if(count($memberBillingInfo)!=0) { ?>
<div style="width:650px;padding-top:10px;">
    <div class="divRow" style="width:100px;" align="center"><h5>Date</h5></div>
    <div class="divRow" style="width:200px;" align="center"><h5>Amount</h5></div>
    <div class="divRow" style="width:250px;" align="center"><h5>Description</h5></div>
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

for($mb=$startLimit;$mb<$endLimit;$mb++)
{
	if($mb%2==0){
		$bgColor="#D6D6D6";
	} else {
		$bgColor="#FFFFFF";
	}
	//$invoice_id=$memberBillingInfo[$mb]['id'];
	$payment_desc=$memberBillingInfo[$mb]['amt_desc'];
	$created_dt=strtotime($memberBillingInfo[$mb]['created_dt']);
	$pay_date = date('d M Y h a',$created_dt);
	$pay_amount = $memberBillingInfo[$mb]['amount'];
?>
<div style="width:650px;background-color:<?php echo $bgColor; ?>;" align="center" class="invoice_list">
    <div class="divRow" style="width:100px;"  id="smallText"><?php echo $pay_date; ?></div>
    <div class="divRow" style="width:200px;"><?php echo $pay_amount; ?></div>
    <div class="divRow" style="width:250px;text-transform:capitalize;" id="smallText"><?php echo $payment_desc; ?></div>
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
	No Any Single Payment Found.
<?php } ?>
</div>