// JavaScript Document
$(document).ready(function() { 
	
	$('#formLogin').submit( function() {
	
		if(($('#loginEmail').val().length!=0) && ($('#password').val().length!=0))
		{	
			var remember = 0;
			if($('#remember').is(':checked'))
			{
				remember = 1;
			}
			var currentUrl = $('#currentUrl').val();
			
			 $.post("index.php?process=login_process",{ email  :$('#loginEmail').val(), password : $('#password').val(), remember : remember, currentUrl : currentUrl,rand:Math.random() } ,function(msg)
			{
			  if(msg == 1)
			  {	
				  $("#loginMsg").html('Your login details are wrong...').addClass('error');
			  }
			  else if(msg == 2)
			  {
				  $("#loginMsg").html(msg).addClass('error');
			  }
			  else
			  {	
				  $("#loginMsg").fadeTo(200,0.1,function()  //start fading the messagebox
					{ 
					  //add message and change the class of the box and start fading
					  $(this).html('Logging in.....').addClass('required').fadeTo(900,1,
					  function()
					  { 
					  	var currentUrl = jQuery(location).attr('href')
						var currentUrlArr = currentUrl.split("process=");
						var thisUrl = jQuery.trim(currentUrlArr[1]);
						if(thisUrl.length == 0 || thisUrl == 'signin') { thisUrl = 'myAccount'; }
						document.location='index.php?process='+thisUrl;
					  });
					  
					});
			  }
			});
			return false;
		}else{
			if($('#loginEmail').val().length!=0)
			{
				$("#loginMsg").html('Please Enter Email Address.').addClass('error');
				$("#loginEmail").addClass("error_focus");
			}else if($('#password').val().length!=0){
				$("#loginMsg").html('Please Enter Password').addClass('error');
				$("#password").addClass("error_focus");
			}else{
				$("#loginMsg").html('Please Enter Email Address and Password').addClass('error');
				$("#loginEmail").addClass("error_focus");
				$("#password").addClass("error_focus");
			}
			return false;
		}
		return false;
	});
	
	/*Forget Password*/
	 $('#forgetPwd').submit( function() {
		 if($('#reset_email').val().length!=0)
		{
			 $.post("index.php?process=forgot_pwd",{ reset_email:$('#reset_email').val(),rand:Math.random() } ,function(msg)
			{
			  if(msg==0)	//if incorrect email  ID
			  {
				$("#resetEmailmsg").html('Wrong Email Address!!').addClass('error');
				$("#reset_email").addClass("error_focus");
			  }
			  else if(msg==1)	//if password is sent
			  {
				$("#reset_email").removeClass("error_focus");
				$("#resetEmailmsg").html('Your Password is sent to your email Address.').addClass('required');
			  }
			  else
			  {
				  $("#resetEmailmsg").html(msg).addClass('error');
				  $("#reset_email").addClass("error_focus");
			  }
			});
			return false;
		}else{
			$("#resetEmailmsg").html('Please Enter Email Address.').addClass('error');
			$("#reset_email").addClass("error_focus");
			return false;
		}
	});
});
/*forget Password */
$(document).ready(function() { 
/*Change Password */
	var currentFlag=0;
	var newFlag=0;
	var confirmFlag=0;
$('#current').bind("keyup focus blur", function() {		//Check for Current Password
	
	if($('#current').val().length!=0)
	{
		$("#msgCurrent").html('').removeClass('required');
		$("#mandCurrent").removeClass('error');
		$("#mand").removeClass('error');
		$("#current").removeClass("error_focus_required");
		//check the password exists or not from ajax
		$.post("index.php?process=validate_register",{ current_pwd:$('#current').val() } ,function(msg)
		{
		  if(msg != 0)	//if current password is correct
		  {
			$("#msgCurrent").html('').removeClass('error');
			$("#current").removeClass("error_focus");
			currentFlag=1;
		  }
		  else 
		  {
			$("#msgCurrent").html('Wrong Current Password!!').addClass('error');
			$("#current").addClass("error_focus");
			currentFlag=0;
		  }
		});
	}
	else
	{	
		$("#msgCurrent").html('Mandatory').addClass('required');
		$("#mandCurrent").addClass('error');
		$("#mand").addClass('error');
		$("#current").addClass("error_focus_required");
		currentFlag=0;
	}
	return currentFlag;
});
	   
$('#new').bind("keyup focus blur", function() {		//Password Validation
			
				if($('#new').val().length!=0)
				{
					$("#new").passStrength({
						userid:	"#current"
						});
					$("#new").passStrength({
						shortPass: 		"shortPass",
						badPass:		"badPass",
						goodPass:		"goodPass",
						strongPass:		"strongPass",
						baseStyle:		"testresult",
						userid:			"#current",
						messageloc:		1
					});
					$("#mandNew").removeClass('error');
					$("#mand").removeClass('error');
					$("#msgNew").html('').removeClass('required');
					$("#new").removeClass("error_focus_required");
					$("#msgNew").html('').removeClass('error');
					$("#new").removeClass("error_focus");
					 newFlag=1
				}
				else
				{
					$("#msgNew").html('').removeClass('error');
					$("#new").removeClass("error_focus");
					$("#msgNew").html('Mandatory').addClass('required');
					$("#new").addClass("error_focus_required");
					$("#mandNew").addClass('error');
					$("#mand").addClass('error');
					newFlag=0
				}
			return newFlag;
	   });
	   $('#confirm').bind("keyup focus blur", function() {		//Password Confirmation
	   		if($('#confirm').val().length!=0)
			{
				$("#mandConfirm").removeClass('error');
				$("#mand").removeClass('error');
				$("#msgConfirm").html('').removeClass('required');
				$("#confirm").removeClass("error_focus_required");
				
				if($('#new').val()==$('#confirm').val())
				{
					$("#msgConfirm").html('').removeClass('required');
					$("#confirm").removeClass("error_focus_required");
					$("#msgConfirm").html('').removeClass('error');
					$("#confirm").removeClass("error_focus");
					confirmFlag=1
				}
				else
				{
					$("#msgConfirm").html('Password Does not Match').addClass('error');
					$("#confirm").addClass("error_focus");
					confirmFlag=0;
				}
			}
			else
			{
				$("#msgConfirm").html('').removeClass('error');
				$("#confirm").removeClass("error_focus");
				$("#msgConfirm").html('Plese Enter New Password and Confirm').addClass('required');
				$("#confirm").addClass("error_focus_required");
				$("#mandConfirm").addClass('error');
				$("#mand").addClass('error');
				confirmFlag=0;
			}
			return confirmFlag;
	   });
	   
	   $('#change_pwd').submit( function() {
		   $('#current').keyup();
		   $('#new').keyup();
		   $('#confirm').keyup();
		   
		   if(currentFlag==1 &&newFlag==1 && confirmFlag==1)
		   {
			   return true; 
		   }
		   else
		   {
			   $('#submitmsg').addClass('required');
			   return false; 
		   }
			return false; 
	   });
});
/*Change Password */