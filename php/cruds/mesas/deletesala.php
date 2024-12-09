<?php
session_start();
if ($_SESSION['user_role'] != 2) {  // Verificar que el rol sea Administrador (id_rol == 2)
    header("Location: ../../view/index.php");
    exit();
}

require_once '../../conexion.php';

// Obtener el ID de la sala a eliminar
$id_sala = $_GET['id'];

$query_delete = "DELETE FROM tbl_sala WHERE id_sala = :id_sala";
$stmt_delete = $conn->prepare($query_delete);
$stmt_delete->bindParam(':id_sala', $id_sala);

if ($stmt_delete->execute()) {
    header('Location: ../../../view/admin.php'); 
    exit();
} else {
    echo "Hubo un error al eliminar la sala.";
}
