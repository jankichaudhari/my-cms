<div>
<h1>Member Search Results</h1>
<?php
if(count($results)!=0)
{
	for($i=0;$i<(count($results));$i++){
		$flag=1;
		for($j=0;$j<count($groupMembers);$j++){
			if($groupMembers[$j]['user_id']==$results[$i]['id']){
				$flag=0;
			}
		}
		
		$userId = $results[$i]['id'];
		
		/*$authUserInfo=$this->model->getResult("auth_users",""," WHERE id='$userId' ","");
		$userEmail = $authUserInfo[0]['email'];
		$userBirthDt = date('d.m.y',strtotime($authUserInfo[0]['birthDate']));*/
		
		$fileName='userImg_' . $userId;
		$listPhoto=$this->model->getResult("listing_photos",""," WHERE file_name='$fileName' AND type_id='$userId' AND type='U' AND active='y' ","");
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
		$fname=$results[$i]['first_name'];
		$lname=$results[$i]['last_name'];
		$town=$results[$i]['town'];
		$postcode=$results[$i]['postcode'];
		$countryId=$results[$i]['country_id'];
		$countryInfo=$this->model->getResult("countries",""," WHERE id='$countryId' ","");
		$countryName = $countryInfo[0]['printable_name'];
		
		if($searchField=='first_name'){
			$fname= '<span id="orangeText">' . $fname .'</span>';
		} else if($searchField=='last_name'){
			$lname= '<strong>' . $lname .'</strong>';
		} else if($searchField=='town'){
			$town= '<strong>' . $town .'</strong>';
		} else if($searchField=='postcode'){
			$postcode= '<strong>' . $postcode .'</strong>';
		} else if($searchField=='first_name'){
			$country_id= '<strong>' . $results[$i]['country_id'] .'</strong>';
		} else if($searchField=='first_name'){
			$user_id= '<strong>' . $results[$i]['user_id'] .'</strong>';
		}
	?>
		<div style="width:600px;">
			<div style="width:90px;height:105px;border:1px solid #D6D6D6;" class="divRow" align="center">
				<img src="<?php echo $profilePhotoSrc; ?>" width="<?php echo $profilePhotoWidth; ?>" height="<?php echo $profilePhotoHeight; ?>" border="0"  style="margin-top:<?php echo $profileTopmargin; ?>px" />
			</div>
			 <div style="width:450px;" class="divRow">
				<div>
						Name&nbsp;:&nbsp;<?php echo $fname . $lname; ?>
				 </div>
				 <div>
						Town&nbsp;:&nbsp;<?php echo $town; ?>
				 </div>
				 <div>
						Postcode&nbsp;:&nbsp;<?php echo $postcode; ?>
				 </div>
				 <div>
						Location &nbsp;:&nbsp;<?php echo $countryName; ?>
				 </div>
				<!-- <div>
						Email&nbsp;:&nbsp;<?php// echo $userEmail; ?>
				 </div>-->
			 </div>
             <div class="clear"></div>
		 </div>
         <?php if($flag==0){
			 echo "Already Member in Group";
		 } else { ?>
	<p>Click here to Add as a Member&nbsp;&nbsp;<input type="image" src="public/images/search-button.gif" style="border:0;cursor:hand;" name="addMem" value="<?php echo $userId; ?>" alt="Add"/></p>
	<?
		 }
	}
}
else
{
	echo '<p> No Search Result Found </p>';
}
?>
</div>