
<?php

session_start();

require("../Model/Report.php");


// Request check

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}


// Admin check

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}


// Get input

$reportType = isset($_POST["report_type"]) ? $_POST["report_type"] : "";
$from = isset($_POST["from"]) ? $_POST["from"] : "";
$to = isset($_POST["to"]) ? $_POST["to"] : "";


// Validation

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


// Date format

$fromStart = $from . " 00:00:00";
$toEnd = $to . " 23:59:59";


echo "<h2>System Report Generated</h2>";

echo "<p>Report Type: " .
     htmlspecialchars($reportType) .
     "</p>";

echo "<p>From: " .
     htmlspecialchars($from) .
     " To: " .
     htmlspecialchars($to) .
     "</p>";


// Active Listings

if ($reportType === "Active Listings") {

    $result = getActiveListings($fromStart, $toEnd);

    echo "<table border='1' cellpadding='6'>";

    echo "<tr>
            <th>Title</th>
            <th>Location</th>
            <th>Price</th>
            <th>Status</th>
            <th>Posted</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {

        echo "<tr>";

        echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["location"]) . "</td>";
        echo "<td>" . $row["price"] . "</td>";
        echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
        echo "<td>" . $row["created_at"] . "</td>";

        echo "</tr>";
    }

    echo "</table>";
}


// New Users

else if ($reportType === "New Users") {

    $result = getNewUsers($fromStart, $toEnd);

    echo "<table border='1' cellpadding='6'>";

    echo "<tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Joined</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {

        echo "<tr>";

        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
        echo "<td>" . $row["created_at"] . "</td>";

        echo "</tr>";
    }

    echo "</table>";
}


// Interest Requests

else if ($reportType === "Interest Requests") {

    $result = getInterestRequests($fromStart, $toEnd);

    echo "<table border='1' cellpadding='6'>";

    echo "<tr>
            <th>Listing</th>
            <th>Seeker</th>
            <th>Status</th>
            <th>Date</th>
          </tr>";

    while ($row = mysqli_fetch_assoc($result)) {

        echo "<tr>";

        echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["seeker"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
        echo "<td>" . $row["created_at"] . "</td>";

        echo "</tr>";
    }

    echo "</table>";
}


// Usage Statistics

else {

    $usersCount = countNewUsers($fromStart, $toEnd);

    $listingsCount = countNewListings($fromStart, $toEnd);

    $requestsCount = countInterestRequests($fromStart, $toEnd);


    echo "<ul>";

    echo "<li>New users: " .
         $usersCount .
         "</li>";

    echo "<li>New listings: " .
         $listingsCount .
         "</li>";

    echo "<li>New interest requests: " .
         $requestsCount .
         "</li>";

    echo "</ul>";
}


mysqli_close($conn);

echo "<p>
        <a href='../View/admin/system-reports.php'>
            Back to reports
        </a>
      </p>";

?>
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

require("../Model/Stats.php");

$toEnd = $to . " 23:59:59";
$fromStart = $from . " 00:00:00";

echo "<h2>System Report Generated</h2>";
echo "<p>Report Type: " . htmlspecialchars($reportType) . "</p>";
echo "<p>From: " . htmlspecialchars($from) . " To: " . htmlspecialchars($to) . "</p>";

if ($reportType === "Active Listings") {

    $rows = getListingsReport($fromStart, $toEnd);

    echo "<table border='1' cellpadding='6'><tr><th>Title</th><th>Location</th><th>Price</th><th>Status</th><th>Posted</th></tr>";
    foreach ($rows as $row) {
        echo "<tr><td>" . htmlspecialchars($row["title"]) . "</td><td>" . htmlspecialchars($row["location"]) . "</td><td>" . $row["price"] . "</td><td>" . htmlspecialchars($row["status"]) . "</td><td>" . $row["created_at"] . "</td></tr>";
    }
    echo "</table>";

} else if ($reportType === "New Users") {

    $rows = getNewUsersReport($fromStart, $toEnd);

    echo "<table border='1' cellpadding='6'><tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr>";
    foreach ($rows as $row) {
        echo "<tr><td>" . htmlspecialchars($row["name"]) . "</td><td>" . htmlspecialchars($row["email"]) . "</td><td>" . htmlspecialchars($row["role"]) . "</td><td>" . $row["created_at"] . "</td></tr>";
    }
    echo "</table>";

} else if ($reportType === "Interest Requests") {

    $rows = getInterestRequestsReport($fromStart, $toEnd);

    echo "<table border='1' cellpadding='6'><tr><th>Listing</th><th>Seeker</th><th>Status</th><th>Date</th></tr>";
    foreach ($rows as $row) {
        echo "<tr><td>" . htmlspecialchars($row["title"]) . "</td><td>" . htmlspecialchars($row["seeker"]) . "</td><td>" . htmlspecialchars($row["status"]) . "</td><td>" . $row["created_at"] . "</td></tr>";
    }
    echo "</table>";

} else {
    // Usage Statistics
    $usersCount = countNewUsers($fromStart, $toEnd);
    $listingsCount = countNewListings($fromStart, $toEnd);
    $requestsCount = countNewInterestRequests($fromStart, $toEnd);

    echo "<ul>";
    echo "<li>New users: " . $usersCount . "</li>";
    echo "<li>New listings: " . $listingsCount . "</li>";
    echo "<li>New interest requests: " . $requestsCount . "</li>";
    echo "</ul>";
}

echo "<p><a href='../View/admin/system-reports.php'>Back to reports</a></p>";

?>
