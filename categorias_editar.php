<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Editar Categoría';

// Ensure category ID is provided
if (!isset($_GET['id'])) {
    header('Location: categorias.php');
    exit;
}

$id = intval($_GET['id']);

// Fetch the category details
$stmt = $conn->prepare("SELECT id, nombre FROM categorias_productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$categoria = $result->fetch_assoc();

if (!$categoria) {
    echo "<p>Categoría no encontrada.</p>";
    exit;
}

$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];

    $stmt = $conn->prepare("UPDATE categorias_productos SET nombre = ? WHERE id = ?");
    $stmt->bind_param("si", $nombre, $id);

    if ($stmt->execute()) {
        header('Location: categorias.php?updated=1');
        exit;
    } else {
        echo "<p>Error al actualizar la categoría: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

include('header.php');
?>

<div class="main">
    <h1>Editar Categoría</h1>
    <form action="categorias_editar.php?id=<?php echo $id; ?>" method="POST">
        <!-- Nombre -->
        <div class="form-group">
            <label for="nombre">Nombre de la Categoría:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($categoria['nombre']); ?>" required>
        </div>

        <button type="submit" class="btn">Actualizar Categoría</button>
    </form>
</div>

<?php include('footer.php'); ?>
