<div class='mainInfo'>

<div class="divRow" style="width:400px;">
<h1>Add a Listing</h1><br/>
STEP-4&nbsp;&nbsp;<img src="public/images/barstep<?php echo $storedStep; ?>of4.gif" alt="Easyshare step 4" border="0" usemap="#Map"/>
<map name="Map" id="Map">
<?php for($s=1;$s<=$storedStep;$s++) 
{ 
	switch($s)
	{
		case 1 : $coords = "0,0,13,13";
		break;
		case 3 : $coords = "30,0,43,13";
		break;
		case 2 : $coords = "15,0,28,12";
		break;
		default : $coords="44,0,58,12";
		break;
	}
?>
  <area shape="rect" coords="<?php echo $coords; ?>" href="javascript:void(0);" alt="step <?php echo $s; ?>" title="step <?php echo $s; ?>" onclick="return show_step<?php echo $s; ?>('<?php echo $listId; ?>');"/>
  <?php } ?>
</map>
</div>
<div class="divRow" style="width:250px;height:10px;" align="right"></div>
<div class="clear"></div>

<br/><div><p>Listing for <span style="color:#000000;"><?php echo $listingname ?></span></p></div><br/><br/>
<form name="step4" action="index.php?process=store_step4" method="post" onsubmit="javascript:storeStep4();">
<input type="hidden" name="listId" id="listId" value="<?php echo $listId; ?>"/>
  <span style="color:red;"><?php echo $message; ?></span>
  <p>Enter the description of your listing here.</p><br/>
    <div style="width:650px;">
    <textarea name="content" cols="70" rows="20"  maxlength="760" ><?php echo $contentText; ?></textarea>
    </div>
    <br/><br/><br/>
    <?php   if($thisApproved=='1') { ?>
    <p>Click Here to Save Your Progress&nbsp;&nbsp;<input type="image" src="public/images/search-button.gif" name="submit" value="submit"></p>
    <?php } else { ?>
    
    <p>Click here to Finish and Upload your New Listing&nbsp;&nbsp;<input type="image" src="public/images/search-button.gif" name="finish" value="finish" /></p>
    <p>Click Here to Store Your Progress and Upload later&nbsp;&nbsp;<input type="image" src="public/images/search-button.gif" name="submit" value="submit"></p>
    <?php } ?>
</form>
</div>