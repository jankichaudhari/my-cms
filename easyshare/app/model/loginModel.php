<?php
class loginModel extends listModel{
	public function __construct($SERVER,$DBASE,$USERNAME,$PASSWORD)
	{
		parent::__construct($SERVER,$DBASE,$USERNAME,$PASSWORD);
	}
	function loginProcess($_POST)
	{
		$loginmail=$_POST['email'];
		$password=$_POST['password'];
		$remember=$_POST['remember'];
		$currentPage=$_POST['currentPage'];
		$indulgeon_url = commonModel :: indulgeon_url;
		$currentUrl = $indulgeon_url.'index.php?process=myAccount';
		
		if(!empty($currentPage))
		{	
			if($currentPage!='signin')
			{
				$currentUrl = $indulgeon_url.'index.php?process='.$currentPage;
			}
		}
		
		
		$r=parent::getResult("auth_users",""," WHERE email='$loginmail' ","");
		$salt=$r[0]['salt'];
	
		$pwd=md5(md5($password) . $salt);
		$result=parent::getResult("auth_users",""," WHERE email='$loginmail' AND password='$pwd' AND active='y' ","");
		$db_res=count($result);
		
		if($db_res==1)
		{
			$user=$result[0]['username'];
			$_SESSION['user']=$user;
			$_SESSION['user_id']=$result[0]['id'];
			if($remember==1)
			{
				setcookie("email",$loginmail,time()+60*60*24*100,"/");
				setcookie("password",$password,time()+60*60*24*100,"/");
			}
			$this->memberBilling(0);
			$this->regularInvoices();
			return $currentUrl ;
		}
		else
		{
			return 1;
		}
	}
		
	function activateSignin($activeCode)
	{
		$r=parent::getResult("auth_users",""," WHERE activation_code='$activeCode' ","");
		if(count($r) == 1)
		{
			//update query for active..
			$q="UPDATE auth_users SET active='y' WHERE activation_code='$activeCode'";
			$result=parent::update($q);
			
			//send welcome email
			if(!empty($result))
			{	
				$user=$r[0]['username'];
				$emailid=$r[0]['email'];
				
				$from_indul_adminEmail = commonModel :: indulgeon_admin_emailId;
				$from_indul_adminName = commonModel :: indulgeon_admin_name;
				$emailMsg='Welcome to Indulgeon....<http://www.indulgeon.com>';
				$emailSubject="Welcome to Indulgeon";
				parent::mailSend($from_indul_adminName,$from_indul_adminEmail,$user,$emailid,$emailSubject,$emailMsg);
			}
			
		}
		return $result;
	}
	
	function for_pwd_process($mailid)
	{
		$result=parent::getResult("auth_users",""," WHERE email='$mailid' AND active='y' ","");
		$db_res=count($result);
		$user=$result[0]['username'];
		$id=$result[0]['id'];
		
		if($db_res==1)
		{
			$newpwd=parent::getRand(8);
			$salt=md5($user);
			$pwd=md5(md5($newpwd) . $salt);
			
			$q="UPDATE auth_users SET password='$pwd' WHERE id='$id' ";
			$r=parent::update($q);
			
			$emailMsg=' Your new Password for Indulgeon.com is : '.$newpwd;
			//parent::mailSend("Admin","admin@admin.com",$user,$mailid,"New Password for Indulgeon.com",$emailMsg);
			$from_indul_adminEmail = commonModel :: indulgeon_admin_emailId;
			$from_indul_adminName = commonModel :: indulgeon_admin_name;
			$emailSubject="New Password for Indulgeon.com";
			parent::mailSend($from_indul_adminName,$from_indul_adminEmail,$user,$mailid,$emailSubject,$emailMsg);
			
			$content_status= 1;
			return $content_status;
		}
		else
		{
			$content_status= 0;
			return $content_status;
		}
	}
		
	function create_user($allvar)
	{	
		$totalResult = array();
		$tempImagePath = 'public/images/tmp/assets/';
		$uploaddir="public/images/users/assets/";
		$uploadTempDir = "public/images/users/thumbs/";
		$image='userImg';
		
		//$ipadd=parent::getIpAddr();
		$userId=$allvar['userId'];
		$user=$allvar['txtuser'];
		$salt=md5($user);
		$orgpwd=$allvar['pwd'];
		$pwd=md5(md5($orgpwd) . $salt);
		$email=$allvar['txtemail'];
		$birthDate=$allvar['birthDate'];
		//$forget_pwd_code=parent::getRand(10);
		$active='n';
		$currenttime=date("Y-m-d H:i:s");
		
		$firstnm=$allvar['txtfirstname'];
		$lastnm=$allvar['txtlastname'];
		$address=$allvar['txtaddress'];
		$country=$allvar['selectCountry'];
		$county=$allvar['txtcounty'];
		$town	=$allvar['txttown'];
		$postcode=$allvar['txtpostcode'];
		$phone=$allvar['txtphone'];
		
		$imgName=$allvar['selImageName'];
		$imgSize=$allvar['selImageSize'];
		
		$active_code=$this->createRandomPassword();
		
		if(empty($userId))
		{
			$query1="INSERT INTO auth_users(username,password,salt,email,birthDate,activation_code,active,created,modified) VALUES('$user','$pwd','$salt','$email','$birthDate','$active_code','$active','$currenttime','$currenttime')";
			$result1=parent::insert($query1);
			if(!empty($result1))
			{
				$query2="INSERT INTO auth_profile(user_id,first_name,last_name,address,country_id,county,town,phone,postcode) VALUES('$result1','$firstnm','$lastnm','$address','$country','$county','$town','$phone','$postcode')";
				$result2=parent::insert($query2);
			}
			
			if(!empty($result2) && !empty($imgName) && !empty($imgSize))
			{
				$imageId='user_' . $result2;
				$imgTmpName = $tempImagePath . $imgName;
				$imgType = $imageInfoArray[0];
				
				$_FILESVALUES = array (
					$image  => array("name" => $imgName, "type" => $imgType, "tmp_path" => $imgTmpName, "size" => $imgSize)
				);
				
				$r=parent:: store_uploaded_image($_FILESVALUES,$image,$imageId,$uploaddir,0,$uploadTempDir);
				$affected_msg = $r[1];
			}
			$result = $result2;
			//mail();
			$to_emailname=$firstnm."  " .$lastnm;
			$from_indul_adminEmail = commonModel :: indulgeon_admin_emailId;
			$from_indul_adminName = commonModel :: indulgeon_admin_name;
			$indulgeon_url = commonModel :: indulgeon_url;
			$emailSubject="Activate Registration";
			$emailMsg="Click Here to activate your Registration ".$indulgeon_url."index.php?process=signin&param1=$active_code";
			parent::mailSend($from_indul_adminName,$from_indul_adminEmail,$to_emailname,$email,$emailSubject,$emailMsg);
		}
		else
		{
			if(!empty($userId) && !empty($imgName) && !empty($imgSize))
			{
				$imageId='user_' . $userId;
				$imgTmpName = $tempImagePath . $imgName;
				$imgType = $imageInfoArray[0];
				
				$_FILESVALUES = array (
					$image  => array("name" => $imgName, "type" => $imgType, "tmp_path" => $imgTmpName, "size" => $imgSize)
				);
				
				$r=parent:: store_uploaded_image($_FILESVALUES,$image,$imageId,$uploaddir,0,$uploadTempDir);
				$affected_msg = $r[1];
			}
			$r=parent:: store_uploaded_image($_FILES,$image,$imageId,$uploaddir,0,$uploadTempDir);
				
			$query2="UPDATE auth_profile SET first_name='$firstnm',last_name='$lastnm',address='$address',country_id='$country',county='$county',town='$town',phone='$phone',postcode='$postcode' WHERE id='$userId' ";
			$result2=parent::update($query2);
			$result = $userId;
		}
		if(isset($affected_msg))
		{
			array_push($totalResult,$affected_msg);
			array_push($totalResult,$result);
		}
		else
		{
			array_push($totalResult,0);
			array_push($totalResult,$result);
		}
		return $totalResult;
	}
		
	function change_pwd_process($_POST,$userid)
	{	
		$r=parent::getResult("auth_users"," salt "," WHERE id='$userid' ","");
		$salt=$r[0]['salt'];
		$current=$_POST['current'];
		$new=$_POST['new'];
		
		$currentPwd=md5(md5($current) . $salt);
		$newPwd=md5(md5($new) . $salt);
		
		$query="UPDATE auth_users SET password='$newPwd' WHERE id='$userid' AND password='$currentPwd' ";
		$result=parent::update($query);
		
		return $result;
	}
		
	function createRandomPassword() 
	{
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;
		while ($i <= 9) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}
}
?>