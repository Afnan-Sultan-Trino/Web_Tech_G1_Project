<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

if (!isset($_SESSION["logged_in"])) {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

if (isset($_POST["listing_id"])) {
    $listingId = trim($_POST["listing_id"]);
} else {
    $listingId = "";
}

if (isset($_POST["reported_user_id"])) {
    $reportedUserId = trim($_POST["reported_user_id"]);
} else {
    $reportedUserId = "";
}

if (isset($_POST["reason"])) {
    $reason = trim($_POST["reason"]);
} else {
    $reason = "";
}

if (isset($_POST["details"])) {
    $details = trim($_POST["details"]);
} else {
    $details = "";
}

if (empty($listingId) || !ctype_digit((string) $listingId)) {
    die("Listing ID is required.");
}

if (empty($reportedUserId) || !ctype_digit((string) $reportedUserId)) {
    die("Reported user is required.");
}

if (empty($reason)) {
    die("Please select a reason for this report.");
}

$allowedReasons = [
    "Misleading listing",
    "Scam or fraud",
    "Inappropriate content",
    "Unresponsive or rude lister",
    "Other",
];

if (!in_array($reason, $allowedReasons, true)) {
    die("Invalid report reason.");
}

require("../Model/Report.php");

$reporterId = $_SESSION["user_id"];

if ((int) $reportedUserId === (int) $reporterId) {
    die("You cannot report yourself.");
}

if (insertReport($reporterId, $reportedUserId, $listingId, $reason, $details)) {

    echo "<h2>Report Submitted</h2>";
    echo "<p>Thanks — an admin will review this listing shortly.</p>";
    echo "<p><a href='../View/seeker/seeker-dashboard.php'>Back to dashboard</a></p>";

} else {
    die("Failed to submit report.");
}

?>
