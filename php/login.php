<?php
session_start();
include('config.php');

// Ensure Content-Type is JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate POST data
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        error_log("POST data missing.");
        echo json_encode(['success' => false, 'message' => 'Datos de entrada no válidos.']);
        exit;
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Debug username
    error_log("Attempting login for user: $username");

    // Prepared statement
    $stmt = $conn->prepare("SELECT id, username, password, role_id FROM users WHERE username = ?");
    if (!$stmt) {
        error_log("Statement Preparation Failed: " . $conn->error);
        echo json_encode(['success' => false, 'message' => 'Error en el servidor.']);
        exit;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Password verification (plain text for testing)
        if ($password === $user['password']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_id'] = $user['role_id'];

            echo json_encode(['success' => true]);
            exit;
        } else {
            error_log("Password mismatch for user: $username");
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta.']);
            exit;
        }
    } else {
        error_log("User not found: $username");
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
?>
