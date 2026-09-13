<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

if (isset($_POST["report_id"])) {
    $reportId = trim($_POST["report_id"]);
} else {
    $reportId = "";
}

if (isset($_POST["action"])) {
    $action = $_POST["action"];
} else {
    $action = "";
}

if (empty($reportId) || !ctype_digit((string) $reportId)) {
    die("Report ID is required.");
}

if ($action !== "resolve" &&
    $action !== "remove") {
    die("Invalid report action.");
}

require("../Model/Report.php");
require("../Model/Listing.php");

if ($action === "remove") {

    $listingId = findReportListingId($reportId);

    if ($listingId) {
        markListingRemoved($listingId);
    }

    $newStatus = "removed";

} else {
    $newStatus = "resolved";
}

updateReportStatus($reportId, $newStatus);

header("Location: ../View/admin/reports.php");
exit();

?>
