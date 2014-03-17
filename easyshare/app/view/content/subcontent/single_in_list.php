<div style="width:650px;">
<div style="width:650px;" id="invoice_log"></div>
<div style="width:650px;"><h4>Single Invoices</h4></div>
<!--Title Row-->
<div style="width:650px;padding-top:10px;" id="smallText" align="right">
	<a href="javascript:void(0);" onclick="javascript:newForm(this);" id="single_in_<?php echo $groupId."_".$groupMonth; ?>" class="newForm">Add Single Invoice</a>
</div>

<?php if(count($memberBillingInfo)!=0) { ?>
<div style="width:650px;">
    <div class="divRow" style="width:70px;" align="center"><h5>Ref No</h5></div>
    <div class="divRow" style="width:70px;" align="center"><h5>Date</h5></div>
    <div class="divRow" style="width:180px;" align="center"><h5>Member</h5></div>
    <div class="divRow" style="width:190px;" align="center"><h5>Amount</h5></div>
    <div class="divRow" style="width:70px;" align="center"><h5>Status</h5></div>
    <div class="divRow" style="width:70px;" align="center"><h5>view</h5></div>
    <div class="clear"></div>
</div>
<!--end Title Row-->
<!--Data Rows-->
<div id="invoiceLog">
<form name="invoiceLog" id="invoiceLog">
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
	$invoice_id=$memberBillingInfo[$mb]['id'];
	$invoice_status=$memberBillingInfo[$mb]['status'];
	$member_id=$memberBillingInfo[$mb]['member_id'];
	$memberInfo = $this->model->getResult("group_members",""," WHERE id='$member_id' AND active='y' ","");
	$user_id = $memberInfo[0]['user_id'];
	$authInfo = $this->model->getResult("auth_profile",""," WHERE 	id='$user_id'","");
	$first_name=$authInfo[0]['first_name'];
	$last_name=$authInfo[0]['last_name'];
	$mem_name=$first_name." ".$last_name;
	
	$created_dt=strtotime($memberBillingInfo[$mb]['dateInvoice']);
	$dateInvoice = date('d M Y h a',$created_dt);
	$invoiceInfo = $this->model->getResult("bill_invoice_manual",""," WHERE 	bill_invoice_id='$invoice_id' AND active='y' ","");
	$manual_amount = 0;
	foreach($invoiceInfo as $i=>$value)
	{
		$amt = $invoiceInfo[$i]['totalCost'];
		$manual_amount = $manual_amount + (float) $amt;
	}
	$invoice_amount = $manual_amount;
	
	if($invoice_status=='p')
	{
		$pay_status = 'Paid';
	}
	else if($invoice_status=='u')
	{
		$pay_status = 'Unpaid';
	}
?>
<div style="width:650px;background-color:<?php echo $bgColor; ?>;" align="center" class="invoice_list">
    <div class="divRow" style="width:70px;"><?php echo $invoice_id; ?></div>
    <div class="divRow" style="width:70px;" id="smallText"><?php echo $dateInvoice; ?></div>
    <div class="divRow" style="width:180px;text-transform:capitalize;" id="smallText"><?php echo $mem_name; ?></div>
    <div class="divRow" style="width:190px;"><?php echo $invoice_amount; ?></div>
    <div class="divRow" style="width:70px;"><?php echo $pay_status; ?></div>
    <div class="divRow" style="width:70px;" id="smallText"><a href="javascript:void(0);" onclick="viewInvoice('<?php echo $invoice_id; ?>')" id="<?php echo $invoice_id; ?>" style="font-size:11px;">view</a></div>
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
	No Any Single Invoice Found.
<?php } ?>

</div>