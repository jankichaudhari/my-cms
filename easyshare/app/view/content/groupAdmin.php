<div class='mainInfo'>
<div id="subContainer">


<div>
	<div class="error"><?php echo $message; ?></div>
	<?php $this->groupBasicInfo($groupId,0,'A'); ?>
    
    <!-- start group member section -->
    <?php $this->bookRules($groupId,'A'); ?>
    <!-- end group member section -->
    
    <!-- start group usage monitor -->
    <?php $this->groupUsage($groupId,'A');?>
    <!-- end group usage monitor -->
    
    <?php $this->adminLog($groupId,$sortIndex,$sortState,$pagenum); ?>
    
    <p>Click here to Save Changes&nbsp;&nbsp;<input type="button" style="background:url(public/images/search-button.gif) no-repeat;width:25px;" name="saveGroupSetUp" class="btnGroupSetUp" alt="Save"/></p>
</div>

</div>
</div>