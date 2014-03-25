<?php
include("includes/checklogin.php");
include("includes/dbconnect.php");
$project = $_GET['project'];
$taskID = $_GET['task'];
$user = $_SESSION['INSE_userID'];

// Select all the tasks specific to the project given
$sql = "SELECT * FROM INSE_tblTask INNER JOIN INSE_tblProject ON INSE_tblTask.projectID = INSE_tblProject.projectID WHERE INSE_tblProject.projectID='$project' AND taskID!='$taskID'";

$anyResults = "SELECT COUNT(*) FROM INSE_tblTask INNER JOIN INSE_tblProject ON INSE_tblTask.projectID = INSE_tblProject.projectID WHERE INSE_tblProject.projectID='$project' AND taskID!='$taskID'";

$anyTasks = mysql_query($anyResults);
$anyTasks = mysql_num_rows($anyTasks);
if($anyTasks > 0){

$result = mysql_query($sql);
$row = mysql_fetch_array($result);

echo '<label for="taskParentID">taskParentID: </label>';
echo '<select name = "taskParentID">';

$parentID = $row['parentID'];

echo "Laaaaaaaaaaa" . $parentID;

if($parentID > 0){
	echo '<option value="">' .$parentID .'</option>';
}
else{
	echo '<option value=""></option>';
}


while($row = mysql_fetch_array($result)){
	$taskName = $row['taskName'];
	$taskID = $row['taskID'];
	
	echo '<option value = "' . $taskID . '">' . $taskName . '</option>';
	}
	
echo '</select>';

echo '<br>';
echo '<br>';

echo '<label for="taskPredecessor">taskPredecessor: </label>';

$result = mysql_query($sql);
while($row = mysql_fetch_array($result)){
	$taskName = $row['taskName'];
	$taskID = $row['taskID'];
	
	echo '<input type="checkbox" name="taskPredecessor" value="' . $taskID . '">' . $taskName . '<br>';
	}
}
else{
	echo 'No Parent or Predecessor available to select';
}
?>