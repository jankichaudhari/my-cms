<?php

    $category_count = count($categories);
    for($i=0;$i<$category_count;$i++){
        $s='';
        if($categories[$i]['id']==$scategory){
            $s = 'selected="selected"';
        }
        $category_options .= "<option value='{$categories[$i]['id']}' $s>{$categories[$i]['name']}</option>";
    }
    
    //IF SUB CATEGORY POSTED, NEED TO LOAD IT BY DEFAULT
    if($ssubcategory){
        $subcategory_count = count($subcategories);
        for($i=0;$i<$subcategory_count;$i++){
            $s='';
            if($subcategories[$i]['id']==$ssubcategory){
                $s = 'selected="selected"';
            }
            $subcategory_options .= "<option value='{$subcategories[$i]['id']}' $s>{$subcategories[$i]['name']}</option>";
        }
    }
    
    $distance_options = select_options(array('-- select distance --','within 1 mile','within 10 mile','within 20 mile','within 30 mile','within 40 mile','within 60 mile','within 100 mile','within 200 mile'),array(1100000,1609,16093,32187,48281,64374,96561,160935,321880),$sdistance);
    
    if($stimeframe_fee>5 || $stimeframe_fee<1){
        $stimeframe_fee = 4; //month by default
    }
    $timeframe_fee_options = select_options(array('per day','per week','per fortnight','per month','per year'),array(1,2,5,3,4),$stimeframe_fee);

    if($stimeframe_usage>5 || $stimeframe_usage<1){
        $stimeframe_usage = 4; //month by default
    }
    $stimeframe_usage_options = select_options(array('per day','per week','per fortnight','per month','per year'),array(1,2,5,3,4),$stimeframe_usage);

    
    function select_options($labels,$values=null,$selected=null){
        $options_count = count($labels); 
        $options='';
        for($i=0;$i<$options_count;$i++){
            $s='';
            if($values!=null){
                $value = $values[$i];
            }
            else{
                $value = $labels[$i];
            }
            if($value==$selected){
                $s = 'selected="selected"';
            }
            $options .= "<option value='$value' $s>{$labels[$i]}</option>";
        }
        return $options;
    }
    
?>

<script type="text/javascript">

function display_subcategory(maincat){
    //$('#subcategory').load('/Easyshare_110707-ossi-search_section/app/view/content/loadtest.html');
    $('#subcategory').load('index.php?process=subcategory&param1='+maincat);
    //alert(maincat);
}


function presubmit(){

    //enter validation here
    
    return true;
} 

</script>

<!-- STYLES - MOVE TO STYLESHEET WHEN ALL WORKING -->
<style type="text/css" media="screen">
#register_interest_form td{border:solid 1px #222;padding:5px;background:#111;font-size:11px;}   
#register_interest_form td div {display:inline;}

#register_interest_form input.small{width:60px; font-size:12px;}
#register_interest_form input.medium{width:150px;font-size:12px;}
#register_interest_form input.long{wproduct-slideshow-doc-readyidth:260px;font-size:12px;}



td.col1 {width:190px;}

td.col2 {width:160px;} 



td.rightj{text-align:right; }




</style>

<div class='mainInfo'>
<div id="subContainer">
    

    <h1>Register Interest</h1>
    
    <p>Please enter details for the item you're looking for and we will notify you when another user either lists or registers interest in an asset meeting their category and distance criteria </p>

	
	<div id="infoMessage"><?php if(isset($message)){echo $message;}?></div>
    
		<form id="register_interest_form" class=""  method="post" action="index.php?process=register_interest" onsubmit="return presubmit();">
		    <input type="hidden" name="userId" id="userId" value="<?php echo $userId; ?>" />	
			
                    <table cellpadding="0" cellspacing="0">

                        <tr>
                            <td class="col1">
                                <label class="description" for="scategory"></label>
                                <div>
                                <select class="element select medium" id="scategory" name="scategory" onchange="display_subcategory(this.value);"> 
                                        <option value="0" selected="selected"> -- select category -- </option> 
                                        <?php echo $category_options; ?>
                
                
                
                                </select>
                                </div> 
                                                 
                
                            </td>
                            <td class="col2">
                                <label class="description" for="spostcode">UK post code </label>
                                <div>
                                        <input id="spostcode" name="spostcode" class="element text small" type="text" maxlength="255" value="<?php echo htmlentities($spostcode); ?>"/> 
                                </div> 
                            </td>
                            <!--
                            <td class="col3">
                                Cost of share: <label class="description" for="smincost">Min</label>
                                <div>
                                        <input id="smincost" name="smincost" class="element text small" type="text" maxlength="255" value="<?php echo htmlentities($smincost); ?>"/> 
                                </div> 
                                <label class="description" for="smaxcost">Max</label>
                                <div>
                                        <input id="smaxcost" name="smaxcost" class="element text small" type="text" maxlength="255" value="<?php echo htmlentities($smaxcost); ?>"/> 
                                </div> 
                            </td>-->
                        </tr>
                        <tr>
                            <td>
                                <!-- dynamically loaded subcategory start -->                   
                                <label class="description" for="ssubcategory"></label>
                                <div id="subcategory">
                                    
                                    <?php if($subcategory_options){ ?>
                                            <select class="element select medium" id="ssubcategory" name="ssubcategory"> 
                                            <?php echo $subcategory_options; ?>
                                            </select>
                                    <?php } ?>
                                    
                                    
                                </div>
                                <!-- dynamically loaded subcategory end -->   
                            </td>
                            <td>
                                <label class="description" for="sdistance"></label>   
                                <div>
                                <select class="element select small" id="sdistance" name="sdistance">
                                    <?php echo $distance_options; ?>
                                </select> 
                
                                </div>
                            </td>
                            <!--
                            <td>
                                Group size:&nbsp;&nbsp;&nbsp;&nbsp;
                                <label class="description" for="smingroup">Min</label>
                                <div>
                                <input class="element text small" id="smingroup" name="smingroup" type="text" maxlength="255" value="<?php echo htmlentities($smingroup); ?>"/> 
                
                                </div> 

                                <label class="description" for="smaxgroup">Max</label>
                                <div>
                                <input class="element text small" id="smaxgroup" name="smaxgroup" type="text" maxlength="255" value="<?php echo htmlentities($smaxgroup); ?>"/> 
                                </div> 
                            </td>
                        </tr>
                        <tr>
                            <td class="rightj"  colspan="3">
                                Fees: &nbsp;&nbsp;&nbsp;

                                <label class="description" for="sminfee">Min </label>
                                <div>
                                        <input id="sminfee" name="sminfee" class="element text small" type="text" maxlength="255" value="<?php echo htmlentities($sminfee); ?>"/> 
                                </div>&nbsp;&nbsp;&nbsp; 
                                <label class="description" for="smaxfee">Max </label>
                                <div>
                                        <input id="smaxfee" name="smaxfee" class="element text small" type="text" maxlength="255" value="<?php echo htmlentities($smaxfee); ?>"/> 
                                </div> &nbsp;&nbsp;&nbsp;
                                <label class="description" for="stimeframe_fee"></label>
                                <div>
                                <select class="element select small" id="stimeframe_fee" name="stimeframe_fee"> 
                                    <?php echo $timeframe_fee_options; ?>
                                </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="rightj"  colspan="3">
                                Usage charge: &nbsp;&nbsp;&nbsp;

                                <label class="description" for="sminusage">Min </label><div>
                                        <input id="sminusage" name="sminusage" class="element text small" type="text" maxlength="255" value="<?php echo htmlentities($sminusage); ?>"/> 
                                </div> &nbsp;&nbsp;&nbsp;
                                <label class="description" for="smaxusage">Max </label>
                                <div>
                                        <input id="smaxusage" name="smaxusage" class="element text small" type="text" maxlength="255" value="<?php echo htmlentities($smaxusage); ?>"/> 
                                </div> &nbsp;&nbsp;&nbsp;
                                <label class="description" for="stimeframe_usage"></label>
                                <div>
                                <select class="element select small" id="stimeframe_usage" name="stimeframe_usage"> 
                                    <?php echo $stimeframe_usage_options; ?>
                                </select>
                                </div>
                            </td>
                        </tr>-->
                        <tr> 

			    <td  class="rightj"  colspan="2">
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Register Interest" />
                            </td>
                        </tr>
                    </table>
		</form>
                
               



                
</div>
</div>