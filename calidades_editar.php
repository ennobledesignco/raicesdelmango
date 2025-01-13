<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Editar Calidad';

// Ensure calidad ID is provided
if (!isset($_GET['id'])) {
    header('Location: calidades.php');
    exit;
}

$id = intval($_GET['id']);

// Fetch the calidad details
$stmt = $conn->prepare("SELECT id, nombre FROM calidades WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$calidad = $result->fetch_assoc();

if (!$calidad) {
    echo "<p>Calidad no encontrada.</p>";
    exit;
}

$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];

    $stmt = $conn->prepare("UPDATE calidades SET nombre = ? WHERE id = ?");
    $stmt->bind_param("si", $nombre, $id);

    if ($stmt->execute()) {
        header('Location: calidades.php?updated=1');
        exit;
    } else {
        echo "<p>Error al actualizar la calidad: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

include('header.php');
?>

<div class="main">
    <h1>Editar Calidad</h1>
    <form action="calidades_editar.php?id=<?php echo $id; ?>" method="POST">
        <!-- Nombre -->
        <div class="form-group">
            <label for="nombre">Nombre de la Calidad:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($calidad['nombre']); ?>" required>
        </div>

        <button type="submit" class="btn">Actualizar Calidad</button>
    </form>
</div>

<?php include('footer.php'); ?>
