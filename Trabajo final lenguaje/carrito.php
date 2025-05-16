<?php
session_start();

// **¡REEMPLAZA ESTOS VALORES CON TUS CREDENCIALES REALES DE MYSQL!**
$servername = "localhost";
$database = "coleccion_90";
$username = "root";
$password = "";
$port = 3306;

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

// Procesar la eliminación del carrito si se recibe la solicitud
if (isset($_GET['eliminar'])) {
    $referencia_a_eliminar = $_GET['eliminar'];
    if (isset($_SESSION['carrito'][$referencia_a_eliminar])) {
        unset($_SESSION['carrito'][$referencia_a_eliminar]);
        header("Location: carrito.php"); // Redirigir para actualizar la vista
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Colección 90</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Y+5n76dVWjxYh0utjz4dSgKK+itHv3JnCVLrV0YbCLH1hHw7VgZuEIwhImpQDax" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA5usXiu1uMjKlPj5GV9KiNiEaWPfy" crossorigin="anonymous"></script>
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
        .resumen-carrito {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            text-align: right;
        }
        .resumen-fila {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .resumen-fila.total {
            font-weight: bold;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
        }
        .boton-comprar {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            text-decoration: none; /* Para que el enlace dentro del botón no tenga subrayado */
        }
        .boton-comprar:hover {
            background-color: #0056b3;
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
                    echo '<a href="?eliminar=' . htmlspecialchars($referencia) . '">Eliminar</a>';
                    echo '</div>';
                    echo '</li>';
                }
                $stmt->close();
            }
            echo '</ul>';

            echo '<div class="resumen-carrito">';
            echo '<div class="resumen-fila">';
            echo '<p>Subtotal:</p>';
            echo '<span>' . htmlspecialchars(number_format($total_carrito, 2)) . ' €</span>';
            echo '</div>';
            echo '<div class="resumen-fila">';
            echo '<p>Envío:</p>';
            echo '<span>Calculado al finalizar</span>';
            echo '</div>';
            echo '<div class="resumen-fila total">';
            echo '<p>Total:</p>';
            echo '<span>' . htmlspecialchars(number_format($total_carrito, 2)) . ' €</span>';
            echo '</div>';
            echo '<a href="finalizar_compra.php" class="boton-comprar">Finalizar Compra</a>';
            echo '</div>';

        } else {
            echo '<p>Tu carrito está vacío.</p>';
        }

        if ($conn) {
            $conn->close();
        }
        ?>
    </div>

<footer class = "footer-carrito ">
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