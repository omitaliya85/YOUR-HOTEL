<?php
include('../config/db.php');

$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$limit = 6; // Rooms per page
$offset = ($page - 1) * $limit;

$where = "";
if ($status !== 'all') {
    $where = "WHERE availability_status = '$status'";
}

// Count Total Rooms
$total_query = "SELECT COUNT(*) AS total FROM rooms $where";
$total_result = mysqli_query($conn, $total_query);
$total_rooms = mysqli_fetch_assoc($total_result)['total'];

// Fetch Rooms
$query = "SELECT * FROM rooms $where LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

$rooms = '';
while ($room = mysqli_fetch_assoc($result)) {
    $imgPath = "../" . htmlspecialchars($room['image']);
    $defaultImage = "../uploads/rooms/default-room.jpeg";

    if (empty($room['image']) || !file_exists($imgPath)) {
        $imgPath = $defaultImage;
    }

    $rooms .= '
        <div class="room-card">
            <img src="' . $imgPath . '" alt="' . htmlspecialchars($room['room_type']) . '" class="room-img">
            <div class="room-info">
                <h3 class="room-title">' . htmlspecialchars($room['room_type']) . '</h3>
                <p class="room-price">₹' . number_format($room['price'], 2) . ' / night</p>
                <a href="room_details.php?id=' . $room['id'] . '" class="btn room-btn">View Details</a>
            </div>
        </div>
    ';
}

// Pagination Logic
$total_pages = ceil($total_rooms / $limit);
$pagination = '';
for ($i = 1; $i <= $total_pages; $i++) {
    $pagination .= '<button class="pagination-btn" data-page="' . $i . '">' . $i . '</button>';
}

// Return Response
echo json_encode(['rooms' => $rooms, 'pagination' => $pagination]);
?>