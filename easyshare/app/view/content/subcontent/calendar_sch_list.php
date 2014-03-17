<div style="width:650px;">
<div style="width:650px;" id="msg_calendar_sch"></div>
<div style="width:650px;"><h4>Schdule based on calendar days</h4></div>
<!--Title Row-->
<div style="width:650px;padding-top:10px;" id="smallText" align="right">
	<a href="javascript:void(0);" onclick="javascript:new_schdule('<?php echo $groupId; ?>','c');">Add a maintenance item based on calendar days</a>
     <?php if(count($calendarSchInfo)!=0) { ?>
     <span id="orangeText">|</span>
    <a href="javascript:void(0);" onclick="javascript:deleteSchdule('c');">Remove</a>
    <?php } ?>
</div>

<?php if(count($calendarSchInfo)!=0) { ?>
<div style="width:650px;">
   <div class="divRow" style="width:20px;padding-top:20px;" align="center"><span class="inActiveRecord">
    	<input type="checkbox" id="checkAll" onchange="javascript:checkUncheckAll(this,'checkCalendarSch');" class="checkBoxOpt"/>
   </span></div>
    <div class="divRow" style="width:85px;" align="center"><h5>Trigger on</h5></div>
    <div class="divRow" style="width:100px;" align="center"><h5>Period</h5></div>
    <div class="divRow" style="width:100px;" align="center"><h5>Activate Before</h5></div>
    <div class="divRow" style="width:210px;" align="center"><h5>Description</h5></div>
    <div class="divRow" style="width:85px;" align="center"><h5>Created on</h5></div>
    <div class="clear"></div>
</div>
<!--end Title Row-->
<!--Data Rows-->
<div id="calendarSchLog">
<form name="calSchLog" id="calSchLog">
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
	$id=$calendarSchInfo[$mb]['id'];
	$start_dt=$calendarSchInfo[$mb]['start_dt'];
	$period_id=$calendarSchInfo[$mb]['period_id'];
	$periodInfo = $this->model->getResult("units",""," WHERE  id='$period_id' AND active='y' ","");
	$periodName = $periodInfo[0]['name'];
	$beforeVal=(float)($calendarSchInfo[$mb]['before_value']);
	if($beforeVal >= 30)
	{
		$beforeVal = floor($beforeVal / 30) ;
		$before_value = $beforeVal ." month(s)";
	}
	else if($beforeVal == 7)
	{
		$before_value = "1 week";
	}
	else
	{
		$before_value = $beforeVal ." day";
	}
	$reminder_desc=$calendarSchInfo[$mb]['reminderDesc'];
	
	$created_dt=strtotime($calendarSchInfo[$mb]['created_dt']);
	$createdDt = date('d M Y h a',$created_dt);
?>
<div style="width:650px;background-color:<?php echo $bgColor; ?>;" align="center" class="calendar_sch_list">
   <div class="divRow" style="width:20px;" align="center"><span class="inActiveRecord">
    	<input type="checkbox" value="<?php echo $id;  ?>" id="<?php echo $id; ?>" onchange="changeCheckStyle(this);" name="checkCalendarSch" class="checkBoxOpt"/>
    </span></div>
    <div class="divRow" style="width:85px;"><?php echo $start_dt; ?></div>
    <div class="divRow" style="width:100px;text-transform:capitalize;"><?php echo $periodName; ?></div>
    <div class="divRow" style="width:100px;"><?php echo $before_value; ?></div>
    <div class="divRow" style="width:210px;text-transform:capitalize;" id="smallText"><?php echo $reminder_desc; ?></div>
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
No Any Schdule based on Calendar Found.
<?php } ?>
</div>