<div id="billingPrefer" style="width:650px;border-bottom:thin solid #9D9D9D;padding:20px 0px 20px 0px;">
    <h1><a name="billingPreference">Billing Preferences</a></h1>
    <span id="errorBilling"></span><br/>
    <form name="groupBillingRules" id="groupBillingRules" action="index.php?process=store_billing_rules" method="post">
    <input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId; ?>"/>
    <input type="hidden" name="groupFC" id="groupFC" value="<?php echo $groupFC; ?>"/>
    <input type="hidden" name="groupMonth" id="groupMonth" value="<?php echo $groupMonth; ?>"/>
    <input type="hidden" name="sr_io_type" id="sr_io_type" value="<?php echo $sr_io_type; ?>"/>
    <input type="hidden" name="sr_io_pagenum" id="sr_io_pagenum" value="<?php echo $sr_io_pagenum; ?>"/>
    <input type="hidden" name="monthBill" id="monthBill" value="none"/>
	<input type="hidden" name="usageBill" id="usageBill" value="none"/>
        <span>Add the items you wish to appear on each Monthly Invoice&nbsp;<a href="#" id="smallText">what's this?</a></span>

<!-- fixed monthly item -->
<?php
if($totalBillMonth!=0) {
 for($m=0;$m<$totalBillMonth;$m++) { 
 $monthBillId=$billingMonthInfo[$m]['id'];
 ?>
<div id="monthChargeActive">
  <p>
      Fixed monthly charge of:&nbsp;
         <select name="billCurrency<?php echo $monthBillId; ?>" id="billCurrency" style="background: #D6D6D6;color: #2D2D2D;width:35px;">
             <?php
                   for($i=0;$i<count($currencyList);$i++)
                   {
                       if($currencyList[$i]['id']==$billingMonthInfo[$m]['cost_curr'])   {
                            ?><option value="<?php echo $currencyList[$i]['id'] ?>" selected="selected"><?php echo $currencyList[$i]['symbol'];?></option><?php
                       }   else   {
                           ?><option value="<?php echo $currencyList[$i]['id'] ?>"><?php echo $currencyList[$i]['symbol'];?></option><?php
                       }
                   }
                   ?>
            <option>N/A</option>
            </select>&nbsp;
      <input type="text" name="billCost<?php echo $monthBillId; ?>" value="<?php echo $billingMonthInfo[$m]['cost']; ?>" id="billCost" size="5" maxlength="50" onkeyup="javascript:billCostValid(this.value);"/>
      &nbsp;for:&nbsp;<input type="text" name="billDetail<?php echo $monthBillId; ?>" value="<?php echo $billingMonthInfo[$m]['type_detail']; ?>" id="billDetail" size="20" maxlength="200" />
      &nbsp;<span id="smallText"><a href="javascript:void(0);" class="removeMonthBill" id="<?php echo $monthBillId; ?>" >remove</a></span></p>
</div>
<?php } } ?>
<div id="monthCharge">
  <p id="paraMonthCost_0">
      Fixed monthly charge of:&nbsp;
         <select name="monthCurrency_0" id="monthCurrency_0" style="background: #D6D6D6;color: #2D2D2D;width:35px;">
             <?php
                   for($i=0;$i<count($currencyList);$i++)
                   {
                        ?><option value="<?php echo $currencyList[$i]['id'] ?>"><?php echo $currencyList[$i]['symbol'];?></option><?php
                   }
                   ?>
            <option>N/A</option>
            </select>&nbsp;
      <input type="text" name="monthCost_0" id="monthCost_0" size="5" maxlength="50" onkeyup="javascript:billCostValid(this.value);"/>&nbsp;for:&nbsp;<input type="text" name="monthDesc_0" id="monthDesc_0" size="20" maxlength="200" />
      &nbsp;<span id="smallText"><a href="javascript:void(0)" id="removeMonthCost_0" onclick="removeMonthCost(this)">remove</a></span></p>
</div>
<!-- end fixed monthly item -->

<!-- Usage Based item -->
<?php
if($totalBillUsage!=0) {
 for($u=0;$u<$totalBillUsage;$u++) { 
 $usageBillId=$billingUsageInfo[$u]['id'];
  $usageBillActive=$billingUsageInfo[$u]['active'];
  if($usageBillActive=='n')
{
	$thisStyle = '#2C2C2C';
	$disabled = 'disabled="disabled"';
	$readOnly = 'readonly="readonly"';
	?><input type="hidden" name="billDetail<?php echo $usageBillId; ?>" id="billDetail"  value="<?php echo $billingUsageInfo[$u]['type_detail']; ?>"/><?php
}
 ?>
<div id="usageChargeActive" style="color:<?php echo $thisStyle ; ?>"><p>
Charge for individual usage based the following usage Monitor:&nbsp;
 <select name="billDetail<?php echo $usageBillId; ?>" id="billDetail" style="background: #D6D6D6;color: #2D2D2D;"  <?php echo $disabled; ?>>
     <?php
           for($i=0;$i<count($usageUnitInfo);$i++)
           {	
			  /* $selectedUnit=explode('_',$billingUsageInfo[$u]['type_detail']);
			   $selectedUnit=$selectedUnit[1];*/
			   $selectedUnit=$billingUsageInfo[$u]['type_detail'];
               if($usageUnitInfo[$i]['id']==$selectedUnit)   {
                    ?><option value="<?php echo $usageUnitInfo[$i]['id'] ?>" selected="selected" ><?php echo $usageUnitInfo[$i]['unit'];?></option><?php
               }   else   {
                   ?><option value="<?php echo $usageUnitInfo[$i]['id'] ?>"><?php echo $usageUnitInfo[$i]['unit'];?></option><?php
               }
           }
           ?>
    <option>N/A</option>
    </select>
&nbsp;<span id="smallText"><a href="javascript:void(0);" class="removeUsageBill" id="<?php echo $usageBillId; ?>" >remove</a></span><br/>
<span class="nowSubActive" style="color:<?php echo $thisStyle ; ?>"><span>Cost per Unit (Cost per Hour if the Unit is time):&nbsp;
 <select name="billCurrency<?php echo $usageBillId; ?>" id="billCurrency" style="background: #D6D6D6;color: #2D2D2D;width:35px;" <?php echo $disabled; ?>>
     <?php
           for($i=0;$i<count($currencyList);$i++)
           {
               if($currencyList[$i]['id']==$billingUsageInfo[$u]['cost_curr'])   {
                    ?><option value="<?php echo $currencyList[$i]['id'] ?>" selected="selected"><?php echo $currencyList[$i]['symbol'];?></option><?php
               }   else   {
                   ?><option value="<?php echo $currencyList[$i]['id'] ?>"><?php echo $currencyList[$i]['symbol'];?></option><?php
               }
           }
           ?>
    <option>N/A</option>
    </select>
&nbsp;<input type="text" name="billCost<?php echo $usageBillId; ?>" value="<?php echo $billingUsageInfo[$u]['cost']; ?>" id="billCost" size="10" maxlength="10" onkeyup="javascript:billCostValid(this.value);" <?php echo $readOnly; ?>/></span></span>
</p>
</div>
 <?php } } ?>
<div id="usageCharge">
<p id="paraVariantCost_0">
    Charge for individual usage based the following usage Monitor:&nbsp;
     <select name="usageList_0" id="usageList_0" style="background: #D6D6D6;color: #2D2D2D;">
         <?php
               for($i=0;$i<count($usageUnitInfo);$i++)
               {
                      ?><option value="<?php echo $usageUnitInfo[$i]['id'] ?>"><?php echo $usageUnitInfo[$i]['unit'];?></option><?php
               }
               ?>
        <option>N/A</option>
        </select>
    &nbsp;<span id="smallText"><a href="javascript:void(0);" id="removeVariantCost_0" onclick="removeVariantCost(this)">remove</a></span><br/>
    <span class="nowSubActive"><span>Cost per Unit (Cost per Hour if the Unit is time):&nbsp;
     <select name="usageCurrency_0" id="usageCurrency_0" style="background: #D6D6D6;color: #2D2D2D;width:35px;">
         <?php
               for($i=0;$i<count($currencyList);$i++)
               {
                   ?><option value="<?php echo $currencyList[$i]['id'] ?>"><?php echo $currencyList[$i]['symbol'];?></option><?php
               }
               ?>
        <option>N/A</option>
        </select>
    &nbsp;<input type="text" name="usageCost_0" id="usageCost_0" size="10" maxlength="10" onkeyup="javascript:billCostValid(this.value);"/></span></span>
    </p>
</div>
<!-- end Usage Based item -->

       <div style="padding: 5px 0px 0px 20px;width: 540px;">
       <a href="javascript:void(0);" id="monthlyUsage">Click here to Add a Fixed Monthly item</a><br/>
       <a href="javascript:void(0);" id="variantUsage">Click here to Add a Usage Based item</a>
       </div>
 <input type="image" name="storeBillingRules" id="storeBillingRules" value="Save" align="right"/>
</form>
</div>
