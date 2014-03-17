<?php

if($postdata){
    $postdata = urlencode(serialize($postdata));
}


?>


<!-- MOVE TO CSS WHEN DONE...-->
<style type="text/css">
.simpleformfield label{display:inline;float:left;width:120px;margin:20px 10px 20px 0px;text-align:right;}
.simpleformfield input{display:inline;float:left;width:200px;margin:20px 10px 20px 0px;text-align:left;}
.clear{clear:both;}
</style>


<h1>Tell a Friend</h1>
    <!--<div>
<span id="submitmsg" style="float:left">Please enter the users information below.</span>&nbsp;&nbsp;&nbsp;&nbsp;<span style="float:right">
<span id="mand">*</span>&nbsp;Fields are Mandatory.</span>
</div>-->

    
    <div id="infoMessage"><?php if(isset($message)){echo $message;}?></div>

<?php if(!$done){
    ?>
    <p>Tell your friend about the listing on Easyshare.com</p>

    <form method="post" action="" id="form_tellfriend">
        
        <input type="hidden" name="listing_id" id="listing_id" value="<?php echo $listing_id; ?>" />
        <input type="hidden" name="postdata" id="postdata" value="<?php echo $postdata; ?>" />
    
    <div class="simpleformfield">
        <label for="myname">My Name</label>
        <input class="simpletextfield" type="text" name="myname" id="myname" value="<?php echo htmlentities($myname); ?>" />
    </div><br class="clear" />
    
     <div class="simpleformfield">
        <label for="myemail">My Email</label>
        <input class="simpletextfield" type="email" name="email" id="email" value="<?php echo htmlentities($email); ?>" />
    </div><br class="clear" />
    
     <div class="simpleformfield">
        <label for="friendemail">Friend's Email</label>
        <input class="simpletextfield" type="email" name="friendemail" id="friendemail" value="<?php echo htmlentities($friendemail); ?>" />
    </div><br class="clear" />
    

    
    
     <div class="simpleformfield">
        <label for="submit"></label>
        <input type="submit" id="submit" name="submit" value="Send" style="width:100px;margin-left:100px;text-align:center;" />
    </div><br class="clear" />
    
    </form>
    <?php
}
?>

<?php

if($postdata){
    ?>
    <a href="?process=advanced_search_page&param2=<?php echo $listing_id; ?>&param3=<?php echo $postdata; ?>">Back to Listing</a><br />    
<?php
}
else{
    ?>
    <a href="?process=listing_display&param1=<?php echo $listing_id; ?>">Back to Listing</a><br />    
<?php    
}
