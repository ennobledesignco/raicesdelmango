<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Agregar Departamento';
include('header.php');
?>

<div class="main">
    <h1>Agregar Departamento</h1>
    <form action="departamentos_agregar.php" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre del Departamento:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <button type="submit" class="btn">Agregar Departamento</button>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $stmt = $conn->prepare("INSERT INTO departamentos (nombre) VALUES (?)");
    $stmt->bind_param("s", $nombre);

    if ($stmt->execute()) {
        echo "<p>Departamento agregado exitosamente.</p>";
    } else {
        echo "<p>Error al agregar departamento: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<?php include('footer.php'); ?>
