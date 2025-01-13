<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Agregar Huerta';
include('header.php');
?>

<div class="main">
    <h1>Agregar Nueva Huerta</h1>
    <form action="huertas_agregar.php" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre de la Huerta:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="ubicacion">Ubicación:</label>
            <input type="text" id="ubicacion" name="ubicacion" required>
        </div>
        <button type="submit" class="btn">Agregar Huerta</button>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $ubicacion = $_POST['ubicacion'];

    $stmt = $conn->prepare("INSERT INTO huertas (nombre, ubicacion) VALUES (?, ?)");
    $stmt->bind_param("ss", $nombre, $ubicacion);

    if ($stmt->execute()) {
        echo "<p>Huerta agregada exitosamente.</p>";
    } else {
        echo "<p>Error al agregar huerta: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<?php include('footer.php'); ?>
