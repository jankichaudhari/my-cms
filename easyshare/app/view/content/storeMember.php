<div class='mainInfo'>
<div id="subContainer">
<form name="addMember" id="addMember" action="index.php?process=storeMember" method="post">
<input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId; ?>"/>
<input type="hidden" name="memId" id="memId" value="<?php echo $memId; ?>"/>
<div class="error"><?php echo $message; ?></div>
<h1>Add Member</h1><br/>
<p>
Type your Search Keyword&nbsp;:<br/><?php print_r($selectedMemName) ?>
<input type="text" name="memSearch" id="memSearch" value="<?php echo $selectedMemName; ?>" />
</p>
<p>
Select Search Criteria&nbsp;:<br/>
<select name="searchFields" id="searchFields">
<option value="first_name">First Name</option>
<option value="last_name">Last Name</option>
<option value="town">Town</option>
<option value="postcode">Postcode</option>
<option value="name">Country</option>
</select>
</p>
<p>Click here to Search&nbsp;&nbsp;<input type="image" src="public/images/search-button.gif" style="border:0;cursor:hand;" name="search" value="search" alt="searchMember" id="searchMember"/></p>
<p><span id="searchResult"></span></p>
</form>
</div>
</div>