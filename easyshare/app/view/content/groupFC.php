<div class='mainInfo'>
<div id="subContainer">
<h1>Finance Control</h1>
<?php $this->invoice_payment($groupId,$groupMonth,$sr_io_type); ?>
<?php  $this->groupAccount($groupId,$groupMonth,$sr_io_type,$sr_io_pagenum,NULL); ?>
<!-- start Billing Preferences section -->
<?php $this->groupBilling($groupId,'F',$groupMonth,$sr_io_type,$sr_io_pagenum); ?>
<!-- end Billing Preferences section -->
</div>
</div>
