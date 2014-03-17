<?php
	$currentUser=$_SESSION['user_id'];
	
	$process = NULL;
	if(isset($_REQUEST['process']) )
	{
		$process = $_REQUEST['process'];
	}
	
	if($format == 'noFormat')
	{
	}
	else
	{
		//$status = 'signin';
		$status = NULL;
		if(!empty($currentUser))
		{
			if(isset($_REQUEST['process']))
			{
				$status = $_REQUEST['process'];
			}
		}
?>
<div id="leftCol">
		<ul>
        <?php if(isset($_SESSION['user'])) { ?>
            <li><a href="index.php?signout=yes">Sign Out</a></li>
        <?php }else { ?>
            <li><a href="index.php?process=signin">Sign In</a></li>
            <li><a href="index.php?process=aboutus">About Us</a></li>
        <?php } 
		if(!empty($status))
		{
			switch($status) 
			{ 
				case 'myAccount' : echo '<li><a href="#myGroup">My Group</a></li><li><a href="#myListing">My Listing</a></li><li><a href="#myTagListing">My Tagged Listing</a></li><li><a href="#acDetails">Account Details</a></li>';
				break;
				case 'groupSetup' : echo '<li><a href="#members">Members</a></li><li><a href="#bookingRules">Booking Rules</a></li><li><a href="#usageMonitors">Usage Monitors</a></li><li><a href="#billingPreference">Billing Preferancess</a></li>';
				break;
				case 'groupAdmin' : echo '<li><a href="#adminLog">Admin Log</a></li><li><a href="#members">Members</a></li><li><a href="#bookingRules">Booking Rules</a></li><li><a href="#usageMonitors">Usage Monitors</a></li>';
				break;
				case 'my_group' : echo '<li><a href="#myBooking">My Booking</a></li><li><a href="#myUsage">My Usage</a></li><li><a href="#myInvoices">My Invoices</a></li><li><a href="#financialSummary">Financial Summary</a></li>';
				break;
				case 'group_fc_page' : echo '<li><a href="#invoicePayment">Invoices and Payments</a></li><li><a href="#groupAccount">Group Account</a></li><li><a href="#billingPreference">Billing Preferancess</a></li>';
				break;
				case 'group_mc_page' : echo '<li><a href="#maintenanceSchedule">Maintenance Schedule</a></li><li><a href="#maintenanceBooking">Maintenance Bookings and Availability</a></li>';
				break;
				default : echo '<li><a href="mailto:jankichaudhari@gmail.com">Contact Us</a></li>';
				break;
			}
		}
		else { ?>
			<!--<li class="reg_instruction"><a href="index.php?process=create_user_page"><span style="font-size:30px;">Register</span> your Asset or Product</a></li>
            <li class="reg_instruction"><a href="index.php?process=edit_record">List it</a></li>
            <li class="reg_instruction"><a href="index.php?process=myAccount">Start Sharing!!</a></li>-->
		<?php } ?>
        
		</ul>
		<?php
		
		if($sidebar_search){
				include_once('app/view/includes/sidebar_search.inc.php'); 
		}
		
		?>
</div>
    
    
  <?php } ?>