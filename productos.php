<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Listado de Productos';
include('header.php');
?>

<div class="main">
    <h1>Listado de Productos</h1>
    <a href="productos_agregar.php" class="btn">Agregar Producto</a>

    <!-- Table Section -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "
                SELECT p.id, p.nombre, p.descripcion, c.nombre AS categoria
                FROM productos p
                LEFT JOIN categorias_productos c ON p.categoria_id = c.id
                ORDER BY p.nombre ASC
            ";

            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nombre']}</td>
                        <td>{$row['descripcion']}</td>
                        <td>{$row['categoria']}</td>
                        <td>
                            <a href='productos_editar.php?id={$row['id']}' class='btn'>Editar</a>
                            <a href='productos_eliminar.php?id={$row['id']}' class='btn-danger' onclick='return confirm(\"¿Está seguro de eliminar este producto?\");'>Eliminar</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No se encontraron productos.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
