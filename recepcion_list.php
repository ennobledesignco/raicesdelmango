<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include('php/config.php');
$page_title = 'Listado de Recepciones';

// Fetch all receptions
$query = "SELECT r.id, h.nombre AS huerta_nombre, p.nombre AS productor_nombre, 
                 r.fecha_recepcion, r.cantidad_cajas, r.numero_cajas_vacias 
          FROM recepciones r 
          LEFT JOIN huertas h ON r.huerta_id = h.id
          LEFT JOIN productores p ON r.productor_id = p.id
          ORDER BY r.fecha_recepcion DESC";
$result = $conn->query($query);

include('header.php');
?>

<div class="main">
    <h1>Listado de Recepciones</h1>
    <a href="recepcion_agregar.php" class="btn">Agregar Recepción</a>

    <!-- Display success message -->
    <?php if (isset($_GET['message'])): ?>
        <p class="success"><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Huerta</th>
                <th>Productor</th>
                <th>Fecha de Recepción</th>
                <th>Cajas Recibidas</th>
                <th>Cajas Vacías</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['huerta_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['productor_nombre']); ?></td>
                        <td><?php echo $row['fecha_recepcion']; ?></td>
                        <td><?php echo $row['cantidad_cajas']; ?></td>
                        <td><?php echo $row['numero_cajas_vacias']; ?></td>
                        <td>
                            <a href="recepcion_resumen.php?recepcion_id=<?php echo $row['id']; ?>" class="btn">Ver Resumen</a>
                            <a href="recepcion_editar.php?id=<?php echo $row['id']; ?>" class="btn-warning">Editar</a>
                            <form action="recepcion_eliminar.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta recepción?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No se encontraron recepciones.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
