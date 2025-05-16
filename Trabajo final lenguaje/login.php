<?php
session_start();

// Verificar si el usuario ya está logueado
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.html"); // Cambia "index.html" por tu página principal
    exit;
}

// Incluir el archivo de procesamiento del login
require_once "login_process.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colección 90 - Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.jpg" alt="Logo Colección 90" class="logo-imagen">
            <h2>COLECCION 90</h2>
        </div>
        <nav class="header-nav">
            <ul class="botones-navegacion">
                <li><a href="index.html">Inicio</a></li>
                <li><a href="camisetas.html">Camisetas</a></li>
                <li><a href="equipos.html">Equipos</a></li>
                <li><a href="carrito.php">Carrito</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="form-section">
            <h2>Iniciar Sesión</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="login-form">
                <div class="form-group">
                    <label for="username">Usuario:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-primary">Iniciar Sesión</button>
                <p class="link-below">¿No tienes cuenta? <a href="registrar_usuario.php">Regístrate aquí</a></p>
                <?php if (isset($login_err)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($login_err); ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Colección 90 - Camisetas de Fútbol Antiguas</p>
        <nav class="footer-nav">
            <ul>
                <li><a href="#">Términos y Condiciones</a></li>
                <li><a href="#">Política de Privacidad</a></li>
            </ul>
        </nav>
    </footer>
</body>
</html>