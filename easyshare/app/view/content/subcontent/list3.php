<?php if(isset($display_list3)) { if(count($display_list3)!=0) {?>
            <select name="list3" size="<?php echo count($display_list3); ?>" style="background: #D6D6D6;color: #2D2D2D;" onchange="javascript:Listing4(this,'<?php echo $listId; ?>');">
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
            <?php } else { $submit="yes"; }} ?>