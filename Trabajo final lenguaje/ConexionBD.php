<?php

// **¡IMPORTANTE! Reemplaza estos valores con tus credenciales reales de MySQL**
$servername = "localhost"; // O la dirección de tu servidor MySQL (puede ser 127.0.0.1)
$database = "coleccion_90"; // El nombre de tu base de datos
$username = "root"; // Tu nombre de usuario de MySQL
$password = ""; // Tu contraseña de MySQL

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    echo "Conexión exitosa a la base de datos coleccion_90<br>";
}

// **A partir de aquí puedes realizar tus consultas a la base de datos**

// Ejemplo: Obtener todas las camisetas de la tabla 'camisetas'
$sql = "SELECT * FROM camisetas";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>Listado de camisetas:</h3>";
    echo "<ul>";
    while($row = $result->fetch_assoc()) {
        echo "<li>Equipo: " . $row["equipo"] . ", Temporada: " . $row["temporada"] . ", Marca: " . $row["marca"] . ", Talla: " . $row["talla"] . ", Precio: " . $row["precio"] . " €</li>";
    }
    echo "</ul>";
} else {
    echo "No se encontraron camisetas en la base de datos.<br>";
}

// **¡Cierra la conexión cuando hayas terminado de interactuar con la base de datos!**
$conn->close();

?>