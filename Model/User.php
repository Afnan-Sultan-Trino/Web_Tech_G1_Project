
<?php

require_once "db.php";


// Check email already exists
function emailExists($email)
{
    global $conn;

    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $exists = mysqli_num_rows($result) > 0;

    mysqli_stmt_close($stmt);

    return $exists;
}


// Register user
function registerUser($name, $email, $password, $role)
{
    global $conn;

    $sql = "INSERT INTO users (name, email, password, role)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $name,
        $email,
        $password,
        $role
    );

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}


// Login user
function loginUser($email)
{
    global $conn;

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        mysqli_stmt_close($stmt);
        return false;
    }

    $user = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $user;
}

function getUserPhone($userId)
{
    global $conn;

    $userId = mysqli_real_escape_string($conn, $userId);

    $sql = "SELECT phone FROM users WHERE id = '$userId'";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            return $row["phone"];
        }
    }

    return "";
}

function updateUserStatus($userId, $newStatus)
{
    global $conn;

    $userId = mysqli_real_escape_string($conn, $userId);
    $newStatus = mysqli_real_escape_string($conn, $newStatus);

    $sql = "UPDATE users
            SET status = '$newStatus'
            WHERE id = '$userId' AND role != 'admin'";

    return mysqli_query($conn, $sql);
}

function deleteUser($userId)
{
    global $conn;

    $userId = mysqli_real_escape_string($conn, $userId);

    $sql = "DELETE FROM users
            WHERE id = '$userId' AND role != 'admin'";

    return mysqli_query($conn, $sql);
}
?>

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

