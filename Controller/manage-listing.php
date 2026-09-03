<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "lister") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

if (isset($_POST["listing_id"])) {
    $listingId = trim($_POST["listing_id"]);
} else {
    $listingId = "";
}

if (isset($_POST["action"])) {
    $action = $_POST["action"];
} else {
    $action = "";
}

if (empty($listingId) || !ctype_digit((string) $listingId)) {
    die("Listing ID is required.");
}

if (
    $action !== "occupied" &&
    $action !== "available" &&
    $action !== "delete"
) {
    die("Invalid listing action.");
}

require("../Model/db.php");

$listerId = $_SESSION["user_id"];

// Make sure this listing actually belongs to the logged-in lister.
$ownSql = "SELECT id FROM listings WHERE id = ? AND lister_id = ?";
$ownStmt = mysqli_prepare($conn, $ownSql);
mysqli_stmt_bind_param($ownStmt, "ii", $listingId, $listerId);
mysqli_stmt_execute($ownStmt);
$ownResult = mysqli_stmt_get_result($ownStmt);

if (mysqli_num_rows($ownResult) === 0) {
    mysqli_stmt_close($ownStmt);
    mysqli_close($conn);
    die("You do not have permission to modify this listing.");
}

mysqli_stmt_close($ownStmt);

if ($action === "delete") {

    $sql = "DELETE FROM listings WHERE id = ? AND lister_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $listingId, $listerId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

} else {

    $sql = "UPDATE listings SET status = ? WHERE id = ? AND lister_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sii", $action, $listingId, $listerId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

}

mysqli_close($conn);

header("Location: ../View/lister/manage-listing.php");
exit();

?>
