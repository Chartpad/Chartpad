<?php
session_start();
if(isset($_SESSION['INSE_Email']))
	{ 
	$uGrpID = $_SESSION['INSE_uGrpID'];
	$admin = '1';
	
	$hour = date("G");
    if ($hour >= 0 && $hour <= 11) {
        echo ('<h1 class="title">Good Morning ' . $_SESSION['INSE_NameF'] . ', you are signed in with account: ' . $_SESSION['INSE_Email']. '</h1>');
	} else if ($hour >= 12 && $hour <= 17) {
        echo ('<h1 class="title">Good Afternoon ' . $_SESSION['INSE_NameF'] . ', you are signed in with account: ' . $_SESSION['INSE_Email']. '</h1>');
    } else {
	   	echo ('<h1 class="title">Good Evening ' . $_SESSION['INSE_NameF'] . ', you are signed in with account: ' . $_SESSION['INSE_Email']. '</h1>');
	}
	
	echo ('
	<h1 class="homeHeading">Add</h1>
	<a href="index.php?pg=addProject" alt="Add Project"><button class="button-sm" type="submit" name="addproject">Add Project</button></a> 
	<a href="index.php?pg=addTask" alt="Add Task"><button class="button-sm" type="submit" name="addtask">Add Task</button></a>
	<h1 class="homeHeading">View</h1>
	<a href="index.php?pg=viewProjects" alt="View Projects"><button class="button-sm" type="submit" name="viewprojects">View Projects</button></a>
	<a href="index.php?pg=viewTasks" alt="View Tasks"><button class="button-sm" type="submit" name="viewtasks">View Tasks</button></a>
	<h1 class="homeHeading">Support</h1>
	<a href="index.php?pg=support" alt="Support Desk"><button class="button-sm" type="submit" name="supportdesk">Support Desk</button></a>');
	
	if($uGrpID === $admin)
	{
	echo('<p>&nbsp;</p>
	<h1 class="homeHeading">Admin</h1>');
	echo('<p>	<a href="index.php?pg=bug-mgmt" alt "Known Bugs"><button class="redbtn">Known Bugs</button></a><br />
				<a href="index.php?pg=manageUsers"><button class="button-sm" type="submit" name="manageusers">Manage Users</button></a>
				<a href="index.php?pg=faq-mgmt"><button class="button-sm" type="submit" name="faqmanagement">FAQ Management</button></a></p>');
	echo('<p> 	<a href="index.php?pg=manageTasks"><button class="button-sm" type="submit" name="managealltasks">Manage All Tasks</button></a> 
				<a href="index.php?pg=manageProjects"><button class="button-sm" type="submit" name="manageallprojects">Manage All Projects</button></a>  </p>');
	}
	else
	{
	}
	}
 	else
	{
	header("Location: ../");
	}

?>