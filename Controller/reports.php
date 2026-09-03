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

if (
    $action !== "resolve" &&
    $action !== "remove"
) {
    die("Invalid report action.");
}

require("../Model/db.php");

if ($action === "remove") {

    // Find the listing tied to this report (if any) and remove it, then close the report.
    $findSql = "SELECT listing_id FROM reports WHERE id = ?";
    $findStmt = mysqli_prepare($conn, $findSql);
    mysqli_stmt_bind_param($findStmt, "i", $reportId);
    mysqli_stmt_execute($findStmt);
    $findResult = mysqli_stmt_get_result($findStmt);
    $row = mysqli_fetch_assoc($findResult);
    mysqli_stmt_close($findStmt);

    if ($row && $row["listing_id"]) {
        $listingId = $row["listing_id"];
        $delSql = "UPDATE listings SET status = 'removed' WHERE id = ?";
        $delStmt = mysqli_prepare($conn, $delSql);
        mysqli_stmt_bind_param($delStmt, "i", $listingId);
        mysqli_stmt_execute($delStmt);
        mysqli_stmt_close($delStmt);
    }

    $newStatus = "removed";

} else {
    $newStatus = "resolved";
}

$sql = "UPDATE reports SET status = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $newStatus, $reportId);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
mysqli_close($conn);

header("Location: ../View/admin/reports.php");
exit();

?>
