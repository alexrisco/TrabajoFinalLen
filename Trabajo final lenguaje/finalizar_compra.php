<?php
session_start();

// **¡REEMPLAZA ESTOS VALORES CON TUS CREDENCIALES REALES DE MYSQL!**
$servername = "localhost";
$database = "coleccion_90";
$username = "root";
$password = "";
$port = 3307;

$conn = null;

try {
    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $database, $port);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

} catch(Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Procesar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $apellidos = isset($_POST['apellidos']) ? trim($_POST['apellidos']) : '';
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';

    $errores = array();

    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio.";
    }
    if (empty($apellidos)) {
        $errores[] = "Los apellidos son obligatorios.";
    }
    if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido.";
    }

    if (empty($errores)) {
        // Insertar la información del cliente en la base de datos (MODIFICADO PARA COINCIDIR CON TU TABLA)
        $sql = "INSERT INTO clientes (nombre, apellidos, correo) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nombre, $apellidos, $correo);

        if ($stmt->execute()) {
            $cliente_id = $stmt->insert_id; // Obtener el ID del cliente insertado

            // **Aquí iría la lógica para procesar el pago con una pasarela de pago real.**
            // Por ahora, simplemente mostraremos un mensaje de éxito simulado.

            // Limpiar el carrito después de la compra simulada
            unset($_SESSION['carrito']);

            // Redirigir a una página de éxito
            header("Location: pago_exitoso.php?cliente_id=" . $cliente_id);
            exit();
        } else {
            $error_db = "Error al guardar la información del cliente: " . $stmt->error;
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - Colección 90</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Y+5n76dVWjxYh0utjz4dSgKK+itHv3JnCVLrV0YbCLH1hHw7VgZuEIwhImpQDax" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA5usXiu1uMjKlPj5GV9KiNiEaWPfy" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* ... tus estilos CSS ... */
    </style>
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
                <li><a href="contacto.html">Contacto</a></li>
                <li><a href="carrito.php">Carrito</a></li>
                <li><a href="login.html">Login</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>Finalizar Compra</h1>

        <?php if (!empty($errores)): ?>
            <div class="error-message">
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (isset($error_db)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_db); ?></div>
        <?php endif; ?>

        <div class="formulario-pago">
            <h2>Información del Cliente</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">

                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($apellidos); ?>">

                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($correo); ?>">

                <h2>Información de Pago (Simulada)</h2>
                <p>Por favor, introduce los datos de tu tarjeta de crédito:</p>
                <label for="tarjeta_numero">Número de Tarjeta:</label>
                <input type="text" id="tarjeta_numero" name="tarjeta_numero" placeholder="XXXX-XXXX-XXXX-XXXX">

                <label for="tarjeta_expiracion">Fecha de Expiración:</label>
                <input type="text" id="tarjeta_expiracion" name="tarjeta_expiracion" placeholder="MM/AA">

                <label for="tarjeta_cvv">CVV:</label>
                <input type="text" id="tarjeta_cvv" name="tarjeta_cvv" placeholder="XXX">

                <button type="submit">Realizar Pedido</button>
            </form>
        </div>
    </div>

<footer>
    <p>&copy; 2025 Colección 90 - Camisetas de Fútbol Antiguas</p>
    <nav class="footer-nav">
        <ul>
            <li><a href="#">Términos y Condiciones</a></li>
            <li><a href="#">Política de Privacidad</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
    </nav>
</footer>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
if ($conn) {
    $conn->close();
}
?>