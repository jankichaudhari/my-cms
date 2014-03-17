<div class='mainInfo'>
<div id="subContainer">
<form name="changeUsage" id="changeUsage" action="index.php?process=changeUsageData" method="post">
<input type="hidden" name="bookId" id="bookId" value="<?php echo $bookId; ?>"/>
<input type="hidden" name="groupRuleId" id="groupRuleId" value="<?php echo $groupRuleId; ?>"/>
<input type="hidden" name="usageId" id="usageId" value="<?php echo $usageId; ?>"/>
<input type="hidden" name="sortIndex" id="sortIndex" value="<?php echo $sortIndex; ?>"/>
<input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId; ?>"/>
<input type="hidden" name="usageType" id="usageType" value="<?php echo $usageType; ?>"/>
<h1>Fill Up You Values </h1><br/>
<p id="errorUsage"></p>
<?php if($usageType=='SE') { ?>
<p style="text-transform:capitalize;"><?php echo $groupRuleInfo[0]['unit']; ?>&nbsp;&nbsp;Start&nbsp;:
<input type="text" name="changeStart" value="<?php if($startVal!='none'){ echo $startVal;} ?>" id="changeStart" /><span id="msgChangeStart"></span><br/></p>
<p style="text-transform:capitalize;"><?php echo $groupRuleInfo[0]['unit']; ?>&nbsp;&nbsp;Finish&nbsp;:
<input type="text" name="changeFinish" value="<?php if($finishVal!='none'){ echo $finishVal;} ?>"  id="changeFinish" /><span id="msgChangeFinish"></span><br/></p>
<p><span id="usageTotal"></span></p>
<?php } else { ?>
<div class="usageUnit" id="<?php echo $usageId; ?>" align="center">
<!--start date-->
 <div class="divRow" style="width:111px;margin-right:5px;">
    <span style="color:#CCC;">Start Date</span><br/>
    <input name="usageStartDate" id="usageStartDate_<?php echo $bookId; ?>" class="usageStartDate" value="<?php echo date('Y-m-d',$startBookDt); ?>" size="10" readonly="readonly" onclick="javascript:pastDatePicker(this);" title="<?php echo date('Y-m-d',$startBookDt); ?>"style="text-align:center;"/>
  </div>
  <!--start hour-->
   <div class="divRow" style="width:44px;margin-right:2px;">
    <span style="color:#CCC;">Hour </span><br/>
    <input type="text" class="timeInputH" name="startH" id="startH_<?php echo $bookId; ?>" value="<?php if(isset($starth)) { echo $starth; } else { echo 'hh'; } ?>" size="2" maxlength="2" onfocus="javascript:timeFocus(this,'h');" onblur="javascript:timeBlur(this,'h');" onkeyup="javascript:checkTime(this,'h')" onmousedown="javascript:checkTime(this,'h')"  style="text-align:center;"/>
    <br/><div style="min-height:20px;width:80px;" id="msgstartH<?php echo $bookId; ?>"></div>
    </div>
    <!--start minute-->
    <div class="divRow" style="width:45px;margin-right:2px;">
   <span style="color:#CCC;">Minute </span><br/>
    <input type="text" class="timeInputM" name="startM" id="startM_<?php echo $bookId; ?>" value="<?php if(isset($startm)) { echo $startm; } else { echo 'mm'; } ?>" size="2" maxlength="2" onfocus="javascript:timeFocus(this,'m');" onblur="javascript:timeBlur(this,'m');" onkeyup="javascript:checkTime(this,'m')" onmousedown="javascript:checkTime(this,'m')" style="text-align:center;"/>
    <br/><div style="min-height:20px;width:40px;" id="msgstartM<?php echo $bookId; ?>"></div>
   </div>
 <!--finish date-->
 <div class="divRow" style="width:111px;margin-right:5px;">
     <span style="color:#CCC;">Finish Date </span><br/>
     <input name="usageFinishDate" id="usageFinishDate_<?php echo $bookId; ?>" class="usageFinishDate" value="<?php echo date('Y-m-d',$finishBookDt); ?>" size="10" readonly="readonly" onclick="javascript:pastDatePicker(this);" title="<?php echo date('Y-m-d',$finishBookDt); ?>" style="text-align:center;"/>
  </div>
  <!--finish hour-->
  <div class="divRow" style="width:44px;margin-right:2px;">
   <span style="color:#CCC;">Hour </span><br/>
    <input type="text" class="timeInputH" name="finishH" id="finishH_<?php echo $bookId; ?>" size="2" value="<?php if(isset($finishh)) { echo $finishh; } else { echo 'hh'; } ?>" maxlength="2" onfocus="javascript:timeFocus(this,'h');" onblur="javascript:timeBlur(this,'h');"  onkeyup="javascript:checkTime(this,'h')" onmousedown="javascript:checkTime(this,'h')" style="text-align:center;"/>
     <br/><div style="min-height:20px;width:80px;" id="msgfinishH<?php echo $bookId; ?>"></div>
  </div>
  <!--finish minute-->
  <div class="divRow" style="width:45px;margin-right:2px;">  
   <span style="color:#CCC;">Minute </span><br/>
     <input type="text" class="timeInputM" name="finishM" id="finishM_<?php echo $bookId; ?>" size="2" value="<?php if(isset($finishm)) { echo $finishm; } else { echo 'mm'; } ?>" maxlength="2" onfocus="javascript:timeFocus(this,'m');" onblur="javascript:timeBlur(this,'m');"  onkeyup="javascript:checkTime(this,'m')" onmousedown="javascript:checkTime(this,'m')" style="text-align:center;"/>
      <br/><div style="min-height:20px;width:40px;" id="msgfinishM<?php echo $bookId; ?>"></div>
   </div>
<!--Total-->
 <div class="divRow" style="width:100px;" align="center">
     <span style="color:#CCC;" id="totalTime">Total</span>
     <div id="totalTimeDiff<?php echo $bookId; ?>" style="border:1px solid #333;width:50px;height:18px;"><?php if(isset($totalTimeDiff)) { echo $totalTimeDiff; } ?></div>
 </div>
<div class="clear"></div>
</div>
<?php } ?>
<p>Click here to Add&nbsp;&nbsp;<input type="image" src="public/images/search-button.gif" style="border:0;cursor:hand;" name="addusage" value="Add" alt="Add" id="addusage"/></p>
<p><span id="searchResult"></span></p>
</form>
</div>
</div>