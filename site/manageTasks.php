<?php
include("includes/checklogin.php");
include("includes/dbconnect.php");
$user = $_SESSION['INSE_userID'];

if(ISSET($_GET['delete'])){
	$taskID = $_GET['delete'];
	
	mysql_query("DELETE FROM INSE_tblTask WHERE taskID='$taskID'");
}
?>
<a href="index.php"><button class='submit' type='submit'name='back'>Back</button></a>

<table class="taskList">
	<?php
	
	$per_page = 10; 
	$currentPage = 1;
	if (isset($_GET['page'])) 
		{
		  $currentPage = intval($_GET['page']); 
		  if($currentPage < 1) $currentPage = 1;
		}
	$start_from = ($currentPage - 1) * $per_page;
	
	echo '<th class="taskList">Task Name</th>';
	echo '<th class="taskList">Project</th>';
	echo '<th class="taskList" colspan="2">Action</th>';
	
	$result = mysql_query("SELECT * 
							FROM INSE_tblTask
							INNER JOIN INSE_tblProject
							ON INSE_tblTask.projectID = INSE_tblProject.projectID
							ORDER BY INSE_tblTask.projectID
							LIMIT $start_from, $per_page");
	while($row = mysql_fetch_array($result)){
		$taskID = $row['taskID'];
		$taskName = $row['taskName'];
		$project = $row['projectName'];
		
		echo '<tr><td class="taskList">' . $taskName . '</td>';
		echo '<td class="taskList">' . $project . '</td>';
		echo '<td class="taskList"><a href="index.php?pg=addTask&edit=' . $taskID . '"><button class="submit" type="submit" name="back">Edit</button></a></td>';
		echo '<td class="taskList"><a href="index.php?pg=manageTasks&delete=' . $taskID . '"><button id="delete" class="submit" type="submit" name="back">Delete</button></a></td></tr>';
	}
	
	
	echo '</table><br/>';
	
	$total_rows = mysql_query("SELECT COUNT(*) FROM INSE_tblTask");
	$total_rows = mysql_fetch_row($total_rows);
	$total_rows = $total_rows[0];

	$total_pages = $total_rows / $per_page;
	$total_pages = ceil($total_pages); 
	$next = $currentPage + 1;
	$prev = $currentPage - 1;
	
	if($total_rows > $per_page){
	echo "<section id='pagination'>";
	if ($currentPage !== 1){
		echo "<a href='index.php?pg=manageTasks&page=$prev'><section class='pagination'>Previous</section></a>";
	}
	for($i = 1; $i  <= $total_pages; ++$i){
	 echo "<a href='index.php?pg=manageTasks&page=$i'><section class='pagination'>$i</section></a>";
	}
	if ($currentPage < $total_pages){
		echo "<a href='index.php?pg=manageTasks&page=$next'><section class='pagination'>Next</section></a>";
	}
	echo "</section>";
	}
	
	echo '<p>&nbsp;</p>';
	
	?>