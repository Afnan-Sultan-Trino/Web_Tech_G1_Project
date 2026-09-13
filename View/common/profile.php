<?php

session_start();
require("../../Model/User.php");

if (!isset($_SESSION["logged_in"])) {
    header("Location: login.html?error=login_required");
    exit();
}

$user = getUserById($_SESSION["user_id"]);

$success = isset($_GET["success"]);

?>
<!DOCTYPE html>
<head>

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



            <div class="nest-field">

                <label for="phone"> Phone </label>

                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user["phone"] ?? ""); ?>">

            </div>



            <div class="nest-actions">

                <button type="submit" class="btn btn-primary"> 
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


    <div class="nest-card">

        <h2 class="nest-card-title">
            Refresh Profile Data
        </h2>

        

        <button type="button" id="loadProfileBtn" class="btn btn-gold">
            Load My Profile
        </button>

        <div id="profileData" style="margin-top: 15px;"></div>

    </div>


    <div class="nest-card">

        <h2 class="nest-card-title">
            Change Password
        </h2>

        

        <form id="changePasswordForm" onsubmit="return submitPasswordChange(this)" novalidate>

            <div class="nest-field">
                <label for="currentPassword">Current Password</label>
                <input type="password" id="currentPassword" name="currentPassword" placeholder="Current Password" required>
            </div>

            <div class="nest-field">
                <label for="newPassword">New Password</label>
                <input type="password" id="newPassword" name="newPassword" placeholder="New Password (min 6 chars)" required>
            </div>

            <div class="nest-field">
                <label for="confirmPassword">Confirm New Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
            </div>

            <div class="nest-actions">
                <button type="submit" class="btn btn-primary">
                    Update Password
                </button>
            </div>

        </form>

        <div id="passwordMessage" style="margin-top: 10px;"></div>

    </div>

</div>


<script src="../assets/validation.js"></script>

<script src="profile-ajax.js"></script>

</body>
</html>
