<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');

if (!isset($_GET['id'])) {
    header('Location: productos.php');
    exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header('Location: productos.php?deleted=1');
    exit;
} else {
    echo "<p>Error al eliminar el producto: " . $stmt->error . "</p>";
}

$stmt->close();
?>
