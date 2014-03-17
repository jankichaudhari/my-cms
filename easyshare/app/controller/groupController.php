<?php
class groupController extends homeController{								
		function __construct($modelObj)		//initializing parent constructor with Model Object
		{
			parent::__construct($modelObj);
		}
		function groupImage()
		{	
				$groupId=$_POST['groupId'] ;
				$groupAC=$_POST['groupAC'] ;
				$imageId='group_' . $groupId;
				$uploaddir="public/images/group/";
				$image='groupImg';
				if ($_FILES[$image]["error"] > 0)
				{
					$message="Image Error : " . $_FILES[$image]["error"] ;
				}
				else
				{	
					$uploadedResult=$this->model->store_uploaded_image($_FILES,$image,$imageId,$uploaddir,0);
					$affected_id = $uploadedResult[0];
					$affected_msg = $uploadedResult[1];
					if(empty($affected_id) || isset($affected_msg))
					{	
						$message = $affected_msg;
					}
					else
					{	
						$dis_list=$this->model->getResult("listing_photos"," type_id "," WHERE id='$affected_id' ","");
						$groupId=$dis_list[0]['type_id'];
					}
				}
				if(!empty($groupAC))
				{
					$process_type = 'groupAdmin';
				}
				else
				{
					$process_type = 'groupSetup';
				}
				header('Location:index.php?process='.$process_type.'&param1=' . $groupId.'&error='.$message.'#basicInfo');
		}
		function group_opt_page()
		{
			$location=$this->model->breadcrumb(0,'N',11,0);
			$curr_user_id=$_SESSION['user_id']; 
			$listingList=$this->model->getResult("listing_items",""," WHERE user_id='$curr_user_id' AND approved=1 AND deleted='n' ","");
			$groupList=$this->model->getResult("group_info"," list_id "," WHERE  deleted='n' ","");
			$content_status="group_opt_page";
			include('app/view/index.php');
		}
		function groupSetup()
		{
			$location=$this->model->breadcrumb(0,'N',12,0);
			$groupId=$_REQUEST['param1'];
			$groupOpt = $_POST['groupOpt'];
			if(isset($_REQUEST['error']))
			{
				$message = $_REQUEST['error'];
			}
			if(!empty($groupId))
			{
				$content_status="groupSetup";
				include('app/view/index.php');
			}
			else if(empty($groupId) && !empty($groupOpt))
			{
					//$result_id=$this->model->store_group($_POST,$groupId);
					if($groupOpt=='exist') {
						$listId=$_POST['listing'];
					} else if($groupOpt=='scratch') {
						$listId = 0;
					}
					$_SESSION['group_type'] = $groupOpt . "_" . $listId;
					$content_status="groupSetup";
					include('app/view/index.php');
			}
			else if(empty($groupId))
			{
				$message = "Group can not created without basic Information!!";
				$content_status="groupSetup";
				 include('app/view/index.php');
			}
			else
			{
				header('Location:index.php?process=group_opt_page');
			}
		}
		function groupBasicInfo($groupId,$listId,$groupAC)
		{
			if(empty($groupId) && empty($listId))
			{
				$listName='No List Title';
				$listId=0;
				$photoSrc='public/images/noImage.jpg';
				$photoHeight=70;
				$photoWidth=100;
			}
			else
			{	
				if(!empty($groupId))
				{
					$group_record=$this->model->getResult("group_info",""," WHERE id='$groupId' AND deleted='n' ","");
					$title=$group_record[0]['title'];
					//$listId=$group_record[0]['list_id'];
					$listPhoto=$this->model->getResult("listing_photos",""," WHERE type_id='$groupId' AND type='G' AND active='y' ","");
					$photoId=$listPhoto[0]['id'];
					$photoName=$listPhoto[0]['file_name'];
					$photoType=$listPhoto[0]['file_type'];
					$photoSrc='public/images/group/thumbs/' . $photoName . '.'.$photoType;
					{
						list($width, $height, $type, $attr) = getimagesize($photoSrc);
						$photoHeight = $height;
						$photoWidth = $width;
						$topMargin = (80-$photoHeight) / 2;
					}
				}
				else if(!empty($listId))
				{
					$listingDetail=$this->model->getResult("listing_items"," name "," WHERE id='$listId' AND deleted='n' ",""); 
					$listName=$listingDetail[0]['name'];
					$title=$listName;
					$fileName='mainImage_' . $listId;
					$listPhoto=$this->model->getResult("listing_photos",""," WHERE file_name='$fileName' AND type_id='$listId' AND type='L'  AND active='y' ","");
					$photoSrc=$listPhoto[0]['file_path'];
					$photoWidth=$listPhoto[0]['image_width'];
					$photoHeight=$listPhoto[0]['image_height'];
					$photoType=$listPhoto[0]['file_type'];
					$photoSrc='public/images/listing/thumbs/' . $fileName . '.'.$photoType;
					$thumbPathDir = $this->list_image_resized($photoSrc,100,70,"/tmp/thumbnails/","/listing/thumbs/");
					$photoHeight=70;
					$photoWidth=100;
					$topMargin = (80-$photoHeight) / 2;
				}
				
				
				if(!file_exists($photoSrc))
				//else 
				{
					$listName='No List Title';
					$listId=0;
					$photoSrc='public/images/noImage.jpg';
					$photoHeight=70;
					$photoWidth=100;
				}
			}
			$mem_user_id = $_SESSION['user_id'];
			if(!empty($groupId))
			{
				$group_record=$this->model->getResult("group_info",""," WHERE id='$groupId' AND deleted='n' ","");
				$memberList=$this->model->getResult("group_members",""," WHERE group_id='$groupId' "," order by id ");	//print_r($memberList);
			}
			$content_status="groupBasicInfo";
			include('app/view/includes/content.inc.php');
		}
		function bookRules($groupId,$groupAC)
		{
			$allocation_units=$this->model->getResult("units",""," WHERE type1='book' AND active='y' ","");
			$group_record=$this->model->getResult("group_info",""," WHERE id='$groupId' AND deleted='n' ","");
			$book_info_id=$group_record[0]['booking_rule'];
			if(!empty($book_info_id)) {
					list($rule_type,$rule_id) = split('_', $book_info_id);
					$rule_info=$this->model->getResult("group_bookRules",""," WHERE id='$rule_id' AND active='y' "," order by id desc");
					$rota_info=$this->model->getResult("units",""," WHERE id='$rule_id' "," order by id desc");
					if($rule_type=='rule') {
						$rotaClass='rota';
						$rotaLinkClass='nonActive';
						$ruleClass='ruleActive';
						$ruleLinkClass='nowActive';
						$totalDays=$rule_info[0]['totalDays'];
						if($totalDays!=0){
							$limittotalLink='nowActive';
							$limittotalblock='limittotalActive';
						} else {
							$limittotalLink='nonActive';
							$limittotalblock='limittotalblock';
						}
						$weekendDays=$rule_info[0]['weekendDays'];
						if($weekendDays!=0){
							$limitWeekendLink='nowActive';
							$limitWeekendblock='limitWeekendActive';
						} else {
							$limitWeekendLink='nonActive';
							$limitWeekendblock='limitWeekendblock';
						}
						$totalHolidays=$rule_info[0]['totalHolidays'];
						$perHolidays=$rule_info[0]['perHolidays'];
						if($totalHolidays!=0 || $perHolidays!=0){
							$limitExtendLink='nowActive';
							$limitExtendblock='limitExtendActive';
						} else {
							$limitExtendLink='nonActive';
							$limitExtendblock='limitExtendblock';
						}
					} 
					else if($rule_type=='rota')
					 {
						$ruleClass='rule';
						$ruleLinkClass='nonActive';
						$limittotalLink='nonActive';
						$limittotalblock='limittotalblock';
						$limitWeekendLink='nonActive';
						$limitWeekendblock='limitWeekendblock';
						$limitExtendLink='nonActive';
						$limitExtendblock='limitExtendblock';
						$rotaClass='rotaActive';
						$rotaLinkClass='nowActive';
					}
				}
				else
				{
					$ruleClass='rule';
					$ruleLinkClass='nonActive';
					$limittotalLink='nonActive';
					$limittotalblock='limittotalblock';
					$limitWeekendLink='nonActive';
					$limitWeekendblock='limitWeekendblock';
					$limitExtendLink='nonActive';
					$limitExtendblock='limitExtendblock';
					$rotaClass='rota';
					$rotaLinkClass='nonActive';
				}
				$content_status="bookingRules";
				include('app/view/includes/content.inc.php');
		}
		function groupUsage($groupId,$groupAC)
		{
			$usageUnitInfo=$this->model->getResult("group_usageMonitor",""," WHERE group_id='$groupId' AND type='T' "," order by id desc");
			$totalUsages=count($usageUnitInfo);
			$startEndInfo=$this->model->getResult("group_usageMonitor",""," WHERE group_id='$groupId' AND type='SE' "," order by id desc");
			$totalstartEnd=count($startEndInfo);
			$content_status="usageMonitor";
			include('app/view/includes/content.inc.php');
		}
		function groupBilling($groupId,$groupFC,$groupMonth,$sr_io_type,$sr_io_pagenum)
		{	
				$currencyList=$this->model->currencyList();
				$usageUnitInfo=$this->model->getResult("group_usageMonitor",""," WHERE group_id='$groupId' "," order by id desc");
				$billingMonthInfo=$this->model->getResult("group_billing_type",""," WHERE group_id='$groupId' AND bill_type='M' AND active='y' "," order by id desc");
				$totalBillMonth=count($billingMonthInfo);
				$billingUsageInfo=$this->model->getResult("group_billing_type",""," WHERE group_id='$groupId' AND bill_type='U' AND active='y' "," order by id desc");
				$totalBillUsage=count($billingUsageInfo);
				$content_status="billing";
				include('app/view/includes/content.inc.php');
		}
		function group_activation()
		{
			$groupId = $_REQUEST['param1'];
			$submitstatus = $_REQUEST['param2'];
			if($submitstatus=='F')
			{
				if(!empty($groupId))
				{
					$result = $this->model->group_activation($groupId);
					if(!empty($result))
					{
						header('Location:index.php?process=groupSetup&param1='.$groupId.'&error='.$result);
					}
					else
					{
						header('Location:index.php?process=myAccount#myGroup');
					}
				}
				else
				{
					header('Location:index.php?process=groupSetup');
				}
			}
			else
			{
				header('Location:index.php?process=myAccount#myGroup');
			}
		}
		function store_billing_rules()
		{
			$groupId=$_POST['groupId'];
			$groupFC=$_POST['groupFC'] ;
			$groupMonth=$_POST['groupMonth'] ;
			$sr_io_type=$_POST['sr_io_type'] ;
			$sr_io_pagenum=$_POST['sr_io_pagenum'] ;
			if(isset($_POST['storeBillingRules']))
			{
				$result=$this->model->store_billing_rules($_POST,$groupId);
			}
			else
			{
				?><script type="text/javascript" language=javascript>
				alert("Error!! Not Saved!!!");
				</script><?php
			}
			if(isset($result) || !empty($result))
			{
				if($groupFC=='F')
				{
					$process_type = 'group_fc_page';
					header('Location:index.php?process='.$process_type.'&param1=' . $groupId.'&param2='.$groupMonth.'&param3='.$sr_io_type.'&pagenum='.$sr_io_pagenum.'#billingPreference');
				}
				else
				{
					header('Location:index.php?process=groupSetup&param1=' . $groupId.'#billingPreference');
				}
				
				//header('Location:index.php?process=groupSetup&param1=' . $groupId);
			}
		}
		function store_usage_rules()
		{	
			$groupId=$_POST['groupId'];
			$groupAC=$_POST['groupAC'] ;
			if(isset($_POST['storeusageMonitors']))
			{
				$result=$this->model->store_usage_rules($_POST,$groupId);
			}
			else
			{
				?><script type="text/javascript" language=javascript>
				alert("Error!! Not Saved!!!");
				</script><?php
			}
			if(isset($result) || !empty($result))
			{
				if($groupAC=='A')
				{
					$process_type = 'groupAdmin';
				}
				else
				{
					$process_type = 'groupSetup';
				}
				header('Location:index.php?process='.$process_type.'&param1=' . $groupId.'#usageMonitors');
				//header('Location:index.php?process=groupSetup&param1=' . $groupId);
			}
		}
		function store_book_rules()
		{	
			$groupId=$_POST['groupId'];
			$groupAC=$_POST['groupAC'] ;	
			if(isset($_POST['storeBookRules']))
			{
				$result=$this->model->store_book_rules($_POST,$groupId);
			}
			else
			{
				?><script type="text/javascript" language=javascript>
				alert("Error!! Not Saved!!!");
				</script><?php
			}
			if(isset($result) || !empty($result))
			{
				if($groupAC=='A')
				{
					$process_type = 'groupAdmin';
				}
				else
				{
					$process_type = 'groupSetup';
				}
				header('Location:index.php?process='.$process_type.'&param1=' . $groupId.'#bookingRules');
			}
		}
		function store_group_Basic()
		{	
			$groupId=$_POST['groupId'];
			$listId = $_POST['listId'];
			$groupAC=$_POST['groupAC'] ;
			//if(isset($_POST['storeGroupBasic']))
			if(count($_POST) != 0)
			{	
				if(empty($groupId))
				{
					$error = 0;
					$groupId = $this->model->addGroup();
					
					if(!empty($listId))
					{	
						$mainFileName = "mainImage_".$listId;
						$db_res=$this->model->getResult("listing_photos",""," WHERE type='L' AND type_id='$listId' AND file_name='$mainFileName' AND active='y' ","");
						
						$size=$db_res[0]['file_size'];
						$width=$db_res[0]['image_width'];
						$height=$db_res[0]['image_height'];
						
						$old_file=$db_res[0]['file_path'];
						$filetype=$db_res[0]['file_type'];
						$filename = 'groupImg_' . $groupId;
						$filepath = "public/images/group/assets/".$filename. "." .$filetype;
						if (!copy($old_file, $filepath)) 
						{
							$error = 1;
						}	
						
						$thumbListImgPath = $this->list_image_resized($old_file,100,70,"/listing/assets/","/group/thumbs/");
						$thumbGroupImgPath = "public/images/group/thumbs/".$filename. "." .$filetype;
						if (!copy($thumbListImgPath, $thumbGroupImgPath)) 
						{
							$error = 1;
						}
					}
				}
				if(!empty($groupId) && $error == 0)
				{
					$result=$this->model->store_group_Basic($_POST,$groupId);
				}
				else
				{
					$result = 0;
				}
			}
			if($groupAC=='A')
			{
				$process_type = 'groupAdmin';
			}
			else
			{
				$process_type = 'groupSetup';
			}
			if(!empty($result))
			{
				header('Location:index.php?process='.$process_type.'&param1=' . $result.'#basicInfo');
			}
			else
			{
				header('Location:index.php?process='.$process_type.'&param1=' . $groupId.'#basicInfo');
			}
		}
		function store_group_members()
		{
			$groupId=$_POST['groupId'];
			$groupAC=$_POST['groupAC'] ;
			if(isset($_POST['storeGroupBasic']))
			{
				$result=$this->model->store_group_members($_POST,$groupId);
			}
			if($groupAC=='A')
			{
				$process_type = 'groupAdmin';
			}
			else
			{
				$process_type = 'groupSetup';
			}
			if(!empty($result))
			{
				header('Location:index.php?process='.$process_type.'&param1=' . $result.'#members');
			}
			else
			{
				header('Location:index.php?process='.$process_type.'&param1=' . $groupId.'#members');
			}
		}
		function member_page()
		{	
			$selectedMemName = NULL;
			$location=$this->model->breadcrumb(0,'N',13,0);
			$userList=$this->model->userList();
			$groupId=$_REQUEST['param1'];
			$memId=$_REQUEST['param2'];
			$selectMem=$this->model->getResult("group_members",""," WHERE id='$memId' AND  active='y' ","");
			$selectedUserId = $selectMem[0]['user_id'];
			$selectUser=$this->model->getResult("auth_profile",""," WHERE id='$selectedUserId'","");
			if(count($selectUser)!=0)
			{
				$selectedMemName = $selectUser[0]['first_name']." ".$selectUser[0]['last_name'];
			}
			$memberList=$this->model->getResult("group_members",""," WHERE group_id='$groupId' AND  active='y' ","");
			$content_status="member_page";
			include('app/view/index.php');
		}
		function storeMember()
		{
			$results=$this->model->storeMember($_POST);
			
			if (!empty($results)) 
			{
				?><script type="text/javascript" language=javascript>
				 window.close(); 
					if (window.opener && !window.opener.closed) {
						window.opener.location.reload();
					} </script><?php
			}
			else
			{
				$message = "Error!! member not added..";
				$content_status="member_page";
				include('app/view/index.php');
			}
		}
		function activateMember()
		{
			$memberId = $_REQUEST['param1'];
			$query="UPDATE group_members SET active='y' WHERE id='$memberId'";
			$result=$this->model->update($query);
			if(!empty($result))
			{
				$message = 'Your membership acivated successfully...';
			}
			else
			{
				$message = 'Error!! You are not activated...';
			}
			header('Location:index.php?process=signin&param2='.$message);
		}
		function deleteMember()
		{	
			$results=$this->model->deleteMember($_POST);
			$result = (!empty($results)) ? $result=1 : $result=0;
			echo $result;
		}
		function searchMember()
		{
			$groupId=$_POST['groupId'];
			$groupMembers=$this->model->getResult("group_members",""," WHERE group_id='$groupId' ","");
			$results=$this->model->searchMember($_POST);	
			$searchText=$_POST['searchText'];
			$searchField=$_POST['searchField'];
			$content_status="memSearchRes";
			include('app/view/includes/content.inc.php');
		}
		
		/* start active and delete usage monitor */
		/*function deleteUsage()
		{
			$results=$this->model->deleteUsage($_POST);
			$result = (!empty($results)) ? $result=1 : $result=0;
			echo $result;
		}*/
		/*function activeUsage()
		{
			$usageId = $_POST['usageId'];
			$q1="UPDATE group_usageMonitor SET active='y' WHERE id='$usageId'";
			$r1=$this->model->update($q1);
		}*/
		/* end active and delete usage monitor */
		
		/* start active and delete start end units */
		function deleteUsageMonitor()
		{
			$results=$this->model->deleteUsageMonitor($_POST);
			$result = (!empty($results)) ? $result=1 : $result=0;
			echo $result;
		}
		/*function activeStartEnd()
		{
			$startEndId = $_POST['startEndId'];
			$q1="UPDATE group_usageMonitor SET active='y' WHERE id='$startEndId'";
			$r1=$this->model->update($q1);
		}*/
		/* end active and delete start end units */
		
		/* delete billing preferences */
		function deleteUsageBill()
		{
			$results=$this->model->deleteUsageBill($_POST);
			$result = (!empty($results)) ? $result=1 : $result=0;
			echo $result;
		}
		function deleteMonthBill()
		{
			$results=$this->model->deleteMonthBill($_POST);
			$result = (!empty($results)) ? $result=1 : $result=0;
			echo $result;
		}
		/* end delete billing preferences */
		
		function my_group($groupId)
		{
			$location=$this->model->breadcrumb($groupId,'G',14,0);
			if(empty($groupId) || !isset($groupId))
			{
				if(isset($_REQUEST['param1']))
				{
					$groupId=$_REQUEST['param1'];
				}
				else
				{
					$groupId=$_SESSION['mygroup'];
				}
			}
			$_SESSION['mygroup']=$groupId;
			$param2=$_REQUEST['param2'];
			if($param2=='error')
			{
				$errorMsg = 'Error!! Usage Data Not inserted.';
			}
			$myGroupInfo=$this->model->getResult("group_info",""," WHERE id='$groupId' AND  active='y' AND deleted='n' ","");
			$title=$myGroupInfo[0]['title'];
			$listId=$myGroupInfo[0]['list_id'];
			$adminMemId = $myGroupInfo[0]['prev_Admin_id'];
			$fcMemId = $myGroupInfo[0]['prev_FC_id'];
			$mcMemId = $myGroupInfo[0]['prev_MC_id'];
			$groupCreatedDt = strtotime($myGroupInfo[0]['created_dt']);
			$userId=$_SESSION['user_id'];
			$userInfo=$this->model->getResult("auth_profile"," id "," WHERE user_id='$userId'","");
			$user_id=$userInfo[0]['id'];
			$memberInfo=$this->model->getResult("group_members",""," WHERE user_id='$user_id' AND group_id='$groupId'  AND  active='y' ","");
			$memberId=$memberInfo[0]['id'];
			$memJoinDt=strtotime($memberInfo[0]['created_dt']);
			$memberStatus = NULL;
			switch($memberId)
			{
				case $adminMemId : $memberStatus = 'Member, Administrator';
				break;
				case $fcMemId : $memberStatus = 'Member, Finance';
				break;
				case $mcMemId : $memberStatus = 'Member, Maintenance';
				break;
				default : $memberStatus = 'Member';
				break;
			}
			$today=date('Y-m-d');
			$memberBookInfo=$this->model->getResult("member_bookingInfo","","  WHERE member_id='$memberId' AND (finishDate < '$today' OR (finishDate = '$today' AND finishTime <= '$todayNow'))  AND type='N' AND usageData='N' AND active='y' "," ORDER BY finishDate ");
			
			$listPhoto=$this->model->getResult("listing_photos",""," WHERE type_id='$groupId' AND type='G' AND active='y' ","");
			if((count($listPhoto)==0) && $listId!=0)
			{
				$listingDetail=$this->model->getResult("listing_items"," name "," WHERE id='$listId' AND deleted='n' ",""); 
				$title=$listingDetail[0]['name'];
				$fileName='mainImage_' . $listId;
				$listPhoto=$this->model->getResult("listing_photos",""," WHERE file_name='$fileName' AND type_id='$listId' AND type='L' AND active='y' ","");
			}
			
			if(count($listPhoto)!=0) 
			{
				$photoId=$listPhoto[0]['id'];
				//$photoName=$listPhoto[0]['file_name'];
				$photoSrc=$listPhoto[0]['file_path'];
				$photoWidth=$listPhoto[0]['image_width'];
				$photoHeight=$listPhoto[0]['image_height'];
				list($x,$y)=$this->model->imageSize('70','100',$photoWidth,$photoHeight);
				$photoHeight=$y;
				$photoWidth=$x;
			}
			else 
			{
				$listName='No List Title';
				$listId='No List Id';
				$photoSrc='public/images/noImage.jpg';
				$photoHeight=70;
				$photoWidth=100;
			}
			$content_status="myGroup";
			include('app/view/index.php');
		}
		function bookingValidation()
		{
			$memberAllBookings = array();
			$groupId=$_POST['groupId'];
			$start_dt=$_POST['start_dt'];
			$finish_dt=$_POST['finish_dt'];
			$memberId=$_POST['memberId'];
			$groupMC=$_POST['groupMC'];
			if($groupMC=='M')
			{
				$type='MC';
			}
			else
			{
				$type='N';
			}
			
			$startDt=explode("-",$start_dt);
			$finishDt=explode("-",$finish_dt);
			
			$start_t=$startDt[5];
			$finish_t=$finishDt[5];
			
			$today = date('Y-m-d');
			$todayNow = date('H:i:s');
			
			$groupRulesIdInfo=$this->model->getResult("group_info","  booking_rule "," WHERE id='$groupId'  AND deleted='n' ","");
			$groupRulesId=$groupRulesIdInfo[0]['booking_rule']; 
			$groupRulesId=explode("_",$groupRulesId);
			$groupRulesId=$groupRulesId[1];
			$groupRulesInfo=$this->model->getResult("group_bookRules",""," WHERE id='$groupRulesId' AND active='y' ","");
			$totalDays=$groupRulesInfo[0]['totalDays'];
			$weekendDays=$groupRulesInfo[0]['weekendDays'];
			$totalHolidays=$groupRulesInfo[0]['totalHolidays'];
			$perHolidays=$groupRulesInfo[0]['perHolidays'];
			
			$flag=0;
			
			$memberBookingInfo=$this->model->getResult("member_bookingInfo",""," WHERE member_id='$memberId' AND ((finishDate > '$today') OR (finishDate = '$today' AND finishTime > '$todayNow') ) AND  active='y' AND type='$type' "," ORDER BY finishDate,finishTime DESC ");
			
			for($m=0;$m<=count($memberBookingInfo);$m++)
			{	
				$start_Date = strtotime($memberBookingInfo[$m]['startDate']);
				$finish_Date = strtotime($memberBookingInfo[$m]['finishDate']);
				if($m==count($memberBookingInfo))
				{
					$start_Date = strtotime($startDt[0]."-".$startDt[1]."-".$startDt[2]);
					$finish_Date = strtotime($finishDt[0]."-".$finishDt[1]."-".$finishDt[2]);
				}
				if($start_Date != $finish_Date)
				{
					$s = $start_Date;
					$f = $finish_Date;
					while($s <= $f){
						array_push($memberAllBookings,$s);
						$s = strtotime('+1 day',$s);
					}
				}
				else
				{
					array_push($memberAllBookings,$start_Date);
				}
			}
			$memberAllBookings = array_unique($memberAllBookings);
			sort($memberAllBookings);
			$totalBookDays = count($memberAllBookings);
			$totalWeekendBookdays = 0;
			$per_holidays = 0;
			$countHolidays = 0;
			if($totalHolidays!=0 && $totalHolidays!=1)
			{
				for($a=0;$a<count($memberAllBookings);$a++)
				{
					if(!isset($memberAllBookings[$a+1]))
					{
						$date_difference = 0;
					}
					else
					{
						$dateDiff = $memberAllBookings[$a+1] - $memberAllBookings[$a];
						$date_difference = ceil($dateDiff/(60*60*24));
					}
					
					if($date_difference==1)
					{
						$countHolidays++;
					}
					else
					{
						if(($countHolidays+1) >= $totalHolidays)
						{
							$per_holidays++;
						}
						$countHolidays=0;
					}
					//weekend booking days counting
					if(date('w',$memberAllBookings[$a])==0 || date('w',$memberAllBookings[$a])==6)
					{
						$totalWeekendBookdays++;
					}
				}
			}
			//echo $totalBookDays."//".$totalWeekendBookdays."//".$per_holidays;
			$errorMessage = NULL;
			if($totalDays!=0)	
			{
				if($totalDays >= $totalBookDays)		//check for total number of booking days
				{
					if($weekendDays!=0)
					{
						if($weekendDays < $totalWeekendBookdays)	
						{
							$flag = 1;
							$errorMessage = $errorMessage . "<br/>Weekend Booking Days must not be more then ".$weekendDays;
						}
					}
					
					if($perHolidays < $per_holidays)
					{
						$flag=1;
						$errorMessage = $errorMessage . "<br/>Extended Booking limit is " . $perHolidays ." at any one time";
					}
				}
				else
				{
					$flag=1;
					$errorMessage = $errorMessage . "<br/>Total Number of Booking days must not be more then " . $totalDays;
				}
			}
			
			
			if($flag==0)
			{
				$result=$this->model->storeBooking($_POST,NULL);
				//$result = "yes";
			}
			else
			{
				echo $errorMessage;
				//echo date('Y-m-d-M-D-H');
				$result=0;
			}
			
			if(!empty($result) && $result!=0)
			{
				$bookedInfo=$this->model->getResult("member_bookingInfo"," finishDate,finishTime "," WHERE id='$result'  AND  active='y' ","");
				$finishD=strtotime($bookedInfo[0]['finishDate']." ".$bookedInfo[0]['finishTime']);
				echo date('Y-m-d-M-D-H',$finishD); 
			}
		}
		function myBooking($groupId,$groupMC)
		{
			$userId=$_SESSION['user_id'];
			$userInfo=$this->model->getResult("auth_profile"," id "," WHERE user_id='$userId'","");
			$user_id=$userInfo[0]['id'];
			$memberInfo=$this->model->getResult("group_members"," id "," WHERE user_id='$user_id' AND group_id='$groupId' AND  active='y' ","");
			$memberId=$memberInfo[0]['id'];
			if(!empty($groupMC) || isset($groupMC))
			{
				$memberBookInfo=$this->model->getResult("member_bookingInfo","","  WHERE member_id='$memberId' AND  active='y' AND type='MC' ","");
			} else 
			{
				$groupMC='N';
				$memberBookInfo=$this->model->getResult("member_bookingInfo","","  WHERE member_id='$memberId' AND  active='y' AND type='N' ","");
			}
			$currentDt=date('Y-m-d-M-D');
			$content_status="myBooking";
			include('app/view/includes/content.inc.php');
		}
		function calendar_page($memberId,$groupMC)
		{	
			if(empty($memberId) || !isset($memberId))
			{
				$memberId=$_POST['memberId'];
			}
			if(empty($groupMC) || !isset($groupMC))
			{
				$groupMC=$_POST['groupMC'];
			}
			
			$selected=$_POST['selectedDay'];
			$selectedDay=explode("-",$selected);
			
			$month_names = array(1=>'january', 2=>'february', 3=>'march', 4=>'april', 5=>'may', 6=>'june', 7=>'july', 8=>'august', 9=>'september', 10=>'october', 11=>'november', 12=>'december');
			$day_names = array('MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU');
			//$allow_past = false;
			$allow_past = true;
			$date_format = 'Y-m-d';
			$m=$_REQUEST['month'];
			$month = isset($m)? $m : date('n');
			$pd = mktime (0,0,0,$month,1,date('Y'));// timestamp of the first day
			
			$zd = -(date('w', $pd)? (date('w', $pd)-1) : 6)+1;// monday before
			$kd = date('t', $pd);// last day of moth
			
			$allAvl=$this->model->calendarAvailable($memberId,$month);
			$notAvl=$allAvl[1];
			$partAvl=$allAvl[0];
			
			$myBook=$this->model->myBooking($memberId,$groupMC);
			
			$start_dt=$_POST['start_dt'];
			$finish_dt=$_POST['finish_dt'];
			
			$startDt=explode("-",$start_dt);
			$finishDt=explode("-",$finish_dt);
			$displayMonth = date('n',$pd);
			if($selectedDay[1]==$displayMonth)
			{
				if(count($selectedDay) > 1) {
						$selected_date=$selectedDay[0] .$selectedDay[1] .$selectedDay[2];
				} 
			}
			else if($finishDt[1]==$displayMonth || $startDt[1]==$displayMonth)
			{
				if(count($finishDt) >1 ||  count($startDt) >1)
				{
					if($finishDt[1]==$displayMonth)
					{
						$selected_date=$finishDt[0] .$finishDt[1] .$finishDt[2];
					} else if($startDt[1]==$displayMonth)
					{
						$selected_date=$startDt[0] .$startDt[1] .$startDt[2];
					}
				}
				else {	
					$selected_date=date('Ymd');
				}
			}
			else 
			{	
					$selected_date=date('Ymd');
			}
			
			$content_status="calendar";
			include('app/view/includes/content.inc.php');
		}
		function time_slots($memberId,$groupMC)
		{
			if(empty($memberId) || !isset($memberId))
			{
				$memberId=$_POST['memberId'];
			}
			if(empty($groupMC) || !isset($groupMC))
			{
				$groupMC=$_POST['groupMC'];
			}
			$selected=$_POST['selectedDay'];
			$selectedDay=explode("-",$selected);
			if(empty($selected)) {
				$currentDay=date('D. d M, Y');
				$selected=date('Y-m-d-M-D');
				$selectedDay=explode("-",$selected);
				$current_dt=strtotime("now");
			} else {
				$currentDay=$selectedDay[4] . ". " . $selectedDay[2] . "  " . $selectedDay[3] . ", " .$selectedDay[0];
				$current_dt=strtotime($selectedDay[0].$selectedDay[1].$selectedDay[2]);
			}
			
			$current_d=date('Y-m-d',$current_dt);
			$start_dt=$_POST['start_dt'];
			$finish_dt=$_POST['finish_dt'];
			$startDt=explode("-",$start_dt);
			$finishDt=explode("-",$finish_dt);
		
			$members_noFinishId=$this->model->timeSlot($current_dt,$start_dt,$groupMC,$memberId);
			$members=$members_noFinishId[0];
			$startFinishId=$members_noFinishId[1];
			$endFinishId=$members_noFinishId[2];
			$bookIds=$members_noFinishId[3];
			$bookingId = $_POST['bookingId'];
			
			if(date('Ymd',$current_dt) >= date('Ymd'))
			{	
				if(empty($start_dt) || $start_dt=='none') {	
					$time_state = "Start";
				} else {
					
					if(($selectedDay[0] .$selectedDay[1] .$selectedDay[2])==($finishDt[0] .$finishDt[1]. $finishDt[2]))
					{
						$classId=$finishDt[5]; 
					}
					
					if(($selectedDay[0] .$selectedDay[1] .$selectedDay[2])>=($startDt[0] .$startDt[1]. $startDt[2]))
					{
						$time_state = 'Finish';
						if(($selectedDay[0] .$selectedDay[1] .$selectedDay[2])==($startDt[0] .$startDt[1]. $startDt[2]))
						{
							$startClass=$startDt[5];
						} else {
							$startClass=NULL;
						}
					}
					else 
					{
						$time_state = '';
					}
				}
			} else {
				$time_state = '';
			}
			
			$content_status="timeSlots";
			include('app/view/includes/content.inc.php');
		}
		function new_booking($memberId,$groupMC)
		{	
			if(empty($groupMC) || !isset($groupMC))
			{
				$groupMC=$_POST['groupMC'];
			}
			$startDate=$_POST['date'];
			$content_status="newBooking";
			include('app/view/includes/content.inc.php');
		}
		function all_booking_list($memberId,$groupMC)
		{	
			$allBookingInfo=$this->model->getResult("member_bookingInfo","","  WHERE type='N' AND  active='y' AND  member_id IN (SELECT id FROM group_members WHERE active='y' AND group_id IN ( SELECT group_id FROM group_members WHERE id='$memberId')) "," ORDER BY startDate DESC,startTime DESC ");
			
			$content_status="allBookingList";
			include('app/view/includes/content.inc.php');
		}
		function my_booking_list($memberId,$groupMC)
		{
			if(empty($memberId) || !isset($memberId))
			{
				$memberId=$_POST['memberId'];
			}
			if(empty($groupMC) || !isset($groupMC))
			{
				$groupMC=$_POST['groupMC'];
			}
			if($groupMC=='M')
			{
				$type='MC';
			}
			else
			{
				$type='N';
			}
			$memberBookInfo=$this->model->getResult("member_bookingInfo","","  WHERE member_id='$memberId' AND type='$type' AND  active='y' "," ORDER BY startDate DESC,startTime DESC ");
			
			$content_status="myBookingList";
			include('app/view/includes/content.inc.php');
		}
		function checkBookingTime()
		{
			$currentId = $_POST['currentId'];
			$today = date('Y-m-d');
			$todayNow = date('H:i:s');
			$memberBookInfo=$this->model->getResult("member_bookingInfo","","  WHERE id='$currentId' AND  active='y' ","");
			$thisFinishDate = $memberBookInfo[0]['finishDate'];
			$thisFinishTime = $memberBookInfo[0]['finishTime'];
			if($thisFinishDate > $today)
			{
				echo 0;
			}
			else if($thisFinishDate == $today)
			{
				if($thisFinishTime >= $todayNow)
				{
					echo 0;
				}
				else
				{
					echo 1;
				}
			}
			else
			{
				echo 1;
			}
		}
		function deleteBooking()
		{
			$bookId = $_POST['bookId'];
			if($bookId=='none')
			{
				$groupMC = $_POST['groupMC'];
				if($groupMC=='M')
				{
					$groupMC='MC';
				}
				else
				{
					$groupMC='N';
				}
				$bookStartDate = $_POST['bookStartDate'];
				$startDateArray = explode("-",$bookStartDate);
				$thisStartDt = $startDateArray[0]."-".$startDateArray[1]."-".$startDateArray[2];
				$today = date('Y-m-d');
				$todayNow = date('H:i:s');
				//echo "WHERE (startDate >'$today' OR (startDate='$today' AND startTime >'$todayNow')) AND startDate='$thisStartDt' AND type='$groupMC' AND active='y'";
				$thisBookingInfo = $this->model->getResult("member_bookingInfo","","  WHERE (startDate >'$today' OR (startDate='$today' AND startTime >'$todayNow')) AND startDate='$thisStartDt' AND type='$groupMC' AND active='y' ","");
				if(count($thisBookingInfo)!=0)
				{
					$flag = 0;
					for($b=0;$b<count($thisBookingInfo);$b++)
					{
						$thisId = $thisBookingInfo[$b]['id'];
						$thisResult = $this->model->deleteBooking($thisId);
						if(empty($thisResult))
						{
							$flag = 1;
						}
					}
					if($flag == 1)
					{
						$results=0;
					}
					else
					{
						$results=1;
					}
				}
			}
			else
			{
				$results=$this->model->deleteBooking($bookId);
			}
			//$results=$this->model->deleteBooking($_POST);
			$result = (!empty($results)) ? $result=1 : $result=0;
			echo $result;
		}
		function myUsageLog($groupId)
		{
			if(empty($groupId) || !isset($groupId))
			{
				$groupId=$_POST['groupId'];
			}
			$groupInfo = $this->model->getResult("group_info"," created_dt ","  WHERE id='$groupId' AND active='y' AND deleted='n' ","");
			$groupCreatedDt = strtotime($groupInfo[0]['created_dt']);
			$lastYearDate=strtotime("-12 months");
			$selectedDt = $lastYearDate;
			if($groupCreatedDt > $lastYearDate)
			{
				$selectedDt = $groupCreatedDt;
			}
			$selectedDate = date('Y-m-d',$selectedDt);
		
			$groupUsageInfo=$this->model->getResult("group_usageMonitor","","  WHERE group_id='$groupId' AND active='y' ","");
			if(count($groupUsageInfo)!=0)
			{	
				$totalUsages=count($groupUsageInfo);
				$userId=$_SESSION['user_id'];
				$userInfo=$this->model->getResult("auth_profile"," id "," WHERE user_id='$userId'","");
				$user_id=$userInfo[0]['id'];
				$memberInfo=$this->model->getResult("group_members"," id "," WHERE user_id='$user_id' AND group_id='$groupId'  AND  active='y' ","");
				$memberId=$memberInfo[0]['id'];
				$today=date('Y-m-d');
				$todayNow=date('H:i:s');
				$memberBookInfo=$this->model->getResult("member_bookingInfo","","  WHERE member_id='$memberId' AND type='N' AND ((finishDate < '$today') OR (finishDate = '$today' AND finishTime <= '$todayNow')) AND finishDate >= '$selectedDate' AND active='y' "," ORDER BY finishDate,finishTime ");
				
				if(count($memberBookInfo)!=0)
				{	
					$countHours=0;
					$countMinutes=0;
					$countPeriod=0;
					
					for($b=0;$b<count($memberBookInfo);$b++) { 
						
						$memBookId=$memberBookInfo[$b]['id'];
						$filledUsageData=$memberBookInfo[$b]['usageData'];
						$finishBookDt=strtotime($memberBookInfo[$b]['finishDate']);
						
						if($filledUsageData=='F')
						{
							$memberUsage=$this->model->getResult("member_usageInfo",""," WHERE booking_id='$memBookId' AND usage_type_id=0 AND active='y' ","");
							for($mu=0;$mu<count($memberUsage);$mu++)
							{
								$countPeriod++;
								$usageUnitValId=$memberUsage[$mu]['usageUnitValue'];
								$usageValueInfo=$this->model->getResult("booking_usageInfo",""," WHERE id='$usageUnitValId'  AND active='y'  ","");
								$startUsageValue=$usageValueInfo[0]['startValue'];
								$finishUsageValue=$usageValueInfo[0]['finishValue'];
								
								$startTm      =    strtotime( $startUsageValue );
								$finishTm        =    strtotime( $finishUsageValue );
								if( $finishTm >= $startTm )
								{
									$diff    =    $finishTm - $startTm;
									if( $days=intval((floor($diff/86400))) )
										$diff = $diff % 86400;
									if( $hours=intval((floor($diff/3600))) )
										$diff = $diff % 3600;
									if( $minutes=intval((floor($diff/60))) )
										$diff = $diff % 60;
									$diff    =    intval( $diff );            
									
									if($minutes == 60){
										$hours = $hours+1;
										$minutes = 0;
									} 
									
									$dayHours = $days * 24;
									$countHours =$countHours+$dayHours + $hours;
									$countMinutes =$countMinutes+$minutes;
								}
							}
							
							if($countMinutes > 60)
							{
								$hrs = $countMinutes/60;
								$hrsInfo = explode(".",$hrs);
								$countMinutes = (int)($hrsInfo[1][0].$hrsInfo[1][1]);
								$countHours = $countHours + ((float)$hrsInfo[0]);
							}
						}
					}
					
					$content_status="myUsageLog";
					include('app/view/includes/content.inc.php');
				} else {
					echo "No Any Booking Found";
				}
			} else {
					echo "No Any Member Found";
			}
		}
		function checkUsageDT($startDtTm,$finishDtTm)
		{
			if(isset($startDtTm) && isset($finishDtTm))
			{
				$startTm      =    strtotime( $startDtTm);
				$finishTm        =    strtotime( $finishDtTm );
			}
			else
			{
				$startDt = $_POST['startDt'];
				$finishDt = $_POST['finishDt'];
				
				//$startt = $_POST['startTm'];
				$startH = $_POST['startH'];
				$startM = $_POST['startM'];
				if(empty($startM) || ($finishM=='mm'))
				{
					$startM = 00;
				}
				$startTime = (int) $startH .":" . (int) $startM .":". 00;
				
				//$finisht = $_POST['finishTm'];
				$finishH = $_POST['finishH'];
				$finishM = $_POST['finishM'];
				if(empty($finishM) || ($finishM=='mm'))
				{
					$finishM = 00;
				}
				$finishTime = (int) $finishH .":" . (int) $finishM .":". 00;
				
				$startTm      =    strtotime( $startDt." ".$startTime);
				$finishTm        =    strtotime( $finishDt." ".$finishTime );
			}
			if( $finishTm >= $startTm )
			{
				$diff    =    $finishTm - $startTm;
				if( $days=intval((floor($diff/86400))) )
					$diff = $diff % 86400;
				if( $hours=intval((floor($diff/3600))) )
					$diff = $diff % 3600;
				if( $minutes=intval((floor($diff/60))) )
					$diff = $diff % 60;
				$diff    =    intval( $diff );            
				
				if($minutes == 60){
					$hours = $hours+1;
					$minutes = 0;
				} 
				//echo  $hours.".".$minutes;
				$dayHours = $days * 24;
				$countHours =$dayHours + $hours;
				
				if(isset($startDtTm) && isset($finishDtTm))
				{	return $countHours.".".$minutes; }
				else
				{	echo  $countHours.":".$minutes;	}
			}
			else
			{
				if(isset($startDtTm) && isset($finishDtTm))
				{	return 0;	}
				else
				{	echo  0;	}
			}
		}
		function myUsage($groupId)
		{
			if(empty($groupId))
			{
				$groupId=$_POST['groupId'];
			}
			if(empty($groupId))
			{
				$groupId=$_POST['param1'];
			}
			
			$param3 = $_REQUEST['param3'];
			$param4 = $_REQUEST['param4'];
			if((!empty($param3)) && (empty($param4)))
			{
				$params = '&param3='.$param3;
			}
			else if((!empty($param4)) && (empty($param3)))
			{
				$params = '&param4='.$param4;
			}
			else if((!empty($param3)) && (!empty($param4)))
			{
				$params = '&param3='.$param3.'&param4='.$param4;
			}
			
			$groupUsageInfo=$this->model->getResult("group_usageMonitor","","  WHERE group_id='$groupId' AND active='y' "," ORDER BY type DESC ");
			if(count($groupUsageInfo)!=0)
			{
				$totalUsages=count($groupUsageInfo);
				$userId=$_SESSION['user_id'];
				$userInfo=$this->model->getResult("auth_profile"," id "," WHERE user_id='$userId'","");
				$user_id=$userInfo[0]['id'];
				$memberInfo=$this->model->getResult("group_members"," id "," WHERE user_id='$user_id' AND group_id='$groupId'  AND  active='y' ","");
				$memberId=$memberInfo[0]['id'];
				$today=date('Y-m-d');
				//$today='2011-07-30';
				$todayNow=date('H:i:s');
				$memberBookInfo=$this->model->getResult("member_bookingInfo","","  WHERE member_id='$memberId' AND (finishDate < '$today' OR (finishDate = '$today' AND finishTime <= '$todayNow'))  AND type='N' AND usageData='N' AND active='y' "," ORDER BY finishDate ");
				$totalBookings=count($memberBookInfo);
			}
			$flage=0;
			if( ( ($totalBookings!=0)  && ($totalUsages!=0) )) {
			?><p><span style="font-size:13px;font-weight:bold" id="orangeText">Usage Data</span>&nbsp;-&nbsp;Enter Usage for the following booking(s)</p><?php
			}
				 
					if($totalBookings==0) 
					{
						echo "No Any Booking Found.";
					}
					else if($totalUsages==0)
					{
							echo "No Any Usage Rules Found";
					}
					else
					{
						for($b=0;$b<count($memberBookInfo);$b++) { 
							
							$bookingId=$memberBookInfo[$b]['id'];
							$startBookDt=strtotime($memberBookInfo[$b]['startDate']."  ".$memberBookInfo[$b]['startTime']);
							$finishBookDt=strtotime($memberBookInfo[$b]['finishDate']."  ".$memberBookInfo[$b]['finishTime']);
							$bookingDate=date('d M Y  h a',$startBookDt);	
								
								$content_status="myUsage";
								include('app/view/includes/content.inc.php');
						}
					}
						
		}
		
		function storeUsageData()
		{
			$results=$this->model->storeUsageData($_POST);
			$groupId=$_POST['groupId'];
			$params=$_POST['params'];
			
			if(!empty($results))
			{
				$this->model->memberBilling(0);		
				header('Location:index.php?process=my_group&param1='.$groupId.$params.'#myUsage');
			}
			else
			{
				$this->model->memberBilling(0);
				header('Location:index.php?process=my_group&param1='.$groupId.'&param2=error'.$params.'#myUsage');
			}
		}
		function inactivateMyUsage()
		{
			$results=$this->model->inactivateMyUsage($_POST);
			echo $results;
		}
		function myInvoices($groupId)
		{
			$param2 = $_REQUEST['param2'];
			$sortField = $_REQUEST['param3'];
			$param4 = $_REQUEST['param4'];
			
			if(empty($groupId))
			{
				$groupId=$_POST['groupId'];
			}
			if(empty($sortField))
			{
				$sortField = 1;
			}
			
			$myInvoicesInfo = $this->model->myInvoicesLog($groupId);
			$sortArray = $myInvoicesInfo[$sortField];
			if($param4=='d')
			{
				array_multisort($sortArray,SORT_DESC,$myInvoicesInfo[0],$myInvoicesInfo[1],$myInvoicesInfo[2],$myInvoicesInfo[3],$myInvoicesInfo[4]);
				$sortOrder='a';
			}
			else
			{
				array_multisort($sortArray,SORT_ASC,$myInvoicesInfo[0],$myInvoicesInfo[1],$myInvoicesInfo[2],$myInvoicesInfo[3],$myInvoicesInfo[4]);
				$param4 = 'a';
				$sortOrder='d';
			}
			
			if(!empty($param2))
			{
				$href="index.php?process=my_group&param1=$groupId&param2=$param2&param3=$sortField&param4=$param4#myInvoices";
			}
			else
			{
				$href="index.php?process=my_group&param1=$groupId&param3=$sortField&param4=$param4#myInvoices";
			}
			
			//$href="index.php?process=my_group&param1=".$groupId;
			$paging_html=$this->model->paging($sortArray,$href,5);
			$startLimit=$paging_html[0];
			$endLimit=$paging_html[1];
			$paging_html_text=$paging_html[2];
			
			$content_status="myInvoices";
			include('app/view/includes/content.inc.php');
		}
		function changePayStatus()
		{
			$thisInvoiceValue = $_POST['thisInvoiceValue'];
			$results=$this->model->changePayStatus($_POST);
			
			if(!empty($results))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		function viewInvoice()
		{
			$invoiceId = $_REQUEST['invoiceId'];	
			$viewStatus = $_REQUEST['type'];
			$thisBilling = $this->model->getResult("member_billing",""," WHERE id='$invoiceId' AND active='y' ","");
			$thisInvoiceType = $thisBilling[0]['invoice_type'];
			$thisMemberId = $thisBilling[0]['member_id'];
			$thisinvoiceDt = strtotime($thisBilling[0]['dateInvoice']);
			$thisMemInfo = $this->model->getResult("group_members",""," WHERE id='$thisMemberId' AND active='y' ","");
			$thisUserId = $thisMemInfo[0]['user_id'];
			$userInfo = $this->model->getResult("auth_profile",""," WHERE id='$thisUserId'","");
			$firstUname=$userInfo[0]['first_name'];
			$lastUname=$userInfo[0]['last_name'];
			$userFullName=$firstUname." ".$lastUname;
			$thisGroupInfo = $this->model->getResult("group_members"," user_id ","  WHERE id IN (SELECT prev_Admin_id FROM group_info WHERE id IN (SELECT group_id FROM group_members WHERE id='$thisMemberId' AND deleted='n') AND active='y' ) ","");
			$adminUserId = $thisGroupInfo[0]['user_id'];
			$authInfo = $this->model->getResult("auth_profile",""," WHERE id='$adminUserId'","");
			$first_name=$authInfo[0]['first_name'];
			$last_name=$authInfo[0]['last_name'];
			$adminFullName=$first_name." ".$last_name;
		
			if($thisInvoiceType=='a')
			{
				$invoiceType = 'Monthly';
				$invoiceDetails = $this->model->getResult("bill_invoice_auto",""," WHERE bill_invoice_id='$invoiceId' AND active='y' ","");
				$thisInvoiceId = $thisInvoiceInfo[0]['id'];
				$totalAmount = 0;
			}
			else if($thisInvoiceType=='m')
			{
				$invoiceType = 'Manual';
				$thisInvoiceInfo = $this->model->getResult("bill_invoice_manual",""," WHERE bill_invoice_id='$invoiceId' ","");
				$thisInvoiceId = $thisInvoiceInfo[0]['id'];
				$totalAmount = $thisInvoiceInfo[0]['totalCost'];
				$addNotes = $thisInvoiceInfo[0]['add_notes'];
				$invoiceDetails = $this->model->getResult("invoice_manual_detail",""," WHERE invoice_manual_id='$thisInvoiceId' ","");
			}
			else if($thisInvoiceType=='r')
			{
				$invoiceType = 'Regular';
				$thisInvoiceInfo = $this->model->getResult("bill_invoice_regular",""," WHERE bill_invoice_id='$invoiceId' ","");
				$thisInvoiceId = $thisInvoiceInfo[0]['id'];
				$totalAmount = $thisInvoiceInfo[0]['amount'];
				$groupACId = $thisInvoiceInfo[0]['regular_id'];
				$groupAC_Info = $this->model->getResult("group_AC",""," WHERE id='$groupACId'  AND active='y' ","");
				$typeId = $groupAC_Info[0]['type_id'];
				$invoiceDetails = $groupAC_Info[0]['amt_desc'];
				//$reccInfo=$this->model->getResult("AC_regular_IO",""," WHERE id='$typeId' ","");
				//$reccId = $reccInfo[0]['recurrence'];
				$reccUnitInfo=$this->model->getResult("units",""," WHERE id IN (SELECT recurrence FROM AC_regular_IO WHERE id='$typeId') ","");
				$invoiceType = $invoiceType . " " .$reccUnitInfo[0]['name'];
			}
		
			if($viewStatus=='download')
			{
				require('lib/fpdf.php');

				$pdf=new FPDF();
				$pdf->AddPage(); //Create new PDF page
				
				$pdf->SetFont('Arial','B',10); //Set the base font & size
				if(count($invoiceDetails)!=0)
				{
					//Cell function gets two parameters:Width & Height of the Cell
					$pdf->Cell(100,3,"Invoice");
					$pdf->Ln();//Creates a new line, blank here
					$pdf->SetFont('Arial','B',8);
					//Rect($x, $y, $w, $h, $style='')
					$pdf->Cell(100,3,"Date:".date('d M Y',$thisinvoiceDt),0,0,'R');
					$pdf->Ln();
					$pdf->Cell(100,3,$invoiceType." Invoice",0,0,'C');
					$pdf->Ln();
					$pdf->Cell(100,3,"From:".$adminFullName);
					$pdf->Ln();
					$pdf->Cell(100,3,"To:".$userFullName);
					$pdf->Ln();
					$pdf->Ln();
					$pdf->Cell(50,3,"Services/Description",0,1);
					$pdf->Cell(50,3,"Amount(£)",0,1);
					$pdf->Ln();
					$pdf->SetFont('Arial','',6);
					for($i=0;$i<count($invoiceDetails);$i++)
					 {	
						 if($thisInvoiceType=='a')
						 {
							$thisType = $invoiceDetails[$i]['type'];
							if($thisType=='U')
							{
								$invoiceDesc = 'Total Usage Charge';
								$cost =  $invoiceDetails[$i]['amount'];
								$totalAmount = $totalAmount + ((float)$cost);
							}
							else
							{
								$invoiceDesc = 'Fixed Monthly Charge';
								$cost =  $invoiceDetails[$i]['amount'];
								$totalAmount = $totalAmount + ((float)$cost);
							}
						 }
						else if($thisInvoiceType=='m')
						{
							$units =$invoiceDetails[$i]['units'];
							$rate = $invoiceDetails[$i]['rate'];
							$cost = ((float) $units) * ((float)$rate);
							$invoiceDesc = $invoiceDetails[$i]['service_desc'];
						}
						else
						{
							$invoiceDesc = $invoiceDetails;
							$cost = $totalAmount;
						}
						$pdf->Cell(50,3,$invoiceDesc,0,1);
						$pdf->Cell(50,3,number_format($cost,2,'.',','),0,1);
						$pdf->Ln();
						$pdf->Cell(50,3,"Total",0,1);
						$pdf->Cell(50,3,number_format($totalAmount,2,'.',','),0,1);
						$pdf->Ln();
						$pdf->Cell(100,3,"*This Invoice is for reference purpose only, and should not be used as proof of purchase/payment.",0,1);
					 }
				}
				else
				{
					$pdf->Cell(100,5,"No Any Invoice Record");
				}
				
				 $pdf->Output($invoiceType." Invoce","I");
			}
			else
			{
				$content_status="viewInvoice";
				$format = 'noFormat';
				include('app/view/includes/content.inc.php');	
			}
		}
		function financialSummery($groupId)
		{
			$groupMonth = date('m');
			$prevNextMonthInfo = $this->prevNextMonthInfo($groupId);
			$groupMinMonthInfo = $prevNextMonthInfo[0];
			$groupMaxMonthInfo = $prevNextMonthInfo[1];
			$groupACInfo = $this->model->groupAccount($groupId,$groupMaxMonthInfo,$groupMinMonthInfo);
			$groupBalDate = $groupACInfo[5][0];
			$groupBalance = $groupACInfo[5][1];
			$groupInfo = $this->model->getResult("group_info"," prev_Admin_id "," WHERE id='$groupId' AND deleted='n' ","");
			$groupAdminId = $groupInfo[0]['prev_Admin_id'];
			$adminMemInfo = $this->model->getResult("group_members",""," WHERE id='$groupAdminId' AND active='y' ","");
			$user_id = $adminMemInfo[0]['user_id'];
			$authInfo = $this->model->getResult("auth_profile",""," WHERE id='$user_id'","");
			$first_name=$authInfo[0]['first_name'];
			$last_name=$authInfo[0]['last_name'];
			$adminFullName=$first_name." ".$last_name;
			
			$content_status="financialSummery";
			include('app/view/includes/content.inc.php');
		}
		function groupAdmin($groupId)
		{
			$location=$this->model->breadcrumb($groupId,'G',14,'A');
			if(empty($groupId) || !isset($groupId))
			{
				if(isset($_REQUEST['param1']))
				{
					$groupId=$_REQUEST['param1'];
				}
				else
				{
					$groupId=$_SESSION['group'];
				}
			}
			$_SESSION['group']=$groupId;
			/*$noInvoiceBookings = $this->model->memberBilling($groupId);*/
			if(isset($_REQUEST['error']))
			{
				$message = $_REQUEST['error'];
			}
			$sortIndex=$_REQUEST['param2'];
			$sortState=$_REQUEST['param3'];
			$pagenum=$_REQUEST['pagenum'];
			if(!isset($pagenum))
			{
				$pagenum = 1;
			}
			$content_status="groupAdmin";
			include('app/view/index.php');
		}
		function adminLog($groupId,$sortIndex,$sortState,$pagenum)
		{
			if(empty($groupId))
			{
				$groupId=$_SESSION['group'];
			}
			if(empty($sortIndex) || !isset($sortIndex) || empty($sortState) || !isset($sortState))
			{
				$sortIndex=$_REQUEST['sortIndex'];
				$sortState=$_REQUEST['sortState'];
			}
			if(!isset($sortIndex))
			{
				$sortIndex=0;
			}
			if(!isset($sortState))
			{
				$sortState='a';
			}
			$noInvoiceBookings = $this->model->memberBilling($groupId);
			$logArray=$this->model->adminLogArray($groupId);
			
			$sortArray=$logArray[$sortIndex];
			if($sortState=='d')
			{
			array_multisort($sortArray,SORT_DESC,$logArray[1],$logArray[2],$logArray[5],$logArray[6],$logArray[3],$logArray[4],$logArray[7],$logArray[8],$logArray[10],$logArray[11],$logArray[12],$logArray[0]);
			}else if($sortState=='a')
			{
			array_multisort($sortArray,SORT_ASC,$logArray[1],$logArray[2],$logArray[5],$logArray[6],$logArray[3],$logArray[4],$logArray[7],$logArray[8],$logArray[10],$logArray[11],$logArray[12],$logArray[0]);
			}
			
			$array=$logArray[$sortIndex];
			$href="index.php?process=groupAdmin&param1=".$groupId."&param2=".$sortIndex."&param3=".$sortState."#adminLog";
			$paging_html=$this->model->paging($array,$href,10);
			$startLimit=$paging_html[0];
			$endLimit=$paging_html[1];
			$paging_html_text=$paging_html[2];
			
			$id=$logArray[0];
			$startDate=$logArray[1];
			$finishDate=$logArray[2];
			$fullName=$logArray[3];
			$groupUnit=$logArray[4];
			$startUsageValue=$logArray[5];
			$finishUsageValue=$logArray[6];
			$total=$logArray[7];
			$usageTypes=$logArray[8];
			$unActionedEnt=$logArray[9];
			$bookIds=$logArray[10];
			$groupRuleIds=$logArray[11];
			$usageInfoIds=$logArray[12];
			$inactiveUsages=$logArray[13];
			
			$content_status="adminLog";
			include('app/view/includes/content.inc.php');
		}
		function myAccount($message)
		{
			$location=$this->model->breadcrumb($groupId,'G',14,0);
			$curr_user_id=$_SESSION['user_id'];
			$images = array();
			$groupMemInfo=$this->model->getResult("group_members",""," WHERE user_id='$curr_user_id' AND active='y' ","");
			$myRegProfile=$this->model->getResult("auth_profile",""," WHERE user_id='$curr_user_id' ","");
			$name = $myRegProfile[0]['first_name']." ".$myRegProfile[0]['last_name'];
			$address = stripcslashes($myRegProfile[0]['address']);
			$phone = $myRegProfile[0]['phone'];
			$myRegUser=$this->model->getResult("auth_users",""," WHERE id='$curr_user_id' ","");
			$usename = $myRegUser[0]['username'];
			$email = $myRegUser[0]['email'];
			$birthDate = strtotime($myRegUser[0]['birthDate']);
			$fileName='userImg_' . $curr_user_id;
			$listPhoto=$this->model->getResult("listing_photos",""," WHERE file_name='$fileName' AND type_id='$curr_user_id' AND type='U' AND active='y' ","");
			
			if(count($listPhoto)!=0)
			{
				$profilePhotoId=$listPhoto[0]['id'];
				$profilePhotoSrc=$listPhoto[0]['file_path'];
				$profilePhotoWidth=$listPhoto[0]['image_width'];
				$profilePhotoHeight=$listPhoto[0]['image_height'];
				list($x,$y)=$this->model->imageSize('100','90',$profilePhotoWidth,$profilePhotoHeight);
				$profilePhotoHeight=$y;
				$profilePhotoWidth=$x;
				$profileTopmargin = ((100 - (float)$profilePhotoHeight) / 2);
			}
			else
			{	
				$profilePhotoSrc = 'public/images/users/user0th.jpg';
				$profilePhotoWidth = 80;
				$profilePhotoHeight = 95;
				$profileTopmargin = 5;
			}
			
			$content_status="myAccount";
			include('app/view/index.php');
		}
		function myTaggedListing()
		{
			$tagIds = array();
			$listingName = array();
			$listingShareCost = array();
			$listingLocation = array();
			$images = array();
			$imgWidths = array();
			$imgHeights = array();
			$popImgWidths = array();
			$popImgHeights = array();
			$curr_user_id=$_SESSION['user_id'];
			$first = max(0, intval($_POST['first']) - 1);
			$last  = max($first + 1, intval($_POST['last']) - 1);
			
			$imgDirPath = "public/images/listing/thumbs/";
			
			$length = $last - $first + 1;
			
			$myTags=$this->model->getResult("listing_tagged",""," WHERE user_id='$curr_user_id' ","");
			foreach($myTags as $mt=>$value)
			{
				$thisTagId = $myTags[$mt]['id'];
				$thisListId = $myTags[$mt]['list_id'];
				array_push($tagIds,$thisTagId);
				$myTagListingInfo=$this->model->getResult("listing_items",""," WHERE id='$thisListId' AND deleted='n' ","");
				$locId = $myTagListingInfo[0]['location_id'];
				$locationInfo=$this->model->getResult("postcodes"," town,county "," WHERE id='$locId' ","");
				$location = NULL;
				if(!empty($locationInfo[0]['town'] ))
				{
					$location = $locationInfo[0]['town'].",";
				}
				if(!empty($locationInfo[0]['county']))
				{
					$location = $location . $locationInfo[0]['county'];
				}
				//$location = $locationInfo[0]['town'] ."_" .$locationInfo[0]['county'];
				array_push($listingName,$myTagListingInfo[0]['name']);
				array_push($listingShareCost,$myTagListingInfo[0]['cost_per_share']);
				array_push($listingLocation,$location);
				$thisFileNm = 'mainImage_'.$myTagListingInfo[0]['id'];
				$listImgInfo=$this->model->getResult("listing_photos",""," WHERE type='L' AND type_id='$thisListId' AND  file_name='$thisFileNm' AND active='y' ","");
				if(count($listImgInfo)!=0)
				{
					//$thisImgPath = $listImgInfo[0]['file_path'];
					$thisImgPath = $imgDirPath . $listImgInfo[0]['file_name'] . ".".$listImgInfo[0]['file_type'];
					$thisImgWidth = $listImgInfo[0]['image_width'];
					$thisImgHeight = $listImgInfo[0]['image_height'];
				}
				else
				{
					$thisImgPath ="public/images/noImage.jpg";
					$thisImgWidth =116;
					$thisImgHeight = 86;
				}
				list($x1,$y1)=$this->model->imageSize(86,116,$thisImgWidth,$thisImgHeight);
				list($x,$y)=$this->model->imageSize(110,138,$thisImgWidth,$thisImgHeight);
				array_push($imgWidths,$x1);
				array_push($imgHeights,$y1);
				array_push($popImgWidths,$x);
				array_push($popImgHeights,$y);
				array_push($images,$thisImgPath);
			}
			
			$total    = count($myTags);
			if(!empty($total))
			{
				$selected = array_slice($images, $first, $length);
				
				header('Content-Type: text/xml');
				
				echo '<data>';
				
				// Return total number of images so the callback
				// can set the size of the carousel.
				echo '  <total>' . $total . '</total>';
				for($i=0;$i<$total ;$i++)
				{
					echo '  <id> ';
					echo '  <imdId>' . $i . '</imdId>';
					echo '  <image>' . $images[$i] . '</image>';
					echo '  <width>' . $imgWidths[$i] . '</width>';
					echo '  <height>' . $imgHeights[$i] . '</height>';
					echo '  <popWidth>' . $popImgWidths[$i] . '</popWidth>';
					echo '  <popHeight>' . $popImgHeights[$i] . '</popHeight>';
					echo '  <name>' . $listingName[$i] . '</name>';
					echo '  <cost>' . $listingShareCost[$i] . '</cost>';
					echo '  <location>' . $listingLocation[$i] . '</location>';
					echo '  <tagId>' . $tagIds[$i] . '</tagId>';
					echo ' </id> ';
				}
				
				echo '</data>';
			}
			else
			{
				echo 0;
			}
		}
		function unTagAllListing()
		{
			$result = $this->model->unTagAllListing($_POST);
			if(!empty($result))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		function unTagListing()
		{
			$result = $this->model->unTagListing($_POST);
			if(!empty($result))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		function myListing()
		{
			$listingIds = array();
			$listingNames = array();
			$listingStatus = array();
			$storedStatus = array();
			$images = array();
			$imgWidths = array();
			$imgHeights = array();
			$popImgWidths = array();
			$popImgHeights = array();
			$curr_user_id=$_SESSION['user_id'];
			$first = max(0, intval($_POST['first']) - 1);
			$last  = max($first + 1, intval($_POST['last']) - 1);
			
			$imgDirPath = "public/images/listing/thumbs/";
			
			$length = $last - $first + 1;
			
			$myListing=$this->model->getResult("listing_items",""," WHERE user_id='$curr_user_id' AND deleted='n' ","");
			foreach($myListing as $ml=>$value)
			{
				$thisListId = $myListing[$ml]['id'];
				$thisListName = $myListing[$ml]['name'];
				array_push($listingIds,$thisListId);
				array_push($listingNames,$thisListName);
				$store_step = $myListing[$ml]['store_step'];
				if($store_step==0)
				{
					$store_step = 1;
				}
				$approved = $myListing[$ml]['approved'];
				if($approved==1)
				{
					$status = 'Active';
				}
				else
				{
					$status = 'Inactive';
				}
				array_push($listingStatus,$status);
				array_push($storedStatus,$store_step);
				$thisFileNm = 'mainImage_'.$thisListId;
				$listImgInfo=$this->model->getResult("listing_photos",""," WHERE type='L' AND type_id='$thisListId' AND  file_name='$thisFileNm' AND active='y' ","");
				if(count($listImgInfo)!=0)
				{
					//$thisImgPath = $listImgInfo[0]['file_path'];
					$thisImgPath = $imgDirPath . $listImgInfo[0]['file_name'] . ".".$listImgInfo[0]['file_type'];
					$thisImgWidth = $listImgInfo[0]['image_width'];
					$thisImgHeight = $listImgInfo[0]['image_height'];
				}
				else
				{
					$thisImgPath ="public/images/noImage.jpg";
					$thisImgWidth =116;
					$thisImgHeight = 86;
				}
				list($x1,$y1)=$this->model->imageSize(86,116,$thisImgWidth,$thisImgHeight);
				list($x,$y)=$this->model->imageSize(110,138,$thisImgWidth,$thisImgHeight);
				array_push($imgWidths,$x1);
				array_push($imgHeights,$y1);
				array_push($popImgWidths,$x);
				array_push($popImgHeights,$y);
				array_push($images,$thisImgPath);
			}
			
			$total    = count($myListing);
			if(!empty($total))
			{
				$selected = array_slice($images, $first, $length);
				
				header('Content-Type: text/xml');
				
				echo '<data>';
				
				// Return total number of images so the callback
				// can set the size of the carousel.
				echo '  <total>' . $total . '</total>';
				for($i=0;$i<$total ;$i++)
				{
					echo '  <id> ';
					echo '  <imdId>' . $i . '</imdId>';
					echo '  <image>' . $images[$i] . '</image>';
					echo '  <width>' . $imgWidths[$i] . '</width>';
					echo '  <height>' . $imgHeights[$i] . '</height>';
					echo '  <popWidth>' . $popImgWidths[$i] . '</popWidth>';
					echo '  <popHeight>' . $popImgHeights[$i] . '</popHeight>';
					echo '  <name>' . $listingNames[$i] . '</name>';
					echo '  <status>' . $listingStatus[$i] . '</status>';
					echo '  <step>' . $storedStatus[$i] . '</step>';
					echo '  <listId>' . $listingIds[$i] . '</listId>';
					echo ' </id> ';
				}
				echo '</data>';
			}
			else
			{
				echo 0;
			}
		}
		function deleteListing()
		{
			$result = $this->model->deleteListing($_POST);
			if(!empty($result))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		function endGroup()
		{
			$groupId=$_POST['groupId'];
			$result = $this->model->endGroup($_POST);
			echo $result;
		}
		function resumeGroup()
		{
			$groupId=$_POST['groupId'];
			$result = $this->model->resumeGroup($_POST);
			if(!empty($result))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		function usageInfo_page()
		{	
			$groupId=$_REQUEST['param6'];
			$usageType=$_REQUEST['param7'];
			$location=$this->model->breadcrumb($groupId,'G',14,0);
			$userList=$this->model->userList();
			$bookId=$_REQUEST['param1'];
			$usageInfo=$this->model->getResult("member_bookingInfo",""," WHERE id='$bookId'","");
			$startBookDt = strtotime($usageInfo[0]['startDate']."  ".$usageInfo[0]['startTime']);
			$finishBookDt = strtotime($usageInfo[0]['finishDate']."  ".$usageInfo[0]['finishTime']);
			$groupRuleId=$_REQUEST['param2'];
			$usageId=$_REQUEST['param3'];
			$usageInfo=$this->model->getResult("member_usageInfo",""," WHERE id='$usageId'","");
			$usageValId=$usageInfo[0]['usageUnitValue'];
			$usageValues=$this->model->getResult("booking_usageInfo",""," WHERE id='$usageValId' ","");
			$startVal=$usageValues[0]['startValue'];
			$finishVal=$usageValues[0]['finishValue'];
			if($usageType=='T')
			{
				$startBookDt = strtotime($startVal);
				$finishBookDt = strtotime($finishVal);
				$starth=date('H',$startBookDt);
				$finishh=date('H',$finishBookDt);
				$startm=date('i',$startBookDt);
				$finishm=date('i',$finishBookDt);
				
				$totalTimeDiff = $this->checkUsageDT($startVal,$finishVal);
			}
			$sortIndex=$_REQUEST['param4'];
			$sortState=$_REQUEST['param5'];
			
			
			$groupRuleInfo=$this->model->getResult("group_usageMonitor",""," WHERE id='$groupRuleId' AND active='y' ","");	
			$content_status="usageData_page";
			include('app/view/index.php');
		}
		function changeUsageData()
		{
			$results=$this->model->changeUsageData($_POST);
			$sortIndex=$_POST['sortIndex'];
			$sortState=$_POST['sortState'];
			
			if ($results!=0 || !empty($results)) {
				?><script type="text/javascript" language=javascript>
				 window.close(); 
				 
				if (window.opener && !window.opener.closed) {
					window.opener.location.reload();
				} 
                </script>
				<?php
			}
		}
		function deleteUsageData()
		{	
			$sortIndex=$_POST['sortIndex'];
			$sortState=$_POST['sortState'];
			$pagenum=$_POST['pagenum'];
			
			$results=$this->model->deleteUsageData($_POST);
			/*if(!empty($results) || isset($results))
			{
				$result=1;
			}
			else
			{
				$result=0;
			}*/
			echo $results;
		}
		/* check before deleting usage monitor */
		function checkUsageBooking()
		{
			$usageId = $_POST['usageId'];
			$groupId = $_POST['groupId'];
			$today = date('Y-m-d');
			$todayNow = date('H:i:s');
			$groupUsageInfo=$this->model->getResult("group_usageMonitor","","  WHERE id='$usageId'  AND active='y' ","");
			$usageCreatedDt = strtotime($groupUsageInfo[0]['created_dt']);
			$usageDt = date('Y-m-d',$usageCreatedDt);
			$userId=$_SESSION['user_id'];
			$userInfo=$this->model->getResult("auth_profile"," id "," WHERE user_id='$userId'","");
			$user_id=$userInfo[0]['id'];	
			$memberInfo=$this->model->getResult("group_members"," id "," WHERE user_id='$user_id' AND group_id='$groupId' AND  active='y' ","");
			$memberId=$memberInfo[0]['id'];
			$today=date('Y-m-d');
			$todayNow=date('H:i:s');
			$memberBookInfo=$this->model->getResult("member_bookingInfo","","  WHERE member_id='$memberId' AND (finishDate < '$today' OR (finishDate = '$today' AND finishTime <= '$todayNow')) AND startDate >= '$usageDt' AND type='N' AND usageData='N' AND active='y' "," ORDER BY finishDate ");
			
			if(count($memberBookInfo)!=0)
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		function viewGroupAC()
		{
			$viewStatus = $_REQUEST['param3'];	
			$groupId = $_REQUEST['param1'];
			$groupMonth=$_REQUEST['param2'];	
			
			$prevNextMonthInfo = $this->prevNextMonthInfo($groupId);
			
			$groupMinMonthInfo = $prevNextMonthInfo[0];
			$groupMaxMonthInfo = $prevNextMonthInfo[1];
			
			$groupBalanceInfo = $this->model->groupAccount($groupId,$groupMaxMonthInfo,$groupMinMonthInfo);
			
			$groupBalance = $groupBalanceInfo[5][1];
			
			if($viewStatus=='download')
			{
				require('lib/fpdf.php');

				$pdf=new FPDF();
				$pdf->AddPage(); //Create new PDF page
				
				$allMonthYear = $_REQUEST['param4'];	
				$monthyearArray = explode("_",$allMonthYear);
				$pdf->SetFont('Arial','B',10); //Set the base font & size
				if(count($monthyearArray) > 1)
				{
					//Cell function gets two parameters:Width & Height of the Cell
					$pdf->Cell(50,3,"Group Account");
					$pdf->Ln();//Creates a new line, blank here
					for($m=1;$m<count($monthyearArray);$m++)
					{
						$pdf->Ln();
						$thisGroupMonth = $monthyearArray[$m];
						//array_push($allMonths,$thisGroupMonth);
						$groupACInfo = $this->model->groupAccount($groupId,$thisGroupMonth,$groupMinMonthInfo);
						$date = $groupACInfo[0];
						$description = $groupACInfo[1];
						$payment = $groupACInfo[2];
						$receipt = $groupACInfo[3];
						$balance = $groupACInfo[4];
						
						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(200,5,"Group Account Statement for ".date('F Y',$thisGroupMonth));
						$pdf->Ln();
						if($m==1)
						{
							$pdf->SetFont('Arial','B',5);
							$pdf->Cell(200,5,"Total Balance of group is £".$groupBalance);
							$pdf->Ln();
						}
						if(count($groupACInfo[0])!=0)
						{
							$pdf->SetFont('Arial','B',8);
							$pdf->Cell(10,5,"Date");
							$pdf->Cell(120,5,"Description");
							$pdf->Cell(20,5,"Payments(£)");
							$pdf->Cell(20,5,"Receipts(£)");
							$pdf->Cell(20,5,"Balance(£)");
							$pdf->Ln();
							$pdf->Cell(200,3,'-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------');
							$pdf->Ln();
							$pdf->SetFont('Arial','',6);
							//MultiCell
							 for($a=0;$a<count($date);$a++) 
							 {
								if($a%2==0){
									$bgColor="#151515";
								} else {
									$bgColor="#000000";
								}
								$amt_dt = date('d M',$date[$a]);
								$amt_desc = $description[$a];
								$ac_payment = number_format($payment[$a], 2, '.', ',');
								if($ac_payment==0)
								{
									$ac_payment = '-';
								}
								$ac_receipt = number_format($receipt[$a], 2, '.', ',');
								if($ac_receipt==0)
								{
									$ac_receipt = '-';
								}
								$ac_balance = number_format($balance[$a], 2, '.', ',');
								if($ac_balance==0)
								{
									$ac_balance = '-';
								}
								$pdf->Cell(10,3,$amt_dt);
								$pdf->Cell(120,3,$amt_desc);
								$pdf->Cell(20,3,$ac_payment);
								$pdf->Cell(20,3,$ac_receipt);
								$pdf->Cell(20,3,$ac_balance);
								$pdf->Ln();
							 }
						}
					}
				}
				else
				{
					$pdf->Cell(200,5,"No Transaction Found");
				}
				
				 $pdf->Output("Group_Account","I");
			}
			else if($viewStatus =='print')
			{
				$allMonthYear = $_POST['allMonthYear'];
				$monthyearArray = explode("_",$allMonthYear);
				if(count($monthyearArray) > 1)
				{
					$allMonths = array();
					$allInfo = array();
					for($m=1;$m<count($monthyearArray);$m++)
					{
						$thisGroupMonth = $monthyearArray[$m];
						array_push($allMonths,$thisGroupMonth);
						$groupACInfo = $this->model->groupAccount($groupId,$thisGroupMonth,$groupMinMonthInfo);
						array_push($allInfo,$groupACInfo);
					}
					$content_status="groupACPrint";
					include('app/view/includes/content.inc.php');
				}
				else
				{
					echo 'No Transcation Found.';
				}
			}
			else
			{
				if(empty($viewStatus))
				{
					$viewStatus = 'view';
				}
				$this->groupAccount($groupId,$groupMonth,NULL,NULL,$viewStatus);
			}
		}
		function prevNextMonthInfo($groupId)
		{
			//activated_dt
			$prevNextMonthInfo = array();
			$groupMonthInfo = array();
			$allInfo = $this->model->getResult("group_AC"," MAX(created_dt) as maxBillDate,MIN(created_dt) as minBillDate ","  WHERE group_id='$groupId'  AND active='y' ","");
			if($allInfo[0]['minBillDate']!=NULL && $allInfo[0]['maxBillDate']!=NULL)
			{
				array_push($groupMonthInfo,strtotime($allInfo[0]['maxBillDate']));
				array_push($groupMonthInfo,strtotime($allInfo[0]['minBillDate']));
			}
			$groupMembersInfo = $this->model->getResult("group_members",""," WHERE group_id ='$groupId' AND  active='y' ","");
			for($m=0;$m<count($groupMembersInfo);$m++)
			{
				$thisMemId = $groupMembersInfo[$m]['id'];
				$allInfo = $this->model->getResult("member_billing"," MAX(created_dt) as maxBillDate,MIN(created_dt) as minBillDate ","  WHERE member_id='$thisMemId' AND active='y' ","");
				if($allInfo[0]['minBillDate']!=NULL && $allInfo[0]['maxBillDate']!=NULL)
				{
					array_push($groupMonthInfo,strtotime($allInfo[0]['maxBillDate']));
					array_push($groupMonthInfo,strtotime($allInfo[0]['minBillDate']));
				}
			}
			$groupMinMonthInfo = min($groupMonthInfo);
			$groupMaxMonthInfo = max($groupMonthInfo);
			
			array_push($prevNextMonthInfo,$groupMinMonthInfo);
			array_push($prevNextMonthInfo,$groupMaxMonthInfo);
			
			return $prevNextMonthInfo;
		}
		function groupAccount($groupId,$groupMonth,$sr_io_type,$sr_io_pagenum,$view)
		{	
			$prevNextMonthInfo = $this->prevNextMonthInfo($groupId);
			
			$groupMinMonthInfo = $prevNextMonthInfo[0];
			$groupMaxMonthInfo = $prevNextMonthInfo[1];
			
			$prevMonthStr = strtotime('-1 month',$groupMonth);
			$nextMonthStr = strtotime('+1 month',$groupMonth);
			
			if( (date('Ym',$prevMonthStr)) < (date('Ym',$groupMinMonthInfo)) )
			{
				$prevMonthStr = $groupMonth;
			}
			//echo $nextMonthStr .'//'. date('d-m-Y H:i:s',$nextMonthStr) .'>'. $groupMaxMonthInfo  .'//'. date('d-m-Y H:i:s',$groupMaxMonthInfo);
			if( (date('Ym',$nextMonthStr)) > (date('Ym',$groupMaxMonthInfo)) )
			{
				$nextMonthStr = $groupMonth;
			}
			
			$monthname = date('F Y',$groupMonth);
			
			$groupACInfo = $this->model->groupAccount($groupId,$groupMonth,$groupMinMonthInfo);
			
			$date = $groupACInfo[0];
			$description = $groupACInfo[1];
			$payment = $groupACInfo[2];
			$receipt = $groupACInfo[3];
			$balance = $groupACInfo[4];
			
			$this->model->groupBalance($groupId,$groupMinMonthInfo,$groupMaxMonthInfo);
			
			$groupBalanceInfo = $this->model->groupAccount($groupId,$groupMaxMonthInfo,$groupMinMonthInfo);
			$groupBalance = $groupBalanceInfo[5][1];
			
			//if(count($date)!=0 && count($description)!=0 && count($payment)!=0 && count($receipt)!=0 && count($balance)!=0)
			{
				if( (!empty($view)) && ($view=='view'))
				{
					$content_status="groupAccount";
					$format = 'noFormat';
					include('app/view/index.php');
				}
				else
				{
					$content_status="groupAccount";
					$groupACStyle = 'height:400px;overflow:scroll;';
					include('app/view/includes/content.inc.php');
				}
			}
			/*else
			{
				echo "<h2>Group Account</h2><br/>No Transaction found.";
			}*/
		}
		function group_fc_page($groupId)
		{
			if(empty($groupId) || !isset($groupId))
			{
				if(isset($_REQUEST['param1']))
				{
					$groupId=$_REQUEST['param1'];
				}
				else
				{
					$groupId=$_SESSION['fcgroup'];
				}
			}
			$location=$this->model->breadcrumb($groupId,'G',14,'F');
			if(isset($_REQUEST['param2']))
			{
				$groupMonth = $_REQUEST['param2'];
			}
			else
			{
				$groupMonth = strtotime("now");
			}
			if(isset($_REQUEST['pagenum']))
			{
				$sr_io_pagenum= $_REQUEST['pagenum'];
			}
			else
			{
				$sr_io_pagenum =1 ;
			}
			if(isset($_REQUEST['param3']))
			{
				$sr_io_type = $_REQUEST['param3'];
			}
			
			$_SESSION['fcgroup']=$groupId;
			
			$content_status="group_fc_page";
			include('app/view/index.php');
		}
		
		function invoice_payment($groupId,$groupMonth,$sr_io_type)
		{
			$content_status="invoice_payment";
			include('app/view/includes/content.inc.php');
		}
		function single_in()
		{	
			$groupId=$_POST['groupId'];
			$groupMonth = $_POST['groupMonth'];
			$groumMembersInfo = $this->model->getResult("group_members",""," WHERE group_id='$groupId' AND active='y' ","");
			
			$content_status="create_invoice";
			include('app/view/includes/content.inc.php');
		}
		function regular_InPay()
		{	
			$io_units=$this->model->getResult("units",""," WHERE type2='a/c' AND active='y' ","");
			$io = $_POST['io'];
			$groupId = $_POST['groupId'];
			$groupMonth=$_POST['groupMonth'];
			
			$content_status="regular_io";
			include('app/view/includes/content.inc.php');
		}
		function single_pay()
		{	
			$groupId = $_POST['groupId'];
			$groupMonth = $_POST['groupMonth'];
			$content_status="single_pay";
			include('app/view/includes/content.inc.php');
		}
		function store_single_invoice()
		{
			$allValues=$_POST['allInvoiceVal'];
			$groupId=$_POST['groupId'];
			$groupMonth = $_POST['groupMonth'];
			
			$results=$this->model->store_single_invoice($allValues);
			if($results==0)
			{
				$this->single_in_list($groupId,$groupMonth);
			}
			else
			{
				echo $results;
			}
		}
		
		function store_single_pay()
		{	
			$groupId = $_POST['groupId'];
			$groupMonth = $_POST['groupMonth'];
			$results=$this->model->store_single_pay($_POST);
			if(!empty($results))
			{
				$this->single_pay_list($groupId,$groupMonth);
			}
			else
			{
				echo "Error!!";
			}
		}
		function store_regular_InPay()
		{	
			$groupMonth = $_POST['groupMonth'];
			$groupId = $_POST['groupId'];
			$type = $_POST['regular_type'];
			$results=$this->model->store_regular_InPay($_POST);
			if(!empty($results))
			{
				if($type == 'i')
				{
					$this->model->regularInvoices();
					$this->regular_In_list($groupId,$groupMonth);
				}
				else if($type == 'o')
				{
					$this->regular_Pay_list($groupId,$groupMonth);
				}
			}
			else
			{
				echo "Error!!";
			}
		}
		function single_in_list($groupId,$groupMonth)
		{	
			if(empty($groupId))
			{
				$groupId=$_POST['groupId'];
			}
			if(empty($groupMonth))
			{
				$groupMonth = $_POST['groupMonth'];
			}
			
			$memberBillingInfo = $this->model->getResult("member_billing",""," WHERE invoice_type='m' AND active='y' AND member_id IN (SELECT id FROM group_members WHERE group_id='$groupId' AND active='y') "," ORDER BY created_dt DESC ");
			
			$href="index.php?process=group_fc_page&param1=".$groupId."&param2=".$groupMonth."&param3=si#invoicePayment";
			$paging_html=$this->model->paging($memberBillingInfo,$href,3);
			$startLimit=$paging_html[0];
			$endLimit=$paging_html[1];
			$paging_html_text=$paging_html[2];
			
			$content_status="single_in_list";
			include('app/view/includes/content.inc.php');
		}
		function single_pay_list($groupId,$groupMonth)
		{	
			if(empty($groupId))
			{
				$groupId=$_POST['groupId'];
			}
			if(empty($groupMonth))
			{
				$groupMonth = $_POST['groupMonth'];
			}
			//$location=$this->model->breadcrumb(0,'N',11);
			$memberBillingInfo = $this->model->getResult("group_AC",""," WHERE type='S' AND io_type='o' AND active='y' AND group_id='$groupId' "," ORDER BY created_dt DESC ");
			
			$href="index.php?process=group_fc_page&param1=".$groupId."&param2=".$groupMonth."&param3=so#invoicePayment";
			$paging_html=$this->model->paging($memberBillingInfo,$href,3);
			$startLimit=$paging_html[0];
			$endLimit=$paging_html[1];
			$paging_html_text=$paging_html[2];
			
			$content_status="single_pay_list";
			include('app/view/includes/content.inc.php');
		}
		function regular_In_list($groupId,$groupMonth)
		{
			if(empty($groupId))
			{
				$groupId=$_POST['groupId'];
			}
			
			if(empty($groupMonth))
			{
				$groupMonth = $_POST['groupMonth'];
			}
			
			$memberBillingInfo = $this->model->getResult("group_AC",""," WHERE type='R' AND io_type='i' AND active='y' AND group_id='$groupId' "," ORDER BY created_dt DESC ");
			
			$href="index.php?process=group_fc_page&param1=".$groupId."&param2=".$groupMonth."&param3=ri#invoicePayment";
			$paging_html=$this->model->paging($memberBillingInfo,$href,3);
			$startLimit=$paging_html[0];
			$endLimit=$paging_html[1];
			$paging_html_text=$paging_html[2];
			
			$content_status="regular_in_list";
			include('app/view/includes/content.inc.php');
		}
		function regular_Pay_list($groupId,$groupMonth)
		{
			if(empty($groupId))
			{
				$groupId=$_POST['groupId'];
			}
			if(empty($groupMonth))
			{
				$groupMonth = $_POST['groupMonth'];
			}
			//$location=$this->model->breadcrumb(0,'N',11);
			$memberBillingInfo = $this->model->getResult("group_AC",""," WHERE type='R' AND io_type='o' AND active='y' AND group_id='$groupId' "," ORDER BY created_dt DESC ");
			
			$href="index.php?process=group_fc_page&param1=".$groupId."&param2=".$groupMonth."&param3=ro#invoicePayment";
			$paging_html=$this->model->paging($memberBillingInfo,$href,3);
			$startLimit=$paging_html[0];
			$endLimit=$paging_html[1];
			$paging_html_text=$paging_html[2];
			
			$content_status="regular_pay_list";
			include('app/view/includes/content.inc.php');
		}
		function validDatePeriod()
		{	
			$reccuranceId = $_POST['reccuranceId'];
			$start_Date = $_POST['start_dt'];
			$end_Date = $_POST['end_dt'];
			$reccuranceInfo = $this->model->getResult("units",""," WHERE id='$reccuranceId' ","");
			$value = $reccuranceInfo[0]['value'];
			$nextReccTime = strtotime($start_Date ." +" . $value);
			if($nextReccTime <= strtotime($end_Date))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		function checkRegPayIn()
		{
			$payIn_id=$_POST['payIn_id'];
			$groupACInfo = $this->model->getResult("group_AC",""," WHERE id='$payIn_id'  AND active='y' ","");
			$type_id = $groupACInfo[0]['type_id'];
			$amount = $groupACInfo[0]['amount'];
			$acRegInfo = $this->model->getResult("AC_regular_IO",""," WHERE id='$type_id' ","");
			$thisStartDate =strtotime($acRegInfo[0]['start_date']);
			$thisEndDate =strtotime($acRegInfo[0]['end_date']);
			$todaynow = strtotime("now");
			
			if($thisStartDate < $todaynow)
			{
				if($thisEndDate >= $todaynow)
				{
					echo 1;
				}
				else
				{
					echo 0;
				}
			}
			else
			{
				echo 0;
			}
		}
		function deleteRegPayIn()
		{
			$results=$this->model->deleteRegPayIn($_POST);
			$result = ($results!=0) ? $result=1 : $result=0;
			echo $result;
		}
		function group_mc_page($groupId)
		{
			$sessFlag = 0;
			if(empty($groupId) || !isset($groupId))
			{
				if(isset($_REQUEST['param1']))
				{
					$groupId=$_REQUEST['param1'];
				}
				else if(isset($_SESSION['mcgroup']))
				{
					$groupId=$_SESSION['mcgroup'];
				}
				else
				{
					$sessFlag = 1;
				}
			}
			$location=$this->model->breadcrumb($groupId,'G',14,'M');
			$_SESSION['mcgroup']=$groupId;
			
			if($sessFlag==1)
			{
				$content_status="login";
				include('app/view/index.php');
			}
			else
			{
				$content_status="group_mc_page";
				include('app/view/index.php');
			}
		}
		function maintenance_schedule($groupId)
		{
			$schedule_type = $_REQUEST['param2'];
			$today = date('Y-m-d');
			$calendarSchdules = $this->model->getResult("group_reminder_calendar",""," WHERE group_id='$groupId' AND start_dt <= '$today' AND active='y' "," ORDER BY start_dt  ASC ");
			
			$usagesSchdules  = $this->model->getResult("group_reminder_usage",""," WHERE group_id='$groupId' AND active='y' "," ORDER BY id DESC ");
			
			$content_status="maintenance_schedule";
			include('app/view/includes/content.inc.php');
		}
		function calendar_schdule_list($groupId)
		{
			$calendarSchInfo = $this->model->getResult("group_reminder_calendar",""," WHERE group_id='$groupId' AND active='y' "," ORDER BY start_dt  ASC ");
			
			$href="index.php?process=group_mc_page&param1=".$groupId."&param2=c#maintenanceSchedule";
			$paging_html=$this->model->paging($calendarSchInfo,$href,2);
			$startLimit=$paging_html[0];
			$endLimit=$paging_html[1];
			$paging_html_text=$paging_html[2];
			
			$content_status="calendar_sch_list";
			include('app/view/includes/content.inc.php');
		}
		function usage_schdule_list($groupId)
		{
			$usagesSchInfo = $this->model->getResult("group_reminder_usage",""," WHERE group_id='$groupId' AND active='y' "," ORDER BY id DESC ");
			
			$href="index.php?process=group_mc_page&param1=".$groupId."&param2=u#maintenanceSchedule";
			$paging_html=$this->model->paging($usagesSchInfo,$href,2);
			$startLimit=$paging_html[0];
			$endLimit=$paging_html[1];
			$paging_html_text=$paging_html[2];
			
			$content_status="usage_sch_list";
			include('app/view/includes/content.inc.php');
		}
		function calendar_schdule()
		{
			$groupId=$_POST['groupId'];
			$periodsInfo = $this->model->getResult("units",""," WHERE type3='maintenance' "," ORDER BY value ASC ");
			$content_status="calendar_schdule";
			include('app/view/includes/content.inc.php');
		}
		function usage_schdule()
		{
			$groupId=$_POST['groupId'];
			$usagesInfo = $this->model->getResult("group_usageMonitor",""," WHERE group_id='$groupId'  AND active='y' "," ORDER BY id ASC ");
			$content_status="usage_schdule";
			include('app/view/includes/content.inc.php');
		}
		function store_usage_schdule()
		{	
			$allValues=$_POST['allUsageVals'];
			$results=$this->model->store_usage_schdule($allValues);
			if ($results!=0 || !empty($results) || isset($results)) {
				$this->usage_schdule_list($groupId);
			}
			else
			{
				echo "Error!!";
			}
		}
		function store_calendar_schdule()
		{	
			$allValues=$_POST['allCalendarVals'];
			$results=$this->model->store_calendar_schdule($allValues);
			if ($results!=0 || !empty($results) || isset($results)) {
				$this->calendar_schdule_list($groupId);
			}
			else
			{
				echo "Error!!";
			}
		}
		function delete_usage_schdule()
		{
			$results=$this->model->delete_usage_schdule($_POST);
			$result = ($results!=0) ? $result=1 : $result=0;
			echo $result;
			//echo $_POST['thisDeleteId'];
		}
		function delete_calendar_schdule()
		{
			
			$results=$this->model->delete_calendar_schdule($_POST);
			$result = ($results!=0) ? $result=1 : $result=0;
			echo $result;
			//echo $_POST['thisDeleteId'];
		}
		function dissmissReminder()
		{
			$results=$this->model->dissmissReminder($_POST);
			$result = ($results!=0 && isset($results)) ? $result=1 : $result=0;
			echo $result;
		}
}
?>