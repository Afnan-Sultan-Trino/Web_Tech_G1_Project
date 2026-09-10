<?php

// AdminModel.php - all admin SQL queries

function updateUserStatus($conn, $userId, $status) {
    $sql = "UPDATE users SET status = '$status' WHERE id = $userId AND role != 'admin'";
    return mysqli_query($conn, $sql);
}

function deleteUser($conn, $userId) {
    $sql = "DELETE FROM users WHERE id = $userId AND role != 'admin'";
    return mysqli_query($conn, $sql);
}

function approveListing($conn, $listingId) {
    $sql = "UPDATE listings SET status = 'available' WHERE id = $listingId AND status = 'pending'";
    return mysqli_query($conn, $sql);
}

function rejectListing($conn, $listingId) {
    $sql = "DELETE FROM listings WHERE id = $listingId AND status = 'pending'";
    return mysqli_query($conn, $sql);
}

function getListingIdFromReport($conn, $reportId) {
    $sql = "SELECT listing_id FROM reports WHERE id = $reportId";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row ? $row["listing_id"] : null;
}

function markListingRemoved($conn, $listingId) {
    $sql = "UPDATE listings SET status = 'removed' WHERE id = $listingId";
    return mysqli_query($conn, $sql);
}

function updateReportStatus($conn, $reportId, $status) {
    $sql = "UPDATE reports SET status = '$status' WHERE id = $reportId";
    return mysqli_query($conn, $sql);
}

function getActiveListingsReport($conn, $fromStart, $toEnd) {
    $sql = "SELECT title, location, price, status, created_at FROM listings
            WHERE created_at BETWEEN '$fromStart' AND '$toEnd' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getNewUsersReport($conn, $fromStart, $toEnd) {
    $sql = "SELECT name, email, role, created_at FROM users
            WHERE created_at BETWEEN '$fromStart' AND '$toEnd' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getInterestRequestsReport($conn, $fromStart, $toEnd) {
    $sql = "SELECT listings.title, users.name AS seeker, interest_requests.status, interest_requests.created_at
            FROM interest_requests
            JOIN listings ON listings.id = interest_requests.listing_id
            JOIN users ON users.id = interest_requests.seeker_id
            WHERE interest_requests.created_at BETWEEN '$fromStart' AND '$toEnd'
            ORDER BY interest_requests.created_at DESC";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getUsageStatistics($conn, $fromStart, $toEnd) {
    $usersResult = mysqli_query($conn, "SELECT COUNT(*) AS c FROM users WHERE created_at BETWEEN '$fromStart' AND '$toEnd'");
    $usersCount = mysqli_fetch_assoc($usersResult)["c"];

    $listingsResult = mysqli_query($conn, "SELECT COUNT(*) AS c FROM listings WHERE created_at BETWEEN '$fromStart' AND '$toEnd'");
    $listingsCount = mysqli_fetch_assoc($listingsResult)["c"];

    $requestsResult = mysqli_query($conn, "SELECT COUNT(*) AS c FROM interest_requests WHERE created_at BETWEEN '$fromStart' AND '$toEnd'");
    $requestsCount = mysqli_fetch_assoc($requestsResult)["c"];

    return [
        "users" => $usersCount,
        "listings" => $listingsCount,
        "requests" => $requestsCount
    ];
}

?>