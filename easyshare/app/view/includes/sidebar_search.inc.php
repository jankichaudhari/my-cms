<style>

#advanced_search_form_sidebar{font-size:10px;}
#advanced_search_form_sidebar input{font-size:10px;}
#advanced_search_form_sidebar select{font-size:10px;}


#advanced_search_form_sidebar input.text {margin-bottom:10px;}
#advanced_search_form_sidebar select {margin-bottom:10px;}

#advanced_search_form_sidebar input.small{width:44px;}
#advanced_search_form_sidebar input.medium{width:80px;}
#advanced_search_form_sidebar input.large{width:160px;}
#advanced_search_form_sidebar select.large{width:165px;}
#advanced_search_form_sidebar select.medium{width:165px;}

#advanced_search_form_sidebar label.small{width:30px;}
#advanced_search_form_sidebar label.medium{width:80px;}
#advanced_search_form_sidebar label.large{width:160px;}

#advanced_search_form_sidebar label.keywordfield{width:40px;}
#advanced_search_form_sidebar input.keywordfield{width:120px;}

#advanced_search_form_sidebar div.form_row label {float:left;display:inline;}
#advanced_search_form_sidebar div.form_row input {float:left;display:inline;}

.middlelabel {margin-left:6px;}

.clear {clear:both;}

</style>

<h2>Search options</h2>

<form id="advanced_search_form_sidebar" class=""  method="post" action="index.php?process=advanced_search_page&param4=1" onsubmit="return presubmit();">
	<input type="hidden" name="userId" id="userId" value="<?php echo $userId; ?>" />	
		
 
	<?php
	
	if($keyword || TRUE){
		if(!$cat_search){
			//CAT SEARCH EXCLUDED BLOCK 1 START
	  ?>

		<div class='form_row'>
			<label class="description keywordfield" for="keyword">Search: </label>
			<input id="keyword" name="keyword" class="element text keywordfield medium" type="text" maxlength="255" value="<?php echo htmlentities($keyword); ?>"/> 
		</div><br class="clear" />

	  <?php
		  //CAT SEARCH EXCLUDED BLOCK 1 END
		}
		else{
			$keyword = '';
		}
	}
	?>
			 
	<div>
		<label class="description" for="scategory"></label>
		<select class="element select large" id="scategory" name="scategory" onchange="display_subcategory(this.value);"> 
			<option value="0" selected="selected"> -- select category -- </option> 
			<?php echo $category_options; ?>
		</select>
	</div> 

	<!-- dynamically loaded subcategory start -->   
	<div id="subcategory">
		<label class="description" for="ssubcategory"></label>
		<?php if($subcategory_options){ ?>
				<select class="element select large" id="ssubcategory" name="ssubcategory"> 
				<?php echo $subcategory_options; ?>
				</select>
		<?php } ?>
	</div>
	<!-- dynamically loaded subcategory end -->   
	
	<?php
		if(!$cat_search){
			//CAT SEARCH EXCLUDED BLOCK 2 START
	?>
	
	<div class='form_row'>
		<label class="description medium" for="spostcode">UK post code: </label>
		<input id="spostcode" name="spostcode" class="element text medium" type="text" maxlength="255" value="<?php echo htmlentities($spostcode); ?>"/> 
	</div><br class="clear" />

	<div>
		<label class="description" for="sdistance"></label>   
		<select class="element select large" id="sdistance" name="sdistance">
			<?php echo $distance_options; ?>
		</select> 
	</div>


	Cost of share:<br /> 
	<div class='form_row'>
		<label class="description small" for="smincost">Min:</label>
		<input id="smincost" name="smincost" class="element text small" type="text" maxlength="255" value="<?php echo htmlentities($smincost); ?>"/> 
		<label class="description small middlelabel" for="smaxcost">Max:</label>
		<input id="smaxcost" name="smaxcost" class="element text small" type="text" maxlength="255" value="<?php echo htmlentities($smaxcost); ?>"/> 
	</div><br class="clear" />



	Group size:<br /> 
	<div class='form_row'>
		<label class="description small" for="smingroup">Min:</label>
		<input class="element text small" id="smingroup" name="smingroup" type="text" maxlength="255" value="<?php echo htmlentities($smingroup); ?>"/> 
		<label class="description small middlelabel" for="smaxgroup">Max:</label>
		<input class="element text small" id="smaxgroup" name="smaxgroup" type="text" maxlength="255" value="<?php echo htmlentities($smaxgroup); ?>"/> 
	</div><br class="clear" />


	Fees:<br /> 
	<div class='form_row'>
		<label class="description small" for="sminfee">Min: </label>
		<input id="sminfee" name="sminfee" class="element text small" type="text" maxlength="255" value="<?php echo htmlentities($sminfee); ?>"/> 

		<label class="description small middlelabel" for="smaxfee">Max: </label>
		<input id="smaxfee" name="smaxfee" class="element text small" type="text" maxlength="255" value="<?php echo htmlentities($smaxfee); ?>"/> 
	</div><br class="clear" /> 

	<div>
		<label class="description" for="stimeframe_fee"></label>
		<select class="element select large" id="stimeframe_fee" name="stimeframe_fee"> 
			<?php echo $timeframe_fee_options; ?>
		</select>
	</div>

	Usage charge:<br /> 
	<div class='form_row'>
		<label class="description small" for="sminusage">Min: </label>
		<input id="sminusage" name="sminusage" class="element text small" type="text" maxlength="255" value="<?php echo htmlentities($sminusage); ?>"/> 
		<label class="description small middlelabel" for="smaxusage">Max: </label>
		<input id="smaxusage" name="smaxusage" class="element text small" type="text" maxlength="255" value="<?php echo htmlentities($smaxusage); ?>"/> 
	</div><br class="clear" />

	<div>
		<label class="description" for="stimeframe_usage"></label>
		<select class="element select large" id="stimeframe_usage" name="stimeframe_usage"> 
			<?php echo $stimeframe_usage_options; ?>
		</select>
	</div>

	<?php
		}
		//CAT SEARCH EXCLUDED BLOCK 2 END
	?>

	<div style="text-align:right;margin-right:4px;">
		<input id="saveForm" class="button_text" type="submit" name="submit" value="Search" />
	</div>                            
</form>