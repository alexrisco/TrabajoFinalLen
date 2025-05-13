<?php
session_start(); // Iniciar la sesión

// **¡REEMPLAZA ESTOS VALORES CON TUS CREDENCIALES REALES DE MYSQL!**
$servername = "localhost";
$database = "coleccion_90";
$username_db = "root"; // Reemplaza con tu usuario de la base de datos
$password_db = "";     // Reemplaza con tu contraseña de la base de datos
$port = 3307;

$conn = null;

try {
    // Crear conexión
    $conn = new mysqli($servername, $username_db, $password_db, $database, $port);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verificar si se han enviado datos del formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $username_input = $_POST["username"];
        $password_input = $_POST["password"];

        // Consulta SQL para buscar al usuario por nombre de usuario
        // **NOMBRES DE COLUMNAS ACTUALIZADOS PARA COINCIDIR CON TU TABLA**
        $sql = "SELECT id, usuario, contrasena FROM usuario WHERE usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username_input);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Verificar la contraseña (¡IMPORTANTE: Debes usar password_verify ya que hasheado las contraseñas!)
            if (password_verify($password_input, $row["contrasena"])) {
                // La contraseña es correcta, iniciar sesión
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $row["id"];
                $_SESSION["username"] = $row["usuario"]; // Usamos 'usuario' aquí también

                // Redirigir al usuario a la página principal o a una página de bienvenida
                header("location: index.html"); // Cambia "index.html" por la página deseada
            } else {
                // Contraseña incorrecta, mostrar un mensaje de error
                $login_err = "Nombre de usuario o contraseña incorrectos.";
            }
        } else {
            // No se encontró el nombre de usuario, mostrar un mensaje de error
            $login_err = "Nombre de usuario o contraseña incorrectos.";
        }

        $stmt->close();
    }
} catch(Exception $e) {
    die("Error: " . $e->getMessage());
} finally {
    // Cerrar la conexión
    if ($conn) {
        $conn->close();
    }
}
?>