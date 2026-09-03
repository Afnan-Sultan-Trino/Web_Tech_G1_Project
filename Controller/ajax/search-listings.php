<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "seeker") {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($query) < 2) {
    echo json_encode(['results' => []]);
    exit();
}

require(__DIR__ . "/../../Model/db.php");

// Adjust table/column names to match your database
$sql = "SELECT id, title, location, description FROM listings 
        WHERE title LIKE ? OR location LIKE ? 
        LIMIT 10";

$stmt = mysqli_prepare($conn, $sql);
$searchTerm = "%" . $query . "%";
mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$listings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $listings[] = $row;
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

echo json_encode(['results' => $listings]);
?>