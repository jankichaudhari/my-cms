<div class='mainInfo'>
<div id="subContainer">
<h1>Maintenance Control</h1>
<?php // $this->maintenance_schedule($groupId,$groupMonth,$sr_io_type); ?>
<?php $this->maintenance_schedule($groupId); ?>
<h1><a name="maintenanceBooking">Maintenance Bookings and Availability</a></h1>
<?php $this->myBooking($groupId,'M'); ?>
</div>
</div>
