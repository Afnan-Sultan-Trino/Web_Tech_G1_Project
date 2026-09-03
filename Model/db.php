<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = "localhost";
$username   = "root";
$db_password = "";   
$dbname     = "campusnest";

try {
    $conn = mysqli_connect($servername, $username, $db_password, $dbname);
    mysqli_set_charset($conn, "utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Connection failed: " . $e->getMessage() .
        "<br>Did you import schema.sql into a database called 'campusnest'?");
}

?>