<div style="width:650px;border-bottom:thin solid #FFF;padding-bottom:20px;">
<span><h1><a name="bookingRules">Booking Rules</a></h1><span id="msgRules"></span></span>
<form name="groupBookRules" id="groupBookRules" action="index.php?process=store_book_rules" method="post">
    <input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId; ?>"/>
    <input type="hidden" name="groupAC" id="groupAC" value="<?php echo $groupAC; ?>"/>
    <input type="hidden" name="bookRule" id="bookRule" value="none"/>
<div id="rulesOptions">
    <ul id="rulesList">
        <li class="<?php echo $ruleLinkClass; ?>"><a href="javascript:void(0);"  id="rule"><span>Run the Group with a Rule-Based System</span></a></li>
        <li class="<?php echo $rotaLinkClass; ?>" style="margin-left:20px;"><a href="javascript:void(0);"  id="rota"><span>Run the Group with an Allocation Rota</span></a></li>
    </ul>
</div><br/><br/>
<!-- booking rules option expanded-->
<div class="txtBookingRules">
<!-- rules -->
    <span id="<?php echo $ruleClass; ?>">
    	<!-- total block -->
            <span class="<?php echo $limittotalLink; ?>"><a href="javascript:void(0);" id="limittotalBooking"><span>Limit total number of Booking days? &nbsp;&nbsp;</span></a></span><a href="#" id="smallText">what's this?</a><br/>
            <p id="<?php echo $limittotalblock; ?>">
                <span class="nowSubActive"><span>
                	Each member is allowed&nbsp;<input type="text" name="txttotalBooking" id="txttotalBooking" size="2" maxlength="4" value="<?php echo $totalDays; ?>"/>&nbsp;concurrent booking(s)
                    <span id="msgTotalBooking"></span>
                 </span></span><br/><br/>
        	</p>
           <!-- weekend block -->
                <span class="<?php echo $limitWeekendLink; ?>"><a href="javascript:void(0);" id="limitWeekendBooking"><span>Limit Weekend Booking days &nbsp;&nbsp;</span></a></span><a href="#" id="smallText">what's this?</a><br/>
                <p id="<?php echo $limitWeekendblock; ?>">
                    <span class="nowSubActive"><span>
                        Each member can have only&nbsp;<input type="text" name="txtWeekBooking" id="txtWeekBooking" size="2" maxlength="4" value="<?php echo $weekendDays; ?>"/>&nbsp;Weekend Booking(s) at any one time
                        <span id="msgWeekBooking"></span>
                        </span></span><br/><br/>
         		</p>
       		<!-- holiday block -->
                    <span class="<?php echo $limitExtendLink; ?>"><a href="javascript:void(0);" id="limitExtendBooking"><span>Limit Holiday Bookings (Extended bookings) &nbsp;&nbsp;</span></a></span><a href="#" id="smallText">what's this?</a><br/>
                    <p id="<?php echo $limitExtendblock; ?>">
                        <span class="nowSubActive"><span>
                            An ExtendedBooking is&nbsp;<input type="text" name="txtTotalHolidays" id="txtTotalHolidays" size="2" maxlength="4" value="<?php echo $totalHolidays; ?>"/>&nbsp;days or more
                            <span id="msgHolidayBooking"></span>
                        </span></span><br/>
                        <span class="nowSubActive"><span>
                            Each member can have only&nbsp;<input type="text" name="txtHolidays" id="txtHolidays" size="2" maxlength="4" value="<?php echo $perHolidays; ?>"/>&nbsp;Extended Booking(s) at any one time
                            <span id="msgEachHolidayBooking"></span>
                         </span></span>
          			</p>
    </span>
  <!-- end rules -->
   <!--  rota -->
    <span id="<?php echo $rotaClass; ?>">
        <span class="nowSubActive" style="margin-left:295px;">
        <span>Allocation on &nbsp;<select name="selectTime" id="selectTime">
                <?php
				   for($i=0;$i<count($allocation_units);$i++)
				   {
					   if($allocation_units[$i]['id'] ==$rule_id) {
						   ?><option value="<?php echo $allocation_units[$i]['id'] ?>" selected="selected"><?php echo $allocation_units[$i]['name'];?></option><?php
					   } else {
						  ?><option value="<?php echo $allocation_units[$i]['id'] ?>"><?php echo $allocation_units[$i]['name'];?></option><?php
					   }
				   }
				   ?>
                </select>&nbsp;&nbsp;Rota
        </span></span>
    </span>
    <!-- rota -->
</div>
<input type="image" name="storeBookRules" id="storeBookRules" value="Save" align="right"/>
            </form>
</div>