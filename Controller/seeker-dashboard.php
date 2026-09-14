<?php

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "seeker") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

require("../View/seeker/seeker-dashboard.php");

exit();

?>
