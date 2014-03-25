<?php
include("includes/checklogin.php");
include("includes/dbconnect.php");
$user = $_SESSION['INSE_userID'];

// Functions
// Delete Project
if(ISSET($_GET['deleteProject'])){
	$deleteID = $_GET['deleteProject'];
	$deleteName = $_GET['name'];
	
	$sql = "DELETE FROM INSE_tblProject WHERE projectID='$deleteID'";
	
	if (!mysql_query($sql))
	  {
	  die('Error: ' . mysql_error());
	  }
	else{
		echo $deleteName . " Project and all of its Tasks deleted<br />";
	}
}
?>
<a href="index.php"><button class='submit' type='submit'name='back'>Back</button></a>


<table class="taskList">
<th class="taskList">Project Name</th>
<th class="taskList">No. Tasks</th>
<th class="taskList" colspan="3">View Charts</th>
<th class="taskList" colspan="4">Make Changes</th>
<?php

	$per_page = 10; 
	$currentPage = 1;
	if (isset($_GET['page'])) 
		{
		  $currentPage = intval($_GET['page']); 
		  if($currentPage < 1) $currentPage = 1;
		}
	$start_from = ($currentPage - 1) * $per_page;
	
$result2 = mysql_query("SELECT * 
							FROM INSE_tblProject
							WHERE userID=$user
							ORDER BY (SELECT COUNT(taskID) FROM INSE_tblTask WHERE INSE_tblProject.projectID = INSE_tblTask.projectID) DESC
							 LIMIT $start_from, $per_page");
							
	while($row = mysql_fetch_array($result2)){
		$projectName = $row['projectName'];
		$projectID = $row['projectID'];
		$sql = mysql_query("SELECT * FROM INSE_tblTask WHERE projectID='$projectID'");
		$tasks = mysql_num_rows($sql);
		
		echo '<tr><td class="taskList">' . $projectName . '</td>';
		echo '<td class="taskList">' . $tasks . '</td>';
		if($tasks > 0){
			echo '<td class="taskList"><a href="gantt?pid=' . $projectID . '" target="_blank"><button id="charts" class="submit" type="submit" name="back">Gantt</button></a></td>';
			echo '<td class="taskList"><a href="wbt?pid=' . $projectID . '" target="_blank"><button id="charts" class="submit" type="submit" name="back">WBT</button></a></td>';
			echo '<td class="taskList"><a href="pert?pid=' . $projectID . '"><button id="charts" class="submit" type="submit" name="back">PERT</button></a></td>';
		}
		else{
			echo '<td class="taskList"></td>';
			echo '<td class="taskList"><a href="index.php?pg=addTask"><button id="charts" class="submit" type="submit" name="back">Add Tasks</button></a></td>';
			echo '<td class="taskList"></td>';
		}
		echo '<td class="taskList"><a href="index.php?pg=addTask"><button id="greenbtn" class="submit" type="submit" name="back">Add</button></a></td>';
		echo '<td class="taskList"><a href="index.php?pg=addProject&edit=' . $projectID . '"><button class="submit" type="submit" name="back">Edit</button></a></td>';
		echo '<td class="taskList"><a href="index.php?pg=projectSettings&edit=' . $projectID . '"><button class="submit" type="submit" name="back">Settings</button></a></td>';
		echo '<td class="taskList"><a href="index.php?pg=viewProjects&deleteProject=' . $projectID . '&name=' . $projectName . '" onclick=\'return confirm("Really Delete this Project and ALL of its Tasks?");\'><button id="delete" class="submit" type="submit" name="back">Delete</button></a></td></tr>';
	}
	echo '</table><br/>';
	
	$total_rows = mysql_query("SELECT COUNT(*) FROM INSE_tblProject WHERE userID=$user");
	$total_rows = mysql_fetch_row($total_rows);
	$total_rows = $total_rows[0];

	$total_pages = $total_rows / $per_page;
	$total_pages = ceil($total_pages); 
	$next = $currentPage + 1;
	$prev = $currentPage - 1;
	
	if($total_rows > $per_page){
	echo "<section id='pagination'>";
	if ($currentPage !== 1){
		echo "<a href='index.php?pg=viewProjects&page=$prev'><section class='pagination'>Previous</section></a>";
	}
	for($i = 1; $i  <= $total_pages; ++$i){
	 echo "<a href='index.php?pg=viewProjects&page=$i'><section class='pagination'>$i</section></a>";
	}
	if ($currentPage < $total_pages){
		echo "<a href='index.php?pg=viewProjects&page=$next'><section class='pagination'>Next</section></a>";
	}
	echo "</section>";
	}
	
	echo '<p>&nbsp;</p>';
?>