
		<?php
			$path='app/view/content/';
			$currentUser=$_SESSION['user_id'];
			
			if(isset($content_status))
			{
				switch($content_status)
				{	
					case "signin" : include($path.'login.php');
					break;
					case "forget_password" : include($path.'forget_pwd.php');
					break;
					case "create_user_page" : include($path.'create_user.php');
					break;
					case "calculator" : include($path.'calculator.php');
					break;
					case "indulgeonAdmin" : include($path.'admin/indulgeonAdmin.php');
					break;
					case "discover" : include($path.'discover.php');
					break;
					case "adviceCentre" : include($path.'adviceCentre.php');
					break;
					case "enjoy" : include($path.'enjoy.php');
					break;
					case "faq" : include($path.'static/faq.php');
					break;
					case "createNewGroup" : include($path.'static/createNewGroup.php');
					break;
					case "splitAssetUse" : include($path.'static/splitAssetUse.php');
					break;
					case "sampleContract" : include($path.'static/sampleContract.php');
					break;
					case "finance" : include($path.'static/finance.php');
					break;
					case "allocation" : include($path.'static/allocation.php');
					break;
					case "joinGroup" : include($path.'static/joinGroup.php');
					break;
					case "suitableAsset" : include($path.'static/suitableAsset.php');
					break;
					case "yourGroup" : include($path.'static/yourGroup.php');
					break;
					case "runningAsset" : include($path.'static/runningAsset.php');
					break;
					case "Insurance" : include($path.'static/Insurance.php');
					break;
					case "companySetUp" : include($path.'static/companySetUp.php');
					break;
					case "services" : include($path.'static/services.php');
					break;
					case "advanced_search" : include($path.'advanced_search.php');
					break;
					case "subCategories" : include($path.'subcontent/subcategories.php');
					break;
					case "subListing" : include($path.'subcontent/sublisting.php');
					break;
					case "tellFriend" : include($path.'tellfriend.php');
					break;
					case "listing_display" : include($path.'listing_display.php');
					break;
					case "noresults_options" : include($path.'noresults_options.php');
					break;
					case "popupImages" : include($path.'popupimages.php');
					break;
					case "pdflisting" : include($path.'pdflisting.php');
					break;
					case "home_current" : include($path.'home_current.php');
					break;
					default : 
						if(!empty($currentUser)) {
							switch($content_status)
							{	
								//case "addListing" :  include($path.'welcome.php');
//								break;
								case "change_pwd" : include($path.'change_password.php');
								break;
								case "step1" : include($path.'step1.php');
								break;
								case "step2" : include($path.'step2.php');
								break;
								case "step3" : include($path.'step3.php');
								break;
								case "step4" : include($path.'step4.php');
								break;
								case "finish_list" : include($path.'finish_listing.php');
								break;
								case "group_opt_page" : include($path.'createGroup.php');
								break;
								case "billing" : include($path.'subcontent/billing.php');
								break;
								case "usageMonitor" : include($path.'subcontent/usageMonitor.php');
								break;
								case "bookingRules" : include($path.'subcontent/bookingRules.php');
								break;
								case "groupMember" : include($path.'subcontent/groupMember.php');
								break;
								case "groupImage" : include($path.'subcontent/groupImage.php');
								break;
								case "groupBasicInfo" : include($path.'subcontent/groupBasicInfo.php');
								break;
								case "groupSetup" : include($path.'groupSetup.php');
								break;
								case "myBooking" : include($path.'subcontent/myBooking.php');
								break;
								case "calendar" : include($path.'subcontent/calendar.php');
								break;
								case "timeSlots" : include($path.'subcontent/timeSlots.php');
								break;
								case "newBooking" : include($path.'subcontent/newBooking.php');
								break;
								case "myBookingList" : include($path.'subcontent/myBookingList.php');
								break;
								case "allBookingList" : include($path.'subcontent/allBookingList.php');
								break;
								case "myGroup" : include($path.'myGroup.php');
								break;
								case "myUsage" : include($path.'subcontent/myUsage.php');
								break;
								case "myUsageLog" : include($path.'subcontent/myUsageLog.php');
								break;
								case "myInvoices" : include($path.'subcontent/myInvoices.php');
								break;
								case "viewInvoice" : include($path.'viewInvoice.php');
								break;
								case "financialSummery" : include($path.'subcontent/financialSummery.php');
								break;
								case "group_mc_page" : include($path.'groupMC.php');
								break;
								case "group_fc_page" : include($path.'groupFC.php');
								break;
								case "invoice_payment" : include($path.'subcontent/invoice_payment.php');
								break;
								case "groupAccount" : include($path.'subcontent/groupAccount.php');
								break;
								case "groupACPrint" : include($path.'subcontent/groupACPrint.php');
								break;
								case "fcLog" : include($path.'subcontent/fcLog.php');
								break;
								case "single_pay" : include($path.'subcontent/single_pay.php');
								break;
								case "regular_io" : include($path.'subcontent/regular_io.php');
								break;
								case "single_in_list" : include($path.'subcontent/single_in_list.php');
								break;
								case "single_pay_list" : include($path.'subcontent/single_pay_list.php');
								break;
								case "regular_in_list" : include($path.'subcontent/regular_in_list.php');
								break;
								case "regular_pay_list" : include($path.'subcontent/regular_pay_list.php');
								break;
								case "create_invoice" : include($path.'subcontent/create_invoice.php');
								break;
								case "group_mc_page" : include($path.'groupMC.php');
								break;
								case "maintenance_schedule" : include($path.'subcontent/maintenance_schedule.php');
								break;
								case "calendar_schdule" : include($path.'subcontent/calendar_schdule.php');
								break;
								case "usage_schdule" : include($path.'subcontent/usage_schdule.php');
								break;
								case "usage_sch_list" : include($path.'subcontent/usage_sch_list.php');
								break;
								case "calendar_sch_list" : include($path.'subcontent/calendar_sch_list.php');
								break;
								case "store_group" : include($path.'store_group.php');
								break;
								case "bookingRule" : include($path.'subcontent/booking_rules.php');
								break;
								case "member_page" : include($path.'storeMember.php');
								break;
								case "memSearchRes" : include($path.'subcontent/memSearchRes.php');
								break;
								case "groupAdmin" : include($path.'groupAdmin.php');
								break;
								case "adminLog" : include($path.'subcontent/adminLog.php');
								break;
								case "usageData_page" : include($path.'usageData.php');
								break;
								case "myAccount" : include($path.'myAccount.php');
								break;
								case "taglisting" :  include($path.'taglisting.php');
								break;
								case "register_interest" :  include($path.'register_interest.php');
								break;
								default : include($path.'home.php');
								break;
							}
						} else {
							 include($path.'login.php');
							 break;
						}
					break;
				}
			}
			else
			{
				include($path.'home.php');
			}
					
		?>
		