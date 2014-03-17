<html>
<head>
<?php 
	if($viewStatus=='print') {
		$mainClass = 'printBody';
	?>
<link href="public/css/print.css" rel="stylesheet" type="text/css">
<?php } else { $mainClass = 'mainInfo';  ?>
<link href="public/css/mainstyles.css" rel="stylesheet" type="text/css">
<?php } ?>
</head>

<body>

<div class='<?php echo $mainClass; ?>'>
<div align="left"><img src="public/images/Easyshare.gif" alt="Easyshare" width="215" height="30"></div>

<div align="center">
<div align="center"><h1>Invoice</h1></div>

<div class="viewInvoiceStyle">

<div style="width:630px;" align="right"><h4>Date&nbsp;:&nbsp;<span class="viewInvoiceUser"><?php echo date('d M Y',$thisinvoiceDt); ?></span></h4></div>
<div style="width:630px;" align="center"><h3><?php echo $invoiceType; ?>&nbsp; Invoice</h3></div>
<div style="width:630px;" align="left"><h4>From&nbsp;:&nbsp;<span class="viewInvoiceUser"><?php echo $adminFullName; ?></span></h4></div>
<div style="width:630px;" align="left"><h4>To&nbsp;:&nbsp;<span class="viewInvoiceUser" ><?php echo $userFullName; ?></span></h4></div>

<div style="width:630px;height:50px;"></div>

<div class="viewInvoiceService1">
    <div class="viewInvoiceService2">
    <div style="width:315px;" class="divRow" align="center"><h4>Services/Description</h4></div>
    <div style="width:315px;" class="divRow" align="center"><h4>Amount(&pound;)</h4></div>
    <div class="clear"></div>
    </div>
    
    <?php 
		for($i=0;$i<count($invoiceDetails);$i++)
		 {	
			 if($thisInvoiceType=='a')
			 {
			 	$thisType = $invoiceDetails[$i]['type'];
				if($thisType=='U')
				{
					$invoiceDesc = 'Total Usage Charge';
					$cost =  $invoiceDetails[$i]['amount'];
					$totalAmount = $totalAmount + ((float)$cost);
				}
				else
				{
					$invoiceDesc = 'Fixed Monthly Charge';
					$cost =  $invoiceDetails[$i]['amount'];
					$totalAmount = $totalAmount + ((float)$cost);
				}
			 }
			else if($thisInvoiceType=='m')
			{
				$units =$invoiceDetails[$i]['units'];
				$rate = $invoiceDetails[$i]['rate'];
				$cost = ((float) $units) * ((float)$rate);
				$invoiceDesc = $invoiceDetails[$i]['service_desc'];
			}
			else
			{
				$invoiceDesc = $invoiceDetails;
				$cost = $totalAmount;
			}
		?>
		<div class="viewInvoiceRow">
			<div class="divRow viewInvoiceCell" align="center"><?php echo $invoiceDesc; ?></div>
			<div class="divRow viewInvoiceTotalAmt" align="center"><?php echo number_format($cost,2,'.',','); ?></div>
			<div class="clear"></div>
		</div>
		<?php } ?>
    
    <div class="viewInvoiceRow">
    <div style="width:304px;padding:5px;" class="divRow" align="right">Total:</div>
    <div class="divRow viewInvoiceTotal" align="center"><?php echo number_format($totalAmount,2,'.',','); ?></div>
    <div class="clear"></div>
    </div>
</div>

<div style="width:630px;height:50px;"></div>

<?php if(!empty($addNotes)) { ?>
<div style="width:630px;">Additional Note:</div>
<div class="viewInvoiceNotes"><?php echo $addNotes; ?></div>
<?php } ?>

<div style="width:630px;height:75px;"></div>

<div style="width:630px;padding:10px;">
*This Invoice is for reference purpose only, and should not be used as proof of purchase/payment.
</div>

</div>
</div>

</div>

</body>
</html>