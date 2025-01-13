<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Listado de Calidades';
include('header.php');
?>

<div class="main">
    <h1>Listado de Calidades</h1>
    <a href="calidades_agregar.php" class="btn">Agregar Calidad</a>

    <!-- Table Section -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT id, nombre FROM calidades ORDER BY nombre ASC";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nombre']}</td>
                        <td>
                            <a href='calidades_editar.php?id={$row['id']}' class='btn'>Editar</a>
                            <a href='calidades_eliminar.php?id={$row['id']}' class='btn-danger' onclick='return confirm(\"¿Está seguro de eliminar esta calidad?\");'>Eliminar</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No se encontraron calidades.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
