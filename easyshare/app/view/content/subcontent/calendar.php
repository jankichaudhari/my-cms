
	<!-- calender -->
    <div style="width:160px;vertical-align:top;" id="calender_page">
        <!--<span id="datepicker"></span>-->
        <!--<input class="datepicker" id="datepicker" value="2011-03-10" />
					<label id="closeOnSelect"><input type="checkbox" /> Close on selection</label>-->
           <div class="calender_days">
         <?php
			for ($d=0;$d<7;$d++) {
			  echo '
				<div class="week_day">'.$day_names[$d].'</div>';
			}
			echo '
				<div class="r"></div>';
			for ($d=$zd;$d<=$kd;$d++) {
			  $i = mktime (0,0,0,$month,$d,date('Y'));
			  if ($i >= $pd) {
				//$today = (date('Ymd') == date('Ymd', $i))? '_selected' : '';
				$minulost = (date('Ymd') >= date('Ymd', $i+86400)) && !$allow_past;
				//$selected_dt = ($selected_date == date('Ymd', $i))? '_selected' : '';
				$selected_day = '';
				if (in_array(date('Ymd', $i), $partAvl)==true) {	
					$selected_day = '_partAvl';
				}
				if (in_array(date('Ymd', $i), $notAvl)==true) {
					if($groupMC=='M')
					{
						$selected_day = '_notavl';
						$avalibilty = '_maintanance';
					}
					else
					{
						$selected_day = '_notavl';
					}
				}
				if (in_array(date('Ymd', $i), $myBook)==true) {
					$selected_day = '_myBooking';
				}
				if($selected_date == date('Ymd', $i))
				{
					$selected_day =  '_selected';
				}
				
				$dt=date($date_format,$i);
				?>
                 <div class="day<?php echo $selected_day; ?>" style="cursor:default">
                <?php if($minulost) { echo '<span style="color:#333;">' . date('j', $i) . '</span>'; } 
							else if($selected_day=='_notavl') 
							{ 
								if($avalibilty=='_maintanance') { ?>
								<a title="<?php echo $dt; ?>"  id="<?php echo date("Y-m-d-M-D", $i); ?>" class="daylink<?php echo $selected_day; ?>" href="javascript:insertdate('<?php echo date("Y-m-d-M-D", $i); ?>')"><?php echo 'x'; ?></a>
								<?php } else {
								echo 'x';
								}
							}
							else { ?>
                            <a title="<?php echo $dt; ?>"  id="<?php echo date("Y-m-d-M-D", $i); ?>" class="daylink<?php echo $selected_day; ?>" href="javascript:insertdate('<?php echo date("Y-m-d-M-D", $i); ?>')"><?php echo date('j', $i); ?></a>
                    <?php } ?>
                </div>
                <?php
			  } else {
				echo '
				<div class="no_day">&nbsp;</div>';
			  }
			  if (date('w', $i) == 0 && $i >= $pd) {
				echo '
				<div class="r"></div>';
			  }
			}
			?>
           
            </div>
            	<div class="r"></div>
				<div class="month_title" id="<?php echo $month; ?>">
               <a href=" javascript:month('<?php echo ($month-1); ?>');"  id="month_move_prev" class="month_move">&laquo;</a>
				  <div class="month_name" id="<?php echo $month; ?>"><?php echo $month_names[date('n', mktime(0,0,0,$month,1,date('Y')))].'  '.date('Y', mktime(0,0,0,$month,1,date('Y'))); ?></div>
				  <a href=" javascript:month('<?php echo ($month+1); ?>');" id="month_move_next" class="month_move">&raquo;</a>
				</div>
    </div>
   <!-- end calender --> 