<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

if (isset($_POST["email"])) {
    $email = trim($_POST["email"]);
} else {
    $email = "";
}

if (empty($email)) {
    die("Email is required.");
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

require("../Model/db.php");

$sql = "SELECT id FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$found = mysqli_num_rows($result) > 0;

mysqli_stmt_close($stmt);
mysqli_close($conn);

echo "<h2>Password Reset Request</h2>";

// Don't reveal whether the email exists - just say a link would be sent either way.
echo "<p>If an account with that email exists, a password reset link has been sent.</p>";

if ($found) {
    // In a real deployment this is where you would generate a reset
    // token, save it against the user, and email a reset link.
}

?>
