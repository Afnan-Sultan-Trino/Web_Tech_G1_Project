<?php

require '../Model/User.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/common/register.html");
    exit();
}

// Collect input
$name     = isset($_POST["name"]) ? trim($_POST["name"]) : "";
$email    = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";
$confirm  = isset($_POST["confirm"]) ? $_POST["confirm"] : "";
$role     = isset($_POST["role"]) ? $_POST["role"] : "";

// Validation
if (empty($name)) {
    header("Location: ../View/common/register.html?error=name_required");
    exit();
}

if (empty($email)) {
    header("Location: ../View/common/register.html?error=email_required");
    exit();
}

if (empty($password)) {
    header("Location: ../View/common/register.html?error=password_required");
    exit();
}

if (empty($confirm)) {
    header("Location: ../View/common/register.html?error=confirm_required");
    exit();
}

if (empty($role)) {
    header("Location: ../View/common/register.html?error=role_required");
    exit();
}

if (strlen($name) < 3) {
    header("Location: ../View/common/register.html?error=name_length");
    exit();
}

// Email validation
if (str_contains($email, " ")) {
    header("Location: ../View/common/register.html?error=email_space");
    exit();
}

$atPos = strpos($email, "@");

if ($atPos === false) {
    header("Location: ../View/common/register.html?error=email_at");
    exit();
}

if ($atPos === 0) {
    header("Location: ../View/common/register.html?error=email_username");
    exit();
}

if (strpos($email, "@", $atPos + 1) !== false) {
    header("Location: ../View/common/register.html?error=email_multiple_at");
    exit();
}

$dotPos = strpos($email, ".", $atPos + 1);

if ($dotPos === false) {
    header("Location: ../View/common/register.html?error=email_dot");
    exit();
}

if ($dotPos === $atPos + 1) {
    header("Location: ../View/common/register.html?error=email_format");
    exit();
}

if ($dotPos === strlen($email) - 1) {
    header("Location: ../View/common/register.html?error=email_end");
    exit();
}

if (strlen($password) < 6) {
    header("Location: ../View/common/register.html?error=password_length");
    exit();
}

if ($password !== $confirm) {
    header("Location: ../View/common/register.html?error=password_match");
    exit();
}

$password = password_hash($password, PASSWORD_DEFAULT);

if ($role !== "seeker" && $role !== "lister") {
    header("Location: ../View/common/register.html?error=invalid_role");
    exit();
}


// Check email using Model
if (emailExists($email)) {
    header("Location: ../View/common/register.html?error=email_exists");
    exit();
}


// Register user using Model
if (registerUser($name, $email, $password, $role)) {

    header("Location: ../View/common/login.html?success=registered");
    exit();

} else {

    die("Registration failed. Please try again later.");
}

?>