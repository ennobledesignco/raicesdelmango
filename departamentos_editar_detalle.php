<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');
$page_title = 'Editar Departamento';

if (!isset($_GET['id'])) {
    die('ID de departamento no especificado.');
}

$id = $_GET['id'];

// Fetch department details
$stmt = $conn->prepare("SELECT id, nombre FROM departamentos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$departamento = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];

    $stmt = $conn->prepare("UPDATE departamentos SET nombre = ? WHERE id = ?");
    $stmt->bind_param("si", $nombre, $id);

    if ($stmt->execute()) {
        echo "<p>Departamento actualizado exitosamente.</p>";
    } else {
        echo "<p>Error al actualizar departamento: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
include('header.php');
?>

<div class="main">
    <h1>Editar Departamento</h1>
    <form action="" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre del Departamento:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $departamento['nombre']; ?>" required>
        </div>
        <button type="submit" class="btn">Actualizar Departamento</button>
    </form>
</div>

<?php include('footer.php'); ?>
