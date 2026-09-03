<?php

session_start();
require("../../Model/db.php");

if (!isset($_SESSION["logged_in"])) {
    header("Location: login.html?error=login_required");
    exit();
}

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
mysqli_close($conn);

$success = isset($_GET["success"]);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Account - CampusNest</title>

    <link rel="stylesheet" href="../assets/styles.css">

</head>

<body>


<header class="nest-topbar">

    <a href="../index.php" class="nest-brand">
        CampusNest
    </a>

    <span class="nest-role-pill line">
        Account
    </span>

</header>


<div class="nest-shell">

    <div class="nest-card">

        <div class="profile-image">
            Photo
        </div>


        <h1
            class="nest-heading"
            style="text-align:center;">
            <?php echo htmlspecialchars($user["name"]); ?>
        </h1>

        <p
            style="text-align:center;color:#777;">
            <?php echo ucfirst($user["role"]); ?>
        </p>

        <?php if ($success): ?>
            <p style="text-align:center;color:green;">Profile updated successfully.</p>
        <?php endif; ?>


        <hr style="margin:25px 0;">


        <form
            action="../../Controller/profile.php"
            method="POST"
            novalidate>


            <!-- NAME -->

            <div class="nest-field">

                <label for="name">
                    Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?php echo htmlspecialchars($user["name"]); ?>">

            </div>


            <!-- EMAIL -->

            <div class="nest-field">

                <label for="email">
                    Email
                </label>

                <input
                    type="text"
                    id="email"
                    name="email"
                    value="<?php echo htmlspecialchars($user["email"]); ?>">

            </div>


            <!-- PHONE -->

            <div class="nest-field">

                <label for="phone">
                    Phone
                </label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="<?php echo htmlspecialchars($user["phone"] ?? ""); ?>">

            </div>


            <!-- BUTTONS -->

            <div class="nest-actions">

                <button
                    type="submit"
                    class="btn btn-primary">
                    Save Changes
                </button>


                <a
                    href="../../Controller/logout.php"
                    class="btn btn-danger">
                    Sign Out
                </a>

            </div>


        </form>

    </div>

</div>


<script src="../assets/validation.js"></script>

</body>
</html>
