<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

// Only a logged-in lister can post a listing.
if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "lister") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

if (isset($_POST["location"])) {
    $location = trim($_POST["location"]);
} else {
    $location = "";
}

if (isset($_POST["room"])) {
    $room = $_POST["room"];
} else {
    $room = "";
}

if (isset($_POST["description"])) {
    $description = trim($_POST["description"]);
} else {
    $description = "";
}

if (isset($_POST["price"])) {
    $price = $_POST["price"];
} else {
    $price = "";
}

if (isset($_POST["contact"])) {
    $contact = trim($_POST["contact"]);
} else {
    $contact = "";
}

if (isset($_FILES["image"]) && isset($_FILES["image"]["name"])) {
    $imageName = $_FILES["image"]["name"];
} else {
    $imageName = "";
}

if (empty($location)) {
    die("Location is required.");
}

if (empty($room)) {
    die("Please select a room type.");
}

if (empty($description)) {
    die("Description is required.");
}

if (empty($price)) {
    die("Price is required.");
}

if (empty($contact)) {
    die("Contact number is required.");
}

if (empty($imageName)) {
    die("Please select an image.");
}

if (strlen($location) < 3) {
    die("Location must contain at least 3 characters.");
}

if (strlen($description) < 10) {
    die("Description must contain at least 10 characters.");
}

if (!is_numeric($price)) {
    die("Price must be numeric.");
}

if ($price <= 0) {
    die("Price must be greater than zero.");
}

if (!preg_match("/^[0-9]{11}$/", $contact)) {
    die("Contact number must contain exactly 11 digits.");
}

$dotPosition = strrpos($imageName, ".");

if ($dotPosition === false) {
    die("Image must have an extension.");
}

$extension = strtolower(substr($imageName, $dotPosition + 1));

if (
    $extension !== "jpg" &&
    $extension !== "jpeg" &&
    $extension !== "png" &&
    $extension !== "webp"
) {
    die("Only JPG, JPEG, PNG and WEBP images are allowed.");
}

// Save the uploaded image with a unique name so listings never overwrite each other.
$uploadDir = "../View/Images/uploads/";
$storedName = uniqid("listing_", true) . "." . $extension;

if (!move_uploaded_file($_FILES["image"]["tmp_name"], $uploadDir . $storedName)) {
    die("There was a problem uploading the image.");
}

require("../Model/db.php");

$listerId = $_SESSION["user_id"];
$title = $location . " - " . ucfirst($room) . " Room";

$sql = "INSERT INTO listings (lister_id, title, location, room_type, description, price, contact, image, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param(
    $stmt,
    "issssdss",
    $listerId,
    $title,
    $location,
    $room,
    $description,
    $price,
    $contact,
    $storedName
);

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    echo "<h2>Property Listing Submitted</h2>";
    echo "<p>Your listing has been saved and is pending approval.</p>";
    echo "<p><a href='../View/lister/manage-listing.php'>View my listings</a></p>";

} else {
    $error = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    die("Failed to save listing: " . $error);
}

?>
