<?php

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "seeker") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

require("../Model/Listing.php");

$listingId = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$_SESSION["interestRequestData"] = getListingWithLister($listingId);

header("Location: ../View/seeker/interest-request.php");
exit();

?>
