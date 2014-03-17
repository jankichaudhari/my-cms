<form name="list" action="index.php?process=store_step1" method="post" onsubmit="return store_step1('<?php echo $cat_id; ?>','<?php echo $listId; ?>');">
<input type="hidden" name="listId" value="<?php echo $listId; ?>"/>
<input type="hidden" name="catId" value="<?php echo $cat_id; ?>"/>
	<?php if(isset($display_list1)) { ?>
    STEP-1&nbsp;&nbsp;<img src="public/images/barstep1of4.gif"  alt="Easyshare Step 1" />
    <p>Title&nbsp;:&nbsp;<input type="text" name="txtTitle" value="<?php echo $title ?>" required="required" maxlength="50" size="30"/>
&nbsp;&nbsp;<span id="enterTitle" style="color:red;"></span></p><br /><br />
    <p>You have successfully Categorized your Listing.</p><br/>
    Category&nbsp;:&nbsp;Select the Appropriate Category for your Listing.&nbsp;<a href="#">what's this?</a>
    <p>
        <select name="list1" size="<?php echo count($display_list1); ?>" style="background:#D6D6D6;color:#2D2D2D;">
        <?php
        for($i=0;$i<count($display_list1);$i++) 
        {
        	if($list1_id==$display_list1[$i]['id'])
			{
			?>
            <option value="<?php echo $display_list1[$i]['id']; ?>" onClick="javascript:Listing2('<?php echo $display_list1[$i]['id']; ?>','<?php echo $listId; ?>')" selected="selected">
				<?php echo $display_list1[$i]['name']; ?></option>
            <?php
			}
			else
			{
			?>
			<option value="<?php echo $display_list1[$i]['id']; ?>" onClick="javascript:Listing2('<?php echo $display_list1[$i]['id']; ?>','<?php echo $listId; ?>')">
				<?php echo $display_list1[$i]['name']; ?></option>
			<?php	 
			}
        }
        ?>
        </select>
        <?php } ?>
        <span id="list2">
			<?php if(isset($display_list2)) { if(count($display_list2)!=0) {?>
            <select name="list2" size="<?php echo count($display_list2); ?>" style="background:#D6D6D6;color:#2D2D2D;">
              <?php
				for($i=0;$i<count($display_list2);$i++) 
				{
					if($list2_id==$display_list2[$i]['id'])
					{
					?>
					<option value="<?php echo $display_list2[$i]['id']; ?>" onClick="javascript:Listing3('<?php echo $display_list2[$i]['id']; ?>','<?php echo $listId; ?>')" selected="selected">
						<?php echo $display_list2[$i]['name']; ?></option>
					<?php
					}
					else
					{
					?>
					<option value="<?php echo $display_list2[$i]['id']; ?>" onClick="javascript:Listing3('<?php echo $display_list2[$i]['id']; ?>','<?php echo $listId; ?>')">
						<?php echo $display_list2[$i]['name']; ?></option>
					<?php	 
					}
				}
				?>
            </select>
            <?php } else { $submit="yes"; }} ?>
        </span>
        <span id="list3">
			<?php if(isset($display_list3)) { if(count($display_list3)!=0) {?>
            <select name="list3" size="<?php echo count($display_list3); ?>" style="background:#D6D6D6;color:#2D2D2D;">
              <?php
				for($i=0;$i<count($display_list3);$i++) 
				{
					if($list3_id==$display_list3[$i]['id'])
					{
					?>
					<option value="<?php echo $display_list3[$i]['id']; ?>" onClick="javascript:Listing4('<?php echo $display_list3[$i]['id']; ?>','<?php echo $listId; ?>')" selected="selected">
						<?php echo $display_list3[$i]['name']; ?></option>
					<?php
					}
					else
					{
					?>
					<option value="<?php echo $display_list3[$i]['id']; ?>" onClick="javascript:Listing4('<?php echo $display_list3[$i]['id']; ?>','<?php echo $listId; ?>')">
						<?php echo $display_list3[$i]['name']; ?></option>
					<?php	 
					}
				}
				?>
            </select>
            <?php } else { $submit="yes"; }} ?>
        </span>
    </p><br/>
   	<?php
	if($submit=="yes")
	{	
		?><br/><br/><br/><span>Click Here to Store Your Progress and Proceed to STEP-2&nbsp;&nbsp;<input type="submit" style="background-image:url(public/images/search-button.gif);width:25px;cursor:hand;" name="submit" value=""/></span><?php
	}
	?>
    <span id="click"></span>
</form>