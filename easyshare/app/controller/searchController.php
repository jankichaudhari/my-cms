<?php
// /* SEARCH PAGE */ //
class searchController extends groupController{

		public $model;									
		function __construct($modelObj)		//Creating Model Object
		{
			$this->model=$modelObj;
		}

		// /*  ADVANCED SEACH PAGE */ //
		function advanced_search_page($keyword,$listing_id,$postdata,$sidebar_search)
		{
			$location=$this->model->breadcrumb(0,'L',15);
			
			$userId=$_SESSION['user_id'];
			
			//param4 not going through for whatever reason...
			if(isset($_REQUEST['param4'])){
				$sidebar_search = $_REQUEST['param4'];
				$_SESSION['sidebar_search'] = $sidebar_search;
			}
			else if(isset($_SESSION['sidebar_search'])){
				$sidebar_search = $_SESSION['sidebar_search'];
			}
			
			//param5 not going through for whatever reason...
			if(isset($_REQUEST['param5'])){
				$cat_search = $_REQUEST['param5'];
				$_SESSION['cat_search'] = $cat_search;
			}
			else if(isset($_SESSION['cat_search'])){
				$cat_search = $_SESSION['cat_search'];
			}
			
			
			if(!empty($userId))
			{
				$userProfileInfo=$this->model->getResult("auth_profile",""," WHERE user_id='$userId'","");
				$spostcode = $userProfileInfo[0]['postcode'];
			}
			
			$categories = $this->model->getResult("taxonomy_categories","id, name"," WHERE type='N' AND approved=1 AND parent_id=0 ","");
			$postdata = stripslashes($postdata);
			$postdata = unserialize($postdata);
			
			/* START SEARCH POST HANDLER*/
			if($_POST || $keyword || $postdata){
				$pagenumber=1;
				if($postdata && !$_POST){
					$scategory = mysql_real_escape_string($postdata['scategory']);
					$ssubcategory = mysql_real_escape_string($postdata['ssubcategory']);
					$spostcode = mysql_real_escape_string($postdata['spostcode']);
					$sdistance = mysql_real_escape_string($postdata['sdistance']);
					$smincost = mysql_real_escape_string($postdata['smincost']);
					$smaxcost = mysql_real_escape_string($postdata['smaxcost']);
					$smingroup = mysql_real_escape_string($postdata['smingroup']);
					$smaxgroup = mysql_real_escape_string($postdata['smaxgroup']);
					$sminfee = mysql_real_escape_string($postdata['sminfee']);
					$smaxfee = mysql_real_escape_string($postdata['smaxfee']);
					$stimeframe_fee = mysql_real_escape_string($postdata['stimeframe_fee']);
					$sminusage = mysql_real_escape_string($postdata['sminusage']);
					$smaxusage = mysql_real_escape_string($postdata['smaxusage']);
					$stimeframe_usage = mysql_real_escape_string($postdata['stimeframe_usage']);
					$pagenumber = $postdata['pagenumber'];
					
					//keyword in the advanced search form, overrides any in query string
					$use_key_search = TRUE;
					if($postdata['keyword'] && strlen($postdata['keyword'])>1){
						$keyword = $postdata['keyword'];
					}
					else if($postdata && $keyword){
						$keyword = '';
						$use_key_search = FALSE;
					}
					
					$postdata['keyword'] = $keyword;
					$_SESSION['search_filters'] = $postdata;
				}
				else{
					$listing_id=0;
					$scategory = mysql_real_escape_string($_POST['scategory']);
					$ssubcategory = mysql_real_escape_string($_POST['ssubcategory']);
					$spostcode = mysql_real_escape_string($_POST['spostcode']);
					$sdistance = mysql_real_escape_string($_POST['sdistance']);
					$smincost = mysql_real_escape_string($_POST['smincost']);
					$smaxcost = mysql_real_escape_string($_POST['smaxcost']);
					$smingroup = mysql_real_escape_string($_POST['smingroup']);
					$smaxgroup = mysql_real_escape_string($_POST['smaxgroup']);
					$sminfee = mysql_real_escape_string($_POST['sminfee']);
					$smaxfee = mysql_real_escape_string($_POST['smaxfee']);
					$stimeframe_fee = mysql_real_escape_string($_POST['stimeframe_fee']);
					$sminusage = mysql_real_escape_string($_POST['sminusage']);
					$smaxusage = mysql_real_escape_string($_POST['smaxusage']);
					$stimeframe_usage = mysql_real_escape_string($_POST['stimeframe_usage']);
					$pagenumber = $_POST['pagenumber'];
					
					//keyword in the advanced search form, overrides any in query string
					$use_key_search = TRUE;
					if($_POST['keyword'] && strlen($_POST['keyword'])>1){
						$keyword = $_POST['keyword'];
					}
					else if($_POST && $keyword){
						$keyword = '';
						$use_key_search = FALSE;
					}
					
					
					$_SESSION['search_filters'] = $_POST;
					$_SESSION['search_filters']['keyword'] = $keyword;
				}
				$_SESSION['search_filters']['listing_id'] = $listing_id;
				
				if($ssubcategory){
					$category_id = $ssubcategory;
				}
				else{
					$category_id = $scategory;
				}
				
				//we can only narrow down and do rest of the filtering based on a set of results (distance etc needs calculating )
				if($category_id){
					$sql_conditions = " category_id = $category_id and ";
				}
				if(is_numeric($smincost)){
					$sql_conditions .= " cost_per_share >= $smincost and ";
				}
				if(is_numeric($smaxcost)){
					$sql_conditions .= " cost_per_share <= $smaxcost and ";
				}
				if(is_numeric($smingroup)){
					$sql_conditions .= " group_size >= $smingroup and ";
				}
				if(is_numeric($smaxgroup)){
					$sql_conditions .= " group_size <= $smaxgroup and ";
				}

				if($keyword && strlen($keyword)>1 && $use_key_search){
					$keyword = mysql_real_escape_string(str_replace(',',' ',$keyword));
					//$sql_conditions .= " name like '%$keyword%' OR description like '%$keyword%' "; //simple, inefficient way...
					$sql_conditions .= " MATCH(name, description) AGAINST ('$keyword') "; //require[s/d] ALTER TABLE `listing_items` ADD FULLTEXT (name,description)
				}


				$sql_conditions = $this->_str_lreplace('and', '', $sql_conditions);
				
				if(strlen($sql_conditions)){
					$sql_conditions = " WHERE $sql_conditions";
															
				}
				$base_results = $this->model->getResult("listing_items",""," $sql_conditions ","");
				
				//echo 'QUERY WITHOUT POST';
				//print_r($base_results);
				
				
				$c1=FALSE;$c2=FALSE;$c3=FALSE;
				
				//fee conditions
				if($stimeframe_fee && ($sminfee || $smaxfee)){
					$stimeframe_fee_days = $this->model->getResult("listing_units","daycount"," where id like $stimeframe_fee ","");
					if(count($stimeframe_fee_days)){
						$sminfee_per_day = $sminfee / $stimeframe_fee_days[0];
						$smaxfee_per_day = $smaxfee / $stimeframe_fee_days[0];
						$c1 = TRUE;
					}
				}
				
				//usage conditions
				if($stimeframe_usage && ($sminusage || $smaxusage)){
					$stimeframe_usage_days = $this->model->getResult("listing_units","daycount"," where id like $stimeframe_usage ","");
					if(count($stimeframe_usage_days)){
						$sminusage_per_day = $sminusage / $stimeframe_usage_days[0];
						$smaxusage_per_day = $smaxusage / $stimeframe_usage_days[0];
						$c2 = TRUE;
					}
				}
				
				//echo 'test1';
				//distance conditions
				if($spostcode && $sdistance<1050000){
					//validate postcode first
					$postcode_elements = split(' ',$spostcode);
					$spostcode = strtoupper($postcode_elements[0]);
					
					
					//echo 'test2';
					//get x,y of the search location
					$postcode_coords = $this->model->getResult("postcodes","x, y"," where postcode like '$spostcode' limit 1","");
					//$postcode_sql2 = "select x, y from postcodes where postcode like '$spostcode limit 1' ";
					if(count($postcode_coords)){
						$sx = $postcode_coords[0]['x'];
						$sy = $postcode_coords[0]['y'];
						//get ids of postcodes within selected distance from the search location
						$filtered_postcodes_res = $this->model->getResult("postcodes","id"," where sqrt(power((x-$sx),2) + power((y-$sy),2))<$sdistance ","");
						for($i=0;$i<count($filtered_postcodes_res);$i++){
							$filtered_postcodes[$i] = $filtered_postcodes_res[$i]['id'];
						}
						//$postcode_sql = "select id from postcodes where sqrt(power((x-$sx),2) + power((y-$sy),2))<$sdistance ";
						$c3 = TRUE;
					}
					else{
						$message ="<div class='error'>Invalid UK postcode - distance search option ignored.</div>";
					}
				}
				
				$base_count = count($base_results);
				$j=0;
				for($i=0;$i<$base_count;$i++){
					if($c1){
						$temp = $base_results[$i]['fees_per'];
						$stimeframe_fee_days_comp = $this->model->getResult("listing_units","daycount"," where id like $temp ","");
						$temp = $base_results[$i]['fees'] / $stimeframe_fee_days_comp[0];
						if($temp < $sminfee_per_day || $temp > $smaxfee_per_day){
							//$ignored .= "<p>C1:<br />
							//$temp < $sminfee_per_day || $temp > $smaxfee_per_day							
							//</p>";
							continue;}
					}
					if($c2){
						$temp = $base_results[$i]['usage_charge_per'];
						$stimeframe_usage_days_comp = $this->model->getResult("listing_units","daycount"," where id like $temp ","");
						$temp = $base_results[$i]['usage_charge'] / $stimeframe_usage_days_comp[0];
						if($temp < $sminusage_per_day || $temp > $smaxusage_per_day){
							//$ignored .= "<p>C2:<br />
							//$temp < $sminusage_per_day || $temp > $smaxusage_per_day							
							//</p>";
							continue;}
					}
					if($c3){
						if(in_array($base_results[$i]['postcode_id'],$filtered_postcodes)){
							$search_results[$j] = $base_results[$i];
							$j++;
						}
						/*else{
							$ignored .= "<p>C3:<br />
							{$base_results[$i]['postcode_id']}
							</p>";
						}*/
					}
					else{
						$search_results[$j] = $base_results[$i];
						$j++;
					}
				}
				
				//finally add images to search results
				
				$resultcount = count($search_results);
				for($i=0;$i<$resultcount;$i++){
                    $temp[$i] = $this->model->getResult("listing_photos","file_path"," where type like 'L' and file_name like 'mainImage%' and type_id like {$search_results[$i]['id']} limit 1","");
					if($temp[$i]){
						$search_results[$i]['image_path'] = $temp[$i][0][0];
						if(!file_exists($temp[$i][0][0])||is_dir($temp[$i][0][0])||$temp[$i][0][0]==''){
							$search_results[$i]['image_path'] = 'public/images/no-image.png';
						}
					}
					else{
						$search_results[$i]['image_path'] = 'public/images/no-image.png';
					}
                    $temp[$i] = $this->model->getResult("countries","iso3"," where id like {$search_results[$i]['country_id']} limit 1",""); //name or iso3
					if($temp[$i]){
						$search_results[$i]['country'] = $temp[$i][0][0];
					}
                }

				//Keep search form prefilled:
				if($ssubcategory){
					$subcategories = $this->model->getResult("taxonomy_categories","id, name"," WHERE type='N' AND approved=1 AND parent_id=$scategory ","");
				}
				
				if(!$pagenumber){$pagenumber=1;}
				if((($pagenumber-1)*10)<$resultcount){
					$offset=($pagenumber-1)*10;
				}
				
				
				/* START RESULTS PAGER*/
				if($resultcount>10){
					//create pager buttons => hidden forms to post
					$pagecount = ceil($resultcount/10);
					
					for($i=1;$i<=$pagecount;$i++ ){
						$formfields = "
							<input type='hidden' name='ssubcategory' value='$ssubcategory' />
							<input type='hidden' name='spostcode' value='$spostcode' />
							<input type='hidden' name='sdistance' value='$sdistance' />
							<input type='hidden' name='smincost' value='$smincost' />
							<input type='hidden' name='smaxcost' value='$smaxcost' />
							<input type='hidden' name='smingroup' value='$smingroup' />
							<input type='hidden' name='smaxgroup' value='$smaxgroup' />
							<input type='hidden' name='sminfee' value='$sminfee' />
							<input type='hidden' name='smaxfee' value='$smaxfee' />
							<input type='hidden' name='stimeframe_fee' value='$stimeframe_fee' />
							<input type='hidden' name='sminusage' value='$sminusage' />
							<input type='hidden' name='smaxusage' value='$smaxusage' />
							<input type='hidden' name='stimeframe_usage' value='$stimeframe_usage' />
							<input type='hidden' name='pagenumber' value='$i' />
							";
						$pager .="<form class='pager_button_form' method='post' action='index.php?process=advanced_search_page'><input type='hidden' name='scategory' value='$scategory' />
							$formfields
							";
						
						if($i==$pagenumber){
							$pager .="<input class='pager_button' type='image' src='public/images/search_pager_activebutton.png' alt='page $i' title='page $i' />";
							//$pager .="<img class='pager_button' src='public/images/search_pager_activebutton.png' alt='page $i' title='page $i' />";
						}
						else{					
							$pager .="<input class='pager_button' type='image' src='public/images/search_pager_button.png' alt='page $i' title='page $i' />";
						}
						$pager .="</form>";
						
						if(($i+1)==$pagenumber){
							$previous_form = "<form class='pager_previous_form' method='post' action='index.php?process=advanced_search_page'><input type='hidden' name='scategory' value='$scategory' />
							$formfields
							<input class='pager_button' type='image' src='public/images/search_pager_arrow_left.png' alt='page $i' title='previous page ($i)' /></form>";
						}
						else if(($i-1)==$pagenumber){
							$next_form = "<form class='pager_previous_form' method='post' action='index.php?process=advanced_search_page'><input type='hidden' name='scategory' value='$scategory' />
							$formfields
							<input class='pager_button' type='image' src='public/images/search_pager_arrow_right.png' alt='page $i' title='next page ($i)' /></form>";
						}
					}
					
				}/* END RESULTS PAGER*/
															
			}/* END SEARCH POST HANDLER*/
			
			if($listing_id){
				$listing_location ='';
				$listing_details = array();
				$listing_images = array();
				$image_count=0;
				$main_images ='';
				$additional_images = array();
				
				$this -> _listing_details($listing_id, &$listing_location, &$listing_details, &$listing_images, &$image_count, &$main_image, &$additional_images);
			}
			
			/* VIEW FORMATTING START*/
			$category_count = count($categories);
			for($i=0;$i<$category_count;$i++){
				$s='';
				if($categories[$i]['id']==$scategory){
					$s = 'selected="selected"';
				}
				$category_options .= "<option value='{$categories[$i]['id']}' $s>{$categories[$i]['name']}</option>";
			}
			
			//IF SUB CATEGORY POSTED, NEED TO LOAD IT BY DEFAULT
			if($ssubcategory){
				$subcategory_count = count($subcategories);
				for($i=0;$i<$subcategory_count;$i++){
					$s='';
					if($subcategories[$i]['id']==$ssubcategory){
						$s = 'selected="selected"';
					}
					$subcategory_options .= "<option value='{$subcategories[$i]['id']}' $s>{$subcategories[$i]['name']}</option>";
				}
			}
			
			$distance_options = $this -> _select_options(array('-- select distance --','within 1 mile','within 10 mile','within 20 mile','within 30 mile','within 40 mile','within 60 mile','within 100 mile','within 200 mile','National'),array(1100000,1609,16093,32187,48281,64374,96561,160935,321880,1050000),$sdistance);
			
			if($stimeframe_fee>5 || $stimeframe_fee<1){
				$stimeframe_fee = 4; //month by default
			}
			$timeframe_fee_options = $this -> _select_options(array('per day','per week','per fortnight','per month','per year'),array(1,2,5,3,4),$stimeframe_fee);
		
			if($stimeframe_usage>5 || $stimeframe_usage<1){
				$stimeframe_usage = 4; //month by default
			}
			$stimeframe_usage_options = $this -> _select_options(array('per day','per week','per fortnight','per month','per year'),array(1,2,5,3,4),$stimeframe_usage);
		
    		/* VIEW FORMATTING END*/
			
			
			
			$content_status="advanced_search";
			include('app/view/index.php');
		}
		
		function sublisting($listing_id,$postdata){
			$userId=$_SESSION['user_id'];
			if($postdata){
				$postdata = stripslashes($postdata);
				$postdata = unserialize($postdata);
			}
			else if($_SESSION['search_filters']){
				$postdata = $_SESSION['search_filters'];
			}
			
			if($listing_id){
				$_SESSION['search_filters']['listing_id'] = $listing_id;
			}
			else if(!$listing_id && $_SESSION['search_filters']['listing_id']){
				$listing_id = $_SESSION['search_filters']['listing_id'];
			}
			
			$listing_location ='';
			$listing_details = array();
			$listing_images = array();
			$image_count=0;
			$main_images ='';
			$additional_images = array();
			
			$this -> _listing_details($listing_id, &$listing_location, &$listing_details, &$listing_images, &$image_count, &$main_image, &$additional_images);
			
			//$tagged = $this->model->is_tagged($listing_id,$userId);
			
			$content_status="subListing";
			include('app/view/insert.php');
		}
		
		
		function subcategory($maincat){
			if($maincat){
				$subcategories = $this->model->getResult("taxonomy_categories","id, name"," WHERE type='N' AND approved=1 AND parent_id=$maincat ","");
			}
			else{
				$subcategories = null;
			}

			$content_status="subCategories";
			include('app/view/insert.php');
		}
		
		function popupimages($listing_id){
			if(!$listing_id && $_SESSION['search_filters']['listing_id']){
				$listing_id = $_SESSION['search_filters']['listing_id'];
			}
			if($listing_id){
				$listing_images = $this->model->getResult("listing_photos","file_path, file_name"," where type like 'L' and type_id like $listing_id","");
				$image_count = count($listing_images);
				
				for($i=0,$j=0;$i<$image_count;$i++){
					if(strpos($listing_images[$i]['file_name'],'mainImage')===0 || $image_count==1){
						$main_image = $listing_images[$i]['file_path'];
					}
					else{
						$additional_images[$j]['thumb'] = $this->_image_to_resized($listing_images[$i]['file_path']);
						$additional_images[$j]['original'] = $listing_images[$i]['file_path'];
						$j++;
					}
				}
			}
			
			$content_status="popupImages";
			include('app/view/popup1.php');
		}
		
		function tellfriend($listing_id, $postdata){
			$done = FALSE;
			if(!$listing_id && $_SESSION['search_filters']['listing_id']){
				$listing_id = $_SESSION['search_filters']['listing_id'];
			}

			if(!$listing_id && !$_POST){
				header("Location: index.php?process=advanced_search_page");
			}
			
			$location=$this->model->breadcrumb(0,'L',17);
			
			$userId=$_SESSION['user_id'];
			
			if(!empty($userId))
			{
				$userProfileInfo=$this->model->getResult("auth_profile",""," WHERE user_id='$userId'","");
				//$spostcode = $userProfileInfo[0]['postcode'];
				$user_email = $userProfileInfo[0]['email'];
			}

			if(!$_POST){
				if($user_email){
					$myemail = $user_email;
				}
			}
						
			if($_POST){
				$listing_id = mysql_real_escape_string($_POST['listing_id']);
				$postdata = mysql_real_escape_string($_POST['postdata']);
				
				//validate emails and send the emails
				$email = mysql_real_escape_string($_POST['email']);
				$friendemail = mysql_real_escape_string($_POST['friendemail']);
				
				if(!$_POST['myname']) {
					$message = "<div class='error'>Please enter your name</div>";
				}
				else {
					$myname = $_POST['myname'];
				}


				if(!filter_var($email, FILTER_VALIDATE_EMAIL) || !filter_var($friendemail, FILTER_VALIDATE_EMAIL)) {
					$message .= "<div class='error'>Please make sure both emails are valid email addresses</div>";
				}
				else if($myname){
					$message = "<div class='success'>Thank you, the listing was sent successfully.</div>";

					$headers .= "Reply-To: $myname <$email>\r\n";
					$headers .= "Return-Path: $myname <$email>\r\n";
					$headers .= "From: Indulgeon.com <no-reply@indulgeon.com>\r\n";
				  
					$headers .= "Organization: Sender Organization\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
					$headers .= "X-Priority: 3\r\n";
					$headers .= "X-Mailer: PHP". phpversion() ."\r\n";
				  
					$subject = "$myname sent you link from Indulgeon.com";
					
					$friendmessage = "Hi,
					
					$myname thought you would be interested in the following:
					
					http://indulgeon.com/index.php?process=listing_display&param1=$listing_id
					
					Please click the above link or copy and paste it in your web browser to view the content.
					
					Best wishes,
					
					Indulgeon.com
					
					This message was sent to you by $myname, $myemail, from indulgeon.com, http://indulgeon.com
					
					";
				  
					mail($friendemail, $subject, $friendmessage, $headers);
					
					$done = TRUE;
				}
			}

			if($postdata){
				$postdata = stripslashes($postdata);
				$postdata = unserialize($postdata);
			}
			else if($_SESSION['search_filters']){
				$postdata = $_SESSION['search_filters'];
			}
			
			if($listing_id){
				$_SESSION['search_filters']['listing_id'] = $listing_id;
			}

			
			
			if(!$email){
				$email = $user_email;
			}


			$content_status="tellFriend";
			include('app/view/index.php');
		}
		
		function listing_display($listing_id){
			$location=$this->model->breadcrumb(0,'L',16);
			
			$userId=$_SESSION['user_id'];
			
			if(!empty($userId))
			{
				//$userProfileInfo=$this->model->getResult("auth_profile",""," WHERE user_id='$userId'","");
			}

			if($listing_id){
				$_SESSION['search_filters']['listing_id'] = $listing_id;
			}
			else if(!$listing_id && $_SESSION['search_filters']['listing_id']){
				$listing_id = $_SESSION['search_filters']['listing_id'];
			}


			$listing_location ='';
			$listing_details = array();
			$listing_images = array();
			$image_count=0;
			$main_images ='';
			$additional_images = array();
			
			$this -> _listing_details($listing_id, &$listing_location, &$listing_details, &$listing_images, &$image_count, &$main_image, &$additional_images);
			
			
			
			$content_status="listing_display";
			include('app/view/index.php');
		}
		
		function noresults_options(){
			$location=$this->model->breadcrumb(0,'L',18);
			
			$userId=$_SESSION['user_id'];
			
			if(!empty($userId))
			{
				$userProfileInfo=$this->model->getResult("auth_profile",""," WHERE user_id='$userId'","");
				//$spostcode = $userProfileInfo[0]['postcode'];
				//$user_email = $userProfileInfo[0]['email'];
			}


			$content_status="noresults_options";
			include('app/view/index.php');
		}
		
		//need to be logged in for this
		function taglisting($listing_id,$postdata, $untag=''){
			$userId=$_SESSION['user_id'];
			
			if($postdata){
				$postdata = stripslashes($postdata);
				$postdata = unserialize($postdata);
			}
			else if($_SESSION['search_filters']){
				$postdata = $_SESSION['search_filters'];
			}
			
			if($listing_id){
				$_SESSION['search_filters']['listing_id'] = $listing_id;
			}
			else if(!$listing_id && $_SESSION['search_filters']['listing_id']){
				$listing_id = $_SESSION['search_filters']['listing_id'];
			}
			
			/*
			 we should be logged in and have postdata and listing_id...
			 so let's tag the listing in db, and display the same page, listing 'tagged' (with popup?).
			*/
			
			if($untag){
				//echo "UNTAG: $listing_id, $userId";
				$result = $this->model->untaglisting($listing_id,$userId);
			}
			else{
				//echo "TAG: $listing_id, $userId";
				$result = $this->model->taglisting($listing_id,$userId);
			}
			if($result){
				//echo $result;
			}
			$content_status="taglisting";
			include('app/view/index.php');
		}
		
		function pdflisting($listing_id){
			$userId=$_SESSION['user_id'];
			if($listing_id){
				$_SESSION['search_filters']['listing_id'] = $listing_id;
			}
			else if(!$listing_id && $_SESSION['search_filters']['listing_id']){
				$listing_id = $_SESSION['search_filters']['listing_id'];
			}
			if(!$listing_id){$message = "no listing selected";return;}
			
			$listing_location ='';
			$listing_details = array();
			$listing_images = array();
			$image_count=0;
			$main_image ='';
			$additional_images = array();
			
			$this -> _listing_details($listing_id, &$listing_location, &$listing_details, &$listing_images, &$image_count, &$main_image, &$additional_images);
			
			//Data formatting
			$description = strip_tags(str_replace('</p>',"\n\n",str_replace('<br />',"\n",str_replace("\n",'',str_replace('Untitled document', '',$listing_details[0]['description'])))));
			$title = $listing_details[0]['name'];
			$temp = $this->model->getResult("taxonomy_categories","id, name"," WHERE type='N' AND approved=1 AND id={$listing_details['0']['category_id']} LIMIT 1 ","");
			$category = $temp[0]['name'];
			
			
			$base_url="http://localhost:8888/INDULGEON/indulgeon_110707-ossi-search_section/";
			$link = $base_url.'index.php?process=listing_display&param1='.$listing_id;
			
			require('lib/fpdf.php');

			$pdf=new FPDF();
			$pdf->AddPage(); //Create new PDF page
			
			
			
			$pdf->Image('public/images/indulgeon-inverted.gif',10);
			
			$pdf->setY($pdf->getY+30);
			
			$pdf->SetFont('Arial','B',18); //Set the base font & size
			$pdf->Cell(105,12,$title,0,0,'T', false,$link);


			$pdf->SetFont('Arial','B',8); //Set the base font & size
			$pdf->MultiCell(85,6,'Category:'."\n".$category,0,'R');



			$pdf->SetFont('Arial','',10); //Set the base font & size
			$pdf->MultiCell(190,5,$description,0,1);
			
			$pdf->ln();

			/*
			$pdf->Image($main_image,10,$pdf->getY(), -72);
			$additional_images_count = count($additional_images);
			
			if($additional_images_count >= $MAX_IMAGES){
				$additional_images_count = $MAX_IMAGES;
			}
			$nextwidth=0;
			
			for($i=0;$i<$additional_images_count;$i++){
				$nextwidth = $nextwidth + $listing_images[$i]['image_width'];
				$pdf->Image($additional_images[$i],$nextwidth,$pdf->getY(), -72);
			}
			*/
			
			$imagecount = count($listing_images);
			$MAX_IMAGES = 3;
			if($imagecount > $MAX_IMAGES){
				$imagecount = $MAX_IMAGES;
			}
			for($i=0;$i<$imagecount;$i++){
				//echo "$i: FILE PATH: {$listing_images[$i]['file_path']}, IMAGE WIDTH: {$listing_images[$i]['image_width']}<br />";
				$pdf->Image($listing_images[$i]['file_path'],10+($i*65),$pdf->getY(), 40);
			}
			
			
			//$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
			$pdf->setY($pdf->getY()+55);
			
			$pdf->SetFont('Arial','B',8); //Set the base font & size
			$pdf->Cell(190,12,"Cost of Share: {$listing_details[0]['cost_per_share']}",0,1,'T');
			$pdf->Cell(190,12,"Location: $listing_location",0,1,'T');
			$pdf->Cell(190,12,"Group Size: {$listing_details[0]['group_size']}",0,1,'T');
			$pdf->Cell(190,12,"Monthly Fees: {$listing_details[0]['fees']}",0,1,'T');
			$pdf->Cell(190,12,"Daily Usage Charge: {$listing_details[0]['usage_charge']}",0,1,'T');
			

			$pdf->SetFont('Arial','I',12); //Set the base font & size
			$pdf->SetFillColor(100,100,100);
			$pdf->SetTextColor(255,205,0);
			$pdf->Cell(190,12,"http://indulgeon.com",0,1,'C',TRUE,'http://indulgeon.com/');
			
				$pdf->Output();
			
			
			$content_status="pdflisting";
			include('app/view/insert.php');
		}
		
		function register_interest(){
			$location=$this->model->breadcrumb(0,'L',15);
			
			$userId=$_SESSION['user_id'];
			
			if(!empty($userId))
			{
				$userProfileInfo=$this->model->getResult("auth_profile",""," WHERE user_id='$userId'","");
				$spostcode = $userProfileInfo[0]['postcode'];
			}

			/* START SEARCH POST HANDLER*/
			if($_POST){
				$scategory = mysql_real_escape_string($_POST['scategory']);
				$ssubcategory = mysql_real_escape_string($_POST['ssubcategory']);
				$spostcode = mysql_real_escape_string($_POST['spostcode']);
				$sdistance = mysql_real_escape_string($_POST['sdistance']);
				/*$smincost = mysql_real_escape_string($_POST['smincost']);
				$smaxcost = mysql_real_escape_string($_POST['smaxcost']);
				$smingroup = mysql_real_escape_string($_POST['smingroup']);
				$smaxgroup = mysql_real_escape_string($_POST['smaxgroup']);
				$sminfee = mysql_real_escape_string($_POST['sminfee']);
				$smaxfee = mysql_real_escape_string($_POST['smaxfee']);
				$stimeframe_fee = mysql_real_escape_string($_POST['stimeframe_fee']);
				$sminusage = mysql_real_escape_string($_POST['sminusage']);
				$smaxusage = mysql_real_escape_string($_POST['smaxusage']);
				$stimeframe_usage = mysql_real_escape_string($_POST['stimeframe_usage']);
				$*/
				
				$_SESSION['register_interest_filters'] = $_POST;
				
				if($ssubcategory){
					$category_id = $ssubcategory;
				}
				else{
					$category_id = $scategory;
				}
				
				if($spostcode && $sdistance<1050000){
					$postcode_elements = split(' ',$spostcode);
					$spostcode = strtoupper($postcode_elements[0]);
					$postcode_record = $this->model->getResult("postcodes",""," where postcode like '$spostcode' limit 1","");
					if(count($postcode_record)){
						$postcode_id = $postcode_record[0]['id'];
					}
					else{
						$postcode_id=0;
					}
				}
				else{
					$postcode_id=0;
				}
				
				if(($postcode_id && $sdistance>1000000) || (!$postcode_id && $sdistance<1000000) || (!$postcode_id && $spostcode!='')){
					$message = "<div class='error'>To use the distance critea, a valid UK postcode and distance are required.</div>";
					$error = TRUE;

				}

				
				if(!$error){
					$sdistance = round($sdistance);
					
					$sql = "INSERT IGNORE INTO `registered_interest` (`user_id`,`category_id`,`postcode_id`,distance) VALUES ($userId,$category_id,$postcode_id,$sdistance)";
					$insert = $this->model->insert($sql);
					
					$message .= "<div style='margin:20px 0;padding:5px;background:#333;'>The details were added</div>";// and we will email you when a suitable listing is added. Thank you for your interest.";
					
				}
				
				//echo 'QUERY WITHOUT POST';
				//print_r($base_results);
				
				
				$c1=FALSE;$c2=FALSE;$c3=FALSE;
				
				//fee conditions
				if($stimeframe_fee && ($sminfee || $smaxfee)){
					$stimeframe_fee_days = $this->model->getResult("listing_units","daycount"," where id like $stimeframe_fee ","");
					if(count($stimeframe_fee_days)){
						$sminfee_per_day = $sminfee / $stimeframe_fee_days[0];
						$smaxfee_per_day = $smaxfee / $stimeframe_fee_days[0];
						$c1 = TRUE;
					}
				}
				
				//usage conditions
				if($stimeframe_usage && ($sminusage || $smaxusage)){
					$stimeframe_usage_days = $this->model->getResult("listing_units","daycount"," where id like $stimeframe_usage ","");
					if(count($stimeframe_usage_days)){
						$sminusage_per_day = $sminusage / $stimeframe_usage_days[0];
						$smaxusage_per_day = $smaxusage / $stimeframe_usage_days[0];
						$c2 = TRUE;
					}
				}
				


				


				//Keep search form prefilled:
				if($ssubcategory){
					$subcategories = $this->model->getResult("taxonomy_categories","id, name"," WHERE type='N' AND approved=1 AND parent_id=$scategory ","");
				}

															
			}/* END SEARCH POST HANDLER*/

			
			$categories = $this->model->getResult("taxonomy_categories","id, name"," WHERE type='N' AND approved=1 AND parent_id=0 ","");
			
			
			
			$content_status="register_interest";
			include('app/view/index.php');
			
		}

		function _str_lreplace($search, $replace, $subject)
		{
			$pos = strrpos($subject, $search);
		
			if($pos === false)
			{
				return $subject;
			}
			else
			{
				return substr_replace($subject, $replace, $pos, strlen($search));
			}
		}
		
		function _image_to_resized($path,$w=102,$h=78, $prefix='thumb_'){
			//create thumbnails for any that don't exist
			$thumb_path = str_replace('/temp/',"/temp/$prefix",$path);
			//if(!file_exists($thumb_path)){
				try
				{
					 $thumb = PhpThumbFactory::create($path);
				}
				catch (Exception $e)
				{
					 // handle error here however you'd like
					 echo '<div class="error">Can\'t create image; error: '.print_r($e). ' thrown</div>';
				}
				
				$thumb->adaptiveResize($w, $h);
				//$thumb->show();
				$thumb->save($thumb_path);
			//}
			return $thumb_path;
		}
		
		function list_image_resized($path,$w=102,$h=78, $newPath,$oldPath){
			//create thumbnails for any that don't exist
			$thumb_path = str_replace($oldPath,$newPath,$path);
			//if(!file_exists($thumb_path)){
				try
				{
					 $thumb = PhpThumbFactory::create($path);
				}
				catch (Exception $e)
				{
					 // handle error here however you'd like
					 echo '<div class="error">Can\'t create image; error: '.print_r($e). ' thrown</div>';
				}
				
				$thumb->adaptiveResize($w, $h);
				//$thumb->show();
				$thumb->save($thumb_path);
			//}
			return $thumb_path;
		}
		
		function _listing_details($listing_id, &$listing_location, &$listing_details, &$listing_images, &$image_count, &$main_image, &$additional_images){
			if($listing_id){
				$listing_details = $this->model->getResult("listing_items",""," WHERE id like $listing_id LIMIT 1 ","");
				
				$listing_location = "{$listing_details[0]['townName']},{$listing_details[0]['country']}";
				if(strlen($listing_location)==1){
					$listing_location = 'unknown';
				}
				$listing_location = trim($listing_location, ',');
				
                $listing_images = $this->model->getResult("listing_photos","file_path, file_name, image_width"," where type like 'L' and type_id like {$listing_details[0]['id']}","");
				$image_count = count($listing_images);
				
				for($i=0,$j=0;$i<$image_count;$i++){
					if(strpos($listing_images[$i]['file_name'],'mainImage')===0 || $image_count==1){
						$main_image = $listing_images[$i]['file_path'];
						//break;
					}
					else{
						$additional_images[$j] = $listing_images[$i]['file_path'];
						$j++;
					}
				}
			}
		}

		function _select_options($labels,$values=null,$selected=null){
			$options_count = count($labels); 
			$options='';
			for($i=0;$i<$options_count;$i++){
				$s='';
				if($values!=null){
					$value = $values[$i];
				}
				else{
					$value = $labels[$i];
				}
				if($value==$selected){
					$s = 'selected="selected"';
				}
				$options .= "<option value='$value' $s>{$labels[$i]}</option>";
			}
			return $options;
		}

}
?>