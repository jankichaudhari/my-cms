
 
   
<?php  if(isset($display_list1)) { 	?>
<form name="step1" action="index.php?process=store_step1" method="post" onsubmit="return store_step1();">
<input type="hidden" name="listId" id="listId" value="<?php echo $listId; ?>"/>
<input type="hidden" name="catId"  id="catId" value="<?php echo $cat_id; ?>"/>
<input type="hidden" name="hiddenOther" id="hiddenOther" value="0"/>
<div style="width:650px;">
<div class="divRow" style="width:400px;">
<h1>Add a Listing</h1><br/>
  STEP-1&nbsp;&nbsp;<img src="public/images/barstep<?php echo $storedStep; ?>of4.gif"  alt="Easyshare Step 1"  usemap="#Map"/>
   
<map name="Map" id="Map">
<?php for($s=1;$s<=$storedStep;$s++) 
{ 
	switch($s)
	{
		case 2 : $coords = "15,0,28,12";
		break;
		case 3 : $coords = "30,0,43,13";
		break;
		case 4 : $coords = "44,0,58,12";
		break;
		default : $coords="0,0,13,13";
		break;
	}
?>
 
  <area shape="rect" coords="<?php echo $coords; ?>" href="javascript:void(0);" alt="step <?php echo $s; ?>" title="step <?php echo $s; ?>" onclick="return show_step<?php echo $s; ?>('<?php echo $listId; ?>');"/>
  <?php } ?>
</map>
</div>
<div class="divRow" style="width:250px;height:10px;" align="right"></div>
<div class="clear"></div>

<br/><div>
   <?php if(!empty($listId)) { ?>
   <p>Listing for <span style="color:#000000;"><?php echo $title ?></span></p>
   <?php } else { ?>
   <p>Add New Listing</p>
   <?php } ?>
   </div><br/><br/>
    <p>Title&nbsp;:&nbsp;<input type="text" name="txtTitle" value="<?php echo $title ?>" required="required" maxlength="50" size="30"/>
&nbsp;&nbsp;<span id="enterTitle" style="color:red;"></span></p><br /><br />
    <p>You have successfully Categorized your Listing.</p><br/>
    <p id="msgStep2"></p><br/>
    Category&nbsp;:&nbsp;Select the Appropriate Category for your Listing.
  <?php 
  }
  if(isset($display_list1)) { ?>
    <div class="divRow" style="width:200px;">
        <select name="list1" id="selectList1" size="<?php echo (count($display_list1)+1); ?>" style="background: #D6D6D6;color: #2D2D2D;" onchange="javascript:Listing2(this,'<?php echo $listId; ?>')" onclick="javascript:Listing2(this,'<?php echo $listId; ?>')">
        <?php
        for($i=0;$i<count($display_list1);$i++) 
        {
        	if($list1_id==$display_list1[$i]['id'])
			{
			?>
            <option value="<?php echo $display_list1[$i]['id']; ?>" selected="selected">
				<?php echo $display_list1[$i]['name']; ?></option>
            <?php
			}
			else
			{
			?>
			<option value="<?php echo $display_list1[$i]['id']; ?>" >
				<?php echo $display_list1[$i]['name']; ?></option>
			<?php	 
			}
        }
		if($list1_id==0)
		{
        ?>
        <option value="0" id="misc" selected="selected">Miscellaneous</option>
        <?php } else { ?>
        <option value="0" id="misc">Miscellaneous</option>
        <?php } ?>
        </select>
        <?php } ?>
       </div>
       <?php  if(isset($display_list1)) {  echo '<div class="divRow" style="width:200px;" id="list2">'; }
			if(isset($display_list2)) { if(count($display_list2)!=0) {?>
            <select name="list2" id="selectList2" size="<?php echo (count($display_list2)+1); ?>" style="background: #D6D6D6;color: #2D2D2D;" onchange="javascript:Listing3(this,'<?php echo $listId; ?>','list3');">
              <?php
				for($i=0;$i<count($display_list2);$i++) 
				{
					if($list2_id==$display_list2[$i]['id'])
					{
					?>
					<option value="<?php echo $display_list2[$i]['id']; ?>" selected="selected" onclick="javascript:Listing3(this,'<?php echo $listId; ?>','list3');">
						<?php echo $display_list2[$i]['name']; ?></option>
					<?php
					}
					else
					{
					?>
					<option value="<?php echo $display_list2[$i]['id']; ?>">
						<?php echo $display_list2[$i]['name']; ?></option>
					<?php	 
					}
				}
				if($approve == 'n')
				{
					?><option value="<?php echo $txtValue; ?>" id="selOtherOpt"><?php echo $txtName; ?></option><?php
				}
				?>
            </select>
            <?php } } ?>
        <?php  if(isset($display_list1)) {  echo '</div>'; }	?>
        
        <?php  if(isset($display_list1)) {  echo '<div class="divRow" style="width:200px;" id="list3">'; }
			if(isset($display_list3)) { if(count($display_list3)!=0) {?>
            <select name="list3" size="<?php echo count($display_list3); ?>" style="color:#2D2D2D;background-color:#D6D6D6" onchange="javascript:Listing3(this,'<?php echo $listId; ?>','');" onclick="javascript:Listing3(this,'<?php echo $listId; ?>','');">
              <?php
				for($i=0;$i<count($display_list3);$i++) 
				{
					if($list3_id==$display_list3[$i]['id'])
					{
					?>
					<option value="<?php echo $display_list3[$i]['id']; ?>" selected="selected">
						<?php echo $display_list3[$i]['name']; ?></option>
					<?php
					}
					else
					{
					?>
					<option value="<?php echo $display_list3[$i]['id']; ?>">
						<?php echo $display_list3[$i]['name']; ?></option>
					<?php	 
					}
				}
				?>
            </select>
            <?php } } 
         if(isset($display_list1)) {  echo '</div>'; }	?>
         
   <div class="clear"></div>
</div>

<?php  if(isset($display_list1)) { 	?>
    <div style="width:650px;" id="click">
   	<?php
	if($submit==1)
	{	
		?><br/><br/><br/><span>Click Here to Store Your Progress and Proceed to STEP-2&nbsp;&nbsp;<input type="image" src="public/images/search-button.gif" name="submit" value="Save"/></span><?php
	}
	?>
    </div>

 	</form>  
 <?php } ?>