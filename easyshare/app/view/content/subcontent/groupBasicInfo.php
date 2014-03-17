<div id="smallText" style="width:155px;height:16px;">(click on image to change picture)</div>
<div style="width:650px;border-bottom:thin solid #FFF;padding-bottom:30px;padding-top:20px;">

<a name="basicInfo"></a>
<!--group Image start-->
<div style="width:150px;">
<?php  //if($photoSrc=='public/images/noImage.jpg') { ?>
    <div id="fileupload" style="">
    <form id="imageInfo" action="uploadGroupImage.php" method="POST" enctype="multipart/form-data">
        <div class="files" align="center" id="groupImageFiles"></div>
        <!--<div class="fileupload-buttonbar">-->
         <div class="fileinput-button" id="inputButton" align="center" style="border:1px solid #333;">
                 <img src="<?php echo $photoSrc; ?>" id="preview" height="<?php echo $photoHeight; ?>" width="<?php echo $photoWidth; ?>" alt="Indulgeon Group" class="ui-button-text" style="background-color:#000;margin-top:<?php echo $topMargin; ?>px" border="0"/>
                <input type="file" name="files[]" id="userImg" class="user_file_input_hidden" style="font-size:17px;height:108px;">
           </div>
          <!-- </div>-->
    </form>
</div>
<?php //}	?>
  
  <div id="imageError" style="width:180px;height:16px;"></div> 
    
    <script id="template-upload" type="text/x-jquery-tmpl">
    <div class="template-upload{{if error}} ui-state-error{{/if}}" align="center">
		<div class="preview"></div>
        {{if error}}<div class="error" colspan="2">Error:
                {{if error === 'maxFileSize'}}File is too big
                {{else error === 'minFileSize'}}File is too small
                {{else error === 'acceptFileTypes'}}Filetype not allowed
                {{else error === 'maxNumberOfFiles'}}Max number of files exceeded
                {{else}}${error}
                {{/if}}
            </div>
        {{else}}<div class="progress"><div></div></div><div class="start"><button>Start</button></div>
        {{/if}}<div align="left"><div class="cancel"><button id="cancelImg">Cancel</button></div></div>
    </div>
</script>
<script id="template-download" type="text/x-jquery-tmpl">
    <div class="template-download{{if error}} ui-state-error{{/if}}" id="errorTemplate">
        {{if error}}
			<div>
				{{if error === 'maxFileSizeNoImg' || error === 'minFileSizeNoImg' || error === 'acceptFileTypesNoImg'}}
					<img src="public/images/noImage.jpg" width="110" height="80">
				{{/if}}
			</div>
			<div class="error" style="font-size:10px;">Error:
                {{if error === 1}}File exceeds upload_max_filesize (php.ini directive)
                {{else error === 2}}File exceeds MAX_FILE_SIZE (HTML form directive)
                {{else error === 3}}File was only partially uploaded
                {{else error === 4}}No File was uploaded
                {{else error === 5}}Missing a temporary folder
                {{else error === 6}}Failed to write file to disk
                {{else error === 7}}File upload stopped by extension
                {{else error === 'maxFileSize' || error === 'maxFileSizeNoImg'}}File is too big ${sizef}
                {{else error === 'minFileSize' || error === 'minFileSizeNoImg'}}File is too small
                {{else error === 'acceptFileTypes' || error === 'acceptFileTypesNoImg'}}Filetype not allowed
                {{else error === 'maxNumberOfFiles'}}Max number of files exceeded
                {{else error === 'uploadedBytes'}}Uploaded bytes exceed file size
                {{else error === 'emptyResult'}}Empty file upload result
                {{else}}${error}
                {{/if}}
            </div>
        {{else}}<div class="preview">
                {{if thumbnail_url}}
					<div class="groupImgPreview">
                    	<img src="${thumbnail_url}" style="margin-top:${top_margin}px">
					</div>
					<input type="hidden" name="groupImgName" id="groupImgName" value="${name}"/>
					<input type="hidden" name="groupImgSize" id="groupImgSize" value="${size}"/>
					
                {{/if}}
				</div>
        {{/if}}
    </div>
</script>
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>-->
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>
<script src="public/scripts/jquery.iframe-transport.js"></script>
<script src="public/scripts/jquery.groupImgupload.js"></script>
<script src="public/scripts/jquery.groupImage-ui.js"></script>
<script src="public/scripts/application.js"></script>
</div>
<!--group Image finish-->

<!--group basic info anf member section start-->

<form name="groupBasicInfo" id="groupBasicInfo" action="index.php?process=store_group_Basic" method="post">
<input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId; ?>"/>
<input type="hidden" name="listId"  id="listId" value="<?php echo $listId; ?>"/>
<input type="hidden" name="groupAC" id="groupAC" value="<?php echo $groupAC; ?>"/>
<input type="hidden" name="groupImageName" id="groupImageName" value=""/>
<input type="hidden" name="groupImageSize" id="groupImageSize" value=""/>
<input type="hidden" name="storedImageSrc" id="storedImageSrc" value="<?php echo $photoSrc; ?>"/>
<!-- start basic info section -->
<div style="width:500px;float:right;position:relative;right: -55px;top:-95px;margin-bottom:-70px;">
        <div>Name of Group Title:&nbsp;&nbsp; <input type="text" name="groupTitle" id="groupTitle" value="<?php echo $title; ?>"/>&nbsp;&nbsp;<a href="#" id="smallText">what's this?</a></div>
        <div id="msgGroupTitle" align="right"></div>
</div>
<!-- end basic info section -->
<input type="image" name="storeGroupBasic" id="storeGroupBasic" value="Save" align="right"/>
</form>
</div>



<div style="width:650px;border-bottom:thin solid #FFF;padding-bottom:20px;">
<form name="groupMemberInfo" id="groupMemberInfo" action="index.php?process=store_group_members" method="post">
<input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId; ?>"/>
<input type="hidden" name="listId"  id="listId" value="<?php echo $listId; ?>"/>
<input type="hidden" name="groupAC" id="groupAC" value="<?php echo $groupAC; ?>"/>
<!--group members section start-->
<div style="width:650px;" id="memberList">
	<h1><a name="members">Members</a></h1>
	<span id="errorMsg"></span>
   <div><span> Members&nbsp;Including&nbsp;You&nbsp;:&nbsp;&nbsp;<?php if(count($memberList)==0) { echo 1; } else { echo count($memberList); } ?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span><a href="#" id="smallText">what's this?</a></span></div>
         <?php
		 	$totalMem=count($memberList);
			if($totalMem != 0)
			{
				$totalRecord=$totalMem + 1;
				for($i=0;$i<($totalMem+1);$i++)
				{
					if(($i%2)==0)
					{
						$bgcolor="#FFFFFF";
					}
					else
					{
						$bgcolor="#D6D6D6";
					}
					$fontColour = "#2C2C2C";
					
					$mem_user_id=$memberList[$i]['user_id'];
					$mem_active=$memberList[$i]['active'];
					$user_list=$this->model->getResult("auth_profile",""," WHERE id='$mem_user_id' ","");
					$member_name=$user_list[0]['first_name'] . $user_list[0]['last_name'] ;
					$prev_Admin_id=$group_record[0]['prev_Admin_id'];
					$remove='remove';
					$change='change';
					$changeStyle='cursor:pointer;cursor:hand;';
					
					if($memberList[$i]['id']==$prev_Admin_id){ 
						$admin_class='activeOption' ; 
						$adminCheck='checked="checked"'; 
						 $remove='notremove'; 
						 $change='notchange';
						 $changeStyle='cursor:default;';
					} else {
							$admin_class='inActiveOption';
							$adminCheck=''; 
					}
					$prev_FC_id=$group_record[0]['prev_FC_id'];
					if($memberList[$i]['id']==$prev_FC_id){ 
						$fc_class='activeOption' ; 
						$fcCheck='checked="checked"'; 
					} else {
							$fc_class='inActiveOption';
							$fcCheck=''; 
					}
					$prev_MC_id=$group_record[0]['prev_MC_id'];
					if($memberList[$i]['id']==$prev_MC_id){ 
						$mc_class='activeOption' ; 
						$mcCheck='checked="checked"'; 
					} else {
							$mc_class='inActiveOption';
							$mcCheck=''; 
					}
					if($mem_active=='n')
					{
						 $remove='notremove'; 
						 $change='notchange';
						 $changeStyle='cursor:default;';
						 $fontColour = "#333";
					}
					if($i>=$totalMem || $mem_active=='n') 
					{ 
						$name='checkbox';
						$adminCheck='disabled="disabled"';
						$fcCheck='disabled="disabled"';
						$mcCheck='disabled="disabled"';
						$admin_class='disableOption';
						$fc_class='disableOption';
						$mc_class='disableOption';
					} else { $name='radio'; }
					
					$j = $i * 35;
				?>
				<div style="width:650px;color:<?php echo $fontColour; ?>;background-color:<?php echo $bgcolor; ?>;font-size: 10px;" id="<?php echo $mem_user_id; ?>">
					<div style="width:40px;background-color:<?php echo $bgcolor; ?>;" class="divRow memberRow">
					<?php if($remove=='remove'  && $name=='radio') { ?>
						<a href="javascript:void(0);" class="removeMember" id="<?php echo $memberList[$i]['id']; ?>" >remove</a>
					<?php } else { ?>
						<span style="visibility:hidden;"><a href="javascript:void(0);" class="removeMember" id="<?php echo $memberList[$i]['id']; ?>" >remove</a></span>
					<?php }?>
					</div>
					<div style="width:185px;background-color:<?php echo $bgcolor; ?>;text-align:center;" class="divRow memberRow">
						<span id="<?php echo $memberList[$i]['id']; ?>"  style="font-size:10px;text-align:center;<?php echo $changeStyle; ?>" class="<?php echo $change; ?>">
						<?php if(empty($member_name)){ echo "Click to Add Member"; } else { echo $member_name; } ?>
						</span>
					</div>
					<div style="cursor:default;width:10px;background-color:<?php echo $bgcolor; ?>;" class="divRow memberRow"><span id="orangeText">|</span></div>
					<div style="cursor:default;width:55px;background-color:<?php echo $bgcolor; ?>;" class="divRow memberRow"><span id="smallText">Privelidges</span></div>
					<div style="width:65px;background-color:<?php echo $bgcolor; ?>;" class="divRow memberRow"><span id="smallText"><a href="#" id="smallText">what's this?</a></span></div>
					<div style="width:80px;background-color:<?php echo $bgcolor; ?>;" class="divRow memberRow">
						<span class="<?php echo $admin_class; ?>">
						<input type="<?php echo $name; ?>" value="<?php echo $memberList[$i]['id']; ?>" id="<?php echo $memberList[$i]['id']; ?>_a" class="memopt" name="adminRadio" <?php echo $adminCheck; ?>/>Administrator</span>
					</div>
					<div style="width:80px;background-color:<?php echo $bgcolor; ?>;" class="divRow memberRow">
						<span class="<?php echo $mc_class; ?>">
						<input type="<?php echo $name; ?>" value="<?php echo $memberList[$i]['id']; ?>" id="<?php echo $memberList[$i]['id']; ?>_m" class="memopt" name="mcRadio" <?php echo $mcCheck; ?>/>Maintenance</span>
					</div>
					<div style="width:80px;background-color:<?php echo $bgcolor; ?>;" class="divRow memberRow">
						<span class="<?php echo $fc_class; ?>">
						<input type="<?php echo $name; ?>" value="<?php echo $memberList[$i]['id']; ?>" id="<?php echo $memberList[$i]['id']; ?>_f" class="memopt" name="fcRadio" <?php echo $fcCheck; ?>/>Finance</span>
					</div>
				</div>
				 <div class="clear"></div>
				<?php
				}
		}
		else
		{	
			$user_list=$this->model->getResult("auth_profile",""," WHERE id='$mem_user_id' ","");
			$member_name=$user_list[0]['first_name'] . $user_list[0]['last_name'] ;
			?>
            <div style="width:650px;background: #D6D6D6;color: #2D2D2D;font-size: 10px;" id="<?php echo $mem_user_id; ?>">
                <div style="width:185px;background: #D6D6D6;color: #2D2D2D;text-align:center;" class="divRow memberRow">
                    <span id="0"  style="font-size:10px;text-align:center;cursor:default;" class="notchange">
                    <?php echo $member_name; ?>
                    </span>
                </div>
                <div style="cursor:default;width:10px;background: #D6D6D6;color: #2D2D2D;" class="divRow memberRow"><span id="orangeText">|</span></div>
                <div style="cursor:default;width:55px;background: #D6D6D6;color: #2D2D2D;;" class="divRow memberRow"><span id="smallText">Privelidges</span></div>
                <div style="width:65px;background:#D6D6D6;color:#2D2D2D;" class="divRow memberRow"><span id="smallText"><a href="#" id="smallText">what's this?</a></span></div>
                <div style="width:80px;background:#D6D6D6;color:#2D2D2D;" class="divRow memberRow">
                    <span class="activeOption">
                    <input type="radio" value="0" id="0_a" class="memopt" name="adminRadio" <?php echo $adminCheck; ?>/>Administrator</span>
                </div>
                <div style="width:80px;background:#D6D6D6;color:#2D2D2D;" class="divRow memberRow">
                    <span class="activeOption">
                    <input type="radio" value="0" id="0_m" class="memopt" name="mcRadio" <?php echo $mcCheck; ?>/>Maintenance</span>
                </div>
                <div style="width:80px;background:#D6D6D6;color:#2D2D2D;" class="divRow memberRow">
                    <span class="activeOption">
                    <input type="radio" value="0" id="0_f" class="memopt" name="fcRadio" <?php echo $fcCheck; ?>/>Finance</span>
                </div>
            </div>
            <div class="clear"></div>
        <?php
		}
		?>
</div>
<!--group members section finish-->

<?php if(count($memberList)!=0) { ?>
<input type="image" name="storeGroupMember" id="storeGroupMember" value="Save" align="right"/>
<?php } ?>
</form>
</div>
<!--group basic info anf member section finish-->
