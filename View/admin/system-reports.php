<?php

session_start();

$isAuthorized = isset($_SESSION["logged_in"]) && $_SESSION["role"] === "admin";
$hasData = isset($_SESSION["systemReportsData"]);

$totalSeekers = 0;
$totalListers = 0;
$activeListings = 0;

if ($isAuthorized && $hasData) {
    $totalSeekers = $_SESSION["systemReportsData"]["totalSeekers"];
    $totalListers = $_SESSION["systemReportsData"]["totalListers"];
    $activeListings = $_SESSION["systemReportsData"]["activeListings"];
    unset($_SESSION["systemReportsData"]);
}

?>
<!DOCTYPE html>
<head>
    <title>System Reports - CampusNest</title>

    <link rel="stylesheet" href="../assets/styles.css">

</head>

<body>

<?php if (!$isAuthorized): ?>

<div class="nest-shell">
    <div class="nest-card">
        <p>You must be logged in as an admin to view this page.</p>
        <a href="../common/login.html" class="btn btn-primary">Go to login</a>
    </div>
</div>

<?php elseif (!$hasData): ?>

<div class="nest-shell">
    <div class="nest-card">
        <p>Please open this page from the admin dashboard.</p>
        <a href="admin-dashboard.php" class="btn btn-primary">Go to dashboard</a>
    </div>
</div>

<?php else: ?>


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

<?php endif; ?>

<script src="../assets/validation.js"></script>
</body>
</html>
