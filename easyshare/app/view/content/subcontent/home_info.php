<?php
		if(!isset($display_photo))
		{
			$photo_path = "public/images/assets/thumbs/cat1.jpg";
			$prev_photo_id = $maxPhotoId;
			$next_photo_id = $minPhotoId;
		}
		else
		{
			$photo_path = $display_photo[0]['file_path'];
			$prev_photo_id = $display_photo[0]['id'] - 1;
			$next_photo_id = $display_photo[0]['id'] + 1;
		}
		
		if($next_photo_id == ($maxPhotoId + 1))
		{
			$next_photo_id=$minPhotoId;
		}
		if($prev_photo_id == ($minPhotoId - 1))
		{
			$prev_photo_id=$maxPhotoId;
		}
?>
<div id="galleryScroll">
            <img src="public/images/galleryleft2.gif" id="prev" width="31" height="33" onclick="dispContent(<?php echo $prev_photo_id; ?>)"> <img src="public/images/gallerybar.gif" width="89" height="33"><img src="public/images/galleryright2.gif" width="31" height="33" border="0" onclick="dispContent(<?php echo $next_photo_id; ?>)">
	</div>

<?php
	echo " <br>" . $display_home_res[0]['town'] . " <br>";
	//print_r($display_photo);
?>
<div>
<img src="<?php echo $photo_path; ?>" width="400px" height="300px"><img src="<?php echo $photo_path; ?>" width="100px" height="80px"><img src="<?php echo $photo_path; ?>" width="100px" height="80px"></div>
