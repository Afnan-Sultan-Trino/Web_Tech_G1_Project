<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

if (isset($_POST["listing_id"])) {
    $listingId = trim($_POST["listing_id"]);
} else {
    $listingId = "";
}

if (isset($_POST["action"])) {
    $action = $_POST["action"];
} else {
    $action = "";
}

if (empty($listingId) || !ctype_digit((string) $listingId)) {
    die("Listing ID is required.");
}

if (
    $action !== "approve" &&
    $action !== "reject"
) {
    die("Invalid listing action.");
}

require("../Model/db.php");

if ($action === "approve") {

    // Only a listing that is still pending can be approved.
    $sql = "UPDATE listings SET status = 'available' WHERE id = ? AND status = 'pending'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $listingId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

} else {

    // Reject = delete the pending listing entirely.
    $sql = "DELETE FROM listings WHERE id = ? AND status = 'pending'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $listingId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

}

mysqli_close($conn);

header("Location: ../View/admin/manage-listings.php");
exit();

?>
