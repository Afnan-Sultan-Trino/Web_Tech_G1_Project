<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["logged_in"])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require(__DIR__ . "/../../Model/db.php");

$userId = $_SESSION['user_id'];

$sql = "SELECT id, name, email, phone, role FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode(['success' => true, 'user' => $row]);
} else {
    echo json_encode(['error' => 'User not found']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>