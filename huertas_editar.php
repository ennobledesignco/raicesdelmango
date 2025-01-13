<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Editar Huerta';

if (!isset($_GET['id'])) {
    header('Location: huertas_overview.php');
    exit;
}

$id = $_GET['id'];

// Fetch Huerta details
$stmt = $conn->prepare("SELECT id, nombre, ubicacion FROM huertas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$huerta = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $ubicacion = $_POST['ubicacion'];

    $stmt = $conn->prepare("UPDATE huertas SET nombre = ?, ubicacion = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nombre, $ubicacion, $id);

    if ($stmt->execute()) {
        echo "<p>Huerta actualizada exitosamente.</p>";
        header('Location: huertas_overview.php');
        exit;
    } else {
        echo "<p>Error al actualizar la huerta: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
include('header.php');
?>

<div class="main">
    <h1>Editar Huerta</h1>
    <form action="" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre de la Huerta:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $huerta['nombre']; ?>" required>
        </div>
        <div class="form-group">
            <label for="ubicacion">Ubicación:</label>
            <input type="text" id="ubicacion" name="ubicacion" value="<?php echo $huerta['ubicacion']; ?>" required>
        </div>
        <button type="submit" class="btn">Actualizar Huerta</button>
    </form>
</div>

<?php include('footer.php'); ?>
