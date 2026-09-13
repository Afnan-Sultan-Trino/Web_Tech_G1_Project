<<<<<<< HEAD
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

require("../Model/Listing.php");

$listerId = $_SESSION["user_id"];


/* Check ownership */

if (!checkListingOwner($listingId, $listerId)) {
    die("You do not have permission to modify this listing.");
}


/* Delete or update */

if ($action === "delete") {

    deleteListing($listingId, $listerId);

} else {

    updateListingStatus($listingId, $listerId, $action);
}


header("Location: ../View/lister/manage-listing.php");
exit();

?>
=======
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

if ($action !== "occupied" &&
    $action !== "available" &&
    $action !== "delete") {
    die("Invalid listing action.");
}

require("../Model/Listing.php");

$listerId = $_SESSION["user_id"];

if ($action === "delete") {
    $ok = deleteListingForLister($listingId, $listerId);
} else {
    $ok = updateListingStatusForLister($listingId, $listerId, $action);
}

if (!$ok) {
    die("You do not have permission to modify this listing.");
}

header("Location: ../View/lister/manage-listing.php");
exit();

?>
>>>>>>> 2f6f798ea5c5d0c92aaf999052bc71a41a9c4993
