<?php
class groupModel extends loginModel{
	public function __construct($SERVER,$DBASE,$USERNAME,$PASSWORD)
	{
		parent::__construct($SERVER,$DBASE,$USERNAME,$PASSWORD);
		$this->regularInvoices();
		$this->memberBilling(1);
	}
	function regularInvoices()
	{	
		$groupACInfo=$this->getResult("group_AC",""," WHERE io_type='i' AND type='R' AND 	active='y' "," order by id DESC");
		
		for($g=0;$g<count($groupACInfo);$g++)
		{
			$groupAcId = $groupACInfo[$g]['id'];
			$typeId = $groupACInfo[$g]['type_id'];
			$groupId = $groupACInfo[$g]['group_id'];
			$amount = $groupACInfo[$g]['amount'];
			$reccInfo=$this->getResult("AC_regular_IO",""," WHERE id='$typeId' ","");
			$reccId = $reccInfo[0]['recurrence'];
			$start_date = $reccInfo[0]['start_date'];
			$end_date = $reccInfo[0]['end_date'];
			$reccUnitInfo=$this->getResult("units",""," WHERE id='$reccId' ","");
			$reccValue = $reccUnitInfo[0]['value'];
			$reccValUnit = $reccUnitInfo[0]['valueUnit'];
			
			$today = strtotime("now");
			$firststartDateStr = strtotime($start_date);
			$firstendDateStr = strtotime($end_date);
			
			if($firststartDateStr <= $today)
			{	
				$todaynow = strtotime("now");
				$startDateStr =$firststartDateStr;
				$endDateStr =$firstendDateStr;
				
				$groupMemberInfo=$this->getResult("group_members",""," WHERE group_id='$groupId' AND  active='y' "," order by id");
				
				for($m=0;$m<count($groupMemberInfo);$m++)
				{
					$memId = $groupMemberInfo[$m]['id'];
					$currenttime = date('Y-m-d H:i:s');
					$invoice_type = 'r';
					$status = 'u';
					$active = 'y';
					
					if($endDateStr >= $todaynow)
					{	
						$newUnitDate = strtotime($start_date." + ". $reccValue ." " . $reccValUnit);
						while($newUnitDate < $todaynow)
						{
							//check and insert record for $newUnitDate 
							$thisInvoiceDate = date('Y-m-d H:i:s',$newUnitDate);	
							$memberBillingInfo=$this->getResult("member_billing",""," WHERE member_id='$memId' AND invoice_type='$invoice_type' AND dateInvoice='$thisInvoiceDate' AND active='y' "," order by id desc");
							if(count($memberBillingInfo)==0)
							{
								 $q="INSERT INTO member_billing(member_id,invoice_type,status,active,dateInvoice,created_dt) VALUES('$memId','$invoice_type','$status','$active','$thisInvoiceDate','$currenttime')";
								 $r=parent::insert($q);
							 	 if(isset($r))
							 	{
									$q1="INSERT INTO bill_invoice_regular(bill_invoice_id,regular_id,amount,active) VALUES('$r','$groupAcId','$amount','y')";
									$r1=parent::insert($q1);
							 	}
							}
							$newUnitDate = strtotime($thisInvoiceDate." + ". $reccValue ." " . $reccValUnit);	
						}
					}
					else
					{
						$newUnitDate = strtotime($start_date." + ". $reccValue ." " . $reccValUnit);
						
						while($newUnitDate <= $endDateStr)
						{
							//check and insert record for $newUnitDate
							$thisInvoiceDate = date('Y-m-d H:i:s',$newUnitDate);		
							$memberBillingInfo=$this->getResult("member_billing",""," WHERE member_id='$memId' AND invoice_type='$invoice_type' AND dateInvoice='$thisInvoiceDate' AND active='y' "," order by id desc");
							if(count($memberBillingInfo)==0)
							{
								 $q="INSERT INTO member_billing(member_id,invoice_type,status,active,dateInvoice,created_dt) VALUES('$memId','$invoice_type','$status','$active','$thisInvoiceDate','$currenttime')";
								 $r=parent::insert($q);
								 if(isset($r))
								 {
									$q1="INSERT INTO bill_invoice_regular(bill_invoice_id,regular_id,amount,active) VALUES('$r','$groupAcId','$amount','y')";
									$r1=parent::insert($q1);
								 }
							}
							
							$newUnitDate = strtotime($start_date." + ". $reccValue ." " . $reccValUnit);
							
							if($newUnitDate < $endDateStr)
							{
								$prevDate =  date('Y-m-d H:i:s',$newUnitDate);
								$newUnitDate = strtotime($thisInvoiceDate." + ". $reccValue ." " . $reccValUnit);
								if($newUnitDate > $endDateStr)
								{
									//insert for $endDateStr
									$perDayCharge = ((float)$amount) / ((float)$reccValue);
									
									$dtFinish = new DateTime($end_date);	//count total days
									$dtStart = new DateTime($prevDate);
									$total_interval = $dtStart->diff($dtFinish);
									$total_interval_days=($total_interval->format('%a'));
									
									$totalInvoiceCharge = $perDayCharge * (float)$total_interval_days;
									$thisInvoiceDate = date('Y-m-d H:i:s',$endDateStr);	
									$memberBillingInfo=$this->getResult("member_billing",""," WHERE member_id='$memId' AND invoice_type='$invoice_type' AND dateInvoice='$thisInvoiceDate' AND active='y' "," order by id desc");
									if(count($memberBillingInfo)==0)
									{
										 $q="INSERT INTO member_billing(member_id,invoice_type,status,active,dateInvoice,created_dt) VALUES('$memId','$invoice_type','$status','$active','$thisInvoiceDate','$currenttime')";
										 $r=parent::insert($q);
										 if(isset($r))
										 {
											$q1="INSERT INTO bill_invoice_regular(bill_invoice_id,regular_id,amount,active) VALUES('$r','$groupAcId','$totalInvoiceCharge','y')";
											$r1=parent::insert($q1);
										 }
										 
										 $memberLoginInfo = $this->getResult("auth_users","","  WHERE id IN (SELECT user_id FROM group_members WHERE id='$memId'  AND active='y') AND active='y' ","");
										 $to_userName = $memberLoginInfo[0]['username'];
										 $to_emailId = $memberLoginInfo[0]['email'];
										 //mail invoice notification	
										 $from_indul_adminEmail = commonModel :: indulgeon_admin_emailId;
										 $from_indul_adminName = commonModel :: indulgeon_admin_name;
										 $emailMsg="Your Regular Invoice Referance No.: *";
										 $emailMsg=$emailMsg . $r. "* has been created. \n please check your account  to view/print/download full invoice ";
										 $emailSubject="Regular Invoice created";
									
										 $mailSentMsg = parent::mailSend($from_indul_adminName,$from_indul_adminEmail,$to_userName,$to_emailId,$emailSubject,$emailMsg);
									}
								}
							}
							
						}	// end while loop
					}	//end else section 
					
				} //end member info for loop	
				
			}
			
		}
	}
	function memberBilling($thisGroupId)
	{
		$noInvoiceBookings = array();
		$invoiceMonths = array();
		//activated_dt
		if(!empty($thisGroupId))
		{
			$groupInfo=$this->getResult("group_info",""," WHERE id ='$thisGroupId' AND  active='y' AND deleted='n' "," order by id");
		}
		else
		{
			$groupInfo=$this->getResult("group_info",""," WHERE active='y' AND deleted='n' "," order by id");
		}
		foreach($groupInfo as $g=>$value)
		{
			$groupId = $groupInfo[$g]['id'];
			$createdTime = $groupInfo[$g]['modified_dt'];
			//$createdTime = '2011-05-03 10:44:33';
			$resumeGroup = $groupInfo[$g]['resume'];
			$todaynow = new DateTime("now");	//count total days
			$dtStart = new DateTime($createdTime);
			$total_interval = $dtStart->diff($todaynow);
			$total_months=($total_interval->format('%m'));
			
			if($total_months >= 1)
			{
				for($i=1;$i<=$total_months;$i++)
				{
					$createdDT = strtotime($createdTime);
					$beforeMonth = strtotime(" + " . ($i-1) . " month",$createdDT);
					$beforeDt = date('Y-m-d',$beforeMonth);
					$monthOfGroup = strtotime(" + " . $i . " month",$createdDT);
					$thisDt = date('Y-m-d',$monthOfGroup);
					$monthTm = date('H:i:s',$monthOfGroup);
					$dateInvoice = date('Y-m-d H:i:s',$monthOfGroup);
					
					$groupMemberInfo=$this->getResult("group_members",""," WHERE group_id='$groupId' AND active='y' "," order by id");
					foreach($groupMemberInfo as $m=>$memValue)
					{	
						$flageMember = 0;
						$memId = $groupMemberInfo[$m]['id'];	
						$invoice_type = 'a';
						$status = 'u';
						$active = 'y';
						$currenttime = date('Y-m-d H:i:s');
						
						$groupBillingType=$this->getResult("group_billing_type",""," WHERE group_id='$groupId'  AND active='y' "," ORDER BY bill_type ASC");
						$monthCost = 0;
						$usageCost = 0;
						
						foreach($groupBillingType as $bill=>$billTypeValue)
						{
							$group_bill_type=$groupBillingType[$bill]['bill_type'];	
							$group_bill_cost=$groupBillingType[$bill]['cost'];
							
							if($group_bill_type=='U')
							{	
								$billUsageId = $groupBillingType[$bill]['type_detail'];
								$groupUsageMonitor=$this->getResult("group_usageMonitor",""," WHERE id='$billUsageId' "," ORDER BY type DESC");
								$groupusageType = $groupUsageMonitor[0]['type'];
								$groupUnitDt = strtotime($groupUsageMonitor[0]['created_dt']);
								
								$memberBookingInfo=$this->getResult("member_bookingInfo",""," WHERE member_id='$memId' AND ((finishDate > '$beforeDt') OR (finishDate = '$beforeDt' AND finishTime >= '$monthTm')) AND ((finishDate < '$thisDt') OR (finishDate = '$thisDt' AND finishTime <= '$monthTm'))  AND type='N' "," order by id");
								if(count($memberBookingInfo)!=0)
								{
									foreach($memberBookingInfo as $b=>$bookingValue)
									{
										$bookingId = $memberBookingInfo[$b]['id'];
										$filledUsageData = $memberBookingInfo[$b]['usageData'];
										$bookingStart = strtotime($memberBookingInfo[$b]['startDate']."  ".$memberBookingInfo[$b]['startTime']);
										
										if($filledUsageData=='F')
										{	
											$memberUsageInfo=$this->getResult("member_usageInfo",""," WHERE booking_id='$bookingId' AND usage_type_id='$billUsageId' "," order by usageUnitValue ");
											foreach($memberUsageInfo as $mu=>$memUsageValue)
											{
												$thisUsageId = $memberUsageInfo[$mu]['id'];	
												$usageVal = $memberUsageInfo[$mu]['usageUnitValue'];
												$thisGroupType = $memberUsageInfo[$mu]['usage_type_id'];
												$usageDataActive = $memberUsageInfo[$mu]['active'];
												
												if($usageDataActive=='y')
												{
													$usageValuesInfo = $this->getResult("booking_usageInfo",""," WHERE id='$usageVal' "," order by id");
													$valid = $usageValuesInfo[0]['valid'];
													
													if($valid=='y')
													{
														if($groupusageType=='SE')
														{
															$startVal = (float) $usageValuesInfo[0]['startValue'];
															$finishVal = (float) $usageValuesInfo[0]['finishValue'];
															$diffValue = $finishVal - $startVal;
															$usageBillCost = $group_bill_cost * $diffValue;
															$usageCost = $usageCost + $usageBillCost;
														}
														else if($groupusageType=='T')
														{
															$startUsageValue=$usageValuesInfo[0]['startValue'];
															$finishUsageValue=$usageValuesInfo[0]['finishValue'];
															
															$startTm = strtotime( $startUsageValue );
															$finishTm= strtotime( $finishUsageValue );
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
																$usageBillCost = $group_bill_cost * $countHours;
																$usageCost = $usageCost + $usageBillCost;
															}
														}
													}
													else
													{	
														$memberBillingInfo=$this->getResult("member_billing",""," WHERE member_id='$memId' AND invoice_type='a' AND dateInvoice='$dateInvoice' AND active='y' "," order by id desc");
														
														if(count($memberBillingInfo)==0)
														{
															array_push($noInvoiceBookings,$bookingId);
														}
														 
														$flageMember=1;
														break;
													}
												}
												/*else if($groupusageType=='T' && $usageDataActive=='y')
												{	
													$usageBillCost = ((float) $group_bill_cost) * ((float) $usageVal);	
													$usageCost = $usageCost + $usageBillCost;
												}*/
											}
										}
										else  if($bookingStart >= $groupUnitDt)
										{
											$memberBillingInfo=$this->getResult("member_billing",""," WHERE member_id='$memId' AND invoice_type='a' AND dateInvoice='$dateInvoice' AND active='y' "," order by id desc");
														
														if(count($memberBillingInfo)==0)
														{	
															array_push($noInvoiceBookings,$bookingId);
														}
											 
											$flageMember=1;
											break;
										}
									}
								}
							}
							else if($group_bill_type=='M')
							{
								$monthCost = $monthCost + $group_bill_cost;
							}
						}
						
						if($flageMember==0)
						{	
							$memberBillingInfo=$this->getResult("member_billing",""," WHERE member_id='$memId' AND invoice_type='a'  AND dateInvoice='$dateInvoice' AND active='y' "," order by id desc");
							
							if(count($memberBillingInfo)==0 && ($monthCost!=0 || $usageCost!=0))
							 {
								  $q="INSERT INTO member_billing(member_id,invoice_type,status,active,dateInvoice,created_dt) VALUES('$memId','$invoice_type','$status','$active','$dateInvoice','$currenttime')";
								  $r=parent::insert($q);
									if($monthCost!=0)
									{
										$q1="INSERT INTO bill_invoice_auto(bill_invoice_id,type,amount,active) VALUES('$r','M','$monthCost','$active')";
										$r1=parent::insert($q1);
									}
									if($usageCost!=0)
									{
										$q2="INSERT INTO bill_invoice_auto(bill_invoice_id,type,amount,active) VALUES('$r','U','$usageCost','$active')";
										$r2=parent::insert($q2);
									}
									 
									 $memberLoginInfo = $this->getResult("auth_users","","  WHERE id IN (SELECT user_id FROM group_members WHERE id='$memId'  AND active='y') AND active='y' ","");
									 $to_userName = $memberLoginInfo[0]['username'];
									 $to_emailId = $memberLoginInfo[0]['email'];
									 //mail invoice notification	
									 $from_indul_adminEmail = commonModel :: indulgeon_admin_emailId;
									 $from_indul_adminName = commonModel :: indulgeon_admin_name;
									 $emailMsg="Your Monthly Invoice Referance No.: *";
									 $emailMsg=$emailMsg . $r. "* has been created. \n please check your account  to view/print/download full invoice ";
									 $emailSubject="Monthly Invoice created";
								
									 $mailSentMsg = parent::mailSend($from_indul_adminName,$from_indul_adminEmail,$to_userName,$to_emailId,$emailSubject,$emailMsg);
									
									/*deactivate the group */
									if($resumeGroup == 'y')
									{
										$this->dectiveGroup($groupId);
									}
							 }
						} 
						
						
						/* Check created group rules which needs to be activated */
						$groupUsageRulesInfo=$this->getResult("group_usageMonitor",""," WHERE group_id='$thisGroupId' AND created_dt<'$dateInvoice' AND active='n' "," order by created_dt DESC");
						if(count($groupUsageRulesInfo)!=0)
						{
							for($gi=0;$gi<count($groupUsageRulesInfo);$gi++)
							{
								$groupUsageCreatedDt = $groupUsageRulesInfo[$gi]['created_dt'];
								$thisGroupRuleId = $groupUsageRulesInfo[$gi]['id'];
								$dateNow =date('Y-m-d H:i:s');
								echo "UPDATE group_usageMonitor SET active='y,created_dt='$dateNow'  WHERE id='$thisGroupRuleId'";
								$q1="UPDATE group_usageMonitor SET active='y',created_dt='$dateNow'  WHERE id='$thisGroupRuleId' ";
								$r1=parent::update($q1);
							}
						}
					}
				}
			}
			
			/* Check created group rules which needs to be activated */
			if($total_months==0)
			{
				$groupUsageRulesInfo=$this->getResult("group_usageMonitor",""," WHERE group_id='$thisGroupId' AND created_dt>'$createdTime' AND active='n' "," order by created_dt DESC");
				if(count($groupUsageRulesInfo)!=0)
				{
					for($gi=0;$gi<count($groupUsageRulesInfo);$gi++)
					{
						$groupUsageCreatedDt = $groupUsageRulesInfo[$gi]['created_dt'];
						$thisGroupRuleId = $groupUsageRulesInfo[$gi]['id'];
						$dateNow =date('Y-m-d H:i:s');
						$q1="UPDATE group_usageMonitor SET active='y',created_dt='$dateNow'  WHERE id='$thisGroupRuleId' ";
						$r1=parent::update($q1);
					}
				}
			}
		}
		$results = array_unique($noInvoiceBookings);
		return $results;
	}
	function dectiveGroup($groupId)
	{
		/* deactive group account */
		$q1="UPDATE group_AC SET active='n'  WHERE group_id='$groupId' ";
		$r1=parent::update($q1);
		/* deactive calendar reminders */
		$q2="UPDATE group_reminder_calendar SET active='n'  WHERE group_id='$groupId' ";
		$r2=parent::update($q2);
		/* deactive usage reminders */
		$q3="UPDATE group_reminder_usage SET active='n'  WHERE group_id='$groupId' ";
		$r3=parent::update($q3);
		
		/* deactive group usage monitor */
		$q4="UPDATE group_usageMonitor SET active='n'  WHERE group_id='$groupId' ";
		$r4=parent::update($q4);
		/* deactive group booking rules */
		$q5="UPDATE group_bookRules SET active='n'  WHERE group_id='$groupId' ";
		$r5=parent::update($q5);
		/* deactive group billing types */
		$q6="UPDATE group_billing_type SET active='n'  WHERE group_id='$groupId' ";
		$r6=parent::update($q6);
		
		/* deactive Members */
		$q7="UPDATE group_members SET active='n'  WHERE group_id='$groupId' ";
		$r7=parent::update($q7);
		/* deactive Member billing */
		$q8="UPDATE member_billing SET active='n'  WHERE member_id IN (SELECT id FROM group_members WHERE group_id='$groupId' AND active='n') ";
		$r8=parent::update($q8);
		/* deactive auto invoices*/
		$q9="UPDATE bill_invoice_auto SET active='n'  WHERE bill_invoice_id IN (SELECT id FROM member_billing WHERE member_id IN (SELECT id FROM group_members WHERE group_id='$groupId' AND active='n')) ";
		$r9=parent::update($q9);
		/* deactive manual invoices*/
		$q10="UPDATE bill_invoice_manual SET active='n'  WHERE bill_invoice_id IN (SELECT id FROM member_billing WHERE member_id IN (SELECT id FROM group_members WHERE group_id='$groupId' AND active='n')) ";
		$r10=parent::update($q10);
		/* deactive regular invoices*/
		$q11="UPDATE bill_invoice_regular SET active='n'  WHERE bill_invoice_id IN (SELECT id FROM member_billing WHERE member_id IN (SELECT id FROM group_members WHERE group_id='$groupId' AND active='n')) ";
		$r11=parent::update($q11);
		/* deactive Member bookings */
		$q12="UPDATE member_bookingInfo SET active='n'  WHERE member_id IN (SELECT id FROM group_members WHERE group_id='$groupId' AND active='n') ";
		$r12=parent::update($q12);
		/* deactive booking usage */
		$q13="UPDATE member_usageInfo SET active='n'  WHERE booking_id IN (SELECT id FROM member_bookingInfo WHERE member_id IN (SELECT id FROM group_members WHERE group_id='$groupId' AND active='n')) ";
		$r13=parent::update($q13);
		/* deactive usage detailed info */
		$q14="UPDATE booking_usageInfo SET active='n'  WHERE id IN (SELECT usageUnitValue FROM member_usageInfo WHERE booking_id IN (SELECT id FROM member_bookingInfo WHERE member_id IN (SELECT id FROM group_members WHERE group_id='$groupId' AND active='n'))') ";
		$r14=parent::update($q14);
		
		/* deactive Members */
		$q7="UPDATE listing_photos SET active='n'  WHERE type='G' AND type_id='$groupId' ";
		$r7=parent::update($q7);
		
		/* deactive group */
		$current_dt = date('Y-m-d H:i:s');
		$q15="UPDATE group_info SET modified_dt='$current_dt',active='n',resume='n',deleted='y' WHERE id='$groupId' ";
		$r15=parent::update($q15);
	}
	function addGroup()
	{
		$user_id=$_SESSION['user_id'];
		$currenttime=date("Y-m-d H:i:s");
		$active='n';
		$adminMemActive='y';
		$status='P';
		
		$group_type = $_SESSION['group_type'];
		$groupTypeInfo = explode("_",$group_type);
		$groupType = $groupTypeInfo[0];
		
		$q4="INSERT INTO group_members(user_id,active,created_dt,modified_dt) VALUES('$user_id','$adminMemActive','$currenttime','$currenttime')";
		$r4=parent::insert($q4);
		
		if(!empty($r4))
		{
			if($groupType=='exist') {
				$listId = $groupTypeInfo[1];
				$list_record=$this->getResult("listing_items",""," WHERE approved=1 AND id='$listId' ","");
				$title = $list_record[0]['name'];
				$query="INSERT INTO group_info(list_id,title,prev_Admin_id,prev_FC_id,prev_MC_id,status,active,created_dt,modified_dt,deleted,resume) VALUES('$listId','$title','$r4','$r4','$r4','$status','$active','$currenttime','$currenttime','n','n')";
				$result=parent::insert($query);
				$q5="UPDATE group_members SET group_id='$result' WHERE id='$r4' AND active='y' ";	
				$r5=parent::update($q5);
				$affected_id=$result;
			} else if($groupType=='scratch') {
				$query="INSERT INTO group_info(prev_Admin_id,prev_FC_id,prev_MC_id,active,created_dt,modified_dt,deleted,resume) VALUES('$r4','$r4','$r4','$active','$currenttime','$currenttime','n','n')";
				$result=parent::insert($query);
				$q5="UPDATE group_members SET group_id='$result' WHERE id='$r4' AND active='y' ";	
				$r5=parent::update($q5);
				$affected_id=$result;
			} else {
				$result=0;
			}
		}else
		{
			$result=0;
		}
		
		return $result;
	}
	
	/* start store basic group Info */
	function store_group_Basic($_POST,$groupId)
	{	
		$groupTitle=$_POST['groupTitle'];
		$user_id=$_SESSION['user_id'];
		$currenttime=date("Y-m-d H:i:s");
		
		$tempImagePath = 'public/images/tmp/assets/';
		$uploaddir="public/images/group/assets/";
		$uploadTempDir="public/images/group/thumbs/";
		$image='groupImg';
		$groupId=$_POST['groupId'] ;
		$groupAC=$_POST['groupAC'] ;
		$imgName=$_POST['groupImageName'];
		$imgSize=$_POST['groupImageSize'];
		$storedImageSrc = $_POST['storedImageSrc'];
		$imgTmpName = $tempImagePath . $imgName;
		$imgType = NULL;
		
		if( (!empty($groupId)) && empty($imgName)  && empty($imgSize))
		{
			$query="UPDATE group_info SET title='$groupTitle' WHERE id='$groupId' AND deleted='n' ";
			$result=parent::update($query);
		}
		else
		{
			if( (!empty($groupId)) && (!empty($imgName))  && (!empty($imgSize)) )
			{
				$listId = 0;
				$imageId='group_'.$groupId;
				
				$_FILESVALUES = array (
					$image  => array("name" => $imgName, "type" => $imgType, "tmp_path" => $imgTmpName, "size" => $imgSize)
				);
			}
			else
			{
				$groupId = $this->addGroup();
				$imageId='group_'.$groupId;
				$listId = $_POST['listId'];
				if(!empty($listId))
				{	
					$_FILESVALUES = NULL;
				}
				else
				{	
					$listId = 0;
					
					$_FILESVALUES = array (
						$image  => array("name" => $imgName, "type" => $imgType, "tmp_path" => $imgTmpName, "size" => $imgSize)
					);
				}
			}
			
			
			$uploadedResult=$this->store_uploaded_image($_FILESVALUES,$image,$imageId,$uploaddir,$listId,$uploadTempDir);
			$affectedId = $uploadedResult[0];
			$affected_msg = $uploadedResult[1];
			
			if(empty($affectedId) || isset($affected_msg))
			{
				$result =0;
			}
			else
			{
				$query="UPDATE group_info SET title='$groupTitle',modified_dt='$currenttime' WHERE id='$groupId'";
				$result=parent::update($query);
			}
		}
		
		
		if(!empty($result) || !empty($groupId))
		{
			return $groupId;
		}
		else
		{
			return 0;
		}
	}
	/* end store basic group Info */
	
	/* start member preferances Info */
	function store_group_members($_POST,$groupId)
	{	
		$adminRadio=$_POST['adminRadio'];
		$mcRadio=$_POST['mcRadio'];
		$fcRadio=$_POST['fcRadio'];
		//$user_id=$_SESSION['user_id'];
		$currenttime=date("Y-m-d H:i:s");
		if(!empty($groupId))
		{
			$query="UPDATE group_info SET prev_Admin_id='$adminRadio',prev_MC_id='$mcRadio',prev_FC_id='$fcRadio' WHERE id='$groupId' AND deleted='n' ";
			$result=parent::update($query);
			if(!empty($result))
			{
				return $groupId;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return 0;
		}
	}
	/* end store member preferances Info */
	
	/* start store billing group Info */
	function store_billing_rules($_POST,$groupId)
	{	
		$result = array();
		$currenttime=date("Y-m-d H:i:s");
		
		//update billing preferences
			$billingInfo=$this->getResult("group_billing_type",""," WHERE group_id='$groupId'  AND active='y' "," order by id desc");
			if(count($billingInfo)!=0) {
			 for($b=0;$b<count($billingInfo);$b++) 
			 {
				$billId=$billingInfo[$b]['id'];
				$billType=$billingInfo[$b]['bill_type'];
				$monthCost=$_POST['billCost'.$billId];
				$cost_curr=$_POST['billCurrency'.$billId];
				$detail=$_POST['billDetail'.$billId];
				$currenttime=date("Y-m-d H:i:s");
				
				$bill_query="UPDATE group_billing_type SET cost='$monthCost',cost_curr='$cost_curr',type_detail='$detail',modified_dt='$currenttime'  WHERE id='$billId' AND active='y' ";
				$bill_result=parent::update($bill_query);
			 }
			} 
		
		//insert billing preferences
		foreach($_POST as $key =>$value)
		{
			$valueArray = explode("_",$key);
			$valType  = $valueArray[0];
			$valSeqNo = $valueArray[1];
			
			if(!empty($value))
			{
				if($valType=='monthDesc')	////usage unit 
				{
					$monthCost=$_POST['monthCost_'.$valSeqNo];
					$cost_curr=$_POST['monthCurrency_'.$valSeqNo];
					$billType='M';
					$detail=$_POST['monthDesc_'.$valSeqNo];
					$month_q1="INSERT INTO group_billing_type (group_id,cost,cost_curr,bill_type,type_detail,created_dt,modified_dt,active)
								VALUES('$groupId','$monthCost','$cost_curr','$billType','$detail','$currenttime','$currenttime','y')";
					$month_r1=parent::insert($month_q1);
				}
				if($valType=='usageCost')	////start end info
				{	
					$monthCost=$_POST['usageCost_'.$valSeqNo];
					$cost_curr=$_POST['usageCurrency_'.$valSeqNo];
					$billType='U';
					$usageType=$_POST['usageList_'.$valSeqNo];
					/*$detail='usage_' . $usageType;*/
					$detail=$usageType;
					
					$usage_q2="INSERT INTO group_billing_type (group_id,cost,cost_curr,bill_type,type_detail,created_dt,modified_dt,active)
							VALUES('$groupId','$monthCost','$cost_curr','$billType','$detail','$currenttime','$currenttime','y')";
					$usage_r2=parent::insert($usage_q2);
				}
			}
		}
		if(!empty($month_r1) || !empty($usage_r2))
		{
			$this->memberBilling($groupId);
		}
		array_push($result,$month_r1);
		array_push($result,$usage_r2);
		return $result;
	}
	/* end store billing group Info */
	/* start store usage group Info */
	function store_usage_rules($_POST,$groupId)
	{	
		$result = 1;
		//usage moniters info
		$timeUsage=$_POST['timeUsage'];
		$totalStartEnd=$_POST['startEnd'];
		$currenttime=date("Y-m-d H:i:s");
		
		//update usage monitors values
		$usageMonitorInfo=$this->getResult("group_usageMonitor",""," WHERE group_id='$groupId' AND type='SE' "," order by id desc");
		if(count($usageMonitorInfo)!=0) {
		 for($unit=0;$unit<count($usageMonitorInfo);$unit++) { 
			$usageId=$usageMonitorInfo[$unit]['id'];
			$usageUnit=$_POST['usageMonitor'.$usageId];
			$currentMonitor=$_POST['currentMonitor'.$usageId];
				
			$usage_q1="UPDATE group_usageMonitor SET unit='$usageUnit',currentValue='$currentMonitor',modified_dt='$currenttime'  WHERE id='$usageId'";
			$usage_r1=parent::update($usage_q1);
			if(empty($usage_r1))
			{
				$result = 0;
			}
		 }
		}
		
		$timeMoniterInfo=$this->getResult("group_usageMonitor",""," WHERE group_id='$groupId' AND type='T' "," order by id desc");
		if(count($timeMoniterInfo)==0 && ($timeUsage!=0))
		{
			$thisUnit = 'time';
			$usage_type = 'T';
			$usage_q1="INSERT INTO group_usageMonitor (group_id,unit,type,active,created_dt,modified_dt) 
							VALUES('$groupId','$thisUnit','$usage_type','n','$currenttime','$currenttime')";
			$usage_r1=parent::insert($usage_q1);
			if(empty($usage_r1))
			{
				$result = 0;
			}
		}
		
		foreach($_POST as $key =>$value)
		{
			$valueArray = explode("_",$key);
			$valType  = $valueArray[0];
			$valSeqNo = $valueArray[1];
			if(!empty($value))
			{
				if($valType=='txtStartEndUnit')	////start end info
				{	
					$usageUnit=$value;
					$usage_type='SE';
					$monitorValue = $_POST['currentMonitor_'.$valSeqNo];
					$startEnd_q2="INSERT INTO group_usageMonitor (group_id,unit,type,currentValue,active,created_dt,modified_dt) 
							VALUES('$groupId','$usageUnit','$usage_type','$monitorValue','n','$currenttime','$currenttime')";
					$startEnd_r2=parent::insert($startEnd_q2);
					if(empty($startEnd_r2))
					{
						$result = 0;
					}
				}
			}
		}
		
		return $result;
	}
	/* end store basic group Info */
	/* start store book rules Info */
	function store_book_rules($_POST,$groupId)
	{	
		//booking rules info
		$bookRule=$_POST['bookRule'];
		$selectTime=$_POST['selectTime'];
		$txttotalBooking=$_POST['txttotalBooking'];
		$txtWeekBooking=$_POST['txtWeekBooking'];
		$txtTotalHolidays=$_POST['txtTotalHolidays'];
		$txtHolidays=$_POST['txtHolidays'];
		$currenttime=date("Y-m-d H:i:s");
		
		//booking rules updation
		if($bookRule!='none'){
			if($bookRule=='rule') {
				$ruleInfo=$this->getResult("group_bookRules",""," WHERE group_id= '$groupId' AND active='y' "," order by id desc");
				$ruleId=$ruleInfo[0]['id'];
				if(!empty($ruleId)) {	
				
						$rule_q1="UPDATE group_bookRules SET totalDays='$txttotalBooking',  weekendDays='$txtWeekBooking', totalHolidays='$txtTotalHolidays', perHolidays='$txtHolidays', modified_dt='$currenttime', active='$active' WHERE id='$ruleId' AND active='y' ";
						$rule_r1=parent::update($rule_q1);
					$book_rule_id='rule_' . $ruleId; 
				} else {
					$q2="INSERT INTO group_bookRules(	group_id,totalDays,weekendDays,totalHolidays,perHolidays,active,created_dt,modified_dt) 
						VALUES('$groupId','$txttotalBooking','$txtWeekBooking','$txtTotalHolidays','$txtHolidays','y','$currenttime','$currenttime')";
					$r2=parent::insert($q2);
					$book_rule_id='rule_' . $r2; 
				}
			} else {
				$book_rule_id='rota_' . $selectTime; }
				
			//Update book rule in group table
			$query="UPDATE group_info SET booking_rule='$book_rule_id' WHERE id='$groupId'  AND deleted='n' ";
			$result=parent::update($query);
		}
		return $result;
	}
	/* end store book rules Info */
	
	function group_activation($groupId)
	{
			$currentDt=date("Y-m-d H:i:s");
			$query="UPDATE group_info SET status='F',modified_dt='$currentDt' WHERE id='$groupId' AND deleted='n' ";
			$result=parent::update($query);
			
			$groupInfo=$this->getResult("group_info",""," WHERE id='$groupId' AND active='n' ","");	
			if(count($groupInfo)!=0)
			{
				$groupTitle = $groupInfo[0]['title'];
				if(empty($groupTitle))
				{
					$groupTitle = 'No Title';
				}
				$groupAdminId = $groupInfo[0]['prev_Admin_id'];
				$adminUserInfo = $this->getResult("auth_users","","  WHERE id IN (SELECT user_id FROM group_members WHERE id='$groupAdminId' ) AND active='y' ","");
				$from_userName = $adminUserInfo[0]['username'];
				$from_emailId = $adminUserInfo[0]['email'];
				$adminProfileInfo = $this->getResult("auth_profile",""," WHERE user_id IN (SELECT id FROM auth_users WHERE id IN (SELECT user_id FROM group_members WHERE id='$groupAdminId' ) AND active='y' ) ","");
				$groupAdminFullName = $adminProfileInfo[0]['first_name']." ".$adminProfileInfo[0]['last_name'];
				
				$to_indul_adminEmail = commonModel :: indulgeon_admin_emailId;
				$to_indul_adminName = commonModel :: indulgeon_admin_name;
				$indulgeon_url = commonModel :: indulgeon_url;
				$emailMsg="This is request to activate following group: \n *";
				$emailMsg=$emailMsg . $groupTitle. "* \n Administrator : ";
				$emailMsg=$emailMsg . $groupAdminFullName." \n \n \n : ";
				$emailMsg=$emailMsg . "\n Click Here to activate this group ".$indulgeon_url."index.php?process=indulgeonAdmin&param1=group&param2=".$groupId;
				$emailSubject="Group Confirmation Request";
				$mailSentMsg = parent::mailSend($from_userName,$from_emailId,$to_indul_adminName,$to_indul_adminEmail,$emailSubject,$emailMsg);
				if(!empty($mailSentMsg))
				{	
					$finalMessage = 0;
				}
				else
				{
					$message = "Error!! group activation email not sent";
					$finalMessage = $message;
				}
			}
			else
			{
				$message = "Error!! group activation email not sent";
				$finalMessage = $message;
			}
			return $finalMessage;
	}
	
	/* start store Booking */
	function storeBooking($_POST,$bookId)
	{
		$currenttime=date("Y-m-d");
		$groupId=$_POST['groupId'];
		$memberId=$_POST['memberId'];
		$start_dt=$_POST['start_dt'];
		$finish_dt=$_POST['finish_dt'];
		$groupMC=$_POST['groupMC'];
		$mc_desc=$_POST['txtMOT'];
		
		$startDt=explode("-",$start_dt);
		$finishDt=explode("-",$finish_dt);
		
		$s_dt=$startDt[0]."-".$startDt[1]."-".$startDt[2];
		$f_dt=$finishDt[0]."-".$finishDt[1]."-".$finishDt[2];
		//$todayDate = date('Y-m-d');
		$s_t=$startDt[5].":00:00";
		$f_t=$finishDt[5].":00:00";
		if(empty($bookId))
		{	
			if($groupMC=='M')
			{
				$type='MC';
				$thisMemId = $memberInfo[$m]['id'];
			
				$condCheck1 = $this->getResult("member_bookingInfo",""," WHERE active = 'y' AND ( startDate > '$s_dt' OR (startDate = '$s_dt' AND startTime >= '$s_t') ) AND (finishDate < '$f_dt' OR (finishDate = '$f_dt' AND finishTime <= '$f_t')) AND member_id IN (SELECT id FROM group_members WHERE group_id='$groupId'  AND active='y') "," order by startDate,startTime ");
				if(count($condCheck1)!=0)
				{
					foreach($condCheck1 as $c1=>$checkVal)
					{
						$thiscondId = $condCheck1[$c1]['id'];
						
						$d3="DELETE FROM member_bookingInfo  WHERE id='$thiscondId' ";
						$dr3 = parent::delete($d3);
					}
				}
				$condCheck2 = $this->getResult("member_bookingInfo",""," WHERE  active='y' AND (startDate > '$s_dt' OR (startDate = '$s_dt' AND startTime >= '$s_t') ) AND (startDate < '$f_dt' OR (startDate = '$f_dt' AND startTime <= '$f_t') ) AND (finishDate > '$f_dt' OR (finishDate = '$f_dt' AND finishTime > '$f_t')) AND member_id IN (SELECT id FROM group_members WHERE group_id='$groupId'  AND active='y') "," order by startDate,startTime ");
				if(count($condCheck2)!=0)
				{
						$thiscond2Id = $condCheck2[0]['id'];
						
						$q2 = "UPDATE member_bookingInfo  SET startDate='$f_dt',startTime='$f_t',modified_dt='$currenttime' WHERE id='$thiscond2Id' ";
						$r2=parent::update($q2);
				}
				$condCheck3 = $this->getResult("member_bookingInfo",""," WHERE  active='y' AND (startDate < '$s_dt' OR (startDate = '$s_dt' AND startTime < '$s_t') ) AND (finishDate > '$s_dt' OR (finishDate = '$s_dt' AND finishTime >= '$s_t') ) AND (finishDate < '$f_dt' OR (finishDate = '$f_dt' AND finishTime <= '$f_t')) AND member_id IN (SELECT id FROM group_members WHERE group_id='$groupId'  AND active='y') ","");
				if(count($condCheck3)!=0)
				{
						$thiscond3Id = $condCheck3[0]['id'];
						
						$q3 = "UPDATE member_bookingInfo SET finishDate='$s_dt',finishTime='$s_t',modified_dt='$currenttime' WHERE id='$thiscond3Id' ";
						$r3=parent::update($q3);
				}
				$flagAddBooking =false;
				$resCondCheck4=$this->getResult("member_bookingInfo",""," WHERE ( startDate < '$s_dt' OR (startDate = '$s_dt' AND startTime < '$s_t') ) AND (finishDate > '$f_dt' OR (finishDate = '$f_dt' AND finishTime > '$f_t')) AND active = 'y' "," order by startDate,startTime AND member_id IN (SELECT id FROM group_members WHERE group_id='$groupId'  AND active='y') ","");
				if(count($resCondCheck4)!=0)
				{
					$thisBookId = $startDTBookInfo[0]['id'];
					$thisType = $startDTBookInfo[0]['type'];
					$thisfinishDate = $startDTBookInfo[0]['finishDate'];
					$thisfinishTime = $startDTBookInfo[0]['finishTime'];
					$thisCreated_dt = $startDTBookInfo[0]['created_dt'];
					if($thisType=='N')
					{
						$condCheck4 = "UPDATE member_bookingInfo  SET finishDate='$s_dt',finishTime='$s_t',modified_dt='$currenttime' WHERE id='$thisBookId'";
						$r4=parent::update($condCheck4);
						
						$addCondCheck4="INSERT INTO member_bookingInfo (member_id,type,startDate,startTime,finishDate,finishTime,mc_desc,created_dt,modified_dt,usageData,active) VALUES('$memberId','$type','$f_dt','$f_t','$thisfinishDate','$thisfinishTime','','$thisCreated_dt','$currenttime','N','y')";
						$res4=parent::insert($addCondCheck4);
					}
					else
					{
						$flagAddBooking =true;
					}
				}
				if($flagAddBooking == false)
				{
					$query="INSERT INTO member_bookingInfo (member_id,type,startDate,startTime,finishDate,finishTime,mc_desc,created_dt,modified_dt,usageData,active) VALUES('$memberId','$type','$s_dt','$s_t','$f_dt','$f_t','$mc_desc','$currenttime','$currenttime','N','y')";
					$result=parent::insert($query);
				}
			}
			else
			{
				$type='N';
				$query="INSERT INTO member_bookingInfo (member_id,type,startDate,startTime,finishDate,finishTime,created_dt,modified_dt,active) VALUES('$memberId','$type','$s_dt','$s_t','$f_dt','$f_t','$currenttime','$currenttime','y')";
				$result=parent::insert($query);
			}
		}
		return $result;
	}
	/* start store Booking */
	/* calendar Availability */
	function calendarAvailable($memberId,$month)
	{
		$allAvl=array();
		$notAvl=array();
		$partAvl=array();
		$myBook=array();
		
		$thisGroupInfo = $this->getResult("group_members",""," WHERE  id='$memberId' AND active='y' ","");
		$thisGroupId = $thisGroupInfo[0]['group_id'];
		$memberBookInfoArray=$this->getResult("member_bookingInfo"," startDate,finishDate "," WHERE member_id IN (SELECT id FROM group_members WHERE group_id='$thisGroupId'  AND active='y') AND active='y' "," order by startDate,startTime ");
		$memberBookInfo = array ();
		for($m=0;$m<count($memberBookInfoArray);$m++)
		{
			$sdt=$memberBookInfoArray[$m]['startDate'];
			if (!in_array($sdt,$memberBookInfo)) array_push($memberBookInfo,$sdt);
			$fdt=$memberBookInfoArray[$m]['finishDate'];
			if (!in_array($fdt,$memberBookInfo)) array_push($memberBookInfo,$fdt);
		}
		//$myBookInfo=$this->getResult("member_bookingInfo",""," WHERE member_id='$memberId' ","");
		for($m=0;$m<count($memberBookInfo);$m++)
		{
			$com_date=$memberBookInfo[$m] ;
			$cm_d=strtotime($com_date);
			if(date('Ymd',$cm_d) >= date('Ymd'))
			{
				$dayBookInfo=$this->getResult("member_bookingInfo",""," WHERE startDate='$com_date' OR finishDate='$com_date' AND  member_id IN (SELECT id FROM group_members WHERE group_id='$thisGroupId'  AND active='y') AND active='y' "," order by startDate,startTime ");
				$dayUsedCnt=0;
				for($sm=0;$sm<count($dayBookInfo);$sm++)
				{
					$start_d=strtotime($dayBookInfo[$sm]['startDate']);
					$finish_d=strtotime($dayBookInfo[$sm]['finishDate']);
					$starthour=strtotime($dayBookInfo[$sm]['startDate']." ".$dayBookInfo[$sm]['startTime']);
					$finishhour=strtotime($dayBookInfo[$sm]['finishDate']." ".$dayBookInfo[$sm]['finishTime']);
					if($start_d==$finish_d)
					{
						$f_hour=date('H',$finishhour);
						$s_hour=date('H',$starthour);
						if($f_hour==00 || $f_hour==0) {
							$f_hour=24;
						}
						$hourDiff=$f_hour-$s_hour;
						$dayUsedCnt=$dayUsedCnt+$hourDiff;
					} 
					else
					{
						if(date('Ymd',$finish_d) > date('Ymd',$start_d)){
							$s=strtotime('+1 day',$start_d);
							//$f=strtotime('-1 day',$f);
							$f=$finish_d;
							while($s < $f){
								array_push($notAvl,date('Ymd', $s));
								$s = strtotime('+1 day',$s);
							}
						}
						//echo date('Y-m-d',$cm_d) ."/" . date('Y-m-d',$start_d) . "/" . date('Y-m-d',$finish_d)."<br/>";
						 if($start_d==$cm_d){
							$f_hour=24;
							$s_hour=date('H',$starthour);
							$hourDiff=$f_hour-$s_hour;
							$dayUsedCnt=$dayUsedCnt+$hourDiff;
						}else if($finish_d=$cm_d){
							$f_hour=date('H',$finishhour);
							$s_hour=0;
							$hourDiff=$f_hour-$s_hour;
							$dayUsedCnt=$dayUsedCnt+$hourDiff;
						}
					}
				}
				if($dayUsedCnt == 24)
				{
					array_push($notAvl,date('Ymd', $cm_d));
				} else if($dayUsedCnt > 0){
					array_push($partAvl,date('Ymd', $cm_d));
				}
			} else {
				array_push($notAvl,date('Ymd', $cm_d));
			}
		} 
		array_push($allAvl,$partAvl);
		array_push($allAvl,$notAvl);
		
		//$myBook=$this->myBooking($memberId);
		//array_push($allAvl,$myBook);
		return $allAvl;
	}
	/* calendar */
	/*My Booking */
	function myBooking($memberId,$groupMC)
	{
		//$myBookDetails=array();
		$myBook=array();
		//$myBookIds=array();
		if($groupMC=='M')
		{
			$type='MC';
		}
		else
		{
			$type='N';
		}
		$myBookInfo=$this->getResult("member_bookingInfo",""," WHERE member_id='$memberId' AND  active='y' AND type='$type' ","");
		for($n=0;$n<count($myBookInfo);$n++)
		{
			$n_startDate=$myBookInfo[$n]['startDate'];
			$n_finishDate=$myBookInfo[$n]['finishDate'];
			$n_startTime=$myBookInfo[$n]['startTime'];
			$n_finishTime=$myBookInfo[$n]['finishTime'];
			$bookId=$myBookInfo[$n]['id'];
			
			$n_start_d=strtotime($n_startDate . " " .$n_startTime);
			$n_finish_d=strtotime($n_finishDate . " " . $n_finishTime);
				if($n_finish_d!=$n_start_d) {
					if($n_finish_d>$n_start_d){	
						//$s=strtotime('+1 day',$n_start_d);
						$s=$n_start_d;
						//$f=strtotime('-1 day',$f);
						$f=$n_finish_d;
								while($s<$f){
									array_push($myBook,date('Ymd', $s));
									$s = strtotime('+1 day',$s);
								}
						}
						//$s=strtotime('+1 day',$n_start_d);
				}
				array_push($myBook,date('Ymd', $n_start_d));
				if(date('H',$n_finish_d)!=0) {
					array_push($myBook,date('Ymd', $n_finish_d));
				}
			array_push($myBookIds,date('Ymd', $bookId));
		}
		//BookDetails
		//array_push($BookDetails,$myBook);
		//$BookDetails=$myBook;
		//array_push($BookDetails,$myBookIds);
		//print_r($BookDetails);
		return $myBook;
	}
	/* My Booking */
	
	/* Time Slots */
	function timeSlot($current_dt,$start_dt,$groupMC,$memberId)
	{
		$allInfo=array();
		$startDt=explode("-",$start_dt);
		$current_d=date('Y-m-d',$current_dt);
		
		$thisGroupInfo = $this->getResult("group_members",""," WHERE  id='$memberId' AND active='y' ","");
		$thisGroupId = $thisGroupInfo[0]['group_id'];
		
		$memberBookInfo=$this->getResult("member_bookingInfo",""," WHERE (startDate <= '$current_d') AND (finishDate >= '$current_d') AND  member_id IN (SELECT id FROM group_members WHERE group_id='$thisGroupId'  AND active='y') AND  active='y' "," order by startDate,startTime ");
		if(!empty($start_dt) && $start_dt!='none')
		{	
			if(date('Ymd',$current_dt) == ($startDt[0].$startDt[1].$startDt[2]))
			{	
				unset($_SESSION['noFinish']);
				if(count($memberBookInfo)==0)
				{
					$startFinishId=0;
					$endFinishId=24;	//print Finish
				}
				else {
					$setStatus="startFinish";	//start Finish
				}
			} 
			else if(date('Ymd',$current_dt) > ($startDt[0].$startDt[1].$startDt[2]))
			{	
				if(isset($_SESSION['noFinish']))
				{
					$startFinishId=24;
					$endFinishId=-1;	//dont print finish
				} else {
					//dont print finish
					$allAvl=$this->calendarAvailable($memberId,$month);
					$partAvl=$allAvl[0];
					$notAvl=$allAvl[1];
					/* First No Available Day */
						$j=0;
						while($j<count($notAvl))
						{
							if($notAvl[$j] >($startDt[0].$startDt[1].$startDt[2]) && (empty($firstNotDay)))
							{
								$firstNotDay=$notAvl[$j];
								break;
							}
							$j++;
						}
					/* First Partial Day */
					$i=0;
					while($i<count($partAvl))
					{	//echo $partAvl[$i] . "  " . $startDt[0].$startDt[1].$startDt[2] ."<br/>";
						if($partAvl[$i] >($startDt[0].$startDt[1].$startDt[2]) && (empty($firstPartDay)))
						{
							$firstPartDay=$partAvl[$i];
							break;
						}
						$i++;
					}
					
					if(!isset($firstNotDay) && isset($firstPartDay))
					{
						$firstDay = $firstPartDay;
					}
					else if(!isset($firstPartDay) && isset($firstNotDay))
					{
						$firstDay = $firstNotDay;
					}
					else
					{
						if($firstNotDay < $firstPartDay)
						{	$firstDay=$firstNotDay;
						} else if($firstPartDay < $firstNotDay) {
							$firstDay=$firstPartDay;
						}
					}
				
					if(!empty($firstDay) || isset($firstDay))
					{
						if (in_array(date('Ymd',$current_dt),$notAvl)) {
							$startFinishId=24;
							$endFinishId=-1;	//dont print finish	
						} else if(date('Ymd',$current_dt)==$firstNotDay)
						{	
							$startFinishId=24;
							$endFinishId=-1;	//dont print finish
						} else if(date('Ymd',$current_dt) == $firstPartDay){
							$setStatus="endFinish"; //end Finish
							//echo $setStatus ."<br/>";
						} else if(date('Ymd',$current_dt) < $firstDay)
						{	
							$startFinishId=0;
							$endFinishId=24;	//print Finish
						} else if(date('Ymd',$current_dt) > $firstDay) {
							$startFinishId=24;
							$endFinishId=-1;	//dont print finish
						}
					}
				}
			}
		} 
		$bookIds = array();
		$members=array();
		if(count($memberBookInfo)!=0)
		{
			for($m=0;$m<count($memberBookInfo);$m++)
			{
				$bookId='bookId' . $m;
				$$bookId=array();
				$member='member' . $m;
				$$member=array();
				
				$startDate=$memberBookInfo[$m]['startDate'];
				$finishDate=$memberBookInfo[$m]['finishDate'];
				$startTime=$memberBookInfo[$m]['startTime'];
				$finishTime=$memberBookInfo[$m]['finishTime'];
				$mc_desc=$memberBookInfo[$m]['mc_desc'];
				
				$memId=$memberBookInfo[$m]['member_id'];
				$bid=$memberBookInfo[$m]['id'];
				$book_type=$memberBookInfo[$m]['type'];
				array_push($$bookId,$bid);
				array_push($$member,$memId);
				
				$userIdInfo=$this->getResult("group_members"," user_id "," WHERE id='$memId' AND  active='y' "," order by id ");
				$start_d=strtotime($startDate . " " .$startTime);
				$finish_d=strtotime($finishDate . " " . $finishTime);
				if($book_type=='MC')
				{
					if(!empty($mc_desc))
					{
						$shortName=substr($mc_desc, 0, 5);
					}
					else
					{
						$shortName='MOT';
					}
				}
				else
				{
					
					$userId=$userIdInfo[0]['user_id'];
					$memberNameInfo=$this->getResult("auth_profile"," first_name,last_name "," WHERE id='$userId'","");
					$firstName= $memberNameInfo[0]['first_name'];
					$lastName= $memberNameInfo[0]['last_name'];
					$firstName=substr($firstName, 0, 4);
					$lastName=substr($lastName, 0, 1);
					$shortName=$firstName ." " . $lastName;
				}
				array_push($$member,$shortName);
				//echo $startDate .'=='.$current_d .'&&'. $startTime.'!='.'23:00:00' .'||'. $finishDate.'=='.$current_d .'&&'. $finishTime.'!='.'00:00:00';
				//if(($startDate==$current_d && $startTime!='23:00:00') || ($finishDate==$current_d && $finishTime!='00:00:00'))
				{
					if(date('Ymd',$start_d)==date('Ymd',$finish_d))
					{
						$start_tId=date('H',$start_d);
						$finish_tId=date('H',$finish_d);
						$curr_finish_tId=date('H',$finish_d);
					} else if(date('Ymd',$current_dt) == date('Ymd',$start_d)) {
							$start_tId=date('H',$start_d);
							$finish_tId=24;
							$curr_finish_tId=0;
					} else if(date('Ymd',$current_dt) == date('Ymd',$finish_d)) {	
							/*$start_tId=0;
							$finish_tId=date('H',$finish_d);
							$curr_finish_tId=date('H',$finish_d);*/
							$start_tId=0;
							$finish_tId=date('H',$finish_d);
							$curr_finish_tId=date('H',$finish_d);
							if(date('H',$finish_d)==0)
							{
								$curr_finish_tId=24;
							}
					} else {
						$start_tId=0;
						$finish_tId=24;
						$curr_finish_tId=0;
					}
					$nextFlag=false;
					if(($m+1)<count($memberBookInfo)) {
						$next_start=strtotime($memberBookInfo[$m+1]['startDate'] . " " .$memberBookInfo[$m+1]['startTime']);
						$next_finish=strtotime($memberBookInfo[$m+1]['finishDate'] . " " . $memberBookInfo[$m+1]['finishTime']);
						if(date('Ymd',$next_start)==date('Ymd',$next_finish))
						{
							$next_start_tId=date('H',$next_start);
							$next_finish_tId=date('H',$next_finish);
						} else if(date('Ymd',$current_dt) == date('Ymd',$next_start))
						{
							$next_start_tId=date('H',$next_start);
						}
						$nextFlag=true;
						
					} else {
						if($curr_finish_tId==0)
						{
							$next_start_tId=$start_tId;
							$nextFlag=true;
							
						} else {
							$next_start_tId=24;
						}
					}
					
					if($setStatus=="startFinish" && ($startDt[5] >= $curr_finish_tId) && ($startDt[5] <= $next_start_tId))
					{
						if( $nextFlag==true )
						{
							//check if finish time and next finish time are on same day and selected time is in between them
							if( ($startDt[5] >= $curr_finish_tId) && ($startDt[5] <= $next_start_tId) && $setStatus=="startFinish") 
							{
								$_SESSION['noFinish']=$curr_finish_tId; 
							}
						}
						$startFinishId=$startDt[5]+1;
						$endFinishId=$next_start_tId;
						$setStatus='';
					} else if($setStatus=="endFinish")
					{
						$startFinishId=0;
						$endFinishId=$curr_finish_tId;
						$setStatus='';
					}
				}
				array_push($$member,$start_tId);
				array_push($$member,$finish_tId);
				array_push($bookIds,$$bookId);
				array_push($members,$$member);
			}
		}
		
		
		array_push($allInfo,$members);
		array_push($allInfo,$startFinishId);
		array_push($allInfo,$endFinishId);
		array_push($allInfo,$bookIds);
		return $allInfo;
	}
	/*end time slots */
	
	/*start Add Member */
	function storeMember($allvar)
		{
			$user_id=$allvar['addMem'];
			$memId=$allvar['memId'];
			$groupId=$allvar['groupId'];
			
			$active='n';
			$currenttime=date("Y-m-d H:i:s");
			
			if(!empty($memId))
			{
				$query="UPDATE group_members SET user_id='$user_id',active='$active' WHERE id='$memId'";
				$result=parent::update($query);
			}
			else
			{	
				$query="INSERT INTO group_members(user_id,group_id,active,created_dt,modified_dt) VALUES('$user_id','$groupId','$active','$currenttime','$currenttime')";
				$result=parent::insert($query);
				$memId = $result;
			}
			
			if(!empty($result))
			{
				$thisUserInfo=$this->getResult("auth_users",""," WHERE id='$user_id' AND active='y' ","");	
				$toUserName = $thisUserInfo[0]['username'];
				$toUserEmailId = $thisUserInfo[0]['email'];
				
				$from_indul_adminEmail = commonModel :: indulgeon_admin_emailId;
				$from_indul_adminName = commonModel :: indulgeon_admin_name;
				$indulgeon_url = commonModel :: indulgeon_url;
				$fromnm=$firstnm."  " .$lastnm;
				$emailMsg="Click Here to activate your membership in group ".$indulgeon_url."index.php?process=activateMember&param1=".$memId;
				$emailSubject = "Accept Group Invitation";
				
				$mailStatus = parent::mailSend($from_indul_adminName,$from_indul_adminEmail,$toUserName,$toUserEmailId,$emailSubject,$emailMsg);
				$result = $mailStatus;
			}
			else
			{
				$result = 0;
			}
			
			return $result;
		}
		
		/*end Add Member */
		
		/* delete member */
		function deleteMember($_POST)
		{
			 $memId=$_POST['memId'];
			 $groupId=$_POST['groupId'];
			 $user_id=$_SESSION['user_id'];
			 $prev_admin=$_POST['prev_a'];
			 $prev_mc=$_POST['prev_m'];
			 $prev_fc= $_POST['prev_f'];
			 $today=date('Y-m-d');
			 $todayNow = date('H:i:s');
			
			$adminMem=$this->getResult("group_members"," id "," WHERE user_id= '$user_id' AND group_id='$groupId' AND  active='y' ","");
			$admMem=$adminMem[0]['id'];
			if($prev_admin!=0) {
				$q1="UPDATE group_info SET prev_Admin_id='$admMem' WHERE id='$groupId' AND deleted='n' ";
				$r1=parent::update($q1);	
			}
			if($prev_mc!=0) {
				$q2="UPDATE group_info SET  prev_MC_id='$admMem'  WHERE id='$groupId' AND deleted='n' ";	
				$r2=parent::update($q2);
			}
			if($prev_fc!=0) {
				$q3="UPDATE group_info SET  prev_FC_id='$admMem'  WHERE id='$groupId' AND deleted='n' ";	
				$r3=parent::update($q3);
			}
			
			$query2="UPDATE member_bookingInfo SET active='n' WHERE member_id='$memId' AND usageData='F' AND type='N'  ";
			$result2=parent::update($query2);
			
			$query4="UPDATE member_usageInfo SET active='n' WHERE booking_id IN (SELECT id FROM member_bookingInfo WHERE member_id='$memId' AND usageData='F' AND type='N')  ";
			$result4=parent::update($query4);
			
			$query3="DELETE FROM member_bookingInfo WHERE (finishDate > '$today' OR (finishDate = '$today' AND finishTime >= '$todayNow')) AND type='N' AND member_id='$memId' AND usageData='N' ";
			$result3=parent::delete($query3);
			
			$query="UPDATE group_members SET active='n' WHERE id='$memId' ";
			$result=parent::update($query);
			
			return $result;
		}
		/* end delet member */
		
		/* delete usage monitor */
		/*function deleteUsage()
		{
			 $usageId=$_POST['usageId'];
			 
			$q1="UPDATE group_billing_type SET active='n'  WHERE bill_type='U' AND type_detail='$usageId' ";
			$r1=parent::update($q1);
			
			$q2="UPDATE member_usageInfo SET active='n' WHERE usage_type_id='$usageId'";	
			$r2=parent::update($q2);
			
			$q3="UPDATE group_reminder_usage SET active='n'  WHERE usage_id='$usageId' ";	
			$r3=parent::update($q3);
			
			$query="UPDATE group_usageMonitor SET active='n' WHERE id='$usageId'";
			$result=parent::update($query);
			
			return $result;
		}*/
		function deleteUsageMonitor()
		{
			 $usageMonitorId=$_POST['usageMonitorId'];
			 
			//$q0="UPDATE group_billing_type SET active='n'  WHERE bill_type='U' AND type_detail='$usageMonitorId' ";	
			//$r0=parent::update($q0);
			$q0="DELETE FROM group_billing_type WHERE bill_type='U' AND type_detail='$usageMonitorId' ";
			$r0=parent::delete($q0);
			
			/*$bookUsageInfo=$this->getResult("booking_usageInfo"," MAX(finishValue) ","  WHERE id in ( SELECT usageUnitValue FROM member_usageInfo WHERE usage_type_id='$usageMonitorId' ) ","");
			$newCurrentValue = $bookUsageInfo[0]['MAX(finishValue)'];*/
			
			//$q1="UPDATE booking_usageInfo SET active='n'  WHERE id in ( SELECT usageUnitValue FROM member_usageInfo WHERE usage_type_id='$usageMonitorId' ) ";	
			//$r1=parent::update($q1);
			$q1="DELETE FROM booking_usageInfo  WHERE id in ( SELECT usageUnitValue FROM member_usageInfo WHERE usage_type_id='$usageMonitorId' )' ";
			$r1=parent::delete($q1);
			
			//$q2="UPDATE member_usageInfo SET active='n' WHERE usage_type_id='$usageMonitorId'";	
			//$r2=parent::update($q2);
			$q2="DELETE FROM member_usageInfo WHERE usage_type_id='$usageMonitorId' ";
			$r2=parent::delete($q2);
			
			//$q3="UPDATE group_reminder_usage SET active='n'  WHERE usage_id='$usageMonitorId' ";	
			//$r3=parent::update($q3);
			$q3="DELETE FROM group_reminder_usage  WHERE usage_id='$usageMonitorId' ";
			$r3=parent::delete($q3);
			
			//$query="UPDATE group_usageMonitor SET currentValue='$newCurrentValue',active='n' WHERE id='$usageMonitorId'";	
			//$result=parent::update($query);
			$query="DELETE FROM group_usageMonitor  WHERE id='$usageMonitorId' ";
			$result=parent::delete($query);
			
			return $result;
		}
		/* end delete usage monitor */
		
		
		/* delete billing preferences */
		function deleteMonthBill()
		{
			 $monthBillId=$_POST['monthBillId'];
			 
			$query="DELETE FROM group_billing_type WHERE id='$monthBillId' AND active='y'";
			$result=parent::delete($query);
			
			return $result;
		}
		function deleteUsageBill()
		{
			 $usageBillId=$_POST['usageBillId'];
			 
			$query="DELETE FROM group_billing_type WHERE id='$usageBillId'  AND active='y' ";
			$result=parent::delete($query);
			
			return $result;
		}
		/* end delete billing preferences */
		/* delete usage monitor */
		function deleteBooking($bookId)
		{
			 //$bookId=$_POST['bookId'];
			 
			 $userId=$_SESSION['user_id'];
			 $currentUserInfo = $this->getResult("auth_users","","  WHERE id='$userId' AND active='y' ","");
			 $to_userName = $currentUserInfo[0]['username'];
			 $to_emailId = $currentUserInfo[0]['email'];
			 
			 $bookingInfo=$this->getResult("member_bookingInfo",""," WHERE id='$bookId' AND active='y' ","");
			 $bkStartDate = date('d M Y a h',strtotime($bookingInfo[0]['startDate'] . " ".$bookingInfo[0]['startTime']));
			 $today = strtotime("now");
			 $bkFinishDate = date('d M Y a h',strtotime($bookingInfo[0]['finishDate'] . " ".$bookingInfo[0]['finishTime']));
			 $finishBookingDt = new DateTime($bookingInfo[0]['finishDate'] . " ".$bookingInfo[0]['finishTime']);
			 $todaynow = new DateTime("now");	//count total days
			 $total_interval = $todaynow->diff($finishBookingDt);
			 $total_days=($total_interval->format('%d'));
			 
			 $query="DELETE FROM member_bookingInfo WHERE id='$bookId'";
			 $result=parent::delete($query);
			 
			if(!empty($result))
			{
				if($total_days >= 7)
				{
					$from_indul_adminEmail = commonModel :: indulgeon_admin_emailId;
					$from_indul_adminName = commonModel :: indulgeon_admin_name;
					$emailMsg="Booking from " . $bkStartDate ." to ".$bkFinishDate." has been cancelled.\n";
					 if($bkStartDate < $today)
					 {
						 $bkStartDate = date('d M Y a h');
					 }
					$emailMsg=$emailMsg . "From  " .$bkStartDate . " to " .$bkFinishDate ." booking is available.\n\n\n";
					$emailMsg=$emailMsg . $groupAdminFullName." \n \n \n : ";
					$emailSubject="Booking Available";
					$mailSentMsg = parent::mailSend($from_indul_adminName,$from_indul_adminEmail,$to_userName,$to_emailId,$emailSubject,$emailMsg);
					$result=$mailSentMsg;
				}
			}
			
			return $result;
		}
		/* end delete usage monitor */
		/* search member */
		function searchMember($_POST)
		{
			$searchText=$_POST['searchText'];
			$searchField=$_POST['searchField'];
			if(!empty($searchField))
			{	
				switch($searchField)
				{
					case name : 
						{
							$searchResult=$this->getResult("auth_profile",""," WHERE user_id IN (SELECT id FROM auth_users WHERE active='y') AND  country_id IN (SELECT id FROM countries  WHERE $searchField LIKE '%$searchText%') ","");
						}
					break;
					default : $searchResult=$this->getResult("auth_profile",""," WHERE user_id IN (SELECT id FROM auth_users WHERE active='y') AND  $searchField LIKE '%$searchText%' ","");
					break;
				}
			}
			else
			{
				$searchResult="Please Select the search field..";
			}
			return $searchResult;
		}
		/* end search member */
		
		function prevNextBooking($groupId,$bookingId,$groupRuleId)
		{
			$result=array();
			$memberArray=array();
			$today = date('Y-m-d');
			$todayNow = date('H:i:s');
			
			$groupUsageInfo=$this->getResult("group_usageMonitor","","  WHERE id='$groupRuleId' ","");
			$created_dt = strtotime($groupUsageInfo[0]['created_dt']);
			$groupUsageDt = date('Y-m-d',$created_dt);
			$groupUsageTm = date('H:i:s',$created_dt);
			
			$selectedBooking=$this->getResult("member_bookingInfo",""," WHERE id='$bookingId' ","");
			$startDt = strtotime($selectedBooking[0]['startDate']."  ".$selectedBooking[0]['startTime']);
			
			$bookingInfo = $this->getResult("member_bookingInfo",""," WHERE member_id IN (SELECT id FROM group_members WHERE group_id='$groupId' AND active='y' ) AND ((finishDate < '$today') OR (finishDate='$today' AND finishTime='$todayNow')) AND (startDate>'$groupUsageDt' || (startDate='$groupUsageDt' AND startTime>='$groupUsageTm')) AND type='N' AND active='y' ","  ORDER by startDate asc, startTime asc  ");
			
			for($bi=0;$bi<count($bookingInfo);$bi++)
			{	
				$thisStartDt = strtotime($bookingInfo[$bi]['startDate']."  ".$bookingInfo[$bi]['startTime']);
				if($thisStartDt == $startDt)
				{	
					$beforDtId = $bookingInfo[$bi-1]['id'];
					$nextDtId = $bookingInfo[$bi+1]['id'];
					if(($bi-1) < 0)
					{
						$beforDtId =0;
					}
					if(($bi+1) > (count($bookingInfo)))
					{
						$nextDtId = 0;
					}
				}
			}
			
			array_push($result,$beforDtId);
			array_push($result,$nextDtId);
			return $result;
		}
		function validPrevNextValues($beforDtId,$nextDtId,$groupRuleId,$usageCurrentVal)
		{
			$result = array();
			$valid = 'y';
		
			$groupUsageInfo=$this->getResult("group_usageMonitor","","  WHERE id='$groupRuleId' ","");
			$thisActive = $groupUsageInfo[0]['active'];
			$usageType = $groupUsageInfo[0]['type'];
			
			$finalPrevFinishVal = NULL;
			$finalNextFinishVal = NULL;	
			if($beforDtId!=0)
			{	
				$memberUsage=$this->getResult("booking_usageInfo","","  WHERE id IN (SELECT usageUnitValue FROM member_usageInfo WHERE usage_type_id='$groupRuleId' ) ","");
				if(count($memberUsage)!=0)
				{
					$prevMemberUsage=$this->getResult("booking_usageInfo"," id,MAX(finishValue) ","  WHERE id IN (SELECT usageUnitValue FROM member_usageInfo WHERE booking_id='$beforDtId' AND usage_type_id='$groupRuleId' AND active='y' ) ","");
					
					$prevFinishValue = $prevMemberUsage[0]['MAX(finishValue)'];
					if(count($prevMemberUsage)!=0 && $prevFinishValue!=NULL && $prevFinishValue!='none')
					{
						$finalPrevFinishVal = $prevMemberUsage[0]['MAX(finishValue)'];
					}
					else
					{
						$valid ='n';
					}
				}
				else
				{
					$finalPrevFinishVal = $usageCurrentVal;
				}
			} else {
				$finalPrevFinishVal = $usageCurrentVal;
			}
			
			if($nextDtId!=0)
			{
				$memberUsage=$this->getResult("booking_usageInfo","","  WHERE id IN (SELECT usageUnitValue FROM member_usageInfo WHERE usage_type_id='$groupRuleId' ) ","");
				
				if(count($memberUsage)!=0)
				{
					$nextMemberUsage=$this->getResult("booking_usageInfo"," MIN(startValue) ","  WHERE id IN (SELECT usageUnitValue FROM member_usageInfo WHERE booking_id='$nextDtId' AND usage_type_id='$groupRuleId' AND active='y' ) ","");
					
					$nextstartvalue = $nextMemberUsage[0]['MIN(startValue)'];
					if(count($nextMemberUsage)!=0 && $nextstartvalue!=NULL && $nextstartvalue!='none')
					{
						$finalNextFinishVal = $nextMemberUsage[0]['MIN(startValue)'];
					}
					else
					{
						$valid = 'n';
					}
					//echo $nextMemberUsage[0]['id']."....".$finalNextFinishVal."....".$valid."<br/>";
				}
			}
			array_push($result,$valid);
			array_push($result,$finalPrevFinishVal);
			array_push($result,$finalNextFinishVal);
			return $result;
		}
		/* strore Usage Data */
		function storeUsageData($_POST)
		{	
			
			$bookingId=$_POST['bookingId'];
			$groupId=$_POST['groupId'];
			$totalUsages=$_POST['totalUsages'];
			$params=$_POST['params'];
			$currenttime=date("Y-m-d H:i:s");
			$active='y';
			
			foreach($_POST as $key =>$value)
			{	
				$valueArray = explode("_",$key);
				$valType  = $valueArray[0];
				$valFirstNo = $valueArray[1];
				$valSecNo = $valueArray[2];
				
				if($key=='usageStartDate')
				{
					$groupUsageInfo=$this->getResult("group_usageMonitor","","  WHERE group_id='$groupId' AND type='T' AND active='y' "," ORDER BY type DESC ");
					$usageId = $groupUsageInfo[0]['id'];
					$usageStartDate = $_POST['usageStartDate'];
					$usageStartS = $_POST['startH'];
					$usageStartM = $_POST['startM'];
					if($usageStartM=='mm')
					{
						$usageStartM = '00';
					}
					$startValue=$usageStartDate."  ".$usageStartS . ":".$usageStartM.":00";
					$usageFinishDate = $_POST['usageFinishDate'];
					$usageFinishS = $_POST['finishH'];
					$usageFinishM = $_POST['finishM'];
					if($usageFinishM=='mm')
					{
						$usageFinishM = '00';
					}
					$finishValue=$usageFinishDate."  ".$usageFinishS . ":".$usageFinishM.":00";
					$valid = 'y';
					
					$q0="INSERT INTO booking_usageInfo(startValue,finishValue,valid) VALUES('$startValue','$finishValue','$valid')";
					$r0=parent::insert($q0);
					$q1="INSERT INTO member_usageInfo(booking_id,usage_type_id,usageUnitValue,created_dt,modified_dt,active) VALUES('$bookingId','$usageId','$r0','$currenttime','$currenttime','$active')";
					$r1=parent::insert($q1);
				}	
				if($valType=='startValue')
				{
					$usageId = $valSecNo;
					$startValue = $value;
					$finishValue = $_POST['finishValue_'.$valFirstNo.'_'.$usageId];
					
					//$groupUsageInfo=$this->getResult("group_usageMonitor","","  WHERE group_id='$groupId' AND type='SE' AND active='y' "," ORDER BY type DESC ");
					$groupUsageInfo=$this->getResult("group_usageMonitor","","  WHERE id='$usageId' "," ORDER BY type DESC ");
					$usageCurrentVal = $groupUsageInfo[$i]['currentValue'];
					$prevNextBook=$this->prevNextBooking($groupId,$bookingId,$usageId);
					$beforDtId = $prevNextBook[0];
					$nextDtId = $prevNextBook[1];
					$prevNextValues=$this->validPrevNextValues($beforDtId,$nextDtId,$usageId,$usageCurrentVal);
					$valid = $prevNextValues[0];
					$finalPrevFinishVal = $prevNextValues[1];
					$finalNextFinishVal = $prevNextValues[2];
					if($finalPrevFinishVal!=NULL)
					{
						if($startValue!=$finalPrevFinishVal)
						{
							$valid ='n';
						}
					}
					if($finalNextFinishVal!=NULL)
					{
						if($finishValue!=$finalNextFinishVal)
						{
							$valid ='n';
						}
					}
					
					$q2="INSERT INTO booking_usageInfo(startValue,finishValue,valid) VALUES('$startValue','$finishValue','$valid')";
					$r2=parent::insert($q2);
					$q3="INSERT INTO member_usageInfo(booking_id,usage_type_id,usageUnitValue,created_dt,modified_dt,active) VALUES('$bookingId','$usageId','$r2','$currenttime','$currenttime','$active')";
					$r3=parent::insert($q3);
				}
				
				
			}
			
			$query="UPDATE member_bookingInfo SET  usageData='F'  WHERE id='$bookingId'";	
			$result=parent::update($query);
			
			if( (!empty($r0)) || (!empty($r3)) )
			{
				$result=$bookingId;
			} else {
				$result=0;
			}
			
			return $result;
		}
		/* end UsageData */
		
		/* start change UsageData */
		function changeUsageData($_POST)
		{	
			$bookingId=$_POST['bookId'];
			$groupRuleId=$_POST['groupRuleId'];
			$memUsageId=$_POST['usageId'];
			$groupId=$_POST['groupId'];
			$usageType=$_POST['usageType'];
			$currenttime=date("Y-m-d H:i:s");
			$active='y';
			
			$today = date('Y-m-d');
			$todayNow = date('H:i:s');
			//$memberArray=array();
			
			if($usageType=='T')
			{
				$valid = 'y';
				$usageStartDate = $_POST['usageStartDate'];
				$usageStartS = $_POST['startH'];
				$usageStartM = $_POST['startM'];
				if($usageStartM=='mm')
				{
					$usageStartM = '00';
				}
				$startValue=$usageStartDate."  ".$usageStartS . ":".$usageStartM.":00";
				$usageFinishDate = $_POST['usageFinishDate'];
				$usageFinishS = $_POST['finishH'];
				$usageFinishM = $_POST['finishM'];
				if($usageFinishM=='mm')
				{
					$usageFinishM = '00';
				}
				$finishValue=$usageFinishDate."  ".$usageFinishS . ":".$usageFinishM.":00";
			}
			else
			{
				$startValue=$_POST['changeStart'];
				$finishValue=$_POST['changeFinish'];
				$groupUsageInfo=$this->getResult("group_usageMonitor","","  WHERE id='$groupRuleId' ","");
				$usageType=$groupUsageInfo[0]['type'];
				$usageCurrentVal = $groupUsageInfo[0]['currentValue'];
				
				$prevNextBook=$this->prevNextBooking($groupId,$bookingId,$groupRuleId);
				$beforDtId = $prevNextBook[0];
				$nextDtId = $prevNextBook[1];
				
				$prevNextValues=$this->validPrevNextValues($beforDtId,$nextDtId,$groupRuleId,$usageCurrentVal);
				$valid = $prevNextValues[0];
				$finalPrevFinishVal = $prevNextValues[1];
				$finalNextFinishVal = $prevNextValues[2];
				$thisStartVal =$startValue;
				$thisFinishVal = $finishValue;
				//echo $finalPrevFinishVal ."--". $finalNextFinishVal."<br/>";
				
				$memUsageInfo=$this->getResult("member_usageInfo","","   WHERE  booking_id='$bookingId' AND usage_type_id='$groupRuleId' AND active='y' ","");	
				
				if(count($memUsageInfo) > 1)
				{	
					$allBookUsageInfo=$this->getResult("booking_usageInfo","","  WHERE id IN (SELECT usageUnitValue FROM member_usageInfo WHERE   booking_id='$bookingId' AND usage_type_id='$groupRuleId' ) "," ORDER BY startValue ASC ");
					
					if(count($allBookUsageInfo) > 1)
					{
						for($a=0;$a<count($allBookUsageInfo);$a++)
						{
							if( ($thisStartVal==$allBookUsageInfo[$a]['startValue']) && ($thisFinishVal==$allBookUsageInfo[$a]['finishValue']) )
							{
								if($a==0)
								{
									$nextStartValue = $allBookUsageInfo[$a+1]['startValue'];
									//echo $nextStartValue ."--".$finalPrevFinishVal;
									if( ($finalPrevFinishVal!=NULL) && ($thisStartVal==$finalPrevFinishVal) && ($thisFinishVal==$nextStartValue) )
									{
										$valid ='y';
									}
								}
								else if($a==(count($allBookUsageInfo)-1))
								{
									$preFinishValue = $allBookUsageInfo[$a-1]['finishValue'];
									if( ($finalNextFinishVal!=NULL) && ($thisFinishVal==$finalNextFinishVal) && ($thisStartVal==$preFinishValue) )
									{
										$valid ='y';
									}
								}
								else
								{
									$nextStartValue = $allBookUsageInfo[$a+1]['startValue'];
									$preFinishValue = $allBookUsageInfo[$a-1]['finishValue'];
									if( ($thisStartVal==$preFinishValue) && ($thisFinishVal==$nextStartValue) )
									{
										$valid = 'y';
									}
									else
									{
										$valid = 'n';
									}
								}
							}
						}
					}
					
				}
				else
				{
					if($finalPrevFinishVal!=NULL)
					{
						if($thisStartVal!=$finalPrevFinishVal)
						{
							$valid ='n';
						}
					}
					
					if($finalNextFinishVal!=NULL)
					{
						if($thisFinishVal!=$finalNextFinishVal)
						{
							$valid ='n';
						}
					}
				}
			}
			
			if($memUsageId==0)
			{
				$allGroupUsages=$this->getResult("group_usageMonitor","","  WHERE group_id='$groupId' "," ORDER BY type DESC ");
				if(count($allGroupUsages)!=0)
				{
					for($a=0;$a<count($allGroupUsages);$a++)
					{
						$thisRuleId = $allGroupUsages[$a]['id'];
						if($thisRuleId!=$groupRuleId)
						{
							$usageTypeId = $thisRuleId;
							$startValue='none';
							$finishValue='none';
							$valid='n';
						}
						else
						{
							$usageTypeId = $groupRuleId;
						}
						$q1="INSERT INTO booking_usageInfo(startValue,finishValue,valid) VALUES('$startValue','$finishValue','$valid')";
						$r1=parent::insert($q1);
						$q2="INSERT INTO member_usageInfo(booking_id,usage_type_id,usageUnitValue,created_dt,modified_dt,active) VALUES('$bookingId','$usageTypeId','$r1','$currenttime','$currenttime','$active')";
						$r2=parent::insert($q2);						
						$result=$r2;
					}
					$q3="UPDATE member_bookingInfo SET  usageData='F'  WHERE id='$bookingId'";	
					$r3=parent::update($q3);
				}
			}
			else
			{	
				$memUsageData=$this->getResult("member_usageInfo",""," WHERE id='$memUsageId' ","");
				$usageId=$memUsageData[0]['usageUnitValue'];
				$q1="UPDATE booking_usageInfo SET startValue='$startValue', finishValue='$finishValue', valid='$valid' WHERE id='$usageId'";
				$r1=parent::update($q1);
				$q2="UPDATE member_usageInfo SET usageUnitValue='$usageId', modified_dt='$currenttime' WHERE id='$memUsageId' ";
				$r2=parent::update($q2);
				$result=$memUsageId;
			}
			return $result;
		}
		/* end change UsageData */
		
		function inactivateMyUsage($_POST)
		{
			$bookingId=$_POST['bookingId'];
			
			$query="UPDATE member_bookingInfo SET usageData='F'  WHERE id='$bookingId'";	
			$result=parent::update($query);
			
			return $result;
		}
		function myInvoicesLog($groupId)
		{
			$allinvoiceInfo = array();
			$theseIds = array();
			$thesedates = array();
			$theseTypes = array();
			$theseAmts = array();
			$theseStatus = array();
			
			$userId=$_SESSION['user_id'];
			$userInfo=$this->getResult("auth_profile"," id "," WHERE user_id='$userId'","");
			$user_id=$userInfo[0]['id'];
		
			$myInvoicesInfo = $this->getResult("member_billing","","  WHERE member_id IN (SELECT id FROM group_members WHERE user_id='$user_id' AND group_id='$groupId' AND active='y') AND active='y' ","");
			for($mi=0;$mi<count($myInvoicesInfo);$mi++)
			 {
				 	$id=$myInvoicesInfo[$mi]['id'];
					$dateInvoice=strtotime($myInvoicesInfo[$mi]['dateInvoice']);
					
					$invoice_type=$myInvoicesInfo[$mi]['invoice_type'];
					if($invoice_type=='a')
					{
						$invoiceType = 'Monthly Invoice';
						$autoInvoicesInfo = $this->getResult("bill_invoice_auto"," SUM(amount) ","  WHERE bill_invoice_id='$id' AND active='y' ","");
						$invoiceAmount = $autoInvoicesInfo[0]['SUM(amount)'];
					}
					else if($invoice_type=='m')
					{
						$invoiceType = 'Manual Invoice';
						$manualInvoicesInfo = $this->getResult("bill_invoice_manual"," SUM(totalCost) ","  WHERE bill_invoice_id='$id' AND active='y' ","");
						$invoiceAmount = $manualInvoicesInfo[0]['SUM(totalCost)'];
					}
					else if($invoice_type=='r')
					{
						$regInvoicesInfo = $this->getResult("bill_invoice_regular","","  WHERE bill_invoice_id='$id' AND active='y' ","");
						$invoiceAmount = $regInvoicesInfo[0]['amount'];
						$invoice_regId = $regInvoicesInfo[0]['regular_id'];
						$thisACInfo = $this->getResult("group_AC","","  WHERE id='$invoice_regId' AND active='y' ","");
						$thisTypeId = $thisACInfo[0]['type_id'];
						$thisReccInfo = $this->getResult("AC_regular_IO","","  WHERE id='$thisTypeId' ","");
						$thisReccId = $thisReccInfo[0]['recurrence'];
						$thisReccInfo = $this->getResult("units","","  WHERE id='$thisReccId' AND active='y' ","");
						$reccurance = $thisReccInfo[0]['name'];
						$invoiceType = 'Reg. ' .$reccurance. ' Invoice';
					}
					$status=$myInvoicesInfo[$mi]['status'];
					
					array_push($theseIds,$id);
					array_push($thesedates,$dateInvoice);
					array_push($theseTypes,$invoiceType);
					array_push($theseAmts,$invoiceAmount);
					array_push($theseStatus,$status);
			 }
			 
			 array_push($allinvoiceInfo,$theseIds);
			 array_push($allinvoiceInfo,$thesedates);
			array_push($allinvoiceInfo,$theseTypes);
			array_push($allinvoiceInfo,$theseAmts);
			array_push($allinvoiceInfo,$theseStatus);
				 
			return $allinvoiceInfo;
		}
		function changePayStatus($_POST)
		{
			$thisInvoiceId = $_POST['thisInvoiceId'];
			$thisInvoiceValue = $_POST['thisInvoiceValue'];
			$query="UPDATE member_billing SET status='$thisInvoiceValue'  WHERE id='$thisInvoiceId'";
			$result=parent::update($query);
			return $result;
		}
		function unTagAllListing($_POST)
		{
			$q1="DELETE FROM listing_tagged";
			$r1=parent::delete($q1);
			return $r1;
		}
		function unTagListing($_POST)
		{
			$unTagId = $_POST['id'];
			$q1="DELETE FROM listing_tagged WHERE id='$unTagId'";
			$r1=parent::delete($q1);
			return $r1;
		}
		function deleteListing($_POST)
		{
			$thisListId = $_POST['id'];
			$q1="UPDATE listing_items SET approved='0'  WHERE id='$thisListId'";
			$r1=parent::update($q1);
			return $r1;
		}
		function endGroup($_POST)
		{
			$groupId = $_POST['groupId'];
			$this->memberBilling($groupId);
			$q1="UPDATE group_info SET resume='y'  WHERE id='$groupId' AND deleted='n' ";
			$r1=parent::update($q1);
			if(!empty($r1))
			{
				$this->memberBilling($groupId);
			}
			$thisGroupInfo=$this->getResult("group_info",""," WHERE id='$groupId' ","");
			$thisDelete = $thisGroupInfo[0]['deleted'];
			$thisResume = $thisGroupInfo[0]['resume'];
			if($thisDelete=='y' && $thisDelete=='n')
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}
		function resumeGroup($_POST)
		{
			$groupId = $_POST['groupId'];
			$q1="UPDATE group_info SET resume='n' WHERE id='$groupId' AND deleted='n' ";
			$r1=parent::update($q1);
			return $r1;
		}
		function adminLogArray($groupId)
		{
			if(empty($groupId) || !isset($groupId))
			{
				$groupId=$_POST['groupId'];
			}
			$userId=$_SESSION['user_id'];
			
			$logArray=array();
			$selectedBookIds=array();
			$startDts=array();
			$finishDts=array();
			$members=array();
			$units=array();
			$usageTypes=array();
			$startUnits=array();
			$finishUnits=array();
			$unitDiffs=array();
			$unActionedEnt=array();
			$ids=array();
			$groupRuleIds=array();
			$inactiveUsages=array();
			$usageInfoIds=array();
			$selectedIds=array();
			$start_dts=array();
			$start_units=array();
			$finish_units=array();
			$b_id=array();
			
			$beforeWeek=strtotime("-7 days");
			//$beforeWeek=strtotime("2011-07-02");
			//$dayLimit='2011-05-09';
			$dayLimit = date('Y-m-d');
			$nowLimit = date('H:i:s');
			$countId=0;
			
			$memberInfo=$this->getResult("group_members",""," WHERE group_id='$groupId' AND active='y' ","");
			for($mem=0;$mem<count($memberInfo);$mem++) {
				$memberId=$memberInfo[$mem]['id'];
				$user_id=$memberInfo[$mem]['user_id'];
				$memberActive=$memberInfo[$mem]['active'];
				$userInfo=$this->getResult("auth_profile",""," WHERE id='$user_id'","");	
				$mem_firstNm=$userInfo[0]['first_name'];
				$mem_LastNm=$userInfo[0]['last_name'];
				$fullName=$mem_firstNm ."  " .$mem_LastNm;
				$memberBookInfo=$this->getResult("member_bookingInfo","","  WHERE member_id='$memberId' AND (finishDate < '$dayLimit' OR (finishDate = '$dayLimit' AND finishTime <= '$nowLimit')) AND type='N' "," ORDER by startDate asc, startTime asc ");
				for($m=0;$m<count($memberBookInfo);$m++) {	
					$bookId=$memberBookInfo[$m]['id'];	
					$filledUsage=$memberBookInfo[$m]['usageData'];
					$memberId=$memberBookInfo[$m]['member_id'];
					$bookingActive=$memberBookInfo[$m]['active'];
					$startDate=strtotime($memberBookInfo[$m]['startDate']."  " .$memberBookInfo[$m]['startTime']);
					$finishDate=strtotime($memberBookInfo[$m]['finishDate']."  ". $memberBookInfo[$m]['finishTime']);
					
					$groupUsageInfo=$this->getResult("group_usageMonitor","","  WHERE group_id='$groupId' "," ORDER BY created_dt ");
					
					for($gu=0;$gu<count($groupUsageInfo);$gu++) {
						$groupRuleId=$groupUsageInfo[$gu]['id'];
						$groupUnit=$groupUsageInfo[$gu]['unit'];
						$currentVal=$groupUsageInfo[$gu]['currentValue'];
						$groupUsageActive=$groupUsageInfo[$gu]['active'];
						$groupCreatedDt=strtotime($groupUsageInfo[$gu]['created_dt']);
						$usageType=$groupUsageInfo[$gu]['type'];
						
						if($startDate >= $groupCreatedDt && $groupUsageActive=='y')
						{
							if($filledUsage=='N' && ($finishBookDt <= $beforeWeek) )
							{
									$countId++;
									array_push($unActionedEnt,$countId);
									array_push($ids,$countId);
									array_push($usageInfoIds,0);
									array_push($selectedBookIds,$bookId);
									array_push($startDts,$startDate);
									array_push($finishDts,$finishDate);
									array_push($members,$fullName);
									array_push($groupRuleIds,$groupRuleId);
									array_push($units,$groupUnit);
									array_push($usageTypes,$usageType);
									array_push($startUnits,0);
									array_push($finishUnits,0);
									array_push($unitDiffs,0);
							}
							else
							{
								$memberUsage=$this->getResult("member_usageInfo",""," WHERE booking_id='$bookId' AND usage_type_id='$groupRuleId' "," ORDER BY modified_dt DESC ");
						
								if(count($memberUsage)!=0)
								{
									for($u=0;$u<count($memberUsage);$u++) {
										$allValuesArray = array();
										$usageInfoId=$memberUsage[$u]['id'];
										$usageValueId=$memberUsage[$u]['usageUnitValue'];
										$usageValueInfo=$this->getResult("booking_usageInfo",""," WHERE id='$usageValueId' ","");	
										$startUsageValue=$usageValueInfo[0]['startValue'];
										$finishUsageValue=$usageValueInfo[0]['finishValue'];
										$allValuesArray['bookId']=$bookId;
										$allValuesArray['groupRuleId']=$groupRuleId;
										$allValuesArray['usageId']=$usageInfoId;
										$allValuesArray['changeStart']=$startUsageValue;
										$allValuesArray['changeFinish']=$finishUsageValue;
										$allValuesArray['groupId']=$groupId;
										$this->changeUsageData($allValuesArray);
									}
									
									
									for($u=0;$u<count($memberUsage);$u++) {
										$usageValueId=$memberUsage[$u]['usageUnitValue'];
										$usageInfoId=$memberUsage[$u]['id'];
										$bookingId=$memberUsage[$u]['booking_id'];
										$usageActive=$memberUsage[$u]['active'];
										
											//$usageValueId=$memberUsage[$u]['usageUnitValue'];
											$usageValueInfo=$this->getResult("booking_usageInfo",""," WHERE id='$usageValueId' ","");	
											$startUsageValue=$usageValueInfo[0]['startValue'];
											$finishUsageValue=$usageValueInfo[0]['finishValue'];
											$valid=$usageValueInfo[0]['valid'];
											//$active=$usageValueInfo[0]['active'];
											$total=(float)($finishUsageValue-$startUsageValue);
											
											
											$countId++;	
											//array_push($selectedIds,$countId);	//ids which has booking
											if($usageType!='T')
											{
												if($valid=='n' || $startUsageValue=='none' || $finishUsageValue=='none')
												{
													array_push($unActionedEnt,$countId);
												}
											}
											array_push($ids,$countId);
											array_push($usageInfoIds,$usageInfoId);
											array_push($selectedBookIds,$bookId);
											array_push($startDts,$startDate);
											array_push($finishDts,$finishDate);
											array_push($members,$fullName);
											array_push($groupRuleIds,$groupRuleId);
											array_push($units,$groupUnit);
											array_push($usageTypes,$usageType);
											array_push($startUnits,$startUsageValue);
											array_push($finishUnits,$finishUsageValue);
											array_push($unitDiffs,$total);
											/*if($groupUsageActive=='n' || $memberActive=='n' || $bookingActive=='n' || $usageActive=='n')
											{
												array_push($inactiveUsages,$countId);
											}*/
									}
								}
							}
						}
					}
				}
		}
		array_push($logArray,$ids);
		array_push($logArray,$startDts);
		array_push($logArray,$finishDts);
		array_push($logArray,$members);
		array_push($logArray,$units);
		array_push($logArray,$startUnits);
		array_push($logArray,$finishUnits);
		array_push($logArray,$unitDiffs);
		array_push($logArray,$usageTypes);
		array_push($logArray,$unActionedEnt);		
		array_push($logArray,$selectedBookIds);
		array_push($logArray,$groupRuleIds);
		array_push($logArray,$usageInfoIds);
		array_push($logArray,$inactiveUsages);
		return $logArray;
	}
		/* delete usage Info */
		function deleteUsageData($_POST)
		{
			$usageInfoId=$_POST['usageInfoId'];	
			$bookId=$_POST['bookId'];
				
			if($usageInfoId!=0)
			{
				$usageInfo=$this->getResult("member_usageInfo",""," WHERE id='$usageInfoId'","");
				$usageValId=$usageInfo[0]['usageUnitValue'];
				$usageTypeId=$usageInfo[0]['usage_type_id'];
				
				$bookingData=$this->getResult("booking_usageInfo",""," WHERE id='$usageValId'","");
				$lastAvlVal = $bookingData[0]['finishValue'];
				
				$q1="DELETE FROM booking_usageInfo WHERE id='$usageValId'";
				$r1=parent::delete($q1);
				
				$q2="DELETE FROM member_usageInfo WHERE id='$usageInfoId'";
				$r2=parent::delete($q2);
				
				$allUsageDataInfo=$this->getResult("member_usageInfo",""," WHERE usage_type_id='$usageTypeId' ","");
				if(count($allUsageDataInfo)==0)
				{
					$q3="UPDATE group_usageMonitor SET currentValue='$lastAvlVal' WHERE id='$usageTypeId' ";	
					$r3=parent::update($q3);
				}
				
				if(($r1) && ($r2))
				{
					$result = 1;
				}
				else
				{
					$result = 0;
				}
				
			}
			else
			{
				$query="UPDATE member_bookingInfo SET  usageData='F'  WHERE id='$bookId'";	
				$result=parent::update($query);
			}
			
			$allUsageInfo=$this->getResult("member_usageInfo",""," WHERE 	booking_id='$bookId' ","");
			if(count($allUsageInfo)==0)
			{
				$q3="DELETE FROM member_usageInfo WHERE booking_id='$bookId'";
				$r3=parent::delete($q3);
					
				if($r3)
				{
					$query="DELETE FROM member_bookingInfo WHERE id='$bookId'";
					$result=parent::delete($query);
				}
			}
			return $result;
		}
		/* strore single i/o Data */
		function store_single_pay($_POST)
		{	
			$amount=$_POST['sin_amt'];
			//$amt_curr=$_POST['sin_amt_curr'];
			//$amt_type=$_POST['single_type'];
			$regular_io_id=0;
			$sin_desc=$_POST['sin_desc'];
			$groupId=$_POST['groupId'];
			$currenttime=date("Y-m-d H:i:s");
			$active='y';
			
			//if(isset($_POST['add_single_io']))
			{
				$query="INSERT INTO group_AC(amount,group_id,type,io_type,type_id,amt_desc,created_dt,modified_dt,active) VALUES('$amount','$groupId','S','o','$regular_io_id','$sin_desc','$currenttime','$currenttime','$active')";
				$result=parent::insert($query);	
			}
			return $result;
		}
		/* strore single i/o Data */
		/* strore regular i/o Data */
		function store_regular_InPay($_POST)
		{	
			$startDt=$_POST['reg_startDt'];
			$endDt=$_POST['reg_endDt'];
			$start_date=$startDt ." ". date('H:i:s');
			$end_date=$endDt ." ". date('H:i:s');
			$recurrence=$_POST['reg_units'];
			$amount=$_POST['reg_amt'];
			//$amt_curr=$_POST['reg_amt_curr'];
			$regular_type=$_POST['regular_type'];
			$reg_des=$_POST['reg_des'];
			$groupId=$_POST['groupId'];
			$currenttime=date("Y-m-d H:i:s");
			$active='y';
			$status='u';
			$invoice_type='a';
			
			//if(isset($_POST['add_regular_io']))
			//{
				$q1="INSERT INTO AC_regular_IO(start_date,end_date,recurrence) VALUES('$start_date','$end_date','$recurrence')";
				$r1=parent::insert($q1);
				
				$query="INSERT INTO group_AC(amount,group_id,type,io_type,type_id,amt_desc,created_dt,modified_dt,active) VALUES('$amount','$groupId','R','$regular_type','$r1','$reg_des','$currenttime','$currenttime','$active')";
				$result=parent::insert($query);
			//}
			return $result;
		}
		/* strore regular i/o Data */
		
		/* strore manual invoice data */
		function store_single_invoice($allvalues)
		{	
			$allvalues = urldecode($allvalues);
			$allvaluesArray = explode("&",$allvalues);
			
			$memberlist = array();
			$postedValues = array();
			
			for($key=0;$key<count($allvaluesArray);$key++)
			{
				$thisValueArray = explode("=",$allvaluesArray[$key]);
				if($thisValueArray[0]=='groupMemberList')
				{
					array_push($memberlist,$thisValueArray[1]);
				}
				else
				{
					$postedValues[$thisValueArray[0]] = $thisValueArray[1];
				}
			}
			$_POST = $postedValues;
			$invoice_type = 'm';
			$status = 'u';
			$active = 'y';
			$currenttime = date('Y-m-d H:i:s');
			//$dateInvoice = $_POST['regular_io_startDt'];
			$totalCost = $_POST['totalCostIncoice'];
			$txtAddnotes = $_POST['addNotes'];
			
			$flag = 0;
			if(count($memberlist)!=0)
			{
				for($m=0;$m<count($memberlist);$m++)
				{
					$memId = $memberlist[$m];
					$q1="INSERT INTO member_billing(member_id,invoice_type,status,active,dateInvoice,created_dt) VALUES('$memId','$invoice_type','$status','$active','$currenttime','$currenttime')";
					$r1=parent::insert($q1);
					
					$q2="INSERT INTO bill_invoice_manual (bill_invoice_id,totalCost,add_notes,active) VALUES('$r1','$totalCost','$txtAddnotes','$active')";
					$r2=parent::insert($q2);
					
					foreach($_POST as $key =>$value)
					{
						$valueArray = explode("_",$key);
						$valType  = $valueArray[0];
						$valSeqNo = $valueArray[1];
						
						if(!empty($value))
						{
							if($valType=='txtCost')	////usage unit 
							{
								$txtService=$_POST['txtService_'.$valSeqNo];
								$txtUnits=$_POST['txtUnits_'.$valSeqNo];
								$txtRate=$_POST['txtRate_'.$valSeqNo];
								$txtCost=$_POST['txtCost_'.$valSeqNo];
								
								$query="INSERT INTO invoice_manual_detail (invoice_manual_id,service_desc,units,rate) VALUES('$r2','$txtService','$txtUnits','$txtRate')";
								$result=parent::insert($query);
								if(empty($r1) || empty($r2) || empty($result))
								{
									$flag = 2;
								}
							}
						}
					}
					
					$memberLoginInfo = $this->getResult("auth_users","","  WHERE id IN (SELECT user_id FROM group_members WHERE id='$memId'  AND active='y') AND active='y' ","");
					$to_userName = $memberLoginInfo[0]['username'];
					$to_emailId = $memberLoginInfo[0]['email'];
					//mail invoice notification	
					$from_indul_adminEmail = commonModel :: indulgeon_admin_emailId;
					$from_indul_adminName = commonModel :: indulgeon_admin_name;
					$emailMsg="Your Manual Invoice Referance No.: *";
					$emailMsg=$emailMsg . $r1. "* has been created. \n please check your account  to view/print/download full invoice ";
					$emailSubject="Manual Invoice created";
				
					$mailSentMsg = parent::mailSend($from_indul_adminName,$from_indul_adminEmail,$to_userName,$to_emailId,$emailSubject,$emailMsg);
					if(empty($mailSentMsg))
					{	
						$flag = 3;
					}
				}
			}
			else
			{
				$flag = 1;
			}
			return $flag;
		}
		/* strore manual invoice Data */
		function deleteRegPayIn()
		{
			$payIn_id=$_POST['payIn_id'];
			$updateRecord = $_POST['updateRecord'];
			$todaynow = date('Y-m-d H:i:s');
			
			$groupACInfo = $this->getResult("group_AC",""," WHERE id='$payIn_id' AND active='y' ","");
			$type_id = $groupACInfo[0]['type_id'];
			
			$q1="UPDATE AC_regular_IO SET end_date='$todaynow' WHERE id='$type_id'";	
			$r1=parent::update($q1);
			
			if($r1!=0)
			{
				$query="UPDATE group_AC SET active='n' WHERE id='$payIn_id'  AND active='y' ";
				$result=parent::update($query);
			}
			else
			{
				$result = 0;
			}
			
			return $result;
		}
		function groupAccount($groupId,$groupMonthStr,$groupMinMonthInfo)
		{
			$groupAccountInfo = array();
			$ac_date = array();
			$ac_desc = array();
			$ac_payments = array();
			$ac_receipts = array();
			$ac_balance = array();
			$groupBalance = array();
			
			$groupMonth = date('m',$groupMonthStr);
			$groupYear = date('Y',$groupMonthStr);
			$groupMonthDt = date('Y-m-d H:i:s',$groupMonthStr);
			
			$prevGroupMonth = strtotime("-1 month",$groupMonthStr);
			$prevGroupM = date('m',$prevGroupMonth);
			$prevGroupY = date('Y',$prevGroupMonth);
			
			$groupACInfo = $this->getResult("group_AC",""," WHERE io_type='o' AND group_id='$groupId'  AND active='y' ","");
			$autoInvoiceInfo = $this->getResult("member_billing",""," WHERE status='p' AND invoice_type='a' AND (MONTH(dateInvoice)='$groupMonth' AND YEAR(dateInvoice)='$groupYear') AND active='y' AND member_id IN (SELECT id FROM group_members WHERE group_id='$groupId' AND active='y' ) ","");
			$manualInvoiceInfo = $this->getResult("member_billing",""," WHERE status='p' AND invoice_type='m' AND (MONTH(dateInvoice)='$groupMonth' AND YEAR(dateInvoice)='$groupYear') AND active='y'  AND member_id IN (SELECT id FROM group_members WHERE group_id='$groupId' AND active='y' ) ","");
			$regularInvoiceInfo = $this->getResult("member_billing",""," WHERE status='p' AND invoice_type='r' AND  (MONTH(dateInvoice)='$groupMonth' AND YEAR(dateInvoice)='$groupYear') AND active='y' AND member_id IN (SELECT id FROM group_members WHERE group_id='$groupId' AND active='y' ) ","");
			
			$groupMembers = $this->getResult("group_members",""," WHERE group_id='$groupId' AND active='y' ","");
			
			foreach($groupACInfo as $ga=>$acValue)
			{
				$created_dt=strtotime($groupACInfo[$ga]['created_dt']);
				$amount = $groupACInfo[$ga]['amount'];
				$createdDateMonth = date('m',$created_dt);
				$createdDt = date('d M Y',$created_dt);
				//$amt_type = $groupACInfo[$ga]['amt_type'];
				$type = $groupACInfo[$ga]['type'];
				if($type=='S' && (date('m',$created_dt)==$groupMonth) && (date('Y',$created_dt)==$groupYear))
				{
					$amtDesc = $groupACInfo[$ga]['amt_desc'];
					
					$amt_desc ="Single Payment for '$amtDesc'";
					array_push($ac_date,$created_dt);
					array_push($ac_desc,$amt_desc);
					array_push($ac_payments,$amount);
					array_push($ac_receipts,0);
				}
				else if($type=='R')
				{
					$typeId = $groupACInfo[$ga]['type_id'];
					$groupId = $groupACInfo[$ga]['group_id'];
					$amount = $groupACInfo[$ga]['amount'];
					$amtDesc = $groupACInfo[$ga]['amt_desc'];
					$reccInfo=$this->getResult("AC_regular_IO",""," WHERE id='$typeId' ","");
					$reccId = $reccInfo[0]['recurrence'];
					$start_date = $reccInfo[0]['start_date'];
					$end_date = $reccInfo[0]['end_date'];
					$reccUnitInfo=$this->getResult("units",""," WHERE id='$reccId' ","");
					$recurrence = $reccUnitInfo[0]['name'];
					$reccValue = $reccUnitInfo[0]['value'];
					$reccValUnit = $reccUnitInfo[0]['valueUnit'];
					
					$todaynow = strtotime("now");
					$startDateStr = strtotime($start_date);
					$endDateStr = strtotime($end_date);
					
					if($startDateStr < $todaynow)
					{
						for($m=0;$m<count($groupMembers);$m++)
						{
							$thisMemId = $groupMembers[$m]['id'];
							$user_id = $groupMembers[$m]['user_id'];
							$authInfo = $this->getResult("auth_profile",""," WHERE 	id='$user_id'","");
							$first_name=$authInfo[0]['first_name'];
							$last_name=$authInfo[0]['last_name'];
							$mem_name=$first_name." ".$last_name;
							$amt_desc ="Regular Payment to '$mem_name ' as a '$recurrence' payment " .$amtDesc;
							if($endDateStr >= $todaynow)
							{	
								$newUnitDate = strtotime($start_date." + ". $reccValue ." " . $reccValUnit);
								while($newUnitDate < $todaynow){
									$thisInvoiceDate = date('Y-m-d H:i:s',$newUnitDate);	
									if((date('m',$newUnitDate)==$groupMonth) && (date('Y',$newUnitDate)==$groupYear))
									{
										array_push($ac_date,$newUnitDate);
										array_push($ac_desc,$amt_desc);
										array_push($ac_payments,$amount);
										array_push($ac_receipts,0);
									}
									$newUnitDate = strtotime($thisInvoiceDate." + ". $reccValue ." " . $reccValUnit);	
								}
							}
							else
							{
								$newUnitDate = strtotime($start_date." + ". $reccValue ." " . $reccValUnit);
								
								while($newUnitDate <= $endDateStr)
								{
										//check and insert record for $newUnitDate
										$thisInvoiceDate = date('Y-m-d H:i:s',$newUnitDate);		
										if((date('m',$newUnitDate)==$groupMonth) && (date('Y',$newUnitDate)==$groupYear))
										{
											array_push($ac_date,$newUnitDate);
											array_push($ac_desc,$amt_desc);
											array_push($ac_payments,$amount);
											array_push($ac_receipts,0);
										}
										
										$prevDate =  date('Y-m-d H:i:s',$newUnitDate);
										$newUnitDate = strtotime($thisInvoiceDate." + ". $reccValue ." " . $reccValUnit);
										if($newUnitDate > $endDateStr)
										{
											$perDayCharge = ((float)$amount) / ((float)$reccValue);
											
											$dtFinish = new DateTime($end_date);	//count total days
											$dtStart = new DateTime($prevDate);
											$total_interval = $dtStart->diff($dtFinish);
											$total_interval_days=($total_interval->format('%a'));
											
											$totalInvoiceCharge = $perDayCharge * (float)$total_interval_days;
											$thisInvoiceDate = date('Y-m-d H:i:s',$endDateStr);
											if((date('m',$endDateStr)==$groupMonth) && (date('Y',$endDateStr)==$groupYear))
											{
												array_push($ac_date,$endDateStr);
												array_push($ac_desc,$amt_desc);
												array_push($ac_payments,$totalInvoiceCharge);
												array_push($ac_receipts,0);
											}
										}
										
								}//finish while loop	
							}
						}
					}
					
				}
			}
			
			foreach($autoInvoiceInfo as $mb=>$memBillValue)
			{
				$invoice_id=$autoInvoiceInfo[$mb]['id'];	
				$created_dt=strtotime($autoInvoiceInfo[$mb]['dateInvoice']);
				//$created_dt=$autoInvoiceInfo[$mb]['created_dt'];
				$member_id=$autoInvoiceInfo[$mb]['member_id'];
				$memberInfo = $this->getResult("group_members",""," WHERE 	id='$member_id' AND active='y' ","");
				$user_id = $memberInfo[0]['user_id'];
				$authInfo = $this->getResult("auth_profile",""," WHERE 	id='$user_id'","");
				$first_name=$authInfo[0]['first_name'];
				$last_name=$authInfo[0]['last_name'];
				$mem_name=$first_name." ".$last_name;
				
				$invoiceInfo = $this->getResult("bill_invoice_auto",""," WHERE 	bill_invoice_id='$invoice_id' AND active='y' ","");
				$auto_amount = 0;
				foreach($invoiceInfo as $i=>$value)
				{
					$amt = $invoiceInfo[$i]['amount'];
					$auto_amount = $auto_amount + (float) $amt;
				}
				$member_desc = "Monthly Charge paid by '$mem_name'. Ref: '$invoice_id'";
				array_push($ac_date,$created_dt);
				array_push($ac_desc,$member_desc);
				array_push($ac_payments,0);
				array_push($ac_receipts,$auto_amount);
			}
			
			foreach($manualInvoiceInfo as $mb=>$memBillValue)
			{
				$invoice_id=$manualInvoiceInfo[$mb]['id'];	
				$created_dt=strtotime($manualInvoiceInfo[$mb]['dateInvoice']);
				//$created_dt=$manualInvoiceInfo[$mb]['created_dt'];
				$member_id=$manualInvoiceInfo[$mb]['member_id'];
				$memberInfo = $this->getResult("group_members",""," WHERE 	id='$member_id' AND active='y' ","");
				$user_id = $memberInfo[0]['user_id'];
				$authInfo = $this->getResult("auth_profile",""," WHERE 	id='$user_id'","");
				$first_name=$authInfo[0]['first_name'];
				$last_name=$authInfo[0]['last_name'];
				$mem_name=$first_name." ".$last_name;
				
				$invoiceInfo = $this->getResult("bill_invoice_manual",""," WHERE bill_invoice_id='$invoice_id' AND active='y' ","");
				$auto_amount = 0;
				foreach($invoiceInfo as $i=>$value)
				{
					$amt = $invoiceInfo[$i]['totalCost'];
					$auto_amount = $auto_amount + (float) $amt;
				}
				
				$member_desc = "Charge paid by '$mem_name'. Ref: '$invoice_id'";
				array_push($ac_date,$created_dt);
				array_push($ac_desc,$member_desc);
				array_push($ac_payments,0);	
				array_push($ac_receipts,$auto_amount);
			}
			
			foreach($regularInvoiceInfo as $mb=>$memBillValue)
			{
				$invoice_id=$regularInvoiceInfo[$mb]['id'];	
				$created_dt=strtotime($regularInvoiceInfo[$mb]['dateInvoice']);	
				//$created_dt=$regularInvoiceInfo[$mb]['created_dt'];
				$member_id=$regularInvoiceInfo[$mb]['member_id'];
				$memberInfo = $this->getResult("group_members",""," WHERE 	id='$member_id' AND active='y' ","");
				//$groupId = $memberInfo[0]['group_id'];
				$user_id = $memberInfo[0]['user_id'];
				$authInfo = $this->getResult("auth_profile",""," WHERE 	id='$user_id'","");
				$first_name=$authInfo[0]['first_name'];
				$last_name=$authInfo[0]['last_name'];
				$mem_name=$first_name." ".$last_name;
				
				$invoiceInfo = $this->getResult("bill_invoice_regular",""," WHERE bill_invoice_id='$invoice_id' AND active='y' ","");
				$groupacId = $invoiceInfo[0]['regular_id'];
				$auto_amount = $invoiceInfo[0]['amount'];
				
				$groupAC_Info = $this->getResult("group_AC",""," WHERE id='$groupacId' AND active='y' ","");
				$typeId = $groupAC_Info[0]['type_id'];
				$amtDesc = $groupAC_Info[0]['amt_desc'];
				$reccInfo=$this->getResult("AC_regular_IO",""," WHERE id='$typeId' ","");
				$reccId = $reccInfo[0]['recurrence'];
				$reccUnitInfo=$this->getResult("units",""," WHERE id='$reccId' ","");
				$recuName = $reccUnitInfo[0]['name'];
				
				$member_desc = "Regular '$recuName' charge paid by '$mem_name' for '$amtDesc'. Ref:'$invoice_id'";
				array_push($ac_date,$created_dt);
				array_push($ac_desc,$member_desc);
				array_push($ac_payments,0);	
				array_push($ac_receipts,$auto_amount);
			}
			
			//array_multisort($ac_date,SORT_ASC,$ac_desc,$ac_payments,$ac_receipts);
			array_push($groupAccountInfo,$ac_date);
			array_push($groupAccountInfo,$ac_desc);
			array_push($groupAccountInfo,$ac_payments);
			array_push($groupAccountInfo,$ac_receipts);
			
			array_multisort($groupAccountInfo[0],SORT_ASC,$groupAccountInfo[1],$groupAccountInfo[2],$groupAccountInfo[3]);
			
			$total_bal =0 ;
			if(count($ac_date)!=0)
			{
				for($g=0;$g<count($ac_date);$g++)
				{
					//echo date('m',$groupAccountInfo[0][$g]). "<br/>";
					$pay = $groupAccountInfo[2][$g];
					$rec = $groupAccountInfo[3][$g];
					
					$bal = (float) $rec - (float) $pay;
					if($total_bal==0 && ($groupMonthStr > $groupMinMonthInfo) )
					{
						$groupMonthBalanceInfo = $this->getResult("group_month_balance",""," WHERE MONTH(group_balance_dt)='$prevGroupM' AND YEAR(group_balance_dt)='$prevGroupY' AND group_id='$groupId' AND active='y' ","");
						if(count($groupMonthBalanceInfo)!=0)
						{
							$total_bal = (float) ($groupMonthBalanceInfo[0]['balance']);
						}
					}
					$total_bal = $total_bal + $bal;
					array_push($ac_balance,$total_bal);
					if($g==(count($ac_date)-1))
					{	
						array_push($groupBalance,$ac_date[$g]);
						array_push($groupBalance,$total_bal);
					}
				}
			}
			else if($total_bal==0 && ($groupMonthStr > $groupMinMonthInfo) )
			{
				$groupMonthBalanceInfo = $this->getResult("group_month_balance",""," WHERE MONTH(group_balance_dt)='$prevGroupM' AND YEAR(group_balance_dt)='$prevGroupY' AND group_id='$groupId' AND active='y' ","");
				if(count($groupMonthBalanceInfo)!=0)
				{
					$balanceDt = strtotime($groupMonthBalanceInfo[0]['group_balance_dt']);
					$total_bal = (float) ($groupMonthBalanceInfo[0]['balance']);
				}
				array_push($groupBalance,$balanceDt);
				array_push($groupBalance,$total_bal);
			}
			
			array_push($groupAccountInfo,$ac_balance);
			array_push($groupAccountInfo,$groupBalance);
			
			return $groupAccountInfo;
		}
		function groupBalance($groupId,$groupMinMonthInfo,$groupMaxMonthInfo)
		{
			$result = 0;
			$thisMonth=$groupMinMonthInfo;
			while($thisMonth <= $groupMaxMonthInfo)
			{
				
				$active = 'y';
				$balMonthYear = date('Ym',$thisMonth);
				$group_balance_dt = date('Y-m-d',$thisMonth);
				$groupMonthBalanceInfo = $this->getResult("group_month_balance",""," WHERE group_balance_dt='$group_balance_dt' AND group_id='$groupId' ","");
				if(count($groupMonthBalanceInfo)==0 && (date('Ym')!=$balMonthYear) )
				{
					$thisMonthAcInfo = $this->groupAccount($groupId,$thisMonth,$groupMinMonthInfo);
					$thisMonthBal = $thisMonthAcInfo[5][1];
					$query="INSERT INTO group_month_balance(group_id,group_balance_dt,balance,active) VALUES('$groupId','$group_balance_dt','$thisMonthBal','$active')";
					$result=parent::insert($query);
				}
				$thisMonth=strtotime("+1 month",$thisMonth);
			}
			return $result;
		}
		
		function store_usage_schdule($allvalues)
		{	
			$allvalues = urldecode($allvalues);
			$allvaluesArray = explode("&",$allvalues);
			
			$postedValues = array();
			
			for($key=0;$key<count($allvaluesArray);$key++)
			{
				$thisValueArray = explode("=",$allvaluesArray[$key]);
				$postedValues[$thisValueArray[0]] = $thisValueArray[1];
			}
			$_POST = $postedValues;
			
			$currenttime = date('Y-m-d H:i:s');
			$groupId = $_POST['groupId'];	
			$usageId = $_POST['usages'];
			$usageSch_desc = $_POST['usageSch_desc'];
			$trigger_value = $_POST['trigger_value'];
			/*$before_value = $_POST['before_value'];*/
			$active = 'y';
				
			$query="INSERT INTO group_reminder_usage(group_id,usage_id,trigger_value,reminderDesc,created_dt,active) VALUES('$groupId','$usageId','$trigger_value','$usageSch_desc','$currenttime','$active')";
			$result=parent::insert($query);
			
			return $result;
		}
		function store_calendar_schdule($allvalues)
		{	
			$allvalues = urldecode($allvalues);
			$allvaluesArray = explode("&",$allvalues);
			
			$postedValues = array();
			
			for($key=0;$key<count($allvaluesArray);$key++)
			{
				$thisValueArray = explode("=",$allvaluesArray[$key]);
				$postedValues[$thisValueArray[0]] = $thisValueArray[1];
			}
			$_POST = $postedValues;
			
			$currenttime = date('Y-m-d H:i:s');
			$groupId = $_POST['groupId'];	
			$periodId = $_POST['periods'];
			$calSch_desc = $_POST['calSch_desc'];
			$trigger_date = $_POST['trigger_date'];
			$before_value = $_POST['before'];
			$active = 'y';
				
			$query="INSERT INTO group_reminder_calendar(group_id,period_id,start_dt,before_value,reminderDesc,created_dt,active) VALUES('$groupId','$periodId','$trigger_date','$before_value','$calSch_desc','$currenttime','$active')";
			$result=parent::insert($query);
			
			return $result;
		}
		/* delete usage schdule */
		function delete_usage_schdule()
		{
			 $thisId=$_POST['thisDeleteId'];
			 
			$query="DELETE FROM group_reminder_usage WHERE id='$thisId'";
			$result=parent::delete($query);
			
			return $result;
		}
		/* delete calendar schdule */
		function delete_calendar_schdule()
		{
			 $thisId=$_POST['thisDeleteId'];
			 
			$query="DELETE FROM group_reminder_calendar WHERE id='$thisId'";
			$result=parent::delete($query);
			
			return $result;
		}
		function dissmissReminder()
		{
			
			$thisId = $_POST['thisId'];
			$thisType = $_POST['thisType'];
			
			if($thisType=='c')
			{
				$query="UPDATE group_reminder_calendar SET active='n' WHERE id='$thisId'";
				$result=parent::update($query);
			}
			else if($thisType=='u')
			{
				$query="UPDATE group_reminder_usage SET active='n' WHERE id='$thisId'";
				$result=parent::update($query);
			}
			return $result;
		}
}
?>