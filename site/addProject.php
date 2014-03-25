<?php
include("includes/checklogin.php");
include("includes/dbconnect.php");
$user = $_SESSION['INSE_userID'];

// Functions
// Add Project
if(ISSET($_POST['projectName'])){
	$projectName = $_POST['projectName'];
	$projectStartDate = $_POST['projectStartDate'];
	$projectEndDate = $_POST['projectEndDate'];

// Validation and Filtering
$validity = "valid";

// Check Project End Date
if($validity == "valid"){
	// Checks if the finish date is before the start date
	if($projectEndDate < $projectStartDate){
		$validity = "Project End Date is before the Project Start";
	}
	else{
		$validity = "valid";
	}
}

// Check if has dangerous characters
if($validity == "valid"){
	$inputs = array($projectName);
	
	foreach($inputs as $input){
	if($validity == "valid"){
		if(preg_match('/[^a-z0-9, ]/i', $input, $matches)){
			$validity = "You have inputted an invalid character.";
			foreach($matches as $match){
				$errorMatch = "Invalid Characters: '" . $match . "'";
			}
		}
	}
	}
}	
	
if($validity == "valid"){
	// Project Data Input
	
	$sql="INSERT INTO INSE_tblProject(userID,projectName,projectStartDate,projectDeadline) VALUES('$user','$projectName','$projectStartDate','$projectEndDate')";

	if (!mysql_query($sql))
	  {
	  die('Error: ' . mysql_error());
	  }
	header('Location: index.php?pg=viewProjects');
}

else{
	echo '<section class="error">';
	echo '<h2>Error!</h2>';
	echo '<p>' . $validity . '</p>';
	echo '<p>' . $errorMatch . '</p>';
	echo '</section>';
}
}

// Edit Project
if(ISSET($_POST['projectNameEdit'])){
	// Project Data Input
	$projectID = $_POST['projectID'];
	$projectName = $_POST['projectNameEdit'];
	$projectStartDate = $_POST['projectStartDateEdit'];
	$projectEndDate = $_POST['projectEndDateEdit'];

	$sql="UPDATE INSE_tblProject SET projectName='$projectName',projectStartDate='$projectStartDate',projectDeadline='$projectEndDate' WHERE projectID='$projectID'";

	if (!mysql_query($sql))
	  {
	  die('Error: ' . mysql_error());
	  }
	header('Location: index.php?pg=viewProjects');
}
?>
<section>

<a href="index.php"><button class='submit' type='submit'name='back'>Back</button></a>

<form class="form-inputs" method="POST">
<ul>
</li>
<?php
if(ISSET($_GET['edit'])){
	// Edit Project
	$projectID = $_GET['edit'];

	$sql = mysql_query("SELECT * FROM INSE_tblProject WHERE projectID='$projectID'");
	$row = mysql_fetch_array($sql);

	$projectName = $row['projectName'];
	$projectStartDate = $row['projectStartDate'];
	$projectEndDate = $row['projectDeadline'];

	// Display form filled in, ready to edit
	echo '
		<h3>Edit Project</h3>
		<li>
			<label for="projectName">Project Name: </label>
				<input type="text" name="projectNameEdit" value="' . $projectName . '" autofocus required/><br/>
		</li>
		<li>		
			<label for="projectStartDate">Project Start Date: </label>
				<input type="date" name="projectStartDateEdit" value="' . $projectStartDate . '" required/> <br/>
		</li>
		<li>		
			<label for="projectEndDate">Project End Date: </label>
				<input type="date" name="projectEndDateEdit" value="' . $projectEndDate . '" required/> <br/>
		</li>
		<li>
			<input type="hidden" name="projectID" value="' . $projectID . '"/>
			<button class="submit" type="submit" value="Submit">Edit</button>
		</li>
		';
}

// Add project
else{
	echo '
		<h3>Add Project</h3>
		<li>
			<label for="projectName">Project Name: </label>
				<input type="text" name="projectName" value="' . $_POST['projectName'] . '" autofocus required/><br/>
		</li>
		<li>		
			<label for="projectStartDate">Project Start Date: </label>
				<input type="date" name="projectStartDate" value="' . $_POST['projectStartDate'] . '" required/> <br/>
		</li>
		<li>		
			<label for="projectEndDate">Project End Date: </label>
				<input type="date" name="projectEndDate" value="' . $_POST['projectEndDate'] . '" required/> <br/>
		</li>
		<li>
			<button class="submit" type="submit" value="Submit">Add</button>
		</li>
		<p>&nbsp;</p>
	';
}
?>
</ul>
	
</form>

</section>