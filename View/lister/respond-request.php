<?php

session_start();

$isAuthorized = isset($_SESSION["logged_in"]) && $_SESSION["role"] === "lister";
$hasData = isset($_SESSION["respondRequestData"]);

$requests = [];

if ($isAuthorized && $hasData) {
    $requests = $_SESSION["respondRequestData"];
    unset($_SESSION["respondRequestData"]);
}

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

<?php endif; ?>

<script src="../assets/validation.js"></script>
</body>
</html>
