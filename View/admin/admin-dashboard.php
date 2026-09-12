<?php
session_start();
if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../common/login.html?error=login_required");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CampusNest</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>

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
            <a href="manage-users.php" class="btn btn-primary">Manage Users</a>
        </div>

        <div class="nest-card">
            <h2 class="nest-card-title">Manage Listings</h2>
            <a href="manage-listings.php" class="btn btn-primary">Review Pending Listings</a>
        </div>

        <div class="nest-card">
            <h2 class="nest-card-title">Manage Reports</h2>
            <a href="reports.php" class="btn btn-primary">View Reports</a>
        </div>

        <div class="nest-card">
            <h2 class="nest-card-title">System Reports</h2>
            <a href="system-reports.php" class="btn btn-primary">View Statistics</a>
        </div>

    </div>
</main>

</body>
</html>
