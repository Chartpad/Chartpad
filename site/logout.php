<?php
session_start();
session_destroy();
if(isset($_SESSION['INSE_Email']))
unset($_SESSION['INSE_Email']);
header('Location: ../');
?>