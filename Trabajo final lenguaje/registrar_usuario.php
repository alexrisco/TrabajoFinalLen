<?php
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
        $username = $_POST["username"];
        $password = $_POST["password"];
        $nombre = $_POST["nombre"];
        $apellidos = $_POST["apellidos"];
        $correo = $_POST["correo"];
        $fecha_nacimiento = $_POST["fecha_nacimiento"];
        $genero = $_POST["genero"];

        // **¡IMPORTANTE! Hash de la contraseña antes de guardarla**
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Consulta SQL para insertar los datos del nuevo usuario
        // **NOMBRES DE COLUMNAS ACTUALIZADOS PARA COINCIDIR CON TU TABLA**
        $sql = "INSERT INTO usuario (usuario, contrasena, nombre, apellidos, correo, fecha_nacimiento, genero) VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Preparar la sentencia SQL
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Vincular los parámetros
            $stmt->bind_param("sssssss", $username, $hashed_password, $nombre, $apellidos, $correo, $fecha_nacimiento, $genero);

            // Ejecutar la sentencia
            if ($stmt->execute()) {
                // Registro exitoso, redirigir al usuario a la página de inicio de sesión o a una página de éxito
                header("location: login.html"); // Cambia "login.html" por la página deseada
                exit;
            } else {
                echo "Error al registrar el usuario: " . $stmt->error;
            }

            // Cerrar la sentencia
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $conn->error;
        }
    }
} catch(Exception $e) {
    die("Error de conexión: " . $e->getMessage());
} finally {
    // Cerrar la conexión
    if ($conn) {
        $conn->close();
    }
}
?>