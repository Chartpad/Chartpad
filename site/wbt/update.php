<?php
	error_reporting(-1);
	include("../includes/dbconnect.php");

	if (isset($_POST['b']) && isset($_POST['s'])) {
		$settingsQuery = "UPDATE INSE_tblProject SET ";
		$settingsQuery .= "wbtWidth=" . $_POST['s']['canvasWidth'] . ", ";
		$settingsQuery .= "wbtHeight=" . $_POST['s']['canvasHeight'] . ", ";
		$settingsQuery .= "wbtLineWidth=" . $_POST['s']['lineWidth'] . ", ";
		$settingsQuery .= "wbtPadding=" . $_POST['s']['padding'] . ", ";
		$settingsQuery .= "wbtFontSize=" . $_POST['s']['fontSize'] . ", ";
		$settingsQuery .= "wbtFontFamily='".$_POST['s']['fontFamily'] . "', ";
		$settingsQuery .= "wbtBackgroundColor='" . $_POST['s']['backgroundColor'] . "', ";
		$settingsQuery .= "wbtBorderColor='" . $_POST['s']['boxBorderColor'] . "', ";
		$settingsQuery .= "wbtBoxBackgroundColor='" . $_POST['s']['boxBackgroundColor'] . "', ";
		$settingsQuery .= "wbtBoxLineColor='" . $_POST['s']['boxLineColor'] . "' ";
		$settingsQuery .= "WHERE projectID=" . $_POST['s']['projectID'];
		//echo $settingsQuery;
		mysql_query($settingsQuery);

		for ($i=0; $i < count($_POST['b']); $i++) {
			$boxesQuery = "UPDATE INSE_tblTask SET ";
			$boxesQuery .= "wbtX=" . $_POST['b'][$i]['x'] . ", ";
			$boxesQuery .= "wbtY=" . $_POST['b'][$i]['y'] . " ";
			$boxesQuery .= "WHERE taskID=" . $_POST['b'][$i]['id'];
			//echo $boxesQuery;
			mysql_query($boxesQuery) or die(mysql_error());
		}
	}
?>