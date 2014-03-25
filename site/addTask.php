<?php
include("includes/checklogin.php");
include("includes/dbconnect.php");
$user = $_SESSION['INSE_userID'];

// Functions
// Add Task
	if(ISSET($_POST['taskName'])){
		// Task variables
		$taskName = $_POST['taskName'];
		$taskDescription = $_POST['taskDescription'];
		$taskStartDate = $_POST['taskStartDate'];
		$taskEndDate = $_POST['taskEndDate'];
		$taskEarliestStartDate = $_POST['taskEarliestStartDate'];
		$taskLatestEndDate = $_POST['taskLatestEndDate'];
		$taskProject = $_POST['taskProject'];
		$taskPredecessor = $_POST['taskPredecessor'];
		$taskParentID = $_POST['taskParentID'];
		
		// Project Variables used for validation
		$result = mysql_query("SELECT * FROM INSE_tblProject WHERE projectID='$taskProject'");
		$row = mysql_fetch_array($result);
		$projectStartDate = $row['projectStartDate'];
		$projectEndDate =  $row['projectDeadline'];
		
		
		
// Validation and Filtering
$validity = "valid";

// Adds the inputted data into an array

$data = array($taskName,$taskDescription);
$dates = array($taskStartDate,$taskEndDate,$taskEarliestStartDate,$taskLatestEndDate);

// Loops through to check if any dates are not within the project dates
if($validity == "valid"){
foreach($dates as $date){
	if($date < $projectStartDate){
		$validity = "You are trying to add something that starts before the project starts. The task has not been created.";
	}
	else{
		$validity = "valid";
	}
	if($date > $projectEndDate){
		$validity = "You are trying to add something that ends after the project ends. The task has not been created.";
	}
	else{
		$validity = "valid";
	}
}
}

// Checks if a tasks start date is before its earliest start date
if($validity == "valid"){
	if($taskStartDate < $taskEarliestStartDate){
		$validity = "You are trying to add a task that starts before the tasks earliest start date. The task has not been created.";
	}
	else{
		$validity = "valid";
	}
}
	
// Checks if a tasks end date is after its latest end date
if($validity == "valid"){
	if($taskEndDate > $taskLatestEndDate){
		$validity = "You are trying to add a task that ends after the tasks latest end date. The task has not been created.";
	}
	else{
		$validity = "valid";
	}
}

// Check if has dangerous characters
if($validity == "valid"){
	$inputs = array($taskName,$taskDescription);
	
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

		$sql2="INSERT INTO INSE_tblTask(taskName,taskDescription,taskStartDate,taskEndDate,taskEarliestStartDate,taskLatestEndDate,projectID,taskPredecessor,parentID) VALUES('$taskName','$taskDescription','$taskStartDate','$taskEndDate','$taskEarliestStartDate','$taskLatestEndDate','$taskProject','$taskPredecessor','$taskParentID')";

		if (!mysql_query($sql2))
		  {
		  die('Error: ' . mysql_error());
		  }
		header('Location: index.php?pg=viewTasks');
}
// If not valid
else{
	echo '<section class="error">';
	echo '<h2>Error!</h2>';
	echo '<p>' . $validity . '</p>';
	echo '<p>' . $errorMatch . '</p>';
	echo '</section>';
}
}

// Edit Task
	if(ISSET($_POST['taskNameEdit'])){
		// Project Data Input
		$taskID = $_POST['taskID'];
		$taskName = $_POST['taskNameEdit'];
		$taskDesc = $_POST['taskDescription'];
		$taskStartDate = $_POST['taskStartDate'];
		$taskEndDate = $_POST['taskEndDate'];
		$taskEarliestStart = $_POST['taskEarliestStartDate'];
		$taskLatestEndDate = $_POST['taskLatestEndDate'];

		$sql="UPDATE INSE_tblTask SET taskName='$taskName', taskDescription='$taskDesc', taskStartDate='$taskStartDate', taskEndDate='$taskEndDate', taskEarliestStartDate='$taskEarliestStart', taskLatestEndDate='$taskLatestEndDate' WHERE taskID='$taskID'";

		if (!mysql_query($sql))
		  {
		  die('Error: ' . mysql_error());
		  }
		header('Location: index.php?pg=viewTasks');
}
// End of Edit
?>
<section>

<a href="index.php"><button class='submit' type='submit'name='back'>Back</button></a>

<form class="form-inputs" method="POST" onload="parentAndPredeccessor(this.value,' . $taskID . ')">
<ul>
<?php
$anyProjects = mysql_query("SELECT COUNT(*) FROM INSE_tblProject WHERE userID='$user'");

// Checks if the user has any projects
if($anyProjects > 0){
if(ISSET($_GET['edit'])){
// Edit Project
$taskID = $_GET['edit'];

$sql = mysql_query("SELECT * FROM INSE_tblTask WHERE taskID=$taskID");
$row = mysql_fetch_array($sql);

$taskName = $row['taskName'];
$taskDesc =  $row['taskDescription'];
$taskStartDate = $row['taskStartDate'];
$taskEndDate = $row['taskEndDate'];
$taskEarliestStart = $row['taskEarliestStartDate'];
$taskLatestEndDate = $row['taskLatestEndDate'];
$projectID = $row['projectID'];
$taskPredecessor = $row['taskPredecessor'];
$parentID = $row['parentID'];

// Display form filled in, ready to edit
echo '
<h3>Edit Task</h3>
<li>
	<label for="taskNameEdit">Task Name: </label>
		<input type="text" name="taskNameEdit" value="' . $taskName . '" autofocus required/> <br/>
</li>
<li>		
	<label for="taskDescription">Task Description: </label>
		<textarea type="text" name="taskDescription"/>' . $taskDesc . '</textarea> <br/>
</li>
<li>
	<label for="taskStartDate">Task Start Date: </label>
		<input id="taskStartDate" type="date" name="taskStartDate" value="' . $taskStartDate . '" required/><br/>
</li>
<li>		
	<label for="taskEndDate">Task End Date: </label>
		<input type="date" name="taskEndDate" value="' . $taskEndDate . '" required/> <br/>
</li>
<li>		
	<label for="taskEarliestStartDate">Task Earliest Start Date: </label>
		<input id="taskEarliestStartDate" type="date" name="taskEarliestStartDate" value="' . $taskEarliestStart . '" required/> <br/>
</li>
<li>		
	<label for="taskLatestEndDate">Task Latest End Date: </label>
		<input type="date" name="taskLatestEndDate" value="' . $taskLatestEndDate . '" required/> <br/>
</li>
<li>';
	// Automatically fill the drop down menus with the users projects
	$sql = mysql_query("SELECT * FROM INSE_tblProject WHERE projectID=$projectID");
	$result = mysql_fetch_array($sql);
	
	$projectName = $result['projectName'];
	
echo'	
	<label for="taskProject">Task Project: </label>
	<select name = "taskProject" required onchange="parentAndPredeccessor(this.value)">';

	

	echo '<option value="' . $projectID . '">' . $projectName . '</option>';
	
	$sql2 = mysql_query("SELECT * FROM INSE_tblProject WHERE userID=$user AND projectID!=$projectID");
	
	while($row = mysql_fetch_array($sql2)){
		$projectName = $row['projectName'];
		$projectID = $row['projectID'];
		echo '<option value = "' . $projectID . '">' . $projectName . '</option>';
	}
echo'
</select> <br/>
</li>

<li>
<div id="parentAndPredeccessor"></div>
</li>

<li>
	<input type="hidden" name="taskID" value="' . $taskID . '">
	<button class="submit" type="submit" value="Submit">Edit</button>
</li>
';
}

else{
// Display Add Form
echo '
<h3>Add Task</h3>
<li>
	<label for="taskName">Task Name: </label>
		<input type="text" name="taskName" value="' . $_POST['taskName'] . '" required/> <br/>
</li>
<li>		
	<label for="taskDescription">Task Description: </label>
		<textarea type="text" name="taskDescription"/>' . $_POST['taskDescription'] . '</textarea> <br/>
</li>
<li>
	<label for="taskStartDate">Task Start Date: </label>
		<input type="date" name="taskStartDate" value="' . $_POST['taskStartDate'] . '" required/><br/>
</li>
<li>		
	<label for="taskEndDate">Task End Date: </label>
		<input type="date" name="taskEndDate" value="' . $_POST['taskEndDate'] . '" required/> <br/>
</li>
<li>		
	<label for="taskEarliestStartDate">Task Earliest Start Date: </label>
		<input type="date" name="taskEarliestStartDate" value="' . $_POST['taskEarliestStartDate'] . '" required/> <br/>
</li>
<li>		
	<label for="taskLatestEndDate">Task Latest End Date: </label>
		<input type="date" name="taskLatestEndDate" value="' . $_POST['taskLatestEndDate'] . '" required/> <br/>
</li>
<li>		
	<label for="taskProject">Task Project: </label>
	<select name = "taskProject" onchange="parentAndPredeccessor(this.value)" value="' . $_POST['taskProject'] . '" required>
	<option value=""></option>';
	
	// Automatically fill the drop down menus with the users projects
	$result = mysql_query("SELECT * FROM INSE_tblProject WHERE userID=$user");
	while($row = mysql_fetch_array($result)){
		$projectName = $row['projectName'];
		$projectID = $row['projectID'];
		echo '<option value = "' . $projectID . '">' . $projectName . '</option>';
}
echo'
</select> <br/>
</li>

<li>
<div id="parentAndPredeccessor"></div>
</li>

<li>
	<button class="submit" type="submit" value="Submit">Add</button>
</li>';
}
}

// If the user hasnt created a project yet
else{
	echo '<h3>Error</h3>
	<li>
		<p>You need to create a Project first!</p>
	</li>
';
}
?>
</ul>
<p>&nbsp;</p>
	
</form>

</section>