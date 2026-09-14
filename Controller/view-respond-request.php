<?php

session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "lister") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

require("../Model/InterestRequest.php");

$listerId = $_SESSION["user_id"];

$_SESSION["respondRequestData"] = getPendingRequestsForLister($listerId);

header("Location: ../View/lister/respond-request.php");
exit();

?>
