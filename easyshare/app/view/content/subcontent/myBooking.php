<!-- start group booking section -->
<div style="width:650px;border-bottom:thin solid #9D9D9D;padding-bottom:20px;">
<form name="myBooking" id="myBooking">
<input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId; ?>"/>
<input type="hidden" name="memberId" id="memberId" value="<?php echo $memberId; ?>"/>
<input type="hidden" name="groupMC" id="groupMC" value="<?php echo $groupMC; ?>"/>
<input type="hidden" name="start_dt" id="start_dt" value="none"/>
<input type="hidden" name="finish_dt" id="finish_dt" value="none"/>
<div id="errorBooking"></div>
<div style="width:650px;">
	<!-- calender -->
    <div style="width:160px;" id="calender" class="divRow">
        <!--<span id="datepicker"></span>-->
         <?php $this->calendar_page($memberId,$groupMC);  ?>
         <div style="position:relative;top:10px;">
         	<div><img src="public/images/no_available.jpg" width="10" height="10"/>&nbsp;&nbsp;&nbsp;<span id="smallText">No Availability</span></div>
            <div><img src="public/images/greysquare.gif" width="9" height="9"/>&nbsp;&nbsp;&nbsp;<span id="smallText">Partial Availability</span></div>
            <div><img src="public/images/my_booking.jpg" width="10" height="11"/>&nbsp;&nbsp;&nbsp;<span id="smallText">My Booking</span></div>
            <div><img src="public/images/activeImg.gif" width="9" height="9"/>&nbsp;&nbsp;&nbsp;<span id="smallText">Selected Day</span></div>
         </div>
        </div>
       <!-- end calender --> 
   <!-- time slots -->
   <div style="width:220px;" class="divRow">
    <div id="time_slot" style="position:relative;bottom:3px;"><?php $this->time_slots($memberId,$groupMC); ?></div>
     <div><p><input type="button" name="removeAllBooking" id="removeAllBooking" value="Remove All" alt="Remove All"/></p></div>
    </div>
    <!-- end time slots -->

    <div style="width:255px;" class="divRow">
        <div id="newBooking" style="width:255px;">
        	<!-- start new booking-->
            <?php $this->new_booking($memberId,$groupMC); ?>
            <div id="txtboxMOT">Description&nbsp;:&nbsp;<input type="text" name="txtMOT" id="txtMOT" value=""/></div>
             <!-- end new booking--></div>
        <div style="width:255px;">
        	<div style="width:120px;padding-right:5px;" class="divRow">
         	<!-- start my booking-->
			<div id="myBooking" ><?php $this->my_booking_list($memberId,$groupMC);  ?></div>
               <!-- end my booking-->
              <div style="width:255px;"> <p>
               <input type="button" name="submitMyBooking" id="submitMyBooking" value="Submit" alt="submit"/>
               <input type="reset" name="resetBooking" id="resetBooking" value="Reset" alt="Reset"/>
               <input type="button" name="removeBooking" id="removeBooking" value="Remove" alt="Remove"/>
               </p></div>
               <div style="width:255px;"> <p><span id="smallText">To view or Remove a Booking select it from the list above</span></p></div>
            </div>
           
            <?php if($groupMC=='M') { ?>
            <div id="allBooking" style="width:120px;padding-right:5px;" class="divRow">
         	<!-- start all booking-->
			<?php $this->all_booking_list($memberId,$groupMC);  ?>
               <!-- end all booking-->
            </div>
            <?php } ?>
            <div class="clear"></div>
        </div>
    </div>
    
<div class="clear"></div>
</div>
</form>
</div>
<!-- end group booking section -->