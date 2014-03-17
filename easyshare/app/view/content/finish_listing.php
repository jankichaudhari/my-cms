<div id="mainInfo">
<div id="subContainer">
<p><input type="button" name="back" value="Back" onclick="return show_step4('<?php echo $listId; ?>');"/></p>
<?php
if($save=='finish')
{
	echo "You have successfully finished Listing....Your Listing will activated soon...";
}
else
{
	echo "Your Listing has been stored but not activated.";
}
?>
</div>
</div>