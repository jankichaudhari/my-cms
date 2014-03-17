<form name="regular_io" id="regular_io">
<input type="hidden" name="groupId" value="<?php echo $groupId; ?>" id="groupId"/>
<input type="hidden" name="groupMonth" value="<?php echo $groupMonth; ?>" id="groupMonth"/>
<?php
	if($io=='i')
	{
		$type='Invoice';
	}
	else if($io=='o')
	{
		$type='Payment';
	}
?>
<input type="hidden" name="regular_type" value="<?php echo $io; ?>" id="regular_type"/>
<h4>Add a Regular <?php echo $type; ?></h4>
<span id="msgRegular_io"></span>
<p>
	Amount&nbsp;:
    <input type="text" name="regular_io_amt" id="regular_io_amt" maxlength="10" size="7"/>
    Description&nbsp;:<input type="text" name="regular_io_desc" id="regular_io_desc" value=""/>
</p>
<p>
	Start Date&nbsp;:<input class="regular_io_startDt" id="regular_io_startDt" value="<?php echo date('Y-m-d'); ?>" onclick="javascript:displayCalender(this);" size="12"/>
    End Date&nbsp;:<input class="regular_io_endDt" id="regular_io_endDt" value="<?php echo date('Y-m-d'); ?>" onclick="javascript:displayCalender(this);" size="12"/>
    Recurrence&nbsp;:
    <select name="io_units" id="io_units" style="background:#D6D6D6;color:#2D2D2D;width:100px;"  onchange="javascript:checkdateDiff(this.value);">
	 <?php
	   for($i=0;$i<count($io_units);$i++)
	   {
		?><option value="<?php echo $io_units[$i]['id'] ?>"><?php echo $io_units[$i]['name'];?></option><?php
	   }
	   ?>
    </select>
    &nbsp;&nbsp;<input type="button" name="add_regular_io" value="Add"  onclick="javascript:submitRegular_io();"/>
</p>
</form>