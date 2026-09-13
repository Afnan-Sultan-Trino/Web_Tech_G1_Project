<?php 
require 'dbConnect.php';

function insertReport($reporterId, $reportedUserId, $listingId, $reason, $details) {
	$conn = connect();


	$sql = "INSERT INTO reports (reporter_id, reported_user_id, listing_id, reason, details, status)
		VALUES ($reporterId, $reportedUserId, $listingId, '$reason', '$details', 'open')";
	$result = mysqli_query($conn, $sql);

	if ($result) 
		return true;
	return false;
}

function getOpenReports() {
	$conn = connect();

	$sql = "SELECT reports.*, reporter.name AS reporter_name, reported.name AS reported_name
		FROM reports
		JOIN users AS reporter ON reporter.id = reports.reporter_id
		LEFT JOIN users AS reported ON reported.id = reports.reported_user_id
		WHERE reports.status = 'open'
		ORDER BY reports.created_at DESC";
	$result = mysqli_query($conn, $sql);

	$reports = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$reports[] = $row;
	}
	return $reports;
}

function findReportListingId($reportId) {
	$conn = connect();

	$sql = "SELECT listing_id FROM reports WHERE id = $reportId";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

	if ($row && $row["listing_id"]) {
		return $row["listing_id"];
	}
	return null;
}

function updateReportStatus($reportId, $status) {
	$conn = connect();


	$sql = "UPDATE reports SET status = '$status' WHERE id = $reportId";
	$result = mysqli_query($conn, $sql);

	if ($result) 
		return true;
	return false;
}

?>