<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Listado de Gastos';
include('header.php');
?>

<div class="main">
    <h1>Listado de Gastos</h1>
    <a href="gastos_agregar.php" class="btn">Agregar Gasto</a>

    <!-- Filter Section -->
    <form method="GET" action="listado_gastos.php" class="form-filter">
        <label for="huerta_id">Filtrar por Huerta:</label>
        <select id="huerta_id" name="huerta_id">
            <option value="">Todas las Huertas</option>
            <?php
            $huertas = $conn->query("SELECT id, nombre FROM huertas");
            while ($row = $huertas->fetch_assoc()) {
                $selected = ($_GET['huerta_id'] ?? '') == $row['id'] ? 'selected' : '';
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
                <th>Fecha</th>
                <th>Huerta</th>
                <th>Centro</th>
                <th>Categoría</th>
                <th>Monto</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Build query with filters
            $filters = [];
            if (!empty($_GET['huerta_id'])) {
                $filters[] = "g.huerta_id = " . intval($_GET['huerta_id']);
            }
            if (!empty($_GET['start_date'])) {
                $filters[] = "g.fecha >= '" . $conn->real_escape_string($_GET['start_date']) . "'";
            }
            if (!empty($_GET['end_date'])) {
                $filters[] = "g.fecha <= '" . $conn->real_escape_string($_GET['end_date']) . "'";
            }
            $where_clause = $filters ? 'WHERE ' . implode(' AND ', $filters) : '';

            $query = "
                SELECT g.id, g.fecha, h.nombre AS huerta, ca.nombre AS centro, 
                       c.nombre AS categoria, g.monto, g.descripcion
                FROM gastos g
                LEFT JOIN huertas h ON g.huerta_id = h.id
                LEFT JOIN centros_abastecimiento ca ON g.centro_id = ca.id
                LEFT JOIN categorias_gastos c ON g.categoria_id = c.id
                $where_clause
                ORDER BY g.fecha DESC
            ";

            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['fecha']}</td>
                        <td>{$row['huerta']}</td>
                        <td>{$row['centro']}</td>
                        <td>{$row['categoria']}</td>
                        <td>{$row['monto']}</td>
                        <td>{$row['descripcion']}</td>
                        <td>
                            <a href='gastos_editar.php?id={$row['id']}' class='btn'>Editar</a>
                            <a href='gastos_eliminar.php?id={$row['id']}' class='btn-danger' onclick='return confirm(\"¿Está seguro de eliminar este gasto?\");'>Eliminar</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No se encontraron gastos para los filtros aplicados.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
