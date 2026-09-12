<?php

session_start();
require("../../Model/db.php");

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../common/login.html?error=login_required");
    exit();
}

$sql = "SELECT reports.*, reporter.name AS reporter_name, reported.name AS reported_name
        FROM reports
        JOIN users AS reporter ON reporter.id = reports.reporter_id
        LEFT JOIN users AS reported ON reported.id = reports.reported_user_id
        WHERE reports.status = 'open'
        ORDER BY reports.created_at DESC";
$result = mysqli_query($conn, $sql);
$reports = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Reports - CampusNest</title>

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

    <h1 class="nest-heading">
        Manage User <em>Reports</em>
    </h1>

    <p class="nest-sub">
        Review reports submitted by seekers and listers.
    </p>

    <?php if (empty($reports)): ?>

        <div class="nest-card">
            <p>There are no open reports.</p>
        </div>

    <?php else: ?>

        <?php foreach ($reports as $r): ?>

            <form action="../../Controller/reports.php" method="POST">

                <div class="nest-card">

                    <div class="nest-card-head">

                        <h2 class="nest-card-title">
                            Report #RPT-<?php echo str_pad($r["id"], 3, "0", STR_PAD_LEFT); ?>
                        </h2>

                        <span class="badge badge-flag">
                            Open
                        </span>

                    </div>

                    <dl class="nest-info-list">

                        <div>
                            <dt>Reported By</dt>
                            <dd><?php echo htmlspecialchars($r["reporter_name"]); ?></dd>
                        </div>

                        <div>
                            <dt>Reported User</dt>
                            <dd><?php echo htmlspecialchars($r["reported_name"] ?? "N/A"); ?></dd>
                        </div>

                        <div>
                            <dt>Reason</dt>
                            <dd><?php echo htmlspecialchars($r["reason"]); ?></dd>
                        </div>

                        <?php if (!empty($r["details"])): ?>
                            <div>
                                <dt>Details</dt>
                                <dd><?php echo htmlspecialchars($r["details"]); ?></dd>
                            </div>
                        <?php endif; ?>

                    </dl>

                    <div class="nest-actions">

                        <input type="hidden" name="report_id" value="<?php echo $r["id"]; ?>">

                        <button type="submit" name="action" value="resolve" class="btn btn-primary">
                            Resolve
                        </button>

                        <button type="submit" name="action" value="remove" class="btn btn-danger">
                            Remove Listing
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
