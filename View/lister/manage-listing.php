<?php

session_start();

$isAuthorized = isset($_SESSION["logged_in"]) && $_SESSION["role"] === "lister";
$hasData = isset($_SESSION["manageListingData"]);

$listings = [];

if ($isAuthorized && $hasData) {
    $listings = $_SESSION["manageListingData"];
    unset($_SESSION["manageListingData"]);
}

$badgeClass = [
    "available" => "badge-available",
    "pending"   => "badge-pending",
    "occupied"  => "badge-flag",
    "removed"   => "badge-flag",
];

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Listings - CampusNest</title>

    <link rel="stylesheet" href="../assets/styles.css">

</head>

<body>

<?php if (!$isAuthorized): ?>

<div class="nest-shell">
    <div class="nest-card">
        <p>You must be logged in as a lister to view this page.</p>
        <a href="../common/login.html" class="btn btn-primary">Go to login</a>
    </div>
</div>

<?php elseif (!$hasData): ?>

<div class="nest-shell">
    <div class="nest-card">
        <p>Please open this page from the lister dashboard.</p>
        <a href="lister-dashboard.php" class="btn btn-primary">Go to dashboard</a>
    </div>
</div>

<?php else: ?>


<header class="nest-topbar">

    <a href="../index.php" class="nest-brand">
        CampusNest
    </a>

    <span class="nest-role-pill gold">
        Lister
    </span>

</header>

<div class="nest-shell">

    <h1 class="nest-heading">
        Manage <em>Listings</em>
    </h1>

    <p class="nest-sub">
        Edit, remove or update your property listings.
    </p>

    <?php if (empty($listings)): ?>

        <div class="nest-card">
            <p>You haven't posted any listings yet.
                <a href="post-listing.html">Post your first room.</a>
            </p>
        </div>

    <?php else: ?>

        <?php foreach ($listings as $listing): ?>

            <form action="../../Controller/manage-listing.php" method="POST">

                <div class="nest-card">

                    <div class="nest-card-head">

                        <h2 class="nest-card-title">
                            <?php echo htmlspecialchars($listing["title"]); ?>
                        </h2>

                        <span class="badge <?php echo $badgeClass[$listing["status"]] ?? "badge-pending"; ?>">
                            <?php echo ucfirst($listing["status"]); ?>
                        </span>

                    </div>

                    <?php if (!empty($listing["image"])): ?>

                        <div class="property-image">
                            <img
                                src="../Images/uploads/<?php echo htmlspecialchars($listing["image"]); ?>"
                                alt="<?php echo htmlspecialchars($listing["title"]); ?>">
                        </div>

                    <?php endif; ?>

                    <dl class="nest-info-list">

                        <div>
                            <dt>Location</dt>
                            <dd><?php echo htmlspecialchars($listing["location"]); ?></dd>
                        </div>

                        <div>
                            <dt>Room Type</dt>
                            <dd><?php echo htmlspecialchars(ucfirst($listing["room_type"])); ?></dd>
                        </div>

                        <div>
                            <dt>Rent</dt>
                            <dd>৳<?php echo number_format($listing["price"]); ?></dd>
                        </div>

                    </dl>

                    <div class="nest-actions">

                        <input type="hidden" name="listing_id" value="<?php echo $listing["id"]; ?>">

                        <?php if ($listing["status"] !== "occupied"): ?>
                            <button type="submit" name="action" value="occupied" class="btn btn-gold">
                                Mark Occupied
                            </button>
                        <?php else: ?>
                            <button type="submit" name="action" value="available" class="btn btn-gold">
                                Mark Available
                            </button>
                        <?php endif; ?>

                        <button type="submit" name="action" value="delete" class="btn btn-danger"
                                onclick="return confirm('Delete this listing? This cannot be undone.');">
                            Delete
                        </button>

                    </div>

                </div>

            </form>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<?php endif; ?>

<script src="../assets/validation.js"></script>
</body>
</html>
