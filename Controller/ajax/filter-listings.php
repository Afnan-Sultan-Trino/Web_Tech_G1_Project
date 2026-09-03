<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "seeker") {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Get filter parameters
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$roomDetails = isset($_GET['room_details']) ? explode(',', $_GET['room_details']) : [];
$priceRange = isset($_GET['price']) ? trim($_GET['price']) : '';

require(__DIR__ . "/../../Model/db.php");

// Base query
$sql = "SELECT id, title, location, room_type, description, price, contact, image, status, created_at 
        FROM listings 
        WHERE status = 'available'";

$params = [];
$types = "";

// Add location filter
if (!empty($location)) {
    $sql .= " AND location = ?";
    $types .= "s";
    $params[] = $location;
}

// Add room details filter (matches room_type column)
if (!empty($roomDetails)) {
    $placeholders = [];
    foreach ($roomDetails as $detail) {
        $placeholders[] = "?";
        $types .= "s";
        $params[] = $detail;
    }
    $sql .= " AND room_type IN (" . implode(",", $placeholders) . ")";
}

// Add price range filter
if (!empty($priceRange)) {
    $rangeParts = explode("-", $priceRange);
    if (count($rangeParts) === 2) {
        $minPrice = (int)$rangeParts[0];
        $maxPrice = (int)$rangeParts[1];
        $sql .= " AND price BETWEEN ? AND ?";
        $types .= "ii";
        $params[] = $minPrice;
        $params[] = $maxPrice;
    }
}

$sql .= " ORDER BY created_at DESC LIMIT 20";

// Prepare and execute
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    echo json_encode(['error' => 'Database error']);
    mysqli_close($conn);
    exit();
}

// Bind parameters if any
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

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