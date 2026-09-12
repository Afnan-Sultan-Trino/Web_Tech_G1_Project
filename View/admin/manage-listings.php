<?php

session_start();
require("../../Model/db.php");

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../common/login.html?error=login_required");
    exit();
}

$sql = "SELECT listings.*, users.name AS lister_name
        FROM listings
        JOIN users ON users.id = listings.lister_id
        WHERE listings.status = 'pending'
        ORDER BY listings.created_at DESC";
$result = mysqli_query($conn, $sql);
$listings = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

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


<header class="nest-topbar">

    <a href="../index.php" class="nest-brand">
        CampusNest
    </a>

    <span class="nest-role-pill">
        Admin
    </span>

</header>


<div class="nest-shell">

    <p class="nest-eyebrow">
        Admin
    </p>

    <h1 class="nest-heading">
        Manage <em>Listings</em>
    </h1>

    <p class="nest-sub">
        Review listings submitted by listers before they go live.
    </p>

    <?php if (empty($listings)): ?>

        <div class="nest-card">
            <p>There are no listings waiting for approval.</p>
        </div>

    <?php else: ?>

        <?php foreach ($listings as $listing): ?>

            <form action="../../Controller/approve-listing.php" method="POST">

                <div class="nest-card">

                    <div class="nest-card-head">

                        <h2 class="nest-card-title">
                            <?php echo htmlspecialchars($listing["title"]); ?>
                        </h2>

                        <span class="badge badge-pending">
                            Pending
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
                            <dt>Lister</dt>
                            <dd><?php echo htmlspecialchars($listing["lister_name"]); ?></dd>
                        </div>

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
                            <dd>৳<?php echo number_format($listing["price"]); ?> / month</dd>
                        </div>

                        <div>
                            <dt>Description</dt>
                            <dd><?php echo htmlspecialchars($listing["description"]); ?></dd>
                        </div>

                    </dl>

                    <div class="nest-actions">

                        <input type="hidden" name="listing_id" value="<?php echo $listing["id"]; ?>">

                        <button type="submit" name="action" value="approve" class="btn btn-primary">
                            Approve
                        </button>

                        <button type="submit" name="action" value="reject" class="btn btn-danger"
                                onclick="return confirm('Reject and delete this listing? This cannot be undone.');">
                            Reject
                        </button>

                    </div>

                </div>

            </form>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<script src="../assets/validation.js"></script>
</body>
</html>
