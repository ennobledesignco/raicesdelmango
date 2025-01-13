<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Editar Puesto';

if (!isset($_GET['id'])) {
    die('ID de puesto no especificado.');
}

$id = $_GET['id'];

// Fetch position details
$stmt = $conn->prepare("SELECT id, nombre FROM puestos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$puesto = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];

    $stmt = $conn->prepare("UPDATE puestos SET nombre = ? WHERE id = ?");
    $stmt->bind_param("si", $nombre, $id);

    if ($stmt->execute()) {
        echo "<p>Puesto actualizado exitosamente.</p>";
    } else {
        echo "<p>Error al actualizar puesto: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
include('header.php');
?>

<div class="main">
    <h1>Editar Puesto</h1>
    <form action="" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre del Puesto:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $puesto['nombre']; ?>" required>
        </div>
        <button type="submit" class="btn">Actualizar Puesto</button>
    </form>
</div>

<?php include('footer.php'); ?>
