<?php
session_start();
require 'connexion.php';
if (isset($_GET['id'])) {
   $reservation_id = $_GET['id'];
   $stmt = $conn->prepare("UPDATE reservations SET status = 'Canceled' WHERE reservation_id = ?");
   $stmt->bind_param("i", $reservation_id);
   $stmt->execute();
   header("Location: bar_dashboard.php");
   exit();
}
?>
