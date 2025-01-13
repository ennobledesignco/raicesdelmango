<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');

if (!isset($_GET['id'])) {
    header('Location: huertas_overview.php');
    exit;
}

$id = $_GET['id'];

// Check if the Huerta is referenced in other tables
$stmt_check = $conn->prepare("
    SELECT COUNT(*) AS total FROM recepciones WHERE huerta_id = ?
");
$stmt_check->bind_param("i", $id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row_check = $result_check->fetch_assoc();

if ($row_check['total'] > 0) {
    echo "<p>No se puede eliminar la huerta porque está asociada con recepciones.</p>";
    echo "<a href='huertas_overview.php' class='btn'>Regresar</a>";
    exit;
}

// Proceed with deletion if no dependencies
$stmt = $conn->prepare("DELETE FROM huertas WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<p>Huerta eliminada exitosamente.</p>";
} else {
    echo "<p>Error al eliminar la huerta: " . $stmt->error . "</p>";
}

$stmt->close();
header('Location: huertas_overview.php');
?>
