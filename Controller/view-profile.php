<?php

session_start();

if (!isset($_SESSION["logged_in"])) {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

require("../Model/User.php");

$_SESSION["profileData"] = getUserById($_SESSION["user_id"]);

if (isset($_GET["success"])) {
    header("Location: ../View/common/profile.php?success=1");
} else {
    header("Location: ../View/common/profile.php");
}
exit();

?>
