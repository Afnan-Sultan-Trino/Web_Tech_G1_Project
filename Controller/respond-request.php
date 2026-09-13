<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "lister") {
    header("Location: ../View/common/login.html?error=login_required");
    exit();
}

if (isset($_POST["request_id"])) {
    $requestId = trim($_POST["request_id"]);
} else {
    $requestId = "";
}

if (isset($_POST["action"])) {
    $action = $_POST["action"];
} else {
    $action = "";
}

if (empty($requestId) || !ctype_digit((string) $requestId)) {
    die("Request ID is required.");
}

if (
    $action !== "approve" &&
    $action !== "decline"
) {
    die("Invalid request action.");
}

require("../Model/InterestRequest.php");

$listerId = $_SESSION["user_id"];
$newStatus = $action === "approve" ? "approved" : "declined";

if (!respondToRequestForLister($requestId, $listerId, $newStatus)) {
    die("You do not have permission to update this request.");
}

header("Location: ../View/lister/respond-request.php");
exit();

?>
