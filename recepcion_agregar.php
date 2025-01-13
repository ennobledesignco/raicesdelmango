<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include('php/config.php');
$page_title = 'Agregar Recepción';
include('header.php');
?>

<div class="main">
    <h1>Agregar Nueva Recepción</h1>
    <form action="recepcion_agregar.php" method="POST">
        <!-- Form Fields -->
        <div class="form-group">
            <label for="huerta_id">Huerta:</label>
            <select id="huerta_id" name="huerta_id" required>
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
            <label for="fecha_corte">Fecha de Corte:</label>
            <input type="date" id="fecha_corte" name="fecha_corte" required>
        </div>
        <div class="form-group">
            <label for="fecha_recepcion">Fecha de Recepción:</label>
            <input type="date" id="fecha_recepcion" name="fecha_recepcion" required>
        </div>
        <div class="form-group">
            <label for="cantidad_cajas">Cantidad de Cajas:</label>
            <input type="number" id="cantidad_cajas" name="cantidad_cajas" required>
        </div>
        <button type="submit" class="btn">Registrar Recepción</button>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $huerta_id = $_POST['huerta_id'];
    $fecha_corte = $_POST['fecha_corte'];
    $fecha_recepcion = $_POST['fecha_recepcion'];
    $cantidad_cajas = $_POST['cantidad_cajas'];

    $stmt = $conn->prepare("
        INSERT INTO recepciones 
        (huerta_id, fecha_corte, fecha_recepcion, cantidad_cajas)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("issi", $huerta_id, $fecha_corte, $fecha_recepcion, $cantidad_cajas);

    if ($stmt->execute()) {
        // Redirect with a success message
        header('Location: recepcion_list.php?message=Recepción agregada exitosamente.');
        exit;
    } else {
        echo "<p>Error al registrar la recepción: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<?php include('footer.php'); ?>
