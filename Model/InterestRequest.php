
<?php

require("db.php");

function updateRequestStatus($requestId, $listerId, $newStatus)
{
    global $conn;

    $requestId = mysqli_real_escape_string($conn, $requestId);
    $listerId = mysqli_real_escape_string($conn, $listerId);
    $newStatus = mysqli_real_escape_string($conn, $newStatus);

    $sql = "UPDATE interest_requests
            JOIN listings
            ON listings.id = interest_requests.listing_id
            SET interest_requests.status = '$newStatus'
            WHERE interest_requests.id = '$requestId'
            AND listings.lister_id = '$listerId'";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        return mysqli_affected_rows($conn);
    }

    return 0;
}


function listingExists($listingId)
{
    global $conn;

    $listingId = mysqli_real_escape_string($conn, $listingId);

    $sql = "SELECT id FROM listings
            WHERE id = '$listingId'";

    $result = mysqli_query($conn, $sql);

    return mysqli_num_rows($result) > 0;
}


function submitInterestRequest($listingId, $seekerId, $message, $phone)
{
    global $conn;

    $listingId = mysqli_real_escape_string($conn, $listingId);
    $seekerId = mysqli_real_escape_string($conn, $seekerId);
    $message = mysqli_real_escape_string($conn, $message);
    $phone = mysqli_real_escape_string($conn, $phone);

    $sql = "INSERT INTO interest_requests
            (listing_id, seeker_id, message, phone, status)
            VALUES
            ('$listingId', '$seekerId', '$message', '$phone', 'pending')";

    return mysqli_query($conn, $sql);
}


<?php 
require 'dbConnect.php';

function insertInterestRequest($listingId, $seekerId, $message, $phone) {
	$conn = connect();

	$sql = "INSERT INTO interest_requests (listing_id, seeker_id, message, phone, status)
		VALUES ($listingId, $seekerId, '$message', '$phone', 'pending')";
	$result = mysqli_query($conn, $sql);

	if ($result) 
		return true;
	return false;
}

function getPendingRequestsForLister($listerId) {
	$conn = connect();

	$sql = "SELECT interest_requests.*, listings.title AS listing_title,
			users.name AS seeker_name
		FROM interest_requests
		JOIN listings ON listings.id = interest_requests.listing_id
		JOIN users ON users.id = interest_requests.seeker_id
		WHERE listings.lister_id = $listerId AND interest_requests.status = 'pending'
		ORDER BY interest_requests.created_at DESC";
	$result = mysqli_query($conn, $sql);

	$requests = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$requests[] = $row;
	}
	return $requests;
}

function respondToRequestForLister($requestId, $listerId, $status) {
	$conn = connect();

	$sql = "UPDATE interest_requests
		JOIN listings ON listings.id = interest_requests.listing_id
		SET interest_requests.status = '$status'
		WHERE interest_requests.id = $requestId AND listings.lister_id = $listerId";
	mysqli_query($conn, $sql);

	return mysqli_affected_rows($conn) > 0;
}

?>