<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Editar Empleados';
include('header.php');
?>

<div class="main">
    <h1>Lista de Empleados</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Puesto</th>
                <th>Departamento</th>
                <th>Supervisor</th>
                <th>Fecha de Inicio</th>
                <th>Salario</th>
                <th>Frecuencia</th> <!-- Payment Frequency -->
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $empleados = $conn->query("
                SELECT e.id, e.nombre, p.nombre AS puesto, d.nombre AS departamento, 
                       s.nombre AS supervisor, e.fecha_inicio, e.salario, e.tipo_pago
                FROM empleados e
                JOIN puestos p ON e.puesto_id = p.id
                JOIN departamentos d ON e.departamento_id = d.id
                LEFT JOIN empleados s ON e.supervisor_id = s.id
            ");
            while ($row = $empleados->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['nombre']}</td>
                    <td>{$row['puesto']}</td>
                    <td>{$row['departamento']}</td>
                    <td>" . ($row['supervisor'] ?: 'Ninguno') . "</td>
                    <td>{$row['fecha_inicio']}</td>
                    <td>{$row['salario']}</td>
                    <td>{$row['tipo_pago']}</td> <!-- Display Payment Frequency -->
                    <td>
                        <a href='empleados_editar_detalle.php?id={$row['id']}' class='btn'>Editar</a>
                        <a href='empleados_eliminar.php?id={$row['id']}' class='btn-danger'>Eliminar</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
