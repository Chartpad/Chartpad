<?php
include ("includes/dbconnect.php");
if(isset($_SESSION['INSE_Email']))
	{ 
	$uGrpID = $_SESSION['INSE_uGrpID'];
	$admin = '1';}
if($uGrpID === $admin)
	{
		if ($_GET['del'] <> "") { 
			$del = $_GET['del']; 
			$Bdel = "DELETE FROM INSE_tblBUG WHERE bugID='$del'";
			mysql_query($Bdel)
			or die (mysql_error());
			header ("Location: index.php?pg=bug-mgmt");
			ob_end_flush();
			} 
		if ($_GET['edit'] <> "") { 
			$edit = $_GET['edit']; 
			$result= mysql_query("SELECT * FROM INSE_tblBUG WHERE bugID = $edit")
			or die(mysql_error());
			while ($row = mysql_fetch_assoc($result)){
			
			$bugTitle = $_POST['bugTitle'];
			$bugDetails = $_POST['bugDetails'];
			if(isset($_POST['editbug'])){
			$chBUG = "UPDATE INSE_tblBUG SET bugTitle = '$bugTitle', bugDetails = '$bugDetails' WHERE bugID='$edit'";
			mysql_query($chBUG)
			or die (mysql_error());
			header ("Location: index.php?pg=bug-mgmt");
			ob_end_flush();
			}
			else
			{
			
			
			echo ('<form class="form-inputs" method="post" name="bug-mgmt-form">
				<ul>
					<li>
						 <h1 class="title">Known Bugs</h1>
						 
					</li>
					<li>
						<label for="bugTitle">Title:</label>
						<textarea rows="6" cols="20" name="bugTitle">' . $row["bugTitle"] . '</textarea>				
					</li>
					<li>
						<label for="bugDetails">Details:</label>
						<textarea rows="6" cols="20" name="bugDetails">' . $row["bugDetails"] . '</textarea>
					</li>
					<li>
						<button class="submit" type="submit" name="editbug">Save</button>
					</li>
				</ul>
			</form>');
			}
			}
			}
		if (!isset($_GET['del']) && !isset($_GET['edit']))
		{
		if(isset($_POST['savebug'])){
		$bugTitle = $_POST['bugTitle'];
		$bugDetails = $_POST['bugDetails'];
		
		$addbug = "INSERT INTO INSE_tblBUG (bugTitle, bugDetails) VALUES ('$bugTitle', '$bugDetails')";
		mysql_query($addbug)
		or die (mysql_error());
		header ("Location: index.php?pg=bug-mgmt");
		ob_end_flush();
		}
		
		echo ('<form class="form-inputs" method="post" name="bug-mgmt-form">
			<ul>
				<li>
					 <h1 class="title">Known Bugs</h1>
					 
				</li>
				<li>
					<label for="bugTitle">Title:</label>
					<textarea rows="6" cols="20" name="bugTitle"></textarea>				
				</li>
				<li>
					<label for="bugDetails">Details:</label>
					<textarea rows="6" cols="20" name="bugDetails"></textarea>
				</li>
				<li>
					<button class="submit" type="submit" name="savebug">Save</button>
				</li>
			</ul>
		</form>');
		}
		$per_page = 5; 
		$page = 1;
		if (isset($_GET['page'])) 
			{
			  $page = intval($_GET['page']); 
			  if($page < 1) $page = 1;
			}
		$start_from = ($page - 1) * $per_page; 

		$sql= mysql_query("SELECT * FROM INSE_tblBUG LIMIT $start_from, $per_page")
		or die(mysql_error());
		if( mysql_num_rows($sql) > 0)
		{
		$result= mysql_num_rows($sql);
		echo "	<table>";
		while($INSE_BUG = mysql_fetch_array( $sql )) 	
			{
			echo "<tr>";
			echo "<td>Bug Title:</td>";
			echo "<td>" . $INSE_BUG['bugTitle'] . "</td></tr>";
			echo "<tr><td>Details:</td>";
			echo "<td>" . $INSE_BUG['bugDetails'] . "</td></tr>";
			echo "<tr><td colspan='2'><a href=\"index.php?pg=bug-mgmt&del=" . $INSE_BUG['bugID'] . "\" onclick=\"return confirm('Really Delete?');\"><button class='button-sm' type='submit'name='deletebug'>Delete Bug</button></a>
					<a href='index.php?pg=bug-mgmt&edit=" . $INSE_BUG['bugID'] . "'><button class='button-sm' type='submit'name='edit'>Edit</button></a>
					</td>";
			echo "</tr>";
			}
		echo "</table>";
		}
		else
		{
			echo 'this page does not exists'; 
		}
		$total_rows = mysql_query("SELECT COUNT(*) FROM INSE_tblBUG");
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
			echo "<a href='index.php?pg=bug-mgmt&page=$prev'>Previous</a> | ";
		}
		for($i = 1; $i  <= $total_pages; ++$i)
		{
		 echo "<a href='index.php?pg=bug-mgmt&page=$i'>$i</a> | ";
		}
		if ($page < $total_pages)
		{
			echo "<a href='index.php?pg=bug-mgmt&page=$next'>Next</a>";
		}
		echo "</span>";
		
				
		echo ('<p><a href="index.php"><button class="button-sm" type="submit" name="back">Back</button></a><p>&nbsp;</p>');
				
	}
	else
	{
		echo ("You are not authorised to view this page.");
		echo ('<p><a href="index.php"><button class="button-sm" type="submit" name="back">Back</button></a>');
	}
?>