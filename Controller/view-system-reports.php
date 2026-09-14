<?php

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

require("../Model/Stats.php");

$_SESSION["systemReportsData"] = [
    "totalSeekers" => countUsersByRole("seeker"),
    "totalListers" => countUsersByRole("lister"),
    "activeListings" => countAvailableListings(),
];

header("Location: ../View/admin/system-reports.php");
exit();

?>
