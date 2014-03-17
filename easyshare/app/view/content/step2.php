<div class='mainInfo'>

<div class="divRow" style="width:400px;">
<h1>Add a Listing</h1><br/>
<div>
STEP-2&nbsp;&nbsp;<img src="public/images/barstep<?php echo $storedStep; ?>of4.gif" alt="Easyshare step 2" border="0" usemap="#Map"/>
<map name="Map" id="Map">
<?php for($s=1;$s<=$storedStep;$s++) 
{ 
	switch($s)
	{
		case 1 : $coords = "0,0,13,13";
		break;
		case 3 : $coords = "30,0,43,13";
		break;
		case 4 : $coords = "44,0,58,12";
		break;
		default : $coords="15,0,28,12";
		break;
	}
?>
  <!--<area shape="rect" coords="0,0,13,13" href="javascript:void(0);" alt="Easyshare step 2" title="step 1" onclick="return show_step1('<?php echo $list_id; ?>');"/>-->
  <area shape="rect" coords="<?php echo $coords; ?>" href="javascript:void(0);" alt="step <?php echo $s; ?>" title="step <?php echo $s; ?>" onclick="return show_step<?php echo $s; ?>('<?php echo $list_id; ?>');"/>
  <?php } ?>
</map>
</div>
</div>
<div class="divRow" style="width:250px;height:80px;" align="right"></div>
<div class="clear"></div>

<div>
    <p>Listing for <span style="color:#000000;font-weight:bold;"><?php echo $listingname ?></span>
    <span id="msg" name="msg" style="">&nbsp;&nbsp;[* Only jpg,png,jpeg,gif files and Maximum file size is 2 MB]</span>
    </p>
 </div><br/>
 <div>
 <p><span style="color:red"><?php echo $message; ?></span></p>
 </div><br/>
<div><p style="font-weight:bold;">To include images in your listing click on the appropriate box</p></div>

<form name="step2" action="index.php?process=checkFileType" method="post" enctype="multipart/form-data">
  <input type="hidden" name="images" value=""/>
<input type="hidden" name="listId" value="<?php echo $list_id ?>" />

<div style="width:650px;">
<?php	
for($i=0;$i<9;$i++) 
{
	$img_id="img_id" . $i;
	$img_id=$$img_id;
	
	if($i==0)
	{	
		$wt = 320;
		$ht = 230;
		$imgName = 'mainImage';
		$margin = '5px 50px 1px 5px';
	}
	else if($i>0 && $i < 3)
	{
		$margin = '6px 6px 1px 6px';
		$imgName = 'image'.$i;
		$wt=105;
		$ht=80;
	}
	else
	{
		$margin = '10px 10px 1px 10px';
		$imgName = 'image'.$i;
		$wt=85;
		$ht=60;
	}
    $finalPath = "finalPath" .$i;
	$finalPath = $$finalPath;
	if(isset($imgName))
	{	
		if(!isset($finalPath))
		{
			$finalPath="public/images/noImage.jpg";
			$thisWidth = $wt;
			$thisHeight = $ht;
			$top = 0;
		}
		else
		{
		   $thisWidth = "thisWidth" .$i;
		   $thisWidth = $$thisWidth;
		   $thisHeight = "thisHeight" .$i;
			$thisHeight = $$thisHeight;
			$top = "top".$i;
			$top = ($ht - $thisHeight) / 2;
			$right = "right".$i;
			$right = ($ht - $thisWidth) / 2;
		}
	?>
    
    <div class="divRow">
       <div class="file_input_div" style="background:#FFFFFF;width:<?php echo $wt; ?>px;height:<?php echo $ht; ?>px;border: solid thin #999;margin:<?php echo $margin; ?>" align="center">
              <img src="<?php echo $finalPath; ?>"  class="file_input_img" style="margin-top:<?php echo $top; ?>px" height="<?php echo $thisHeight; ?>" width="<?php echo $thisWidth; ?>" alt="image"/>
              <input type="file" id="<?php echo $imgName; ?>" name="<?php echo $imgName; ?>" class="file_input_hidden" onchange="javascript:fileType(this);" style="height:<?php echo $ht; ?>px;width:<?php echo $wt; ?>px;font-size: <?php echo $wt; ?>px" />
         </div>
        <div>
            <?php if($finalPath!="public/images/noImage.jpg") { ?><a href="javascript:void(0);" onclick="deleteImage('<?php echo $img_id; ?>','msgimage')" id="smallText">Delete</a> <?php } ?>
            <br/><div id="msg<?php echo $imgName; ?>" style="color:#F00"></div>
        </div>
    </div>
    
<?php
	} else {	?>
    	 <div class="divRow">
           <div class="file_input_div" style="background:#000;width: <?php echo $wt; ?>px;height:<?php echo $ht; ?>px;border: solid thin #999;margin:<?php echo $margin; ?>" align="center">
                  <img src="public/images/noImage.jpg" height="<?php echo $ht; ?>" width="<?php echo $wt; ?>"  class="file_input_img" alt="image"/>
                  <input type="file" id="image<?php echo $i; ?>" name="image<?php echo $i; ?>" class="file_input_hidden" onchange="javascript:fileType(this);" style="height:<?php echo $ht; ?>px;width:<?php echo $wt; ?>px;font-size:<?php echo $wt; ?>px;" />
             </div>
              <br/><div id="msg<?php echo $imgName; ?>" style="color:#F00"></div>
        </div>
<?php
	 } 
	 if($i==2)
	 {
		 echo '<div class="clear"></div>';
		 echo '<div style="padding-top:10px;"><p>Additional images can be added below</p></div>';
	 }
}?>
</div>
<div class="clear"></div>
</form>
<br/><br/><br/>
<div>
<form name="showStep3" id="showStep3" action="index.php?process=store_step2" method="post">
<input type="hidden" name="listId" id="listId" value="<?php echo $list_id; ?>"/>
<p>Click Here to Store Your Progress and Proceed to STEP-3&nbsp;&nbsp;<input type="image" name="forward" value="Forward" src="public/images/search-button.gif" /></p>
<!--<p>Click Here to Store Your Progress and Proceed to STEP-3&nbsp;&nbsp;
<a href="index.php?process=show_step3&param1=<?php echo $list_id; ?>"><input type="button" name="forward" value="" style="background-image:url(public/images/search-button.gif);width:25px;cursor:hand;" /></a></p>-->
</form>
</div>
</div>