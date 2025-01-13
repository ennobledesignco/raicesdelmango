<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Cambiar Contraseña';
include('header.php');

$user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session

// Handle form submission for password update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_actual = $_POST['password_actual'];
    $password_nuevo = $_POST['password_nuevo'];
    $password_confirmar = $_POST['password_confirmar'];

    // Check if new passwords match
    if ($password_nuevo !== $password_confirmar) {
        $error = "Las contraseñas nuevas no coinciden.";
    } else {
        // Verify current password
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (password_verify($password_actual, $user['password'])) {
            // Update to new password
            $password_hash = password_hash($password_nuevo, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $password_hash, $user_id);

            if ($stmt->execute()) {
                header('Location: cambiar_password.php?updated=1');
                exit;
            } else {
                $error = "Error al actualizar la contraseña.";
            }

            $stmt->close();
        } else {
            $error = "La contraseña actual no es correcta.";
        }
    }
}
?>

<div class="main">
    <h1>Cambiar Contraseña</h1>
    <?php if (isset($_GET['updated'])): ?>
        <p class="success">Contraseña actualizada correctamente.</p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="cambiar_password.php" method="POST">
        <!-- Current Password -->
        <div class="form-group">
            <label for="password_actual">Contraseña Actual:</label>
            <input type="password" id="password_actual" name="password_actual" required>
        </div>

        <!-- New Password -->
        <div class="form-group">
            <label for="password_nuevo">Nueva Contraseña:</label>
            <input type="password" id="password_nuevo" name="password_nuevo" required>
        </div>

        <!-- Confirm New Password -->
        <div class="form-group">
            <label for="password_confirmar">Confirmar Nueva Contraseña:</label>
            <input type="password" id="password_confirmar" name="password_confirmar" required>
        </div>

        <button type="submit" class="btn">Cambiar Contraseña</button>
    </form>
</div>

<?php include('footer.php'); ?>
