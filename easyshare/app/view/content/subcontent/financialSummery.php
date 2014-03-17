<!-- start financial summary section -->
<div style="width:650px;border-bottom:thin solid #9D9D9D;padding-bottom:20px;">
	<p>The balance of your group is currently &nbsp;<span style="color:#000000;font-weight:bold;">&pound; <?php echo number_format($groupBalance, 2, '.', ','); ?></span></p><br/>
    <p>Updated by <span style="color:#000000;font-weight:bold;"><?php echo $adminFullName; ?></span> on <span style="color:#000000;font-weight:bold;"><?php echo date('d M Y',$groupBalDate); ?></span></p>
</div>
<!-- end financial summary section -->