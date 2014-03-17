<div class='mainInfo'>
<div id="subContainer">
<h1>Change Password</h1>
<form name="change_pwd" id="change_pwd" method="post" action="index.php?process=change_password">
<span class="error"><?php echo $message; ?></span>
<p><span id="submitmsg">Plese fill up the form</span></p>
<p> <span id="mand">*</span>&nbsp;Fields are Mandatory.</span></p>
<p><span id="mandCurrent">*</span>&nbsp;Current Password:<br /><input type="password" name="current" id="current" required="required"/>
	&nbsp;&nbsp;<span id="msgCurrent"></span></p>
<p><span id="mandNew">*</span>&nbsp;New Password:<br /><input type="password" name="new" id="new" required="required"/>
	&nbsp;&nbsp;<span id="msgNew"></span></p>
<p><span id="mandConfirm">*</span>&nbsp;Confirm New Password:<br /><input type="password" name="confirm" id="confirm" required="required"/>
	&nbsp;&nbsp;<span id="msgConfirm"></span></p><br/>
<p><input type="submit" name="ChangePassword" value="Change Password"/></p>
</form>
</div>
</div>