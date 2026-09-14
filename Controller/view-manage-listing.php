<?php

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "lister") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

require("../Model/Listing.php");

$listerId = $_SESSION["user_id"];

$_SESSION["manageListingData"] = getListingsByLister($listerId);

header("Location: ../View/lister/manage-listing.php");
exit();

?>
