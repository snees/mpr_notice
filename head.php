<?php
include 'session.php';
include 'db.php';
$conn = mysqli_connect($host, $userid, $userpw, $databasename);
mysqli_query($conn, "set session character_set_connection=utf8;");
mysqli_query($conn, "set session character_set_results=utf8;");
mysqli_query($conn, "set session character_set_client=utf8;");
?>
