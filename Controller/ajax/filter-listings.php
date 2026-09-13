<?php

session_start();

require '../../Model/Listing.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'seeker') {
	echo "<p style=\"color:#c0392b;\">Unauthorized</p>";
	exit();
}

$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$roomDetails = isset($_GET['room_details']) ? explode(',', $_GET['room_details']) : [];
$priceRange = isset($_GET['price']) ? trim($_GET['price']) : '';

$minPrice = null;
$maxPrice = null;

if (!empty($priceRange)) {
	$rangeParts = explode("-", $priceRange);
	if (count($rangeParts) === 2) {
		$minPrice = (int) $rangeParts[0];
		$maxPrice = (int) $rangeParts[1];
	}
}

$listings = filterListings($location, $roomDetails, $minPrice, $maxPrice);

if (count($listings) === 0) {
	echo "<div class=\"no-results\">No listings found matching your criteria.</div>";
	exit();
}

echo "<div class=\"results-count\">Found <strong>" . count($listings) . "</strong> listing(s)</div>";

foreach ($listings as $listing) {

	$statusClass = $listing['status'] === 'available' ? 'Available' :   htmlspecialchars($listing['status']);

	echo "<div class=\"listing-item\">";
	echo "<h3>" . htmlspecialchars($listing['title']) . "</h3>";
	echo "<p class=\"location\">" . htmlspecialchars($listing['location']) . "</p>";

	if (!empty($listing['description'])) {
		echo "<p>" . htmlspecialchars($listing['description']) . "</p>";
	}

	echo "<p class=\"price\">" . htmlspecialchars($listing['price']) . " BDT</p>";

	if (!empty($listing['contact'])) {
		echo "<p>" . htmlspecialchars($listing['contact']) . "</p>";
	}

	echo "<p class=\"status\">Status: " . $statusClass . "</p>";
	echo "<a href=\"interest-request.php?id=" . (int) $listing['id'] . "\" class=\"btn btn-primary\">View</a>";
	echo "</div>";
}

?>
