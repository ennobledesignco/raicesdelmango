<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Huertas Overview';
include('header.php');
?>

<div class="main">
    <h1>Huertas Overview</h1>
    <a href="huertas_agregar.php" class="btn">Agregar Nueva Huerta</a>

    <h2>Lista de Huertas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Ubicaci¨®n</th>
                <th>Total Cajas</th>
                <th>Peso Total</th>
                <th>Total Ventas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch Huertas with summarized data
            $query = "
                SELECT h.id, h.nombre, h.ubicacion, 
                       IFNULL(SUM(r.cantidad_cajas), 0) AS total_cajas,
                       IFNULL(SUM(r.peso_caja * r.cantidad_cajas), 0) AS peso_total,
                       IFNULL(SUM(v.precio - v.descuento), 0) AS total_ventas
                FROM huertas h
                LEFT JOIN recepciones r ON h.id = r.huerta_id
                LEFT JOIN ventas v ON h.id = v.huerta_id
                GROUP BY h.id
            ";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nombre']}</td>
                        <td>{$row['ubicacion']}</td>
                        <td>{$row['total_cajas']}</td>
                        <td>{$row['peso_total']}</td>
                        <td>\${$row['total_ventas']}</td>
                        <td>
                            <a href='huertas_editar.php?id={$row['id']}' class='btn'>Editar</a>
                            <a href='huertas_eliminar.php?id={$row['id']}' class='btn-danger'>Eliminar</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No hay huertas registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
