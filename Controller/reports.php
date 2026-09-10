<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

$reportId = isset($_POST["report_id"]) ? trim($_POST["report_id"]) : "";
$action = isset($_POST["action"]) ? $_POST["action"] : "";

if (empty($reportId) || !ctype_digit((string) $reportId)) {
    die("Report ID is required.");
}

if ($action !== "resolve" && $action !== "remove") {
    die("Invalid report action.");
}

require("../Model/db.php");
require("../Model/AdminModel.php");

if ($action === "remove") {

    $listingId = getListingIdFromReport($conn, $reportId);

    if ($listingId) {
        markListingRemoved($conn, $listingId);
    }

    updateReportStatus($conn, $reportId, "removed");

} else {
    updateReportStatus($conn, $reportId, "resolved");
}

mysqli_close($conn);

header("Location: ../View/admin/reports.php");
exit();

?>