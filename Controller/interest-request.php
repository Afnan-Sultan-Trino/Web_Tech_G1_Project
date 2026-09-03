<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "seeker") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

if (isset($_POST["listing_id"])) {
    $listingId = trim($_POST["listing_id"]);
} else {
    $listingId = "";
}

if (isset($_POST["message"])) {
    $message = trim($_POST["message"]);
} else {
    $message = "";
}

if (isset($_POST["phone"])) {
    $phone = trim($_POST["phone"]);
} else {
    $phone = "";
}

if (empty($listingId) || !ctype_digit((string) $listingId)) {
    die("A valid listing is required.");
}

if (empty($message)) {
    die("Message is required.");
}

if (empty($phone)) {
    die("Phone number is required.");
}

if (strlen($message) < 10) {
    die("Message must contain at least 10 characters.");
}

if (!preg_match("/^[0-9]{11}$/", $phone)) {
    die("Phone number must contain exactly 11 digits.");
}

require("../Model/db.php");

$seekerId = $_SESSION["user_id"];

$checkSql = "SELECT id FROM listings WHERE id = ?";
$checkStmt = mysqli_prepare($conn, $checkSql);
mysqli_stmt_bind_param($checkStmt, "i", $listingId);
mysqli_stmt_execute($checkStmt);
$checkResult = mysqli_stmt_get_result($checkStmt);

if (mysqli_num_rows($checkResult) === 0) {
    mysqli_stmt_close($checkStmt);
    mysqli_close($conn);
    die("That listing no longer exists.");
}

mysqli_stmt_close($checkStmt);

$sql = "INSERT INTO interest_requests (listing_id, seeker_id, message, phone, status)
        VALUES (?, ?, ?, ?, 'pending')";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iiss", $listingId, $seekerId, $message, $phone);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    echo "<h2>Interest Request Submitted</h2>";
    echo "<p>Your request has been sent to the lister.</p>";
    echo "<p><a href='../View/seeker/seeker-dashboard.php'>Back to dashboard</a></p>";

} else {
    $error = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    die("Failed to submit request: " . $error);
}

?>
