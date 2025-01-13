<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include('php/config.php');

// Debug URL parameters
echo "<pre>";
print_r($_GET);
echo "</pre>";

// Validate reception ID
if (!isset($_GET['recepcion_id']) || empty($_GET['recepcion_id'])) {
    header("Location: recepcion_list.php?error=missing_id");
    exit;
}

$recepcion_id = intval($_GET['recepcion_id']);

// Fetch reception details
$query_recepcion = "SELECT * FROM recepciones WHERE id = ?";
$stmt_recepcion = $conn->prepare($query_recepcion);
$stmt_recepcion->bind_param("i", $recepcion_id);
$stmt_recepcion->execute();
$result_recepcion = $stmt_recepcion->get_result();
$recepcion = $result_recepcion->fetch_assoc();

if (!$recepcion) {
    die("Recepción no encontrada.");
}

// Fetch notes for this reception
$query_notas = "SELECT * FROM notas_recepcion WHERE recepcion_id = ?";
$stmt_notas = $conn->prepare($query_notas);
$stmt_notas->bind_param("i", $recepcion_id);
$stmt_notas->execute();
$result_notas = $stmt_notas->get_result();

include('header.php');
?>

<div class="main">
    <h1>Resumen de Recepción</h1>
    <div class="card">
        <h3>Detalles de la Recepción</h3>
        <p><strong>ID de Recepción:</strong> <?php echo $recepcion['id']; ?></p>
        <p><strong>Fecha de Recepción:</strong> <?php echo $recepcion['fecha_recepcion']; ?></p>
        <p><strong>Huerta ID:</strong> <?php echo $recepcion['huerta_id']; ?></p>
        <p><strong>Cantidad de Cajas:</strong> <?php echo $recepcion['cantidad_cajas']; ?></p>
    </div>

    <div class="card">
        <h3>Notas de Recepción</h3>
        <a href="nota_recepcion_agregar.php?recepcion_id=<?php echo $recepcion_id; ?>" class="btn">Agregar Nota</a>
        <table>
            <thead>
                <tr>
                    <th>Calidad</th>
                    <th>Cajas</th>
                    <th>Kilogramos</th>
                    <th>Precio</th>
                    <th>Importe</th>
                    <th>Porcentaje</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_notas->num_rows > 0): ?>
                    <?php while ($nota = $result_notas->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($nota['calidad']); ?></td>
                            <td><?php echo $nota['cajas']; ?></td>
                            <td><?php echo $nota['kg']; ?></td>
                            <td>$<?php echo number_format($nota['precio'], 2); ?></td>
                            <td>$<?php echo number_format($nota['importe'], 2); ?></td>
                            <td><?php echo $nota['porcentaje']; ?>%</td>
                            <td>
                                <a href="nota_recepcion_editar.php?id=<?php echo $nota['id']; ?>" class="btn-warning">Editar</a>
                                <form action="nota_recepcion_eliminar.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $nota['id']; ?>">
                                    <button type="submit" class="btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta nota?');">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No se encontraron notas para esta recepción.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>
