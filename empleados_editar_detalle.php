<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Editar Detalles de Empleado';

if (!isset($_GET['id'])) {
    die('ID de empleado no especificado.');
}

$id = $_GET['id'];

// Fetch employee details
$stmt = $conn->prepare("
    SELECT id, nombre, puesto_id, departamento_id, supervisor_id, fecha_inicio, salario, tipo_pago 
    FROM empleados WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$empleado = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $puesto_id = $_POST['puesto_id'];
    $departamento_id = $_POST['departamento_id'];
    $supervisor_id = $_POST['supervisor_id'] ?: null;
    $fecha_inicio = $_POST['fecha_inicio'];
    $salario = $_POST['salario'];
    $tipo_pago = $_POST['tipo_pago'];

    $stmt = $conn->prepare("
        UPDATE empleados 
        SET nombre = ?, puesto_id = ?, departamento_id = ?, supervisor_id = ?, fecha_inicio = ?, salario = ?, tipo_pago = ? 
        WHERE id = ?
    ");
    $stmt->bind_param("siissdsi", $nombre, $puesto_id, $departamento_id, $supervisor_id, $fecha_inicio, $salario, $tipo_pago, $id);

    if ($stmt->execute()) {
        echo "<p>Empleado actualizado exitosamente.</p>";
    } else {
        echo "<p>Error al actualizar empleado: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
include('header.php');
?>

<div class="main">
    <h1>Editar Detalles de Empleado</h1>
    <form action="" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre del Empleado:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $empleado['nombre']; ?>" required>
        </div>
        <div class="form-group">
            <label for="puesto_id">Puesto:</label>
            <select id="puesto_id" name="puesto_id" required>
                <?php
                $puestos = $conn->query("SELECT id, nombre FROM puestos");
                while ($row = $puestos->fetch_assoc()) {
                    $selected = $row['id'] == $empleado['puesto_id'] ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="departamento_id">Departamento:</label>
            <select id="departamento_id" name="departamento_id" required>
                <?php
                $departamentos = $conn->query("SELECT id, nombre FROM departamentos");
                while ($row = $departamentos->fetch_assoc()) {
                    $selected = $row['id'] == $empleado['departamento_id'] ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="supervisor_id">Supervisor:</label>
            <select id="supervisor_id" name="supervisor_id">
                <option value="">Ninguno</option>
                <?php
                $supervisors = $conn->query("SELECT id, nombre FROM empleados WHERE id != $id");
                while ($row = $supervisors->fetch_assoc()) {
                    $selected = $row['id'] == $empleado['supervisor_id'] ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo $empleado['fecha_inicio']; ?>" required>
        </div>
        <div class="form-group">
            <label for="salario">Salario:</label>
            <input type="number" id="salario" name="salario" step="0.01" value="<?php echo $empleado['salario']; ?>" required>
        </div>
        <div class="form-group">
            <label for="tipo_pago">Tipo de Pago:</label>
            <select id="tipo_pago" name="tipo_pago" required>
                <option value="Diario" <?php echo $empleado['tipo_pago'] == 'Diario' ? 'selected' : ''; ?>>Diario</option>
                <option value="Semanal" <?php echo $empleado['tipo_pago'] == 'Semanal' ? 'selected' : ''; ?>>Semanal</option>
                <option value="Mensual" <?php echo $empleado['tipo_pago'] == 'Mensual' ? 'selected' : ''; ?>>Mensual</option>
            </select>
        </div>
        <button type="submit" class="btn">Actualizar Empleado</button>
    </form>
</div>

<?php include('footer.php'); ?>
