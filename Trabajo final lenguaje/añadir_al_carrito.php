<?php
session_start(); // Necesitamos sesiones para mantener el carrito del usuario

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['referencia'])) {
    $referencia = $_POST['referencia'];
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;

    // **Aquí iría la lógica para:**
    // 1. (Opcional) Verificar si el usuario está logueado.
    // 2. (Opcional) Buscar la información de la camiseta en la base de datos usando la $referencia
    //    para tener más detalles (nombre, precio, etc.).
    // 3. Añadir la camiseta al carrito del usuario (almacenado en la sesión).

    // Inicializar el carrito si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }

    // Añadir la camiseta al carrito
    if (isset($_SESSION['carrito'][$referencia])) {
        $_SESSION['carrito'][$referencia] += $cantidad;
    } else {
        $_SESSION['carrito'][$referencia] = $cantidad;
    }

    // Responder con éxito
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'success', 'message' => 'Camiseta añadida al carrito', 'carrito' => $_SESSION['carrito']));

} else {
    // Si la petición no es POST o falta la referencia
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'error', 'message' => 'Petición inválida: falta la referencia de la camiseta'));
}
?>