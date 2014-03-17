<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Easyshare</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Page-Enter" content="blendTrans(duration=0.5)" />
<link href="public/css/mainstyles.css" rel="stylesheet" type="text/css">
<!--[if IE]>
<link href="iehacks.css" rel="stylesheet" type="text/css">
<![endif]-->
<link rel="shortcut icon" href="public/favicon.ico" type="image/x-icon" />
<script type="text/javascript" src="public/scripts/jquery.js"></script>
<script type="text/javascript" src="public/scripts/calculator.js"></script>

</head>
<body>
<div align="center">
        <div style="width:650px;height:440px;text-align:left;font-size:11px;">
        	<h1 align="left">Cost Calculator</h1>
        	 <div style="width:395px;height:320px;padding:10px;">
                    <div style="width:350px;padding-bottom:20px;"><span>To calculate how much you could save with Easyshare please fill in as many fields as possible <a href="#" id="smallText">what's this?</a></span></div>
                    <div>
                        <div class="divRow" style="width:190px;height:30px;text-align:right;padding-right:5px;">Number of Group Members</div>
                        <div class="divRow" style="width:100px;height:30px;"><input type="text" name="calTotalMembers" id="calTotalMembers" value="" size="7" onKeyUp="javascript:checkDigits(this);"/></div>
                         <div class="clear"></div>
                     </div>
                    <div>
                        <div class="divRow" style="width:190px;height:30px;text-align:right;padding-right:5px;">Asset Purchase Price</div>
                        <div class="divRow" style="width:100px;height:30px;"><input type="text" name="calAssetTotalCost" id="calAssetTotalCost" value="" size="7" onKeyUp="javascript:checkDigits(this);"/></div>
                         <div class="divRow" style="width:100px;height:30px;" id="orangeText">Frequency</div>
                          <div class="clear"></div>
                    </div>
                    <div>
                        <div class="divRow" style="width:190px;height:30px;text-align:right;padding-right:5px;">Insurance</div>
                        <div class="divRow" style="width:100px;height:30px;"><input type="text" name="calInsurance" id="calInsurance" value="" size="7" onKeyUp="javascript:checkDigits(this);"/></div>
                        <div class="divRow" style="width:100px;height:30px;">
                            <select name="selectCalinsu" id="selectCalinsu" style="width:80px;">
                            <option value="1">Daily</option>
                            <option value="7">Weekly</option>
                             <option value="14">Fortnightly</option>
                            <option value="30">Monthly</option>
                            <option value="365">Yearly</option>
                            </select>
                         </div>
                          <div class="clear"></div>
                    </div>
                    <div>
                        <div class="divRow" style="width:190px;height:30px;text-align:right;padding-right:5px;">Storage</div>
                        <div class="divRow" style="width:100px;height:30px;"><input type="text" name="calStorage" id="calStorage" value="" size="7" onKeyUp="javascript:checkDigits(this);"/></div>
                         <div class="divRow" style="width:100px;height:30px;">
                            <select name="selectCalStorage" id="selectCalStorage" style="width:80px;">
                            <option value="1">Daily</option>
                            <option value="7">Weekly</option>
                             <option value="14">Fortnightly</option>
                            <option value="30">Monthly</option>
                            <option value="365">Yearly</option>
                            </select>
                         </div>
                          <div class="clear"></div>
                     </div>
                    <div>
                        <div class="divRow" style="width:190px;height:30px;text-align:right;padding-right:5px;">Fixed Maintanance</div>
                        <div class="divRow" style="width:100px;height:30px;"><input type="text" name="calFixMaintain" id="calFixMaintain" value="" size="7" onKeyUp="javascript:checkDigits(this);"/></div>
                         <div class="divRow" style="width:100px;height:30px;">
                            <select name="selectCalFixMaintain" id="selectCalFixMaintain" style="width:80px;">
                            <option value="1">Daily</option>
                            <option value="7">Weekly</option>
                             <option value="14">Fortnightly</option>
                            <option value="30">Monthly</option>
                            <option value="365">Yearly</option>
                            </select>
                         </div>
                          <div class="clear"></div>
                    </div>
                    <div>
                        <div class="divRow" style="width:190px;height:30px;text-align:right;padding-right:5px;">Variable Maintanance  (Avg)</div>
                        <div class="divRow" style="width:100px;height:30px;"><input type="text" name="calVarMaintain" id="calVarMaintain" value="" size="7" onKeyUp="javascript:checkDigits(this);"/></div>
                         <div class="divRow" style="width:100px;height:30px;">
                            <select name="selectCalVarMaintain" id="selectCalVarMaintain" style="width:80px;">
                            <option value="1">Daily</option>
                            <option value="7">Weekly</option>
                             <option value="14">Fortnightly</option>
                            <option value="30">Monthly</option>
                            <option value="365">Yearly</option>
                            </select>
                         </div>
                          <div class="clear"></div>
                    </div>
                    <div>
                        <div class="divRow" style="width:190px;height:30px;text-align:right;padding-right:5px;">Miscellaneous 1</div>
                        <div class="divRow" style="width:100px;height:30px;"><input type="text" name="calMisc1" id="calMisc1" value="" size="7" onKeyUp="javascript:checkDigits(this);"/></div>
                         <div class="divRow" style="width:100px;height:30px;">
                            <select name="selectCalMisc1" id="selectCalMisc1" style="width:80px;">
                            <option value="1">Daily</option>
                            <option value="7">Weekly</option>
                             <option value="14">Fortnightly</option>
                            <option value="30">Monthly</option>
                            <option value="365">Yearly</option>
                            </select>
                         </div>
                          <div class="clear"></div>
                    </div>
                    <div>
                            <div class="divRow" style="width:190px;height:30px;text-align:right;padding-right:5px;">Miscellaneous 2</div>
                            <div class="divRow" style="width:100px;height:30px;"><input type="text" name="calMisc2" id="calMisc2" value="" size="7" onKeyUp="javascript:checkDigits(this);"/></div>
                             <div class="divRow" style="width:100px;height:30px;">
                            <select name="selectCaliMisc2" id="selectCalMisc2" style="width:80px;">
                            <option value="1">Daily</option>
                            <option value="7">Weekly</option>
                             <option value="14">Fortnightly</option>
                            <option value="30">Monthly</option>
                            <option value="365">Yearly</option>
                            </select>
                         </div>
                          <div class="clear"></div>
                    </div>
                    <div>
                            <div class="divRow" style="width:190px;height:30px;text-align:right;padding-right:5px;">Miscellaneous 3</div>
                            <div class="divRow" style="width:100px;height:30px;"><input type="text" name="calMisc3" id="calMisc3" value="" size="7" onKeyUp="javascript:checkDigits(this);"/></div>
                             <div class="divRow" style="width:100px;height:30px;">
                            <select name="selectCaliMisc3" id="selectCalMisc3" style="width:80px;">
                            <option value="1">Daily</option>
                            <option value="7">Weekly</option>
                             <option value="14">Fortnightly</option>
                            <option value="30">Monthly</option>
                            <option value="365">Yearly</option>
                            </select>
                         </div>
                          <div class="clear"></div>
                     </div>
        </div>
        <div style="margin-top:-112px;" align="right">
       		 <div style="width:210px;height:90px;padding:10px;" align="left">
                    <div><a href="javascript:void(0);" onClick="javascript:calculator();">click here to calculate your savings > ></a></div>
                    <div style="border:1px solid #333;padding:10px;">
                        <div><!-- Easyshare logo --></div>
                         <div><span id="orangeText">Purchase Price&nbsp;&shy;</span><span id="purchasePrice"></span></div>
                         <div><span id="orangeText">Monthly Charge&nbsp;&shy;</span><span id="monthlyCharge"></span></div>
                     </div>
                </div>
         </div> 
         
        </div>
</div>
</body>
</html>