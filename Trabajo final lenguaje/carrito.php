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

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Colección 90</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .carrito-item {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .carrito-item .info {
            flex-grow: 1;
        }
        .carrito-item .acciones {
            display: flex;
            gap: 10px;
        }
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
        <h1>Tu Carrito de Compras</h1>

        <?php
        if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
            echo '<ul id="lista-carrito">';
            $total_carrito = 0;
            foreach ($_SESSION['carrito'] as $referencia => $cantidad) {
                // Consultar la base de datos para obtener la información de la camiseta
                $sql = "SELECT equipo, precio FROM camisetas WHERE referencia = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $referencia);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $camiseta = $result->fetch_assoc();
                    $subtotal = $camiseta['precio'] * $cantidad;
                    $total_carrito += $subtotal;

                    echo '<li class="carrito-item">';
                    echo '<div class="info">';
                    echo '<p><strong>' . htmlspecialchars($camiseta['equipo']) . '</strong></p>';
                    echo '<p>Referencia: ' . htmlspecialchars($referencia) . '</p>';
                    echo '<p>Cantidad: ' . htmlspecialchars($cantidad) . '</p>';
                    echo '<p>Precio unitario: ' . htmlspecialchars($camiseta['precio']) . ' €</p>';
                    echo '<p>Subtotal: ' . htmlspecialchars(number_format($subtotal, 2)) . ' €</p>';
                    echo '</div>';
                    echo '<div class="acciones">';
                    echo '<button>Eliminar</button>'; // TODO: Implementar funcionalidad de eliminar
                    echo '</div>';
                    echo '</li>';
                }
                $stmt->close();
            }
            echo '</ul>';
            echo '<p><strong>Total del carrito: ' . htmlspecialchars(number_format($total_carrito, 2)) . ' €</strong></p>';
            echo '<button>Finalizar Compra</button>'; // TODO: Implementar funcionalidad de finalizar compra
        } else {
            echo '<p>Tu carrito está vacío.</p>';
        }

        // Cerrar la conexión a la base de datos
        if ($conn) {
            $conn->close();
        }
        ?>
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