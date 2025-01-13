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
    <title>Dashboard - Resumen</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=6.0">
    <script src="js/scripts.js?v=2.0"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include('sidebar.php'); ?>

    <div class="main">
        <h1>Resumen General</h1>
        <p>Bienvenido a CRM Mango. Este es el resumen general de los datos del sistema.</p>

        <!-- Summary Cards -->
        <div class="card">
            <div class="card-header">Total Cajas Recibidas</div>
            <p>Placeholder for total boxes received.</p>
        </div>
        <div class="card">
            <div class="card-header">Total Ventas</div>
            <p>Placeholder for total sales.</p>
        </div>
        <div class="card">
            <div class="card-header">Total Gastos</div>
            <p>Placeholder for total expenses.</p>
        </div>
        <div class="card">
            <div class="card-header">Total Nóminas</div>
            <p>Placeholder for total payroll.</p>
        </div>
        <div class="card">
            <div class="card-header">Total Empleados</div>
            <p>Placeholder for total employees.</p>
        </div>
    </div>
</body>
</html>
