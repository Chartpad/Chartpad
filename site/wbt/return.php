<?php
    include("../includes/dbconnect.php");

    if (isset($_GET['pid'])) {
        $pid = filter_input(INPUT_GET, "pid", FILTER_SANITIZE_NUMBER_INT);

        //grab project information and settings
        $query = "SELECT * FROM INSE_tblProject WHERE projectID=" . $pid;
        $table = mysql_query($query);
        $project = mysql_fetch_assoc($table);

        //grab tasks information
        $query = "SELECT taskID, taskName, parentID, wbtX, wbtY FROM INSE_tblTask WHERE projectID=" . $pid;
        $table = mysql_query($query);
        $tasks = array();
        while($task = mysql_fetch_assoc($table)) {
        	$tasks[] = $task;
        }

        $pack = array();
        $pack["project"] = $project;
        $pack["tasks"] = $tasks;

        header("Content-Type: application/json");
        echo json_encode($pack);
    }
?>