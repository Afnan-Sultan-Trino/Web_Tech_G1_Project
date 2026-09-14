<?php

session_start();

$isAuthorized = isset($_SESSION["logged_in"]) && $_SESSION["role"] === "admin";
$hasData = isset($_SESSION["manageUsersData"]);

$search = "";
$users = [];

if ($isAuthorized && $hasData) {
    $search = $_SESSION["manageUsersData"]["search"];
    $users = $_SESSION["manageUsersData"]["users"];
    unset($_SESSION["manageUsersData"]);
}

?>
<!DOCTYPE html>
<head>
    <title>Manage Users - CampusNest</title>

    <link rel="stylesheet" href="../assets/styles.css">

</head>

<body>

<?php if (!$isAuthorized): ?>

<div class="nest-shell">
    <div class="nest-card">
        <p>You must be logged in as an admin to view this page.</p>
        <a href="../common/login.html" class="btn btn-primary">Go to login</a>
    </div>
</div>

<?php elseif (!$hasData): ?>

<div class="nest-shell">
    <div class="nest-card">
        <p>Please open this page from the admin dashboard.</p>
        <a href="admin-dashboard.php" class="btn btn-primary">Go to dashboard</a>
    </div>
</div>

<?php else: ?>


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
        Manage <em>User Accounts</em>
    </h1>

    <p class="nest-sub">
        Manage registered seekers and listers.
    </p>


    <div class="nest-card">

        <form method="GET" action="../../Controller/view-manage-users.php">

            <div class="nest-field">

                <label for="search">
                    Search User
                </label>

                <input
                    type="search"
                    id="search"
                    name="search"
                    value="<?php echo htmlspecialchars($search); ?>"
                    placeholder="Search by name or email">

            </div>

        </form>

    </div>

    <?php if (empty($users)): ?>

        <div class="nest-card">
            <p>No users found.</p>
        </div>

    <?php else: ?>

        <?php foreach ($users as $u): ?>

            <form action="../../Controller/manage-users.php" method="POST">

                <div class="nest-card">

                    <div class="nest-card-head">

                        <h2 class="nest-card-title">
                            <?php echo htmlspecialchars($u["name"]); ?>
                        </h2>

                        <span class="badge <?php echo $u["status"] === "active" ? "badge-available" : "badge-flag"; ?>">
                            <?php echo ucfirst($u["status"]); ?>
                        </span>

                    </div>

                    <dl class="nest-info-list">

                        <div>
                            <dt>User ID</dt>
                            <dd>USR-<?php echo str_pad($u["id"], 4, "0", STR_PAD_LEFT); ?></dd>
                        </div>

                        <div>
                            <dt>Role</dt>
                            <dd><?php echo ucfirst($u["role"]); ?></dd>
                        </div>

                        <div>
                            <dt>Email</dt>
                            <dd><?php echo htmlspecialchars($u["email"]); ?></dd>
                        </div>

                    </dl>

                    <div class="nest-actions">

                        <input type="hidden" name="user_id" value="<?php echo $u["id"]; ?>">

                        <?php if ($u["status"] === "active"): ?>
                            <button type="submit" name="action" value="suspend" class="btn btn-gold">
                                Suspend
                            </button>
                        <?php else: ?>
                            <button type="submit" name="action" value="activate" class="btn btn-primary">
                                Reactivate
                            </button>
                        <?php endif; ?>

                        <button type="submit" name="action" value="delete" class="btn btn-danger"
                                onclick="return confirm('Delete this user? This cannot be undone.');">
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
