<?php
session_start();
$isAuthorized = isset($_SESSION["logged_in"]) && $_SESSION["role"] === "seeker";
?>
<!DOCTYPE html>
<head>
    <title>Seeker Dashboard - CampusNest</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>

<?php if (!$isAuthorized): ?>

<div class="nest-shell">
    <div class="nest-card">
        <p>You must be logged in as a seeker to view this page.</p>
        <a href="../common/login.html" class="btn btn-primary">Go to login</a>
    </div>
</div>

<?php else: ?>

<header class="nest-topbar">
    <a href="../index.php" class="nest-brand">CampusNest</a>
    <span class="nest-role-pill line">Seeker</span>
</header>

<main class="nest-page">
    <div class="nest-shell">

        <h1 class="nest-heading">Welcome, <em><?php echo htmlspecialchars($_SESSION["name"]); ?></em></h1>
        <p class="nest-sub">What would you like to do today?</p>

        <div class="nest-card">
            <h2 class="nest-card-title">Search for a room</h2>
            <p>Filter listings by location, price and room type.</p>
            <a href="search-filer.html" class="btn btn-primary">Search & Filter</a>
        </div>

        <div class="nest-card">
            <h2 class="nest-card-title">My Account</h2>
            <p>Update your name, email and phone number.</p>
            <a href="../../Controller/view-profile.php" class="btn btn-ghost">View Profile</a>
        </div>

    </div>
</main>

<?php endif; ?>

</body>
</html>