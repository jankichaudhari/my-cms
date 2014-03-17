<?php
	class listModel extends commonModel{
		public function __construct($SERVER,$DBASE,$USERNAME,$PASSWORD)
		{
			parent::__construct($SERVER,$DBASE,$USERNAME,$PASSWORD);
		}
		// /* LISTING */ //
		function addOtherOpt($_POST)
		{
			$thisVal = $_POST['thisVal'];
			$userid=$_SESSION['user_id'];
			$current_date=date("Y-m-d H:i:s");
			$query="INSERT INTO taxonomy_categories(name,type,parent_id,user_id, approved,created,modified) VALUES('$thisVal','O','0','$userid','0','$current_date','$current_date')";
			$result=parent::insert($query);
			
			return $result;
		}
		function store_list($catid,$listId,$title)
		{
			$userid=$_SESSION['user_id'];
			$current_date=date("Y-m-d H:i:s");
			
			if(!empty($listId))
			{
				$listingInfo = $this->getResult("listing_items",""," WHERE id='$listId' AND deleted='n' ","");
				$stroedStep = $listingInfo[0]['store_step'];
				$thisStep = 1;
				if($stroedStep > $thisStep)
				{
					$thisStep = $stroedStep;
				}
				$query="UPDATE listing_items SET name='$title', category_id='$catid',store_step='$thisStep', modified='$current_date'  WHERE id='$listId' AND deleted='n' ";	
				$result=parent::update($query);
				$affected_id=$listId;
			}
			else
			{
				$query="INSERT INTO listing_items(name,user_id,category_id,created,modified,store_step,approved,deleted) VALUES('$title','$userid','$catid','$current_date','$current_date','1','0','n')";
				$result=parent::insert($query);
				$affected_id=$result;
			}
			
			return $affected_id;
		}
		function addOtherFees($_POST)
		{
			$thisType = $_POST['thisType'];
			if($thisType=='UsageCharge')
			{
				$type = 'C';
			}
			else if($thisType=='Fees')
			{
				$type = 'F';
			}
			$thisVal = $_POST['thisVal'];
			$current_date=date("Y-m-d H:i:s");
			$query="INSERT INTO listing_units(name,type,approved,created_dt,modified_dt) VALUES('$thisVal','$type','0','$current_date','$current_date')";
			$result=parent::insert($query);
			
			return $result;
		}
		function store_step3($step3All)
		{
			$listId=$_POST['listId'];
			$current_date=date("Y-m-d H:i:s");
			$costPerShare=$step3All['txtCostPerShare'];
			if(empty($costPerShare))
			{
				$costPerShare =0;
			}
			else
			{
				$costPerShare = floatval($costPerShare);
			}
			$currencyShareId=$step3All['currencyShare'];
			
			$selectCountryId=$step3All['selectCountry'];
			$townName=$step3All['townName'];
			if($selectCountryId==225)
			{
				$postcodeIdText = $step3All['postCodeId'];
				$postcodeText = $step3All['selectPostcode'];
				if(empty($townName))
				{
					$townName=$step3All['suggestTown'];
				}
			}
			else
			{
				$postcodeIdText=0;
				$postcodeText = 0;
			}
			
			$groupsize=$step3All['txtGroupSize'];
			if(empty($groupsize))
			{
				$groupsize =0;
			}
			else
			{
				$groupsize = floatval($groupsize);
			}
			$fees=$step3All['txtFees'];
			if(empty($fees))
			{
				$fees =0;
			}
			else
			{
				$fees = floatval($fees);
			}
			$currencyFeesId=$step3All['currencyFees'];
			$durationFeesId=$step3All['durationFees'];
			$currencyUsageId=$step3All['currencyUsage'];
			$usage=$step3All['txtUsage'];
			if(empty($usage))
			{
				$usage =0;
			}
			else
			{
				$usage = floatval($usage);
			}
			$durationUsageId=$step3All['durationUsage'];
			
			if(!empty($listId))
			{
				$listingInfo = $this->getResult("listing_items",""," WHERE id='$listId' AND deleted='n' ","");
				$stroedStep = $listingInfo[0]['store_step'];
				$thisStep = 3;
				if($stroedStep > $thisStep)
				{
					$thisStep = $stroedStep;
				}
				$query="UPDATE listing_items SET store_step='$thisStep',cost_per_share='$costPerShare', cost_per_share_currency='$currencyShareId',country_id='$selectCountryId',postcode_id='$postcodeIdText',postcode='$postcodeText',townName='$townName', group_size='$groupsize', fees='$fees', fees_currency='$currencyFeesId', fees_per='$durationFeesId', usage_charge='$usage', usage_charge_currency='$currencyUsageId', usage_charge_per='$durationUsageId', modified='$current_date'  WHERE id='$listId'  AND deleted='n' ";
				$result=parent::update($query);
			}
			
			return $result;
		}
		function store_step4($_POST)
		{
			$resultArray = array();
			$listId=$_POST['listId'];
			$content=addslashes($_POST['content']);
			$current_date=date("Y-m-d H:i:s");
			if(isset($_POST['finish']) || isset($_POST['finish_x']))
			{	
				$query="UPDATE listing_items SET description='$content',store_step='4', modified='$current_date'  WHERE id='$listId' ";	
				$result=parent::update($query);
				
				//send confirmation email
				if(!empty($result))
				{	
					$userid=$_SESSION['user_id'];
					$r=$this->getResult("auth_users",""," WHERE id = '$userid' AND active='y' ","");
					$user=$r[0]['username'];
					$emailid=$r[0]['email'];
					
					$listingInfo = $this->getResult("listing_items",""," WHERE id='$listId' AND approved='0' AND deleted='n' ","");
					if(count($listingInfo) != 0)
					{	
						$fees_per = $listingInfo[0]['fees_per'];	
						$feesPerInfo = $this->getResult("listing_units",""," WHERE id='$fees_per' ","");
						$feesUnitActive = $feesPerInfo[0]['approved'];
						$usage_charge_per = $listingInfo[0]['usage_charge_per'];	
						$usagechargePerInfo = $this->getResult("listing_units",""," WHERE id='$usage_charge_per' ","");
						$usageUnitActive = $usagechargePerInfo[0]['approved'];
						$category_id = $listingInfo[0]['category_id'];	
						$categoryInfo=$this->getResult("taxonomy_categories",""," WHERE id='$category_id' ","");
						$categoryActive = $categoryInfo[0]['approved'];
						
							$listingTitle = $listingInfo[0]['name'];
							if($feesUnitActive==1 && $usageUnitActive==1 && $categoryActive==1)
							{	
								$parent1_id = $categoryInfo[0]['parent_id'];
								$catName = $categoryInfo[0]['name'];
								if(!empty($parent1_id))
								{
									$list2_id=$display_parent1_list[0]['id'];
									$display_list2=$this->getResult("taxonomy_categories",""," WHERE approved=1 AND parent_id='$parent1_id' ","");
									$display_parent2_list=$this->getResult("taxonomy_categories",""," WHERE approved=1 AND id='$parent1_id' ","");
									
									$list1_id=$display_parent2_list[0]['id'];
									$parent2_id=$display_parent2_list[0]['parent_id'];
									$catName = $display_parent2_list[0]['name']." >  ".$catName;
									if(!empty($parent2_id))
									{
										$list3_id=$display_parent1_list[0]['id'];
										$display_list2=$this->getResult("taxonomy_categories",""," WHERE approved=1 AND parent_id='$parent2_id' ","");
										$display_list3=$this->getResult("taxonomy_categories",""," WHERE approved=1 AND parent_id='$parent1_id' ","");
										$display_parent3_list=$this->getResult("taxonomy_categories",""," WHERE approved=1 AND id='$parent2_id' ","");
										$list1_id=$display_parent3_list[0]['id'];
										$list2_id=$display_parent2_list[0]['id'];
										$catName = $display_parent3_list[0]['name'] ." > " . $catName;
									}
								}
							}
							else
							{
								$catName= 'Not Approved Category';
							}
							
							$country_id = $listingInfo[0]['country_id'];
							$countryInfo = $this->getResult("countries",""," WHERE id='$country_id' ","");
							$listingCountry = $countryInfo[0]['printable_name'];
							$postcode_id = $listingInfo[0]['postcode_id'];
							$postcodeInfo = $this->getResult("postcodes",""," WHERE id='$postcode_id' ","");
							$listingPostcode = $postcodeInfo[0]['postcode'];
							$townName = $listingInfo[0]['townName'];
							$group_size = $listingInfo[0]['group_size'];
							$cost_per_share_currency = $listingInfo[0]['cost_per_share_currency'];
							$costCurrencyInfo = $this->getResult("currencies",""," WHERE id='$cost_per_share_currency' AND approved=1 ","");
							$costCurrency = $costCurrencyInfo[0]['textSymbol'];
							$cost_per_share = $listingInfo[0]['cost_per_share'];
							$fees_currency = $listingInfo[0]['fees_currency'];
							$feesCurrencyInfo = $this->getResult("currencies",""," WHERE id='$fees_currency' AND approved=1 ","");
							$feesCurrency = $feesCurrencyInfo[0]['textSymbol'];
							$fees = $listingInfo[0]['fees'];
							$feesPer = $feesPerInfo[0]['name'];
							$usage_charge_currency = $listingInfo[0]['usage_charge_currency'];
							$usageCurrencyInfo = $this->getResult("currencies",""," WHERE id='$usage_charge_currency' AND approved=1 ","");
							$usageCurrency = $usageCurrencyInfo[0]['textSymbol'];
							$usage_charge = $listingInfo[0]['usage_charge'];
							$usageChargePer = $usagechargePerInfo[0]['name'];
							$listingDescription = $listingInfo[0]['content'];
							
							$from_indul_adminEmail = commonModel :: indulgeon_admin_emailId;
							$from_indul_adminName = commonModel :: indulgeon_admin_name;
							$indulgeon_url = commonModel :: indulgeon_url;
							$emailMsg="This is request to activate following listing: \n *";
							$emailMsg=$emailMsg . $listingTitle."(".$catName.")* \n Location : ";
							$emailMsg=$emailMsg . $townName .",".$listingPostcode.",".$listingCountry." \n Cost of Share : ";
							$emailMsg=$emailMsg . $costCurrency.$cost_per_share ."\n Group Size : ";
							$emailMsg=$emailMsg . $group_size." \n Fees : ";
							$emailMsg=$emailMsg . $feesCurrency . $fees." per " .$feesPer."\n Usage Charge : ";
							$emailMsg=$emailMsg . $usageCurrency.$usage_charge." per " . $usageChargePer ." \n \n \n  Listing Description : ";
							$emailMsg=$emailMsg . $listingDescription." \n \n \n ";
							
							if($feesUnitActive==0 || $usageUnitActive==0 || $categoryActive==0)
							{
								$activateApproval = NULL;
								$activateIds = NULL;
								if($feesUnitActive==0)
								{
									$activateApproval = $activateApproval . ", New Fees Unit";
									$activateId = $activateIds . "&param3=".$fees_per;
								}
								if($usageUnitActive==0)
								{
									$activateApproval = $activateApproval . ", New Usage Charge Unit";
									$activateId = $activateIds . "&param4=".$usage_charge_per;
								}
								if($categoryActive==0)
								{
									$activateApproval = $activateApproval . ", New Category";
									$activateId = $activateIds . "&param5=".$category_id;
								}
								//listing unit not active
								$emailMsg=$emailMsg . "\n Click Here to activate this Listing ".$activateApproval ." \n ".$indulgeon_url."index.php?process=indulgeonAdmin&param1=listingNunit&param2=".$listId.$activateId;
							}
							else
							{
								$emailMsg=$emailMsg . "\n Click Here to activate this Listing ".$indulgeon_url."index.php?process=indulgeonAdmin&param1=listing&param2=".$listId;
							}
							$emailSubject="Listing Confirmation Request";
							
							$mailSentMsg =$this->mailSend($user,$emailid,$from_indul_adminName,$from_indul_adminEmail,$emailSubject,$emailMsg);
							if($mailSentMsg)
							{
								array_push($resultArray,$result);
								array_push($resultArray,0);
							}
							else
							{	
								//email not sent
								$message = "Error!! activation email not sent";
								array_push($resultArray,0);
								array_push($resultArray,$message);
							}
					}
					else
					{	
						//result empty
						$message = "Error!! Listing not saved properly and activation email not sent";
						array_push($resultArray,0);
						array_push($resultArray,$message);
					}
				}
				else
				{
					//result empty
					$message = "Error!! Listing not saved";
					array_push($resultArray,$result);
					array_push($resultArray,$message);
				}
			}
			else
			{
				//$query="UPDATE listing_items SET description1='$desc1',description2='$desc2',store_step='4',modified='$current_date' WHERE id='$listId' AND deleted='n' ";	
				$query="UPDATE listing_items SET description='$content',store_step='4',modified='$current_date' WHERE id='$listId' AND deleted='n' ";	
				$result=parent::update($query);
				//echo $query; exit;
				if(!empty($result))
				{
					array_push($resultArray,$result);
					array_push($resultArray,0);
				}
				else
				{
					//result empty
					$message = "Error!! Listing not saved";
					array_push($resultArray,$result);
					array_push($resultArray,$message);
				}
			}
			return $resultArray;
		}
		function store_uploaded_image($_FILES,$image,$imageId,$uploaddir,$listId,$uploadThumbDir)
		{
			$uploadedImgInfo = array();
			
			if(!empty($imageId))
			{
				$imageId = explode("_", $imageId);
				if($imageId[0]=='list') {
					$image_type='L';
					$image_id=$imageId[1];
				} else if($imageId[0]=='group') {
					$image_type='G';
					$image_id = $imageId[1];
				} else if($imageId[0]=='user') {
					$image_type='U';
					$image_id = $imageId[1];
				}
				
				if($image_id!=0)
				{
					$filename=$image . "_" . $image_id;
				}
				
				$current_date=date("Y-m-d H:i:s");
				
				$error = 0;
				if(!empty($_FILES) && empty($listId))
				{	
					$imageName = $_FILES[$image]["name"];
					$filetype = strtolower(substr(strrchr($imageName, '.'), 1));
					$userImgPath = 'public/images/tmp/assets/' . $imageName;
					$userThumbImgPath='public/images/tmp/thumbnails/' . $imageName;
					$userImgPath=$_FILES[$image]["tmp_path"];
					$tempPath=$_FILES[$image]["tmp_name"];
					$imageSize=$_FILES[$image]["size"];
					
					
					$size=($imageSize / 1024);	//size in KB
					$fullFilePath = $tempPath;
					if(isset($userImgPath) && ($image_type=='U' || $image_type=='G'))
					{
						$fullFilePath = $userImgPath;
					}
					list($width, $height, $type, $attr) = getimagesize($fullFilePath);
					$filepath=$uploaddir . $filename . "." .$filetype;
					
					if($size<2048)
					{
						if (!file_exists($uploaddir)) 
						{
							mkdir($uploaddir,0777);
						}
						if (!file_exists($uploadThumbDir)) 
						{
							mkdir($uploadThumbDir,0777);
						}
						
						if(isset($userImgPath) && isset($userThumbImgPath) && ($image_type=='U' || $image_type=='G'))
						{
							if( copy($userImgPath, $uploaddir.$filename. "." .$filetype) && copy($userThumbImgPath, $uploadThumbDir.$filename. "." .$filetype)) 
							{	
								unlink($userImgPath.$imageName);
								unlink($userThumbImgPath.$imageName);
							}
							else
							{	//print_r(error_get_last());
								$error = 1;
							}
						}
						else
						{
							$message="Error!!File cannot Upload.";
							$error = 1;
						}
					}
					else
					{
						$message="Error!!Invalid size!! File cannot Upload.";
						$error = 1;
					}
				}
				else if(!empty($_FILES) && !empty($listId) && $image_type=='L')
				{
					$imageName = $_FILES[$image]["name"];
					$imageSize=$_FILES[$image]["size"];
					$size=($imageSize / 1024);	//size in KB
					$filetype = strtolower(substr(strrchr($imageName, '.'), 1));
					$filepath=$uploaddir . $filename . "." .$filetype;
					list($width, $height, $type, $attr) = getimagesize($filepath);
					$filetype = strtolower(substr(strrchr($imageName, '.'), 1));
				}
				else if($image_type=='G' && empty($_FILES))
				{	
					$mainFileName = "mainImage_".$listId;	echo $mainFileName;
					$db_res_img=$this->getResult("listing_photos",""," WHERE type='L' AND type_id='$listId' AND file_name='$mainFileName' AND active='y' ","");
					
					$size=$db_res_img[0]['file_size'];
					$width=$db_res_img[0]['image_width'];
					$height=$db_res_img[0]['image_height'];
					
					$old_file=$db_res_img[0]['file_path'];
					$filetype=$db_res_img[0]['file_type'];
					$filepath = $uploaddir.$filename. "." .$filetype;
					/*if (!copy($old_file, $filepath)) 
					{
						$error = 1;
					}*/
					$thumbListImgPath = "public/images/listing/thumbs/" . $mainFileName .'.'.$filetype;
					$thumbGroupImgPath = $uploadThumbDir.$filename. "." .$filetype;
					/*if (!copy($thumbListImgPath, $thumbGroupImgPath)) 
					{
						$error = 1;
					}*/
				}
				
				$db_res=$this->getResult("listing_photos",""," WHERE file_name='$filename' AND type='$image_type' AND active='y' ","");
				$old_name=$db_res[0]['file_name'];
				$id=$db_res[0]['id'];
				
				if($error==0)
				{
					if(count($db_res)==1)
					{
						$query="UPDATE listing_photos SET type_id='$image_id',file_name='$filename', file_path='$filepath',file_type='$filetype',file_size='$size',image_width='$width',image_height='$height',modified='$current_date' WHERE file_name='$old_name'";
						$result=parent::update($query);
						$affected_id=$id;
					}
					else
					{	
						$query="INSERT INTO listing_photos(type,type_id, file_name,file_path, file_type, file_size, image_width, image_height, created,modified,active) 
										VALUES ('$image_type','$image_id','$filename','$filepath','$filetype','$size','$width','$height','$current_date','$current_date','y')";
						
						$result=parent::insert($query);
						$affected_id=$result;
					}
				}
				else
				{
					$affected_id = 0;
				}
			}
			else
			{
				$message="Error!!File cannot Upload.";
				$affected_id = 0;
			}
			array_push($uploadedImgInfo,$affected_id);
			array_push($uploadedImgInfo,$message);
			//print_r($uploadedImgInfo); exit;
			return $uploadedImgInfo;
		}
		function deleteImage($id,$imagePath,$thumbImgPath)
		{	//echo $imagePath .$thumbImgPath; exit;
			unlink($imagePath);
			unlink($thumbImgPath);
			$query="DELETE FROM listing_photos WHERE id='$id'  AND active='y' ";
			$result=parent::delete($query);
			//print_r($result);
			
			return $result;
		}
		function imageSize($h,$w,$realWidth,$realHeight)	//returns  required size of original image
		{
			//Resize height and width for required size
			if($realWidth > $w || $realHeight > $h)
			{
				if($realHeight > $realWidth)
				{
					$imgWidth=($realWidth/$realHeight) * $h;
					$imgHeight=$h;
				}
				else
				{
					$imgHeight=($realHeight/$realWidth) * $w;
					$imgWidth=$w;
				}
				
				// Again resize height and width if any of them is bigger then required height or width
				if($imgWidth > $w || $imgHeight > $h)
				{
					if($imgWidth > $w)
					{
						$imgHeight=($w/$imgWidth)*$realHeight;
						$imgWidth=$w;
					}
					else
					{
						$imgWidth=($h/$imgHeight)*$imgWidth;
						$imgHeight=$h;
					}
				}
			}
			else
			{
				$imgWidth=$realWidth;
				$imgHeight=$realHeight;
			}
			$measure=array($imgWidth,$imgHeight);
			return $measure;
		}
		// /* LISTING */ //
	}
?>