<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Mi Cuenta';
include('header.php');

// Fetch user details
$user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session
$stmt = $conn->prepare("SELECT username, email, telefono FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<p>Error: No se encontró la información del usuario.</p>";
    exit;
}

$stmt->close();

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? null;
    $telefono = $_POST['telefono'] ?? null;

    $stmt = $conn->prepare("UPDATE users SET email = ?, telefono = ? WHERE id = ?");
    $stmt->bind_param("ssi", $email, $telefono, $user_id);

    if ($stmt->execute()) {
        header('Location: mi_cuenta.php?updated=1');
        exit;
    } else {
        echo "<p>Error al actualizar los datos: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<div class="main">
    <h1>Mi Cuenta</h1>
    <?php if (isset($_GET['updated'])): ?>
        <p class="success">Información actualizada correctamente.</p>
    <?php endif; ?>
    
    <form action="mi_cuenta.php" method="POST">
        <!-- Username -->
        <div class="form-group">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
        </div>

        <!-- Teléfono -->
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($user['telefono']); ?>">
        </div>

        <button type="submit" class="btn">Actualizar Información</button>
    </form>
</div>

<?php include('footer.php'); ?>
