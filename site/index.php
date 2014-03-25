<?php
session_start();
include ("includes/dbconnect.php");
include ("includes/checklogin.php");
ob_start();
?>
<!DOCTYPE html>
<html>
	<head> 
		<title>Chartpad - Web-based Project Management Solution</title> 
		<!-- Metadata -->
		<meta name="description" content="Chartpad is a web-based project management solution. Create customised charts from common data. 
		Completely free to use. Export as a number of different formats. Enter data using easy to use and well designed interfaces. 
		Uses web technologies to render the charts in real-time, on your browser. Get started now by logging in or signing up" />
		<meta name="keywords" content="INSE, Chartpad, Chart, GANTT, PERT, WBT, Project, Management" />
		<meta name="author" content="Brian Brewer, Michael Goodwin, Jill Pomares, Michael Sharp, Scott Walton" />
		<meta charset="UTF-8" /> 
		<!-- Link Stylesheets and Scripts -->
		<link rel="stylesheet" type="text/css" href="includes/style.css" />
		<script type="text/javascript" src="scripts/dock/jquery.js"></script>
		<script type="text/javascript" src="scripts/interface.js"></script>
		<script type="text/javascript" src="scripts/dock.js"></script>
		<script type="text/javascript" src="scripts/mikeyScript.js"></script>
		<script type="text/javascript" src="scripts/faq.js"></script>
		<link rel="stylesheet" type="text/css" href="help/css/start/style.css" />

		<script type="text/javascript" src="help/js/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="help/js/jquery-ui-1.10.2.custom.js"></script>
		<script type="text/javascript" src="help/js/jquery.mousewheel.js"></script>
		<script type="text/javascript" src="help/js/jquery.fancybox.js?v=2.1.4"></script>
		<link rel="stylesheet" type="text/css" href="help/css/jquery.fancybox.css?v=2.1.4" media="screen" />
		
		<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
			<![endif]--> 
	</head>
	<body>
		<div class="header">
			<a href="index.php"><img src="images/logo_sm.png" alt="Chartpad"></a>
			<?php
			$userID = $_SESSION['INSE_userID'];
			$sql = mysql_query("SELECT dock FROM INSE_tblUser WHERE userID = $userID ");
			$row = mysql_fetch_array($sql);
			$dock = $row['dock'];
			if($dock == 1) { ?>
				<script src="scripts/touchmouse.js" type="text/javascript"></script>
				<div class="dock" id="dock">
					<div class="dock-container">
						<a class="dock-item" href="index.php?pg=dockhome"><img src="images/home.png" alt="home" /><span>HOME</span></a> 
						<a class="dock-item" href="index.php?pg=viewProjects"><img src="images/projects.png" alt="tasks" /><span>PROJECTS</span></a> 
						<a class="dock-item" href="index.php?pg=viewTasks"><img src="images/tasks.png" alt="tasks" /><span>TASKS</span></a> 
						<a class="dock-item" href="index.php?pg=faq"><img src="images/faq.png" alt="faq" /><span>FAQ</span></a> 
						<a class="dock-item" href="index.php?pg=help"><img src="images/help.png" alt="help" /><span>HELP</span></a> 
						<a class="dock-item" href="index.php?pg=account"><img src="images/account.png" alt="account" /><span>ACCOUNT</span></a> 
						<?php
						if ($_SESSION['INSE_uGrpID'] === "1") {
						?>
						<a class="dock-item" href="index.php?pg=admin"><img src="images/account.png" alt="account" /><span>ADMIN</span></a> 
						<?php
						}
						?>
						<a class="dock-item" href="logout.php"><img src="images/logout.png" alt="calendar" /><span>LOGOUT</span></a> 
					</div>
				</div>
			<?php
			} else {
			?>
			<ul class="navigation">
				<li><a href="logout.php">Logout</a></li>
				<?php
				if ($_SESSION['INSE_uGrpID'] === "1") {
				?>
				<li class="submenu"><a href="#">Admin</a>
					<ul>
						<li><a href="index.php?pg=manageUsers">Manage Users</a></li>
						<li><a href="index.php?pg=manageProjects">Manage Projects</a></li>
						<li><a href="index.php?pg=manageTasks">Manage Tasks</a></li>
						<li><a href="index.php?pg=faq-mgmt">Manage FAQ</a></li>
					</ul>
				</li>
				<?php
				}
				?>
				<li><a href="index.php?pg=account">Account</a></li>
				<li class="submenu"><a href="#">Help</a>
					<ul>
						<li><a href="index.php?pg=faq">FAQ</a></li>
						<li><a href="index.php?pg=help">Help Centre</a></li>
						<li><a href="index.php?pg=support">Support Desk</a></li>
					</ul>
				</li>
				<li class="submenu"><a href="#">Task</a>
					<ul>
						<li><a href="index.php?pg=addTask">Add Task</a></li>
						<li><a href="index.php?pg=viewTasks">View Tasks</a></li>
					</ul>
				</li>
				<li class="submenu"><a href="#">Project</a>
					<ul>
						<li><a href="index.php?pg=addProject">Add Project</a></li>
						<li><a href="index.php?pg=viewProjects">View Projects</a></li>
					</ul>
				</li>
				<li><a href="index.php">Home</a>
				</li>
			</ul>
			<?php
			}
			?>
			
			</div>
		<div id="content">
			<?php 
			
			if (isset($_GET['pg']))
			{
				include ($_GET['pg'] . ".php");
			}
			else
			{
				$userID = $_SESSION['INSE_userID'];
				$sql = mysql_query("SELECT dock FROM INSE_tblUser WHERE userID = $userID ");
				$row = mysql_fetch_array($sql);
				$dock = $row['dock'];
				if($dock == 1) { 
				include ('dockhome.php');
				}
				else
				{
				include ('home.php');
				}
			}
			?>
		</div>
	</body>
</html>