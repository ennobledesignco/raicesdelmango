<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Agregar Producto';
include('header.php');
?>

<div class="main">
    <h1>Agregar Producto</h1>
    <form action="productos_agregar.php" method="POST">
        <!-- Nombre -->
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <!-- Descripción -->
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"></textarea>
        </div>

        <!-- Categoría -->
        <div class="form-group">
            <label for="categoria_id">Categoría:</label>
            <select id="categoria_id" name="categoria_id">
                <option value="">Seleccione una categoría</option>
                <?php
                $categorias = $conn->query("SELECT id, nombre FROM categorias_productos");
                while ($row = $categorias->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" class="btn">Registrar Producto</button>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $categoria_id = $_POST['categoria_id'] ?: null;

    $stmt = $conn->prepare("
        INSERT INTO productos (nombre, descripcion, categoria_id)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("ssi", $nombre, $descripcion, $categoria_id);

    if ($stmt->execute()) {
        header('Location: productos.php?added=1');
        exit;
    } else {
        echo "<p>Error al registrar el producto: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<?php include('footer.php'); ?>
