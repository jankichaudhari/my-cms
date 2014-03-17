<?php


    
?>
<script type="text/javascript">

function display_subcategory(maincat){
    //$('#subcategory').load('/Easyshare_110707-ossi-search_section/app/view/content/loadtest.html');
    $('#subcategory').load('index.php?process=subcategory&param1='+maincat);
    //alert(maincat);
}


function display_sublisting(listing_id){
    $('#sublisting').load('index.php?process=sublisting&param1='+listing_id+'&param2=<?php echo urlencode(serialize($_POST)); ?>'); 
    for(var i=0;i<10;i++){
        $('.item_no'+i).removeClass('stickyhover');
       //$('.item_no'+i).addClass('unhover');
       //$('.item_no'+i).fadeOut();
       
    }
}

function toggleDetails(item){
    /*
    for(var i=0;i<10;i++){
        $('.item_no'+i).removeClass('stickyhover');
    }
    $(item).toggleClass('stickyhover');
    */
    $(item).toggle();
}

function stickyon(item){
    for(var i=0;i<10;i++){
        $('.item_no'+i).removeClass('stickyhover');
    }
    $(item).toggleClass('stickyhover');
}

function stickyoff(item){
    for(var i=0;i<10;i++){
        $('.item_no'+i).removeClass('stickyhover');
    }
}

function showDetails(item){
    //alert('show '+item)
    //$(item).show();
    for(var i=0;i<10;i++){
        $('.item_no'+i).removeClass('stickyhover');
    }
    $(item).addClass('stickyhover');}

function hideDetails(item){
    //alert('hide '+item)
    for(var i=0;i<10;i++){
        $('.item_no'+i).removeClass('stickyhover');
    }
}


function presubmit(){

    //enter validation here
    
    return true;
} 

</script>

<!-- STYLES - MOVE TO STYLESHEET WHEN ALL WORKING -->
<style type="text/css" media="screen">
#advanced_search_form td{border:solid 1px #2C2C2C;padding:5px;background:#D6D6D6;font-size:11px;}   
#advanced_search_form td div {display:inline;}

#advanced_search_form input.small{width:60px; font-size:12px;}
#advanced_search_form input.medium{width:150px;font-size:12px;}
#advanced_search_form input.long{wproduct-slideshow-doc-readyidth:260px;font-size:12px;}

#advanced_search_form input.keywordfield{width:564px; font-size:12px;}
#advanced_search_form select.medium{width:165px;}

td.col1 {width:190px;}

td.col2 {width:160px;} 

td.col3 {width:254px;} 

td.rightj{text-align:right; }



/*SEARCH RESULTS*/
#search_results {width:580px;position:relative;margin-left:50px;}

/*NOT HOVERED*/
.search_item{width:104px;height:80px;display:inline;float:left;padding:0;margin:0 4px 4px 0;}   
img.search_item_image {width:102px;height:78px;overflow:hidden;border:solid 1px #666;}
.search_item a span{display:none; position:absolute;z-index:1;top:70px;left:-30px;width:160px;background:#FFFFFF;border:solid 1px #aaa;padding:30px 5px 10px 5px;}


/*HOVERED*/
.search_item a span.item_no0 {left:-35px;top:65px;}
.search_item a span.item_no1 {left:75px;top:65px;}
.search_item a span.item_no2 {left:180px;top:65px;}
.search_item a span.item_no3 {left:290px;top:65px;}
.search_item a span.item_no4 {left:395px;top:65px;}
.search_item a span.item_no5 {left:-35px;top:145px;}
.search_item a span.item_no6 {left:75px;top:145px;}
.search_item a span.item_no7 {left:180px;top:145px;}
.search_item a span.item_no8 {left:290px;top:145px;}
.search_item a span.item_no9 {left:395px;top:145px;}
.search_item a:hover span{display:block; }
.search_item a span.stickyhover{display:block;}


/*hovered contents*/
.search_item h3{font-size:12px;font-weight:normal;text-align:center;border-bottom:dotted 1px #666;}
.search_item dd{padding:0;margin:0;}
.search_item dd{color:#0D8DEC;font-style:normal;font-size:10px}
.search_item dt{color:#9D9C9C;font-style:normal;font-size:11px}
.search_item dd,.search_item dt{display: block; float: left;text-align:left;margin:0;padding:0;width:75px;height:22px;line-height:11px;}
.search_item dt{ clear: both; padding-left:2px;}

img.search_item_image_hover {position:absolute;left:25px;top:-73px;width:122px;height:98px;overflow:hidden;}

.more_link{text-align:right;font-size:9px;color:##0D8DEC;cursor:pointer;cursor:hand;}

/*pager*/

.pager_button_form{display:inline;text-align:center;padding:0px;margin:0;}
.pager_button{border:none;padding:0px;margin:0;}

.pager{text-align:center;}
.pager_arrows{text-align:center;}
.pager_arrows form{display:inline;padding:0;margin:0!important; position:relative;top:7px;}
.pager_arrows form input{padding:0;margin:0;}
.pager_details{font-size:10px; text-align:center;background:#242424;padding:4px 10px;display:inline;margin:0 2px;}


#sublisting{
    margin-top:25px;
    font-size:10px;
}

.clear {clear:both;}

.catbox{width:200px;float:left;display:inline;margin-bottom:20px;}
</style>


<div class='mainInfo'>
<div id="subContainer">
    
    
    
    <?php
    /*
    echo 'POST TO SERIALIZE:';
    print_r($_POST);
    echo 'SERIALIZED:';
    $mypost = urlencode(serialize($_POST));
    print_r(urlencode(serialize($_POST)));
    echo 'UNSERIALIZED:';
    print_r(unserialize(urldecode($mypost)));
    */
    ?>


    
    
    
<?php
    //print_r($_POST);
?>
    
    <h1><!-- Advanced --> Search</h1>
	<!--<div>
    <span id="submitmsg" style="float:left">Please enter the users information below.</span>&nbsp;&nbsp;&nbsp;&nbsp;<span style="float:right">
    <span id="mand">*</span>&nbsp;Fields are Mandatory.</span>
    </div>-->
	
	<div id="infoMessage"><?php if(isset($message)){echo $message;}?></div>
    
    
            <?php if(!$sidebar_search){
                ?>
                    <form id="advanced_search_form" class=""  method="post" action="index.php?process=advanced_search_page" onsubmit="return presubmit();">
                        <input type="hidden" name="userId" id="userId" value="<?php echo $userId; ?>" />	

                            <!-- category search version start -->
                            <?php
                                if($cat_search){
                                    ?>
                                    <div  class="catbox">
                                        <label class="description" for="scategory"></label>
                                        <div>
                                        <select class="element select medium" id="scategory" name="scategory" onchange="display_subcategory(this.value);"  size="5"> 
                                                <option value="0" selected="selected"> -- select category -- </option> 
                                                <?php echo $category_options; ?>
                                        </select>
                                        </div> 
                                    </div>

                                    <div  class="catbox">
                                        <!-- dynamically loaded subcategory start -->                   
                                        <label class="description" for="ssubcategory"></label>
                                        <div id="subcategory">
                                           
                                           <?php if($subcategory_options){ ?>
                                                   <select class="element select medium" id="ssubcategory" name="ssubcategory"  size="5"   > 
                                                   <?php echo $subcategory_options; ?>
                                                   </select>
                                           <?php } ?>
                                        </div>
                                        
                                        <!-- dynamically loaded subcategory end -->   
                                    </div>
                                    <br class="clear" />
                                    
                                    <input id="saveForm" class="button_text" type="submit" name="submit" value="Search" />
                
                                    <?php
                                }
                                else{
                            
                            ?>
                            <!-- category search version end -->

                            
                        <table cellpadding="0" cellspacing="0">
                            <?php
                            
                            if($keyword || TRUE){
                              ?>
                            <tr>
                                <td colspan="3">
                                    <label class="description" for="keyword">Search for: </label>
                                    <div>
                                            <input id="keyword" name="keyword" class="element text keywordfield" type="text" maxlength="255" value="<?php echo htmlentities($keyword); ?>"/> 
                                    </div>
                                </td>
                            </tr>
                              <?php
                            }
                            ?>
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
                                <td class="col3">
                                    Cost of share: <label class="description" for="smincost">Min</label>
                                    <div>
                                            <input id="smincost" name="smincost" class="element text small" type="text" maxlength="255" value="<?php echo htmlentities($smincost); ?>"/> 
                                    </div> 
                                    <label class="description" for="smaxcost">Max</label>
                                    <div>
                                            <input id="smaxcost" name="smaxcost" class="element text small" type="text" maxlength="255" value="<?php echo htmlentities($smaxcost); ?>"/> 
                                    </div> 
                                </td>
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
                            </tr>
                            <tr> 
    
                                <td  class="rightj"  colspan="3">
                                    <input id="saveForm" class="button_text" type="submit" name="submit" value="Search" />
                                </td>
                            </tr>
                        </table>
                <?php
                }
            
            ?>        
                    </form>
                
            <?php
                
            }
            ?>
                
               
                
                <?php
                
                //echo '<hr /><hr />BASE RESULTS START:<br />';
                
                //print_r($base_results);
                //echo $sql_conditions;
                
                //echo '<hr />';
                ?>
                
                <?php
                //echo '<hr /><hr />SEARCH RESULTS:<br />';
                
                if(!$resultcount){
                    $resultcount=0;
                }
                
                if($_POST || $keyword || $postdata){
                
                    echo "<h2>Search results - $resultcount matches found:</h2>";
                    
                    /*
                     print_r($search_results);
                    */
                    
                    echo '<div id="search_results">';
                    
                    if(!$offset){
                        $offset = 0;
                    }
                    
                    for($i=0;$i<10;$i++,$offset++){
                        if($offset>=$resultcount){break;}
                        
                        //old link to details - doesn't work as we would again need to send the whole form to get all the rest of the details. so try jquery ajax instead
                        //<a href='?process=advanced_search_page&param2={$search_results[$offset]['id']}'>
                        
                        echo "
                        <div class='search_item'>
                            <a>
                            <span class='item_no$i'  onclick='hideDetails(\".item_no$i\");' >
                                <h3>{$search_results[$offset]['name']}</h3>
                                <dl>
                                
                                    <dt>Cost of Share:&nbsp;</dt>
                                    <dd>{$search_results[$offset]['cost_per_share']}</dd>
                                    
                                    <dt>Location:&nbsp;</dt>
                                    <dd>{$search_results[$offset]['townName']},{$search_results[$offset]['country']}</dd>
                                    
                                    <dt>Group Size:&nbsp;</dt>
                                    <dd>{$search_results[$offset]['group_size']}</dd>
                                    
                                    <dt>Monthly Fees:&nbsp;</dt>
                                    <dd>{$search_results[$offset]['fees']}</dd>
                                    
                                    <dt>Daily Usage Charge:&nbsp;</dt>
                                    <dd>{$search_results[$offset]['usage_charge']}</dd>
                                </dl>
                                <img class='search_item_image_hover' src='{$search_results[$offset]['image_path']}' alt='{$search_results[$offset]['name']}' />
                                <div class='more_link' onclick='display_sublisting({$search_results[$offset]['id']})'>More details &raquo;</div>
                            </span>
                            <img class='search_item_image' src='{$search_results[$offset]['image_path']}' alt='{$search_results[$offset]['name']}' onclick='showDetails(\".item_no$i\");' />
                            
                            </a>
                            
                        </div>
                        ";
                    }
                    
                    echo '<br style="clear:left;" /></div>';
                    
                    //echo '<hr />';
                    
                    if($resultcount<10){
                        echo "<a href='index.php?process=noresults_options'>Can't find what you're looking for?</a>";
                    }
                    
                    if($pagecount>1){
                        echo "<div class='pager'>$pager</div>";
                        
                        echo "<div class='pager_arrows'>$previous_form<div class='pager_details'>Page $pagenumber / $pagecount </div>$next_form</div>";
                    }
                    
                    ?>
    
                    <?php
                    
                    //echo '<hr />Ignored:<br />'.$ignored.'<hr />';
                    
                    ?>
                    
                    <div id="sublisting"><?php
                        if($listing_id){
                            //echo "LISTING ID IS: $listing_id";
                            //if listing_id already known, display it here.
                            include('app/view/content/subcontent/sublisting.php');
                            
                        }
                        else if($resultcount){
                            echo 'Click on an item to view full details';
                        }
                        ?>
                        </div>
                <?php
                }
                ?>
                
</div>
</div>