<?php

session_start();

require '../../Model/User.php';

if (!isset($_SESSION['logged_in'])) {
	echo "Unauthorized";
	exit();
}

$user = getUserById($_SESSION['user_id']);

if ($user) {
	echo "<div style=\"background:#f5f5f5; padding:15px; border-radius:5px;\">"
		. "<p><strong>Name:</strong> " . htmlspecialchars($user['name']) . "</p>"
		. "<p><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</p>"
		. "<p><strong>Phone:</strong> " . htmlspecialchars(!empty($user['phone']) ? $user['phone'] : 'Not provided') . "</p>"
		. "<p><strong>Role:</strong> " . htmlspecialchars($user['role']) . "</p>"
		. "</div>";
}
else {
	echo "<p style=\"color:#c0392b;\">User not found</p>";
}

?>
