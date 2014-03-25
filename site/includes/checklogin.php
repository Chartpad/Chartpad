<?php
session_start();
include ("includes/dbconnect.php");
if(!isset($_SESSION['INSE_Email']))
	{ 
	$_SESSION['INSE_url'] = $_SERVER["PHP_SELF"];
	header ("Location: ../");
	}
 	else
	{
	$email = $_SESSION['INSE_Email']; 
 	$accountcheck = mysql_query("SELECT * FROM INSE_tblUser WHERE userEmail = '$email'")
	or die(mysql_error());
	while($INSE_account = mysql_fetch_array( $accountcheck )) 	
	{
	if ($INSE_account['userDisabled'] == 1)
		{
		session_start();
		session_destroy();
		if(isset($_SESSION['INSE_Email']))
		unset($_SESSION['INSE_Email']);
		header('Location: ../accdis.php');
		}
		else
		{
		}
	}
	}

?>