<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role_id'] !== 1) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Lista de Usuarios';

$query = "SELECT u.id, u.username, u.email, u.telefono, r.nombre AS role_name 
          FROM users u 
          LEFT JOIN roles r ON u.role_id = r.id";
$result = $conn->query($query);

include('header.php');
?>
<div class="main">
    <h1>Lista de Usuarios</h1>
    <a href="user_create.php" class="btn">Agregar Usuario</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de Usuario</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($row['role_name']); ?></td>
                        <td>
                            <a href="user_edit.php?id=<?php echo $row['id']; ?>" class="btn-warning">Editar</a>
                            <form action="user_delete.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;">No hay usuarios registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include('footer.php'); ?>
