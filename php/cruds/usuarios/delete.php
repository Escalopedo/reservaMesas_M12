<?php
session_start();
if ($_SESSION['user_role'] != 2) {
    header("Location: ../../view/index.php");
    exit();
}

require_once '../../conexion.php';

if (isset($_GET['id'])) {
    $id_usuario = (int)$_GET['id'];

    try {
        $query = "DELETE FROM tbl_usuarios WHERE id_usuario = :id_usuario";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();

        header('Location: ../../../view/admin.php'); 
    } catch (PDOException $e) {
        echo "Error al eliminar usuario: " . $e->getMessage();
    }
}
?>
