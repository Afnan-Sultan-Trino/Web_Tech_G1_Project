<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

if (isset($_POST["location"])) {
    $location = trim($_POST["location"]);
} else {
    $location = "";
}

if (isset($_POST["price"])) {
    $price = trim($_POST["price"]);
} else {
    $price = "";
}

if (isset($_POST["room_details"]) && is_array($_POST["room_details"])) {
    $roomDetails = $_POST["room_details"];
} else {
    $roomDetails = [];
}

if (empty($location)) {
    die("Please select a location.");
}

if (empty($price)) {
    die("Please select a price range.");
}

if (empty($roomDetails)) {
    die("Please select at least one room detail.");
}

// The price field comes in as "min-max", e.g. "8000-10000".
$priceParts = explode("-", $price);
$minPrice = isset($priceParts[0]) ? (float) $priceParts[0] : 0;
$maxPrice = isset($priceParts[1]) ? (float) $priceParts[1] : 999999999;

require("../Model/db.php");

// Build a dynamic "room_type IN (?, ?, ...)" clause matching the checked boxes.
$placeholders = implode(",", array_fill(0, count($roomDetails), "?"));

$sql = "SELECT listings.*, users.name AS lister_name
        FROM listings
        JOIN users ON users.id = listings.lister_id
        WHERE listings.status = 'available'
          AND listings.location = ?
          AND listings.price BETWEEN ? AND ?
          AND listings.room_type IN ($placeholders)
        ORDER BY listings.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);

$types = "sdd" . str_repeat("s", count($roomDetails));
$bindParams = [$stmt, $types, $location, $minPrice, $maxPrice];

foreach ($roomDetails as $detail) {
    $bindParams[] = $detail;
}

// Build references (mysqli_stmt_bind_param requires variables, not values).
$refs = [];
foreach ($bindParams as $key => $value) {
    $refs[$key] = &$bindParams[$key];
}

call_user_func_array("mysqli_stmt_bind_param", $refs);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$listings = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - CampusNest</title>
    <link rel="stylesheet" href="../View/assets/styles.css">
</head>

<body>

<header class="nest-topbar">
    <a href="../View/index.php" class="nest-brand">CampusNest</a>
    <span class="nest-role-pill line">Seeker</span>
</header>

<main class="nest-page">
    <div class="nest-shell">

        <h1 class="nest-heading">Search <em>Results</em></h1>
        <p class="nest-sub">
            Showing rooms in <?php echo htmlspecialchars($location); ?>
            between ৳<?php echo number_format($minPrice); ?> and ৳<?php echo number_format($maxPrice); ?>.
        </p>

        <?php if (empty($listings)): ?>

            <div class="nest-card">
                <p>No listings matched your search. Try different filters.</p>
                <a href="../View/seeker/search-filer.html" class="btn btn-ghost">Back to Search</a>
            </div>

        <?php else: ?>

            <?php foreach ($listings as $listing): ?>

                <div class="nest-card">

                    <div class="nest-card-head">
                        <h2 class="nest-card-title"><?php echo htmlspecialchars($listing["title"]); ?></h2>
                        <span class="badge badge-available">Available</span>
                    </div>

                    <?php if (!empty($listing["image"])): ?>

                        <div class="property-image">
                            <img
                                src="../View/Images/uploads/<?php echo htmlspecialchars($listing["image"]); ?>"
                                alt="<?php echo htmlspecialchars($listing["title"]); ?>">
                        </div>

                    <?php endif; ?>

                    <dl class="nest-info-list">
                        <div>
                            <dt>Location</dt>
                            <dd><?php echo htmlspecialchars($listing["location"]); ?></dd>
                        </div>
                        <div>
                            <dt>Rent</dt>
                            <dd>৳<?php echo number_format($listing["price"]); ?> / month</dd>
                        </div>
                        <div>
                            <dt>Lister</dt>
                            <dd><?php echo htmlspecialchars($listing["lister_name"]); ?></dd>
                        </div>
                    </dl>

                    <div class="nest-actions">
                        <a href="../View/seeker/interest-request.php?id=<?php echo $listing["id"]; ?>" class="btn btn-primary">
                            Express Interest
                        </a>
                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>
</main>

</body>
</html>
