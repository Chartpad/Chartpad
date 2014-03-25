<?php
session_start();
if(isset($_SESSION['INSE_Email']))
	{ 
	$uGrpID = $_SESSION['INSE_uGrpID'];
	$admin = '1';
	if($uGrpID === $admin)
	{
	echo('<h1 class="homeHeading">Admin</h1>');
	echo('<p><a href="index.php?pg=manageUsers"><button class="button-sm" type="submit" name="manageusers">Manage Users</button></a>
				<a href="index.php?pg=faq-mgmt"><button class="button-sm" type="submit" name="faqmanagement">FAQ Management</button></a>
				<a href="index.php?pg=manageTasks"><button class="button-sm" type="submit" name="managealltasks">Manage All Tasks</button></a> 
				<a href="index.php?pg=manageProjects"><button class="button-sm" type="submit" name="manageallprojects">Manage All Projects</button></a>  </p>');
	}
	else
	{
	header("Location: index.php");
	}
	}
 	else
	{
	header("Location: ../");
	}

?>