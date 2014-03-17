<?php
// /* HOME PAGE */ //
class homeController extends loginController{								
		function __construct($modelObj)		//initializing parent constructor with Model Object
		{
			parent::__construct($modelObj);
		}
		function mainContent()						//Display Main Home Page
		{	
			$location=$this->model->breadcrumb(0,'L',1,0);
			//$result=$this->model->countryList();
			$Id=$this->model->getResult("listing_photos"," MAX(id) as maxPhotoId, MIN(id) as minPhotoId "," WHERE  active='y' ","");
			$maxPhotoId=$Id[0]['maxPhotoId'];
			$minPhotoId=$Id[0]['minPhotoId'];
			$display_res=$this->model->getResult("listing_photos","",""," WHERE active='y' AND type='L' AND file_name LIKE 'mainImage_%' AND type_id IN (SELECT id FROM listing_items WHERE approved=1 AND deleted='n') ORDER BY id DESC ");
			include('app/view/index.php');
		}
		function home_content()						//Display Main Home Page
		{	
			$location=$this->model->breadcrumb(0,'L',1,0);
			//$result=$this->model->countryList();
			$list_id =$_REQUEST['param1'];
			$display_listing_item=$this->model->getResult("listing_items",""," WHERE id='$list_id'  AND deleted='n' ","");
			
			if(count($display_listing_item)!=0)
			{
				//Listing info
				$list_name = $display_listing_item[0]['name'];
				////listing user info
				$list_user_id = $display_listing_item[0]['user_id'];
				$listUserProfile=$this->model->getResult("auth_profile",""," WHERE user_id='$list_user_id' ","");
				$listUserName = $listUserProfile[0]['first_name']." ".$listUserProfile[0]['last_name'];
				////category
				$list_cat_id = $display_listing_item[0]['category_id'];
				$thisOption=$this->model->getResult("taxonomy_categories",""," WHERE id='$list_cat_id' ","");
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
							$thisName =  $display_parent3_list[0]['name'] ." > " . $thisName;
						}
					}
				}
				////Location
				$location_name = "";
				$town = $display_listing_item[0]['townName'];
				$this_list_country_id = $display_listing_item[0]['country_id'];
				if(!empty($this_list_country_id))
				{
					$country_name_res=$this->model->getResult("countries"," printable_name ","  WHERE id='$this_list_country_id'  ","");
					$country_name = $country_name_res[0]['printable_name'];
					if(isset($town))
					{
						$location_name = $town.', '.$country_name;
					}
					else
					{
						$location_name = $country_name;
					}
				}
				
				////share cost
				$costPerShare = "";
				$cost_per_share_currency_id = $display_listing_item[0]['cost_per_share_currency'];
				if(!empty($cost_per_share_currency_id))
				{
					$cost_per_share_currency_des = $this->model->getResult("currencies"," symbol ",""," WHERE id='$cost_per_share_currency_id'");
					$cost_per_share_currency_sym = $cost_per_share_currency_des[0]['symbol'];
				}
				if(isset($cost_per_share_currency_sym)) {
					$costPerShare = $cost_per_share_currency_sym . $display_listing_item[0]['cost_per_share'];
				} else {
					$costPerShare = $display_listing_item[0]['cost_per_share'];
				}
				////Group size
				$groupSize = 0;
				$groupSize = $display_listing_item[0]['group_size'];
				////Fees
				$fees = "";
				$fees_currency_sym = "";
				$perFees = "";
				$fees_currency_id = $display_listing_item[0]['fees_currency'];
				if(!empty($fees_currency_id)) {
					$fees_currency_des = $this->model->getResult("currencies"," symbol ",""," WHERE id='$fees_currency_id'");
					$fees_currency_sym = $fees_currency_des[0]['symbol'];
				}
				$durationFeesId = $display_listing_item[0]['fees_per'];
				if(!empty($durationFeesId)) {
					$listingFees=$this->model->getResult("listing_units",""," WHERE type!='C' AND (( id='$durationFeesId' AND approved='0') OR approved='1')  "," ORDER BY id");
					if(count($listingFees)!=0) {
						$perFees = ' per '. $listingFees[0]['name'];
					}
				}
				$fees = $fees_currency_sym . $display_listing_item[0]['fees'] . $perFees;
				////Usage charge
				$usageCharge = "";
				$usage_charge_currency_sym = "";
				$perUsageCharge = "";
				$usage_charge_currency_id = $display_listing_item[0]['usage_charge_currency'];
				
				if(!empty($usage_charge_currency_id)) {
					$usage_charge_currency_des = $this->model->getResult("currencies"," symbol "," WHERE id='$usage_charge_currency_id' ","");
					$usage_charge_currency_sym = $usage_charge_currency_des[0]['symbol'];
				}
				$durationUsageId = $display_listing_item[0]['usage_charge_per'];
				if(!empty($durationUsageId)) {
					$listingCharge=$this->model->getResult("listing_units",""," WHERE type!='F' AND (( id='$durationUsageId' AND approved='0') OR approved='1')  "," ORDER BY id ");
					if(count($listingCharge)!=0) {
						$perUsageCharge = ' per '.$listingCharge[0]['name'];
					}
				}
				$usageCharge = $usage_charge_currency_sym . $display_listing_item[0]['usage_charge'] .$perUsageCharge;
				////listing description
				$listing_description = addslashes($display_listing_item[0]['description']);	
			}
			else{ echo "This listing not found"; }
			
			
			$content_status="home_content";
			include('app/view/index.php');
		}
		function display_home($photoId)		//Changes content of Home Page
		{
			$location=$this->model->breadcrumb(0,'L',1,0);
			$Id=$this->model->getResult("listing_photos"," count(*), MAX(id) as maxPhotoId, MIN(id) as minPhotoId "," WHERE  active='y' ","");
			$maxPhotoId=$Id[0]['maxPhotoId'];
			$minPhotoId=$Id[0]['minPhotoId'];
			$display_photo=$this->model->getResult("listing_photos",""," WHERE id='$photoId' AND active='y' ","");
			$list_id=$display_photo[0]['type_id'];
			$display_home_res=$this->model->getResult("listing_items",""," WHERE id='$list_id' AND active='y' ","");
			include('app/view/content/subcontent/home_info.php');
		}
		// /* HOME PAGE */ //
		
		// /* DISCOVER PAGE */ //
		function discover()
		{
			$content_status="discover";
			include('app/view/index.php');
		}
		// /* DISCOVER PAGE */ //
		
		// /* ABOUT US PAGE */ //
		function aboutus()
		{
			$content_status="aboutus";
			include('app/view/index.php');
		}
		// /* ABOUT US PAGE */ //
		
		// /* ENJOY PAGE */ //
		function enjoy()
		{
			$content_status="enjoy";
			include('app/view/index.php');
		}
		// /* ENJOY PAGE */ //
		
		// /* ADVICE CENTRE */ //
		function adviceCentre()
		{
			$content_status="adviceCentre";
			include('app/view/index.php');
		}
		// /* ADVICE CENTRE */ //
		
		// /* STATIC PAGES */ //
		function staticPage()
		{
			$content_status=$_REQUEST['param1'];
			include('app/view/index.php');
		}
		// /* STATIC PAGES */ //
}
?>