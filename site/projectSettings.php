<?php
include("includes/checklogin.php");
include("includes/dbconnect.php");
$user = $_SESSION['INSE_userID'];

// Functions
// Edit Project Settings
if(ISSET($_POST['projectID'])){
	// Project Data Input
	$projectID = $_POST['projectID'];
	$sql = mysql_query("SELECT * FROM INSE_tblProject WHERE projectID=$projectID");
	$row = mysql_fetch_array($sql);
	$projectName = $row['projectName'];
	// General Settings
	$canvasOffsetX = $_POST['canvasOffsetX'];
	$canvasOffsetY = $_POST['canvasOffsetY'];

	// Gantt Settings
	$ganttLineWidth = $_POST['ganttLineWidth'];
	$ganttPadding = $_POST['ganttPadding'];
	$ganttTableFontFamily = $_POST['ganttFontFamily'];
	$ganttTableFontSize = $_POST['ganttTableFontSize'];
	$ganttTapeFontSize = $_POST['ganttTapeFontSize'];
	$ganttTimeStep = $_POST['ganttTimeStep'];
	$ganttBackgroundColor = $_POST['ganttBackgroundColor'];

	// WBT Settings
	$wbtLineWidth = $_POST['wbtLineWidth'];
	$wbtPadding = $_POST['wbtPadding'];
	$wbtFontFamily = $_POST['wbtFontFamily'];
	$wbtFontSize = $_POST['wbtFontSize'];
	$wbtBackgroundColor = $_POST['wbtBackgroundColor'];
	$wbtBoxBackgroundColor = $_POST['wbtBoxBackgroundColor'];
	$wbtBorderColor = $_POST['wbtBorderColor'];
	$wbtBoxLineColor = $_POST['wbtBoxLineColor'];
	
	// PERT Settings
	$pertLineWidth = $_POST['pertLineWidth'];
	$pertPadding = $_POST['pertPadding'];
	$pertFontFamily = $_POST['pertFontFamily'];
	$pertFontSize = $_POST['pertFontSize'];
	$pertBackgroundColor = $_POST['pertBackgroundColor'];
	$pertBoxBackgroundColor = $_POST['pertBoxBackgroundColor'];
	$pertBorderColor = $_POST['pertBorderColor'];
	$pertBoxLineColor = $_POST['pertBoxLineColor'];
	
	$sql="UPDATE INSE_tblProject SET canvasOffsetX='$canvasOffsetX', canvasOffsetY='$canvasOffsetY', ganttLineWidth='$ganttLineWidth', ganttPadding='$ganttPadding', ganttFontFamily='$ganttTableFontFamily', ganttTableFontSize='$ganttTableFontSize', ganttTapeFontSize='$ganttTapeFontSize', ganttTimeStep='$ganttTimeStep', ganttBackgroundColor='$ganttBackgroundColor', wbtLineWidth='$wbtLineWidth', wbtPadding='$wbtPadding', wbtFontFamily='$wbtFontFamily', wbtFontSize='$wbtFontSize', wbtBackgroundColor='$wbtBackgroundColor', wbtBoxBackgroundColor='$wbtBoxBackgroundColor', wbtBorderColor='$wbtBorderColor', wbtBoxLineColor='$wbtBoxLineColor', pertLineWidth='$pertLineWidth', pertPadding='$pertPadding', pertFontFamily='$pertFontFamily', pertFontSize='$pertFontSize', pertBackgroundColor='$pertBackgroundColor', pertBoxBackgroundColor='$pertBoxBackgroundColor', pertBorderColor='$pertBorderColor', pertBoxLineColor='$pertBoxLineColor' WHERE projectID='$projectID'";

	if (!mysql_query($sql))
	  {
	  die('Error: ' . mysql_error());
	  }
	echo '"' . $projectName . '" project settings edited';
}
?>
<section>

<a href="<?php echo $_SERVER['HTTP_REFERER'] ?>"><button class='submit' type='submit'name='back'>Back</button></a>

<form class="form-inputs" method="POST">
<ul>
</li>
<?php
if(ISSET($_GET['edit'])){
// Edit Project Settings
$projectID = $_GET['edit'];

$sql = mysql_query("SELECT * FROM INSE_tblProject WHERE projectID='$projectID'");
$row = mysql_fetch_array($sql);

// General Settings
$canvasOffsetX = $row['canvasOffsetX'];
$canvasOffsetY = $row['canvasOffsetY'];

// Gantt Settings
$ganttLineWidth = $row['ganttLineWidth'];
$ganttPadding = $row['ganttPadding'];
$ganttTableFontFamily = $row['ganttFontFamily'];
$ganttTableFontSize = $row['ganttTableFontSize'];
$ganttTapeFontSize = $row['ganttTapeFontSize'];
$ganttTimeStep = $row['ganttTimeStep'];
$ganttBackgroundColor = $row['ganttBackgroundColor'];

// WBT Settings
$wbtLineWidth = $row['wbtLineWidth'];
$wbtPadding = $row['wbtPadding'];
$wbtFontFamily = $row['wbtFontFamily'];
$wbtFontSize = $row['wbtFontSize'];
$wbtBackgroundColor = $row['wbtBackgroundColor'];
$wbtBoxBackgroundColor = $row['wbtBoxBackgroundColor'];
$wbtBorderColor = $row['wbtBorderColor'];
$wbtBoxLineColor = $row['wbtBoxLineColor'];

// PERT Settings
$pertLineWidth = $row['pertLineWidth'];
$pertPadding = $row['pertPadding'];
$pertFontFamily = $row['pertFontFamily'];
$pertFontSize = $row['pertFontSize'];
$pertBackgroundColor = $row['pertBackgroundColor'];
$pertBoxBackgroundColor = $row['pertBoxBackgroundColor'];
$pertBorderColor = $row['pertBorderColor'];
$pertBoxLineColor = $row['pertBoxLineColor'];

// Display form filled in, ready to edit
echo '
<h3>Edit Project Settings</h3>
<li>		
	<h3>General Settings</h3><br/>
</li>
<li>
	<label for="canvasOffsetX">Canvas Offset X: </label>
		<input type="text" name="canvasOffsetX" value="' . $canvasOffsetX . '"/><br/>
</li>
<li>		
	<label for="canvasOffsetY">Canvas Offset Y: </label>
		<input type="text" name="canvasOffsetY" value="' . $canvasOffsetY . '"/> <br/>
</li>
<li>		
	<h3>Gantt Settings</h3><br/>
</li>
<li>		
	<label for="ganttLineWidth">Gantt Line Width: </label>
		<input type="text" name="ganttLineWidth" value="' . $ganttLineWidth . '"/> <br/>
</li>
<li>		
	<label for="ganttPadding">Gantt Padding: </label>
		<input type="text" name="ganttPadding" value="' . $ganttPadding . '"/> <br/>
</li>
<li>		
	<label for="ganttTableFontFamily">Gantt Table Font Family: </label>
		<input type="text" name="ganttTableFontFamily" value="' . $ganttTableFontFamily . '"/> <br/>
</li>
<li>		
	<label for="ganttTableFontSize">Gantt Table Font Size: </label>
		<input type="text" name="ganttTableFontSize" value="' . $ganttTableFontSize . '"/> <br/>
</li>
<li>		
	<label for="ganttTimeStep">Gantt Time Step: </label>
		<input type="text" name="ganttTimeStep" value="' . $ganttTimeStep . '"/> <br/>
</li>
<li>		
	<label for="ganttBackgroundColor">Gantt Background Color: </label>
		<input type="text" name="ganttBackgroundColor" value="' . $ganttBackgroundColor . '"/> <br/>
</li>
<li>		
	<h3>WBT Settings</h3><br/>
</li>
<li>		
	<label for="wbtLineWidth">WBT Line Width: </label>
		<input type="text" name="wbtLineWidth" value="' . $wbtLineWidth . '"/> <br/>
</li>
<li>		
	<label for="wbtPadding">WBT Padding: </label>
		<input type="text" name="wbtPadding" value="' . $wbtPadding . '"/> <br/>
</li>
<li>		
	<label for="wbtFontFamily">WBT Font Family: </label>
		<input type="text" name="wbtFontFamily" value="' . $wbtFontFamily . '"/> <br/>
</li>
<li>		
	<label for="wbtFontSize">WBT Font Size: </label>
		<input type="text" name="wbtFontSize" value="' . $wbtFontSize . '"/> <br/>
</li>
<li>		
	<label for="wbtBackgroundColor">WBT Background Color: </label>
		<input type="text" name="wbtBackgroundColor" value="' . $wbtBackgroundColor . '"/> <br/>
</li>
<li>		
	<label for="wbtBorderColor">WBT Border Color: </label>
		<input type="text" name="wbtBorderColor" value="' . $wbtBorderColor . '"/> <br/>
</li>
<li>		
	<label for="wbtBoxLineColor">WBT Box Line Color: </label>
		<input type="text" name="wbtBoxLineColor" value="' . $wbtBoxLineColor . '"/> <br/>
</li>
<li>		
	<h3>PERT Settings</h3><br/>
</li>
<li>		
	<label for="pertLineWidth">PERT Line Width: </label>
		<input type="text" name="pertLineWidth" value="' . $pertLineWidth . '"/> <br/>
</li>
<li>		
	<label for="pertPadding">PERT Padding: </label>
		<input type="text" name="pertPadding" value="' . $pertPadding . '"/> <br/>
</li>
<li>		
	<label for="pertFontFamily">PERT Font Family: </label>
		<input type="text" name="pertFontFamily" value="' . $pertFontFamily . '"/> <br/>
</li>
<li>		
	<label for="pertFontSize">PERT Font Size: </label>
		<input type="text" name="pertFontSize" value="' . $pertFontSize . '"/> <br/>
</li>
<li>		
	<label for="pertBackgroundColor">PERT Background Color: </label>
		<input type="text" name="pertBackgroundColor" value="' . $pertBackgroundColor . '"/> <br/>
</li>
<li>		
	<label for="pertBorderColor">PERT Border Color: </label>
		<input type="text" name="pertBorderColor" value="' . $pertBorderColor . '"/> <br/>
</li>
<li>		
	<label for="pertBoxLineColor">PERT Box Line Color: </label>
		<input type="text" name="pertBoxLineColor" value="' . $pertBoxLineColor . '"/> <br/>
</li>
<li>
	<input type="hidden" name="projectID" value="' . $projectID . '"/>
	<button class="submit" type="submit" value="Submit">Change</button>
</li>';
}
?>
</ul>
	
</form>

</section>