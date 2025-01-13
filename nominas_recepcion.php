<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Nóminas por Centros';
include('header.php');
?>

<div class="main">
    <h1>Nóminas por Centros</h1>

    <!-- Filter Section -->
    <form method="GET" action="nominas_centros.php" class="form-filter">
        <label for="centro_id">Seleccionar Centro:</label>
        <select id="centro_id" name="centro_id">
            <option value="">Todos los Centros</option>
            <?php
            $centros = $conn->query("SELECT id, nombre FROM centros_abastecimiento");
            while ($row = $centros->fetch_assoc()) {
                $selected = ($_GET['centro_id'] ?? '') == $row['id'] ? 'selected' : '';
                echo "<option value='{$row['id']}' $selected>{$row['nombre']}</option>";
            }
            ?>
        </select>

        <label for="start_date">Desde:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo $_GET['start_date'] ?? ''; ?>">

        <label for="end_date">Hasta:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo $_GET['end_date'] ?? ''; ?>">

        <button type="submit" class="btn">Filtrar</button>
    </form>

    <!-- Table Section -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Empleado</th>
                <th>Centro</th>
                <th>Fecha</th>
                <th>Tipo de Pago</th>
                <th>Cantidad</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Build query with filters
            $filters = [];
            if (!empty($_GET['centro_id'])) {
                $filters[] = "n.centro_id = " . intval($_GET['centro_id']);
            }
            if (!empty($_GET['start_date'])) {
                $filters[] = "n.fecha >= '" . $conn->real_escape_string($_GET['start_date']) . "'";
            }
            if (!empty($_GET['end_date'])) {
                $filters[] = "n.fecha <= '" . $conn->real_escape_string($_GET['end_date']) . "'";
            }
            $where_clause = $filters ? 'WHERE ' . implode(' AND ', $filters) : '';

            $query = "
                SELECT n.id, e.nombre AS empleado, ca.nombre AS centro, n.fecha, n.tipo_pago, 
                       n.cantidad, n.descripcion
                FROM nominas n
                LEFT JOIN empleados e ON n.empleado_id = e.id
                LEFT JOIN centros_abastecimiento ca ON n.centro_id = ca.id
                $where_clause
                ORDER BY n.fecha DESC
            ";

            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['empleado']}</td>
                        <td>{$row['centro']}</td>
                        <td>{$row['fecha']}</td>
                        <td>{$row['tipo_pago']}</td>
                        <td>{$row['cantidad']}</td>
                        <td>{$row['descripcion']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No se encontraron nóminas para los centros seleccionados.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
