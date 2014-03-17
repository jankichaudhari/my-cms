<?php
	class searchModel extends groupModel {
		public function __construct($SERVER,$DBASE,$USERNAME,$PASSWORD)
		{
			parent::__construct($SERVER,$DBASE,$USERNAME,$PASSWORD);
		}
		
		// /* SEARCH */ //
		function taglisting($listing_id, $userId){
			if(!$userId){return;}
			$sql = "INSERT INTO `listing_tagged` (`list_id`,`user_id`) VALUES ($listing_id,$userId) ";
			$result=parent::insert($sql);
			return $result;
		}
		
		function untaglisting($listing_id, $userId){
			$sql = "DELETE FROM `listing_tagged` WHERE `list_id`=$listing_id AND `user_id`=$userId LIMIT 1 ";
			$result=mysql_query($sql);
			return $result;
		}
		
		function is_tagged($listing_id, $userId){
			$sql = "SELECT `id` FROM `listing_tagged` WHERE `list_id`=$listing_id AND `user_id`=$userId LIMIT 1";
			$result=mysql_query($sql);
			
			if($result){
				$r = mysql_num_rows($result);
				mysql_freeresult($result);
				return $r;
			}
			
			return 0;
		}
	}
?>