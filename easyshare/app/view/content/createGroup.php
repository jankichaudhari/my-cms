
<div class='mainInfo'>
<div id="subContainer">
<form name="groupOpt" action="index.php?process=groupSetup" method="post">
<input type="hidden" name="groupOpt"  id="groupOpt" value="none"/>
<div style="width:535px;" id="createGroupOpt">
<span><h1>Create a New Group</h1><span id="msgRules"></span></span>
<div class="error"><?php echo $message; ?></div>
<div class="nonActive"><a href="javascript:void(0);;"  id="scratch"><span>Create a New Group from scratch</span></a></div>
<div class="nonActive"><a href="javascript:void(0);"  id="exist"><span>Create a New Group from an existing listing</span></a></div>
</div><br/>
<!-- create group option expanded-->
   <!--  exist -->
    <div id="existBlock" class="nowSubActive">
        <span>
        <select name="listing" id="listing">
		<?php 
        for($i=0;$i<count($listingList);$i++)
        {
            $flag=1;
            for($j=0;$j<count($groupList);$j++){
                if( $listingList[$i]['id']==$groupList[$j]['list_id'])
                {
                    $flag=0;
                }
            }
            if($flag==1){
                ?><option value="<?php echo $listingList[$i]['id']; ?>"><?php echo $listingList[$i]['name']; ?></option><?php
            } 
        }
		if($flag==0) {
        ?>
        <option value="0">No any Listing</option>
        <?php } ?>
        </select>
        </span>
    </div>
    <!-- end exist -->
<!-- end create group option  -->
<br/><br/>
<p id="submitGropuOpt">Click here to Create your new Group&nbsp;&nbsp;<input type="image" src="public/images/search-button.gif" style="border:0;cursor:hand;" name="groupOption" id="groupOption" value="groupOption" alt="submit"/></p>
</form>
</div>
</div>