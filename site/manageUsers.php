<?php
include('includes/checklogin.php');
include('includes/dbconnect.php');
$uGrpID = $_SESSION[INSE_uGrpID];
$admin ='1';

if($uGrpID === $admin)
{
echo '<a href="index.php"><button class="submit" type="submit" name="back">Back</button></a>';
echo ('<h1 class="title">Manage Users</h1>');
if ($_GET['R'] <> "") { 
	$R = $_GET['R']; 
	$Udel = "DELETE FROM INSE_tblUser WHERE userID='$R'";
	mysql_query($Udel)
	or die (mysql_error());
	} 
if ($_GET['A'] <> "") { 
	$A = $_GET['A']; 
	$MkAdmin = "UPDATE INSE_tblUser SET uGrpID = '1' WHERE userID='$A'";
	mysql_query($MkAdmin)
	or die (mysql_error());
	}
if ($_GET['U'] <> "") { 
	$U = $_GET['U']; 
	$MkUsr = "UPDATE INSE_tblUser SET uGrpID = '2' WHERE userID='$U'";
	mysql_query($MkUsr)
	or die (mysql_error());
	}

if ($_GET['dis'] <> "") { 
	$dis = $_GET['dis']; 
	$disUsr = "UPDATE INSE_tblUser SET userDisabled = '1' WHERE userID='$dis'";
	mysql_query($disUsr)
	or die (mysql_error());
	}

if ($_GET['en'] <> "") { 
	$en = $_GET['en']; 
	$enUsr = "UPDATE INSE_tblUser SET userDisabled = '0' WHERE userID='$en'";
	mysql_query($enUsr)
	or die (mysql_error());
	}


$per_page = 9; 
$page = 1;

if (isset($_GET['page'])) 
	{
	  $page = intval($_GET['page']); 
	  if($page < 1) $page = 1;
	}
$start_from = ($page - 1) * $per_page; 

$sql= mysql_query("SELECT u.userID, u.userFName, u.userLName, u.userEmail, u.userDisabled, g.uGrpName FROM INSE_tblUser as u INNER JOIN INSE_tblUserGrp as g on u.uGrpID = g.uGrpID WHERE u.userID > '1' ORDER BY g.uGrpID, u.userDisabled ASC LIMIT $start_from, $per_page")
or die(mysql_error());
if( mysql_num_rows($sql) > 0)
	{
	echo "	<table class='taskList'>
			<tr class='taskList'>
			<th class='taskList'>Name</th>
			<th class='taskList'>Email</th>
			<th class='taskList'>User Type</th>
			<th class='taskList'>Admin</th>
			<th class='taskList' colspan='2'>Action</th>
			</tr>";
	while($INSE_account = mysql_fetch_array( $sql )) 	
		{
		echo "<tr class='taskList'>";
		echo "<td class='taskList'>" . $INSE_account['userFName'] . " " . $INSE_account['userLName'] . "</td>";
		echo "<td class='taskList'>" . $INSE_account['userEmail'] . "</td>";
		echo "<td class='taskList'>" . $INSE_account['uGrpName'] . "</td>";
		// Displays Turn off for Admins, and Turn on for Users
		if($INSE_account['uGrpName'] != 'Admin'){
			echo "<td class='taskList'><a href='index.php?pg=manageUsers&A=" . $INSE_account['userID'] . "'><button class='bluebtn' type='submit'name='adminon'>Switch On</button></a></td>";
		}
		else{
			echo "<td class='taskList'><a href='index.php?pg=manageUsers&U=" . $INSE_account['userID'] . "'><button class='orangebtn' type='submit'name='adminoff'>Switch Off</button></a></td>";
		}
		// Displays Disable for enabled accounts, and enable on for disabled accounts
		if($INSE_account['userDisabled'] == 0){
			echo "<td class='taskList'><a href='index.php?pg=manageUsers&dis=" . $INSE_account['userID'] . "'><button class='redbtn' type='submit'name='disusr'>Disable Account</button></a></td>";
		}
		else{
			echo "<td class='taskList'><a href='index.php?pg=manageUsers&en=" . $INSE_account['userID'] . "'><button class='button-sm' type='submit'name='enusr'>Enable Account</button></a></td>";
		}
		echo "<td class='taskList'><a href=\"index.php?pg=manageUsers&R=" . $INSE_account['userID'] . "\" onclick=\"return confirm('Really Delete?');\"><button class='redbtn' type='submit'name='deleteuser'>Delete</button></a></td>";
		echo "</tr>";
		}
	echo "</table>";
	}
	else
	{
		echo 'this page does not exists'; 
	}
	$total_rows = mysql_query("SELECT COUNT(*) FROM `INSE_tblUser`WHERE userID > '1'");
	$total_rows = mysql_fetch_row($total_rows);
	$total_rows = $total_rows[0];

	$total_pages = $total_rows / $per_page;
	$total_pages = ceil($total_pages); 
	$next = $page + 1;
	$prev = $page - 1;
	echo "<span id='pagenos'>";
	echo "Page: ";
	if ($page !== 1)
	{
		echo "<a href='index.php?pg=manageUsers&page=$prev'>Previous</a> | ";
	}
	for($i = 1; $i  <= $total_pages; ++$i)
	{
	 echo "<a href='index.php?pg=manageUsers&page=$i'>$i</a> | ";
	}
	if ($page < $total_pages)
	{
		echo "<a href='index.php?pg=manageUsers&page=$next'>Next</a>";
	}
	echo "</span>";

}
else {
	header("Location: index.php");
}
?>