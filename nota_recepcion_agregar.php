<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include('php/config.php');

// Fetch Recepci車n ID
if (!isset($_GET['recepcion_id']) || empty($_GET['recepcion_id'])) {
    die("ID de recepci車n no especificado.");
}
$recepcion_id = intval($_GET['recepcion_id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $calidad_id = $_POST['calidad_id'];
    $cajas = $_POST['cajas'];
    $kg = $_POST['kg'];
    $precio = $_POST['precio'];
    $importe = $kg * $precio;
    $porcentaje = $_POST['porcentaje'];

    $query = "INSERT INTO notas_recepcion (recepcion_id, calidad_id, cajas, kg, precio, importe, porcentaje)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiddid", $recepcion_id, $calidad_id, $cajas, $kg, $precio, $importe, $porcentaje);

    if ($stmt->execute()) {
        header("Location: recepcion_resumen.php?recepcion_id=$recepcion_id&message=Nota agregada exitosamente");
        exit;
    } else {
        $error = "Error al agregar la nota de recepci車n: " . $stmt->error;
    }
}

// Fetch existing Calidades
$calidades = $conn->query("SELECT id, nombre FROM calidades");

include('header.php');
?>

<div class="main">
    <h1>Agregar Nota de Recepci車n</h1>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="nota_recepcion_agregar.php?recepcion_id=<?php echo $recepcion_id; ?>" method="POST">
        <!-- Calidad Dropdown -->
        <div class="form-group">
            <label for="calidad_id">Calidad:</label>
            <select id="calidad_id" name="calidad_id" required>
                <option value="">Seleccione una calidad</option>
                <?php while ($calidad = $calidades->fetch_assoc()): ?>
                    <option value="<?php echo $calidad['id']; ?>">
                        <?php echo htmlspecialchars($calidad['nombre']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="cajas">Cajas:</label>
            <input type="number" id="cajas" name="cajas" required>
        </div>
        <div class="form-group">
            <label for="kg">Kilogramos:</label>
            <input type="number" step="0.01" id="kg" name="kg" required>
        </div>
        <div class="form-group">
            <label for="precio">Precio por KG:</label>
            <input type="number" step="0.01" id="precio" name="precio" required>
        </div>
        <div class="form-group">
            <label for="porcentaje">Porcentaje:</label>
            <input type="number" step="0.01" id="porcentaje" name="porcentaje" required>
        </div>
        <button type="submit" class="btn">Agregar Nota</button>
    </form>
</div>

<?php include('footer.php'); ?>
