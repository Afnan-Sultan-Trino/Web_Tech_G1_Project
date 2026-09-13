<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

if (isset($_POST["user_id"])) {
    $userId = trim($_POST["user_id"]);
} else {
    $userId = "";
}

if (isset($_POST["action"])) {
    $action = $_POST["action"];
} else {
    $action = "";
}

if (empty($userId) || !ctype_digit((string) $userId)) {
    die("User ID is required.");
}

if ($action !== "activate" &&
    $action !== "suspend" &&
    $action !== "delete") {
    die("Invalid user action.");
}

require("../Model/User.php");

if ($action === "delete") {
    deleteUser($userId);
} else {
    $newStatus = $action === "activate" ? "active" : "suspended";
    updateUserStatus($userId, $newStatus);
}

header("Location: ../View/admin/manage-users.php");
exit();

?>
