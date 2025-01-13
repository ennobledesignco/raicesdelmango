<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php'); // Redirect to dashboard if logged in
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesion - Raices del Mango</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f9f4;
        }

        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        .login-container h1 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: #2d6a4f;
        }

        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }

        .form-group label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #1b4332;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 0.8rem;
            background-color: #2d6a4f;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 1rem;
        }

        .btn:hover {
            background-color: #40916c;
        }

        .error-message {
            color: red;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            display: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>CRM Mango</h1>
        <form id="login-form">
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contrasena</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="btn">Iniciar Sesion</button>
            <p class="error-message" id="error-message"></p>
        </form>
    </div>
<script>
    document.getElementById('login-form').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent the form from submitting the traditional way
        const formData = new FormData(this);

        fetch('php/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const errorMessage = document.getElementById('error-message');
                errorMessage.style.display = 'none'; // Hide error message on success
                setTimeout(() => {
                    window.location.href = 'index.php'; // Redirect after a short delay
                }, 1000); // Optional delay for smoother user experience
            } else {
                const errorMessage = document.getElementById('error-message');
                errorMessage.textContent = data.message;
                errorMessage.style.display = 'block'; // Show error message
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorMessage = document.getElementById('error-message');
            errorMessage.textContent = 'Error en el servidor. Inténtalo de nuevo más tarde.';
            errorMessage.style.display = 'block';
        });
    });
</script>

</body>
</html>
