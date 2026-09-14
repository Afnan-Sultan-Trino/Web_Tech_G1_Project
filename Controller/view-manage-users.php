<?php

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

require("../Model/User.php");

$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";

$_SESSION["manageUsersData"] = [
    "search" => $search,
    "users" => getAllUsers($search),
];

header("Location: ../View/admin/manage-users.php");
exit();

?>
