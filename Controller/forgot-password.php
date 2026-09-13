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

require("../Model/User.php");

$found = emailExists($email);

echo "<h2>Password Reset Request</h2>";

echo "<p>If an account with that email exists, a password reset link has been sent.</p>";


?>
