<?php
session_start();
if ($_SESSION['user_role'] != 2) {  // Verificar que el rol sea Administrador (id_rol == 2)
    header("Location: ../../view/index.php");
    exit();
}

require_once '../../conexion.php';

// Obtener el ID de la mesa a eliminar
$id_mesa = $_GET['id'];

$query_delete = "DELETE FROM tbl_mesa WHERE id_mesa = :id_mesa";
$stmt_delete = $conn->prepare($query_delete);
$stmt_delete->bindParam(':id_mesa', $id_mesa);

if ($stmt_delete->execute()) {
    header('Location: ../../../view/admin.php'); 
    exit();
} else {
    echo "Hubo un error al eliminar la mesa.";
}
