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

