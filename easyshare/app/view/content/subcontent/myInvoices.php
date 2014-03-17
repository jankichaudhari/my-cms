<!-- start group booking section -->
<div style="width:650px;border-bottom:thin solid #9D9D9D;padding-bottom:20px;">
<form name="myInvoices" id="myInvoices">
<input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId; ?>"/>
<input type="hidden" name="memberId" id="memberId" value="<?php echo $memberId; ?>"/>
<input type="hidden" name="groupMC" id="groupMC" value="<?php echo $groupMC; ?>"/>
<input type="hidden" name="start_dt" id="start_dt" value="none"/>
<input type="hidden" name="finish_dt" id="finish_dt" value="none"/>
<div id="errorInvoices"></div>

<?php  if( (count($myInvoicesInfo[0])!=0) && (count($myInvoicesInfo[1])!=0) && (count($myInvoicesInfo[2])!=0) && (count($myInvoicesInfo[3])!=0) && (count($myInvoicesInfo[4])!=0) ) { ?>
<div style="width:650px;">
<div style="width:650px;">		<!--Title Row -->
    <div class="divRow" style="width:100px;" align="center"><a href="index.php?process=my_group&param1=<?php echo $groupId; ?>&param2=<?php echo $param2; ?>&param3=1&param4=<?php echo $sortOrder; ?>#myInvoices">Date</a></div>
    <div class="divRow" style="width:130px;" align="center"><a href="index.php?process=my_group&param1=<?php echo $groupId; ?>&param2=<?php echo $param2; ?>&param3=2&param4=<?php echo $sortOrder; ?>#myInvoices">Type</a></div>
    <div class="divRow" style="width:160px;" align="center"><a href="index.php?process=my_group&param1=<?php echo $groupId; ?>&param2=<?php echo $param2; ?>&param3=3&param4=<?php echo $sortOrder; ?>#myInvoices">Invoice Amount(&pound;)</a></div>
     <div class="divRow" style="width:130px;" align="center"><a href="index.php?process=my_group&param1=<?php echo $groupId; ?>&param2=<?php echo $param2; ?>&param3=4&param4=<?php echo $sortOrder; ?>#myInvoices">Status</a></div>
    <div class="divRow" style="width:130px;"></div>
	<div class="clear"></div>
</div>

<?php
for($i=$startLimit;$i<$endLimit;$i++)	{
	if($i%2==0){
		$bgColor="#D6D6D6";
	} else {
		$bgColor="#FFFFFF";
	}
	$id=$myInvoicesInfo[0][$i];
	
	$dateInvoice=$myInvoicesInfo[1][$i];
	$invoiceDate = date('d M Y',$dateInvoice);
	
	$invoiceType=$myInvoicesInfo[2][$i];
	$invoiceAmount=number_format($myInvoicesInfo[3][$i],2,'.',',');
	$status=$myInvoicesInfo[4][$i];
?>
<div style="width:650px;background-color:<?php echo $bgColor; ?>;">
    <div class="divRow" style="width:100px;text-transform:capitalize;" align="center"><p><?php echo $invoiceDate; ?></p></div>			
    <div class="divRow" style="width:130px;" align="center"><p><?php echo $invoiceType; ?></p></div>
    <div class="divRow" style="width:160px;" align="center"><p><?php echo $invoiceAmount; ?></p></div>
     <div class="divRow" style="width:130px;" align="center">
	 	<select name="incoiceStatus" id="<?php echo $id; ?>" onchange="javascript:changePayStatus(this);">
        <?php if($status=='p') { 
			echo '<option value="p" id="p" selected="selected">Paid</option>';
         } else { 
		 	echo '<option value="p" id="p">Paid</option>';
         } if($status=='u') {
        	echo '<option value="u" id="u" selected="selected">Unpaid</option>';
          } else {
         	echo '<option value="u" id="u">Unpaid</option>';
          } ?>
        </select>
     </div>
    <div class="divRow" style="width:130px;" id="smallText">
    	<a href="javascript:void(0);" onclick="viewInvoice('<?php echo $id; ?>','view')" id="<?php echo $id; ?>" style="font-size:11px;">view</a>
        &nbsp;|&nbsp;
        <a href="javascript:void(0);" onclick="viewInvoice('<?php echo $id; ?>','download')" id="<?php echo $id; ?>" style="font-size:11px;">download</a>
        &nbsp;|&nbsp;
        <a href="javascript:void(0);" onclick="viewInvoice('<?php echo $id; ?>','print')" id="<?php echo $id; ?>" style="font-size:11px;">print</a>
    </div>
	<div class="clear"></div>
</div>
<?php } ?>
</div>

<div style="width:650px;font-size:12px;padding-top:5px;" align="right" id="smallText"><?php echo $paging_html_text; ?></div>
<?php } else { ?>
	No Any Invoice Found
<?php } ?>
</form>
</div>
<!-- end group booking section -->