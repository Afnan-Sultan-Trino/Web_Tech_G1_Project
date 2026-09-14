<?php
session_start();
$isAuthorized = isset($_SESSION["logged_in"]) && $_SESSION["role"] === "admin";
?>
<!DOCTYPE html>
<head>
    <title>Admin Dashboard - CampusNest</title>
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

<?php else: ?>

<header class="nest-topbar">
    <a href="../index.php" class="nest-brand">CampusNest</a>
    <span class="nest-role-pill">Admin</span>
</header>

<main class="nest-page">
    <div class="nest-shell">

        <h1 class="nest-heading">Welcome, <em><?php echo htmlspecialchars($_SESSION["name"]); ?></em></h1>
        <p class="nest-sub">Platform administration.</p>

        <div class="nest-card">
            <h2 class="nest-card-title">Manage Users</h2>
            <a href="../../Controller/view-manage-users.php" class="btn btn-primary">Manage Users</a>
        </div>

        <div class="nest-card">
            <h2 class="nest-card-title">Manage Listings</h2>
            <a href="../../Controller/view-manage-listings.php" class="btn btn-primary">Review Pending Listings</a>
        </div>

        <div class="nest-card">
            <h2 class="nest-card-title">Manage Reports</h2>
            <a href="../../Controller/view-reports.php" class="btn btn-primary">View Reports</a>
        </div>

        <div class="nest-card">
            <h2 class="nest-card-title">System Reports</h2>
            <a href="../../Controller/view-system-reports.php" class="btn btn-primary">View Statistics</a>
        </div>

    </div>
</main>

<?php endif; ?>

</body>
</html>
