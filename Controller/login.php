<?php

session_start();

require("../Model/User.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/common/login.html");
    exit();
}

$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";


// Validation

if (empty($email)) {
    header("Location: ../View/common/login.html?error=email_required");
    exit();
}

if (empty($password)) {
    header("Location: ../View/common/login.html?error=password_required");
    exit();
}

<<<<<<< HEAD

// Get user from Model

$user = loginUser($email);

if ($user === false) {
=======
require("../Model/User.php");

$user = getUserByEmail($email);

if (!$user) {
>>>>>>> 2f6f798ea5c5d0c92aaf999052bc71a41a9c4993
    header("Location: ../View/common/login.html?error=email_not_found");
    exit();
}

<<<<<<< HEAD

// Check password

if (
    !password_verify($password, $user["password"]) &&
    !hash_equals($user["password"], $password)
) {
=======
if ($password !== $user["password"]) {
>>>>>>> 2f6f798ea5c5d0c92aaf999052bc71a41a9c4993
    header("Location: ../View/common/login.html?error=wrong_password");
    exit();
}

<<<<<<< HEAD

// Check account status

=======
>>>>>>> 2f6f798ea5c5d0c92aaf999052bc71a41a9c4993
if ($user["status"] === "suspended") {
    header("Location: ../View/common/login.html?error=account_suspended");
    exit();
}


// Create session

$_SESSION["logged_in"] = true;
$_SESSION["user_id"] = $user["id"];
$_SESSION["name"] = $user["name"];
$_SESSION["email"] = $user["email"];
$_SESSION["role"] = $user["role"];


// Remember me

if (isset($_POST["remember"])) {

    setcookie(
        "remember_email",
        $user["email"],
        time() + (30 * 24 * 60 * 60),
        "/"
    );
}

<<<<<<< HEAD

// Redirect according to role

=======
>>>>>>> 2f6f798ea5c5d0c92aaf999052bc71a41a9c4993
if ($_SESSION["role"] === "lister") {

    header("Location: ../View/lister/lister-dashboard.php");

} else if ($_SESSION["role"] === "admin") {

    header("Location: ../View/admin/admin-dashboard.php");

} else {

    header("Location: ../View/seeker/seeker-dashboard.php");
}

exit();

?>