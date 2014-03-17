<div class='mainInfo'>
<div id="subContainer">
	<h1>Register Now and Start Sharing Today</h1>
	<div>
    <span id="submitmsg" style="float:left">Please enter the users information below.</span>&nbsp;&nbsp;&nbsp;&nbsp;<span style="float:right">
    <span id="mand">*</span>&nbsp;Fields are Mandatory.</span>
    </div>
	
	<div id="infoMessage" class="error"><?php if(isset($message)){echo $message;}?></div>
    <?php if($photoSrc=='public/images/users/user0th.jpg') { ?>
    <div id="fileupload" style="margin:50px 0 0 385px;position:absolute;">
    <form id="imageInfo" action="upload.php" method="POST" enctype="multipart/form-data">
        <div class="files" id="files" align="center"></div>
        <div class="fileupload-buttonbar">
         <label class="fileinput-button" style="overflow:hidden;">
                 <img src="<?php echo $photoSrc; ?>" id="preview" width="110px" height="110px" alt="Easyshare Group" class="ui-button-text" style="background-color:#FFFFFF;" border="0"/>
                <input type="file" name="files[]" id="userImg" class="user_file_input_hidden" style="font-size:17px;height:108px;">
           </label>
           </div>
    </form>
</div>
<?php } else {	?>
	<div style="margin:50px 0 0 385px;position:absolute;background-color:#D6D6D6;border:1px solid #2D2D2D;width:110px;height:110px;" align="center">
		<a href="<?php echo dirname($_SERVER['PHP_SELF'])."/".$photoSrc; ?>" target="_blank"><img src="<?php echo $photoSrc; ?>" id="preview" width="<?php echo $photoWidth; ?>px" height="<?php echo $photoHeight; ?>px" style="margin-top:<?php echo $topMargin; ?>px" border="0" alt="Easyshare Group"/></a>
    </div>
    <div style="margin:170px 0 0 385px;position:absolute" align="left"><a href="javascript:void();" class="deleteGroupImage" id="<?php echo $photoId; ?>">Delete</a></div>
<?php } ?>
    
    
    <script id="template-upload" type="text/x-jquery-tmpl">
    <div class="template-upload{{if error}} ui-state-error{{/if}}" align="center">
		<div class="imgPreview"><div class="preview"></div></div>
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
    <div class="template-download{{if error}} ui-state-error{{/if}}">
        {{if error}}<div><span class="name">${name}</span><span class="size">${sizef}</span></div>
			<div class="error" colspan="2">Error:
                {{if error === 1}}File exceeds upload_max_filesize (php.ini directive)
                {{else error === 2}}File exceeds MAX_FILE_SIZE (HTML form directive)
                {{else error === 3}}File was only partially uploaded
                {{else error === 4}}No File was uploaded
                {{else error === 5}}Missing a temporary folder
                {{else error === 6}}Failed to write file to disk
                {{else error === 7}}File upload stopped by extension
                {{else error === 'maxFileSize'}}File is too big
                {{else error === 'minFileSize'}}File is too small
                {{else error === 'acceptFileTypes'}}Filetype not allowed
                {{else error === 'maxNumberOfFiles'}}Max number of files exceeded
                {{else error === 'uploadedBytes'}}Uploaded bytes exceed file size
                {{else error === 'emptyResult'}}Empty file upload result
                {{else}}${error}
                {{/if}}
            </div>
        {{else}}<div class="preview">
                {{if thumbnail_url}}
					<div class="imgPreview">
                    	<a href="${url}" target="_blank"><img src="${thumbnail_url}" style="margin-top:${top_margin}px"></a>
					</div>
					<input type="hidden" name="imageName" id="imageName" value="${name}"/>
					<input type="hidden" name="imagesize" id="imagesize" value="${size}"/>
					<div align="left">
						<div class="delete"><button data-type="${delete_type}" data-url="${delete_url}" id="cancelImg">Delete</button></div>
					</div>
                {{/if}}
        {{/if}}
    </div>
</script>
<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>-->
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>
<script src="public/scripts/jquery.iframe-transport.js"></script>
<script src="public/scripts/jquery.fileupload.js"></script>
<script src="public/scripts/jquery.fileupload-ui.js"></script>
<script src="public/scripts/application.js"></script>
	<form name="create_user_form" id="create_user_form" method="post" action="index.php?process=create_user">
    <input type="hidden" name="userId" id="userId" value="<?php echo $userId; ?>" />
    <input type="hidden" name="selImageName" id="selImageName" value=""/>
    <input type="hidden" name="selImageSize" id="selImageSize" value=""/>
        <fieldset style="color:#FFF">
            <legend>Account Details:</legend>
           <!-- <div class="divRow" style="width:400px;padding-left:10px;">-->
            <div style="width:400px;padding-left:10px;">
            <?php if(!empty($username)) { ?>
            <p><span id="mandUser">*</span>&nbsp;Username:<br /><input type="text" name="txtuser" value="<?php echo $username; ?>" style="color:#2C2C2C" readonly="readonly" />
            	&nbsp;&nbsp;<span id="usermsg"></span></p>
                <?php } else { ?>
                <p><span id="mandUser">*</span>&nbsp;Username:<br /><input type="text" id="user" name="txtuser" required="required" maxlength="50" />
            	&nbsp;&nbsp;<span id="usermsg"></span></p>
                <?php } if(!empty($username)) { ?>
            <p><span id="mandPwd">*</span>&nbsp;Password:<br /><input type="password" name="pwd" value="******" style="color:#2C2C2C"  readonly="readonly"/>
            	&nbsp;&nbsp;<span id="pwdmsg"></span></p>
                <?php } else { ?>
            <p><span id="mandPwd">*</span>&nbsp;Password:<br /><input type="password" id="pwd" name="pwd" required="required" maxlength="50"/>
            	&nbsp;&nbsp;<span id="pwdmsg"></span></p>
            <p><span id="mandPwdconfirm">*</span>&nbsp;Confirm Password:<br />
            <input type="password" id="pwdconfirm" name="pwdconfirm" required="required" maxlength="50"/>
            	&nbsp;&nbsp;<span id="pwdconfirmmsg"></span></p>
               <?php } if(!empty($email)) { ?>
               <p><span id="mandEmail">*</span>&nbsp;Email:<br /><input type="text"  name="txtemail" value="<?php echo $email; ?>" style="color:#2C2C2C"  readonly="readonly"/>
            	&nbsp;&nbsp;<span id="emailmsg"></span></p>
                <?php } else { ?>
            <p><span id="mandEmail">*</span>&nbsp;Email:<br /><input type="text" id="email" name="txtemail" required="required" maxlength="50"/>
            	&nbsp;&nbsp;<span id="emailmsg"></span></p>
             <p><span id="mandEmailconfirm">*</span>&nbsp;Confirm Email:<br /><input type="text" id="emailconfirm" required="required" maxlength="50"/>
            	&nbsp;&nbsp;<span id="emailconfirmmsg"></span></p>
                <?php }  if(!empty($birthDate)) { ?>
                <p><span id="mandEmailconfirm">*</span>&nbsp;Date of Birth<br />
                 <input name="birthDate" value="<?php echo $birthDate ?>" size="12" readonly="readonly"/>
            	&nbsp;&nbsp;<span id="emailconfirmmsg"></span></p>
                <?php } else { ?>
                <p><span id="mandEmailconfirm">*</span>&nbsp;Date of Birth<br />
                 <input name="birthDate" id="birthDate" class="birthDate" value="<?php echo date('Y-m-d'); ?>" size="12" readonly="readonly"/>
            	&nbsp;&nbsp;<span id="emailconfirmmsg"></span></p>
                <?php } ?>
                </div>
                 <!--<div class="clear"></div>-->
                 
         </fieldset>
         <fieldset>
         	<legend>Profile Details:</legend>
           <p><span id="mandFirstnm">*</span>&nbsp;First Name:<br /><input type="text" name="txtfirstname" id="firstname" value="<?php echo $firstName; ?>" maxlength="50"/>
           &nbsp;&nbsp;<span id="firstnamemsg"></span></p>
           <p><span id="mandLastnm">*</span>&nbsp;Last Name:<br /><input type="text" name="txtlastname" id="lastname" value="<?php echo $lastName; ?>" maxlength="50"/>
           &nbsp;&nbsp;<span id="lastnamemsg"></span></p>
           <p>Address:<br /><textarea name="txtaddress" cols="21" rows="5"><?php echo $address; ?></textarea></p>
            
            <p>Town/City:<br /><input type="text" name="txttown" value="<?php echo $town; ?>" maxlength="50"/></p>
            
           <p>County:<br /><input type="text" name="txtcounty" value="<?php echo $county; ?>" maxlength="50"/></p>
           
           <p><span id="mandPostcode">*</span>&nbsp;Postcode:<br /><input type="text" name="txtpostcode" id="postcode" value="<?php echo $postCode; ?>" maxlength="7"/>
           &nbsp;&nbsp;<span id="postcodemsg"></span></p>
           
           <p><span>*</span>&nbsp;Country:<br />
           <select name="selectCountry" id="country">
           <option value="0">Select Country</option>
           <?php
		   for($i=0;$i<count($countryList);$i++)
		   {
			   if($countryList[$i]['id']==$countryId)	{ 
			   		?><option value="<?php echo $countryList[$i]['id'] ?>" selected="selected"><?php echo $countryList[$i]['printable_name'] ?></option><?php
			   }
			   else
			   {
				   	?><option value="<?php echo $countryList[$i]['id'] ?>"><?php echo $countryList[$i]['printable_name'] ?></option><?php
			   }
		   }
		   ?>
           </select>&nbsp;&nbsp;<span id="countrymsg"></span>
            </p>
            
           <p><span id="mandPhone">*</span>&nbsp;Phone Number:<br /><input type="text" name="txtphone" id="phone" value="<?php echo $phone; ?>" maxlength="12"/>
           &nbsp;&nbsp;<span id="phonemsg"></span></p>
         </fieldset>
    
	<p><input type="checkbox" name="terms" id="terms" value="1"/>&nbsp;&nbsp;Terms and Conditions</p>
    
  	<p style="text-align:right;"><input type="image" name="CreateUser" id="CreateUser" value="Save"/></p>
  </form>
</div>
</div>