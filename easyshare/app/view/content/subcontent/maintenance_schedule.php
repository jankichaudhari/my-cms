<h2><a name="maintenanceSchedule">Maintenance Schedule</a></h2>
<div><span id="errorSchdule"></span></div>
<div style="width:650px;">
        	<?php	$bgcolorFlag = 0;
			if(count($calendarSchdules)!=0)
			{
				?><div style="width:650px;border:solid thin #0D8DEC;padding:5px" id="msgAdminLog"><?php
				echo '<span style="color:#F00;">Schedule Reminder:</span><br/>';
				
				for($c=0;$c<count($calendarSchdules);$c++)
				{	
					$this_id=$calendarSchdules[$c]['id'];
					$this_start_dt=$calendarSchdules[$c]['start_dt'];
					$start_dt = date('d M Y',strtotime($this_start_dt));
					$this_before_value=$calendarSchdules[$c]['before_value'];
					$periodId=$calendarSchdules[$c]['period_id'];
					$calendarSchInfo = $this->model->getResult("units",""," WHERE id='$periodId' ","");
					$thisPeriod = $calendarSchInfo[0]['name'];
					$thisPeriodValue = $calendarSchInfo[0]['value'];
					$this_reminderDesc=$calendarSchdules[$c]['reminderDesc'];
					$todayStr = strtotime("today -7 day");
					$trigger_dt = strtotime($this_start_dt . ' + '. $thisPeriodValue .' day');
					
					if($trigger_dt <= $todayStr)
					{
						$triggerDt  =	 $trigger_dt;
						while($triggerDt <= $todayStr)
						{
							$thisTriggerDt = date('d M Y',$triggerDt);
							$triggerDt = strtotime($thisTriggerDt . ' + '. $thisPeriodValue .' day');
							
							if($triggerDt > $todayStr)
							{
								$beforeDt = strtotime($thisTriggerDt . ' + '. $thisPeriodValue .' day'. ' - '. $this_before_value .' day');
								if($beforeDt <= $todayStr)
								{
									$before_dt = date('d M Y',$beforeDt);
								}
							}
						}
						if(isset($before_dt))
						{
							$trigger = date('d M Y',$triggerDt);
						}
						else if(strtotime($thisTriggerDt) <= $todayStr)
						{
							$trigger = $thisTriggerDt;
						}
						if(isset($trigger))
						{	
							if($bgcolorFlag==0)
							{
								$bgcolor='#FFFFFF';
								$bgcolorFlag = 1;
							}
							else if($bgcolorFlag==1)
							{
								$bgcolor='#D6D6D6';
								$bgcolorFlag = 0;
							}
							?>
                            <div  id="c<?php echo $this_id; ?>">
                                <div class="divRow" style="width:450px;background-color:<?php echo $bgcolor; ?>;min-height:70px;padding-top:5px;padding-left:5px;"><?php
                                echo '<span id="orangeText" style="text-transform:uppercase">'.$this_reminderDesc.'&nbsp;</span> <br/>
                                <span style="font-size:11px;">Your <span id="orangeText">' . $thisPeriod . '</span> reminder for '.$trigger.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Start Date : ' .$start_dt .'</span>' ;
                                ?></div>
                                <div class="divRow" style="width:180px;background-color:<?php echo $bgcolor; ?>;text-align:right;min-height:70px;padding-top:5px;padding-left:5px;">
                                <input type="button" name="dismissCalendarSch" value="Dismiss" onclick="javascript:dissmissReminder('<?php echo $this_id; ?>','c');"/></div>
                              </div>
                            <div class="clear"></div>
							<?php
							
						}
					}
					?> <?php
				}
			}
			?>
            <div class="clear"></div>
            <?php
			if(count($usagesSchdules)!=0)
			{
				for($u=0;$u<count($usagesSchdules);$u++)
				{	
					$this_id=$usagesSchdules[$u]['id'];
					$this_usage_id=$usagesSchdules[$u]['usage_id'];
					$this_trigger_value=$usagesSchdules[$u]['trigger_value'];
					$this_reminderDesc=$usagesSchdules[$u]['reminderDesc'];
					$usageRuleInfo = $this->model->getResult("group_usageMonitor",""," WHERE id='$this_usage_id' active='y' ","");
					$usageValueInfo = $this->model->getResult("booking_usageInfo"," MAX(finishValue) "," WHERE id IN (SELECT usageUnitValue from member_usageInfo WHERE usage_type_id='$this_usage_id' AND active='y') AND active='y' ","");
					$thisMaxValue = $usageValueInfo[0]['MAX(finishValue)'];
					if(count($usageValueInfo)!=0 && $thisMaxValue!=NULL)
					{	
						if($this_trigger_value <= $thisMaxValue)
						{
							if($bgcolorFlag==0)
							{
								$bgcolor='#000000';
								$bgcolorFlag = 1;
							}
							else if($bgcolorFlag==1)
							{
								$bgcolor='#151515';
								$bgcolorFlag = 0;
							}
							?>
                            <div  id="u<?php echo $this_id; ?>">
                                <div class="divRow" style="width:450px;background-color:<?php echo $bgcolor; ?>;min-height:70px;padding-top:5px;padding-left:5px;"><?php
                                echo '<span id="orangeText" style="text-transform:uppercase">'.$this_reminderDesc.'&nbsp;</span> <br/>
                                <span style="font-size:11px;">  <span id="orangeText">' . $thisMaxValue . '</span> usage data is entered. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trigger Value : ' .$this_trigger_value .'</span>' ;
                                ?></div>
                                <div class="divRow" style="width:180px;background-color:<?php echo $bgcolor; ?>;text-align:right;min-height:70px;padding-top:5px;padding-left:5px;">
                                <input type="button" name="dismissUsageSch" value="Dismiss" onclick="javascript:dissmissReminder('<?php echo $this_id; ?>','u');"/></div>
                              </div>
							<div class="clear"></div>
							<?php
						}
					}
					?> <?php
				}
				?> </div><?php
			}
			?>
</div>


<div style="width:650px;">
    <div style="width:300px;" class="divRow">
        <a href="index.php?process=group_mc_page&param1=<?php echo $groupId; ?>&param2=c#maintenanceSchedule"  class="schedule">Schdule based on calendar days</a>
    </div>
    <div style="width:300px;" class="divRow">
    	<a href="index.php?process=group_mc_page&param1=<?php echo $groupId; ?>&param2=u#maintenanceSchedule"  class="schedule">Schedule based on a usage monitor</a>
    </div>
    <div class="clear"></div>
</div>
<div style="width:650px;border-bottom:thin solid #9D9D9D;padding-bottom:20px;" id="maintenance_schedule">
    	<?php
		switch($schedule_type)
		{
			case 'c' : $this->calendar_schdule_list($groupId);
			break;
			case 'u' : $this->usage_schdule_list($groupId);
			break;
		}
		?>
</div>