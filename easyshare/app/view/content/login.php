<div class='mainInfo'>
	<div id="subContainer">
	<p><a href="index.php?process=create_user_page">Register</a></p>
	<h1>Login</h1>
    <div class="pageTitleBorder"></div>
	<p>Please login with your email address and password below.</p>
	
	<p><span class="error"><?php if(isset($message)){echo $message;}?></span></p>
    
      <form name="formLogin" id="formLogin" method="post" onsubmit="javascript:loginTest();">
      <input type="hidden" name="currentPage" id="currentPage" value="<?php echo $thisPage; ?>"/>
      <p>
      	<label for="email">Email:</label>
        <input type="text" name="email" id="loginEmail" value="<?php if(isset($_COOKIE['email'])){echo $_COOKIE['email'];} ?>" required= "required"/>
      </p>
      
      <p>
      	<label for="password">Password:</label>
		<input type="password" name="password" id="password" value="<?php	if(isset($_COOKIE['password'])){echo $_COOKIE['password'];} ?>" required= "required"/>
      </p>
      
      <p>
	      <label for="remember">Remember Me:</label>
			<input type="checkbox" name="remember" id="remember" />
	  </p>
      
      
      <p><input type="submit" name="Login" value="Login"  style="cursor:pointer;cursor:hand;"/>&nbsp;&nbsp;<span id="loginMsg"></span></p>
	 
	 <p><a href="index.php?process=forgot_pwd_page">Forgot Password</a></p>
      
    </form>
   
</div>
</div>
