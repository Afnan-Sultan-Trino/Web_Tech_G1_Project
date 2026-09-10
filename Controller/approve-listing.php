<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

$listingId = isset($_POST["listing_id"]) ? trim($_POST["listing_id"]) : "";
$action = isset($_POST["action"]) ? $_POST["action"] : "";

if (empty($listingId) || !ctype_digit((string) $listingId)) {
    die("Listing ID is required.");
}

if ($action !== "approve" && $action !== "reject") {
    die("Invalid listing action.");
}

require("../Model/db.php");
require("../Model/AdminModel.php");

if ($action === "approve") {
    approveListing($conn, $listingId);
} else {
    rejectListing($conn, $listingId);
}

mysqli_close($conn);

header("Location: ../View/admin/manage-listings.php");
exit();

?>