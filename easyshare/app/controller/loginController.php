<?php
class loginController extends listController{
								
		function __construct($modelObj)		//initializing parent constructor with Model Object
		{
			parent::__construct($modelObj); 
		}
		
		// /*  LOGIN */ //
		/*function show_add()
		{	
			$location=$this->model->breadcrumb(0,'L',2,0);
			$content_status="edit_record";
			include('app/view/index.php');
		}*/
		function signin($active_code,$message)
		{	
			$location=$this->model->breadcrumb(0,'L',7,0);
			if(!empty($active_code))
			{
				$active=$this->model->activateSignin($active_code);			
			}
			//$thisServerName = "http://".$_SERVER['SERVER_NAME'];
			//$thisUrl = $thisServerName.$_SERVER['REQUEST_URI'];
			$thisPage = '';
			if(isset($_REQUEST['process'])){
				$thisPage = $_REQUEST['process'];
			}
			
			$content_status="signin";
			include('app/view/index.php');
		}
		function login_process()
		{
			$email=$_POST['email'];
			$password=$_POST['password'];
			if(!empty($email) && !empty($password))
			{
				$result=$this->model->loginProcess($_POST);
				echo $result;
			}
			else
			{
				echo 2;
			}
		}
		function forgot_pwd_page($message)
		{
			$location=$this->model->breadcrumb(0,'L',9,0);
			$content_status="forget_password";
			include('app/view/index.php');
		}
		function forgot_pwd()
		{
			$reset_email=$_POST['reset_email'];
			if(isset($reset_email))
			{
				$content_status=$this->model->for_pwd_process($reset_email);
				echo $content_status;
			}
			else
			{
				echo "Please Enter email Address";
			}
		}
		function validate_register()
		{
			$user=$_POST['user_name'];
			$email=$_POST['email_id'];
			$pwd=$_POST['current_pwd'];
			
			if(isset($user))
			{
				$r=$this->model->getResult("auth_users"," username "," WHERE username='$user'","");
				echo count($r);
			}
			if(isset($email))
			{
				$r=$this->model->getResult("auth_users"," email "," WHERE email='$email'","");
				echo count($r);
			}
			if(isset($pwd))
			{
				$userid=$_SESSION['user_id'];
				$r=$this->model->getResult("auth_users"," salt "," WHERE id='$userid' ","");
				$salt=$r[0]['salt'];
			
				$password=md5(md5($pwd) . $salt);
				$r=$this->model->getResult("auth_users"," password "," WHERE password='$password'","");
				echo count($r);
			}
		}
		function create_user_page($userId,$message)
		{
			$location=$this->model->breadcrumb(0,'L',8,0);
			$countryList=$this->model->countryList();
			if(!empty($userId))
			{
				$userLoginInfo=$this->model->getResult("auth_users",""," WHERE id='$userId'","");
				$userProfileInfo=$this->model->getResult("auth_profile",""," WHERE user_id='$userId'","");
				
				$username = $userLoginInfo[0]['username'];
				$email = $userLoginInfo[0]['email'];
				$birthDate = $userLoginInfo[0]['birthDate'];
				$firstName = $userProfileInfo[0]['first_name'];
				$lastName = $userProfileInfo[0]['last_name'];
				$address = stripcslashes($userProfileInfo[0]['address']);
				$countryId = $userProfileInfo[0]['country_id'];
				$county = $userProfileInfo[0]['county'];
				$town = $userProfileInfo[0]['town'];
				$postCode = $userProfileInfo[0]['postcode'];
				$phone = $userProfileInfo[0]['phone'];
				
				$fileName='userImg_' . $userId;
				$listPhoto=$this->model->getResult("listing_photos",""," WHERE file_name='$fileName' AND type_id='$userId' AND type='U' AND active='y' ","");
				if(count($listPhoto)!=0)
				{
					$photoId=$listPhoto[0]['id'];
					//$photoSrc=$listPhoto[0]['file_path'];
					$photoType=$listPhoto[0]['file_type'];
					$photoName=$listPhoto[0]['file_name'];
					$photoSize=$listPhoto[0]['file_size'];
					$photoSrc='public/images/users/thumbs/' . $photoName . '.'.$photoType;
					//$photoWidth=$listPhoto[0]['image_width'];
					//$photoHeight=$listPhoto[0]['image_height'];
					//list($x,$y)=$this->model->imageSize('110','110',$photoWidth,$photoHeight);
					//$photoHeight=$y;
					//$photoWidth=$x;
					list($width, $height, $type, $attr) = getimagesize($photoSrc);
					$photoHeight = $height;
					$photoWidth = $width;
					$topMargin = (110-$photoHeight) / 2;
				}
				else
				{
					$photoId=0;
					$photoSrc = 'public/images/users/user0th.jpg';
					$photoName=NULL;
					$photoSize=0;
					$photoWidth = 80;
					$photoHeight = 95;
				}
			}
			else
			{
				$photoId=0;
				$photoSrc = 'public/images/users/user0th.jpg';
				$photoName=NULL;
				$photoSize=0;
				$photoWidth = 80;
				$photoHeight = 95;
			}
			
			$content_status="create_user_page";
			include('app/view/index.php');
		}
		function create_user()
		{	
			$userId=$_POST['userId'];
			if(isset($_POST['CreateUser']))
			{
				$totalResult=$this->model->create_user($_POST);
				$resultMsg = $totalResult[0];
				$result = $totalResult[1];
				
				if(!empty($result))
				{
					if(!empty($userId))
					{
						?>
						<script type="text/javascript" language=javascript>
						 window.close(); 
						if (window.opener && !window.opener.closed && window.closed) {
						window.opener.location.reload();
						} 
						</script>
						<?php
					}
					else
					{
						$msg="Registration Completed...To Active it Check your Email.";
						header('Location:index.php?process=signin&param2='.$msg);
					}
				}
				else if(!empty($resultMsg))
				{
					$msg=$resultMsg;
					$this->create_user_page($userId,$msg);
				}
				else
				{
					$msg="Error!!Registration Failed";
					$this->create_user_page($userId,$msg);
				}
			}
			else
			{
				$msg="Error!!";
				$this->create_user_page($userId,$msg);
			}
		}
		function change_pwd_page($message)
		{
			$location=$this->model->breadcrumb(0,'L',10,0);
			$content_status="change_pwd";
			include('app/view/index.php');
		}
		function change_password()
		{
			$userId=$_SESSION['user_id'];
			if(isset($_POST['ChangePassword']))
			{
				$content_status=$this->model->change_pwd_process($_POST,$userId);
				
				if(!empty($content_status))
				{
					$msg="Your Password has been changed";
					$this->signin('',$msg);
				}
				else
				{
					$msg="Error!!";
					$this->change_pwd_page($msg);
				}
			}
			else
			{
				$msg="Error!!";
				$this->change_pwd_page($msg);
			}
		}
		// /* LOGIN */ //
}