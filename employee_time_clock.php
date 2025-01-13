<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');

// Ensure employee ID is set in the session
if (!isset($_SESSION['employee_id'])) {
    die("Error: Employee ID not found in session.");
}

$employee_id = $_SESSION['employee_id'];

// Handle clock-in or clock-out actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'clock_in') {
        // Insert clock-in time
        $stmt = $conn->prepare("INSERT INTO employee_time_logs (employee_id, login_time) VALUES (?, NOW())");
        $stmt->bind_param("i", $employee_id);
        if ($stmt->execute()) {
            echo "<p>Entrada registrada exitosamente.</p>";
        } else {
            echo "<p>Error al registrar la entrada: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } elseif ($action === 'clock_out') {
        // Update the latest record with clock-out time
        $stmt = $conn->prepare("UPDATE employee_time_logs SET logout_time = NOW() WHERE employee_id = ? AND logout_time IS NULL");
        $stmt->bind_param("i", $employee_id);
        if ($stmt->execute()) {
            echo "<p>Salida registrada exitosamente.</p>";
        } else {
            echo "<p>Error al registrar la salida: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Tiempo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main Content -->
    <div class="main">
        <h1>Registro de Tiempo</h1>
        <p>Usa los botones para registrar tu entrada o salida.</p>
        <form action="employee_time_clock.php" method="POST">
            <button type="submit" name="action" value="clock_in" class="btn">Registrar Entrada</button>
            <button type="submit" name="action" value="clock_out" class="btn">Registrar Salida</button>
        </form>
    </div>
</body>
</html>
