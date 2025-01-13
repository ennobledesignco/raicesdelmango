<?php
// Detalles de la conexión a la base de datos
$servidor = "127.0.0.1:3306"; // Host para HostGator suele ser localhost
$usuario = "u314481296_mangos"; // Cambia por tu usuario de base de datos
$contrasena = "FMj8wh(ggpcL"; // Cambia por la contraseña que asignaste
$base_datos = "u314481296_mangos"; // El nombre exacto de tu base de datos

// Crear la conexión
$conn = new mysqli($servidor, $usuario, $contrasena, $base_datos);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Opcional: Configuración de conjunto de caracteres (UTF-8 recomendado)
$conn->set_charset("utf8");

// No se necesita otro `mysqli` aquí.
?>
