<?php
include("includes/checklogin.php");
include("includes/dbconnect.php");
$user = $_SESSION['INSE_userID'];

// Functions
// Delete Task
if(ISSET($_GET['delete'])){
	$deleteID = $_GET['delete'];
	$sql="DELETE FROM INSE_tblTask WHERE taskID=$deleteID";

	if (!mysql_query($sql))
	  {
	  die('Error: ' . mysql_error());
	  }
	else{
		echo "1 Task deleted";
	}
}
?>
<a href="index.php"><button class='submit' type='submit'name='back'>Back</button></a>
<br><br>

<form>
<label for="project">Project: </label><br>
<select name="project" onchange="showTasks(this.value)">
<option value="">Choose a Project: </option>
<?php
// Automatically fill the drop down menus with the users projects
	$result = mysql_query("SELECT * FROM INSE_tblProject WHERE userID=$user");
	while($row = mysql_fetch_array($result)){
		$projectName = $row['projectName'];
		$projectID = $row['projectID'];
		echo '<option value = "' . $projectID . '">' . $projectName . '</option>
		';
	}
?>
</select>
</form>

<div id="input"></div>
<p>&nbsp;</p>