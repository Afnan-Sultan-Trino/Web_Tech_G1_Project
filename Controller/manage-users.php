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

if (
    $action !== "activate" &&
    $action !== "suspend" &&
    $action !== "delete"
) {
    die("Invalid user action.");
}

require("../Model/db.php");

if ($action === "delete") {

    $sql = "DELETE FROM users WHERE id = ? AND role != 'admin'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

} else {

    $newStatus = $action === "activate" ? "active" : "suspended";

    $sql = "UPDATE users SET status = ? WHERE id = ? AND role != 'admin'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $newStatus, $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

}

mysqli_close($conn);

header("Location: ../View/admin/manage-users.php");
exit();

?>
