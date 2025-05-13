<?php

// Incluye el archivo de conexión a la base de datos
require_once 'conexion_bd.php';

// Define la acción por defecto si no se proporciona
$accion = $_POST['accion'] ?? null;

if ($accion === 'login') {
    loginUsuario($_POST['usuario'] ?? null, $_POST['contrasena'] ?? null);
} elseif ($accion === 'registro') {
    registrarUsuario($_POST['usuario'] ?? null, $_POST['contrasena'] ?? null);
} else {
    // Redirigir si la acción no es válida
    header("Location: login.html");
    exit();
}

function loginUsuario($usuario, $contrasena)
{
    $mensajeError = null;

    try {
        $conn = ConexionBD::getConnection();
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = :usuario AND contrasena = :contrasena");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            // Iniciar sesión (usando sesiones de PHP)
            session_start();
            $_SESSION['usuario'] = $usuario;
            header("Location: index.html"); // Redirigir a la página principal
            exit();
        } else {
            $mensajeError = "Credenciales incorrectas. Por favor, inténtalo de nuevo.";
        }

    } catch (PDOException $e) {
        $mensajeError = "Error al conectar con la base de datos: " . $e->getMessage();
        error_log("Error en loginUsuario: " . $e->getMessage());
    } finally {
        // No es necesario cerrar la conexión PDO explícitamente aquí, se cierra al final del script o al destruir el objeto $conn
    }

    // Pasar el mensaje de error a la página de login (usando parámetros GET en la redirección)
    header("Location: login.html?error=" . urlencode($mensajeError));
    exit();
}

function registrarUsuario($usuario, $contrasena)
{
    $mensajeError = null;

    try {
        $conn = ConexionBD::getConnection();

        // Verificar si el usuario ya existe
        $stmtSelect = $conn->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
        $stmtSelect->bindParam(':usuario', $usuario);
        $stmtSelect->execute();
        $usuarioExistente = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        if ($usuarioExistente) {
            $mensajeError = "El nombre de usuario ya existe. Por favor, elige otro.";
        } else {
            // Insertar el nuevo usuario
            $stmtInsert = $conn->prepare("INSERT INTO usuarios (usuario, contrasena) VALUES (:usuario, :contrasena)");
            $stmtInsert->bindParam(':usuario', $usuario);
            $stmtInsert->bindParam(':contrasena', $contrasena);
            $filasAfectadas = $stmtInsert->execute();

            if ($filasAfectadas > 0) {
                // Redirigir con mensaje de éxito
                header("Location: login.html?registroExitoso=true");
                exit();
            } else {
                $mensajeError = "Error al registrar el usuario.";
            }
        }

    } catch (PDOException $e) {
        $mensajeError = "Error al conectar con la base de datos: " . $e->getMessage();
        error_log("Error en registrarUsuario: " . $e->getMessage());
    } finally {
        // No es necesario cerrar la conexión PDO explícitamente aquí
    }

    // Pasar el mensaje de error a la página de login
    header("Location: login.html?error=" . urlencode($mensajeError));
    exit();
}

?>