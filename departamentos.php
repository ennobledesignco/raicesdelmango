<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Gestión de Departamentos';
include('header.php');
?>

<div class="main">
    <h1>Gestión de Departamentos</h1>
    <a href="departamentos_agregar.php" class="btn">Agregar Nuevo Departamento</a>

    <h2>Lista de Departamentos</h2>
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
            // Fetch all departments
            $departamentos = $conn->query("SELECT id, nombre FROM departamentos");

            if ($departamentos && $departamentos->num_rows > 0) {
                // Display each department in a table row
                while ($row = $departamentos->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nombre']}</td>
                        <td>
                            <a href='departamentos_editar_detalle.php?id={$row['id']}' class='btn'>Editar</a>
                            <a href='departamentos_eliminar.php?id={$row['id']}' class='btn-danger'>Eliminar</a>
                        </td>
                    </tr>";
                }
            } else {
                // If no departments are found, display a message
                echo "<tr><td colspan='3'>No hay departamentos disponibles.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
