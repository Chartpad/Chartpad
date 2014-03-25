<?php
include ("includes/checklogin.php");
include ("includes/dbconnect.php");
if(isset($_SESSION['INSE_Email']))
	{ 
	$uGrpID = $_SESSION['INSE_uGrpID'];
	$admin = '1';
if($uGrpID === $admin)
	{
		include ("support-admin.php");
	}
	else
	{
		include ("support-user.php");
	}
	}
	
?>