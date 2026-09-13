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

require("../Model/Listing.php");

if ($action === "approve") {
    approveListing($listingId);
} else {
    rejectListing($listingId);
}

header("Location: ../View/admin/manage-listings.php");
exit();

?>
