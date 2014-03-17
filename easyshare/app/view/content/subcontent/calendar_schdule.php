<form name="calendar_sch" id="calendar_sch" >
<input type="hidden" name="groupId" value="<?php echo $groupId; ?>" id="groupId"/>
<h4>Add a maintenance item based on calendar days</h4>
<span id="msgCalSch"></span>
<p>
	Period&nbsp;:
    <select name="periods" id="periods" style="background: #D6D6D6;color: #2D2D2D;width:100px;"  onchange="javascript:selectPeriod(this);">
	 <?php	
	   for($i=0;$i<count($periodsInfo);$i++)
	   {
		   $periodId = $periodsInfo[$i]['id'];
		   $periodName = $periodsInfo[$i]['name'];
		   $periodvalue = $periodsInfo[$i]['value'];
		?><option value="<?php echo $periodId; ?>" id="<?php echo $periodvalue; ?>"><?php echo $periodName; ?></option><?php
	   }
	   ?>
    </select>
</p>
 <p>
    Description&nbsp;:<input type="text" name="calSch_desc" id="calSch_desc" value=""/>
      <span id="msgCalDesc"></span>
</p>
<p>
	Start Date&nbsp;:<input class="trigger_date" id="trigger_date" name="trigger_date" value="<?php echo date('Y-m-d'); ?>" onclick="javascript:displayCalender(this);" size="12" />
</p>
<p>
   Remind me Before&nbsp;:
   <select name="before" id="before">
   <option value="1" id="day">a day</option>
   </select>
</p>
<p>
<input type="button" name="saveCalSch" value="Save" onclick="javascript:submitCalendarSch();"/>
</p>
</form>