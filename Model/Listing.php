<?php 
require 'dbConnect.php';

function insertListing($listerId, $title, $location, $roomType, $description, $price, $contact, $image) {
	$conn = connect();
	$sql = "INSERT INTO listings (lister_id, title, location, room_type, description, price, contact, image, status)
		VALUES ($listerId, '$title', '$location', '$roomType', '$description', $price, '$contact', '$image', 'pending')";
	$result = mysqli_query($conn, $sql);

	if ($result) 
		return true;
	return false;
}

function getListingsByLister($listerId) {
	$conn = connect();
	$sql = "SELECT * FROM listings WHERE lister_id = $listerId ORDER BY created_at DESC";
	$result = mysqli_query($conn, $sql);

	$listings = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$listings[] = $row;
	}
	return $listings;
}

function updateListingStatusForLister($id, $listerId, $status) {
	$conn = connect();
	$sql = "UPDATE listings SET status = '$status' WHERE id = $id AND lister_id = $listerId";
	mysqli_query($conn, $sql);

	return mysqli_affected_rows($conn) > 0;
}

function deleteListingForLister($id, $listerId) {
	$conn = connect();
	$sql = "DELETE FROM listings WHERE id = $id AND lister_id = $listerId";
	mysqli_query($conn, $sql);

	return mysqli_affected_rows($conn) > 0;
}

function getPendingListings() {
	$conn = connect();

	$sql = "SELECT listings.*, users.name AS lister_name
		FROM listings
		JOIN users ON users.id = listings.lister_id
		WHERE listings.status = 'pending'
		ORDER BY listings.created_at DESC";
	$result = mysqli_query($conn, $sql);

	$listings = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$listings[] = $row;
	}
	return $listings;
}

function approveListing($id) {
	$conn = connect();

	$sql = "UPDATE listings SET status = 'available' WHERE id = $id AND status = 'pending'";
	$result = mysqli_query($conn, $sql);

	if ($result) 
		return true;
	return false;
}

function rejectListing($id) {
	$conn = connect();

	$sql = "DELETE FROM listings WHERE id = $id AND status = 'pending'";
	$result = mysqli_query($conn, $sql);

	if ($result) 
		return true;
	return false;
}

function getListingWithLister($id) {
	$conn = connect();

	$sql = "SELECT listings.*, users.name AS lister_name
		FROM listings
		JOIN users ON users.id = listings.lister_id
		WHERE listings.id = $id";
	$result = mysqli_query($conn, $sql);

	return mysqli_fetch_assoc($result);
}

function listingExists($id) {
	$conn = connect();

	$id = (int) $id;
	$sql = "SELECT id FROM listings WHERE id = $id";
	$result = mysqli_query($conn, $sql);

	return mysqli_num_rows($result) > 0;
}

function searchAvailableListings($location, $minPrice, $maxPrice, $roomDetails) {
	$conn = connect();

	$sql = "SELECT listings.*, users.name AS lister_name
		FROM listings
		JOIN users ON users.id = listings.lister_id
		WHERE listings.status = 'available'
		  AND listings.location = '$location'
		  AND listings.price BETWEEN $minPrice AND $maxPrice";

	if (!empty($roomDetails)) {
		$quoted = [];
		foreach ($roomDetails as $detail) {
			$quoted[] = "'" . $detail . "'";
		}
		$sql .= " AND listings.room_type IN (" . implode(",", $quoted) . ")";
	}

	$sql .= " ORDER BY listings.created_at DESC";

	$result = mysqli_query($conn, $sql);

	$listings = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$listings[] = $row;
	}
	return $listings;
}
function filterListings($location, $roomDetails, $minPrice, $maxPrice) {
	$conn = connect();

	$sql = "SELECT id, title, location, room_type, description, price, contact, image, status, created_at
		FROM listings
		WHERE status = 'available'";

	if (!empty($location)) {
		$sql .= " AND location = '$location'";
	}

	if (!empty($roomDetails)) {
		$quoted = [];
		foreach ($roomDetails as $detail) {
			$quoted[] = "'" . $detail . "'";
		}
		$sql .= " AND room_type IN (" . implode(",", $quoted) . ")";
	}

	if ($minPrice !== null && $maxPrice !== null) {
		$minPrice = (float) $minPrice;
		$maxPrice = (float) $maxPrice;
		$sql .= " AND price BETWEEN $minPrice AND $maxPrice";
	}

	$sql .= " ORDER BY created_at DESC LIMIT 20";

	$result = mysqli_query($conn, $sql);

	$listings = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$listings[] = $row;
	}
	return $listings;
}

function markListingRemoved($id) {
	$conn = connect();

	$sql = "UPDATE listings SET status = 'removed' WHERE id = $id";
	$result = mysqli_query($conn, $sql);

	if ($result) 
		return true;
	return false;
}

?>