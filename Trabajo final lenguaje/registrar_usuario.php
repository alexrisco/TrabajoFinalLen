<?php
// **¡REEMPLAZA ESTOS VALORES CON TUS CREDENCIALES REALES DE MYSQL!**
$servername = "localhost";
$database = "coleccion_90";
$username_db = "root"; // Reemplaza con tu usuario de la base de datos
$password_db = "";
$port = 3306;

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
        // Obtener los datos del formulario y limpiar
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $nombre = trim($_POST["nombre"]);
        $apellidos = trim($_POST["apellidos"]);
        $correo = trim($_POST["correo"]);
        $fecha_nacimiento = trim($_POST["fecha_nacimiento"]);
        $genero = trim($_POST["genero"]);

        $errores = array();

        if (empty($username)) {
            $errores[] = "El nombre de usuario es obligatorio.";
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errores[] = "El nombre de usuario solo puede contener letras, números y guiones bajos.";
        } elseif (strlen($username) > 50) {
            $errores[] = "El nombre de usuario no puede tener más de 50 caracteres.";
        }
        if (empty($password)) {
            $errores[] = "La contraseña es obligatoria.";
        } elseif (strlen($password) < 6) {
            $errores[] = "La contraseña debe tener al menos 6 caracteres.";
        }
        if (empty($nombre)) {
            $errores[] = "El nombre es obligatorio.";
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $nombre)) {
            $errores[] = "El nombre solo puede contener letras y espacios.";
        } elseif (strlen($nombre) > 100) {
            $errores[] = "El nombre no puede tener más de 100 caracteres.";
        }
        if (empty($apellidos)) {
            $errores[] = "Los apellidos son obligatorios.";
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $apellidos)) {
            $errores[] = "Los apellidos solo pueden contener letras y espacios.";
        } elseif (strlen($apellidos) > 150) {
            $errores[] = "Los apellidos no pueden tener más de 150 caracteres.";
        }
        if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El correo electrónico no es válido.";
        } elseif (strlen($correo) > 255) {
            $errores[] = "El correo electrónico no puede tener más de 255 caracteres.";
        }
        if (empty($fecha_nacimiento)) {
            $errores[] = "La fecha de nacimiento es obligatoria.";
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_nacimiento)) {
            $errores[] = "El formato de la fecha de nacimiento no es válido (YYYY-MM-DD).";
        }
        if (empty($genero)) {
            $errores[] = "El género es obligatorio.";
        } elseif (!in_array($genero, ['masculino', 'femenino', 'otro'])) {
            $errores[] = "El género seleccionado no es válido.";
        } elseif (strlen($genero) > 10) {
            $errores[] = "El género seleccionado no es válido.";
        }

        if (empty($errores)) {
            // **Verificar si el nombre de usuario ya existe**
            $sql_check = "SELECT id FROM usuario WHERE username = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("s", $username);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $error_registro = "El nombre de usuario ya existe. Por favor, elige otro.";
            } else {
                // El nombre de usuario no existe, proceder con el registro
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql_insert = "INSERT INTO usuario (username, contrasena, nombre, apellidos, correo, fecha_nacimiento, genero) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("sssssss", $username, $hashed_password, $nombre, $apellidos, $correo, $fecha_nacimiento, $genero);

                if ($stmt_insert->execute()) {
                    header("location: login.php?registro_exitoso=1"); // Redirigir al login con mensaje
                    exit;
                } else {
                    $error_registro_db = "Error al registrar el usuario: " . $stmt_insert->error;
                }
                $stmt_insert->close();
            }
            $stmt_check->close();
        }
    }
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
} finally {
    // Cerrar la conexión
    if ($conn) {
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colección 90 - Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="form-section" id="registro-seccion">
            <h2>¿Nuevo por aquí? Regístrate</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="register-form" name="registroForm">
                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errores as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if (isset($error_registro)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_registro); ?></div>
                <?php endif; ?>
                <?php if (isset($error_registro_db)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_registro_db); ?></div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="nuevoUsuario">Usuario:</label>
                    <input type="text" id="nuevoUsuario" name="username" required>
                </div>
                <div class="form-group">
                    <label for="nuevaContrasena">Contraseña:</label>
                    <input type="password" id="nuevaContrasena" name="password" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" required>
                </div>
                <div class="form-group">
                    <label for="correo">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" required>
                </div>
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
                </div>
                <div class="form-group">
                    <label for="genero">Género:</label>
                    <select id="genero" name="genero" required>
                        <option value="">Seleccionar género</option>
                        <option value="masculino">Masculino</option>
                        <option value="femenino">Femenino</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Registrarse</button>
                <p class="link-below">¿Ya tienes una cuenta? <a href="login.php">Iniciar Sesión aquí</a></p>
            </form>
        </div>
    </div>
</body>
</html>

<?php
// Cerrar la conexión
if ($conn) {
    $conn->close();
}
?>