<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role_id'] !== 1) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Crear Usuario';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $role_id = $_POST['role_id'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, email, telefono, password, role_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssdi", $username, $email, $telefono, $password, $role_id);

    if ($stmt->execute()) {
        header('Location: user_list.php?message=Usuario creado exitosamente');
        exit;
    } else {
        $error = "Error al crear el usuario: " . $stmt->error;
    }
}

$roles = $conn->query("SELECT id, nombre FROM roles");
include('header.php');
?>
<div class="main">
    <h1>Crear Usuario</h1>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="user_create.php" method="POST">
        <div class="form-group">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono">
        </div>
<div class="form-group">
    <label for="password">Contraseña:</label>
    <div style="position: relative;">
        <input type="password" id="password" name="password" required>
        <i class="fas fa-eye" id="toggle-password" style="position: absolute; right: 10px; top: 10px; cursor: pointer;"></i>
    </div>
</div>
        <div class="form-group">
            <label for="role_id">Rol:</label>
            <select id="role_id" name="role_id" required>
                <option value="">Seleccione un rol</option>
                <?php while ($role = $roles->fetch_assoc()): ?>
                    <option value="<?php echo $role['id']; ?>"><?php echo htmlspecialchars($role['nombre']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn">Crear Usuario</button>
    </form>
</div>
<?php include('footer.php'); ?>
<script>
    const passwordField = document.getElementById('password');
    const togglePassword = document.getElementById('toggle-password');

    togglePassword.addEventListener('click', () => {
        // Toggle password visibility
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            togglePassword.classList.remove('fa-eye');
            togglePassword.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            togglePassword.classList.remove('fa-eye-slash');
            togglePassword.classList.add('fa-eye');
        }
    });
</script>
