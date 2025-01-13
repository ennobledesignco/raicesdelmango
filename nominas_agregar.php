<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Agregar Nómina';
include('header.php');
?>

<div class="main">
    <h1>Agregar Nómina</h1>
    <form action="nominas_agregar.php" method="POST">
        <!-- Select Employee -->
        <div class="form-group">
            <label for="empleado_id">Empleado:</label>
            <select id="empleado_id" name="empleado_id" required>
                <option value="">Seleccione un empleado</option>
                <?php
                $empleados = $conn->query("SELECT id, nombre FROM empleados");
                while ($row = $empleados->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Select Huerta or Centro -->
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

        <!-- Payment Details -->
        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" required>
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad Pagada:</label>
            <input type="number" id="cantidad" name="cantidad" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="tipo_pago">Tipo de Pago:</label>
            <select id="tipo_pago" name="tipo_pago" required>
                <option value="Diario">Diario</option>
                <option value="Semanal">Semanal</option>
                <option value="Mensual">Mensual</option>
            </select>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción (Opcional):</label>
            <textarea id="descripcion" name="descripcion"></textarea>
        </div>

        <button type="submit" class="btn">Registrar Nómina</button>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empleado_id = $_POST['empleado_id'];
    $huerta_id = $_POST['huerta_id'] ?: null;
    $centro_id = $_POST['centro_id'] ?: null;
    $fecha = $_POST['fecha'];
    $cantidad = $_POST['cantidad'];
    $tipo_pago = $_POST['tipo_pago'];
    $descripcion = $_POST['descripcion'];

    $stmt = $conn->prepare("
        INSERT INTO nominas (empleado_id, huerta_id, centro_id, fecha, cantidad, tipo_pago, descripcion)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iiisdss", $empleado_id, $huerta_id, $centro_id, $fecha, $cantidad, $tipo_pago, $descripcion);

    if ($stmt->execute()) {
        header('Location: nominas_resumen.php?added=1');
        exit;
    } else {
        echo "<p>Error al registrar la nómina: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<?php include('footer.php'); ?>
