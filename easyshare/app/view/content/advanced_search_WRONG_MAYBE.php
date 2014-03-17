<div class='mainInfo'>
<div id="subContainer">
	<h1>Advanced search</h1>
	<div>
    <span id="submitmsg" style="float:left">Please enter the users information below.</span>&nbsp;&nbsp;&nbsp;&nbsp;<span style="float:right">
    <span id="mand">*</span>&nbsp;Fields are Mandatory.</span>
    </div>
	
	<div id="infoMessage"><?php if(isset($message)){echo $message;}?></div>
    
	<form name="create_user" id="create_user" method="post" action="index.php?process=create_user" enctype="multipart/form-data">
    <input type="hidden" name="userId" id="userId" value="<?php echo $userId; ?>" />
        <fieldset>
            <legend>Account Details:</legend>
            <div class="divRow" style="width:400px;padding-left:10px;">
            <?php if(!empty($username)) { ?>
            <!--<div class="divRow" style="width:350px;">-->
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
                <div class="divRow" style="background:#D6D6D6;border: solid thin #333;width:80px; height:95px;padding:55px;border: solid thin #FFF;" align="center">
                        <img src="<?php echo $photoSrc; ?>" alt="Easyshare" width="<?php echo $photoWidth; ?>" height="<?php echo $photoHeight; ?>" class="file_input_img" id="userImage" />
                        <p><input type="file" id="userImg" name="userImg" style="height:15px;font-size:7px;margin-left:-35px;" value="" /></p>
                </div>
                 <div class="clear"></div>
                 
         </fieldset>
         <fieldset>
         	<legend>Profile Details:</legend>
           <p><span id="mandFirstnm">*</span>&nbsp;First Name:<br /><input type="text" name="txtfirstname" id="firstname" value="<?php echo $firstName; ?>" maxlength="50"/>
           &nbsp;&nbsp;<span id="firstnamemsg"></span></p>
           <p><span id="mandLastnm">*</span>&nbsp;Last Name:<br /><input type="text" name="txtlastname" id="lastname" value="<?php echo $lastName; ?>" maxlength="50"/>
           &nbsp;&nbsp;<span id="lastnamemsg"></span></p>
           <p>Address:<br /><textarea name="txtaddress" cols="5" rows="5"><?php echo $address; ?></textarea></p>
           <p><span>*</span>&nbsp;Country:<br />
           <select name="selectCountry" id="country" style="background:url(public/images/selectbar.gif) no-repeat;width:228px;">
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
           <p>County:<br /><input type="text" name="txtcounty" value="<?php echo $county; ?>" maxlength="50"/></p>
           <p>Town:<br /><input type="text" name="txttown" value="<?php echo $town; ?>" maxlength="50"/></p>
           <p><span id="mandPostcode">*</span>&nbsp;Post Code:<br /><input type="text" name="txtpostcode" id="postcode" value="<?php echo $postCode; ?>" maxlength="7"/>
           &nbsp;&nbsp;<span id="postcodemsg"></span></p>
           <p><span id="mandPhone">*</span>&nbsp;Phone:<br /><input type="text" name="txtphone" id="phone" value="<?php echo $phone; ?>" maxlength="12"/>
           &nbsp;&nbsp;<span id="phonemsg"></span></p>
         </fieldset>
    
	<p><input type="checkbox" name="terms" id="terms" value="1"/>&nbsp;&nbsp;Terms and Conditions</p>
    
  	<p style="text-align:right;"><input type="submit" name="CreateUser" id="CreateUser" value="Save"/></p>
  </form>
</div>
</div>