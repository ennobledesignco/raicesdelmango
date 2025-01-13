<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Editar Puestos';
include('header.php');
?>

<div class="main">
    <h1>Lista de Puestos</h1>
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
            $puestos = $conn->query("SELECT id, nombre FROM puestos");
            while ($row = $puestos->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nombre']}</td>
                    <td>
                        <a href='puestos_editar_detalle.php?id={$row['id']}' class='btn'>Editar</a>
                        <a href='puestos_eliminar.php?id={$row['id']}' class='btn-danger'>Eliminar</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
