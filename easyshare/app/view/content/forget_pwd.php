<h1>Forgot Password</h1>
<p>Please enter your email address so we can send you an email to reset your password.</p>

<span id="error"><?php if(isset($message)){echo $message;}?></span>
<form name="forgetPwd" id="forgetPwd">
<p>Email Address:<br />
<input type="text" name="reset_email" id="reset_email" value=""/>
	&nbsp;&nbsp;<span id="resetEmailmsg"></span></p>
<p><input type="submit" name="Submit" value="Submit"/></p>
</form>