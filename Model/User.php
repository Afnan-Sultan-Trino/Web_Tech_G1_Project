<?php 
require 'dbConnect.php';

function getUserByEmail($email) {
	$conn = connect();

	$sql = "SELECT * FROM users WHERE email = '$email'";
	$result = mysqli_query($conn, $sql);

	return mysqli_fetch_assoc($result);
}

function emailExists($email) {
	$conn = connect();

	$sql = "SELECT id FROM users WHERE email = '$email'";
	$result = mysqli_query($conn, $sql);

	return mysqli_num_rows($result) > 0;
}

function insertUser($name, $email, $password, $role) {
	$conn = connect();

	$sql = "INSERT INTO users (name, email, password, role)
		VALUES ('$name', '$email', '$password', '$role')";
	$result = mysqli_query($conn, $sql);

	if ($result) 
		return true;
	return false;
}

function getUserById($id) {
	$conn = connect();

	$sql = "SELECT * FROM users WHERE id = $id";
	$result = mysqli_query($conn, $sql);

	return mysqli_fetch_assoc($result);
}

function updateUserProfile($id, $name, $email, $phone) {
	$conn = connect();


	$sql = "UPDATE users
		SET name = '$name', email = '$email', phone = '$phone'
		WHERE id = $id";
	$result = mysqli_query($conn, $sql);

	if ($result) 
		return true;
	return false;
}

function updateUserPassword($id, $password) {
	$conn = connect();


	$sql = "UPDATE users SET password = '$password' WHERE id = $id";
	$result = mysqli_query($conn, $sql);

	if ($result) 
		return true;
	return false;
}

function getAllUsers($search = "") {
	$conn = connect();

	if ($search !== "") {
		$sql = "SELECT * FROM users
			WHERE role != 'admin' AND (name LIKE '%$search%' OR email LIKE '%$search%')
			ORDER BY created_at DESC";
	} else {
		$sql = "SELECT * FROM users WHERE role != 'admin' ORDER BY created_at DESC";
	}

	$result = mysqli_query($conn, $sql);

	$users = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$users[] = $row;
	}
	return $users;
}

function updateUserStatus($id, $status) {
	$conn = connect();

	$sql = "UPDATE users SET status = '$status' WHERE id = $id AND role != 'admin'";
	$result = mysqli_query($conn, $sql);

	if ($result) 
		return true;
	return false;
}

function deleteUser($id) {
	$conn = connect();
	$sql = "DELETE FROM users WHERE id = $id AND role != 'admin'";
	$result = mysqli_query($conn, $sql);

	if ($result) 
		return true;
	return false;
}

?>