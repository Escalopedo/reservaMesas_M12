<?php
session_start();
if ($_SESSION['user_role'] != 2) {  // Verificar que el rol sea Administrador (id_rol == 2)
    header("Location: ../view/index.php");  // Redirigir a mesas.php si no es Administrador
    exit();
}
?>
