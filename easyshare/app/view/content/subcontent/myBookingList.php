<div id="my_booking" style="font-size:10px;">
    <div style="font-size:13px;font-weight:bold" id="orangeText">My Bookings</div>
    <div>
    <?php if(count($memberBookInfo)!=0) { ?>
    <select name="myBookings" id="myBookings" size="7" onchange="javascript:myBookedSlot(this);">
    <?php 
	for($m=0;$m<count($memberBookInfo);$m++)
	{
		$myBookId=$memberBookInfo[$m]['id'];
		$startDate=$memberBookInfo[$m]['startDate'];
		$finishDate=$memberBookInfo[$m]['finishDate'];
		$startTime=$memberBookInfo[$m]['startTime'];
		$finishTime=$memberBookInfo[$m]['finishTime'];
		$start_d=strtotime($startDate . " " .$startTime);
		$finish_d=strtotime($finishDate . " " . $finishTime);
	?>
    <option value="<?php echo $myBookId; ?>" id="<?php echo date('Y-m-d-M-D-H',$start_d); ?>,<?php echo date('Y-m-d-M-D-H',$finish_d); ?>"><?php echo date('d M h a',$start_d); ?></option>
    <?php } ?>
    </select>
    <?php } else { ?>
	<div style="display:block;width:100px;height:75px;">No Bookings</div>
	<?php }?>
     </div>
</div>