<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Agregar Categoría';
include('header.php');
?>

<div class="main">
    <h1>Agregar Categoría</h1>
    <form action="categorias_agregar.php" method="POST">
        <!-- Nombre -->
        <div class="form-group">
            <label for="nombre">Nombre de la Categoría:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <button type="submit" class="btn">Registrar Categoría</button>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];

    $stmt = $conn->prepare("INSERT INTO categorias_productos (nombre) VALUES (?)");
    $stmt->bind_param("s", $nombre);

    if ($stmt->execute()) {
        header('Location: categorias.php?added=1');
        exit;
    } else {
        echo "<p>Error al registrar la categoría: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<?php include('footer.php'); ?>
