<?php

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "lister") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

require("../View/lister/lister-dashboard.php");

exit();

?>
