
<form name="adminLog" id="adminLog">
<input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId; ?>" />
<input type="hidden" name="startLimit" id="startLimit" value="<?php echo $startLimit; ?>" />
<input type="hidden" name="endLimit" id="endLimit" value="<?php echo $endLimit; ?>" />
<div style="width:650px;border-bottom:thin solid #FFF;padding-bottom:20px;padding-top:20px;" id="adminLogPage">
	<div style="width:650px;">
        	<?php
			if(count($noInvoiceBookings)!=0)
			{
				?><div style="width:650px;border:solid thin #FCCF12;padding:5px" id="msgAdminLog"><?php
				echo '<span style="color:#F00;">IMPORTANT:</span>&nbsp;An invoice cannot be created due to insufficient data.  Please check your records and insert the correct usage data in order to generate an invoice.<br/>';
				foreach($noInvoiceBookings as $ni=>$val)
				{	
					$thisId=$noInvoiceBookings[$ni];echo "<br/>";
					$groupBookInfo=$this->model->getResult("member_bookingInfo",""," WHERE id='$thisId' AND  active='y' "," order by id");
					$thisStartTime=strtotime($groupBookInfo[0]['startDate']."  ".$groupBookInfo[0]['startTime']);
					$istartDtTm = date('d/m/Y h a',$thisStartTime);
					$thisFinishTime=strtotime($groupBookInfo[0]['finishDate']."  ".$groupBookInfo[0]['finishTime']);
					$ifinishDtTm = date('d/m/Y h a',$thisFinishTime);
					$thisMemId=$groupBookInfo[0]['member_id'];
					$memberInfo=$this->model->getResult("group_members",""," WHERE id='$thisMemId' AND  active='y' "," order by id");
					$thisGroupId=$memberInfo[0]['group_id'];
					if($thisGroupId==$groupId)
					{
						$user_id=$memberInfo[0]['user_id'];
						$userInfo=$this->model->getResult("auth_profile",""," WHERE id='$user_id'","");	
						$mem_firstNm=$userInfo[0]['first_name'];
						$mem_LastNm=$userInfo[0]['last_name'];
						$ifullUserName=$mem_firstNm ."  " .$mem_LastNm;
						
						echo 'Username : <span style="color:#FCCF12;">' . $ifullUserName . '</span><br/>';
						echo 'Start date & time : <span style="color:#FCCF12;">' . $istartDtTm . '</span><br/>';
						echo 'Finish date & time : <span style="color:#FCCF12;">' . $ifinishDtTm . '</span><br/>';
					}
				}
				?> </div><?php
			}
			?>
</div>
	<h1><a name="adminLog">Admin Log</a></h1>
    <?php if(count($array)!=0)
			{
			?>
             <div style="width:650px;">
                <div style="width:650px;" id="msgAdminLog"></div>
                <div align="right" id="smallText"><a href="javascript:void(0);" id="deleteUsageData">Remove</a></div>
                <!--Title Row-->
                <!--<div style="display:table-cell;width:10px;"></div>-->
                <div align="center">
                    <div style="width:13px;" class="divRow">
                      <span class="inActiveRecord">
                        <input type="checkbox" id="checkAll" onclick="javascript:checkUncheckAll(this,'checkAdminLog');" class="checkBoxOpt"/>
                      </span>
                    </div>
                    <div style="width:75px;" class="divRow"><a href="javascript:void(0);" class="sortLink" onclick="javascript:sortFields(1,<?php echo $pagenum; ?>);" id="1">Start Date</a></div>
                    <div style="width:75px;" class="divRow"><a href="javascript:void(0);" class="sortLink" onclick="javascript:sortFields(2,<?php echo $pagenum; ?>);" id="2">Finish Date</a></div>
                    <div style="width:70px;text-align:center;" class="divRow"><a href="javascript:void(0);" class="sortLink" onclick="javascript:sortFields(3,<?php echo $pagenum; ?>);" id="3">Member</a></div>
                    <div style="width:80px;" class="divRow"><a href="javascript:void(0);" class="sortLink" onclick="javascript:sortFields(4,<?php echo $pagenum; ?>);" id="4">Unit</a></div>
                    <div style="width:110px;" class="divRow"><a href="javascript:void(0);" class="sortLink" onclick="javascript:sortFields(5,<?php echo $pagenum; ?>);" id="5">Start</a></div>
                    <div style="width:110px;" class="divRow"><a href="javascript:void(0);" class="sortLink" onclick="javascript:sortFields(6,<?php echo $pagenum; ?>);" id="6">Finish</a></div>
                    <div style="width:110px;" class="divRow"><a href="javascript:void(0);" class="sortLink" onclick="javascript:sortFields(7,<?php echo $pagenum; ?>);" id="7">Total</a></div>
                    <div class="clear"></div>
                </div>
                <!--end Title Row-->
                <!--Data Rows-->
                <div id="adminLog">
                    <div style="width:650px;">
                <?php
                for($m=$startLimit;$m<$endLimit;$m++) { 
                    $act = 'y';
                    if($m%2==0){
                        $bgColor="#D6D6D6";
                    } else {
                        $bgColor="#FFFFFF";
                    }
                    if(in_array(($id[$m]),$unActionedEnt))
                    {
                        $color="#0D8DEC";
                    } else {
                        $color="#2C2C2C";
                    }
                    if(in_array(($id[$m]),$inactiveUsages))
                    {
                        $color="#9D9C9C";
                        $act = 'n';
                    }
                    $sd=date('d/m/y h-a',$startDate[$m]);
                    $start_dt=date('Y-m-d',$startDate[$m]);
                    $start_t=date('H:00:00',$startDate[$m]);
                    $fd=date('d/m/y h-a',$finishDate[$m]);
                    $finish_dt=date('Y-m-d',$finishDate[$m]);
                    $finish_t=date('H:00:00',$finishDate[$m]);
                    $nm=$fullName[$m];
                    $memUsageId=$usageInfoIds[$m];
                    $unit=$groupUnit[$m];
                    $su=$startUsageValue[$m];
                    if($su==0) { $su='<span id="smallText">change</span>'; }
                    $fu=$finishUsageValue[$m];
                    if($fu==0) { $fu='<span id="smallText">change</span>'; }
                    $t=$total[$m];
					$usageType = $usageTypes[$m];
					if($usageType=='T')
					{
                    	$thisTotal = $this->checkUsageDT($su,$fu);
						if($thisTotal==-1)
						{
							$t = 0;
						}
						else
						{
							$t = $thisTotal;
						}
					}
					
                    $thisBookId=$bookIds[$m];
                    $thisRuleId=$groupRuleIds[$m];
                    $memUsageId=$usageInfoIds[$m];
                ?>
                <div style="color:<?php echo $color; ?>;" class="adminLogList">
                    <div style="width:20px;background-color:<?php echo $bgColor; ?>;" class="divRow adminRow" id="smallText">
                        <span class="inActiveRecord">
                            <input type="checkbox" value="<?php echo $thisBookId ."_".$memUsageId; ?>" id="<?php echo $thisBookId ."_".$memUsageId; ?>" onclick="changeCheckStyle(this);" name="checkAdminLog" class="checkBoxOpt"/></span></div>
                    <div style="width:75px;background-color:<?php echo $bgColor; ?>;" class="divRow adminRow"><?php echo $sd; ?></div>
                    <div style="width:75px;background-color:<?php echo $bgColor; ?>;" class="divRow adminRow"><?php echo $fd; ?></div>
                    <div style="width:70px;background-color:<?php echo $bgColor; ?>;text-align:center" class="divRow adminRow"><?php echo $nm; ?></div>
                    <div style="width:80px;background-color:<?php echo $bgColor; ?>;text-transform:capitalize;text-align:center" class="divRow adminRow"><?php echo $unit; ?></div>
                    <div style="width:110px;background-color:<?php echo $bgColor; ?>;text-align:center" class="divRow adminRow">
                    <?php if($act=='n') {  
                                if($su=='<span id="smallText">change</span>') {
                                    echo "--"; 
                                } else { 
                                    echo $su; }
                                } else { ?> 
                    <a href="javascript:void(0);" onclick="changeUsageData('<?php echo $thisBookId . "_". $thisRuleId ."_" . $memUsageId ."_" .$usageType."_".$sortIndex ."_".$sortState; ?>');" class="changeAdminLog" style="color:<?php echo $color; ?>">
                        <?php echo $su; ?></a>
                        <?php } ?>
                    </div>
                    <div style="width:110px;background-color:<?php echo $bgColor; ?>;text-align:center" class="divRow adminRow">
                     <?php if($act=='n') {  
                                if($fu=='<span id="smallText">change</span>') {
                                    echo "--"; 
                                } else { 
                                    echo $fu; }
                                } else { ?> 
                    <a href="javascript:void(0);" onclick="changeUsageData('<?php echo $thisBookId . "_". $thisRuleId ."_" . $memUsageId ."_" .$usageType."_".$sortIndex ."_".$sortState; ?>');" class="changeAdminLog" style="color:<?php echo $color; ?>">
                        <?php echo $fu; ?></a>
                        <?php } ?>
                    </div>
                    <div style="width:110px;background-color:<?php echo $bgColor; ?>;text-align:center" class="divRow adminRow">
                        <?php  if($act=='n') {  echo "--";  } else { echo $t;	} ?>
                    </div>
                </div>
                <div class="clear"></div>
                <?php 
                }?>
                </div>
                    <div align="right"><div style="width:650px;font-size:12px;padding-top:5px;">
                        <?php echo $paging_html_text; ?>
                    </div></div>
                </div>
                <!--end Data Rows-->
            </div>
            <?php
			}
			else
			{
				echo "No Data Found";
			}
		?>
    
</div>
</form>
<!--admin log finish-->
