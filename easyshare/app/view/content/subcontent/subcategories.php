<?php

    if($_SESSION['cat_search'] && !$_SESSION['sidebar_search']){
        $size = ' size="5" ';
    }

    $subcategory_count = count($subcategories);
    for($i=0;$i<$subcategory_count;$i++){
        $s='';
        if($subcategories[$i]['id']==$ssubcategory){
            $s = 'selected="selected"';
        }
        $subcategory_options .= "<option value='{$subcategories[$i]['id']}' $s>{$subcategories[$i]['name']}</option>";
    }
    
?>
        
<?php if($subcategory_options){ ?>
        <select class="element select medium" id="ssubcategory" name="ssubcategory" <?php echo $size; ?>>
            <option value="0"> -- select subcategory -- </option> 
        <?php echo $subcategory_options; ?>
        </select>
<?php } ?>