<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario tiene sesión activa
if (empty($_SESSION['user_id'])) {
    header("Location: ./index.php");
    exit();
}

require '../php/conexion.php';

if (isset($_GET['id'])) {
    $id_mesa = htmlspecialchars($_GET['id']);

    try {
        // Iniciar una transacción
        $conn->beginTransaction();

        // Actualizar el estado de la mesa
        $update_query = "
            UPDATE tbl_ocupacion
            SET estado_ocupacion = 'Registrada', 
                fecha_final = CURRENT_TIMESTAMP, 
                id_usuario = :id_usuario
            WHERE id_mesa = :id_mesa AND estado_ocupacion = 'Ocupado'";
        $stmt_update = $conn->prepare($update_query);
        $stmt_update->bindParam(':id_usuario', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt_update->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
        $stmt_update->execute();

        // Insertar un nuevo registro indicando que la mesa está disponible
        $insert_query = "
            INSERT INTO tbl_ocupacion (id_mesa, estado_ocupacion, fecha_inicio) 
            VALUES (:id_mesa, 'Disponible', CURRENT_TIMESTAMP)";
        $stmt_insert = $conn->prepare($insert_query);
        $stmt_insert->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
        $stmt_insert->execute();

        // Confirmar la transacción
        $conn->commit();

        header("Location: ../view/mesas.php");
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollBack();
        echo "Error al actualizar la mesa: " . $e->getMessage();
    }
} else {
    echo "ID de mesa no proporcionado.";
}
?>
