<form name="usage_sch" id="usage_sch">
<input type="hidden" name="groupId" value="<?php echo $groupId; ?>" id="groupId"/>
<h4>Add a maintenance item based on a usage monitor</h4>
<span id="msgUsageSch"></span>
<p>
	Usage&nbsp;:
    <select name="usages" id="usages" style="background:#D6D6D6;color:#2D2D2D;width:100px;"  onchange="javascript:selectUsage(this);">
	 <?php
	   for($i=0;$i<count($usagesInfo);$i++)
	   {
		   $usageId = $usagesInfo[$i]['id'];
		   $usageUnitName = $usagesInfo[$i]['unit'];
		?><option value="<?php echo $usageId; ?>"><?php echo $usageUnitName; ?></option><?php
	   }
	   ?>
    </select>
</p>
 <p>
    Description&nbsp;:<input type="text" name="usageSch_desc" id="usageSch_desc" value=""/>
    <span id="msgUsageDesc"></span>
</p>
<p>
	Trigger  Usage Value&nbsp;:<input type="text" name="trigger_value" id="trigger_value" value=""  maxlength="16" onkeyup="javascript:checkUsageValues(this,'msgUsageTrigger')"/>
      <span id="msgUsageTrigger"></span>
</p>
<!--<p>
   Before (Usage Value)&nbsp;:<input type="text" name="before_value" id="before_value" value="" maxlength="16"  onkeyup="javascript:checkUsageValues(this,'msgUsageBefore');"/>
     <span id="msgUsageBefore"></span>
</p>-->
<p>
<input type="button" name="saveUsageSch" value="Save" onclick="javascript:submitUsageSch();"/>
</p>
</form>