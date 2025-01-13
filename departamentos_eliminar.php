<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');

if (!isset($_GET['id'])) {
    header('Location: departamentos.php');
    exit;
}

$id = $_GET['id'];

// Check if the department is referenced in another table
$stmt_check = $conn->prepare("
    SELECT COUNT(*) AS total 
    FROM empleados 
    WHERE departamento_id = ?
");
$stmt_check->bind_param("i", $id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row_check = $result_check->fetch_assoc();

if ($row_check['total'] > 0) {
    // If the department is referenced, show an error message
    echo "<p>No se puede eliminar el departamento porque está asociado con empleados.</p>";
    echo "<a href='departamentos.php' class='btn'>Regresar</a>";
    exit;
}

// Proceed with deletion if no dependencies
$stmt = $conn->prepare("DELETE FROM departamentos WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<p>Departamento eliminado exitosamente.</p>";
} else {
    echo "<p>Error al eliminar departamento: " . $stmt->error . "</p>";
}

$stmt->close();
header('Location: departamentos.php');
?>
