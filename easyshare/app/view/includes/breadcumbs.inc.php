 <?php
	if($format == 'noFormat')
	{
	}
	else
	{
		$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$total_location=count($location[0])-1;
	?>
<div id="breadcrumb">
	<p>
	<?php for($k=$total_location;$k>0;$k--) { ?>
					<a href="<?php echo $location[2][$k]; ?>"><?php echo $location[1][$k]; ?></a> <span id="orangeText">></span>
	<?php } ?>
					<a href="<?php echo $url; ?>"><?php echo $location[1][0]; ?></a> <span ID="orangeText">></span>
	   </p>
</div>

<?php } ?>