<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

if (!isset($_SESSION["logged_in"])) {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

if (isset($_POST["name"])) {
    $name = trim($_POST["name"]);
} else {
    $name = "";
}

if (isset($_POST["email"])) {
    $email = trim($_POST["email"]);
} else {
    $email = "";
}

if (isset($_POST["phone"])) {
    $phone = trim($_POST["phone"]);
} else {
    $phone = "";
}

if (empty($name)) {
    die("Name is required.");
}

if (empty($email)) {
    die("Email is required.");
}

if (empty($phone)) {
    die("Phone number is required.");
}

if (strlen($name) < 3) {
    die("Name must contain at least 3 characters.");
}

if (str_contains($email, " ")) {
    die("Email cannot contain spaces.");
}

$atPosition = strpos($email, "@");

if ($atPosition === false) {
    die("Email must contain @.");
}

if ($atPosition === 0) {
    die("Email must have a username before @.");
}

$secondAt = strpos($email, "@", $atPosition + 1);

if ($secondAt !== false) {
    die("Email can contain only one @.");
}

$dotPosition = strpos($email, ".", $atPosition + 1);

if ($dotPosition === false) {
    die("Email must contain a dot after @.");
}

if ($dotPosition === $atPosition + 1) {
    die("Invalid email format.");
}

if ($dotPosition === strlen($email) - 1) {
    die("Email cannot end with a dot.");
}

if (!preg_match("/^[0-9]{11}$/", $phone)) {
    die("Phone number must contain exactly 11 digits.");
}

require("../Model/db.php");

$userId = $_SESSION["user_id"];

// Make sure the new email isn't already used by a different account.
$checkSql = "SELECT id FROM users WHERE email = ? AND id != ?";
$checkStmt = mysqli_prepare($conn, $checkSql);
mysqli_stmt_bind_param($checkStmt, "si", $email, $userId);
mysqli_stmt_execute($checkStmt);
$checkResult = mysqli_stmt_get_result($checkStmt);

if (mysqli_num_rows($checkResult) > 0) {
    mysqli_stmt_close($checkStmt);
    mysqli_close($conn);
    die("That email is already used by another account.");
}

mysqli_stmt_close($checkStmt);

$sql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $phone, $userId);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    $_SESSION["name"] = $name;
    $_SESSION["email"] = $email;

    header("Location: ../View/common/profile.php?success=1");
    exit();

} else {
    $error = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    die("Failed to update profile: " . $error);
}

?>
