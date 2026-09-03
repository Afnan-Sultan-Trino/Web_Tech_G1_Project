<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['error' => 'Invalid request method']);
    exit();
}

if (!isset($_SESSION["logged_in"])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$userId = $_SESSION['user_id'];
$currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';
$newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';
$confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
    echo json_encode(['error' => 'All fields are required']);
    exit();
}
if (strlen($newPassword) < 6) {
    echo json_encode(['error' => 'New password must be at least 6 characters']);
    exit();
}
if ($newPassword !== $confirmPassword) {
    echo json_encode(['error' => 'Passwords do not match']);
    exit();
}

require(__DIR__ . "/../../Model/db.php");

// Fetch current password
$sql = "SELECT password FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Verify current (plain text or hashed)
$valid = false;
if ($currentPassword === $user['password']) {
    $valid = true;
} elseif (password_verify($currentPassword, $user['password'])) {
    $valid = true;
}

if (!$valid) {
    mysqli_close($conn);
    echo json_encode(['error' => 'Current password is incorrect']);
    exit();
}

// Update (plain text for your setup)
$updateSql = "UPDATE users SET password = ? WHERE id = ?";
$updateStmt = mysqli_prepare($conn, $updateSql);
mysqli_stmt_bind_param($updateStmt, "si", $newPassword, $userId);

if (mysqli_stmt_execute($updateStmt)) {
    mysqli_stmt_close($updateStmt);
    mysqli_close($conn);
    echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
} else {
    mysqli_stmt_close($updateStmt);
    mysqli_close($conn);
    echo json_encode(['error' => 'Failed to update password']);
}
?>