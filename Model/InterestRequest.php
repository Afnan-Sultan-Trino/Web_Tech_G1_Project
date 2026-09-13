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