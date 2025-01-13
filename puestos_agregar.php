<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Agregar Puesto';
include('header.php');
?>

<div class="main">
    <h1>Agregar Puesto</h1>
    <form action="puestos_agregar.php" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre del Puesto:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <button type="submit" class="btn">Agregar Puesto</button>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $stmt = $conn->prepare("INSERT INTO puestos (nombre) VALUES (?)");
    $stmt->bind_param("s", $nombre);

    if ($stmt->execute()) {
        echo "<p>Puesto agregado exitosamente.</p>";
    } else {
        echo "<p>Error al agregar puesto: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<?php include('footer.php'); ?>
