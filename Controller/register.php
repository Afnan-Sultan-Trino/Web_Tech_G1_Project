<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/common/register.html");
    exit();
}

// --- Collect and sanitize input ---
$name     = isset($_POST["name"]) ? trim($_POST["name"]) : "";
$email    = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";
$confirm  = isset($_POST["confirm"]) ? $_POST["confirm"] : "";
$role     = isset($_POST["role"]) ? $_POST["role"] : "";

// --- Validation rules ---
if (empty($name))     { header("Location: ../View/common/register.html?error=name_required"); exit(); }
if (empty($email))    { header("Location: ../View/common/register.html?error=email_required"); exit(); }
if (empty($password)) { header("Location: ../View/common/register.html?error=password_required"); exit(); }
if (empty($confirm))  { header("Location: ../View/common/register.html?error=confirm_required"); exit(); }
if (empty($role))     { header("Location: ../View/common/register.html?error=role_required"); exit(); }

if (strlen($name) < 3) { header("Location: ../View/common/register.html?error=name_length"); exit(); }

// Email validation – check for spaces, @, and dot after @
if (str_contains($email, " ")) { header("Location: ../View/common/register.html?error=email_space"); exit(); }

$atPos = strpos($email, "@");
if ($atPos === false)               { header("Location: ../View/common/register.html?error=email_at"); exit(); }
if ($atPos === 0)                   { header("Location: ../View/common/register.html?error=email_username"); exit(); }
if (strpos($email, "@", $atPos + 1) !== false) { header("Location: ../View/common/register.html?error=email_multiple_at"); exit(); }

$dotPos = strpos($email, ".", $atPos + 1);
if ($dotPos === false)              { header("Location: ../View/common/register.html?error=email_dot"); exit(); }
if ($dotPos === $atPos + 1)         { header("Location: ../View/common/register.html?error=email_format"); exit(); }
if ($dotPos === strlen($email) - 1) { header("Location: ../View/common/register.html?error=email_end"); exit(); }

if (strlen($password) < 6) { header("Location: ../View/common/register.html?error=password_length"); exit(); }
if ($password !== $confirm) { header("Location: ../View/common/register.html?error=password_match"); exit(); }
if ($role !== "seeker" && $role !== "lister") { header("Location: ../View/common/register.html?error=invalid_role"); exit(); }

// --- Database connection ---
require(__DIR__ . "/../Model/db.php");

if (!$conn) {
    die("System error: Unable to connect to database. Please try again later.");
}

// --- Check if email already exists ---
$checkSql = "SELECT id FROM users WHERE email = ?";
$checkStmt = mysqli_prepare($conn, $checkSql);
mysqli_stmt_bind_param($checkStmt, "s", $email);
mysqli_stmt_execute($checkStmt);
$checkResult = mysqli_stmt_get_result($checkStmt);

if (mysqli_num_rows($checkResult) > 0) {
    mysqli_stmt_close($checkStmt);
    mysqli_close($conn);
    header("Location: ../View/common/register.html?error=email_exists");
    exit();
}
mysqli_stmt_close($checkStmt);

// --- Store plain text password (⚠️ LOCAL TESTING ONLY) ---
// For production, replace the next line with:
//   $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$hashedPassword = $password;   // plain text (as you requested)

// --- Insert new user ---
$insertSql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
$insertStmt = mysqli_prepare($conn, $insertSql);

if (!$insertStmt) {
    die("SQL prepare error: " . mysqli_error($conn));
}

// Bind all parameters as strings
mysqli_stmt_bind_param($insertStmt, "ssss", $name, $email, $hashedPassword, $role);

// Execute and check result
if (mysqli_stmt_execute($insertStmt)) {
    // Success – close and redirect to login page
    mysqli_stmt_close($insertStmt);
    mysqli_close($conn);
    header("Location: ../View/common/login.html?success=registered");
    exit();
} else {
    // Something went wrong – show a user‑friendly message
    // (You can log the real error: error_log(mysqli_stmt_error($insertStmt));)
    $error = mysqli_stmt_error($insertStmt);
    mysqli_stmt_close($insertStmt);
    mysqli_close($conn);
    die("Registration failed. Please try again later.");
}

?>