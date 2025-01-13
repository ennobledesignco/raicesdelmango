<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include('php/config.php');
$page_title = 'Ventas';

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM ventas WHERE id = ?");
    if (!$stmt) {
        die("Error preparando la consulta de eliminación: " . $conn->error);
    }
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "Venta eliminada correctamente.";
    } else {
        $error = "Error al eliminar la venta: " . $stmt->error;
    }
}

// Fetch ventas
$query = "SELECT v.*, h.nombre AS huerta_nombre FROM ventas v LEFT JOIN huertas h ON v.huerta_id = h.id ORDER BY fecha DESC";
$result = $conn->query($query);

if (!$result) {
    die("Error al recuperar las ventas: " . $conn->error);
}

include('header.php');
?>

<div class="main">
    <h1>Ventas</h1>
    <a href="ventas_agregar.php" class="btn">Agregar Venta</a>

    <?php if (isset($message)): ?>
        <p class="success"><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Huerta</th>
                <th>Cliente</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Descuento</th>
                <th>Total</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['huerta_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['cliente_nombre']); ?></td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td>$<?php echo number_format($row['precio'], 2); ?></td>
                        <td>$<?php echo number_format($row['descuento'], 2); ?></td>
                        <td>$<?php echo number_format(($row['precio'] - $row['descuento']) * $row['cantidad'], 2); ?></td>
                        <td><?php echo $row['fecha']; ?></td>
                        <td>
                            <form action="ventas.php" method="POST" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar esta venta?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center;">No se encontraron ventas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include('footer.php'); ?>
