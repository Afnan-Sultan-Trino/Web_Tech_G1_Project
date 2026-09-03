<?php

session_start();
require("../../Model/db.php");

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../common/login.html?error=login_required");
    exit();
}

$totalSeekers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users WHERE role = 'seeker'"))["c"];
$totalListers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users WHERE role = 'lister'"))["c"];
$activeListings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM listings WHERE status = 'available'"))["c"];

mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>System Reports - CampusNest</title>

    <link rel="stylesheet" href="../assets/styles.css">

</head>

<body>


<header class="nest-topbar">

    <a href="../index.php" class="nest-brand">
        CampusNest
    </a>

    <span class="nest-role-pill">
        Admin
    </span>

</header>


<div class="nest-shell">

    <h1 class="nest-heading">
        System <em>Reports</em>
    </h1>

    <p class="nest-sub">
        View basic statistics of the CampusNest platform.
    </p>


    <div class="admin-stats">

        <div class="stat-box">
            <h3>Total Seekers</h3>
            <p class="stat-number"><?php echo $totalSeekers; ?></p>
        </div>

        <div class="stat-box">
            <h3>Total Listers</h3>
            <p class="stat-number"><?php echo $totalListers; ?></p>
        </div>

        <div class="stat-box">
            <h3>Active Listings</h3>
            <p class="stat-number"><?php echo $activeListings; ?></p>
        </div>

    </div>


    <div class="nest-card">

        <h2 class="nest-card-title">
            Generate Report
        </h2>

        <form action="../../Controller/system-reports.php" method="POST" novalidate>

            <div class="nest-field">

                <label for="report-type">
                    Report Type
                </label>

                <select id="report-type" name="report_type">
                    <option>Active Listings</option>
                    <option>New Users</option>
                    <option>Interest Requests</option>
                    <option>Usage Statistics</option>
                </select>

            </div>


            <div class="nest-row">

                <div class="nest-field">
                    <label for="from">From</label>
                    <input type="date" id="from" name="from">
                </div>

                <div class="nest-field">
                    <label for="to">To</label>
                    <input type="date" id="to" name="to">
                </div>

            </div>

            <button type="submit" class="btn btn-primary">
                Generate Report
            </button>

        </form>

    </div>

</div>

<script src="../assets/validation.js"></script>
</body>
</html>
