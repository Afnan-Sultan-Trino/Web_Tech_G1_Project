<?php
if (!function_exists('connect')) {
    function connect() {
        $conn = mysqli_connect("localhost", "root", "", "campusnest");
        return $conn;
    }
}
?>