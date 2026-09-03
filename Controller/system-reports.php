<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

if (isset($_POST["report_type"])) {
    $reportType = $_POST["report_type"];
} else {
    $reportType = "";
}

if (isset($_POST["from"])) {
    $from = $_POST["from"];
} else {
    $from = "";
}

if (isset($_POST["to"])) {
    $to = $_POST["to"];
} else {
    $to = "";
}

if (empty($reportType)) {
    die("Please select a report type.");
}

if (empty($from)) {
    die("Please select a starting date.");
}

if (empty($to)) {
    die("Please select an ending date.");
}

if (
    $reportType !== "Active Listings" &&
    $reportType !== "New Users" &&
    $reportType !== "Interest Requests" &&
    $reportType !== "Usage Statistics"
) {
    die("Invalid report type.");
}

if ($from > $to) {
    die("Starting date cannot be after ending date.");
}

require("../Model/db.php");

// The "to" date is inclusive, so push it to the end of that day.
$toEnd = $to . " 23:59:59";
$fromStart = $from . " 00:00:00";

echo "<h2>System Report Generated</h2>";
echo "<p>Report Type: " . htmlspecialchars($reportType) . "</p>";
echo "<p>From: " . htmlspecialchars($from) . " To: " . htmlspecialchars($to) . "</p>";

if ($reportType === "Active Listings") {

    $sql = "SELECT title, location, price, status, created_at FROM listings
            WHERE created_at BETWEEN ? AND ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $fromStart, $toEnd);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    echo "<table border='1' cellpadding='6'><tr><th>Title</th><th>Location</th><th>Price</th><th>Status</th><th>Posted</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . htmlspecialchars($row["title"]) . "</td><td>" . htmlspecialchars($row["location"]) . "</td><td>" . $row["price"] . "</td><td>" . htmlspecialchars($row["status"]) . "</td><td>" . $row["created_at"] . "</td></tr>";
    }
    echo "</table>";
    mysqli_stmt_close($stmt);

} else if ($reportType === "New Users") {

    $sql = "SELECT name, email, role, created_at FROM users
            WHERE created_at BETWEEN ? AND ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $fromStart, $toEnd);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    echo "<table border='1' cellpadding='6'><tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . htmlspecialchars($row["name"]) . "</td><td>" . htmlspecialchars($row["email"]) . "</td><td>" . htmlspecialchars($row["role"]) . "</td><td>" . $row["created_at"] . "</td></tr>";
    }
    echo "</table>";
    mysqli_stmt_close($stmt);

} else if ($reportType === "Interest Requests") {

    $sql = "SELECT listings.title, users.name AS seeker, interest_requests.status, interest_requests.created_at
            FROM interest_requests
            JOIN listings ON listings.id = interest_requests.listing_id
            JOIN users ON users.id = interest_requests.seeker_id
            WHERE interest_requests.created_at BETWEEN ? AND ?
            ORDER BY interest_requests.created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $fromStart, $toEnd);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    echo "<table border='1' cellpadding='6'><tr><th>Listing</th><th>Seeker</th><th>Status</th><th>Date</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . htmlspecialchars($row["title"]) . "</td><td>" . htmlspecialchars($row["seeker"]) . "</td><td>" . htmlspecialchars($row["status"]) . "</td><td>" . $row["created_at"] . "</td></tr>";
    }
    echo "</table>";
    mysqli_stmt_close($stmt);

} else {
    // Usage Statistics
    $usersStmt = mysqli_prepare($conn, "SELECT COUNT(*) AS c FROM users WHERE created_at BETWEEN ? AND ?");
    mysqli_stmt_bind_param($usersStmt, "ss", $fromStart, $toEnd);
    mysqli_stmt_execute($usersStmt);
    $usersCount = mysqli_fetch_assoc(mysqli_stmt_get_result($usersStmt))["c"];
    mysqli_stmt_close($usersStmt);

    $listingsStmt = mysqli_prepare($conn, "SELECT COUNT(*) AS c FROM listings WHERE created_at BETWEEN ? AND ?");
    mysqli_stmt_bind_param($listingsStmt, "ss", $fromStart, $toEnd);
    mysqli_stmt_execute($listingsStmt);
    $listingsCount = mysqli_fetch_assoc(mysqli_stmt_get_result($listingsStmt))["c"];
    mysqli_stmt_close($listingsStmt);

    $requestsStmt = mysqli_prepare($conn, "SELECT COUNT(*) AS c FROM interest_requests WHERE created_at BETWEEN ? AND ?");
    mysqli_stmt_bind_param($requestsStmt, "ss", $fromStart, $toEnd);
    mysqli_stmt_execute($requestsStmt);
    $requestsCount = mysqli_fetch_assoc(mysqli_stmt_get_result($requestsStmt))["c"];
    mysqli_stmt_close($requestsStmt);

    echo "<ul>";
    echo "<li>New users: " . $usersCount . "</li>";
    echo "<li>New listings: " . $listingsCount . "</li>";
    echo "<li>New interest requests: " . $requestsCount . "</li>";
    echo "</ul>";
}

mysqli_close($conn);

echo "<p><a href='../View/admin/system-reports.php'>Back to reports</a></p>";

?>
