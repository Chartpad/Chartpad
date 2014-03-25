<?php
    include("../includes/checklogin-chart.php");
    include("../includes/dbconnect.php");
    if (isset($_GET['pid'])) {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Gantt</title>
        <style>
            body {
                margin: 0px;
                background-color: #888;
            }

            * {
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
            }

            .header {
                position: fixed;
                height: 50px;
                top: 0pc;
                width: 100%;
                background-color: #444;
            }

            .header img {
                height: inherit;
            }

            .header span, .header a {
                color: #777;
                float: right;
                line-height: 50px;
                display: block;
                font-weight: bold;
                padding: 0 10px;
                margin-right: 25px;
                text-decoration: none;
            }

            .header span:hover, .header a:hover {
                background-color: #777;
                cursor: pointer;
                color: #444;
            }

            canvas {
                margin-top: 51px;
            }
        </style>
        <script src="../scripts/jquery.min.js"></script>
        <script src="script.js"></script>
    </head>
    <body>
        <div class="header">
            <img src="../images/logo_sm.png" alt="Chartpad">
            <span id="savePNG">PNG</span>
            <span id="saveJPEG">JPG</span>
            <span id="saveGIF">GIF</span>
        </div>
        <span id="<?php echo $_GET['pid'] ?>"></span>
        <canvas id="gantt"></canvas>
    </body>
</html>
<?php
    } else {
        header("Location: ../viewProjects.php");
    }
?>