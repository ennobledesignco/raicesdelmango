<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');

if (!isset($_GET['id'])) {
    die('ID de puesto no especificado.');
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM puestos WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<p>Puesto eliminado exitosamente.</p>";
} else {
    echo "<p>Error al eliminar puesto: " . $stmt->error . "</p>";
}

$stmt->close();
header('Location: puestos.php');
?>
