<?php

// **¡REEMPLAZA ESTOS VALORES CON TUS CREDENCIALES REALES DE MYSQL!**
$servername = "localhost"; // O la dirección de tu servidor MySQL
$database = "coleccion_90"; // El nombre de tu base de datos
$username = "root"; // Tu nombre de usuario de MySQL
$password = ""; // Tu contraseña de MySQL
$port = 3306; // ¡Asegúrate de usar el puerto correcto!

$conn = null; // Inicializar la variable de conexión

try {
    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $database, $port);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL para obtener todas las camisetas
    // **CAMBIO IMPORTANTE: Usamos el nombre correcto de la columna 'imagen_url'**
    $sql = "SELECT referencia, equipo, temporada, marca, talla, precio, descripcion, imagen_url FROM camisetas";
    $result = $conn->query($sql);

    $camisetas = array(); // Array para almacenar los datos de las camisetas

    if ($result->num_rows > 0) {
        // Recorrer los resultados y almacenarlos en el array
        while($row = $result->fetch_assoc()) {
            $camisetas[] = $row;
        }
    }

    // **Codificar el array de camisetas a formato JSON para usarlo en JavaScript**
    header('Content-Type: application/json'); // Establecer el tipo de contenido como JSON
    echo json_encode($camisetas);

} catch(Exception $e) {
    // En caso de error, devolver un JSON con el mensaje de error
    header('Content-Type: application/json');
    echo json_encode(array('error' => $e->getMessage()));
} finally {
    // Cerrar la conexión
    if ($conn) {
        $conn->close();
    }
}
?>