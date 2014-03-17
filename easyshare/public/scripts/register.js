
$(document).ready(function() {  
	var userFlag=0;
	var pwdFlag=0;
	var pwdconfirmFlag=0;
	var emailFlag=0;
	var firstnmFlag=0;
	var lastnmFlag=0;
	var countyFlag=0;
	
	
	   $('#user').bind("keyup focus blur", function() {		//Username Validation
				 	var pat=/^[\sa-zA-Z0-9._-]{3,16}$/;
					
					if($('#user').val().length!=0)
					{
						if((pat.test($('#user').val()))){
							//check the username exists or not from ajax
							$.post("index.php?process=validate_register",{ user_name:$('#user').val() } ,function(msg)
							{
							  if(msg != 0)	//if correct login detail
							  {
								$("#usermsg").html('').removeClass('required');
								$("#user").removeClass("error_focus_required");
								$("#usermsg").html('Username Already Exist').addClass('error');
								$("#user").addClass("error_focus");
								userFlag=0;
							  }
							  else 
							  {
								$("#usermsg").html('').removeClass('error');
								$("#usermsg").html('').removeClass('required');
								$("#user").removeClass("error_focus");
								$("#user").removeClass("error_focus_required");
								userFlag=1;
							  }
							});
							
						}else{
							$("#usermsg").removeClass('required');
							$("#mandUser").removeClass('error');
							$("#mand").removeClass('error');
							$("#user").removeClass("error_focus_required");
							$("#usermsg").html('Invalid Username').addClass('error');	
							$("#user").addClass("error_focus");
							userFlag=0;
						}
					}
					else
					{	
						$("#usermsg").html('Username Mandatory').addClass('required');
						$("#mandUser").addClass('error');
						$("#mand").addClass('error');
						$("#user").addClass("error_focus_required");
						userFlag=0;
					}
					return userFlag;
	   });
	   
	    $('#pwd').bind("keyup focus blur", function() {		//Password Validation
			
				if($('#pwd').val().length!=0)
				{
					$("#pwd").passStrength({
						userid:	"#user"
						});
					$("#pwd").passStrength({
						shortPass: 		"shortPass",
						badPass:		"badPass",
						goodPass:		"goodPass",
						strongPass:		"strongPass",
						baseStyle:		"testresult",
						userid:			"#user",
						messageloc:		1
					});
					$("#mandPwd").removeClass('error');
					$("#mand").removeClass('error');
					$("#pwdmsg").html('').removeClass('required');
					$("#pwd").removeClass("error_focus_required");
					$("#pwdmsg").html('').removeClass('error');
					$("#pwd").removeClass("error_focus");
					 pwdFlag=1
				}
				else
				{
					$("#pwdmsg").html('').removeClass('error');
					$("#pwd").removeClass("error_focus");
					$("#pwdmsg").html('Password Mandatory').addClass('required');
					$("#pwd").addClass("error_focus_required");
					$("#mandPwd").addClass('error');
					$("#mand").addClass('error');
					pwdFlag=0
				}
			return pwdFlag;
	   });
	   $('#pwdconfirm').bind("keyup focus blur", function() {		//Password Confirmation
	   		if($('#pwdconfirm').val().length!=0)
			{
				if($('#pwd').val()==$('#pwdconfirm').val())
				{
					$("#mandPwdconfirm").removeClass('error');
					$("#mand").removeClass('error');
					$("#pwdconfirmmsg").html('').removeClass('required');
					$("#pwdconfirm").removeClass("error_focus_required");
					$("#pwdconfirmmsg").html('').removeClass('error');
					$("#pwdconfirm").removeClass("error_focus");
					pwdconfirmFlag=1
				}
				else
				{
					$("#pwdconfirmmsg").html('').removeClass('required');
					$("#pwdconfirm").removeClass("error_focus_required");
					$("#pwdconfirmmsg").html('Password Does not Match').addClass('error');
					$("#pwdconfirm").addClass("error_focus");
					pwdconfirmFlag=0;
				}
			}
			else
			{
				$("#pwdconfirmmsg").html('').removeClass('error');
				$("#pwdconfirm").removeClass("error_focus");
				$("#pwdconfirmmsg").html('Plese Enter Password and Confirm').addClass('required');
				$("#pwdconfirm").addClass("error_focus_required");
				$("#mandPwdconfirm").addClass('error');
				$("#mand").addClass('error');
				pwdconfirmFlag=0;
			}
			return pwdconfirmFlag;
	   });
	   
	   $('#email').bind("keyup focus blur", function() {		//Email Validation
				var pat=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
				if($('#email').val().length!=0){
					
					$("#mandEmail").removeClass('error');
					$("#mand").removeClass('error');
					
					if((pat.test($('#email').val()))){
						$.post("index.php?process=validate_register",{ email_id:$('#email').val() } ,function(msg)
						{
						  if(msg != 0)	//if correct login detail
						  {
							$("#emailmsg").html('').removeClass('required');
							$("#email").removeClass("error_focus_required");
							$("#emailmsg").html('Email Already Exist').addClass('error');
							$("#email").addClass("error_focus");
							emailFlag=0;
						  }
						  else 
						  {
							$("#emailmsg").html('').removeClass('error');
							$("#emailmsg").html('').removeClass('required');
							$("#email").removeClass("error_focus");
							$("#email").removeClass("error_focus_required");
							emailFlag=1;
						  }
						});
					
						$("#emailmsg").html('').removeClass('required');
						$("#email").removeClass("error_focus_required");
						$("#emailmsg").html('').removeClass('error');
						$("#email").removeClass("error_focus");
						emailFlag=1;
					}else{
						$("#emailmsg").html('').removeClass('required');
						$("#email").removeClass("error_focus_required");
						$("#emailmsg").html('Invalid Email').addClass('error');	
						$("#email").addClass("error_focus");
					}
				}else{
					$("#emailmsg").html('').removeClass('error');
					$("#email").removeClass("error_focus");
					$("#emailmsg").html('Email Mandatory').addClass('required');
					$("#email").addClass("error_focus_required");
					$("#mandEmail").addClass('error');
					$("#mand").addClass('error');
					emailFlag=0;
				}
				return emailFlag;
	   });
	   
	   $('#emailconfirm').bind("keyup focus blur", function() {		//Email Confirmation
	   		if($('#emailconfirm').val().length!=0)
			{
				if($('#email').val()==$('#emailconfirm').val())
				{
					$("#mandEmailconfirm").removeClass('error');
					$("#mand").removeClass('error');
					$("#emailconfirmmsg").html('').removeClass('required');
					$("#emailconfirm").removeClass("error_focus_required");
					$("#emailconfirmmsg").html('').removeClass('error');
					$("#emailconfirm").removeClass("error_focus");
					emailconfirmFlag=1
				}
				else
				{
					$("#emailconfirmmsg").html('').removeClass('required');
					$("#emailconfirm").removeClass("error_focus_required");
					$("#emailconfirmmsg").html('Email Does not Match').addClass('error');
					$("#emailconfirm").addClass("error_focus");
					emailconfirmFlag=0;
					
				}
			}
			else
			{
				$("#emailconfirmmsg").html('').removeClass('error');
				$("#emailconfirm").removeClass("error_focus");
				$("#emailconfirmmsg").html('Plese Enter Email and Confirm').addClass('required');
				$("#emailconfirm").addClass("error_focus_required");
				$("#mandEmailconfirm").addClass('error');
				$("#mand").addClass('error');
				emailconfirmFlag=0;
			}
			return emailconfirmFlag;
	   });
	   
	    $('#firstname').bind("keyup focus", function() {		//Firstname Validation
			if($('#firstname').val().length == 0)
			{
				$("#mandFirstnm").addClass('error');
				$("#mand").addClass('error');
				$("#firstnamemsg").html('First Name Mandatory').addClass('required');
				$("#firstname").addClass("error_focus_required");
				firstnmFlag=0;
			}
			else
			{
				$("#firstname").removeClass("error_focus_required");
				$("#firstnamemsg").html('').removeClass('required');
				$("#mandFirstnm").removeClass('error');
				$("#mand").removeClass('error');
				firstnmFlag=1;
			}
			return firstnmFlag;
		});
		
		$('#lastname').bind("keyup focus", function() {		//Lastname Validation
			if($('#lastname').val().length == 0)
			{
				$("#mandLastnm").addClass('error');
				$("#mand").addClass('error');
				$("#lastnamemsg").html('Last Name Mandatory').addClass('required');
				$("#lastname").addClass("error_focus_required");
				lastnmFlag=0;
			}
			else
			{
				$("#lastname").removeClass("error_focus_required");
				$("#lastnamemsg").html('').removeClass('required');
				$("#mandLastnm").removeClass('error');
				$("#mand").removeClass('error');
				lastnmFlag=1;
			}
			return lastnmFlag;
		});
		
		$('#postcode').bind("keyup focus", function() {		//Postcode Validation
			var pat=/^[\sa-zA-Z0-9]{5,7}$/;
			
			if($('#postcode').val().length != 0)
			{	
				$("#mandPostcode").removeClass('error');
				$("#mand").removeClass('error');
				$("#postcode").removeClass("error_focus_required");
				$("#postcodemsg").html('').removeClass('required');
					
				if((pat.test($('#postcode').val()))){
					$("#postcodemsg").html('').removeClass('error');	
					$("#postcode").removeClass("error_focus");
					postcodeFlag=1;
				}else{
					$("#postcodemsg").html('Invalid Postcode').addClass('error');	
					$("#postcode").addClass("error_focus");
					postcodeFlag=0;
				}
			}
			else
			{
				$("#mandPostcode").addClass('error');
				$("#mand").addClass('error');
				$("#postcodemsg").html('Postcode Mandatory').addClass('required');
				$("#postcode").addClass("error_focus_required");
				postcodeFlag=0;
			}
			return postcodeFlag;
		});
		
		$('#phone').bind("keyup focus", function() {		//Phone Validation
			var pat=/^[0-9-]{11,12}$/;
			
			if($('#phone').val().length != 0)
			{
				$("#phone").removeClass("error_focus_required");
				$("#phonemsg").html('').removeClass('required');
				$("#mandPhone").removeClass('error');
				$("#mand").removeClass('error');
				
				if((pat.test($('#phone').val()))){
					$("#phonemsg").html('').removeClass('error');	
					$("#phone").removeClass("error_focus");
					phoneFlag=1;
				}else{
					$("#phonemsg").html('Invalid Phone Number').addClass('error');	
					$("#phone").addClass("error_focus");
					phoneFlag=0;
				}
			}
			else
			{
				$("#mandPhone").addClass('error');
				$("#mand").addClass('error');
				$("#phonemsg").html('Phone Number Mandatory').addClass('required');
				$("#phone").addClass("error_focus_required");
				phoneFlag=0;
			}
			return phoneFlag;
		});
		$('#birthDate').DatePicker({
			format:'Y-m-d',
			date: $('#birthDate').val(),
			current: $('#birthDate').val(),
			starts: 1,
			position: 'center',
			onBeforeShow: function(){	
				$('#birthDate').DatePickerSetDate($('#birthDate').val(), true);
			},
			
			onChange: function(formated, dates){
				//datepickerViewDays
				var today = new Date();
				
				var orgValue = $('#birthDate').val().split("-");
				var orgYear=orgValue[0];
				var orgMonth=orgValue[1]-1;
				var orgDay=orgValue[2];
				
				var thisDt = formated.split("-");
				var thisYear=thisDt[0];
				var thisMonth=thisDt[1]-1;
				var thisDay=thisDt[2];
				var s=new Date();
				s.setFullYear(thisYear,thisMonth,thisDay);
				
				if (s > today)
				{
					var td =today.getFullYear()+"-"+(today.getMonth()+1)+"-"+today.getDate();
					$('#birthDate').val(td);
				}
				else
				{
					$('#birthDate').val(formated);
				}
				
				if(orgYear!=thisYear || orgMonth!=thisMonth || orgDay!=thisDay)
				{
					$('#birthDate').DatePickerHide();
				}
			}
		});
		
	   $('#create_user_form').submit( function() {
		   var userIdValue = document.getElementById('userId').value;
		   var acDetailFlag = 1;
		   if(userIdValue.length==0)
		   {
			   $('#user').keyup();
			   $('#pwd').keyup();
			   $('#pwdconfirm').keyup();
			   $('#email').keyup();
			   $('#emailconfirm').keyup();
			   if(userFlag==0 || pwdFlag==0 || pwdconfirmFlag==0 || emailFlag==0 || emailconfirmFlag==0)
			   {
				   acDetailFlag = 0;
			   }
		   }
		    $('#firstname').keyup();
		    $('#lastname').keyup();
			$('#postcode').keyup();
			$('#phone').keyup();
		    var countrySelected=$('#country').val();
		    var imageInfoSrc = document.getElementById('files').innerHTML;
			if(imageInfoSrc.length!=0)
			{
			      var imageName = $('#imageName').val();
			      var imagesize = $('#imagesize').val();
				  if(imageName.length!=0  && imagesize.length!=0 && imageName!='undefined' && imagesize!='undefined')
				  {
					 $('#selImageName').val(imageName);
					  $('#selImageSize').val(imagesize);
				  }
			}
		
		   if(acDetailFlag==1 && firstnmFlag==1 && lastnmFlag==1 && postcodeFlag==1 && phoneFlag==1 && countrySelected!=0)
		   {
			   return true;
		   }
		   else
		   {	
			   if(countrySelected==0)
			   {	
				    $('#countrymsg').html('Country Mandatory').addClass('required');
			   }
			   $('#submitmsg').addClass('required');
			   return false; 
		   }
			return false; 
		  
	   });
});
function test()
{
	return true;
}