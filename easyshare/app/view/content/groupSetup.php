<div class='mainInfo'>
<div id="subContainer">
<h1>Group Setup</h1>
<div class="error"><?php echo $message; ?></div>
<!-- start group basic info and member section -->
<?php $this->groupBasicInfo($groupId,$listId,0);?>
<!-- end group basic info and member section -->

<!-- start group booking rules section -->
<?php $this->bookRules($groupId,0); ?>
<!-- end group booking rules section -->

<!-- start group usage monitor -->
<?php $this->groupUsage($groupId,0);?>
<!-- end group usage monitor -->

<!-- start Billing Preferences section -->
<?php 
	$this->groupBilling($groupId,0);
	?>
<!-- end Billing Preferences section --> 
<p>Click here to Chose your Payment Method and Activate the Group&nbsp;&nbsp;<input type="button" style="background:url(public/images/search-button.gif) no-repeat;width:25px;" id="<?php echo $groupId; ?>" name="F" class="btnGroupSetUp" alt="Activate"/></p>
<p>Click here to Store your Progress and Activate Later&nbsp;&nbsp;<input type="button" style="background:url(public/images/search-button.gif) no-repeat;width:25px;" id="<?php echo $groupId; ?>" name="P" class="btnGroupSetUp" alt="Store"/></p>
</div>
</div>