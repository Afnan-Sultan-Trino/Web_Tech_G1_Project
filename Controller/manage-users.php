<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

$userId = isset($_POST["user_id"]) ? trim($_POST["user_id"]) : "";
$action = isset($_POST["action"]) ? $_POST["action"] : "";

if (empty($userId) || !ctype_digit((string) $userId)) {
    die("User ID is required.");
}

if ($action !== "activate" && $action !== "suspend" && $action !== "delete") {
    die("Invalid user action.");
}

require("../Model/db.php");
require("../Model/AdminModel.php");

if ($action === "delete") {
    deleteUser($conn, $userId);
} else {
    $newStatus = $action === "activate" ? "active" : "suspended";
    updateUserStatus($conn, $userId, $newStatus);
}

mysqli_close($conn);

header("Location: ../View/admin/manage-users.php");
exit();

?>