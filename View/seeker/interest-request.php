<?php

session_start();
require("../../Model/db.php");

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "seeker") {
    header("Location: ../common/login.html?error=login_required");
    exit();
}

$listingId = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$sql = "SELECT listings.*, users.name AS lister_name
        FROM listings
        JOIN users ON users.id = listings.lister_id
        WHERE listings.id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $listingId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$listing = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
mysqli_close($conn);

if (!$listing) {
    die("Listing not found.");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Interest Request - CampusNest</title>

    <link rel="stylesheet" href="../assets/styles.css">

</head>

<body>


<header class="nest-topbar">

    <a href="../index.php" class="nest-brand">
        CampusNest
    </a>

    <span class="nest-role-pill line">
        Seeker
    </span>

</header>


<main class="nest-page">

    <div class="nest-shell">

        <p class="nest-eyebrow">
            Seeker
        </p>

        <h1 class="nest-heading">
            Send <em>Interest Request</em>
        </h1>

        <p class="nest-sub">
            Express your interest in this property.
        </p>


        <div class="nest-card">


            <div class="nest-card-head">

                <h2 class="nest-card-title">
                    <?php echo htmlspecialchars($listing["title"]); ?>
                </h2>

                <span class="badge badge-available">
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
                    <dt>Rent</dt>
                    <dd>৳<?php echo number_format($listing["price"]); ?> / month</dd>
                </div>

                <div>
                    <dt>Lister</dt>
                    <dd><?php echo htmlspecialchars($listing["lister_name"]); ?></dd>
                </div>

            </dl>


            <form action="../../Controller/interest-request.php" method="POST" novalidate>

                <input type="hidden" name="listing_id" value="<?php echo $listing["id"]; ?>">

                <div class="nest-field">

                    <label for="message">
                        Message
                    </label>

                    <textarea
                        id="message"
                        name="message"
                        placeholder="Write a message to the lister..."></textarea>

                </div>


                <div class="nest-field">

                    <label for="phone">
                        Contact Number
                    </label>

                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        placeholder="01XXXXXXXXX">

                </div>


                <div class="nest-actions">

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Send Request
                    </button>

                    <a
                        href="../index.php"
                        class="btn btn-ghost">
                        Cancel
                    </a>

                </div>


            </form>

        </div>


        <div class="nest-card">

            <h2 class="nest-card-title">
                Report This Listing
            </h2>

            <p class="nest-sub">
                Something wrong with this listing or the lister? Let an admin know.
            </p>

            <form action="../../Controller/submit-report.php" method="POST" novalidate>

                <input type="hidden" name="listing_id" value="<?php echo $listing["id"]; ?>">
                <input type="hidden" name="reported_user_id" value="<?php echo $listing["lister_id"]; ?>">

                <div class="nest-field">

                    <label for="reason">
                        Reason
                    </label>

                    <select id="reason" name="reason">
                        <option value="">Select</option>
                        <option value="Misleading listing">Misleading listing</option>
                        <option value="Scam or fraud">Scam or fraud</option>
                        <option value="Inappropriate content">Inappropriate content</option>
                        <option value="Unresponsive or rude lister">Unresponsive or rude lister</option>
                        <option value="Other">Other</option>
                    </select>

                </div>

                <div class="nest-field">

                    <label for="details">
                        Details (optional)
                    </label>

                    <textarea
                        id="details"
                        name="details"
                        placeholder="Add any extra detail that would help an admin review this..."></textarea>

                </div>

                <div class="nest-actions">

                    <button
                        type="submit"
                        class="btn btn-danger">
                        Submit Report
                    </button>

                </div>

            </form>

        </div>

    </div>

</main>
<script src="../assets/validation.js"></script>

</body>
</html>
