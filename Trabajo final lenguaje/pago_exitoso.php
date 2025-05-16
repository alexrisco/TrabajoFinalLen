<?php
session_start();

// Verificar si se recibió el ID del cliente
if (isset($_GET['cliente_id']) && is_numeric($_GET['cliente_id'])) {
    $cliente_id = $_GET['cliente_id'];
} else {
    // Redirigir si no hay ID de cliente
    header("Location: carrito.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Exitoso - Colección 90</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Y+5n76dVWjxYh0utjz4dSgKK+itHv3JnCVLrV0YbCLH1hHw7VgZuEIwhImpQDax" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA5usXiu1uMjKlPj5GV9KiNiEaWPfy" crossorigin="anonymous"></script>
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
        <h1>¡Pago Exitoso!</h1>
        <p>Gracias por tu compra. Tu pedido ha sido procesado correctamente.</p>
        <p>Tu número de cliente es: <strong><?php echo htmlspecialchars($cliente_id); ?></strong></p>
        <p>Recibirás un correo electrónico con los detalles de tu pedido en breve.</p>
        <p><a href="index.html">Volver a la página principal</a></p>
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