<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Resumen Financiero';
include('header.php');
?>

<div class="main">
    <h1>Resumen Financiero</h1>

    <!-- Filters Section -->
    <form method="GET" action="resumen_financiero.php" class="form-filter">
        <label for="start_date">Desde:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo $_GET['start_date'] ?? ''; ?>">

        <label for="end_date">Hasta:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo $_GET['end_date'] ?? ''; ?>">

        <button type="submit" class="btn">Filtrar</button>
    </form>

    <!-- Financial Summary -->
    <div class="card">
        <h2>Resumen General</h2>
        <?php
        // Define date range filters
        $filters = [];
        if (!empty($_GET['start_date'])) {
            $filters[] = "fecha >= '" . $conn->real_escape_string($_GET['start_date']) . "'";
        }
        if (!empty($_GET['end_date'])) {
            $filters[] = "fecha <= '" . $conn->real_escape_string($_GET['end_date']) . "'";
        }
        $where_clause = $filters ? 'WHERE ' . implode(' AND ', $filters) : '';

        // Total Ventas
        $ventas_query = "SELECT SUM(total) AS total_ventas FROM ventas $where_clause";
        $ventas_result = $conn->query($ventas_query);
        $total_ventas = $ventas_result->fetch_assoc()['total_ventas'] ?? 0;

        // Total Ingresos
        $ingresos_query = "SELECT SUM(monto) AS total_ingresos FROM ingresos $where_clause";
        $ingresos_result = $conn->query($ingresos_query);
        $total_ingresos = $ingresos_result->fetch_assoc()['total_ingresos'] ?? 0;

        // Total Gastos
        $gastos_query = "SELECT SUM(monto) AS total_gastos FROM gastos $where_clause";
        $gastos_result = $conn->query($gastos_query);
        $total_gastos = $gastos_result->fetch_assoc()['total_gastos'] ?? 0;

        // Net Profit
        $net_profit = ($total_ventas + $total_ingresos) - $total_gastos;

        echo "<p><strong>Total Ventas:</strong> $" . number_format($total_ventas, 2) . "</p>";
        echo "<p><strong>Total Ingresos:</strong> $" . number_format($total_ingresos, 2) . "</p>";
        echo "<p><strong>Total Gastos:</strong> $" . number_format($total_gastos, 2) . "</p>";
        echo "<p><strong>Utilidad Neta:</strong> $" . number_format($net_profit, 2) . "</p>";
        ?>
    </div>

    <!-- Detailed Breakdown -->
    <div class="card">
        <h2>Detalle por Categoría</h2>
        <?php
        // Breakdown of expenses by category
        $categories_query = "
            SELECT c.nombre AS categoria, SUM(g.monto) AS total
            FROM gastos g
            LEFT JOIN categorias_gastos c ON g.categoria_id = c.id
            $where_clause
            GROUP BY g.categoria_id
            ORDER BY total DESC
        ";
        $categories_result = $conn->query($categories_query);

        if ($categories_result && $categories_result->num_rows > 0) {
            echo "<table>
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>Total Gastado</th>
                    </tr>
                </thead>
                <tbody>";
            while ($row = $categories_result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['categoria']}</td>
                    <td>$" . number_format($row['total'], 2) . "</td>
                </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No se encontraron datos para las categorías.</p>";
        }
        ?>
    </div>
</div>

<?php include('footer.php'); ?>
