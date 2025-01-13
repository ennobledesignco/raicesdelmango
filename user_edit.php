<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role_id'] !== 1) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Editar Usuario';

if (!isset($_GET['id'])) {
    die("ID de usuario no especificado.");
}
$user_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $role_id = $_POST['role_id'];

    $query = "UPDATE users SET username = ?, email = ?, telefono = ?, role_id = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssii", $username, $email, $telefono, $role_id, $user_id);

    if ($stmt->execute()) {
        header('Location: user_list.php?message=Usuario actualizado exitosamente');
        exit;
    } else {
        $error = "Error al actualizar el usuario: " . $stmt->error;
    }
}

$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$roles = $conn->query("SELECT id, nombre FROM roles");
include('header.php');
?>
<div class="main">
    <h1>Editar Usuario</h1>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="user_edit.php?id=<?php echo $user_id; ?>" method="POST">
        <div class="form-group">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($user['telefono']); ?>">
        </div>
        <div class="form-group">
            <label for="role_id">Rol:</label>
            <select id="role_id" name="role_id" required>
                <?php while ($role = $roles->fetch_assoc()): ?>
                    <option value="<?php echo $role['id']; ?>" <?php echo $role['id'] == $user['role_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($role['nombre']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn">Actualizar Usuario</button>
    </form>
</div>
<?php include('footer.php'); ?>
