<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Empleados - CRM Mango</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/crm_mango/css/style.css">
</head>
<body>
    <div class="sidebar">
        <h2>CRM Mango</h2>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="empleados.php">Gestionar Empleados</a></li>
            <li><a href="locations.php">Gestionar Ubicaciones</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="main">
        <h1>Gestionar Empleados</h1>

        <!-- Add Employee Form -->
        <div class="card">
            <div class="card-header">Agregar Empleado</div>
            <form action="php/gestionar_empleados.php" method="POST">
                <div class="form-group">
                    <label for="nombre_empleado">Nombre del Empleado</label>
                    <input type="text" name="nombre_empleado" id="nombre_empleado" required>
                </div>
                <div class="form-group">
                    <label for="puesto">Puesto</label>
                    <input type="text" name="puesto" id="puesto" required>
                </div>
                <div class="form-group">
                    <label for="fecha_inicio">Fecha de Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" required>
                </div>
                <div class="form-group">
                    <label for="fecha_fin">Fecha de Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin">
                </div>
                <button type="submit" class="btn">Guardar Empleado</button>
            </form>
        </div>

        <!-- Display Employees Table -->
        <div class="card">
            <div class="card-header">Empleados Existentes</div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Puesto</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM empleados";
                    $result = $conn->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['nombre']}</td>
                                <td>{$row['puesto']}</td>
                                <td>{$row['fecha_inicio']}</td>
                                <td>" . ($row['fecha_fin'] ? $row['fecha_fin'] : 'Activo') . "</td>
                                <td>
                                    <a href='php/editar_empleado.php?id={$row['id']}' class='btn-warning'>Editar</a>
                                    <a href='php/eliminar_empleado.php?id={$row['id']}' class='btn-danger'>Eliminar</a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
