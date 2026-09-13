<?php
session_start();
if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "lister") {
    header("Location: ../common/login.html?error=login_required");
    exit();
}
?>
<!DOCTYPE html>
<head>
    <title>Lister Dashboard - CampusNest</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>

<header class="nest-topbar">
    <a href="../index.php" class="nest-brand">CampusNest</a>
    <span class="nest-role-pill gold">Lister</span>
</header>

<main class="nest-page">
    <div class="nest-shell">

        <h1 class="nest-heading">Welcome, <em><?php echo htmlspecialchars($_SESSION["name"]); ?></em></h1>
        <p class="nest-sub">Manage your properties and requests.</p>

        <div class="nest-card">
            <h2 class="nest-card-title">Post a Room</h2>
            <p>Add a new property listing.</p>
            <a href="post-listing.html" class="btn btn-primary">Post Listing</a>
        </div>

        <div class="nest-card">
            <h2 class="nest-card-title">Manage Listings</h2>
            <p>Edit, mark occupied, or delete your listings.</p>
            <a href="manage-listing.php" class="btn btn-primary">Manage Listings</a>
        </div>

        <div class="nest-card">
            <h2 class="nest-card-title">Pending Requests</h2>
            <p>Review and respond to interested students.</p>
            <a href="respond-request.php" class="btn btn-primary">View Requests</a>
        </div>

        <div class="nest-card">
            <h2 class="nest-card-title">My Account</h2>
            <a href="../common/profile.php" class="btn btn-ghost">View Profile</a>
        </div>

    </div>
</main>

</body>
</html>
