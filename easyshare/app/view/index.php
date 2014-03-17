<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Easy Share</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Page-Enter" content="blendTrans(duration=0.5)" />
<link href="public/css/mainstyles.css" rel="stylesheet" type="text/css">
<link href="public/css/calendar.css" rel="stylesheet" type="text/css">
<link href="public/css/datepicker.css" rel="stylesheet" type="text/css">
<link  href="public/css/mylisting.css" rel="stylesheet" type="text/css" />
<link  href="public/css/myTagListing.css" rel="stylesheet" type="text/css" />
<link  href="public/css/skin.css" rel="stylesheet" type="text/css" />

<!--<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/base/jquery-ui.css" id="theme">
-->
<?php 
$process = NULL;
if(isset($_REQUEST['process']))
{
	$process = $_REQUEST['process'];
}
/*if($_REQUEST['process']=='groupSetUp') { ?>
<link rel="stylesheet" href="public/css/jquery.groupImgUpload-ui.css">
<?php } else { ?>
<link rel="stylesheet" href="public/css/jquery.fileupload-ui.css">
<?php }*/ ?>
<link rel="stylesheet" href="public/css/jquery.groupImgUpload-ui.css">
<link  href="public/scripts/colorbox/example1/colorbox.css" rel="stylesheet" type="text/css" />

<!--[if IE]>
<link href="iehacks.css" rel="stylesheet" type="text/css">
<![endif]-->
<link rel="shortcut icon" href="public/favicon.ico" type="image/x-icon" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript" src="public/scripts/display_content.js"></script>
<script type="text/javascript" src="public/scripts/listing.js"></script>
<script type="text/javascript" src="public/scripts/step3.js"></script>
<!--<script type="text/javascript" src="public/scripts/richTextArea.js"></script>-->
<script type="text/javascript" src="public/tinymce/tiny_mce.js"></script>
<script type="text/javascript" src="public/scripts/pwd_validation.js"></script>
<script type="text/javascript" src="public/scripts/register.js"></script>
<script type="text/javascript" src="public/scripts/login.js"></script>
<script type="text/javascript" src="public/scripts/groupSetup.js"></script>
<script type="text/javascript" src="public/scripts/datepicker.js"></script>
<script type="text/javascript" src="public/scripts/myGroup.js"></script>
<script type="text/javascript" src="public/scripts/myBooking.js"></script>
<script type="text/javascript" src="public/scripts/myUsage.js"></script>
<script type="text/javascript" src="public/scripts/admin.js"></script>
<script type="text/javascript" src="public/scripts/fc.js"></script>
<script type="text/javascript" src="public/scripts/mc.js"></script>
<script type="text/javascript" src="public/scripts/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="public/scripts/myAccount.js"></script>
<script type="text/javascript" src="public/scripts/staticpages.js"></script>
<script type="text/javascript" src="public/scripts/colorbox/colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="public/scripts/jquery.slideshow.js"></script>

</head>
<body>
<?php include_once('app/view/includes/header.inc.php'); ?>
<div id="container">
	<?php if(!empty($process)) { ?>
	<?php include_once('app/view/includes/breadcumbs.inc.php'); ?>
	<?php include_once('app/view/includes/left.inc.php') ?>
    <div id="mainCol">
		<div id="subContainer">
		<?php include_once('app/view/includes/content.inc.php'); ?>
		</div>
	</div>
    <?php } else { include_once('app/view/includes/content.inc.php'); } ?>
    <div class="clear"></div>
	<!--<div id="copyright">
		<p>&copy; Easyshare 2011</p>
	</div>-->
</div>
<?php include_once('app/view/includes/footer.inc.php'); ?>
</body>
</html>