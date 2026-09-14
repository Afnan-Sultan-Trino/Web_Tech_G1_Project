<?php

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

require("../View/admin/admin-dashboard.php");

exit();

?>
