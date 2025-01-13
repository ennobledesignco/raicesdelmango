<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
include('php/config.php');

$page_title = 'Editar Producto';

// Ensure the product ID is provided
if (!isset($_GET['id'])) {
    header('Location: productos.php');
    exit;
}

$id = intval($_GET['id']);

// Fetch the product details
$stmt = $conn->prepare("SELECT id, nombre, descripcion, categoria_id FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<p>Producto no encontrado.</p>";
    exit;
}

$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'] ?? null;
    $categoria_id = $_POST['categoria_id'] ?: null;

    $stmt = $conn->prepare("
        UPDATE productos 
        SET nombre = ?, descripcion = ?, categoria_id = ?
        WHERE id = ?
    ");
    $stmt->bind_param("ssii", $nombre, $descripcion, $categoria_id, $id);

    if ($stmt->execute()) {
        // Redirect to the product list with a success message
        header('Location: productos.php?updated=1');
        exit;
    } else {
        echo "<p>Error al actualizar el producto: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

include('header.php');
?>

<div class="main">
    <h1>Editar Producto</h1>
    <form action="productos_editar.php?id=<?php echo $id; ?>" method="POST">
        <!-- Nombre -->
        <div class="form-group">
            <label for="nombre">Nombre del Producto:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($product['nombre']); ?>" required>
        </div>

        <!-- Descripción -->
        <div class="form-group">
            <label for="descripcion">Descripción del Producto (Opcional):</label>
            <textarea id="descripcion" name="descripcion"><?php echo htmlspecialchars($product['descripcion']); ?></textarea>
        </div>

        <!-- Categoría -->
        <div class="form-group">
            <label for="categoria_id">Categoría:</label>
            <select id="categoria_id" name="categoria_id">
                <option value="">Seleccione una categoría</option>
                <?php
                // Fetch categories
                $categorias = $conn->query("SELECT id, nombre FROM categorias_productos");
                while ($row = $categorias->fetch_assoc()) {
                    $selected = $row['id'] == $product['categoria_id'] ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>{$row['nombre']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn">Actualizar Producto</button>
    </form>
</div>

<?php include('footer.php'); ?>

