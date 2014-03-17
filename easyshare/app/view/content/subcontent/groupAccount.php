<div style="width:650px;padding-to:20px;padding-bottom:20px;border-bottom:thin solid #9D9D9D;" id="fc_Ac">

<h2><a name="groupAccount">Group Account</a></h2>
<div align="right" style="font-size:10px;">
  	<div>
    <a href="javascript:void(0)" onClick="javascript:printGroupAC('<?php echo $groupId; ?>','<?php echo $groupMonth; ?>','download');">download</a>
     &nbsp; | &nbsp;
     <a href="javascript:void(0)" onClick="javascript:printGroupAC('<?php echo $groupId; ?>','<?php echo $groupMonth; ?>','print');">print</a>
     &nbsp; statement for&nbsp;
    <?php
	$todayNow = strtotime("now");
	?><select name="acMonthYear" id="acMonthYear" multiple="multiple" size="1" onblur="javascript:$(this).attr('size',1);" onfocus="javascript:$(this).attr('size',4);"><?php
	$my = $groupMaxMonthInfo;
	while($my>=$groupMinMonthInfo)
	{
		?><option value="<?php echo $my; ?>" selected="selected"><?php echo date ('F Y',$my); ?></option><?php
		$my = strtotime('-1 month',$my);
	}
	?>
    </select>
    </div>
    <div id="sizemsg">( You can select multiple months)</div>
</div>
<div align="left"><span id="smallText">Total Balance of Group is&nbsp;</span><span style="color:#FFF;font-weight:bold;font-size:11px;">&nbsp;&pound;&nbsp;<?php echo number_format($groupBalance, 2, '.', ','); ?></span></div>
<div style="width:650px;border:solid thin #333;" align="center">
	<div class="divRow" style="width:200px;">
    	<?php if($prevMonthStr != $groupMonth) {?>
    		<a href="index.php?process=group_fc_page&param1=<?php echo $groupId; ?>&param2=<?php echo $prevMonthStr; ?>&param3=<?php echo $sr_io_type; ?>&pagenum=<?php echo $sr_io_pagenum; ?>#groupAccount"><<</a>
        <?php } else { ?>
        	<<
        <?php } ?>
    </div>
	<div class="divRow" style="width:250px;"><?php echo $monthname; ?></div>
    <div class="divRow" style="width:200px;">
    	<?php if($nextMonthStr != $groupMonth) {?>
    		<a href="index.php?process=group_fc_page&param1=<?php echo $groupId; ?>&param2=<?php echo $nextMonthStr; ?>&param3=<?php echo $sr_io_type; ?>&pagenum=<?php echo $sr_io_pagenum; ?>#groupAccount">>></a>
        <?php } else { ?>
        	>>
        <?php } ?>
    </div>
    <div class="clear"></div>
</div>

<div style="width:650px;" id="msgFcAc"></div>

<!-- start fc log -->
<?php
if(count($date)!=0 && count($description)!=0 && count($payment)!=0 && count($receipt)!=0 && count($balance)!=0)
{
?>
<div id="ac_page_content" style="width:650px;<?php echo $groupACStyle; ?>">
    <div style="width:650px;">
    <div style="width:650px;" id="msgFCLog"></div>
    <!--Title Row-->
    <div style="width:650px;">
        <div style="width:90px;" class="divRow"><h4>Date</h4></div>
        <div style="width:200px;" class="divRow"><h4>Description</h4></div>
        <div style="width:120px;" class="divRow"><h4>Payments(&pound;)</h4></div>
        <div style="width:120px;" class="divRow"><h4>Receipts(&pound;)</h4></div>
        <div style="width:120px;" class="divRow"><h4>Balance(&pound;)</h4></div>
        <div class="clear"></div>
    </div>
    <!--end Title Row-->
    
    <!--Data Rows-->
    <div style="width:650px;" id="fcLog">
    <form name="fcLog" id="fcLog">
    <input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId; ?>" />
    <div style="width:650px;">
    <?php 
    for($a=0;$a<count($date);$a++) {
        if($a%2==0){
            $bgColor="#151515";
        } else {
            $bgColor="#000000";
        }
        $amt_dt = date('d M',$date[$a]);
        $amt_desc = $description[$a];
        $ac_payment = number_format($payment[$a], 2, '.', ',');
        if($ac_payment==0)
        {
            $ac_payment = '-';
        }
        $ac_receipt = number_format($receipt[$a], 2, '.', ',');
        if($ac_receipt==0)
        {
            $ac_receipt = '-';
        }
        $ac_balance = number_format($balance[$a], 2, '.', ',');
        if($ac_balance==0)
        {
            $ac_balance = '-';
        }
    ?>
    <div style="background-color:<?php echo $bgColor; ?>;" class="fcLogList">
        <div style="width:90px;" class="divRow">
            <?php echo $amt_dt; ?>
        </div>
        <div style="width:200px;text-transform:capitalize;" class="divRow" id="smallText">
            <?php echo $amt_desc; ?>
        </div>
        <div style="width:120px;" class="divRow">
            <?php echo $ac_payment; ?>
        </div>
        <div style="width:120px;" class="divRow">
            <?php echo $ac_receipt; ?>
        </div>
        <div style="width:120px;" class="divRow">
            <?php echo $ac_balance; ?>
        </div>
        <div class="clear"></div>
    </div>
    <?php 
    
    }?>
    </div>
    </form>
    </div>
    <!--end Data Rows-->
    </div>
</div>
<?php
}
else
{
    echo "<br/><br/>No Transaction found.<br/><br/>";
}
?>
<!-- finish fc log-->

<div style="width:650px;border:solid thin #333;" align="center">
	<div class="divRow" style="width:200px;">
    	<?php if($prevMonthStr != $groupMonth) {?>
    		<a href="index.php?process=group_fc_page&param1=<?php echo $groupId; ?>&param2=<?php echo $prevMonthStr; ?>&param3=<?php echo $sr_io_type; ?>&pagenum=<?php echo $sr_io_pagenum; ?>#groupAccount"><<</a>
        <?php } else { ?>
        	<<
        <?php } ?>
    </div>
	<div class="divRow" style="width:250px;"><?php echo $monthname; ?></div>
    <div class="divRow" style="width:200px;">
    	<?php if($nextMonthStr != $groupMonth) {?>
    		<a href="index.php?process=group_fc_page&param1=<?php echo $groupId; ?>&param2=<?php echo $nextMonthStr; ?>&param3=<?php echo $sr_io_type; ?>&pagenum=<?php echo $sr_io_pagenum; ?>#groupAccount">>></a>
        <?php } else { ?>
        	>>
        <?php } ?>
    </div>
    <div class="clear"></div>
</div>
</div>