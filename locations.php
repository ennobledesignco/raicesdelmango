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
    <title>Gestionar Roles - CRM Mango</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <nav class="sidebar">
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="roles.php">Gestionar Roles</a></li>
                <li><a href="locations.php">Gestionar Ubicaciones</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main>
            <h1>Gestionar Roles</h1>
            <p>Aquí puedes agregar, editar o eliminar roles.</p>
            <!-- Add Role Form -->
            <div class="card">
                <div class="card-header">Agregar Rol</div>
                <div class="card-body">
                    <form action="php/gestionar_roles.php" method="POST">
                        <label for="nombre_rol" class="form-label">Nombre del Rol</label>
                        <input type="text" name="roles[]" class="form-control" required>
                        <button type="submit" class="btn btn-primary mt-2">Guardar Rol</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
