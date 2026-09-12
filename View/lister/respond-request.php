<?php

session_start();
require("../../Model/db.php");

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "lister") {
    header("Location: ../common/login.html?error=login_required");
    exit();
}

$listerId = $_SESSION["user_id"];

$sql = "SELECT interest_requests.*, listings.title AS listing_title,
               users.name AS seeker_name
        FROM interest_requests
        JOIN listings ON listings.id = interest_requests.listing_id
        JOIN users ON users.id = interest_requests.seeker_id
        WHERE listings.lister_id = ? AND interest_requests.status = 'pending'
        ORDER BY interest_requests.created_at DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $listerId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$requests = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pending Requests - CampusNest</title>

    <link rel="stylesheet" href="../assets/styles.css">

</head>

<body>


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
        Your <em>Pending Requests</em>
    </h1>

    <p class="nest-sub">
        Review students who are interested in your property.
    </p>

    <?php if (empty($requests)): ?>

        <div class="nest-card">
            <p>You have no pending interest requests right now.</p>
        </div>

    <?php else: ?>

        <?php foreach ($requests as $req): ?>

            <form action="../../Controller/respond-request.php" method="POST">

                <div class="nest-card">

                    <div class="nest-card-head">

                        <h2 class="nest-card-title">
                            <?php echo htmlspecialchars($req["listing_title"]); ?>
                        </h2>

                        <span class="badge badge-pending">
                            Pending
                        </span>

                    </div>

                    <dl class="nest-info-list">

                        <div>
                            <dt>Student Name</dt>
                            <dd><?php echo htmlspecialchars($req["seeker_name"]); ?></dd>
                        </div>

                        <div>
                            <dt>Contact</dt>
                            <dd><?php echo htmlspecialchars($req["phone"]); ?></dd>
                        </div>

                        <div>
                            <dt>Message</dt>
                            <dd><?php echo htmlspecialchars($req["message"]); ?></dd>
                        </div>

                        <div>
                            <dt>Requested On</dt>
                            <dd><?php echo date("j F Y", strtotime($req["created_at"])); ?></dd>
                        </div>

                    </dl>

                    <div class="nest-actions">

                        <input type="hidden" name="request_id" value="<?php echo $req["id"]; ?>">

                        <button type="submit" name="action" value="approve" class="btn btn-primary">
                            Approve
                        </button>

                        <button type="submit" name="action" value="decline" class="btn btn-danger">
                            Decline
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
