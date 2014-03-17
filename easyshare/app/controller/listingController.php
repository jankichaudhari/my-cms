	<?php
	class listController{
	
		public $model;									
		function __construct($modelObj)		//Creating Model Object
		{
			$this->model=$modelObj;
		}
		
		// /* INDULGEON ADMIN */ //		
		function indulgeonAdmin($requesttype)
		{
			$requesttype = $_REQUEST['param1'];
			
			$location=$this->model->breadcrumb(0,'L',1,0);
			$result = $this->model->indulgeonAdmin($requesttype);
			if(empty($result))
			{
				$message = "<span class='error'>Error!! ".$requesttype." not activated</span>";
			}
			else
			{
				$message = "<span class='required'>".$requesttype."  activated successfully..</span>";
			}
			$content_status="indulgeonAdmin";
			include('app/view/index.php');
		}
		// /*INDULGEON ADMIN */ //		
		
		// /* LISTING PAGE OR STEP1 */ //		
		function list1($parent_id)		//Displays First Select box for categories
		{
			$location=$this->model->breadcrumb(0,'L',3,0);
			$display_list1=$this->model->getResult("taxonomy_categories",""," WHERE type='N' AND approved=1 AND parent_id='$parent_id' ","");
			if(count($display_list1)==0)
			{
				$cat_id=$parent_id;
			}
			$content_status="step1";
			include('app/view/includes/content.inc.php');
		}
		
		function edit_record()
		{
			$listId = $_POST['listId'];
			if(empty($listId))
			{
				$listId = $_REQUEST['param1'];
			}
			$location=$this->model->breadcrumb(0,'L',3,0);	
			
			if(!empty($listId))
			{
				$display_title=$this->model->getResult("listing_items",""," WHERE id='$listId' AND deleted='n' ","");
				$title=$display_title[0]['name'];
				$cat_id=$display_title[0]['category_id'];
				$storedStep=$display_title[0]['store_step'];
				if(empty($storedStep))
				{
					$storedStep=1;
				}
			}	
			else
			{
				$storedStep=1;
			}
			$display_list1=$this->model->getResult("taxonomy_categories",""," WHERE type='N' AND approved='1' AND parent_id=0 ","");
			$display_parent1_list=$this->model->getResult("taxonomy_categories",""," WHERE id='$cat_id' ","");
			$list1_id=$display_parent1_list[0]['id'];
			$parent1_id=$display_parent1_list[0]['parent_id'];
			$list1_type=$display_parent1_list[0]['type'];
			$list1_approved=$display_parent1_list[0]['approved'];
			
			if($list1_type=='N' && $list1_approved==1)
			{
				if(!empty($parent1_id))
				{
					$list2_id=$display_parent1_list[0]['id'];
					$display_list2=$this->model->getResult("taxonomy_categories",""," WHERE approved='1' AND parent_id='$parent1_id' ","");
					$display_parent2_list=$this->model->getResult("taxonomy_categories",""," WHERE approved='1' AND id='$parent1_id' ","");
					$list1_id=$display_parent2_list[0]['id'];
					$parent2_id=$display_parent2_list[0]['parent_id'];
					
					if(!empty($parent2_id))
					{
						$list3_id=$display_parent1_list[0]['id'];
						$display_list2=$this->model->getResult("taxonomy_categories",""," WHERE approved='1' AND parent_id='$parent2_id' ","");
						$display_list3=$this->model->getResult("taxonomy_categories",""," WHERE approved='1' AND parent_id='$parent1_id' ","");
						$display_parent3_list=$this->model->getResult("taxonomy_categories",""," WHERE approved='1' AND id='$parent2_id' ","");
						$list1_id=$display_parent3_list[0]['id'];
						$list2_id=$display_parent2_list[0]['id'];
					}
				}
			}
			else if($list1_type=='O')
			{
				$display_list2=$this->model->getResult("taxonomy_categories",""," WHERE type='O' AND ((approved='1' AND parent_id=0) OR (approved='0' AND id='$cat_id')) ","");
				$list1_id = 0;
				$list2_id = $cat_id;
			}
			
			//$page = ($_REQUEST['param2'] == "content" || $content == "content") ? $page="includes/content.inc" : $page="index"; 
			$content_status="step1";
			include('app/view/index.php');
		}
		
		function list2($parent_id,$listId)			//Display Second  Select box for categories if  necessory
		{
			$location=$this->model->breadcrumb(0,'L',3,0);
			
			if(isset($parent_id))
			{
				if($parent_id==0)
				{	
					
					$display_list2=$this->model->getResult("taxonomy_categories",""," WHERE type='O' AND approved='1' AND parent_id=0 ","");
					if(empty($listId))
					{
						$otherValId = $_REQUEST['param3'];
						$txtValue = '0';
						$txtName = 'Other';
						$approve = 'n';
						if(!empty($otherValId))
						{
							$otherValInfo = $this->model->getResult("taxonomy_categories",""," WHERE id='$otherValId' ","");
							$txtValue = $otherValId;
							$txtName = $otherValInfo[0]['name'];
						}
					}
				}
				else if(!empty($parent_id))
				{
					$display_list2=$this->model->getResult("taxonomy_categories",""," WHERE approved='1' AND parent_id='$parent_id' ","");
					/*if(empty($listId))
					{
						$approve = 'y';
					}*/
				}
				
				if(count($display_list2)==0)
				{
					$cat_id=$parent_id;
					echo 1;
				}
				else
				{
					$content_status="step1";
					include('app/view/includes/content.inc.php');
				}
			}
			
		}
		function list3($parent_id,$listId)			//Display Second  Select box for categories if  necessory
		{
			$location=$this->model->breadcrumb(0,'L',3,0,0);
			
			if(!empty($parent_id))
			{
				$display_list3=$this->model->getResult("taxonomy_categories",""," WHERE approved='1' AND parent_id='$parent_id' ","");
				if(count($display_list3)==0)
				{
					$cat_id=$parent_id;
					echo 1;
				}
				else
				{
					$cat_id=$parent_id;
					$content_status="step1";
					include('app/view/includes/content.inc.php');
				}
			}
			else
			{
				echo 2;
			}
		}
		function addOtherOpt()
		{
			$thisVal = $_POST['thisVal'];
			$thisOption=$this->model->getResult("taxonomy_categories",""," WHERE name='$thisVal' AND approved='1' ","");
			$thisId = $thisOption[0]['id'];
			$parent1_id = $thisOption[0]['parent_id'];
			$thisName = $thisOption[0]['name'];
			if(count($thisOption)!=0)
			{
				if(!empty($parent1_id))
				{
					$list2_id=$display_parent1_list[0]['id'];
					$display_list2=$this->model->getResult("taxonomy_categories",""," WHERE approved='1' AND parent_id='$parent1_id' ","");
					$display_parent2_list=$this->model->getResult("taxonomy_categories",""," WHERE approved='1' AND id='$parent1_id' ","");
					$list1_id=$display_parent2_list[0]['id'];
					$parent2_id=$display_parent2_list[0]['parent_id'];
					$thisName = $display_parent2_list[0]['name']." >  ".$thisName;
					if(!empty($parent2_id))
					{
						$list3_id=$display_parent1_list[0]['id'];
						$display_list2=$this->model->getResult("taxonomy_categories",""," WHERE approved='1' AND parent_id='$parent2_id' ","");
						$display_list3=$this->model->getResult("taxonomy_categories",""," WHERE approved='1' AND parent_id='$parent1_id' ","");
						$display_parent3_list=$this->model->getResult("taxonomy_categories",""," WHERE approved='1' AND id='$parent2_id' ","");
						$list1_id=$display_parent3_list[0]['id'];
						$list2_id=$display_parent2_list[0]['id'];
						$thisName = $display_parent3_list[0]['name'] ." > " . $thisName;
					}
				}
				$thisName = $thisName . '  &nbsp;Already Exist...';
				echo "_exi_".$thisName;
			}
			else
			{
				$result=$this->model->addOtherOpt($_POST);
				if(!empty($result))
				{
					echo "_new_".$result;
				}
				else
				{
					echo 0;
				}
			}
		}
		function store_step1($listId)
		{
			$catid=$_POST['catId'];
			$title=$_POST['txtTitle'];
			$listId=$_POST['listId'];
			
			$result=$this->model->store_list($catid,$listId,$title);
			if(!empty($result))
			{
				//$this->show_step2($listId,$msg);
				header('Location:index.php?process=show_step2&param1='.$result);
			}
			else
			{
				$msg="Error!!";
				$content_status="step1";
				include('app/view/index.php');
			}
		}
		// /* LISTING PAGE OR STEP 1 */ //
		
		// /*  STEP2 PAGE */ //
		function store_step2()
		{
			$listId = $_POST['listId'];
			$current_date = date('Y-m-d H:i:s');
			$listingInfo = $this->model->getResult("listing_items",""," WHERE id='$listId' AND deleted='n' ","");
			$stroedStep = $listingInfo[0]['store_step'];
			$thisStep = 2;
			if($stroedStep > $thisStep)
			{
				$thisStep = $stroedStep;
			}
			$query="UPDATE listing_items SET store_step='$thisStep', modified='$current_date'  WHERE id='$listId' AND deleted='n' ";	
			$result=$this->model->update($query);
			
			//$this->show_step3($list_id,NULL);
			header('Location:index.php?process=show_step3&param1='.$listId);
		}
		
		function show_step2($list_id,$message)
		{	
			if(empty($list_id))
			{
				$list_id = $_POST['listId'];
				if(empty($list_id))
				{
					$list_id=$_REQUEST['param1'];
				}
			}
			
			$param2 = $_REQUEST['param2'];
			if(empty($message) && !empty($param2))
			{
				$message = $param2;
			}
			
			$location=$this->model->breadcrumb(0,'L',4,0);
			if(empty($list_id) || $list_id==0)
			{
				$list_id=$_POST['listId'];
			}
			$listInfo=$this->model->getResult("listing_items",""," WHERE id='$list_id'  AND deleted='n' ","");
				$listingname=$listInfo[0]['name'];
				$storedStep=$listInfo[0]['store_step'];
				if(empty($storedStep))
				{
					$storedStep=1;
				}
				
			for($i=0;$i<9;$i++)
			{
				if($i==0)
				{
					$file="mainImage_" . $list_id;
				}
				else
				{
					$file="image" . $i ."_" . $list_id;
				}
				
				$display_list0=$this->model->getResult("listing_photos",""," WHERE type_id='$list_id' AND type='L' AND file_name='$file' AND active='y' ","");
				
				if(count($display_list0)!=0)
				{
					$img_id="img_id" . $i;
					$$img_id=$display_list0[0]['id'];
					$img_name="img_name" . $i;
					$$img_name=$display_list0[0]['file_name'];
					$img_path="img_path" . $i;
					$$img_path=$display_list0[0]['file_path'];
					$img_width="img_width" . $i;
					$$img_width=$display_list0[0]['image_width'];
					$img_height="img_height" . $i;
					$$img_height=$display_list0[0]['image_height'];
					$img_type="img_type" . $i;
					$$img_type=$display_list0[0]['file_type'];
					
					if($i == 0)
					{
						$w=315;
						$h=225;
					}
					else if($i>0 && $i < 3)
					{
						$w=100;
						$h=75;
					}
					else
					{
						$w=80;
						$h=55;
					}
					
					$finalPath="finalPath" . $i;
					//$$finalPath = $this->list_image_resized($$img_path,$w,$h,"public/images/listing/assets/");
					$$finalPath = 'public/images/listing/thumbs/'.$$img_name.'.'.$$img_type;
					$thisHeight="thisHeight" . $i;
					$thisWidth="thisWidth" . $i;
					list($$thisWidth,$$thisHeight,$type,$attr)  = getimagesize($$finalPath);
				}
				
			}
			
			$content_status="step2";
			include('app/view/index.php');
		}
		function checkFileType()
		{
			//$location=$this->model->breadcrumb(0,'L',4,0);
			$image=$_REQUEST['images'];
			$listId=$_REQUEST['listId'];
			$imageId='list_' . $listId;
			if(!empty($listId))
			{
				$uploaddir="public/images/listing/assets/";
				$uploadThumbDir="public/images/listing/thumbs/";
				if ($_FILES[$image]["error"] > 0)
				{
					$message="Image Error : " . $_FILES[$image]["error"] ;
				}
				else
				{	
					if($image == "mainImage")
					{
						$w=315;
						$h=225;
					}
					else if($image == "image1" || $image == "image2" || $image == "image3")
					{
						$w=100;
						$h=75;
					}
					else
					{
						$w=80;
						$h=55;
					}
					
					if (!file_exists($uploaddir)) 
					{
						mkdir($uploaddir,0777);
					}
					if (!file_exists($uploadThumbDir)) 
					{
						mkdir($uploadThumbDir,0777);
					}
					
					$imageName = $_FILES[$image]["name"];
					$filetype = strtolower(substr(strrchr($imageName, '.'), 1));
					$filename=$image . "_" . $listId;
					$filepath=$uploaddir . $filename . "." .$filetype;
					$tempPath=$_FILES[$image]["tmp_name"];
					$imageSize=$_FILES[$image]["size"];
					$size=($imageSize / 1024);	//size in KB
					if($size<2048)
					{
						if(is_uploaded_file($tempPath))
						{
							move_uploaded_file($tempPath,$uploaddir.$filename. "." .$filetype);
						}
						$uploadedImagePath = $uploaddir.$filename. "." .$filetype;
						list($thisImgWidth,$thisImgHeight) = getimagesize($uploadedImagePath); 
						list($x1,$y1)=$this->model->imageSize($h,$w,$thisImgWidth,$thisImgHeight);
						$thumbPathDir = $this->list_image_resized($uploadedImagePath,$x1,$y1,"listing/thumbs/","listing/assets/");
						$uploadedResult=$this->model->store_uploaded_image($_FILES,$image,$imageId,$uploaddir,$listId,$uploadThumbDir);
						$affected_id = $uploadedResult[0];
						$affected_msg = $uploadedResult[1];
						if(empty($affected_id) || isset($affected_msg))
						{
							$message="Error!! Invalid Image";
						}
					}
					else
					{
						$message="Error!! Invalid Image";
					}
				}
				header('Location:index.php?process=show_step2&param1=' . $listId.'&param2='.$message);
			}
			else
			{
				$message="Error!!";
				$this->show_step2($listId);
			}
		}
		function deleteImage($imageId)
		{	
			//$location=$this->model->breadcrumb(0,'L',4,0);
			if(empty($imageId)){
				$imageId=$_POST['imageId'];
			}
			$dis_list=$this->model->getResult("listing_photos"," * "," WHERE id='$imageId' AND active='y' ","");
			$affected_list_id=$dis_list[0]['type_id'];
			$affected_list_file_path=$dis_list[0]['file_path'];
			$affected_list_file_name=$dis_list[0]['file_name'].".".$dis_list[0]['file_type'];
			$affected_list_type=$dis_list[0]['type'];
			$affected_list_thumb_file_path = '';
			switch($affected_list_type)
			{
				case 'L' : $affected_list_thumb_file_path ='public/images/listing/thumbs/'.$affected_list_file_name;
				break;
				case 'G' : $affected_list_thumb_file_path ='';
				break;
				case 'U' : $affected_list_thumb_file_path ='';
				break;
			}
			$result=$this->model->deleteImage($imageId,$affected_list_file_path,$affected_list_thumb_file_path);
			
			if(count($result)==0)
			{
				//$this->show_step2($affected_id);
				echo $affected_list_id . "-" . "error";
			}
			else
			{
				echo $affected_list_id . "-" ;
				//$this->show_step2($affected_list_id);
			}
		}
		// /*  STEP2 PAGE */ //
		
		// /* LISTING STEP3 PAGE */ //
		function calculator()
		{
			$content_status="calculator";
			$format = 'noFormat';
			include('app/view/index.php');
		}
		function show_step3($listId,$message)
		{
			if(empty($listId))
			{
				$listId = $_POST['listId'];
				if(empty($listId))
				{
					$listId = $_REQUEST['param1'];
				}
			}
			$location=$this->model->breadcrumb(0,'L',5,0);
			$countryList=$this->model->countryList();
			$currencyList=$this->model->currencyList();
			
			$display_list=$this->model->getResult("listing_items",""," WHERE id='$listId'  AND deleted='n' ","");
			if(count($display_list)!=0)
			{
				$listingname=$display_list[0]['name'];
				$storedStep=$display_list[0]['store_step'];
				if(empty($storedStep))
				{
					$storedStep=1;
				}
				
				$costPerShare=$display_list[0]['cost_per_share'];
				$currencyShareId=$display_list[0]['cost_per_share_currency'];
				$countryId=$display_list[0]['country_id'];	
				$postcodeId=$display_list[0]['postcode_id'];
				$postcode=$display_list[0]['postcode'];
				$townname=$display_list[0]['townName'];
				$groupsize=$display_list[0]['group_size'];
				$fees=$display_list[0]['fees'];
				$currencyFeesId=$display_list[0]['fees_currency'];
				$durationFeesId=$display_list[0]['fees_per'];
				$currencyUsageId=$display_list[0]['usage_charge_currency'];
				$usage=$display_list[0]['usage_charge'];
				$durationUsageId=$display_list[0]['usage_charge_per'];
			}
			
			$listingFees=$this->model->getResult("listing_units",""," WHERE type!='C' AND (( id='$durationFeesId' AND approved='0') OR approved='1')  "," ORDER BY id");
			$listingCharge=$this->model->getResult("listing_units",""," WHERE type!='F' AND (( id='$durationUsageId' AND approved='0') OR approved='1')  "," ORDER BY id");
			
			$content_status="step3";
			include('app/view/index.php');
		}
		function addOtherOption()
		{
			$thisVal = $_POST['thisVal'];
			$thisType = $_POST['thisType'];
			if($thisType=='UsageCharge')
			{
				$notType = 'F';
			}
			else if($thisType=='Fees')
			{
				$notType = 'C';
			}
			$thisOption=$this->model->getResult("listing_units",""," WHERE name='$thisVal' AND type!='$notType'  AND approved='1' ","");
			if(count($thisOption)!=0)
			{
				$thisId = $thisOption[0]['id'];
				echo "_exi_".$thisId;
			}
			else
			{
				$result=$this->model->addOtherFees($_POST);
				if(!empty($result))
				{
					echo "_new_".$result;
				}
				else
				{
					echo 0;
				}
			}
		}
		function checkPostCode()
		{
			$thisValue = strtoupper($_POST['postcodeVal']);
			$postCodeInfo=$this->model->getResult("postcodes",""," WHERE postcode='$thisValue' ","");
			
			if(count($postCodeInfo)!=0)
			{
				$postcode = $postCodeInfo[0]['id'];
				$town = $postCodeInfo[0]['town'];
				echo $postcode."_".$town;
			}
			else
			{
				echo 0;
			}
			
		}
		function store_step3()
		{
			$listId = $_POST['listId'];
			if(!empty($listId))
			{
				$result=$this->model->store_step3($_POST);
				if(!empty($result))
				{
					$this->show_step4($listId,$msg);
				}
				else
				{
					$msg="Error!!";
					$this->show_step3($listId,$msg);
				}
			}
			else
			{
				$msg="Error!!";
				$this->show_step3($listId,$msg);
			}
		}
		// /*  STEP3 PAGE */ //
		
		// /*  STEP4 PAGE */ //
		function show_step4($listId,$message)
		{
			if(empty($listId))
			{
				$listId = $_POST['listId'];
				if(empty($listId))
				{
					$listId = $_REQUEST['param1'];
				}
			}
			$location=$this->model->breadcrumb(0,'L',6,0);
			$display_list=$this->model->getResult("listing_items",""," WHERE id='$listId'  AND deleted='n' ","");
			$listingname=$display_list[0]['name'];
			//$description1Text=stripcslashes($display_list[0]['description1']);
			//$description2Text=stripcslashes($display_list[0]['description2']);
			$contentText=stripcslashes($display_list[0]['description']);
			$thisApproved=$display_list[0]['approved'];
			$storedStep=$display_list[0]['store_step'];
			if(empty($storedStep))
			{
				$storedStep=1;
			}
			
			$content_status="step4";
			include('app/view/index.php');
		}
		function store_step4()
		{	
			$listId=$_POST['listId'];
			$finish=$_POST['finish'];
			$submit=$_POST['submit'];
			//echo count($_POST);
			//echo $listId. '-'.$finish.'--'.$submit; exit;
			if((!empty($listId)) && (count($_POST)!=0))
			{
				$resultArray=$this->model->store_step4($_POST);
				
				$result = $resultArray[0];
				$errorMessage = $resultArray[1];
				if(!empty($result))
				{
					?><script type="text/javascript" language=javascript>alert("Your request has been sent..Your listing will be activated soon"); </script> <?php
					header('Location:index.php?process=myAccount#myListing');
				}
				else
				{
					if(empty($errorMessage))
					{
						$errorMessage = 'Error!! Listing not saved properly';
					}
					
					$this->show_step4($listId,$errorMessage);
				}
			}
			else
			{
				$msg = 'Error!! Listing not submitted';
				$this->show_step4($listId,$msg);
			}
		}
		// /*  STEP4 PAGE */ //
		
	}
?>