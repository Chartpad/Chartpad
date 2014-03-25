<?php
$db_host = "";
$db_user = "";
$db_pwd = "";
$db_dbname = "";

mysql_connect($db_host, $db_user, $db_pwd) or die("Could not connect: " . mysql_error());
$seldb = mysql_select_db($db_dbname) or die("Could not connect: " . mysql_error()) ;

?>
