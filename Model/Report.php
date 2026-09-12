<?php

require_once "db.php";


// Active Listings
function getActiveListings($fromStart, $toEnd)
{
    global $conn;

    $fromStart = mysqli_real_escape_string($conn, $fromStart);
    $toEnd = mysqli_real_escape_string($conn, $toEnd);

    $sql = "SELECT title, location, price, status, created_at
            FROM listings
            WHERE created_at BETWEEN '$fromStart' AND '$toEnd'
            ORDER BY created_at DESC";

    return mysqli_query($conn, $sql);
}


// New Users
function getNewUsers($fromStart, $toEnd)
{
    global $conn;

    $fromStart = mysqli_real_escape_string($conn, $fromStart);
    $toEnd = mysqli_real_escape_string($conn, $toEnd);

    $sql = "SELECT name, email, role, created_at
            FROM users
            WHERE created_at BETWEEN '$fromStart' AND '$toEnd'
            ORDER BY created_at DESC";

    return mysqli_query($conn, $sql);
}


// Interest Requests
function getInterestRequests($fromStart, $toEnd)
{
    global $conn;

    $fromStart = mysqli_real_escape_string($conn, $fromStart);
    $toEnd = mysqli_real_escape_string($conn, $toEnd);

    $sql = "SELECT listings.title,
                   users.name AS seeker,
                   interest_requests.status,
                   interest_requests.created_at
            FROM interest_requests
            JOIN listings
            ON listings.id = interest_requests.listing_id
            JOIN users
            ON users.id = interest_requests.seeker_id
            WHERE interest_requests.created_at
            BETWEEN '$fromStart' AND '$toEnd'
            ORDER BY interest_requests.created_at DESC";

    return mysqli_query($conn, $sql);
}


// Count New Users
function countNewUsers($fromStart, $toEnd)
{
    global $conn;

    $fromStart = mysqli_real_escape_string($conn, $fromStart);
    $toEnd = mysqli_real_escape_string($conn, $toEnd);

    $sql = "SELECT COUNT(*) AS c
            FROM users
            WHERE created_at BETWEEN '$fromStart' AND '$toEnd'";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row["c"];
}


// Count New Listings
function countNewListings($fromStart, $toEnd)
{
    global $conn;

    $fromStart = mysqli_real_escape_string($conn, $fromStart);
    $toEnd = mysqli_real_escape_string($conn, $toEnd);

    $sql = "SELECT COUNT(*) AS c
            FROM listings
            WHERE created_at BETWEEN '$fromStart' AND '$toEnd'";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row["c"];
}


// Count Interest Requests
function countInterestRequests($fromStart, $toEnd)
{
    global $conn;

    $fromStart = mysqli_real_escape_string($conn, $fromStart);
    $toEnd = mysqli_real_escape_string($conn, $toEnd);

    $sql = "SELECT COUNT(*) AS c
            FROM interest_requests
            WHERE created_at BETWEEN '$fromStart' AND '$toEnd'";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row["c"];
}

function submitReport($reporterId, $reportedUserId, $listingId, $reason, $details)
{
    global $conn;

    $reason = mysqli_real_escape_string($conn, $reason);
    $details = mysqli_real_escape_string($conn, $details);

    $sql = "INSERT INTO reports
            (reporter_id, reported_user_id, listing_id, reason, details, status)
            VALUES
            ($reporterId, $reportedUserId, $listingId, '$reason', '$details', 'open')";

    return mysqli_query($conn, $sql);
}

function updateReport($reportId, $action)
{
    global $conn;

    $reportId = mysqli_real_escape_string($conn, $reportId);

    if ($action === "remove") {

        $sql = "SELECT listing_id FROM reports WHERE id = '$reportId'";

        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row && $row["listing_id"]) {

            $listingId = mysqli_real_escape_string(
                $conn,
                $row["listing_id"]
            );

            $sql = "UPDATE listings
                    SET status = 'removed'
                    WHERE id = '$listingId'";

            mysqli_query($conn, $sql);
        }

        $newStatus = "removed";

    } else {

        $newStatus = "resolved";
    }

    $newStatus = mysqli_real_escape_string($conn, $newStatus);

    $sql = "UPDATE reports
            SET status = '$newStatus'
            WHERE id = '$reportId'";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        return mysqli_affected_rows($conn);
    }

    return 0;
}
?>