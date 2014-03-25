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
			$Fdel = "DELETE FROM INSE_tblFAQ WHERE faqID='$del'";
			mysql_query($Fdel)
			or die (mysql_error());
			header ("Location: index.php?pg=faq-mgmt");
			ob_end_flush();
			} 
		if ($_GET['edit'] <> "") { 
			$edit = $_GET['edit']; 
			$result= mysql_query("SELECT * FROM INSE_tblFAQ WHERE faqID = $edit")
			or die(mysql_error());
			while ($row = mysql_fetch_assoc($result)){
			
			$faqQ = $_POST['faqQ'];
			$faqA = $_POST['faqA'];
			if(isset($_POST['editfaq'])){
			$chFAQ = "UPDATE INSE_tblFAQ SET faqQ = '$faqQ', faqA = '$faqA' WHERE faqID='$edit'";
			mysql_query($chFAQ)
			or die (mysql_error());
			header ("Location: index.php?pg=faq-mgmt");
			ob_end_flush();
			}
			else
			{
			
			
			echo ('<form class="form-inputs" method="post" name="faq-mgmt-form">
				<ul>
					<li>
						 <h1 class="title">FAQ Management</h1>
						 
					</li>
					<li>
						<label for="faqQ">Question:</label>
						<textarea rows="6" cols="20" name="faqQ">' . $row["faqQ"] . '</textarea>				
					</li>
					<li>
						<label for="faqA">Answer:</label>
						<textarea rows="6" cols="20" name="faqA">' . $row["faqA"] . '</textarea>
					</li>
					<li>
						<button class="submit" type="submit" name="editfaq">Save</button>
					</li>
				</ul>
			</form>');
			}
			}
			}
		if (!isset($_GET['del']) && !isset($_GET['edit']))
		{
		if(isset($_POST['savefaq'])){
		$faqQ = $_POST['faqQ'];
		$faqA = $_POST['faqA'];
		
		$addFAQ = "INSERT INTO INSE_tblFAQ (faqQ,faqA) VALUES ('$faqQ', '$faqA')";
		mysql_query($addFAQ)
		or die (mysql_error());
		header ("Location: index.php?pg=faq-mgmt");
		ob_end_flush();
		}
		
		echo ('<form class="form-inputs" method="post" name="faq-mgmt-form">
			<ul>
				<li>
					 <h1 class="title">FAQ Management</h1>
					 
				</li>
				<li>
					<label for="faqQ">Question:</label>
					<textarea rows="6" cols="20" name="faqQ"></textarea>				
				</li>
				<li>
					<label for="faqA">Answer:</label>
					<textarea rows="6" cols="20" name="faqA"></textarea>
				</li>
				<li>
					<button class="submit" type="submit" name="savefaq">Save</button>
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

		$sql= mysql_query("SELECT * FROM INSE_tblFAQ LIMIT $start_from, $per_page")
		or die(mysql_error());
		if( mysql_num_rows($sql) > 0)
		{
		$result= mysql_num_rows($sql);
		echo "	<table>";
		while($INSE_faq = mysql_fetch_array( $sql )) 	
			{
			echo "<tr>";
			echo "<td>Question:</td>";
			echo "<td>" . $INSE_faq['faqQ'] . "</td></tr>";
			echo "<tr><td>Answer:</td>";
			echo "<td>" . $INSE_faq['faqA'] . "</td></tr>";
			echo "<tr><td colspan='2'><a href=\"index.php?pg=faq-mgmt&del=" . $INSE_faq['faqID'] . "\" onclick=\"return confirm('Really Delete?');\"><button class='button-sm' type='submit'name='deletefaq'>Delete FAQ</button></a>
					<a href='index.php?pg=faq-mgmt&edit=" . $INSE_faq['faqID'] . "'><button class='button-sm' type='submit'name='edit'>Edit</button></a>
					</td>";
			echo "</tr>";
			}
		echo "</table>";
		}
		else
		{
			echo 'this page does not exists'; 
		}
		$total_rows = mysql_query("SELECT COUNT(*) FROM INSE_tblFAQ");
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
			echo "<a href='index.php?pg=faq-mgmt&page=$prev'>Previous</a> | ";
		}
		for($i = 1; $i  <= $total_pages; ++$i)
		{
		 echo "<a href='index.php?pg=faq-mgmt&page=$i'>$i</a> | ";
		}
		if ($page < $total_pages)
		{
			echo "<a href='index.php?pg=faq-mgmt&page=$next'>Next</a>";
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