<?php
class commonModel extends mainClass{
	/* global Variables */
		//const indulgeon_url = "http://".$_SERVER['SERVER_NAME'];
		const indulgeon_url = 'http://webtechno.co/easyshare/';
		const indulgeon_admin_name = 'Easyshare Administrator';
		const indulgeon_admin_emailId = "janki@webtechno.co";
	/* global Variables */

		public function __construct($SERVER,$DBASE,$USERNAME,$PASSWORD)
		{
			parent::__construct($SERVER,$DBASE,$USERNAME,$PASSWORD);
		}
		
		function indulgeonAdmin()
		{
			$requesttype = $_REQUEST['param1'];
			$requestId = $_REQUEST['param2'];
			$current_dt = date('Y-m-d H:i:s');
			
			if(!empty($requesttype))
			{	
				if(!empty($requestId))
				{
					if($requesttype=='listing')
					{
						$query="UPDATE listing_items SET approved='1'  WHERE id='$requestId'";	
						$resultUpdate=parent::update($query);
					}
					else if($requesttype=='listingNunit')
					{
						$feesUnit = $_REQUEST['param3'];
						$usageChrgeUnit = $_REQUEST['param4'];
						$categoryId = $_REQUEST['param5'];
						if(!empty($feesUnit))
						{
							$q1="UPDATE listing_units SET approved='1'  WHERE id='$requestId'";	
							$r1=parent::update($q1);
						}
						if(!empty($usageChrgeUnit))
						{
							$q2="UPDATE listing_units SET approved='1'  WHERE id='$requestId'";	
							$r2=parent::update($q2);
						}
						if(!empty($categoryId))
						{
							$q3="UPDATE taxonomy_categories SET approved='1'  WHERE id='$requestId'";	
							$r3=parent::update($q3);
						}
						
						$query="UPDATE listing_items SET approved='1'  WHERE id='$requestId'";	
						$resultUpdate=parent::update($query);
					}
					else if($requesttype=='group')
					{
						$query="UPDATE group_info SET  modified_dt='$current_dt',active='y'  WHERE id='$requestId'";	
						$resultUpdate=parent::update($query);
						$groupInfo = $this->getResult("group_info"," list_id "," WHERE id='$requestId' AND deleted='n' ","");
						$listId = $groupInfo[0]['list_id'];
						if(!empty($result) && $listId!=0)
						{
							$que="UPDATE listing_items SET deleted='y'  WHERE id='$requestId'";	
							$resultUpdate=parent::update($que);
						}
					}
					else
					{	$resultUpdate = 0;	}
					
					if(!empty($resultUpdate))
					{	
						$result = $requestId;
					}
					else
					{	$result = 0;	}
				}
				else
				{	$result = 0;	}
			}
			else
			{	$result = 0;	}	
			
			return $result;
		}
		
		function countryList()
		{
			$query="SELECT * FROM countries";
			$result=parent::select($query);
			return $result;
		}
		
		function listingPer()
		{
			$query="SELECT * FROM listing_selects";
			$result=parent::select($query);
			return $result;
		}
		
		function currencyList()
		{
			$query="SELECT * FROM currencies";
			$result=parent::select($query);
			return $result;
		}
		
		/*function LocationList()
		{
			$query="SELECT * FROM postcodes";
			$result=parent::select($query);
			return $result;
		}*/
		function userList()
		{
			$query="SELECT * FROM auth_profile";
			$result=parent::select($query);
			return $result;
		}
		
		function breadcrumb($itemId,$itemType,$orgId,$controller)
		{
			$breadcrumb_id=array();
			$breadcrumb_name=array();
			$breadcrumb_link=array();
			
			if(!empty($itemId))
			{
				if($itemType=='G')
				{
					if(!empty($controller))
					{
						switch($controller)
						{
							case 'A' : $controllerName = 'Group Administrator';
							break;
							case 'F' : $controllerName = 'Group Finance';
							break;
							case 'M' : $controllerName = 'Group Maintenance';
							break;
							default : $controllerName = '';
							break;
						}
					}
					$groupIngo=$this->getResult("group_info",""," WHERE id='$itemId' "," ");
					$groupName = $groupIngo[0]['title'] . "(" . $controllerName .")";
					$groupLink = "index.php?process=my_group&param1=".$itemId;
					array_push($breadcrumb_id,$itemId);
					array_push($breadcrumb_name,$groupName);
					array_push($breadcrumb_link,$groupLink);
				}
			}
			if($orgId==0)
			{
				$id = 1;
			}
			else
			{
				$id = $orgId;
			}
			do
			  {
			  	$result=$this->getResult("breadcrumb",""," WHERE id='$id' "," ");
				$id = $result[0]['parentId'];
				$name=$result[0]['name'];
				$link=$result[0]['link'];
				array_push($breadcrumb_id,$id);
				array_push($breadcrumb_name,$name);
				array_push($breadcrumb_link,$link);
			  }
			while ($id!=0);
			
			$breadcrumb=array($breadcrumb_id,$breadcrumb_name,$breadcrumb_link);
			
			return $breadcrumb;
		}
		
		function getResult($table,$fields,$cond,$order)
		{
			if(empty($fields) || $fields==' ')
			{
				$fields="*";
			}
			$query="SELECT " . $fields . " FROM " . $table . " " . $cond . " " . $order ;
			$db_result=parent::select($query);
			return $db_result;
		}
		function paging($array,$href,$numperpage)
		{
			$previous = NULL;
			$next = NULL;
			$paging=array();
			$page=$_REQUEST['pagenum'];
			//$numperpage = 2; //number of records per page
			$total = count($array);
			
			$numofpages = ceil($total / $numperpage); //total num of pages, oh yeah don't forget to round up
			
			if($page > $numofpages)
			{
				$page = $numofpages;
			}
			$currentpage = isset($page) ? (integer)$page : 1;
			
			$hrefArray = explode("#",$href);
			if(isset($hrefArray[1]))
			{
				$href = $hrefArray[0];
				$anchorText = "#".$hrefArray[1];
			}
			else
			{
				$href = $hrefArray[0];
				$anchorText = "";
			}
			
			if(isset($array)){
				if (($currentpage > 0) && ($currentpage <= $numofpages)) {
					//yes pages are required and current page is ok
			
					//show data array
					$start = ($currentpage-1) * $numperpage;
					if($currentpage ==$numofpages)
					{
						$end=$total;
					}
					else
					{
						$end = $numperpage+$start;
					}
					
					if ($currentpage != 1) {//can't go back from page 1
						$previous_page = $currentpage - 1;
						$previous = '<a href="'.$href.'&pagenum='. $previous_page .$anchorText.'"><< Previous</a> ';    
					}    
						
					$pages_html = '';
					for ($a=1; $a<=$numofpages; $a++) {
						if ($a == $currentpage) {
							$pages_html .= $a .'&nbsp;| ';
						} else {
							$pages_html .= '<a href="'.$href.'&pagenum='. $a .$anchorText.'" >'. $a .'</a>&nbsp;| ';
						}
					}
					$pages_html = substr($pages_html,0,-2); //remove the last ,
			
					if ($currentpage != $numofpages) {//can't go forward if we are on the last page
						$next_page = $currentpage + 1;
						$next = ' <a href="'.$href.'&pagenum='. $next_page .$anchorText.'">Next >></a>';
					}
					
					$html_page = $previous . $pages_html . $next;
				}
			}
			array_push($paging,$start);
			array_push($paging,$end);
			array_push($paging,$html_page);
			
			return $paging;
		}
		function mailSend($from_name,$from_mail,$name_to,$mailto,$subject,$message)
		{
			$headers = "From: ".$from_name." <".$from_mail.">\r\n";
			$headers .= "Reply-To: ".$from_mail."\r\n";
			$headers .= 'X-Mailer: PHP/' . phpversion();
			//$headers .= "MIME-Version: 1.0\r\n";
			//$headers .= "Content-type:text/plain; charset=iso-8859-1\r\n";
			//$headers .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
			//$headers .= $name_to.",\n\n";
			//$headers .= $message."\r\n\r\n";
			
			//if (mail($mailto, $subject, "", $header)) {
			if(mail($mailto, $subject, $message, $headers)) {
				return true; // or use booleans here
			} else {
				return false;
			}
		}
}
?>