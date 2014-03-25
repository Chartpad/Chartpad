<?php
    include("../includes/checklogin-chart.php");
    include("../includes/dbconnect.php");
    if (isset($_GET['pid'])) {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PERT</title>
        <link href="../includes/chart.css" rel="stylesheet">
        <script src="../scripts/modernizr.js"></script>
        <script src="../scripts/jquery.min.js"></script>
        <script src="script.js"></script>
    </head>
    <body>
        <div class="header">
            <img src="../images/logo_sm.png" alt="Chartpad">
            <span id="settingsToggle"></span>
            <span id="saveData">Save</span>
            <span id="savePNG">PNG</span>
            <span id="saveJPEG">JPG</span>
            <span id="saveGIF">GIF</span>
        </div>
        <span id="<?php echo $_GET['pid'] ?>"></span>
        <div class="popup"></div>
        <div class="settingsBox">
            <button>Options Later</button>
        </div>
        <canvas id="pert"></canvas>
    </body>
</html>
<?php
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
?>