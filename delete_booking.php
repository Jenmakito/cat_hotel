<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $conn->query("DELETE FROM bookings WHERE id = $id");
}

header("Location: booking_menu.php");
exit();
?>
