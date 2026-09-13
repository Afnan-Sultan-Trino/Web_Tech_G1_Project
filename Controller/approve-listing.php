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

<<<<<<< HEAD

require("../Model/Listing.php");


if ($action === "approve") {

    approveListing($listingId);

} else {

    rejectListing($listingId);
}


=======
require("../Model/Listing.php");

if ($action === "approve") {
    approveListing($listingId);
} else {
    rejectListing($listingId);
}

>>>>>>> 2f6f798ea5c5d0c92aaf999052bc71a41a9c4993
header("Location: ../View/admin/manage-listings.php");
exit();

?>