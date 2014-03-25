<?php
    include("../includes/checklogin-chart.php");
    include("../includes/dbconnect.php");
    if (isset($_GET['pid'])) {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>WBT</title>
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
            <span>Canvas Size</span><br />
            <form id="settingsDimensionForm">
                <input id="settingsDimensionWidth" type="text" /> x 
                <input id="settingsDimensionHeight" type="text" />
            </form>
            <span>Background Colour</span><br />
            <form id="settingsBackgroundForm">
                <input id="settingsBackgroundRed" type="range" min="0" max="255"><br />
                <input id="settingsBackgroundGreen" type="range" min="0" max="255"><br />
                <input id="settingsBackgroundBlue" type="range" min="0" max="255">
            </form>
            <span>Box Border Colour</span><br />
            <form id="settingsBoxBorderForm">
                <input id="settingsBoxBorderRed" type="range" min="0" max="255"><br />
                <input id="settingsBoxBorderGreen" type="range" min="0" max="255"><br />
                <input id="settingsBoxBorderBlue" type="range" min="0" max="255">
            </form>
            <span>Box Background Colour</span><br />
            <form id="settingsBoxBackgroundForm">
                <input id="settingsBoxBackgroundRed" type="range" min="0" max="255"><br />
                <input id="settingsBoxBackgroundGreen" type="range" min="0" max="255"><br />
                <input id="settingsBoxBackgroundBlue" type="range" min="0" max="255">
            </form>
            <span>Link Colour</span>
            <form id="settingsBoxLinkForm">
                <input id="settingsBoxLinkRed" type="range" min="0" max="255"><br />
                <input id="settingsBoxLinkGreen" type="range" min="0" max="255"><br />
                <input id="settingsBoxLinkBlue" type="range" min="0" max="255">
            </form>
            <span>Font Colour</span>
            <form id="settingsFontColourForm">
                <input id="settingsFontColourRed" type="range" min="0" max="255"><br />
                <input id="settingsFontColourGreen" type="range" min="0" max="255"><br />
                <input id="settingsFontColourBlue" type="range" min="0" max="255">
            </form>
            <span>Line Width</span>
            <form id="settingsLineWidthForm">
                <input id="settingsLineWidth" type="number" min="0" max="10">
            </form>
            <span>Padding</span>
            <form id="settingsPaddingForm">
                <input id="settingsPadding" type="number" min="0" max="50">
            </form>
            <span>Font</span>            
            <form id="settingsFontSizeForm">
                <input id="settingsFontSize" type="number" min="6" max="32">
            </form>
        </div>
        <canvas unselectable="on" id="wbt"></canvas>
    </body>
</html>
<?php
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
?>