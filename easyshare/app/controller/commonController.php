<?php
	include_once('app/model/commonModel.php');
	include_once("app/model/listingModel.php");
	include_once("app/model/loginModel.php");
	include_once("app/model/groupModel.php");
	include_once("app/model/searchModel.php");
	
	//$model=new groupModel($SERVER,$DBASE,$USERNAME,$PASSWORD);
	$model=new searchModel($SERVER,$DBASE,$USERNAME,$PASSWORD);
	
	include_once("listingController.php");
	include_once("loginController.php");
	include_once("homeController.php");
	include_once("groupController.php");
	include_once("searchController.php");
	
	//$controller = new groupController($model);
	$controller = new searchController($model);
	
	$process=NULL;
	$param1=NULL;
	$param2=NULL;
	$param3=NULL;
	
	if(isset($_REQUEST['process']))
	{
		$process=$_REQUEST['process'];
	}

	if(isset($_REQUEST['param1']))	{ $param1=$_REQUEST['param1']; }
	if(isset($_REQUEST['param2']))	{ $param2=$_REQUEST['param2']; }
	if(isset($_REQUEST['param3']))	{ $param3=$_REQUEST['param3']; }
	
	if(isset($process))
	{
			switch($process)
			{
				case $process : $controller->$process($param1,$param2,$param3);
				break;
				default : $controller->mainContent();
				break;
			}
	}
	else
	{
		$controller->mainContent();
	}
?>