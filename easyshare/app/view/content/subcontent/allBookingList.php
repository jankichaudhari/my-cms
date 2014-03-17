<div id="all_booking" style="font-size:10px;">
    <div style="font-size:13px;font-weight:bold" id="orangeText">All Bookings</div>
    <div>
    <?php if(count($allBookingInfo)!=0) { ?>
    <select name="allBookings" id="allBookings" size="7" onchange="javascript:myBookedSlot(this);"">
    <?php 
	for($ab=0;$ab<count($allBookingInfo);$ab++)
	{
		$memBookId=$allBookingInfo[$ab]['id'];
		$startDate=$allBookingInfo[$ab]['startDate'];
		$finishDate=$allBookingInfo[$ab]['finishDate'];
		$startTime=$allBookingInfo[$ab]['startTime'];
		$finishTime=$allBookingInfo[$ab]['finishTime'];
		$start_d=strtotime($startDate . " " .$startTime);
		$finish_d=strtotime($finishDate . " " . $finishTime);
	?>
    <option value="<?php echo $memBookId; ?>" id="<?php echo date('Y-m-d-M-D-H',$start_d); ?>,<?php echo date('Y-m-d-M-D-H',$finish_d); ?>" onchange="javascript:allbookingDisplay();">
		<?php echo date('d M h a',$start_d); ?>
   </option>
    <?php } ?>
    </select>
    <?php } else { ?>
	<div style="display:block;width:100px;height:75px;">No Bookings</div>
	<?php }?>
     </div>
</div>