<div>
<h1><?php echo $list_name; ?></h1>
<div>Please click on the image to see in full size.</div>
<div id="this_list_slideShow">
<?php
//Listing Images
$listing_image_res=$this->model->getResult("listing_photos",""," WHERE type='L' AND type_id='$list_id' "," ORDER BY file_name DESC ");
$default_img = $listing_image_res[0]['file_path'];
$default_img_id = $listing_image_res[0]['id'];

?>
<!--<div style="background-color:#FFFFFF;width:650px;border:1px solid #2D2D2D;text-align:center;">
<img src="<?php echo $default_img; ?>" id="<?php echo $default_img_id; ?>" alt="Easyshare" width="625" border="0" style="margin-top:0px">
</div>-->
<div style="margin:10px 0px 10px 0px;">
<?php
foreach($listing_image_res as $eachImg=>$eachVal)
{
	$img_file_name = $listing_image_res[$eachImg]['file_name'];
	$img_file_id = $listing_image_res[$eachImg]['id'];
	$thumb_photoSrc = 'public/images/listing/thumbs/'.$listing_image_res[$eachImg]['file_name'].'.'.$listing_image_res[$eachImg]['file_type'];
	$photoSrc = $listing_image_res[$eachImg]['file_path'];
	$org_photoWidth = $listing_image_res[$eachImg]['image_width'];
	$org_photoHeight = $listing_image_res[$eachImg]['image_height'];
	$req_width = 110;
	$req_height=80;
	list($photoWidth,$photoHeight)=$this->model->imageSize($req_height,$req_width,$org_photoWidth,$org_photoHeight);
	$topMargin = ($req_height-$photoHeight) / 2;
	
	?>
    <div class="divRow" style="background-color:#FFFFFF;width:110px;height:80px;border:1px solid #2D2D2D;text-align:center;margin-right:30px;">
    <a href="<?php echo $photoSrc; ?>" rel="list_slideShow"><img src="<?php echo $thumb_photoSrc; ?>" alt="Easyshare" height="<?php echo $photoHeight; ?>" width="<?php echo $photoWidth; ?>" border="0" style="margin-top:<?php echo $topMargin; ?>px;max-width:100px;"></a>
    </div>
	<?php
}
?>
<div class="clear"></div>
</div>

</div>

<div id="homeDesc">
<div class="list_desc"><span>Listed By&nbsp;:&nbsp;</span><?php echo $listUserName; ?>&nbsp;&nbsp;<a href="#">Contact</a><!--&nbsp;
<span>Last Updated&nbsp;:&nbsp;</span>--><?php //echo date('d M Y',strtotime($display_listing_item[0]['modified'])); ?></div>
<?php if(!empty($thisName)) { ?>
<div class="list_desc"><span>Listing Category&nbsp;:&nbsp;</span><?php echo $thisName; ?></div>
<?php } if(!empty($location_name)) { ?>
<div class="list_desc"><span>Location&nbsp;:&nbsp;</span><?php echo $location_name; ?></div>
<?php } if(!empty($costPerShare)) { ?>
<div class="list_desc"><span>Cost per Share&nbsp;:&nbsp;</span><?php echo $costPerShare; ?></div>
<?php } if(!empty($groupSize)) { ?>
<div class="list_desc"><span>Group size&nbsp;:&nbsp;</span><?php echo $groupSize; ?></div>
<?php } if(!empty($fees)) { ?>
<div class="list_desc"><span>Fees&nbsp;:&nbsp;</span><?php echo $fees; ?></div>
<?php } if(!empty($usageCharge)) { ?>
<div class="list_desc"><span>Usage Charge&nbsp;:&nbsp;</span><?php echo $usageCharge; ?></div>
<?php } ?>
<?php echo $display_listing_item[0]['description']; ?>
</div>
</div>