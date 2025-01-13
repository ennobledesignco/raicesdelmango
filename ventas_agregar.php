<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Agregar Venta';
include('header.php');
?>

<div class="main">
    <h1>Agregar Venta</h1>
    <form action="ventas_agregar.php" method="POST">
        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" required>
        </div>
        <div class="form-group">
            <label for="huerta_id">Huerta:</label>
            <select id="huerta_id" name="huerta_id">
                <option value="">Seleccione una huerta</option>
                <?php
                $huertas = $conn->query("SELECT id, nombre FROM huertas");
                while ($row = $huertas->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="producto_id">Producto:</label>
            <select id="producto_id" name="producto_id" required>
                <option value="">Seleccione un producto</option>
                <?php
                $productos = $conn->query("SELECT id, nombre FROM productos");
                while ($row = $productos->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="precio_unitario">Precio Unitario:</label>
            <input type="number" id="precio_unitario" name="precio_unitario" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción (Opcional):</label>
            <textarea id="descripcion" name="descripcion"></textarea>
        </div>
        <button type="submit" class="btn">Registrar Venta</button>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];
    $huerta_id = $_POST['huerta_id'] ?: null;
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $precio_unitario = $_POST['precio_unitario'];
    $descripcion = $_POST['descripcion'];

    $stmt = $conn->prepare("
        INSERT INTO ventas (fecha, huerta_id, producto_id, cantidad, precio_unitario, descripcion)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("siiids", $fecha, $huerta_id, $producto_id, $cantidad, $precio_unitario, $descripcion);

    if ($stmt->execute()) {
        header('Location: finanzas_resumen.php?venta_added=1');
        exit;
    } else {
        echo "<p>Error al registrar la venta: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<?php include('footer.php'); ?>
