<?php
//print_r($listing_details)
?>
<script type="text/javascript">
$(document).ready(function () {
	$(".indulgeonpopup").colorbox({iframe:true, innerWidth:500, innerHeight:347, scrolling:false});
});

function imagereplace(thumbid){
    var oldpath = $("#main_image img").attr("src");
    var newpath = $("#"+thumbid+" img").attr("src");
    //alert(oldpath);
    $("#main_image img").attr("src",newpath);
    $("#"+thumbid+" img").attr("src",oldpath);
}

</script>
<!-- /*STYLE SECTION - move to css doc when done*/ -->
<style type="text/css" media="all">
#listing_main_details{width:400px;border:solid 1px #2D2D2D;display:inline;float:left;padding:15px 20px;margin:0;background:#FFFFFF;min-height:285px;}
#listing_main_details h3{font-size:14px;font-weight:normal;text-align:left;border-bottom:dotted 1px #666;width:250px;}
#listing_main_details .imagecrop{float:right;margin:0 0 15px 15px;width:120px;height:80px;overflow:hidden}
#listing_main_details img{width:120px;height:auto;overflow:hidden}

#listing_sidebar{width:170px;border:solid 1px #2D2D2D;display:inline;float:left;padding:15px 10px;margin:0;background:#D6D6D6;min-height:285px;}
#listing_sidebar dd{padding:0;margin:0;}
#listing_sidebar dd{color:#0D8DEC;font-style:normal;font-size:10px}
#listing_sidebar dt{color:#2D2D2D;font-style:normal;font-size:11px}
#listing_sidebar dd,#listing_sidebar dt{display: block; float: left;text-align:left;margin:0;padding:0;width:75px;height:22px;line-height:11px;}
#listing_sidebar dt{ clear: both; padding-left:2px;}

#listing_sidebar a{color:#000066;}
#listing_sidebar a:hover{color:#000066;border-bottom:dotted 1px #000066;}
#listing_sidebar .imagecrop {width:76px;height:50px;display:inline-block;overflow:hidden;border:dotted 1px #444;}
#listing_sidebar img {width:76px;height:auto;overflow:hidden;}

</style>
<div id="listing_main_details">
    
    <?php
    if(!file_exists($main_image)){
        $main_image = 'public/images/no-image.png';    
    }
    ?>
    
    <div id="main_image" class='imagecrop'><img src="<?php echo $main_image; ?>" alt=" " /></div>
    
    
    <h3><?php echo $listing_details[0]['name']; ?></h3>
    
    <?php echo  $listing_details[0]['description']; ?>
    
    

    
    
    <br style="clear:right;" />
</div>
<div id="listing_sidebar">
    
    <?php
    
    $additional_images_count = count($additional_images);
    $image_display_limit = 2;
    
    for($i=0;$i<$image_display_limit;$i++){
        if(!file_exists($additional_images[$i])){
            $additional_images[$i] = 'public/images/no-image.png';    
        }
        //old plain link to additional images - replaced with ajax request
        //<div class='imagecrop'><a href='{$additional_images[$i]}'><img src='{$additional_images[$i]}' alt=' ' /></a></div>
        echo "
            <div class='imagecrop' id='thumb$i'><img src='{$additional_images[$i]}' alt=' ' onclick='imagereplace(\"thumb$i\")'; /></div>
            ";
    }
    
    ?>
    
    <br />
    
    <?php echo "<dl>
    
        <dt>Cost of Share:&nbsp;</dt>
        <dd>{$listing_details[0]['cost_per_share']}</dd>
        
        <dt>Location:&nbsp;</dt>
        <dd>$listing_location</dd>
        
        <dt>Group Size:&nbsp;</dt>
        <dd>{$listing_details[0]['group_size']}</dd>
        
        <dt>Monthly Fees:&nbsp;</dt>
        <dd>{$listing_details[0]['fees']}</dd>
        
        <dt>Daily Usage Charge:&nbsp;</dt>
        <dd>{$listing_details[0]['usage_charge']}</dd>
    </dl><br style='clear:both;' />";
    ?>
    
    <div style="border-bottom:solid 1px #444;margin:10px 0 11px 0; text-align:center;font-size:1px;line-height:1px;width:130px;">&nbsp;</div>
    <?php
    
    //THESE SHOULD BE ADDED TO CONTROLLERS, BUT SINCE VIEW DOES IT, NO NEED TO REPEAT IN DIFFERENT CONTROLLER METHODS...
    if($postdata){
        $postdata = urlencode(serialize($postdata));
    }
    else if($_SESSION['search_filters']){
        $postdata = urlencode(serialize($_SESSION['search_filters']));
    }
    $tagged = $this->model->is_tagged($listing_details[0]['id'],$userId);
    
    if($tagged){
        ?>
        <a href="?process=taglisting&param1=<?php echo $listing_details[0]['id']; ?>&param2=<?php echo $postdata; ?>&param3=untag">Untag this Listing</a><br />
    <?php
    }
    else{
        ?>
        <a href="?process=taglisting&param1=<?php echo $listing_details[0]['id']; ?>&param2=<?php echo $postdata; ?>">Tag this Listing</a><br />
    <?php
    }
    ?>
    
    
    
    
    <a href="">Contact the Group</a><br />
    <a href="index.php?process=pdflisting&param1=<?php echo $listing_id; ?>" target="pdf">Download or print Info Pack</a><br />
    
    <?php if($image_display_limit<$additional_images_count){
        echo '<a class="indulgeonpopup" href="index.php?process=popupimages&param1='.$listing_details[0]['id'].'">See more images</a><br />';
    }
    ?>
    <a href="index.php?process=tellfriend&param1=<?php echo $listing_details[0]['id']; ?>&param2=<?php echo $postdata; ?>">Tell a friend</a><br />
    
    
</div>
<br style="clear:both;" />



