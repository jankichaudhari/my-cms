
<h2><a name="invoicePayment">Invoices and Payments</a></h2>
<div style="width:650px;">
    <div style="width:150px;" class="divRow">
        <a href="index.php?process=group_fc_page&param1=<?php echo $groupId; ?>&param2=<?php echo $groupMonth; ?>&param3=si#invoicePayment" id="single_in_<?php echo $groupId."_".$groupMonth; ?>" class="single_reg_io">Single Invoice </a>
    </div>
    <div style="width:150px;" class="divRow">
    	<a href="index.php?process=group_fc_page&param1=<?php echo $groupId; ?>&param2=<?php echo $groupMonth; ?>&param3=ri#invoicePayment" id="regular_In_<?php echo $groupId."_".$groupMonth; ?>" class="single_reg_io">Regular Invoice</a>
    </div>
    <div style="width:150px;" class="divRow">
    	<a href="index.php?process=group_fc_page&param1=<?php echo $groupId; ?>&param2=<?php echo $groupMonth; ?>&param3=so#invoicePayment" id="single_pay_<?php echo $groupId."_".$groupMonth; ?>" class="single_reg_io">Single Payment</a>
    </div>
    <div style="width:150px;" class="divRow">
    	<a href="index.php?process=group_fc_page&param1=<?php echo $groupId; ?>&param2=<?php echo $groupMonth; ?>&param3=ro#invoicePayment" id="regular_Pay_<?php echo $groupId."_".$groupMonth; ?>" class="single_reg_io">Regular Payment</a>
    </div>
    <div class="clear"></div>
</div>
<div style="width:650px;" id="in_pay_content">
    	<?php
		switch($sr_io_type)
		{
			case 'si' : $this-> single_In_list($groupId,$groupMonth);
			break;
			case 'ri' : $this->regular_In_list($groupId,$groupMonth);
			break;
			case 'so' : $this->single_pay_list($groupId,$groupMonth);
			break;
			case 'ro' : $this->regular_Pay_list($groupId,$groupMonth);
			break;
		}
		?>
</div>