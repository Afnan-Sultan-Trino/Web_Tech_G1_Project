<?php

session_start();

if (!isset($_SESSION["logged_in"])) {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/common/profile.php");
    exit();
}

$name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : "";

if (empty($name)) {
    die("Name is required.");
}

if (empty($email)) {
    die("Email is required.");
}

require("../Model/User.php");

$userId = $_SESSION["user_id"];

if (updateUserProfile($userId, $name, $email, $phone)) {

    $_SESSION["name"] = $name;
    $_SESSION["email"] = $email;

    header("Location: ../View/common/profile.php?success=1");
    exit();

} else {
    die("Failed to update profile.");
}

?>
