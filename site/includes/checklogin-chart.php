<?php
session_start();

if(!isset($_SESSION['INSE_Email']))
	{ 
	$_SESSION['INSE_url'] = $_SERVER["PHP_SELF"];
	header("Location: ../");
	}
 	else
	{
	}

?>