<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "lister") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

if (isset($_POST["request_id"])) {
    $requestId = trim($_POST["request_id"]);
} else {
    $requestId = "";
}

if (isset($_POST["action"])) {
    $action = $_POST["action"];
} else {
    $action = "";
}

if (empty($requestId) || !ctype_digit((string) $requestId)) {
    die("Request ID is required.");
}

if (
    $action !== "approve" &&
    $action !== "decline"
) {
    die("Invalid request action.");
}

require("../Model/db.php");

$listerId = $_SESSION["user_id"];
$newStatus = $action === "approve" ? "approved" : "declined";

// Only allow a lister to respond to requests made on their own listings.
$sql = "UPDATE interest_requests
        JOIN listings ON listings.id = interest_requests.listing_id
        SET interest_requests.status = ?
        WHERE interest_requests.id = ? AND listings.lister_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sii", $newStatus, $requestId, $listerId);
mysqli_stmt_execute($stmt);
$affected = mysqli_stmt_affected_rows($stmt);
mysqli_stmt_close($stmt);
mysqli_close($conn);

if ($affected === 0) {
    die("You do not have permission to update this request.");
}

header("Location: ../View/lister/respond-request.php");
exit();

?>
