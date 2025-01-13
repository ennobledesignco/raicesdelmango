<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');

if (!isset($_GET['id'])) {
    die('ID de empleado no especificado.');
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM empleados WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<p>Empleado eliminado exitosamente.</p>";
} else {
    echo "<p>Error al eliminar empleado: " . $stmt->error . "</p>";
}

$stmt->close();
header('Location: empleados_editar.php');
?>
