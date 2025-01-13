<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gather employee data
    $nombre = $_POST['nombre'];
    $puesto_id = $_POST['puesto_id'];
    $departamento_id = $_POST['departamento_id'];
    $salario = $_POST['salario'];
    $tipo_pago = $_POST['tipo_pago'];
    $fecha_inicio = $_POST['fecha_inicio'];

    // Gather user account data
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role_id = $_POST['role_id'];

    // Insert the employee record
    $stmt = $conn->prepare("INSERT INTO empleados (nombre, puesto_id, departamento_id, salario, tipo_pago, fecha_inicio) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siisss", $nombre, $puesto_id, $departamento_id, $salario, $tipo_pago, $fecha_inicio);
    
    if ($stmt->execute()) {
        $employee_id = $stmt->insert_id; // Get the new employee ID

        // Create the user account linked to the employee
        $user_stmt = $conn->prepare("INSERT INTO users (username, password, role_id, employee_id) VALUES (?, ?, ?, ?)");
        $user_stmt->bind_param("ssii", $username, $password, $role_id, $employee_id);

        if ($user_stmt->execute()) {
            echo "<p>Empleado y usuario creados exitosamente.</p>";
        } else {
            echo "<p>Error al crear el usuario: " . $user_stmt->error . "</p>";
        }
        $user_stmt->close();
    } else {
        echo "<p>Error al crear el empleado: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Empleado</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include('sidebar.php'); ?>
    <div class="main">
        <h1>Agregar Nuevo Empleado</h1>
        <form action="empleados_agregar.php" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="puesto_id">Puesto:</label>
                <select id="puesto_id" name="puesto_id" required>
                    <option value="">Selecciona un puesto</option>
                    <?php
                    $puestos = $conn->query("SELECT id, nombre FROM puestos");
                    while ($row = $puestos->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="departamento_id">Departamento:</label>
                <select id="departamento_id" name="departamento_id" required>
                    <option value="">Selecciona un departamento</option>
                    <?php
                    $departamentos = $conn->query("SELECT id, nombre FROM departamentos");
                    while ($row = $departamentos->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="salario">Salario:</label>
                <input type="number" id="salario" name="salario" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="tipo_pago">Tipo de Pago:</label>
                <select id="tipo_pago" name="tipo_pago" required>
                    <option value="Diario">Diario</option>
                    <option value="Semanal">Semanal</option>
                    <option value="Mensual">Mensual</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_inicio">Fecha de Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" required>
            </div>
            <h2>Crear Cuenta de Usuario</h2>
            <div class="form-group">
                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role_id">Rol:</label>
                <select id="role_id" name="role_id" required>
                    <option value="">Selecciona un rol</option>
                    <?php
                    $roles = $conn->query("SELECT id, nombre FROM roles");
                    while ($row = $roles->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn">Guardar</button>
        </form>
    </div>
</body>
</html>
