
<div class='mainInfo'>

<div class="divRow" style="width:400px;">
<h1>Add a Listing</h1><br/>
STEP-3&nbsp;&nbsp;<img src="public/images/barstep<?php echo $storedStep; ?>of4.gif" alt="Easyshare step 3" border="0" usemap="#Map"/>
<!--<map name="Map" id="Map">
  <area shape="rect" coords="15,0,28,13" href="javascript:void(0);" alt="step 2" title="step 2" id="showStep2" onclick="return show_step2('<?php echo $listId; ?>');"/>
  <area shape="rect" coords="0,0,13,13" href="javascript:void(0);" alt="step 1" title="step 1" id="showStep1"  onclick="return show_step1('<?php echo $listId; ?>');"/>
</map>-->
<map name="Map" id="Map">
<?php for($s=1;$s<=$storedStep;$s++) 
{ 
	switch($s)
	{
		case 1 : $coords = "0,0,13,13";
		break;
		case 2 : $coords = "15,0,28,12";
		break;
		case 4 : $coords = "44,0,58,12";
		break;
		default : $coords="30,0,43,13";
		break;
	}
?>
  <area shape="rect" coords="<?php echo $coords; ?>" href="javascript:void(0);" alt="step <?php echo $s; ?>" title="step <?php echo $s; ?>" onclick="return show_step<?php echo $s; ?>('<?php echo $listId; ?>');"/>
  <?php } ?>
</map>
</div>
<div class="divRow" style="width:250px;height:80px;" align="right"></div>
<div class="clear"></div>

<div><p>Listing for <span style="color:#000000;font-weight:bold;"><?php echo $listingname ?></span></p></div><br/><br/>

<form name="step3" action="index.php?process=store_step3" method="post" onsubmit="return store_step3('<?php echo $listId; ?>');">
<input type="hidden" name="listId" id="listId" value="<?php echo $listId; ?>"/>
<input type="hidden" name="suggestTown" id="suggestTown" value="<?php echo $townname; ?>"/>
<input type="hidden" name="postCodeId" id="postCodeId" value="<?php echo $postcodeId; ?>"/>
  <span style="color:red;"><?php echo $message; ?></span>
  <p>
	Cost per share:&nbsp;&nbsp;&nbsp;&nbsp;
    <select name="currencyShare" style="background: #D6D6D6;color: #2D2D2D;">
     <?php
		   for($i=0;$i<count($currencyList);$i++)
		   {
			   if($currencyList[$i]['id']==$currencyShareId)   {
			   		?><option value="<?php echo $currencyList[$i]['id'] ?>" selected="selected"><?php echo $currencyList[$i]['symbol'];?></option><?php
			   }   else   {
				   ?><option value="<?php echo $currencyList[$i]['id'] ?>"><?php echo $currencyList[$i]['symbol'];?></option><?php
			   }
		   }
		   ?>
    <option>N/A</option>
    </select>&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" name="txtCostPerShare" id="txtCostPerShare" value="<?php echo $costPerShare; ?>" maxlength="20" />&nbsp;&nbsp;&nbsp;&nbsp;
    <span id="smallText"><a href="#">what's this?</a></span>
    <span id="msgCostShare"></span>
</p>
<p>
	Location:&nbsp;&nbsp;
    <select name="selectCountry" id="selectCountry" style="width:150px;background: #D6D6D6;color: #2D2D2D;" onchange="javascript:locationPostcode(this);">
    	<option value="0">select</option>
           <?php
		   for($i=0;$i<count($countryList);$i++)
		   {
			   if($countryList[$i]['id']==$countryId)   {
			   		?><option value="<?php echo $countryList[$i]['id'] ?>" selected="selected"><?php echo $countryList[$i]['printable_name']; ?></option><?php
			   }   else   {
				   if($i==0) {
				   ?><option value="225">United Kingdom</option>
				   <option value="226">United States</option><?php
				   }?><option value="<?php echo $countryList[$i]['id'] ?>"><?php echo $countryList[$i]['printable_name']; ?></option><?php
			   }
		   }
		   ?>
     </select>&nbsp;&nbsp;
    <span id="smallText"><a href="#">what's this?</a></span>
     <?php
	 	$postCodeStyle='style="display:none;"';
		$townStyle='style="display:none;"';
	 	if(!empty($postcode))
		{
			$postCodeStyle='';
		}
		if(!empty($townname))
		{
			$townStyle='';
		}
	 ?>
    <div id="postcodeSpan" <?php echo $postCodeStyle; ?>> <p>Postcode&nbsp;:&nbsp;
     <input type="text" name="selectPostcode" id="selectPostcode"  value="<?php echo $postcode; ?>" onkeyup="javascript:postcodeTown(this.value)"/>
     &nbsp;&nbsp;<span id="msgPostcode"></span>
     </p>
     </div>
      <div id="townSpan" <?php echo $townStyle; ?>><p>Town&nbsp;:&nbsp;<input type="text" name="townName" id="townName" value="<?php echo $townname; ?>" >&nbsp;&nbsp;</p></div>
</p>
<p>
	Group Size:&nbsp;&nbsp;
    <input type="text" name="txtGroupSize" id="txtGroupSize" value="<?php echo $groupsize; ?>" maxlength="11" size="5" />&nbsp;&nbsp;
     <span id="smallText"><a href="#">what's this?</a></span>
     <span id="msgGroupSize"></span>
</p>
<p>
	Fees:&nbsp;&nbsp;
    <select name="currencyFees" style="background: #D6D6D6;color: #2D2D2D;">
    <?php
		   for($i=0;$i<count($currencyList);$i++)
		   {
			   if($currencyList[$i]['id']==$currencyFeesId)   {
			   		?><option value="<?php echo $currencyList[$i]['id'] ?>" selected="selected"><?php echo $currencyList[$i]['symbol'];?></option><?php
			   }   else   {
				   ?><option value="<?php echo $currencyList[$i]['id'] ?>"><?php echo $currencyList[$i]['symbol'];?></option><?php
			   }
		   }
		   ?>
    <option>N/A</option>
    </select>&nbsp;&nbsp;
    <input type="text" name="txtFees" id="txtFees" value="<?php echo $fees; ?>" maxlength="11" size="10" />&nbsp;&nbsp;
    per&nbsp;&nbsp;<select name="durationFees" id="Fees" style="background: #D6D6D6;color: #2D2D2D;" onchange="javascript:addNewOption(this);">
           <?php
		   for($i=0;$i<count($listingFees);$i++)
		   {
			   if($listingFees[$i]['id']==$durationFeesId)   {
			   		?><option value="<?php echo $listingFees[$i]['id'] ?>" selected="selected"><?php echo $listingFees[$i]['name'] ?></option><?php
			   }   else   {
				    ?><option value="<?php echo $listingFees[$i]['id'] ?>"><?php echo $listingFees[$i]['name'] ?></option><?php
			   }
		   }
		   ?>
           <option value="0" id="selOtherFees">Other</option>
     </select>&nbsp;&nbsp;<span id="smallText"><a href="#">what's this?</a></span>
     <span id="msgFees"></span>
     <br/>
     <div id="otherFees"></div>
</p>
<p>
	Usage Charge:&nbsp;&nbsp;
    <select name="currencyUsage" style="background: #D6D6D6;color: #2D2D2D;">
    <?php
		   for($i=0;$i<count($currencyList);$i++)
		   {
			   if($currencyList[$i]['id']==$currencyUsageId)   {
			   		?><option value="<?php echo $currencyList[$i]['id'] ?>" selected="selected"><?php echo $currencyList[$i]['symbol'];?></option><?php
			   }   else   {
				   ?><option value="<?php echo $currencyList[$i]['id'] ?>"><?php echo $currencyList[$i]['symbol'];?></option><?php
			   }
		   }
		   ?>
    <option>N/A</option>
    </select>&nbsp;&nbsp;
    <input type="text" name="txtUsage" id="txtUsage" value="<?php echo $usage; ?>" maxlength="11" size="10" />&nbsp;&nbsp;
    per&nbsp;&nbsp;<select name="durationUsage" id="UsageCharge" onchange="javascript:addNewOption(this);">
    <?php
		   for($i=0;$i<count($listingCharge);$i++)
		   {
			   if($listingCharge[$i]['id']==$durationUsageId)   {
			  	  ?><option value="<?php echo $listingCharge[$i]['id'] ?>" selected="selected"><?php echo $listingCharge[$i]['name'] ?></option><?php
			   }   else   {
				  ?><option value="<?php echo $listingCharge[$i]['id'] ?>"><?php echo $listingCharge[$i]['name'] ?></option><?php
			   }
		   }
		   ?>
           <option value="0" id="selOtherUsageCharge">Other</option>
    </select>&nbsp;&nbsp;<span id="smallText"><a href="#">what's this?</a></span>
    <span id="msgUsageCharge"></span>
     <br/>
     <span id="otherUsageCharge"></span>
</p>
<div align="right">
	<div style="border:1px solid #333;width:200px;height:100px;padding:0px 10px 10px 10px;">
    	<h4 align="left" id="orangeText">Calculator</h4>
        <div class="divRow"><input type="text" name="calMainAmt" id="calMainAmt" size="12" style="font-size:10px;" maxlength="20" onkeyup="javascript:checkDigits(this);"/></div>
        <div class="divRow" id="orangeText" style="font-size:20px;padding:0px 2px 2px 2px;">&divide;</div>
        <div class="divRow"><input type="text" name="calDivAmt" id="calDivAmt" size="12" style="font-size:10px;" maxlength="20" onkeyup="javascript:checkDigits(this);"/></div>
        <div class="clear"></div>
        <div class="divRow" id="smallText" align="right"><a href="javascript:void(0);" onclick="calculateStep3();">click here to calculate</a></div>
        <div class="divRow" style="margin-left:12px;"><input type="text" name="calResAmt" id="calResAmt" size="12" style="font-size:10px;" maxlength="20" readonly="readonly"/></div>
        <div class="clear"></div>
    </div>
</div>
<div><a href="javascript:void(0);" onclick="javascript:window.open('index.php?process=calculator','Popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=750,height=450,left=430,top=350');return false;">Calculator</a></div>
<br/><br/><br/>
<p>Click Here to Store Your Progress and Proceed to STEP-4&nbsp;&nbsp;<input type="image" src="public/images/search-button.gif" name="submit" value="submitStep3"/></p>
</form>
</div>