<?php 
require 'dbConnect.php';

function countUsersByRole($role) {
	$conn = connect();

	$sql = "SELECT COUNT(*) AS c FROM users WHERE role = '$role'";
	$result = mysqli_query($conn, $sql);

	return mysqli_fetch_assoc($result)["c"];
}

function countAvailableListings() {
	$conn = connect();

	$sql = "SELECT COUNT(*) AS c FROM listings WHERE status = 'available'";
	$result = mysqli_query($conn, $sql);

	return mysqli_fetch_assoc($result)["c"];
}

function getListingsReport($from, $to) {
	$conn = connect();

	$sql = "SELECT title, location, price, status, created_at FROM listings
		WHERE created_at BETWEEN '$from' AND '$to' ORDER BY created_at DESC";
	$result = mysqli_query($conn, $sql);

	$rows = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$rows[] = $row;
	}
	return $rows;
}

function getNewUsersReport($from, $to) {
	$conn = connect();

	$sql = "SELECT name, email, role, created_at FROM users
		WHERE created_at BETWEEN '$from' AND '$to' ORDER BY created_at DESC";
	$result = mysqli_query($conn, $sql);

	$rows = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$rows[] = $row;
	}
	return $rows;
}

function getInterestRequestsReport($from, $to) {
	$conn = connect();

	$sql = "SELECT listings.title, users.name AS seeker, interest_requests.status, interest_requests.created_at
		FROM interest_requests
		JOIN listings ON listings.id = interest_requests.listing_id
		JOIN users ON users.id = interest_requests.seeker_id
		WHERE interest_requests.created_at BETWEEN '$from' AND '$to'
		ORDER BY interest_requests.created_at DESC";
	$result = mysqli_query($conn, $sql);

	$rows = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$rows[] = $row;
	}
	return $rows;
}

function countNewUsers($from, $to) {
	$conn = connect();

	$sql = "SELECT COUNT(*) AS c FROM users WHERE created_at BETWEEN '$from' AND '$to'";
	$result = mysqli_query($conn, $sql);

	return mysqli_fetch_assoc($result)["c"];
}

function countNewListings($from, $to) {
	$conn = connect();

	$sql = "SELECT COUNT(*) AS c FROM listings WHERE created_at BETWEEN '$from' AND '$to'";
	$result = mysqli_query($conn, $sql);

	return mysqli_fetch_assoc($result)["c"];
}

function countNewInterestRequests($from, $to) {
	$conn = connect();

	$sql = "SELECT COUNT(*) AS c FROM interest_requests WHERE created_at BETWEEN '$from' AND '$to'";
	$result = mysqli_query($conn, $sql);

	return mysqli_fetch_assoc($result)["c"];
}

?>