<?php
	if($format == 'noFormat')
	{
	}
	else
	{
?>
<div id="header">
	<div id="help">
		<p><a href="index.php?process=edit_record">Add</a>&nbsp;|&nbsp;<a href="index.php?process=myAccount">My Account</a>&nbsp;|&nbsp;<a href="index.php?process=create_user_page">Register</a></p>
	</div>
	<a href="index.php"><h1>Easyshare</h1></a>
</div>
<div id="navBar">
	<div id="navLinks">
		<p><a href="?process=advanced_search_page&param4=0&param5=0">Advanced Search</a> <a href="?process=advanced_search_page&param5=1">Category List</a></p>
	</div>
  	<form name="search" method="post" action="?process=advanced_search_page">
		<input type="hidden" name="param4" value="1" />
		<input type="hidden" name="param5" value="0" />
    <input name="param1" type="text" value="Search for ..." size="37" class="textField" onFocus="if(this.value == 'Search for ...') this.value = '';" onBlur="if(this.value == '') this.value = 'Search for ...';">
    
	<!--<input name="Categories" type="text" value="All Categories" size="25" readonly class="selectField"><a href="#"><img src="public/images/selectarrow2.gif" alt="Select Category" width="165" height="19" border="0" align="top" class="selectArrow"></a>
	<div ID="selectDiv">
	<ul ID="selectList">
		<li><input name="Categories" type="text" value="All Categories" size="25" readonly class="selectField"><img src="public/images/selectarrow2.gif" alt="Select Category" width="165" height="19" border="0" align="top" class="selectArrow">
		<ul>
			<li><a href="#">Aeroplanes</a></li>
			<li><a href="#">Automobiles</a></li>
			<li><a href="#">Sailing Boats</a></li>
		</ul>
		</li>
	</ul>-->
    <!-- <a href="index.php?process=advanced_search_page">search</a> -->
	<input type="image" src="public/images/search-button.gif" alt="Search" width="22" height="19" border="0" align="top" class="searchButton" value="Search" name="submit" style="border:none!important;margin-top:1px;" /></a>
  	</div>	
	</form>
</div>

<?php } ?>