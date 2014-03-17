<div id="galery_show">
	
    <div id="gallerySlideShow">
    <?php 
    if(count($display_res) > 0)
    {
        for($i=0;$i<count($display_res);$i++) 
        { 
            $list_id=$display_res[$i]['type_id'];
            $photo_id=$display_res[$i]['id'];
            $photoSrc = $display_res[$i]['file_path'];
            $org_photoHeight = $display_res[$i]['image_height'];
            $org_photoWidth = $display_res[$i]['image_width'];
            //$req_width = 680;
            $req_width = 750;
            $req_height = 525;
            list($photoWidth,$photoHeight)=$this->model->imageSize($req_height,$req_width,$org_photoWidth,$org_photoHeight);
            $topMargin = ($req_height-$photoHeight) / 2;
            $display_listing_item=$this->model->getResult("listing_items","",""," WHERE id='$list_id' ORDER BY id DESC ");
            $title_top = $topMargin + 50;
            
            $location = '';
            $town = $display_listing_item[0]['townName'];
            $country_id = $display_listing_item[0]['country_id'];
            if(!empty($country_id))
            {
                $country_name_res=$this->model->getResult("countries"," printable_name ",""," WHERE id='$country_id'");
                $country_name = '<span style="color:#2d2d2d">'.$country_name_res[0]['printable_name'].'</span>';
                
                if(!empty($town))
                {
                    $location = $town.', '.$country_name;
                }
                else
                {
                    $location = $country_name;
                }
            }
            
            $listing_desc = stripcslashes($display_listing_item[0]['description']);
            
            ?>
    <div class="homepage_slideshow" id="<?php echo $list_id; ?>">
        <div align="center"><div class="homepage_slideshow_name" style="top:<?php echo $title_top; ?>px;width:<?php echo $photoWidth; ?>px">
		<?php echo $display_listing_item[0]['name']; ?></div></div>
        <div class="homepage_slideshow_img"><img src="<?php echo $photoSrc; ?>" alt="Easyshare" width="<?php echo $photoWidth; ?>" height="<?php echo $photoHeight; ?>" border="0" style="margin-top:<?php echo $topMargin; ?>px"></div>
    </div>
       <?php 
       }
    }
    ?>
    </div>
    
    <div id="btn_control">
        <a href="javascript:void(0);" id="nextBtn"><img src="public/images/galleryleft2.gif" width="28" height="21" alt="prev" border="0"/></a>
        <a href="javascript:void(0);" id="pauseBtn"><img src="public/images/resume.gif" width="28" height="21" alt="resume" border="0"/></a>
        <a href="javascript:void(0);" id="resumeBtn"><img src="public/images/play.gif" width="28" height="21" alt="play" border="0"/></a>
        <a href="javascript:void(0);" id="prevBtn"><img src="public/images/galleryright2.gif" width="28" height="21" alt="next" border="0"/></a>
    </div>
</div>

<div id="thumbslides">
	<!--<ul id="thumbs" class="clearfix">-->
	<ul id="thumbs" class="jcarousel jcarousel-skin-tango">
	<?php
    	if(count($display_res) > 0)
        {
            for($i=0;$i<count($display_res);$i++) 
            { 
                $photo_id=$display_res[$i]['id'];
				 $photoSrc = 'public/images/listing/thumbs/'.$display_res[$i]['file_name'].'.'.$display_res[$i]['file_type'];
				echo '<li><a href="#"><img src="'.$photoSrc.'" width="50" /></a></li>';
			}
		}
	?>
    </ul>
</div>
<!--<div id="registration_instruction">
	<a href="index.php?process=create_user_page"><img src="public/images/btn_register.png" width="300" height="100" alt="Register" border="0"/></a>
    <div align="center">
    <img src="public/images/arrow_down.jpg" width="50" height="40" alt="V"/>
    </div>
    <a href="index.php?process=edit_record"><img src="public/images/btn_list.png" width="300" height="100" alt="Register" border="0"/></a>
    <div align="center">
    <img src="public/images/arrow_down.jpg" width="50" height="40" alt="V"/>
    </div>
    <a href="index.php?process=myAccount"><img src="public/images/btn_share.png" width="300" height="100" alt="Register" border="0"/></a>
</div>
--><div class="clear"></div>