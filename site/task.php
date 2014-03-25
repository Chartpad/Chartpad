<?php
include("includes/checklogin.php");
include("includes/dbconnect.php");
$project = $_GET['project'];
$user = $_SESSION['INSE_userID'];


	$per_page = 1000; 
	$currentPage = 1;
	if (isset($_GET['page'])) 
		{
		  $currentPage = intval($_GET['page']); 
		  if($currentPage < 1) $currentPage = 1;
		}
	$start_from = ($currentPage - 1) * $per_page;
	

$sql = "SELECT * FROM INSE_tblTask INNER JOIN INSE_tblProject ON INSE_tblTask.projectID = INSE_tblProject.projectID WHERE INSE_tblProject.userID='" . $user . "' AND INSE_tblProject.projectID='" . $project . "' LIMIT $start_from, $per_page";


$sql2 = mysql_query("SELECT * FROM INSE_tblTask WHERE projectID='$project'");
$anyTasks = mysql_num_rows($sql2);
// Check if any Tasks in Project
if($anyTasks > 0){

echo '<table class="taskList">
<tr>
<th class="taskList">Task Name</th>
<th class="taskList">Project</th>
<th class="taskList" colspan="2">Make Changes</th>
</tr>';

$result = mysql_query($sql);
							
	while($row = mysql_fetch_array($result)){
		$taskName = $row['taskName'];
		$taskID = $row['taskID'];
		$projectName = $row['projectName'];
		
		echo '<tr><td class="taskList">' . $taskName . '</td>';
		echo '<td class="taskList">' . $projectName . '</td>';
		echo '<td class="taskList"><a href="index.php?pg=addTask&edit=' . $taskID . '"><button class="submit" type="submit" name="back">Edit</button></a></td>';
		echo '<td class="taskList"><a href="index.php?pg=viewTasks&delete=' . $taskID . '" onclick=\'return confirm("Really Delete?");\'><button id="delete" class="submit" type="submit" name="back">Delete</button></a></td></tr>';
	}
	echo '<td colspan="4" class="taskList"><a href="index.php?pg=addTask"><button id="greenbtn" class="submit" type="submit" name="back">Add More Tasks</button></a></td>';
	
echo '</table>';
}
else{
	echo '<section class="error">';
	echo '<p>No Tasks currently in this Project</p>';
	echo '<a href="index.php?pg=addTask"><button class="bluebtn">Add a Task</button></a>';
	echo '</section>';
}


	$total_rows = mysql_query("SELECT COUNT(*) FROM INSE_tblTask WHERE projectID='$project'");
	$total_rows = mysql_fetch_row($total_rows);
	$total_rows = $total_rows[0];

	$total_pages = $total_rows / $per_page;
	$total_pages = ceil($total_pages); 
	$next = $currentPage + 1;
	$prev = $currentPage - 1;
	
	if($total_rows > $per_page){
	echo "<section id='pagination'>";
	if ($currentPage !== 1){
		echo "<a href='index.php?pg=viewTasks&page=$prev'><section class='pagination'>Previous</section></a>";
	}
	for($i = 1; $i  <= $total_pages; ++$i){
	 echo "<a href='index.php?pg=viewTasks&page=$i'><section class='pagination'>$i</section></a>";
	}
	if ($currentPage < $total_pages){
		echo "<a href='index.php?pg=viewTasks&page=$next'><section class='pagination'>Next</section></a>";
	}
	echo "</section>";
	}
	
	echo '<p>&nbsp;</p>';
?>