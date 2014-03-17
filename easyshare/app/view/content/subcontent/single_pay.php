<form name="single_io" id="single_io">
<input type="hidden" name="single_type" value="<?php echo $io; ?>" id="single_type"/>
<input type="hidden" name="groupId" value="<?php echo $groupId; ?>" id="groupId"/>
<input type="hidden" name="groupMonth" value="<?php echo $groupMonth; ?>" id="groupMonth"/>
<h4>Add a single Payment</h4>
<span id="msgSingle_io"></span>
<p>
	Amount&nbsp;:
    <input type="text" name="single_io_amt" id="single_io_amt" maxlength="10" size="7"/>
    Description&nbsp;:<input type="text" id="single_io_desc" name="single_io_desc" value=""/>
    &nbsp;&nbsp;<input type="button" name="add_single_io" value="Add" onclick="javascript:submitSingle_pay();"/>
</p>
</form>