<?php
echo("<h1 class='title'>Support Desk</h1>");
include("includes/dbconnect.php");

if (isset($_POST['submit-ticket']))
	{
		if($_FILES['file']['name'] =='')
		{
			$date = date('Y-m-d H:i:s');
			$currentDate = strtotime($date);
			$formatDate = date("Y-m-d H:i:s", $currentDate);
			
			$userID = $_SESSION['INSE_userID'];
			$ticketSubject = $_POST['ticketSubject'];
			$message = $_POST['message'];
			$status = 'Awaiting Response';
			$created_at = $formatDate;
			
			$query = "INSERT INTO INSE_tblTicket (userID, ticketSubject, message, status, created_at) VALUES ('$userID', '$ticketSubject', '$message', '$status', '$created_at')";
			$sql= mysql_query($query);
			$query2 = "SELECT ticketID FROM INSE_tblTicket WHERE userID = '$userID' AND created_at = '$created_at'";
			$sql2 = mysql_query($query2)
			or die(mysql_error());
			$INSE_ticketID = mysql_fetch_array( $sql2 );
			$ticketID = $INSE_ticketID ['ticketID'];
			$query3 = "INSERT INTO INSE_tblTicketComments (userID, ticketID, comment, created_at) VALUES ('1', '$ticketID', 'Ticket Opened', '$created_at')";
			$sql3 = mysql_query($query3)
			or die(mysql_error());
			header ("Location: index.php?pg=support");
		}
		else
		{
			$date = date('Y-m-d H:i:s');
			$currentDate = strtotime($date);
			$formatDate = date("Y-m-d H:i:s", $currentDate);
			
			$userID = $_SESSION['INSE_userID'];
			$ticketSubject = $_POST['ticketSubject'];
			$message = $_POST['message'];
			$status = 'Awaiting Response';
			$created_at = $formatDate;
			
			$allowedExts = array("jpg", "jpeg", "gif", "png");
			$extension = end(explode(".", $_FILES["file"]["name"]));
			if ((($_FILES["file"]["type"] == "image/gif")
			|| ($_FILES["file"]["type"] == "image/jpeg")
			|| ($_FILES["file"]["type"] == "image/png")
			|| ($_FILES["file"]["type"] == "image/pjpeg"))
			&& ($_FILES["file"]["size"] < 1024000)
			&& in_array($extension, $allowedExts))
			  {
			  if ($_FILES["file"]["error"] > 0)
				{
				echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
				}
			  else
				{
				echo "Upload: " . $_FILES["file"]["name"] . "<br>";
				echo "Type: " . $_FILES["file"]["type"] . "<br>";
				echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
				echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

				if (file_exists("images/tickets/" . $userID . $ticketSubject . $created_at . $_FILES["file"]["name"]))
				  {
				  echo  $userID . $ticketSubject . $created_at . $_FILES["file"]["name"] . " already exists. ";
				  }
				else
				  {
				  move_uploaded_file($_FILES["file"]["tmp_name"],
				  "images/tickets/" . $userID . $ticketSubject . $created_at . $_FILES["file"]["name"]);
				  echo "Stored in: " . "images/tickets/" . $userID . $ticketSubject . $created_at . $_FILES["file"]["name"];
				  $ticketScreenshot = ("images/tickets/" . $userID . $ticketSubject . $created_at . $_FILES["file"]["name"]);
				  $query = "INSERT INTO INSE_tblTicket (userID, ticketSubject, message, status, created_at, ticketScreenshot) VALUES ('$userID', '$ticketSubject', '$message', '$status', '$created_at', '$ticketScreenshot')";
					$sql= mysql_query($query);
					$query2 = "SELECT ticketID FROM INSE_tblTicket WHERE userID = '$userID' AND created_at = '$created_at'";
					$sql2 = mysql_query($query2)
					or die(mysql_error());
					$INSE_ticketID = mysql_fetch_array( $sql2 );
					$ticketID = $INSE_ticketID ['ticketID'];
					$query3 = "INSERT INTO INSE_tblTicketComments (userID, ticketID, comment, created_at) VALUES ('1', '$ticketID', 'Ticket Opened', '$created_at')";
					$sql3 = mysql_query($query3)
					or die(mysql_error());
					header ("Location: index.php?pg=support");
				  
				  }
				}
			  }
			else
			  {
			  echo "Invalid file";
			  }
		}
		
	}

if (isset($_POST['submit-comment']))
	{
		if($_FILES['file']['name'] =='')
		{
			$date = date('Y-m-d H:i:s');
			$currentDate = strtotime($date);
			$formatDate = date("Y-m-d H:i:s", $currentDate);
			
			$userID = $_SESSION['INSE_userID'];
			$ticketID = $_POST['ticketID'];
			$comment = $_POST['comment'];
			$created_at = $formatDate;
			$ticketStatus = $_POST['status'];
			//---------
			$query = "INSERT INTO INSE_tblTicketComments (userID, ticketID, comment, created_at) VALUES ('$userID', '$ticketID', '$comment', '$created_at')";
			$query2 = "UPDATE INSE_tblTicket SET status='$ticketStatus' WHERE ticketID = '$ticketID'";
			echo $query;
			echo $query2;
			$sql= mysql_query($query);
			$sql2 = mysql_query($query2)
			or die(mysql_error());
			header ("Location: index.php?pg=support&a=view&tid=$ticketID");
			}
			else
			{
			$date = date('Y-m-d H:i:s');
			$currentDate = strtotime($date);
			$formatDate = date("Y-m-d H:i:s", $currentDate);
			
			$userID = $_SESSION['INSE_userID'];
			$ticketID = $_POST['ticketID'];
			$comment = $_POST['comment'];
			$created_at = $formatDate;
			$ticketStatus = $_POST['status'];
			
			$allowedExts = array("jpg", "jpeg", "gif", "png");
			$extension = end(explode(".", $_FILES["file"]["name"]));
			if ((($_FILES["file"]["type"] == "image/gif")
			|| ($_FILES["file"]["type"] == "image/jpeg")
			|| ($_FILES["file"]["type"] == "image/png")
			|| ($_FILES["file"]["type"] == "image/pjpeg"))
			&& ($_FILES["file"]["size"] < 1024000)
			&& in_array($extension, $allowedExts))
			  {
			  if ($_FILES["file"]["error"] > 0)
				{
				echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
				}
			  else
				{
				echo "Upload: " . $_FILES["file"]["name"] . "<br>";
				echo "Type: " . $_FILES["file"]["type"] . "<br>";
				echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
				echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

				if (file_exists("images/tickets/" . $userID . $ticketSubject . $created_at . $_FILES["file"]["name"]))
				  {
				  echo  $userID . $ticketSubject . $created_at . $_FILES["file"]["name"] . " already exists. ";
				  }
				else
				  {
				  move_uploaded_file($_FILES["file"]["tmp_name"],
				  "images/tickets/" . $userID . $ticketSubject . $created_at . $_FILES["file"]["name"]);
				  echo "Stored in: " . "images/tickets/" . $userID . $ticketSubject . $created_at . $_FILES["file"]["name"];
				  $ticketScreenshot = ("images/tickets/" . $userID . $ticketSubject . $created_at . $_FILES["file"]["name"]);
				  $query = "INSERT INTO INSE_tblTicketComments (userID, ticketID, comment, created_at, ticketScreenshot) VALUES ('$userID', '$ticketID', '$comment', '$created_at', '$ticketScreenshot')";
					$query2 = "UPDATE INSE_tblTicket SET status='$ticketStatus' WHERE ticketID = '$ticketID'";
					echo $query;
					echo $query2;
					$sql= mysql_query($query);
					$sql2 = mysql_query($query2)
					or die(mysql_error());
					header ("Location: index.php?pg=support&a=view&tid=$ticketID");
				  
				  }
				}
			  }
			else
			  {
			  echo "Invalid file";
			  }
		}
		
		
		
		
		
	}

if (isset($_GET['a']))
{
	if ($_GET['a'] == 'new')
	{
		?>
		<form class="form-inputs" method="post" name="new-ticket-form" enctype='multipart/form-data'>
			<ul>
				<li>
					 <h1 class="title">New Support Ticket</h1>
					 
				</li>
				<li>
					<label for="ticketSubject">Ticket Subject:</label>
					<input type="text" name="ticketSubject" />
					
				</li>
				<li>
					<label for="message">Message:</label>
					<textarea rows="6" cols="20" name="message"></textarea>
				</li>
				<li>
					<label for="ticketScreenshot">Upload Screenshot:</label>
					<input type="file" name="file" id="file" />
				</li>
				<li>
					<button class="submit" type="submit" name="submit-ticket">Submit Ticket</button>
				</li>
			</ul>
		</form>
		<?php
	}
	
	if ($_GET['a'] == 'update')
	{
		if ($_GET['tid'] <> "") {
		$tid = $_GET['tid'];
		?>
		<form class="form-inputs" method="post" name="update-ticket-form" enctype='multipart/form-data'>
			<ul>
				<li>
					 <h1 class="title">Update Support Ticket: <?php echo($tid);?></h1>
					 
				</li>
				<li>
					<label for="status">Ticket Status:</label>
					<select name="status">
						<option name="AwitingResponseUser" value="Awaiting Response from User">Awaiting Response from User</option>
						<option name="Pending" value="Pending">Pending</option>
						<option name="InQueue" value="In Queue">In Queue</option>
						<option name="Closed" value="Closed">Closed</option>
					</select>
					
				</li>
				<li>
					<label for="comment">Comment:</label>
					<textarea rows="6" cols="20" name="comment"></textarea>
					
				</li>
				<li>
					<label for="ticketScreenshot">Upload Screenshot:</label>
					<input type="file" name="file" id="file" />
				</li>
				<li>
					<input type="hidden" name="ticketID" value="<?php echo($tid);?>">
					<button class="submit" type="submit" name="submit-comment">Update Ticket</button>
				</li>
			</ul>
		</form>
		<?php
		}
		else
		{
			header ("Location: index.php?pg=support");
		}
	}
	
	if ($_GET['a'] == 'view')
	{
		if ($_GET['tid'] <> "") { 
			$tid = $_GET['tid'];
			$sql = mysql_query("SELECT ticket.ticketID, user.userFName, user.userLName, ticket.ticketSubject, ticket.message, ticket.status, ticket.created_at, ticket.ticketScreenshot FROM INSE_tblTicket AS ticket INNER JOIN INSE_tblUser AS user ON ticket.userID = user.userID WHERE ticketID = '$tid' ")
				   or die(mysql_error());
			$result= mysql_num_rows($sql);
			echo "<div id='ticketTable'><table class='suppDeskTicketList'>
				<tr>
				<th>Ticket ID</th>
				<th>User</th>
				<th>Subject</th>
				<th>Message</th>
				<th>Status</th>
				<th>Time</th>
				<th>Image</th>
				</tr>";
			while($INSE_ticket = mysql_fetch_array( $sql )) 	
				{
				echo "<tr>";
				echo "<td class='center'>" . $INSE_ticket['ticketID'] . "</td>";
				echo "<td class='center'>" . $INSE_ticket['userFName'] . " " . $INSE_ticket['userLName'] . "</td>";
				echo "<td>" . $INSE_ticket['ticketSubject'] . "</td>";
				echo "<td>" . $INSE_ticket['message'] . "</td>";
				echo "<td class='center'>" . $INSE_ticket['status'] . "</td>";
				echo "<td class='center'>" . $INSE_ticket['created_at'] . "</td>";
				if($INSE_ticket['ticketScreenshot'] == NULL){
				echo "<td></td>";}
				else{
				echo "<td class='center'><a href='" . $INSE_ticket['ticketScreenshot'] . "'><button class='bluebtn'>Image</button></a></td>";}
				echo "</tr>";
				}
			
			echo "</table><p>&nbsp;</p>";
			
			$per_page = 15; 
			$page = 1;

			if (isset($_GET['page'])) 
				{
				  $page = intval($_GET['page']); 
				  if($page < 1) $page = 1;
				}
			$start_from = ($page - 1) * $per_page; 
			$sql2 = mysql_query("SELECT * FROM INSE_tblTicketComments AS comment INNER JOIN INSE_tblUser AS user ON comment.userID = user.userID WHERE comment.ticketID = '$tid' LIMIT $start_from, $per_page")
				   or die(mysql_error());
			$result2= mysql_num_rows($sql2);
			if( mysql_num_rows($sql) > 0)
			{
			echo "<h1 class='title'>Ticket Comments</h1>	<table class='suppDeskTicketList'>
				<tr>
				<th>User</th>
				<th>Comment</th>
				<th>Time</th>
				<th>Image</th>
				</tr>";
			while($INSE_comment = mysql_fetch_array( $sql2 )) 	
				{
				echo "<tr>";
				echo "<td class='center'>" . $INSE_comment['userFName'] . " " . $INSE_comment['userLName'] . "</td>";;
				echo "<td>" . $INSE_comment['comment'] . "</td>";
				echo "<td class='center'>" . $INSE_comment['created_at'] . "</td>";
				if($INSE_comment['ticketScreenshot'] == NULL){
				echo "<td></td>";}
				else{
				echo "<td class='center'><a href='" . $INSE_comment['ticketScreenshot'] . "'><button class='bluebtn'>Image</button></a></td>";}
				echo "</tr>";
				}
			echo "</table></div>";
			}
			else
			{
				echo 'this page does not exists'; 
			}
			$total_rows = mysql_query("SELECT COUNT(*) FROM INSE_tblTicketComments WHERE ticketID = '$tid'");
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
				echo "<a href='index.php?pg=support&a=view&tid=$tid&page=$prev'>Previous</a> | ";
			}
			for($i = 1; $i  <= $total_pages; ++$i)
			{
			 echo "<a href='index.php?pg=support&a=view&tid=$tid&page=$i'>$i</a> | ";
			}
			if ($page < $total_pages)
			{
				echo "<a href='index.php?pg=support&a=view&tid=$tid&page=$next'>Next</a>";
			}
			echo "</span>";
			echo ('<p><a href="index.php?pg=support"><button class="button-sm" type="submit" name="back">Back</button></a><a href="index.php?pg=support&a=update&tid=' . $INSE_ticket['ticketID'] . '" alt"Update Ticket"><button class="button-sm" name="update-ticket">Update Ticket</button></a>');
			
		}
	}
	
	if ($_GET['a'] == 'close')
	{
		if ($_GET['tid'] <> "") { 
			$tid = $_GET['tid'];
			$sql = mysql_query("UPDATE INSE_tblTicket SET status = 'Closed' WHERE ticketID = $tid")
				   or die(mysql_error());
			header ("Location: index.php?pg=support");
			
		}
	}
}
else
{
	echo ('<p><a href="index.php?pg=support&a=new"><button class="bluebtn" type="submit" name="new-ticket">New Ticket</button></a>');
	$per_page = 15; 
	$page = 1;

	if (isset($_GET['page'])) 
		{
		  $page = intval($_GET['page']); 
		  if($page < 1) $page = 1;
		}
	$start_from = ($page - 1) * $per_page; 
	$sql= mysql_query("SELECT * FROM INSE_tblTicket LIMIT $start_from, $per_page")
		  or die(mysql_error());
	$result= mysql_num_rows($sql);
	if( mysql_num_rows($sql) > 0)
	{
	echo "	<div id='ticketTable'><table class='suppDeskTicketList'>
				<tr>
				<th>Ticket ID</th>
				<th>Subject</th>
				<th>Status</th>
				<th>Image</th>
				<th>Action</th>
				</tr>";
	while($INSE_ticket = mysql_fetch_array( $sql )) 	
		{
		echo "<tr>";
		echo "<td class='center'>" . $INSE_ticket['ticketID'] . "</td>";
		echo "<td>" . $INSE_ticket['ticketSubject'] . "</td>";
		echo "<td class='center'>" . $INSE_ticket['status'] . "</td>";
		if($INSE_ticket['ticketScreenshot'] == NULL){
		echo "<td></td>";}
		else{
		echo "<td class='center'><a href='" . $INSE_ticket['ticketScreenshot'] . "'><button class='bluebtn'>Image</button></a></td>";}
		echo "<td class='center'><a href='index.php?pg=support&a=view&tid=" . $INSE_ticket['ticketID'] . "' alt'View Ticket'><button class='bluebtn' name='view-ticket'>View</button></a>
		<a href='index.php?pg=support&a=update&tid=" . $INSE_ticket['ticketID'] . "' alt'Update Ticket'><button class='button-sm' name='update-ticket'>Update Ticket</button></a>
		<a href='index.php?pg=support&a=close&tid=" . $INSE_ticket['ticketID'] . "' alt'Close Ticket'><button class='redbtn' name='close-ticket'>Close Ticket</button></a></td>";
		echo "</tr>";
		}
	echo "</table></div>";
	}
	else
	{
		echo 'this page does not exists'; 
	}
	$total_rows = mysql_query("SELECT COUNT(*) FROM `INSE_tblTicket`");
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
		echo "<a href='index.php?pg=support&page=$prev'>Previous</a> | ";
	}
	for($i = 1; $i  <= $total_pages; ++$i)
	{
	 echo "<a href='index.php?pg=support&page=$i'>$i</a> | ";
	}
	if ($page < $total_pages)
	{
		echo "<a href='index.php?pg=support&page=$next'>Next</a>";
	}
	echo "</span>";
	echo ('<p><a href="index.php"><button class="button-sm" type="submit" name="back">Back</button></a>');
}


?>