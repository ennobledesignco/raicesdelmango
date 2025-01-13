<?php
include('config.php');
$id = $_GET['id'];

$sql = "DELETE FROM roles WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    echo "Rol eliminado con éxito.";
} else {
    echo "Error al eliminar el rol: " . $conn->error;
}

$conn->close();
?>
