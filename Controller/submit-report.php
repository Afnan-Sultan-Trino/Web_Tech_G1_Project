<?php

session_start();

require("../Model/Report.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}


// Login check

if (!isset($_SESSION["logged_in"])) {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}


// Get input

$listingId = isset($_POST["listing_id"])
    ? trim($_POST["listing_id"])
    : "";

$reportedUserId = isset($_POST["reported_user_id"])
    ? trim($_POST["reported_user_id"])
    : "";

$reason = isset($_POST["reason"])
    ? trim($_POST["reason"])
    : "";

$details = isset($_POST["details"])
    ? trim($_POST["details"])
    : "";


// Validation

if (empty($listingId) || !ctype_digit((string)$listingId)) {
    die("Listing ID is required.");
}

if (empty($reportedUserId) || !ctype_digit((string)$reportedUserId)) {
    die("Reported user is required.");
}

if (empty($reason)) {
    die("Please select a reason for this report.");
}


// Allowed reasons

$allowedReasons = [
    "Misleading listing",
    "Scam or fraud",
    "Inappropriate content",
    "Unresponsive or rude lister",
    "Other"
];

if (!in_array($reason, $allowedReasons, true)) {
    die("Invalid report reason.");
}


// Get reporter ID from session

$reporterId = $_SESSION["user_id"];


// User cannot report themselves

if ((int)$reportedUserId === (int)$reporterId) {
    die("You cannot report yourself.");
}


// Submit report using Model

if (submitReport(
    $reporterId,
    $reportedUserId,
    $listingId,
    $reason,
    $details
)) {

    echo "<h2>Report Submitted</h2>";

    echo "<p>Thanks — an admin will review this listing shortly.</p>";

    echo "<p>
            <a href='../View/seeker/seeker-dashboard.php'>
                Back to dashboard
            </a>
          </p>";

} else {

    die("Failed to submit report.");

}

?>