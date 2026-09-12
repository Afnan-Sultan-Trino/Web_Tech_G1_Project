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


/* Validation */

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


require("../Model/InterestRequest.php");

$seekerId = $_SESSION["user_id"];


/* Check listing */

if (!listingExists($listingId)) {
    die("That listing no longer exists.");
}


/* Submit request */

if (submitInterestRequest($listingId, $seekerId, $message, $phone)) {

    echo "<h2>Interest Request Submitted</h2>";
    echo "<p>Your request has been sent to the lister.</p>";
    echo "<p><a href='../View/seeker/seeker-dashboard.php'>Back to dashboard</a></p>";

} else {

    die("Failed to submit request.");
}

?>