<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');

// Ensure calidad ID is provided
if (!isset($_GET['id'])) {
    header('Location: calidades.php');
    exit;
}

$id = intval($_GET['id']);

// Delete the calidad
$stmt = $conn->prepare("DELETE FROM calidades WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header('Location: calidades.php?deleted=1');
    exit;
} else {
    echo "<p>Error al eliminar la calidad: " . $stmt->error . "</p>";
}

$stmt->close();
?>
