<div id="usageMonitor">
<form name="groupUsageMonitor" id="groupUsageMonitor" action="index.php?process=store_usage_rules" method="post">
    <input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId; ?>"/>
    <input type="hidden" name="groupAC" id="groupAC" value="<?php echo $groupAC; ?>"/>
    <input type="hidden" name="timeUsage" id="timeUsage" value="0"/>
	<input type="hidden" name="startEnd" id="startEnd" value="none"/>
<h1><a name="usageMonitors">Usage Monitors</a></h1>
<span id="errorUsage"></span><br/>
<span>Which Information do you require after each Booking Period &nbsp;&nbsp;&nbsp;<a href="#" id="smallText">what's this?</a></span>
<!-- Usage Monitor option -->
<?php
if($totalUsages!=0) {
 for($u=0;$u<$totalUsages;$u++) { 
 $usageId=$usageUnitInfo[$u]['id'];
 $usage_unit=$usageUnitInfo[$u]['unit'];
 $thisActive = $usageUnitInfo[$u]['active'];
 $thisStyle=NULL;
 if($thisActive=='n')
{
	$thisStyle = '#2C2C2C';
}
$hideLink = 'style="display:none"';
//$usageUnitBlock='usageUnitActive';
?>
<div id="usageUnitActive"  style="color:<?php echo $thisStyle; ?>">
<p>
	After usage each member is required to record Time
    &nbsp;<span id="smallText"><a href="javascript:void(0);" style="color:<?php echo $thisStyle; ?>" class="removeUsage" id="<?php echo $usageId."_".$thisActive; ?>" >remove</a></span>
</p>
</div>
<?php } 
} else { ?>
<div id="usageUnitBlock">
	<p>
    	After usage each member is required to record Time
    	<span id="smallText"><a href="javascript:void(0);" id="remove_0" onclick="removeTempUsage(this);">remove</a></span>
    </p>
</div>
<?php } ?>	
<!-- end Usage Monitor option -->
<!-- Start & End Monitor -->
<?php
if(!empty($totalstartEnd)) {
for($se=0;$se<$totalstartEnd;$se++) { 
$startEndId=$startEndInfo[$se]['id'];
$startEnd_unit=$startEndInfo[$se]['unit'];
$current_value=$startEndInfo[$se]['currentValue'];
$thisActive = $startEndInfo[$se]['active'];
$thisStyle = NULL;
if($thisActive=='n')
{
	$thisStyle = '#2C2C2C';
	$readOnly = 'readonly="readonly"';
}
//$startEndUnitBlock='startEndUnitActive';
?>
<div id="startEndUnitActive" style="color:<?php echo $thisStyle; ?>">
<p>
    After usage each member is required to record Start &amp; End:&nbsp;
    <input type="text" id="txtStartEndUnit"  name="<?php echo 'usageMonitor' . $startEndId; ?>" value="<?php echo $startEnd_unit; ?>" size="5" maxlength="50" style="color:<?php echo $thisStyle; ?>"  <?php echo $readOnly; ?>/>
    &nbsp;&nbsp;<span id="smallText"><a href="javascript:void(0);" style="color:<?php echo $thisStyle; ?>" class="removeStartEnd" id="<?php echo $startEndId."_".$thisActive; ?>" >remove</a></span>
	<br/>
    <span class="nowSubActive" style="color:<?php echo $thisStyle; ?>"><span>
        Current Value is:&nbsp;
        <input type="text" name="<?php echo 'currentMonitor' . $startEndId; ?>" id="currentMonitor" value="<?php echo $current_value; ?>" size="10" maxlength="10" onkeyup="javascript:currentValValid(this.value);" style="color:<?php echo $thisStyle; ?>"  <?php echo $readOnly; ?>/>
    </span></span>
</p>
</div>
<?php } } ?>
<div id="startEndUnitBlock">
<p id="parSEUnit_0">
    After usage each member is required to record Start &amp; End:&nbsp;
    <input type="text" id="txtStartEndUnit_0"  name="txtStartEndUnit_0" size="5" value="" maxlength="50" />&nbsp;
    <span id="smallText"><a href="javascript:void(0);" id="removeSE_0" onclick="removeTempSE(this);">remove</a></span><br/>
    <span class="nowSubActive"><span>
        Current Value is:&nbsp;<input type="text" name="currentMonitor_0" id="currentMonitor_0" value="" size="10" maxlength="10" onkeyup="javascript:currentValValid(this.value);"/>
    </span></span>
</p>
</div>
<!-- end Start & End Monitor -->
       <div style="padding: 5px 0px 0px 20px;" id="addUsageMonitorLink">
       <span <?php echo $hideLink; ?>><a href="javascript:void(0);" id="usageUnit">Click here to Add a Time Monitor</a></span><br/>
       <a href="javascript:void(0);" id="startEndUnit">Click here to Add a Start & End Monitor</a>
       </div>
<input type="image" name="storeusageMonitors" id="storeusageMonitors" class="saveButtonImage" value="Save" align="right"/>
            </form>
</div>