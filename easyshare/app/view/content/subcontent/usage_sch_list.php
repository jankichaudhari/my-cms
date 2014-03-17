<div style="width:650px;">
<div style="width:650px;" id="msg_usage_sch"></div>
<div style="width:650px;"><h4>Schedule based on a usage monitor</h4></div>
<!--Title Row-->
<div style="width:650px;padding-top:10px;" id="smallText" align="right">
	<a href="javascript:void(0);" onclick="javascript:new_schdule('<?php echo $groupId; ?>','u');">Add a maintenance item based on a usage monitor</a>
    <?php if(count($usagesSchInfo)!=0) { ?>
     <span id="orangeText">|</span>
    <a href="javascript:void(0);" onclick="javascript:deleteSchdule('u');">Remove</a>
    <?php } ?>
</div>

<?php if(count($usagesSchInfo)!=0) { ?>
<div style="width:650px;">
   <div class="divRow" style="width:20px;padding-top:20px;" align="center"><span class="inActiveRecord">
    	<input type="checkbox" id="checkAll" onchange="javascript:checkUncheckAll(this,'checkUsageSch');" class="checkBoxOpt"/></span></div>
    <div class="divRow" style="width:145px;" align="center"><h5>Trigger at</h5></div>
    <div class="divRow" style="width:180px;" align="center"><h5>Usage unit</h5></div>
    <div class="divRow" style="width:190px;" align="center"><h5>Description</h5></div>
    <div class="divRow" style="width:85px;" align="center"><h5>Created on</h5></div>
    <div class="clear"></div>
</div>
<!--end Title Row-->
<!--Data Rows-->
<div id="usage_schdule">
<form name="usageSchLog" id="usageSchLog">
<input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId; ?>" />
<input type="hidden" name="startLimit" id="startLimit" value="<?php echo $startLimit; ?>" />
<input type="hidden" name="endLimit" id="endLimit" value="<?php echo $endLimit; ?>" />
<div style="width:635px;">
<?php 

for($mb=$startLimit;$mb<$endLimit;$mb++)
{
	if($mb%2==0){
		$bgColor="#151515";
	} else {
		$bgColor="#000000";
	}
	$id=$usagesSchInfo[$mb]['id'];
	$trigger_value=$usagesSchInfo[$mb]['trigger_value'];
	$usage_id=$usagesSchInfo[$mb]['usage_id'];
	$groupUsageInfo = $this->model->getResult("group_usageMonitor",""," WHERE id='$usage_id' AND active='y' ","");
	$usageUnit = $groupUsageInfo[0]['unit'];
	$reminderDesc=$usagesSchInfo[$mb]['reminderDesc'];
	$created_dt=strtotime($usagesSchInfo[$mb]['created_dt']);
	$createdDt = date('d M Y h a',$created_dt);
?>
<div style="width:650px;background-color:<?php echo $bgColor; ?>;" align="center" class="iusage_sch_list">
    <div class="divRow" style="width:20px;" align="center"><span class="inActiveRecord">
    	<input type="checkbox" value="<?php echo $id;  ?>" id="<?php echo $id; ?>" onchange="changeCheckStyle(this);" name="checkUsageSch" class="checkBoxOpt"/>
    </span></div>
    <div class="divRow" style="width:145px;"><?php echo $trigger_value; ?></div>
    <div class="divRow" style="width:180px;text-transform:capitalize;"><?php echo $usageUnit; ?></div>
    <div class="divRow" style="width:190px;" id="smallText"><?php echo $reminderDesc; ?></div>
    <div class="divRow" style="width:85px;"><?php echo $createdDt; ?></div>
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
No any Schedule based on Usage Monitor Found.
<?php } ?>
</div>