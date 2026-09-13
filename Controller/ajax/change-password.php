<?php

session_start();

require '../../Model/User.php';

if (!isset($_SESSION['logged_in'])) {
	echo "<p style=\"color:#c0392b;\">Unauthorized</p>";
	exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

	$currentPassword = htmlspecialchars($_POST['current_password']);
	$newPassword = htmlspecialchars($_POST['new_password']);
	$confirmPassword = htmlspecialchars($_POST['confirm_password']);
	$flag = true;

	if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
		$flag = false;
		echo "<p style=\"color:#c0392b;\">All fields are required</p>";
	}

	if ($flag && strlen($newPassword) < 6) {
		$flag = false;
		echo "<p style=\"color:#c0392b;\">New password must be at least 6 characters</p>";
	}

	if ($flag && $newPassword !== $confirmPassword) {
		$flag = false;
		echo "<p style=\"color:#c0392b;\">Passwords do not match</p>";
	}

	if ($flag) {

		$userId = $_SESSION['user_id'];
		$user = getUserById($userId);
		$valid = ($currentPassword === $user['password']) || password_verify($currentPassword, $user['password']);

		if (!$valid) {
			echo "<p style=\"color:#c0392b;\">Current password is incorrect</p>";
		}
		else if (updateUserPassword($userId, $newPassword)) {
			echo "<p style=\"color:green;\">Password updated successfully</p>";
		}
		else {
			echo "<p style=\"color:#c0392b;\">Failed to update password</p>";
		}
	}
}
else {
	echo "<p style=\"color:#c0392b;\">Something went wrong.</p>";
}

?>
