<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/common/login.html");
    exit();
}

$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";

if (empty($email)) {
    header("Location: ../View/common/login.html?error=email_required");
    exit();
}

if (empty($password)) {
    header("Location: ../View/common/login.html?error=password_required");
    exit();
}

require("../Model/db.php");

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header("Location: ../View/common/login.html?error=email_not_found");
    exit();
}

$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($password !== $user["password"]) {
 
    mysqli_close($conn);
 
    header("Location: ../View/common/login.html?error=wrong_password");
    exit();
}


if ($user["status"] === "suspended") {

    mysqli_close($conn);

    header("Location: ../View/common/login.html?error=account_suspended");
    exit();
}

$_SESSION["logged_in"] = true;
$_SESSION["user_id"] = $user["id"];
$_SESSION["name"] = $user["name"];
$_SESSION["email"] = $user["email"];
$_SESSION["role"] = $user["role"];

if (isset($_POST["remember"])) {

    setcookie(
        "remember_email",
        $user["email"],
        time() + (30 * 24 * 60 * 60),
        "/"
    );
}

mysqli_close($conn);

if ($_SESSION["role"] === "lister") {

    header("Location: ../View/lister/lister-dashboard.php");

} else if ($_SESSION["role"] === "admin") {

    header("Location: ../View/admin/admin-dashboard.php");

} else {

    header("Location: ../View/seeker/seeker-dashboard.php");

}

exit();

?>
