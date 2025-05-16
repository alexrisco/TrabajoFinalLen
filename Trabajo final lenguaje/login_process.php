<?php
// **¡REEMPLAZA ESTOS VALORES CON TUS CREDENCIALES REALES DE MYSQL!**
$servername = "localhost";
$database = "coleccion_90";
$username_db = "root"; // Reemplaza con tu usuario de la base de datos
$password_db = "";
$port = 3306;

$conn = null;
$login_err = ""; // Inicializar la variable de error

try {
    // Crear conexión
    $conn = new mysqli($servername, $username_db, $password_db, $database, $port);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verificar si se han enviado datos del formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario y limpiar
        $username_input = trim($_POST["username"]);
        $password_input = trim($_POST["password"]);

        // Validar campos vacíos
        if (empty($username_input)) {
            $login_err = "Por favor, introduce tu nombre de usuario.";
        } elseif (empty($password_input)) {
            $login_err = "Por favor, introduce tu contraseña.";
        } else {
            // Consulta SQL para buscar al usuario por nombre de usuario
            // **USANDO 'username' YA QUE ES EL NOMBRE DE TU COLUMNA**
            $sql = "SELECT id, username, contrasena FROM usuario WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username_input);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                // Verificar la contraseña
                if (password_verify($password_input, $row["contrasena"])) {
                    // La contraseña es correcta, iniciar sesión
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $row["id"];
                    $_SESSION["username"] = $row["username"];

                    // Redirigir al usuario a la página principal
                    header("location: index.html"); // Cambia "index.html" por tu página principal
                    exit;
                } else {
                    // Contraseña incorrecta
                    $login_err = "Nombre de usuario o contraseña incorrectos.";
                }
            } else {
                // No se encontró el nombre de usuario
                $login_err = "Nombre de usuario o contraseña incorrectos.";
            }
            $stmt->close();
        }
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
} finally {
    // Cerrar la conexión
    if ($conn) {
        $conn->close();
    }
}
?>