<?php
session_start(); // Iniciar la sesión (necesario para guardar información del usuario)

// **¡REEMPLAZA ESTOS VALORES CON TUS CREDENCIALES REALES DE MYSQL!**
$servername = "localhost:3307";
$database = "coleccion_90";
$username = "root";
$password = "";

try {
    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $database);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Recoger los datos del formulario de inicio de sesión
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Preparar la consulta SQL para buscar al usuario por su nombre de usuario
    $sql = "SELECT id, contrasena FROM usuario WHERE usuario = ?";

    // Crear una sentencia preparada
    $stmt = $conn->prepare($sql);

    // Vincular el parámetro
    $stmt->bind_param("s", $usuario);

    // Ejecutar la sentencia
    $stmt->execute();

    // Obtener el resultado
    $stmt->bind_result($user_id, $hashed_password);

    // Verificar si se encontró un usuario
    if ($stmt->fetch()) {
        // Verificar la contraseña
        if (password_verify($contrasena, $hashed_password)) {
            // ¡Inicio de sesión exitoso!
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $usuario; // Puedes guardar más información si lo deseas

            echo "¡Inicio de sesión exitoso! Redirigiendo...";
            header("refresh:2;url=index.html"); // Redirige a la página principal
            exit();
        } else {
            // Contraseña incorrecta
            echo "Contraseña incorrecta. <a href='login.html'>Volver a intentar</a>";
        }
    } else {
        // No se encontró el usuario
        echo "Usuario no encontrado. <a href='login.html'>Volver a intentar</a> o <a href='registro.html'>Registrarse</a>";
    }

    // Cerrar la sentencia y la conexión
    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo "Ocurrió un error: " . $e->getMessage();
}

?>