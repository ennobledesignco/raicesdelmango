<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role_id'] !== 1) {
    header('Location: login.php');
    exit;
}
include('php/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['id'];

    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        header('Location: user_list.php?message=Usuario eliminado exitosamente');
    } else {
        echo "Error al eliminar el usuario.";
    }
}
?>
