<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Agregar Gasto';
include('header.php');
?>

<div class="main">
    <h1>Agregar Gasto</h1>
    <form action="gastos_agregar.php" method="POST">
        <!-- Fecha -->
        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" required>
        </div>

        <!-- Huerta -->
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

        <!-- Centro de Recepción -->
        <div class="form-group">
            <label for="centro_id">Centro de Recepción:</label>
            <select id="centro_id" name="centro_id">
                <option value="">Seleccione un centro</option>
                <?php
                $centros = $conn->query("SELECT id, nombre FROM centros_abastecimiento");
                while ($row = $centros->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Categoría -->
        <div class="form-group">
            <label for="categoria_id">Categoría de Gasto:</label>
            <select id="categoria_id" name="categoria_id" required>
                <option value="">Seleccione una categoría</option>
                <?php
                $categorias = $conn->query("SELECT id, nombre FROM categorias_gastos");
                while ($row = $categorias->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Monto -->
        <div class="form-group">
            <label for="monto">Monto:</label>
            <input type="number" id="monto" name="monto" step="0.01" required>
        </div>

        <!-- Descripción -->
        <div class="form-group">
            <label for="descripcion">Descripción (Opcional):</label>
            <textarea id="descripcion" name="descripcion"></textarea>
        </div>

        <button type="submit" class="btn">Registrar Gasto</button>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];
    $huerta_id = $_POST['huerta_id'] ?: null;
    $centro_id = $_POST['centro_id'] ?: null;
    $categoria_id = $_POST['categoria_id'];
    $monto = $_POST['monto'];
    $descripcion = $_POST['descripcion'];

    $stmt = $conn->prepare("
        INSERT INTO gastos (fecha, huerta_id, centro_id, categoria_id, monto, descripcion)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("siiids", $fecha, $huerta_id, $centro_id, $categoria_id, $monto, $descripcion);

    if ($stmt->execute()) {
        header('Location: listado_gastos.php?gasto_added=1');
        exit;
    } else {
        echo "<p>Error al registrar el gasto: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<?php include('footer.php'); ?>
