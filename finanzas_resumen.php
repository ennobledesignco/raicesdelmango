<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Resumen Financiero';
include('header.php');
?>

<div class="main">
    <h1>Resumen Financiero</h1>

    <h2>Total Ventas</h2>
    <?php
    $ventas = $conn->query("SELECT SUM(total) AS total_ventas FROM ventas");
    $row = $ventas->fetch_assoc();
    echo "<p>Total de Ventas: $" . number_format($row['total_ventas'], 2) . "</p>";
    ?>

    <h2>Total Ingresos</h2>
    <?php
    $ingresos = $conn->query("SELECT SUM(monto) AS total_ingresos FROM ingresos");
    $row = $ingresos->fetch_assoc();
    echo "<p>Total de Ingresos: $" . number_format($row['total_ingresos'], 2) . "</p>";
    ?>

    <h2>Total Gastos</h2>
    <?php
    $gastos = $conn->query("SELECT SUM(monto) AS total_gastos FROM gastos");
    $row = $gastos->fetch_assoc();
    echo "<p>Total de Gastos: $" . number_format($row['total_gastos'], 2) . "</p>";
    ?>

    <h2>Utilidad Neta</h2>
    <?php
    $utilidad = $row['total_ventas'] + $row['total_ingresos'] - $row['total_gastos'];
    echo "<p>Utilidad Neta: $" . number_format($utilidad, 2) . "</p>";
    ?>
</div>

<?php include('footer.php'); ?>
