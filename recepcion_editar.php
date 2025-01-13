<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include('php/config.php');
$page_title = 'Editar Recepci車n';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Validate the `id` parameter
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: recepcion_resumen.php?error=missing_id");
    exit;
}

$id = intval($_GET['id']);

// Fetch reception details
$query = "SELECT * FROM recepciones WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Recepci車n no encontrada para el ID proporcionado: $id");
}

$recepcion = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $huerta_id = $_POST['huerta_id'];
    $centro_abastecimiento_id = $_POST['centro_abastecimiento_id'];
    $productor_id = $_POST['productor_id'];
    $producto_id = $_POST['producto_id'];
    $variedad_id = $_POST['variedad_id'];
    $calidad_id = $_POST['calidad_id'];
    $operador_id = $_POST['operador_id'];
    $fecha_corte = $_POST['fecha_corte'];
    $fecha_recepcion = $_POST['fecha_recepcion'];
    $cantidad_cajas = $_POST['cantidad_cajas'];
    $numero_cajas_vacias = $_POST['numero_cajas_vacias'];
    $peso_caja = $_POST['peso_caja'];
    $tipo_cajas = $_POST['tipo_cajas'];
    $placas_vehiculo = $_POST['placas_vehiculo'];
    $placas_remolque = $_POST['placas_remolque'];
    $cuadrillas = $_POST['cuadrillas'];

    $update_query = "
        UPDATE recepciones
        SET huerta_id = ?, centro_abastecimiento_id = ?, productor_id = ?, producto_id = ?,
            variedad_id = ?, calidad_id = ?, operador_id = ?, fecha_corte = ?, fecha_recepcion = ?,
            cantidad_cajas = ?, numero_cajas_vacias = ?, peso_caja = ?, tipo_cajas = ?,
            placas_vehiculo = ?, placas_remolque = ?, cuadrillas = ?
        WHERE id = ?
    ";

    $stmt = $conn->prepare($update_query);
    $stmt->bind_param(
        "iiiiiiissiiissssi",
        $huerta_id, $centro_abastecimiento_id, $productor_id, $producto_id,
        $variedad_id, $calidad_id, $operador_id, $fecha_corte, $fecha_recepcion,
        $cantidad_cajas, $numero_cajas_vacias, $peso_caja, $tipo_cajas,
        $placas_vehiculo, $placas_remolque, $cuadrillas, $id
    );

    if ($stmt->execute()) {
        header("Location: recepcion_resumen.php?updated=1");
        exit;
    } else {
        echo "<p>Error al actualizar la recepci車n: " . $stmt->error . "</p>";
    }
}

include('header.php');
?>

<div class="main">
    <h1>Editar Recepci車n</h1>
    <form action="recepcion_editar.php?id=<?php echo $id; ?>" method="POST">
        <div class="form-group">
            <label for="huerta_id">Huerta:</label>
            <input type="text" id="huerta_id" name="huerta_id" value="<?php echo htmlspecialchars($recepcion['huerta_id']); ?>" required>
        </div>
        <!-- Repeat similar fields for other inputs -->
        <button type="submit" class="btn">Guardar Cambios</button>
    </form>
</div>

<?php include('footer.php'); ?>
