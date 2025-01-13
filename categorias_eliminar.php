<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');

// Ensure category ID is provided
if (!isset($_GET['id'])) {
    header('Location: categorias.php');
    exit;
}

$id = intval($_GET['id']);

// Delete the category
$stmt = $conn->prepare("DELETE FROM categorias_productos WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header('Location: categorias.php?deleted=1');
    exit;
} else {
    echo "<p>Error al eliminar la categoría: " . $stmt->error . "</p>";
}

$stmt->close();
?>
