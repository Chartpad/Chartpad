<?php
include ("includes/checklogin.php");
session_start();
if(isset($_SESSION['INSE_Email']))
	{
	$userID = $_SESSION['INSE_userID'];
	$uGrpID = $_SESSION['INSE_uGrpID'];
	$admin = '1';
	echo ('<h1 class="title">You are signed in as ' . $_SESSION['INSE_Email']. '</h1>');
	if(isset($_POST['dockon']))
	{
	$q = ("UPDATE INSE_tblUser SET dock = '1' WHERE userID = $userID");
	mysql_query ($q)
	or die (mysql_error());
	header ("Location: index.php?pg=account");
	ob_end_flush();
	}
	elseif (isset($_POST['dockoff']))
	{
	$q = ("UPDATE INSE_tblUser SET dock = '0' WHERE userID = $userID");
	mysql_query ($q)
	or die (mysql_error());
	header ("Location: index.php?pg=account");
	ob_end_flush();
	}
		
	
	?>
	<br /> 
	<h2>Settings</h2>
	<p>The dock system may be incompatible with some mobile devices. if you are having trouble, you can revert to the standard navigation.</p>
			<?php
			$userID = $_SESSION['INSE_userID'];
			$sql = mysql_query("SELECT dock FROM INSE_tblUser WHERE userID = $userID ");
			$row = mysql_fetch_array($sql);
			$dock = $row['dock'];
			if($dock == 1) 
			{ 
				echo('<form method="POST"><button class="button-sm" type="submit" name="dockoff">Dock Off</button></form>');
			}
			else {
				echo('	<form method="POST"><button class="button-sm" type="submit" name="dockon">Dock On</button></form>');
					}
					?>
	
	<p><a href="index.php?pg=changepwd" alt="Change Password"><button class="button-sm" type="submit" name="changepassword">Change Password</button></a></p>
	
	<p><a href="index.php"><button class="button-sm" type="submit" name="back">Back</button></a>
	<?php
	}
 	else
	{
	header("Location: ../");
	}
?>