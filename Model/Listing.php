<?php

require_once "db.php";

function searchListings($location, $minPrice, $maxPrice, $roomDetails)
{
    global $conn;

    $location = mysqli_real_escape_string($conn, $location);

    $roomTypes = [];

    foreach ($roomDetails as $detail) {
        $roomTypes[] = "'" . mysqli_real_escape_string($conn, $detail) . "'";
    }

    $roomTypeList = implode(",", $roomTypes);

    $sql = "SELECT listings.*, users.name AS lister_name
            FROM listings
            JOIN users ON users.id = listings.lister_id
            WHERE listings.status = 'available'
            AND listings.location = '$location'
            AND listings.price BETWEEN $minPrice AND $maxPrice
            AND listings.room_type IN ($roomTypeList)
            ORDER BY listings.created_at DESC";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return [];
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
function addListing($listerId, $title, $location, $room, $description, $price, $contact, $image)
{
    global $conn;

    $listerId = mysqli_real_escape_string($conn, $listerId);
    $title = mysqli_real_escape_string($conn, $title);
    $location = mysqli_real_escape_string($conn, $location);
    $room = mysqli_real_escape_string($conn, $room);
    $description = mysqli_real_escape_string($conn, $description);
    $price = mysqli_real_escape_string($conn, $price);
    $contact = mysqli_real_escape_string($conn, $contact);
    $image = mysqli_real_escape_string($conn, $image);

    $sql = "INSERT INTO listings
            (lister_id, title, location, room_type, description,
             price, contact, image, status)
            VALUES
            ('$listerId', '$title', '$location', '$room',
             '$description', '$price', '$contact', '$image', 'pending')";

    return mysqli_query($conn, $sql);

}

function checkListingOwner($listingId, $listerId)
{
    global $conn;

    $listingId = mysqli_real_escape_string($conn, $listingId);
    $listerId = mysqli_real_escape_string($conn, $listerId);

    $sql = "SELECT id FROM listings
            WHERE id = '$listingId' AND lister_id = '$listerId'";

    $result = mysqli_query($conn, $sql);

    return mysqli_num_rows($result) > 0;
}


function deleteListing($listingId, $listerId)
{
    global $conn;

    $listingId = mysqli_real_escape_string($conn, $listingId);
    $listerId = mysqli_real_escape_string($conn, $listerId);

    $sql = "DELETE FROM listings
            WHERE id = '$listingId' AND lister_id = '$listerId'";

    return mysqli_query($conn, $sql);
}


function updateListingStatus($listingId, $listerId, $status)
{
    global $conn;

    $listingId = mysqli_real_escape_string($conn, $listingId);
    $listerId = mysqli_real_escape_string($conn, $listerId);
    $status = mysqli_real_escape_string($conn, $status);

    $sql = "UPDATE listings
            SET status = '$status'
            WHERE id = '$listingId' AND lister_id = '$listerId'";

    return mysqli_query($conn, $sql);
}
function approveListing($listingId)
{
    global $conn;

    $listingId = mysqli_real_escape_string($conn, $listingId);

    $sql = "UPDATE listings
            SET status = 'available'
            WHERE id = '$listingId' AND status = 'pending'";

    return mysqli_query($conn, $sql);
}


function rejectListing($listingId)
{
    global $conn;

    $listingId = mysqli_real_escape_string($conn, $listingId);

    $sql = "DELETE FROM listings
            WHERE id = '$listingId' AND status = 'pending'";

    return mysqli_query($conn, $sql);
}
?>