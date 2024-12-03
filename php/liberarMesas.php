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

    try {
        // Iniciar una transacción
        $conn->beginTransaction();

        // Query para actualizar el estado de la mesa
        $update_query = "UPDATE tbl_ocupacion
                         SET estado_ocupacion = 'Registrada', fecha_final = CURRENT_TIMESTAMP, id_camarero = :id_camarero
                         WHERE id_mesa = :id_mesa AND estado_ocupacion = 'Ocupado'";
        $stmt_update = $conn->prepare($update_query);
        $stmt_update->bindParam(':id_camarero', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt_update->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
        $stmt_update->execute();

        // Query para insertar un nuevo estado de mesa
        $insert_query = "INSERT INTO tbl_ocupacion (id_mesa, estado_ocupacion) 
                         VALUES (:id_mesa, 'Disponible')";
        $stmt_insert = $conn->prepare($insert_query);
        $stmt_insert->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
        $stmt_insert->execute();

        // Confirmar la transacción
        $conn->commit();

        // Redirigir al final
        header("Location: ../view/mesas.php");
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollBack();
        echo "Error al editar: " . $e->getMessage();
    }
} else {
    echo "ID de mesa no proporcionado.";
}
?>
