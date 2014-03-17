<!-- start my Usage section -->

<div id="myUsageBlock<?php echo $bookingId; ?>">
<div class="myUsage" style="border-bottom:dashed thin #9D9C9C">
<form name="myUsage<?php echo $bookingId; ?>" id="myUsage<?php echo $bookingId; ?>" method="post" action="index.php?process=storeUsageData">
<input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId; ?>"/>
<input type="hidden" name="bookingId" id="bookingId" value="<?php echo $bookingId; ?>"/>
<input type="hidden" name="totalUsages" id="totalUsages" value="<?php echo $totalUsages; ?>"/>
<input type="hidden" name="params" id="params" value="<?php echo $params; ?>"/>

<div style="width:650px;min-height:20px;"><span id="errorUsage"><?php echo $param2; ?></span></div>

<div style="width:650px;">

<div class="divRow" style="width:80px;padding:10px 10px 0px 0px;">
<span><?php echo $bookingDate; ?></span><br/> 
<span id="smallText"><a href="javascript:void(0);" onclick="didNotUse('<?php echo $bookingId; ?>');">Did Not Use</a></span>
</div>
     
<div style="width:550px;" class="divRow">
		<div style="width:550px;">
             <div class="usageUnit"  id="0">
                 
            </div> 
            </div>
 <?php 
 $usageIdsArray = array();
 for($i=0;$i<$totalUsages;$i++) { 	
     $usageId=$groupUsageInfo[$i]['id'];
     $usageType=$groupUsageInfo[$i]['type'];
     $usageUnit=$groupUsageInfo[$i]['unit'];	
	 $groupCreatedDt=strtotime($groupUsageInfo[$i]['created_dt']);
	 if($startBookDt >= $groupCreatedDt)
	 {	
              array_push($usageIdsArray,$usageId);
             ?>
             <input type="hidden" name="ids_<?php echo $bookingId; ?>_<?php echo $usageId; ?>" id="ids_<?php echo $bookingId; ?>_<?php echo $usageId; ?>" value="0"/>
                 <div class="usageUnit" id="<?php echo $usageId; ?>" align="center">
                     <?php  if($usageType=='T') { //if($i==0) { ?>
                     		<!--start date-->
                             <div class="divRow" style="width:111px;margin-right:5px;">
                                <span style="color:#000000;">Start Date</span><br/>
                                <input name="usageStartDate" id="usageStartDate_<?php echo $bookingId; ?>" class="usageStartDate" value="<?php echo date('Y-m-d',$startBookDt); ?>" size="10" readonly="readonly" onclick="javascript:pastDatePicker(this);" title="<?php echo date('Y-m-d',$startBookDt); ?>"style="text-align:center;"/>
                              </div>
                              <!--start hour-->
                               <div class="divRow" style="width:44px;margin-right:2px;">
                                <span style="color:#000000;">Hour </span><br/>
                                <input type="text" class="timeInputH" name="startH" id="startH_<?php echo $bookingId; ?>" value="hh" size="2" maxlength="2" onfocus="javascript:timeFocus(this,'h');" onblur="javascript:timeBlur(this,'h');" onkeyup="javascript:checkTime(this,'h')" onmousedown="javascript:checkTime(this,'h')"  style="text-align:center;"/>
                                <br/><div style="min-height:20px;width:80px;" id="msgstartH<?php echo $bookingId; ?>"></div>
                                </div>
                                <!--start minute-->
                                <div class="divRow" style="width:45px;margin-right:2px;">
                               <span style="color:#000000;">Minute </span><br/>
                                <input type="text" class="timeInputM" name="startM" id="startM_<?php echo $bookingId; ?>" value="mm" size="2" maxlength="2" onfocus="javascript:timeFocus(this,'m');" onblur="javascript:timeBlur(this,'m');" onkeyup="javascript:checkTime(this,'m')" onmousedown="javascript:checkTime(this,'m')" style="text-align:center;"/>
                                <br/><div style="min-height:20px;width:40px;" id="msgstartM<?php echo $bookingId; ?>"></div>
                               </div>
                         	 <!--finish date-->
                             <div class="divRow" style="width:111px;margin-right:5px;">
                                 <span style="color:#000000;">Finish Date </span><br/>
                                 <input name="usageFinishDate" id="usageFinishDate_<?php echo $bookingId; ?>" class="usageFinishDate" value="<?php echo date('Y-m-d',$finishBookDt); ?>" size="10" readonly="readonly" onclick="javascript:pastDatePicker(this);" title="<?php echo date('Y-m-d',$finishBookDt); ?>" style="text-align:center;"/>
                              </div>
                              <!--finish hour-->
                              <div class="divRow" style="width:44px;margin-right:2px;">
                               <span style="color:#000000;">Hour </span><br/>
                                <input type="text" class="timeInputH" name="finishH" id="finishH_<?php echo $bookingId; ?>" size="2" value="hh" maxlength="2" onfocus="javascript:timeFocus(this,'h');" onblur="javascript:timeBlur(this,'h');"  onkeyup="javascript:checkTime(this,'h')" onmousedown="javascript:checkTime(this,'h')" style="text-align:center;"/>
                                 <br/><div style="min-height:20px;width:80px;" id="msgfinishH<?php echo $bookingId; ?>"></div>
                              </div>
                              <!--finish minute-->
                              <div class="divRow" style="width:45px;margin-right:2px;">  
                               <span style="color:#000000;">Minute </span><br/>
                                 <input type="text" class="timeInputM" name="finishM" id="finishM_<?php echo $bookingId; ?>" size="2" value="mm" maxlength="2" onfocus="javascript:timeFocus(this,'m');" onblur="javascript:timeBlur(this,'m');"  onkeyup="javascript:checkTime(this,'m')" onmousedown="javascript:checkTime(this,'m')" style="text-align:center;"/><br/>
                                  <br/><div style="min-height:20px;width:40px;" id="msgfinishM<?php echo $bookingId; ?>"></div>
                               </div>
                            <!--Total-->
                             <div class="divRow" style="width:100px;" align="center">
                                 <span style="color:#000000;" id="totalTime">Total</span>
                                 <div id="totalTimeDiff<?php echo $bookingId; ?>" style="border:1px solid #333;width:50px;height:18px;"></div>
                             </div>
                            <div class="clear"></div>
                     <?php  } else if($usageType=='SE') { //else if($i==1) { ?>
                         <div class="divRow" style="width:135px;margin-right:10px;">
                             <span style="color:#000000;text-transform:capitalize;"><?php echo $usageUnit; ?>&nbsp;Start</span><br/>
                             <input type="text" size="16" class="inputUsage<?php echo $bookingId; ?>" name="startValue_0_<?php echo $usageId; ?>" value="" id="<?php echo $bookingId; ?>_s_<?php echo $usageId; ?>_0" onkeyup="javascript:checkValues(this);" onmousedown="javascript:checkValues(this);" maxlength="16" style="background:#9D9C9C;border:0;	color:black;padding:3px 3px 3px 5px;font-size:10px;"/>
                             <div style="min-height:20px;" id="msg0<?php echo $bookingId; ?>s<?php echo $usageId; ?>"></div>
                         </div>
                         <!-- finish unit -->
                         <div class="divRow" style="width:135px;margin-right:10px;">
                             <span style="color:#000000; text-transform:capitalize;"><?php echo $usageUnit; ?>&nbsp;Finish</span><br/>
                             <input type="text" size="16"  class="inputUsage<?php echo $bookingId; ?>" name="finishValue_0_<?php echo $usageId; ?>" value="" id="<?php echo $bookingId; ?>_f_<?php echo $usageId; ?>_0" onkeyup="javascript:checkValues(this);" onmousedown="javascript:checkValues(this);"  maxlength="16" style="background:#9D9C9C;border:0;	color:black;padding:3px 3px 3px 5px;font-size:10px;"/>
                             <div style="min-height:20px;" id="msg0<?php echo $bookingId; ?>f<?php echo $usageId; ?>"></div>
                         </div>
                         <!-- Total -->
                          <div class="divRow" style="width:155px;" align="center">
                             <span style="color:#000000;" id="totalTime">Total</span>
                             <div id="totalUsageDiff_0_<?php echo $bookingId; ?>_<?php echo $usageId; ?>" style="border:1px solid #333;width:130px;height:18px;"></div>
                         </div>
                         <!-- addAnother -->
                         <div class="divRow" style="width:100px;">
                         	<br/><span id="smallText"><a href="javascript:void(0);" onclick="javascript:addAnotherUsage('<?php echo $bookingId; ?>','<?php echo $usageId; ?>','<?php echo $usageUnit; ?>');">Add another <?php echo $usageUnit; ?></a></span>
                         </div>
                    <div class="clear"></div>
                    
                    <!--another block content-->
                    <div style="display:none" id="addAnotherContent_<?php echo $bookingId; ?>_<?php echo $usageId; ?>"></div>
                    
                     <?php } 
                     ?>
                 </div>
             <!--</div>-->
			 <?php
             }
			 
 }?>
</div>
<div class="clear"></div>
<div align="right">
	<?php $usageIds = implode(",", $usageIdsArray);?>
    <input type="button" class="submitMyUsage" name="submitMyUsage" id="<?php echo $bookingId; ?>" value="Submit" alt="submit" onclick="javascript:submitForm('<?php echo $bookingId; ?>','<?php echo $usageIds; ?>');"/>
</div>

</div>

</form>
</div>
</div>
<!-- end my usage section -->