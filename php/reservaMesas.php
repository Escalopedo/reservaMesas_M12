<?php

// Iniciamos la sesión
session_start();

// Verificamos si la sesión del camarero está activa
if (empty($_SESSION['user_id'])) {
    // Si no está activo, redirigimos a la página de inicio de sesión
    header("Location: ./index.php");
    exit();
}

require '../php/conexion.php';

if (isset($_GET['id'])) {
    $id_mesa = htmlspecialchars($_GET['id']);
    $user_id = $_SESSION['user_id']; // Usamos el ID de usuario de la sesión

    try {
        // Preparar la consulta para actualizar el estado de la mesa
        $update_query_ocu = "
            UPDATE tbl_ocupacion
            SET estado_ocupacion = 'Ocupado', fecha_inicio = CURRENT_TIMESTAMP, id_usuario = :id_usuario
            WHERE id_mesa = :id_mesa AND estado_ocupacion = 'Disponible';
        ";

        $stmt = $conn->prepare($update_query_ocu);

        // Enlazar los parámetros
        $stmt->bindParam(':id_usuario', $user_id, PDO::PARAM_INT); // Usamos el id_usuario
        $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Redirigir al final
        header("Location: ../view/mesas.php");
        exit();
    } catch (PDOException $e) {
        echo "Error al reservar la mesa: " . $e->getMessage();
    }
} else {
    echo "ID de mesa no proporcionado.";
}
?>
