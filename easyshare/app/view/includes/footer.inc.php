<?php
	if($format == 'noFormat')
	{
	}
	else
	{
?>
<div id="footer">
	<!--<p><a href="#">About Easyshare</a> | <a href="#">Advice Centre</a> | <a href="#">Contact Us</a> | <a href="#">Policies</a> | <a href="#">Help</a></p>-->
    <p>
    	<a href="mailto:jankichaudhari@gmail.com">Contact Us</a> | <a href="index.php?process=aboutus">About Us</a>
    	<?php if(isset($_SESSION['user'])) { ?>
            <a href="index.php?signout=yes">Sign Out</a>
        <?php }else { ?>
            <a href="index.php?process=signin">Sign In</a>
        <?php } ?>
    </p>
</div>
<?php } ?>