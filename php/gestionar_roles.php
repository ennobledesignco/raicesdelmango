<?php
include('config.php');

if (isset($_POST['roles']) && is_array($_POST['roles']) &&
    isset($_POST['fechas_inicio']) && is_array($_POST['fechas_inicio'])) {
    foreach ($_POST['roles'] as $index => $rol) {
        $rol = $conn->real_escape_string($rol);
        $fecha_inicio = $conn->real_escape_string($_POST['fechas_inicio'][$index]);
        $fecha_fin = isset($_POST['fechas_fin'][$index]) && $_POST['fechas_fin'][$index] != '' ?
                     $conn->real_escape_string($_POST['fechas_fin'][$index]) : NULL;

        $sql = "INSERT INTO roles (nombre, fecha_inicio, fecha_fin) VALUES ('$rol', '$fecha_inicio', " . ($fecha_fin ? "'$fecha_fin'" : "NULL") . ")";
        $conn->query($sql);
    }
    echo "Roles agregados con éxito.";
} else {
    echo "Error: Datos insuficientes.";
}

$conn->close();
?>
