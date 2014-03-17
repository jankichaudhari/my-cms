<div class='mainInfo'>
<div id="subContainer">
<!--basic my account info section start -->
<h1><a name="myGroup">My Groups</a></h1><br/>
<div id="msgMyAccount"></div>

<div style="width:500px;border-bottom:thin solid #9D9D9D;padding-bottom:20px;height:140px;overflow:scroll;overflow-y:scroll;overflow-x:hidden;">
	<div style="width:500px;padding-bottom:5px;">
    <div class="divRow" style="width:250px;">
    	<div style="width:250px;">
            <div class="divRow" style="width:105px;height:75px;background:#D6D6D6;border: 1px solid #333;" align="center">
                          <img src="public/images/addImage.jpg" onclick="javascript:location.href='index.php?process=group_opt_page'" style="cursor:pointer;cursor:hand;" width="105px" height="75px" alt="Easyshare Group" />
             </div>
             <div class="divRow" style="width:125px;padding:0px 5px 0px 10px;">
                 <h5><a href="index.php?process=group_opt_page">Create a New Group</a></h5>
                 <p style="font-size:11px;"> set-up a new group</p>
             </div>
          </div>
    </div>
    
    <?php
	$totalMemers = count($groupMemInfo);
	if($totalMemers!=0)
	{
		//foreach($groupMemInfo as $key=>$value)
		for($key=0;$key<$totalMemers;$key++)
		{
			$memId = $groupMemInfo[$key]['id'];
			$groupId = $groupMemInfo[$key]['group_id'];
			$groupInfo=$this->model->getResult("group_info",""," WHERE id='$groupId'  AND deleted='n' ","");
			$title=$groupInfo[0]['title'];
			$listId=$groupInfo[0]['list_id'];
			$groupStatus=$groupInfo[0]['status'];
			$active=$groupInfo[0]['active'];
			$resume=$groupInfo[0]['resume'];
			if(empty($title))
			{
				$title = 'No Group Title';
			}
			$prev_Admin_id=$groupInfo[0]['prev_Admin_id'];
			$prev_FC_id=$groupInfo[0]['prev_FC_id'];
			$prev_MC_id=$groupInfo[0]['prev_MC_id'];
			
			$listPhoto=$this->model->getResult("listing_photos",""," WHERE type_id='$groupId' AND type='G' AND active='y' ","");
			if((count($listPhoto)==0) && $listId!=0)
			{
				$listingDetail=$this->model->getResult("listing_items"," name "," WHERE id='$listId' ",""); 
				$title=$listingDetail[0]['name'];
				$fileName='mainImage_' . $listId;
				$listPhoto=$this->model->getResult("listing_photos",""," WHERE file_name='$fileName' AND type_id='$listId' AND type='L' AND active='y' ","");
			}
			
			if(count($listPhoto)!=0) 
			{
				$photoId=$listPhoto[0]['id'];
				//$photoName=$listPhoto[0]['file_name'];
				$photoSrc=$listPhoto[0]['file_path'];
				$photoWidth=$listPhoto[0]['image_width'];
				$photoHeight=$listPhoto[0]['image_height'];
				list($x,$y)=$this->model->imageSize('70','100',$photoWidth,$photoHeight);
				$photoHeight=$y;
				$photoWidth=$x;
				$topMargin = ((75 - (float)$photoHeight) / 2);
			}
			else 
			{
				$photoSrc='public/images/noImage.jpg';
				$photoHeight=70;
				$photoWidth=100;
			}
			
       		if(($key%2)!=0)
			{	?><div style="width:500px;padding-bottom:5px;"><?php }
			?>
            <div class="divRow" style="width:250px;">
                <div style="width:250px;">
                    <div class="divRow" style="background:#D6D6D6;width:105px; height:75px;border: solid thin #333;" align="center">
                    		<?php if($active=='y') { ?>
                                   <img src="<?php echo $photoSrc; ?>"  onclick="javascript:location.href='index.php?process=my_group&param1=<?php echo $groupId; ?>'" style="cursor:pointer;cursor:hand;margin-top:<?php echo $topMargin; ?>px;" width="<?php echo $photoWidth; ?>" height="<?php echo $photoHeight; ?>" alt="Easyshare Group"/>
                             <?php }else if($active=='n') { ?>
                             		<img src="<?php echo $photoSrc; ?>"  onclick="javascript:alert('This group has not been activated yet, please speak to your group administrator to find out why?')" style="cursor:pointer;cursor:hand;margin-top:<?php echo $topMargin; ?>px;opacity:0.5" width="<?php echo $photoWidth; ?>" height="<?php echo $photoHeight; ?>" alt="Easyshare Group"/>
                             <?php } ?>
                     </div>
                     <div class="divRow" style="width:125px;padding:0px 5px 0px 10px;">
                     	<?php if($active=='y') { ?>
                         <h5><a href="index.php?process=my_group&param1=<?php echo $groupId; ?>"><?php echo $title; ?></a></h5>
                          <?php }else if($active=='n') { ?>
                           <h5><a href="javascript:alert('This group has not been activated yet, please speak to your group administrator to find out why?')"><?php echo $title; ?></a></h5>
                          <?php } ?>
                         <div class="myACLinks">
                            <?php if($prev_Admin_id==$memId) { 
											 if($active=='y') { ?>
                           						 <a href="index.php?process=groupAdmin&param1=<?php echo $groupId; ?>">Manage Group</a><br/>
                                                 <?php if($resume=='y') { ?>
                                                 <span id="endResumeGroup_<?php echo $groupId; ?>"><a href="javascript:void(0);" onclick="javascript:resumeGroup('<?php echo $groupId; ?>')">Resume Group</a></span><br/>
                                                 <?php } else { ?>
                                                 <span id="endResumeGroup_<?php echo $groupId; ?>"><a href="javascript:void(0);" onclick="javascript:endGroup('<?php echo $groupId; ?>')">End Group</a></span><br/>
                                                 <?php } ?>
                            				<?php } else  if($active=='n') { 
														if($groupStatus=='F') { ?>
                                            				<a href="javascript:alert('Group Activation in process, Please speak to Easyshare Administrator for more information.')">Processing Group Activation</a><br/>
                                                         <?php } else { ?>
                                                         	<a href="index.php?process=groupSetup&param1=<?php echo $groupId; ?>">Edit / Continue </a><br/>
                                                         <?php } ?>
                                              <?php } ?>
                            <?php }	if($prev_FC_id==$memId && $active=='y')		{ ?>
                            <a href="index.php?process=group_fc_page&param1=<?php echo $groupId; ?>">Group Finance</a><br/>
							<?php }	if($prev_MC_id==$memId && $active=='y')		{ ?>
                            <a href="index.php?process=group_mc_page&param1=<?php echo $groupId; ?>">Group Maintenance</a>
							<?php }	 ?>
                            </div>
                     </div>
                  </div>
            </div>
            <?php 
			if(($key%2)==0)
			{	?> <div class="clear"></div></div><?php }
			if($key==($totalMemers-1) && ($key%2)!=0)
			{ ?><div class="clear"></div></div><?php }
		}
	}
	else
	{ ?><div class="clear"></div></div><?php }
	?>
</div>
<!-- basic my account info section end -->

<!--my listing info section start -->
<h1><a name="myListing">My Listings</a></h1><br/>
<div style="width:500px;border-bottom:thin solid #9D9D9D;">
  <div id="myListing" class="myListing">
    <ul></ul>
  </div>
</div>
<!--my listing account info section end -->

<!--my Tagged listing info section start -->
<h1><a name="myTagListing">My Tagged Listings</a></h1><br/>
<div style="width:500px;border-bottom:thin solid #9D9D9D;">
  <div id="myTaggedListing" class="myTaggedListing">
    <ul></ul>
  </div>
</div>
<div style="margin:0px 45px 0px 0px;" id="smallText" align="right"><a href="javascript:void(0);" onclick="javascript:unTagAllListing();">Un-Tag all Listings </a></div>
<!--my Tagged listing info section end -->

<!--Account Details section start -->
<h1><a name="acDetails">Account Details</a></h1><br/>
<div style="width:500px;border-bottom:1px solid #9D9D9D;padding-bottom:20px;">
<div><?php echo $message; ?></div>
 <div style="width:500px;">
     <div class="divRow" style="width:90px;height:100px;border:1px solid #D6D6D6;margin-right:8px;" align="center">
    	<a href="<?php echo dirname($_SERVER['PHP_SELF'])."/".$profilePhotoSrc; ?>" target="_blank">
     	<img src="<?php echo $profilePhotoSrc; ?>" width="<?php echo $profilePhotoWidth; ?>" height="<?php echo $profilePhotoHeight; ?>" style="margin-top:<?php echo $profileTopmargin; ?>px;" alt="Easyshare User" border="0" />
        </a>
     </div>
     <div class="divRow" style="width:400px;">
     		<div class="divRow" style="width:200px;"><?php echo $usename; ?></div>
            <div class="divRow" style="width:200px;"><strong>Password:</strong>******&nbsp;<span id="smallText"><a href="javascript:void(0);" onclick="javascript:changePassword();">Change Password</a></span></div>
            <div class="divRow" style="width:200px;"><strong>Name:</strong><?php echo $name; ?></div>
            <div class="divRow" style="width:200px;"><strong>Email:</strong><?php echo $email; ?></div>
            <div class="divRow" style="width:70px;"><strong>Address:</strong></div><div class="divRow" style="width:330px;"><?php echo $address; ?></div>
            <div class="divRow" style="width:200px;"><strong>Telephone:</strong><?php echo $phone; ?></div>
            <div class="divRow" style="width:200px;"><strong>D.O.B.:</strong><?php echo date('d.m.y',$birthDate) ?></div>
     </div>
     <div class="clear"></div>
</div>

</div>
<div style="margin:0px 80px 0px 0px;" id="smallText" align="right"><a href="javascript:void(0);" onclick="javascript:editProfile(<?php echo $curr_user_id; ?>);">edit profile</a></div>
<!--Account Details info section end -->

</div>
</div>