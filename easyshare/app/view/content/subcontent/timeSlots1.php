<div id="time_class">
<div id="currentDay" align="center"><?php echo $currentDay; ?></div>
<div style="width:215px;padding-right:10px;">
     <?php	$flag=false;
		for($i=0;$i<=23;$i++)
		{
			///start////
			$bgcolor = ($i%2)==0 ? $bgcolor='#FFFFFF' : $bgcolor='#D6D6D6';
			
			$activeClass=false;
			$nameFlag=false;
			 if(isset($time_state)) 
			{	
					if($time_state=='Finish') 
					{	
						 if(($i)>=$startClass) 
						 {
							if(count($startClass)!=0) 
							{	
								if(($i)==$startClass) {
									$timeStatus='Start';	
									$arrow='&nbsp;<span id="orangeText"  style="font-size:9px;">></span>';
									$activeClass=true;
								}
								else
								{
									$timeStatus=$time_state;
									$arrow='&nbsp;<span id="orangeText"  style="font-size:9px;">></span>';	
								}
							}
							else
							{	
								$timeStatus=$time_state;
								$arrow='&nbsp;<span id="orangeText"  style="font-size:9px;">></span>';	
							}
						} 
						else 
						{
							$timeStatus=''; 
							$arrow='';
						}
					}  
					else if($time_state=='Start') 
					{
						if(isset($finishClass))
						{	
							if(($i)<=$finishClass) 
							{
								if(($i)==$finishClass) 
								{
									$timeStatus='Finish';	
									$arrow='&nbsp;<span id="orangeText"  style="font-size:9px;">></span>';
									$activeClass=true;
								}
								else
								{
									$timeStatus=$time_state;
									$arrow='&nbsp;<span id="orangeText"  style="font-size:9px;">></span>';	
								}
							}
							else 
							{
								$timeStatus=''; 
								$arrow='';
							}
						} 
						else 
						{
							 $timeStatus=$time_state; 
							 $arrow='&nbsp;<span id="orangeText"  style="font-size:9px;">></span>';
						}
					}
				}
				
				for($mem=0;$mem<count($members);$mem++)
				{
					$book_Id=$bookIds[$mem][0];
					$start_tId=$members[$mem][2];
					$finish_tId=$members[$mem][3];
					$shortName=$members[$mem][1];
					if($i >= $start_tId && $i <=$finish_tId) {
						$memberName=$shortName;
						if($bookingId==$book_Id && isset($bookingId) && isset($book_Id))
						{
							$memberName='<span id="orangeText">'.$shortName.'</span>';
						}
						$nameFlag=true;
						if($timeStatus!='Finish' && $i == $finish_tId)
						{
							$nameFlag=false;
						}
						if($timeStatus=='Finish' && $i==$start_tId && $flag!=true)
						{
							$flag=true;
							$id=$start_tId;
						}
					}
				}
				
				if($groupMC!='M')
				{	
					if($flag==true)
					{	
						if($i<=$id)
						{	
							if($id==0)
							{
								$timeStatus='';
								$arrow='';
							} else
							{
								$nameFlag=false;
							}
						} else {
							$timeStatus='';
							$arrow='';
						}
					}
					//echo $startFinishId ."//". $endFinishId;
					if(count($startFinishId)!=0 && count($endFinishId)!=0)
					{	//echo $timeStatus;
						if($i >= $startFinishId && $i <= $endFinishId && $timeStatus=='Finish')
						{
							$timeStatus='Finish';
						} else if(isset($startClass) && ($i==$startClass)){
							$timeStatus='Start';
						} else {
							$timeStatus='';
							$arrow='';
						}
					}
				}
				
					if(isset($classId)) {
						if($classId==$i) {
						$classTime='activeText'; 
						} else {
						$classTime='inActiveText'; }
					} 
					else {
						$classTime='inActiveText'; 
					}
					if($activeClass==true)
					{
						$classTime='activeText'; 
						//$activeClass=false;
					}
					if($i < 12) {
						if($i==0) {
							$j=12;
						} else {
							$j=$i;
						}
						if($i==0) {
					?>
                    <!-- first time slot -->
                <div class="divRow" style="width:100px;border:thin solid #9D9C9C;margin-right:10px;">
                  <?php } ?>
					<div style="font-size:11px;background-color:<?php echo $bgcolor ?>;" class="time">
                        <div style="width:100px;">
                            <div  class="divRow" style="width:50px;">
                                <?php
								//if($timeStatus == 'Finish' || $nameFlag==1) {
								//	if($timeStatus == 'Finish') {
									if(strlen($i) < 2)
									{
										echo '0'.$i.'.00';
									}
									else
									{
										echo $i.'.00'; 
									}	
								/*} else {
									echo ($j+0.01); 
								}&nbsp;am*/
								?>
                            </div>
                            <div class="divRow <?php echo $classTime; ?>" style="width:50px;text-align:right;font-size:10px;">
                            <?php if($nameFlag==true) 
								  {
									  if($groupMC=='M')
									  {	
										  if(date('Ymd',$current_dt) == date('Ymd'))
										  {
											  if($i > date('H'))
											  {
												  ?>
												  <a href="javascript:void(0);" onClick="javascript:timeSlot('<?php echo $i; ?>','<?php echo $time_state; ?>');"  id="<?php echo $i; ?>"> 
												  <?php  echo "< " .$memberName . " >";  ?></a>
												  <?php 
											  }
										  }
										  else if(date('Ymd',$current_dt) > date('Ymd'))
										  {
											  ?>
                                              <a href="javascript:void(0);" onClick="javascript:timeSlot('<?php echo $i; ?>','<?php echo $time_state; ?>');"  id="<?php echo $i; ?>"> 
                                              <?php  echo "< " .$memberName . " >";  ?></a>
                                              <?php 
										  }
										  else
										  {
											  echo "< " .$memberName . " >";
										  }
									  }
									  else
									  {
										echo "< " .$memberName . " >";
									  }
								  } 
								  else if(date('Ymd',$current_dt) == date('Ymd'))
								  {
									if($i > date('H'))
									{
										?>
									   <a href="javascript:void(0);" onClick="javascript:timeSlot('<?php echo $i; ?>','<?php echo $time_state; ?>');"  id="<?php echo $i; ?>"> 
									   <?php  echo $timeStatus . $arrow;  ?></a>
									   <?php 
									}
							      } 
								  else
								  {
								   ?>
								   <a href="javascript:void(0);" onClick="javascript:timeSlot('<?php echo $i; ?>','<?php echo $time_state; ?>');"  id="<?php echo $i; ?>"> 
								   <?php  echo $timeStatus . $arrow;   ?>
								   </a>
								   <?php 
								  }?>
                            </div>
                        </div>
                         <div class="clear"></div>
                        </div>
                        <?php if($i==11) { ?>
                </div>
                <?php
						}
				} else if($i >= 12) {
					if($i == 12){
						$j=$i;
					} else {
						$j=$i-12;
					}
					if($i==12) {
				?>
                 <!-- second time slot -->
                  <div class="divRow" style="width:100px;border:thin solid #D6D6D6;">
                 <?php } ?> 
                    <div style="font-size:11px;background-color:<?php echo $bgcolor ?>;" class="time">
                   		<div style="width:100px;">
                            <div  class="divRow" style="width:50px;">
                                <?php
								//if($timeStatus == 'Finish' || $nameFlag==1) {
								//	if($timeStatus == 'Finish') {
									echo $i.'.00'; 
								/*} else {
									echo ($j+0.01); 
								}&nbsp;pm
*/								?>
                            </div>
                            <div class="divRow <?php echo $classTime; ?>" style="width:50px;text-align:right;font-size:10px;">
                            <?php if($nameFlag==true) 
								  {
                                    if($groupMC=='M')
									  {	
										  if(date('Ymd',$current_dt) == date('Ymd'))
										  {
											  if($i > date('H'))
											  {
												  ?><a href="javascript:void(0);" onClick="javascript:timeSlot('<?php echo $i; ?>','<?php echo $time_state; ?>');"  id="<?php echo $i; ?>"> 
												  <?php  echo "< " .$memberName . " >";  ?></a><?php 
											  }
										  }
										  else if(date('Ymd',$current_dt) > date('Ymd'))
										  {
											  ?><a href="javascript:void(0);" onClick="javascript:timeSlot('<?php echo $i; ?>','<?php echo $time_state; ?>');"  id="<?php echo $i; ?>"> 
                                              <?php  echo "< " .$memberName . " >";  ?></a><?php 
										  }
										  else
										  {
											  echo "< " .$memberName . " >";
										  }
									  }
									  else
									  {
										echo "< " .$memberName . " >";
									  }
                                  } 
								  else if(date('Ymd',$current_dt) == date('Ymd'))
								  {
									if($i > date('H'))
									{
										?><a href="javascript:void(0);" onClick="javascript:timeSlot('<?php echo $i; ?>','<?php echo $time_state; ?>');"  id="<?php echo $i; ?>"> 
										 <?php  echo $timeStatus . $arrow;   ?></a><?php 
									}
								  }
								  else
								  {
									 ?><a href="javascript:void(0);" onClick="javascript:timeSlot('<?php echo $i; ?>','<?php echo $time_state; ?>');"  id="<?php echo $i; ?>"> 
									 <?php  echo $timeStatus . $arrow;   ?></a><?php
								  }?>
                            </div>
                        </div>
                    	<div class="clear"></div>
                    </div>
                     <?php    if($i==23) { ?>
                     </div>
				<?php
					 }
				}
		}
		?>
</div>
<div style="margin-top:160px;"><p><input type="button" name="removeAllBooking" id="removeAllBooking" value="Remove All" alt="Remove All"/></p></div>
</div>